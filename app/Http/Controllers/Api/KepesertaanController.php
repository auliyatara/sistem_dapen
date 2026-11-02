<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Kepesertaan;
use App\Models\KepesertaanLink;
use App\Models\KepesertaanDokumen;
use App\Models\KepesertaanIuran;
use App\Models\KepesertaanBefore;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class KepesertaanController extends Controller
{
    private function authorizeEdit(): void
    {
        abort_unless(auth()->check(), 403);
        \Illuminate\Support\Facades\Gate::authorize('manage-kepesertaan');
    }

    private function deleteStoredFiles(array $paths): void
    {
        foreach (array_unique($paths) as $path) {
            try {
                Storage::disk('public')->delete($path);
                Storage::disk('local')->delete($path);
                $mirror = public_path('storage/'.$path);
                $root = realpath(public_path('storage'));
                $resolved = realpath($mirror);
                if ($root && $resolved && str_starts_with($resolved, $root.DIRECTORY_SEPARATOR) && is_file($resolved)) unlink($resolved);
            } catch (\Throwable $exception) { report($exception); }
        }
    }

    private function validateIdentity(Request $request, ?string $id = null): void
    {
        foreach (['kepesertaan_nik', 'kepesertaan_nip'] as $field) {
            if (is_string($request->input($field))) $request->merge([$field => trim($request->input($field))]);
        }
        $nikValue = $request->input('kepesertaan_nik');
        if ($request->input('kepesertaan_nip') === '') $request->merge(['kepesertaan_nip' => null]);
        if ($nikValue === '' || (is_string($nikValue) && preg_match('/^0+$/', $nikValue))) $request->merge(['kepesertaan_nik' => null]);
        $nik = \Illuminate\Validation\Rule::unique('kepesertaans', 'kepesertaan_nik');
        $nip = \Illuminate\Validation\Rule::unique('kepesertaans', 'kepesertaan_nip');
        if ($id !== null) { $nik->ignore($id, 'kepesertaan_id'); $nip->ignore($id, 'kepesertaan_id'); }
        $request->validate([
            'kepesertaan_nik' => ['nullable', 'string', 'regex:/^[0-9]{16}$/', $nik],
            'kepesertaan_nip' => ['nullable', 'string', 'max:50', 'regex:/^[0-9]+$/', $nip],
        ], [
            'kepesertaan_nik.regex' => 'NIK harus terdiri dari 16 digit angka.',
            'kepesertaan_nik.unique' => 'NIK sudah digunakan peserta lain.',
            'kepesertaan_nip.unique' => 'NIP sudah digunakan peserta lain.',
            'kepesertaan_nip.regex' => 'NIP hanya boleh berisi angka.',
        ]);
        if ($request->input('kepesertaan_nip') === null) $request->merge(['kepesertaan_nip' => '']);
    }

    public function index(){
        $operation = Kepesertaan::withCompleteness()->get();
        return $this->response($operation);
    }

    public function count(){
        $operation = DB::select("SELECT
            count(*) AS total,
            kepesertaan_status_kepesertaan,
            CASE 
                WHEN kepesertaan_status_kepesertaan = 'Peserta' THEN 'peserta'
                WHEN kepesertaan_status_kepesertaan = 'Pensiunan' THEN 'pensiunan'
                WHEN kepesertaan_status_kepesertaan = 'Pensiun Ditunda' THEN 'pensiun_ditunda'
                WHEN kepesertaan_status_kepesertaan = 'Pensiun Sekaligus' THEN 'pensiunan_sekaligus'
                WHEN kepesertaan_status_kepesertaan = 'Pensiunan Yang Sudah Tidak Berhak Menerima MP' THEN 'pensiunan_yang_tdk_terima_mp'
            END AS initial	
        FROM
            kepesertaans k
        GROUP BY
            kepesertaan_status_kepesertaan");

        $peserta = DB::select("SELECT
        count(*) AS total,
            kepesertaan_status_jabatan,
            CASE 
                WHEN kepesertaan_status_jabatan = 'Guru Besar' THEN 'guru_besar'
                WHEN kepesertaan_status_jabatan = 'Dosen' THEN 'dosen'
                WHEN kepesertaan_status_jabatan = 'Karyawan' THEN 'karyawan'
            END AS initial
        FROM
            kepesertaans k
        WHERE 
        kepesertaan_status_kepesertaan = 'peserta'
        GROUP BY
            kepesertaan_status_jabatan");

        $pensiunan = DB::select("SELECT
        count(*) AS total,
            kepesertaan_jenis_pensiun,
             CASE 
                WHEN kepesertaan_jenis_pensiun = 'Pensiun Normal' THEN 'pensiun_normal'
                WHEN kepesertaan_jenis_pensiun = 'Pensiun Janda/Duda' THEN 'pensiun_janda_duda'
                WHEN kepesertaan_jenis_pensiun = 'Pensiun Anak' THEN 'pensiun_anak'
                WHEN kepesertaan_jenis_pensiun = 'Pensiun Dipercepat' THEN 'pensiun_dipercepat'
            END AS initial	
        FROM
            kepesertaans k
        WHERE 
        kepesertaan_status_kepesertaan = 'pensiunan'
        GROUP BY
            kepesertaan_jenis_pensiun");

        $operation = array_merge($operation, $peserta, $pensiunan);

        return $this->response($operation);
    }

    public function show($id){
        $year = isset($_GET['year'])? $_GET['year']:date("Y");
        $operation = Kepesertaan::withCompleteness()->where('kepesertaan_id',$id)->with('kepesertaan_dokumen')->with(['kepesertaan_iuran' => function ($query) use ($year) {
            $query->where('kepesertaan_iuran_tahun', $year)->orderBy('kepesertaan_iuran_type', 'desc')->orderBy('kepesertaan_iuran_bulan', 'asc');
        }])->first();
        if($operation){
            $link = KepesertaanLink::where('kepesertaan_link_nama',$operation['kepesertaan_status_kepesertaan'])->first();
            $operation['kepesertaan_link_url'] = isset($link['kepesertaan_link_url'])?$link['kepesertaan_link_url']:'';
        } else{
            $data = explode("_",$id);
            if($data[0]==1){
                $status = "Peserta";
                $filter = "kepesertaan_status_jabatan";
            } else if($data[0]==2){
                $status = "Pensiunan";
                $filter = "kepesertaan_jenis_pensiun";
                $data[1] = $data[1]=="Pensiun Janda Duda"? "Pensiun Janda/Duda":$data[1];
            } else if($data[0]==3){
                $status = "Pensiun Ditunda";
                $filter = "kepesertaan_jenis_pensiun_sekaligus";
            } else if($data[0]==4){
                $status = "Pensiun Sekaligus";
                $filter = "kepesertaan_jenis_pensiun_tdk_berhak_mp";
            } else if($data[0]==5){
                $status = "Pensiunan Yang Sudah Tidak Berhak Menerima MP";
            } 

            if($data[2] == ""){      
                $operation = Kepesertaan::withCompleteness()->where('kepesertaan_status_kepesertaan',$status)->where($filter,$data[1])->get();
            } else{
                if(isset($status)){
                    $operation = Kepesertaan::withCompleteness()->where('kepesertaan_status_kepesertaan',$status)->get();
                } else{
                    $operation = Kepesertaan::withCompleteness()->get();
                }
            }
        }

        $operation['iuran_tahun_distinct'] = DB::select(" SELECT distinct kepesertaan_iuran_tahun FROM kepesertaan_iurans where kepesertaan_iuran_kepesertaan_id = '".$id."' Order By kepesertaan_iuran_tahun desc");

        $getIuran = DB::select('SELECT kepesertaan_iuran_type, SUM(kepesertaan_iuran_jumlah) as kepesertaan_iuran_jumlah, SUM(COALESCE(imported_iuran_peserta, kepesertaan_iuran_jumlah * 0.075)) as imported_total FROM kepesertaan_iurans WHERE kepesertaan_iuran_kepesertaan_id = ? AND kepesertaan_iuran_tahun < ? GROUP BY kepesertaan_iuran_type', [$id, $year]);
        $operation['year_before_iuran_total'] = 0;
        $operation['year_before_iuran_pengembangan'] = 0;
        
        if(count($getIuran)){
            foreach($getIuran as $k=>$v){
                if($v->kepesertaan_iuran_type == 'iuran_total'){
                    $counter = $v->imported_total;
                }else{
                    $temp = $v->kepesertaan_iuran_jumlah * 0.075;
                    $counter = ((0.045/12)*$temp) + $temp;
                }
                $operation['year_before_'.$v->kepesertaan_iuran_type] = round($counter, 2);
            }
        } else{
            $operation['year_before_iuran_total'] = 0;
            $operation['year_before_iuran_pengembangan'] = 0;
        }

        $kepesertaan_before = KepesertaanBefore::where('kepesertaan_before_kepesertaan_id',$id)->get();
        foreach($kepesertaan_before as $k=>$v){
            if($v['kepesertaan_before_total'] != ''){
                $operation['year_before_iuran_total'] += $v['kepesertaan_before_total'];
            }

            if($v['kepesertaan_before_pengembangan'] != ''){
                $operation['year_before_iuran_pengembangan'] += $v['kepesertaan_before_pengembangan'];
            }
        }
        return $this->responseFirst($operation);
    }

    public function store(Request $request){
        $this->authorizeEdit();
        $this->validateIdentity($request);
        \App\Services\ParticipantUpload::validate($request);
        $newFiles = [];
        try {
            $data = $request->all();

            $data['kepesertaan_id'] = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
            $dokumen_file = isset($data['list_file_dokumen'])?$data['list_file_dokumen']:array();
            $dokumen_nama = isset($data['list_dokumen_nama'])?$data['list_dokumen_nama']:array();
            unset($data['list_file_dokumen']);unset($data['list_dokumen_nama']);unset($data['list_dokumen_id']);

            $operation = DB::transaction(function () use($data,$dokumen_file,$dokumen_nama,$request, &$newFiles) {
                if($request->hasFile('file')){
                    $data['kepesertaan_foto'] = \App\Services\ParticipantUpload::store($request->file('file'), 'kepesertaan_foto', $newFiles);
                }

                unset($data['file']);
                $kepesertaan_iuran_tahun = $data['kepesertaan_iuran_tahun'];
                $iuran_total = $data['iuran_total_list'];
                $iuran_pengembangan = $data['iuran_pengembangan_list'];
                $lock_list =  isset($data['kepesertaan_iuran_lock'])?$data['kepesertaan_iuran_lock']:[];
                $kepesertaan_total_manual = $data['kepesertaan_total_manual'];
                $kepesertaan_pengembangan_manual = $data['kepesertaan_pengembangan_manual'];

                $gaji = str_replace(".",'',$data['kepesertaan_gaji_pokok']);
                $skgs = str_replace(".",'',$data['kepesertaan_nilai_sekaligus']);
                $prsn = str_replace(".",'',$data['kepesertaan_nilai_20_persen']);
                $pajk = str_replace(".",'',$data['kepesertaan_nilai_pajak']);
                $bln  = str_replace(".",'',$data['kepesertaan_nilai_bulanan']);
                unset($data['kepesertaan_iuran_tahun']);
                unset($data['iuran_total_list']);
                unset($data['iuran_pengembangan_list']);
                unset($data['kepesertaan_iuran_lock']);
                unset($data['kepesertaan_total_manual']);
                unset($data['kepesertaan_pengembangan_manual']);

                unset($data['kepesertaan_gaji_pokok']);
                unset($data['kepesertaan_nilai_sekaligus']);
                unset($data['kepesertaan_nilai_20_persen']);
                unset($data['kepesertaan_nilai_pajak']);
                unset($data['kepesertaan_nilai_bulanan']);

                $data['kepesertaan_gaji_pokok'] = $gaji ;
                $data['kepesertaan_nilai_sekaligus'] = $skgs ;
                $data['kepesertaan_nilai_20_persen'] = $prsn ;
                $data['kepesertaan_nilai_pajak'] = $pajk ;
                $data['kepesertaan_nilai_bulanan'] = $bln ;
                
                $kepesertaan = Kepesertaan::create($data);
                foreach($dokumen_nama as $key=>$value){
                    $insert_dokumen =[
                        "kepesertaan_dokumen_kepesertaan_id" =>$data['kepesertaan_id'],
                        "kepesertaan_dokumen_judul" =>$value,
                    ];

                    if(isset($dokumen_file[$key])){
                        $insert_dokumen['kepesertaan_dokumen_file'] = \App\Services\ParticipantUpload::store($dokumen_file[$key], 'kepesertaan_dokumen', $newFiles);
                    } else{
                        $insert_dokumen['kepesertaan_dokumen_file'] = '';
                    }

                    KepesertaanDokumen::create($insert_dokumen);
                }


                if($kepesertaan_iuran_tahun != ''){
                    foreach($iuran_total as $key=>$value){  
                        if(in_array($key+1,$lock_list)){
                            $lock = 1;
                        }else{
                            $lock = 0;
                        }   
                        $insert_data =[
                            "kepesertaan_iuran_kepesertaan_id" =>$data['kepesertaan_id'],
                            "kepesertaan_iuran_type" => 'iuran_total',
                            "kepesertaan_iuran_bulan" => $key+1,
                            "kepesertaan_iuran_tahun" => $kepesertaan_iuran_tahun,
                            "kepesertaan_iuran_jumlah" => $value,
                            "kepesertaan_iuran_lock" => $lock,
                        ];
                        KepesertaanIuran::create($insert_data);
                    }
    
                    foreach($iuran_pengembangan as $key=>$value){
                        if(in_array($key+1,$lock_list)){
                            $lock = 1;
                        }else{
                            $lock = 0;
                        }
                        $insert_data =[
                            "kepesertaan_iuran_kepesertaan_id" =>$data['kepesertaan_id'],
                            "kepesertaan_iuran_type" => 'iuran_pengembangan',
                            "kepesertaan_iuran_bulan" => $key+1,
                            "kepesertaan_iuran_tahun" => $kepesertaan_iuran_tahun,
                            "kepesertaan_iuran_jumlah" => $value,
                            "kepesertaan_iuran_lock" => $lock,
                        ];
                        KepesertaanIuran::create($insert_data);
                    }

                    if($kepesertaan_total_manual != '' || $kepesertaan_pengembangan_manual != ''){
                        $insert_data =[
                            "kepesertaan_before_kepesertaan_id" =>$data['kepesertaan_id'],
                            "kepesertaan_before_total" => $kepesertaan_total_manual,
                            "kepesertaan_before_pengembangan" => $kepesertaan_pengembangan_manual,
                        ];
                        KepesertaanBefore::create($insert_data);
                    }
                }
                return $kepesertaan;
            });      

            return $this->responseCreate($operation);
        } catch (\Throwable $e) {
            $this->deleteStoredFiles($newFiles);
            return $this->responseCreate($e->getMessage(),true);
        }
    }

    public function update(Request $request, $id){
        $this->authorizeEdit();
        Kepesertaan::findOrFail($id);
        $this->validateIdentity($request, $id);
        \App\Services\ParticipantUpload::validate($request);
        $request->merge(['kepesertaan_id' => $id]);
        $request->validate(['list_dokumen_id' => 'nullable|array', 'list_dokumen_id.*' => ['nullable', 'string',
            \Illuminate\Validation\Rule::exists('kepesertaan_dokumens', 'kepesertaan_dokumen_id')->where('kepesertaan_dokumen_kepesertaan_id', $id)]]);
        $oldFiles = [];
        $newFiles = [];
        try {
            $data = $request->all();
            $operation = DB::transaction(function () use($data, $id,$request, &$oldFiles, &$newFiles) {
                $dokumen_id = isset($data['list_dokumen_id'])?$data['list_dokumen_id']:array();
                $dokumen_file = isset($data['list_file_dokumen'])?$data['list_file_dokumen']:array();
                $dokumen_nama = isset($data['list_dokumen_nama'])?$data['list_dokumen_nama']:array();
                unset($data['list_file_dokumen']);unset($data['list_dokumen_nama']);unset($data['list_dokumen_id']);

                if($dokumen_nama){    
                    $list_id = array_values(array_filter($dokumen_id));
                    $deleteDoc = kepesertaanDokumen::where('kepesertaan_dokumen_kepesertaan_id', $data['kepesertaan_id'])->whereNotIn('kepesertaan_dokumen_id', $list_id)->get();
    
                    if($deleteDoc){
                        foreach($deleteDoc as $loop){
                            $path = 'kepesertaan_dokumen/';
                            if ($loop['kepesertaan_dokumen_file']) $oldFiles[] = $path.$loop['kepesertaan_dokumen_file'];
                            kepesertaanDokumen::find($loop['kepesertaan_dokumen_id'])->delete();
                        }   
                    } 
    
                    foreach($dokumen_nama as $key=>$value){
                        $check = kepesertaanDokumen::where('kepesertaan_dokumen_kepesertaan_id', $id)->where('kepesertaan_dokumen_id', $dokumen_id[$key] ?? null)->first();

                        if($check){
                            $check = $check->toArray();
                            $check['kepesertaan_dokumen_judul'] = $value;
                            if($dokumen_file){
                                if(array_key_exists($key, $dokumen_file)){
                                    $path = 'kepesertaan_dokumen/';
                                    $keepOld = $check['kepesertaan_dokumen_file'];
                                    
                                    $check['kepesertaan_dokumen_file'] = \App\Services\ParticipantUpload::store($dokumen_file[$key], 'kepesertaan_dokumen', $newFiles);
                                    if ($keepOld) {
                                        KepesertaanDokumen::create([
                                            'kepesertaan_dokumen_kepesertaan_id' => $id,
                                            'kepesertaan_dokumen_judul' => $value,
                                            'kepesertaan_dokumen_file' => $check['kepesertaan_dokumen_file'],
                                        ]);
                                        $check['kepesertaan_dokumen_file'] = $keepOld;
                                    }
                                }
                            }
                            kepesertaanDokumen::find($dokumen_id[$key])->update($check);
                        } else{
                            $insert_dokumen =[
                                "kepesertaan_dokumen_kepesertaan_id" =>$data['kepesertaan_id'],
                                "kepesertaan_dokumen_judul" =>$value,
                            ];
        
                            if(isset($dokumen_file[$key])){
                                $insert_dokumen['kepesertaan_dokumen_file'] = \App\Services\ParticipantUpload::store($dokumen_file[$key], 'kepesertaan_dokumen', $newFiles);
                            } else{
                                $insert_dokumen['kepesertaan_dokumen_file'] = '';
                            }
                            kepesertaanDokumen::create($insert_dokumen);
                        }
                    }
                } else{
                    foreach (kepesertaanDokumen::where('kepesertaan_dokumen_kepesertaan_id', $id)->get() as $document) {
                        if ($document->kepesertaan_dokumen_file) $oldFiles[] = 'kepesertaan_dokumen/'.$document->kepesertaan_dokumen_file;
                    }
                    kepesertaanDokumen::where('kepesertaan_dokumen_kepesertaan_id', $data['kepesertaan_id'])->delete();
                }

                if($request->hasFile('file')){
                    $previousPhoto = Kepesertaan::findOrFail($id)->kepesertaan_foto;
                    if ($previousPhoto) $oldFiles[] = 'kepesertaan_foto/'.$previousPhoto;
                    $data['kepesertaan_foto'] = \App\Services\ParticipantUpload::store($request->file('file'), 'kepesertaan_foto', $newFiles);
                }

                $kepesertaan_iuran_tahun = $data['kepesertaan_iuran_tahun'];
                $iuran_total = $data['iuran_total_list'];
                $iuran_pengembangan = $data['iuran_pengembangan_list'];
                $lock_list =  isset($data['kepesertaan_iuran_lock'])?$data['kepesertaan_iuran_lock']:[];
                unset($data['kepesertaan_iuran_tahun']);
                unset($data['iuran_total_list']);
                unset($data['iuran_pengembangan_list']);
                unset($data['kepesertaan_iuran_lock']);

                $kepesertaan_total_manual = $data['kepesertaan_total_manual'];
                $kepesertaan_pengembangan_manual = $data['kepesertaan_pengembangan_manual'];
                unset($data['kepesertaan_total_manual']);
                unset($data['kepesertaan_pengembangan_manual']);

                $previousIuran = KepesertaanIuran::where('kepesertaan_iuran_kepesertaan_id',$id)->where('kepesertaan_iuran_tahun',$kepesertaan_iuran_tahun)->where('kepesertaan_iuran_type','iuran_total')->get()->keyBy('kepesertaan_iuran_bulan');
                KepesertaanIuran::where('kepesertaan_iuran_kepesertaan_id',$id)->where('kepesertaan_iuran_tahun',$kepesertaan_iuran_tahun)->delete();
                if($kepesertaan_iuran_tahun != ''){
                    foreach($iuran_total as $key=>$value){
                        if(in_array($key+1,$lock_list)){
                            $lock = 1;
                        }else{
                            $lock = 0;
                        }  
                        $insert_data =[
                            "kepesertaan_iuran_kepesertaan_id" =>$data['kepesertaan_id'],
                            "kepesertaan_iuran_type" => 'iuran_total',
                            "kepesertaan_iuran_bulan" => $key+1,
                            "kepesertaan_iuran_tahun" => $kepesertaan_iuran_tahun,
                            "kepesertaan_iuran_jumlah" => $value,
                            "kepesertaan_iuran_lock" => $lock,
                        ];
                        $previous = $previousIuran->get($key + 1);
                        if ($previous && $value !== null && $value !== '' && (float)$previous->kepesertaan_iuran_jumlah === (float)$value) {
                            $insert_data['imported_iuran_peserta'] = $previous->imported_iuran_peserta;
                            $insert_data['imported_iuran_pk'] = $previous->imported_iuran_pk;
                        }
                        KepesertaanIuran::create($insert_data);
                    }
    
                    foreach($iuran_pengembangan as $key=>$value){
                        if(in_array($key+1,$lock_list)){
                            $lock = 1;
                        }else{
                            $lock = 0;
                        }  
                        $insert_data =[
                            "kepesertaan_iuran_kepesertaan_id" =>$data['kepesertaan_id'],
                            "kepesertaan_iuran_type" => 'iuran_pengembangan',
                            "kepesertaan_iuran_bulan" => $key+1,
                            "kepesertaan_iuran_tahun" => $kepesertaan_iuran_tahun,
                            "kepesertaan_iuran_jumlah" => $value,
                            "kepesertaan_iuran_lock" => $lock,
                        ];
                        KepesertaanIuran::create($insert_data);
                    }

                    if(KepesertaanBefore::where('kepesertaan_before_kepesertaan_id', $id)->count()>0){
                        if($kepesertaan_total_manual != '' || $kepesertaan_pengembangan_manual != ''){
                            $insert_data =[
                                "kepesertaan_before_kepesertaan_id" =>$data['kepesertaan_id'],
                                "kepesertaan_before_total" => $kepesertaan_total_manual,
                                "kepesertaan_before_pengembangan" => $kepesertaan_pengembangan_manual,
                            ];
                            KepesertaanBefore::where('kepesertaan_before_kepesertaan_id', $id)->update($insert_data);
                        }
                    } else{
                        if($kepesertaan_total_manual != '' || $kepesertaan_pengembangan_manual != ''){
                            $insert_data =[
                                "kepesertaan_before_kepesertaan_id" =>$data['kepesertaan_id'],
                                "kepesertaan_before_total" => $kepesertaan_total_manual,
                                "kepesertaan_before_pengembangan" => $kepesertaan_pengembangan_manual,
                            ];
                            KepesertaanBefore::create($insert_data);
                        }  
                    }
                    
                }

                $gaji = str_replace(".",'',$data['kepesertaan_gaji_pokok']);
                $skgs = str_replace(".",'',$data['kepesertaan_nilai_sekaligus']);
                $prsn = str_replace(".",'',$data['kepesertaan_nilai_20_persen']);
                $pajk = str_replace(".",'',$data['kepesertaan_nilai_pajak']);
                $bln  = str_replace(".",'',$data['kepesertaan_nilai_bulanan']);
                
                unset($data['file']);
                unset($data['kepesertaan_gaji_pokok']);
                unset($data['kepesertaan_nilai_sekaligus']);
                unset($data['kepesertaan_nilai_20_persen']);
                unset($data['kepesertaan_nilai_pajak']);
                unset($data['kepesertaan_nilai_bulanan']);

                $data['kepesertaan_gaji_pokok'] = $gaji ;
                $data['kepesertaan_nilai_sekaligus'] = $skgs ;
                $data['kepesertaan_nilai_20_persen'] = $prsn ;
                $data['kepesertaan_nilai_pajak'] = $pajk ;
                $data['kepesertaan_nilai_bulanan'] = $bln ;
                
                return kepesertaan::find($id)->update($data);
            });

            DB::afterCommit(function () use ($oldFiles) { $this->deleteStoredFiles($oldFiles); });
            return $this->responseUpdate($operation);
        } catch (\Throwable $e) {
            $this->deleteStoredFiles($newFiles);
            return $this->responseUpdate($e->getMessage(),true);
        }
    }

    public function destroy($id){
        $this->authorizeEdit();
        try {  
            $operation = DB::transaction(function () use($id) {
                kepesertaanDokumen::where('kepesertaan_dokumen_kepesertaan_id',$id)->delete();
                KepesertaanIuran::where('kepesertaan_iuran_kepesertaan_id',$id)->delete();
                return Kepesertaan::find($id)->delete();
            });
            
            return $this->responseDelete($operation);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }
}
