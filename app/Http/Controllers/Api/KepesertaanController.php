<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Kepesertaan;
use App\Models\KepesertaanLink;
use App\Models\KepesertaanDokumen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class KepesertaanController extends Controller
{
    public function index(){
        $operation = Kepesertaan::all();
        return $this->response($operation);
    }

    public function count(){
        $operation = DB::select( DB::raw("SELECT
            count(*) AS total,
            kepesertaan_status_kepesertaan,
            CASE 
                WHEN kepesertaan_status_kepesertaan = 'Peserta' THEN 'peserta'
                WHEN kepesertaan_status_kepesertaan = 'Pensiunan' THEN 'pensiunan'
                WHEN kepesertaan_status_kepesertaan = 'Pensiun Ditunda' THEN 'pensiun_ditunda'
                WHEN kepesertaan_status_kepesertaan = 'Pensiun Sekaligus' THEN 'pensiunan_sekaligus'
            END AS initial	
        FROM
            kepesertaans k
        GROUP BY
            kepesertaan_status_kepesertaan") );

        $peserta = DB::select( DB::raw("SELECT
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
            kepesertaan_status_jabatan") );

        $pensiunan = DB::select( DB::raw("SELECT
        count(*) AS total,
            kepesertaan_jenis_pensiun,
             CASE 
                WHEN kepesertaan_jenis_pensiun = 'Pensiun Normal' THEN 'pensiun_normal'
                WHEN kepesertaan_jenis_pensiun = 'Pensiun Janda/Duda' THEN 'pensiun_janda_duda'
                WHEN kepesertaan_jenis_pensiun = 'Pensiun Anak' THEN 'pensiun_anak'
            END AS initial	
        FROM
            kepesertaans k
        WHERE 
        kepesertaan_status_kepesertaan = 'pensiunan'
        GROUP BY
            kepesertaan_jenis_pensiun") );

        $operation = array_merge($operation, $peserta, $pensiunan);

        return $this->response($operation);
    }

    public function show($id){
        $operation = Kepesertaan::with('kepesertaan_dokumen')->where('kepesertaan_id',$id)->first();
        if($operation){
            $link = KepesertaanLink::where('kepesertaan_link_nama',$operation['kepesertaan_status_kepesertaan'])->first();
            $operation['kepesertaan_link_url'] = $link['kepesertaan_link_url'];
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
            } 

            if($data[2] == ""){      
                $operation = Kepesertaan::where('kepesertaan_status_kepesertaan',$status)->where($filter,$data[1])->get();
            } else{
                if(isset($status)){
                    $operation = Kepesertaan::where('kepesertaan_status_kepesertaan',$status)->get();
                } else{
                    $operation = Kepesertaan::all();
                }
            }
        }

        
        return $this->responseFirst($operation);
    }

    public function store(Request $request){
        try {
            $data = $request->all();
            $data['kepesertaan_id'] = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
            $dokumen_file = isset($data['list_file_dokumen'])?$data['list_file_dokumen']:array();
            $dokumen_nama = isset($data['list_dokumen_nama'])?$data['list_dokumen_nama']:array();
            unset($data['list_file_dokumen']);unset($data['list_dokumen_nama']);unset($data['list_dokumen_id']);

            $operation = DB::transaction(function () use($data,$dokumen_file,$dokumen_nama,$request) {
                if($_FILES['file']['error'] != 4){
                    $data['kepesertaan_foto']= 'kepesertaan_foto'.time().'.'.request()->file->getClientOriginalExtension();
                    Storage::disk('public')->putFileAs('kepesertaan_foto/', $request->file('file'), $data['kepesertaan_foto']);
                    $request->file('file')->move(public_path('storage/kepesertaan_foto'), $data['kepesertaan_foto']);
                }
                unset($data['file']);
                $kepesertaan = Kepesertaan::create($data);

                foreach($dokumen_nama as $key=>$value){
                    $insert_dokumen =[
                        "kepesertaan_dokumen_kepesertaan_id" =>$data['kepesertaan_id'],
                        "kepesertaan_dokumen_judul" =>$value,
                    ];

                    if(isset($dokumen_file[$key])){
                        $insert_dokumen['kepesertaan_dokumen_file']= $value.time().'.'.$dokumen_file[$key]->getClientOriginalExtension();
                        Storage::disk('public')->putFileAs('kepesertaan_dokumen/', $dokumen_file[$key], $insert_dokumen['kepesertaan_dokumen_file']);
                        $dokumen_file[$key]->move(public_path('storage/kepesertaan_dokumen'), $insert_dokumen['kepesertaan_dokumen_file']);
                    } else{
                        $insert_dokumen['kepesertaan_dokumen_file'] = '';
                    }

                    KepesertaanDokumen::create($insert_dokumen);
                }
                return $kepesertaan;
            });      
            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }

    public function update(Request $request, $id){
        try {
            $data = $request->all();
            $operation = DB::transaction(function () use($data, $id,$request) {
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
                            Storage::disk('public')->delete($path.$loop['kepesertaan_dokumen_file']);
                            Storage::delete($path.$loop['kepesertaan_dokumen_file']);
                            kepesertaanDokumen::find($loop['kepesertaan_dokumen_id'])->delete();
                        }   
                    } 
    
                    foreach($dokumen_nama as $key=>$value){
                        $check = kepesertaanDokumen::where('kepesertaan_dokumen_id', $dokumen_id[$key])->first();

                        if($check){
                            $check = $check->toArray();
                            $check['kepesertaan_dokumen_judul'] = $value;
                            if($dokumen_file){
                                if(array_key_exists($key, $dokumen_file)){
                                    $path = 'kepesertaan_dokumen/';
                                    Storage::disk('public')->delete($path.$check['kepesertaan_dokumen_file']);
                                    Storage::delete($path.$check['kepesertaan_dokumen_file']);
                                    
                                    $check['kepesertaan_dokumen_file']= $value.time().'.'.$dokumen_file[$key]->getClientOriginalExtension();
                                    Storage::disk('public')->putFileAs('kepesertaan_dokumen/', $dokumen_file[$key], $check['kepesertaan_dokumen_file']);
                                    $dokumen_file[$key]->move(public_path('storage/kepesertaan_dokumen'), $check['kepesertaan_dokumen_file']);
                                }
                            }
                            kepesertaanDokumen::find($dokumen_id[$key])->update($check);
                        } else{
                            $insert_dokumen =[
                                "kepesertaan_dokumen_kepesertaan_id" =>$data['kepesertaan_id'],
                                "kepesertaan_dokumen_judul" =>$value,
                            ];
        
                            if(isset($dokumen_file[$key])){
                                $insert_dokumen['kepesertaan_dokumen_file']= $value.time().'.'.$dokumen_file[$key]->getClientOriginalExtension();
                                Storage::disk('public')->putFileAs('kepesertaan_dokumen/', $dokumen_file[$key], $insert_dokumen['kepesertaan_dokumen_file']);
                                $dokumen_file[$key]->move(public_path('storage/kepesertaan_dokumen'), $insert_dokumen['kepesertaan_dokumen_file']);
                            } else{
                                $insert_dokumen['kepesertaan_dokumen_file'] = '';
                            }
                            kepesertaanDokumen::create($insert_dokumen);
                        }
                    }
                } else{
                    kepesertaanDokumen::where('kepesertaan_dokumen_kepesertaan_id', $data['kepesertaan_id'])->delete();
                }

                if($_FILES['file']['error'] != 4){
                    $data['kepesertaan_foto']= 'kepesertaan_foto'.time().'.'.request()->file->getClientOriginalExtension();
                    Storage::disk('public')->putFileAs('kepesertaan_foto/', $request->file('file'), $data['kepesertaan_foto']);
                    $request->file('file')->move(public_path('storage/kepesertaan_foto'), $data['kepesertaan_foto']);
                }
                unset($data['file']);
                return kepesertaan::find($id)->update($data);
            });

            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }

    public function destroy($id){
        try {  
            $operation = Kepesertaan::find($id)->delete();
            $destroydoc = kepesertaanDokumen::where('kepesertaan_dokumen_kepesertaan_id',$id)->delete();
            return $this->responseDelete($operation);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }
}
