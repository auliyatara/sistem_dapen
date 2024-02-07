<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Aktifitas;
use App\Models\AktifitasDokumen;
use App\Models\Notifikasi;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class AktifitasController extends Controller
{
    public function index(){
        $auth = Auth::id();
        // $operation = Aktifitas::where('aktifitas_user_id',$auth)->orderBy('created_at', 'asc')->get();
        $operation = Aktifitas::where('aktifitas_user_id',$auth)->orderBy('created_at', 'desc')->get();
        return $this->response($operation);
    }

    public function show($id){
        $operation = Aktifitas::where('aktifitas_id',$id)->with('aktifitas_dokumen')->first();
        $finduser = User::find($operation['aktifitas_user_id']);
        $operation['user_nama'] = $finduser['user_nama'];
        $operation['role_name'] = Role::find($finduser['user_role_id'])['role_name'];
        return $this->responseFirst($operation);
    }

    public function store(Request $request){
        try {
            $request->validate([
                'aktifitas_nama' => 'required',
                'list_dokumen_file.*' => ['required','mimes:jpeg,png,jpg,gif,svg,pdf'],
            ]);

            $data = $request->all();
            $data['aktifitas_id'] = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
            $data['aktifitas_file'] = 'Multiple Document';
            $data['aktifitas_user_id']= Auth::id();
            
            $dokumen_file = isset($data['list_file_dokumen'])?$data['list_file_dokumen']:array();
            $dokumen_nama = isset($data['list_dokumen_nama'])?$data['list_dokumen_nama']:array();
            unset($data['list_file_dokumen']);unset($data['list_dokumen_nama']);unset($data['list_dokumen_id']);

            $operation = DB::transaction(function () use($data,$dokumen_file,$dokumen_nama) {
                $aktifitas = Aktifitas::create($data);

                foreach($dokumen_nama as $key=>$value){
                    $insert_dokumen =[
                        "aktifitas_dokumen_aktifitas_id" =>$data['aktifitas_id'],
                        "aktifitas_dokumen_judul" =>$value,
                    ];

                    if(isset($dokumen_file[$key])){
                        $insert_dokumen['aktifitas_dokumen_file']= $value.time().'.'.$dokumen_file[$key]->getClientOriginalExtension();
                        Storage::disk('public')->putFileAs('file_aktifitas/', $dokumen_file[$key], $insert_dokumen['aktifitas_dokumen_file']);
                        $dokumen_file[$key]->move(public_path('storage/file_aktifitas'), $insert_dokumen['aktifitas_dokumen_file']);
                    } else{
                        $insert_dokumen['aktifitas_dokumen_file'] = '';
                    }

                    AktifitasDokumen::create($insert_dokumen);
                }
                
                $notifikasi = Notifikasi::create([
                    'notifikasi_judul' => 'Pengajuan Aktifitas',
                    'notifikasi_keterangan' => Auth::user()->user_nama.' mengajukan Aktifitas Baru dengan judul '.$data['aktifitas_nama'],
                    'notifikasi_status' => 1,
                    'notifikasi_url' => 'aktifitas_approval',
                    'notifikasi_penerima_user_id' => NULL,
                    'notifikasi_pengirim_user_id' => Auth::id(),
                    'notifikasi_pengirim_user_nama' => Auth::user()->user_nama,
                ]);
                
                return $aktifitas;
            });
            
            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }

    public function update(Request $request, $id){
        try {
            $request->validate([
                'aktifitas_komentar' => 'required',
                'aktifitas_status' => 'required',
            ]);

            $data = $request->all();
            unset($data['_token']);
            
            if($data['aktifitas_status'] == 3){
                $status = 'Merevisi';
            } else if($data['aktifitas_status'] == 2){
                $status = 'Menolak';
            } else if($data['aktifitas_status'] == 1){
                $status = 'Menerima';
            }

            $operation = DB::transaction(function () use($data,$id,$status) {
                $aktifitas = Aktifitas::find($id)->update($data);
                $notifikasi = Notifikasi::create([
                    'notifikasi_judul' => 'Hasil Persetujuan Aktifitas',
                    'notifikasi_keterangan' => 'Direktur '.$status.' aktifitas yang anda ajukan dengan judul '.$data['aktifitas_nama'],
                    'notifikasi_status' => 1,
                    'notifikasi_url' => 'aktifitas',
                    'notifikasi_penerima_user_id' => $data['aktifitas_user_id'],
                    'notifikasi_pengirim_user_id' => Auth::id(),
                    'notifikasi_pengirim_user_nama' => Auth::user()->user_nama,
                ]);
                
                return $aktifitas;
            });

            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }

    public function destroy($id){
        try {  
            $find = Aktifitas::find($id);       
            $path = 'file_aktifitas/';
            Storage::disk('public')->delete($path.$find['aktifitas_file']);
            Storage::delete($path.$find['aktifitas_file']);
            $operation = $find->delete();   
            return $this->responseDelete($operation);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }

    
    public function updatealternate(Request $request){
        try {
            $data = $request->all();

            $data['aktifitas_user_id']= Auth::id();
            $data['aktifitas_nama']=  $data['revisi_aktifitas_nama'];
            $data['aktifitas_status']= 0;
            $data['aktifitas_komentar']= '';
            $id = $data['revisi_aktifitas_id'];
            // print_r($data);exit;
            // Storage::disk('public')->putFileAs('file_aktifitas/', $request->file('revisi_file'), $data['aktifitas_file']);
            // $request->file('revisi_file')->move(public_path('storage/file_aktifitas'), $data['aktifitas_file']);

            $operation = DB::transaction(function () use($data,$id) {
                $dokumen_id = isset($data['list_dokumen_id'])?$data['list_dokumen_id']:array();
                $dokumen_file = isset($data['list_file_dokumen'])?$data['list_file_dokumen']:array();
                $dokumen_nama = isset($data['list_dokumen_nama'])?$data['list_dokumen_nama']:array();
                unset($data['list_file_dokumen']);unset($data['list_dokumen_nama']);unset($data['list_dokumen_id']);unset($data['_token']);
                unset($data['revisi_aktifitas_id']);unset($data['revisi_aktifitas_nama']);
                $aktifitas = Aktifitas::where('aktifitas_id',$id)->update($data);

                if($dokumen_nama){    
                    $list_id = array_values(array_filter($dokumen_id));
                    $deleteDoc = AktifitasDokumen::where('aktifitas_dokumen_aktifitas_id', $id)->whereNotIn('aktifitas_dokumen_id', $list_id)->get();
    
                    if($deleteDoc){
                        foreach($deleteDoc as $loop){
                            $path = 'file_aktifitas/';
                            Storage::disk('public')->delete($path.$loop['aktifitas_dokumen_file']);
                            Storage::delete($path.$loop['aktifitas_dokumen_file']);
                            aktifitasDokumen::find($loop['aktifitas_dokumen_id'])->delete();
                        }   
                    } 
    
                    foreach($dokumen_nama as $key=>$value){
                        $check = aktifitasDokumen::where('aktifitas_dokumen_id', $dokumen_id[$key])->first();

                        if($check){
                            $check = $check->toArray();
                            $check['aktifitas_dokumen_judul'] = $value;
                            if($dokumen_file){
                                if(array_key_exists($key, $dokumen_file)){
                                    $path = 'file_aktifitas/';
                                    Storage::disk('public')->delete($path.$check['aktifitas_dokumen_file']);
                                    Storage::delete($path.$check['aktifitas_dokumen_file']);
                                    
                                    $check['aktifitas_dokumen_file']= $value.time().'.'.$dokumen_file[$key]->getClientOriginalExtension();
                                    Storage::disk('public')->putFileAs('file_aktifitas/', $dokumen_file[$key], $check['aktifitas_dokumen_file']);
                                    $dokumen_file[$key]->move(public_path('storage/file_aktifitas'), $check['aktifitas_dokumen_file']);
                                }
                            }
                            AktifitasDokumen::find($dokumen_id[$key])->update($check);
                        }
                    }
                } else{
                    AktifitasDokumen::where('aktifitas_dokumen_aktifitas_id', $id)->delete();
                }

                $notifikasi = Notifikasi::create([
                    'notifikasi_judul' => 'Pengajuan Revisi Aktifitas',
                    'notifikasi_keterangan' => Auth::user()->user_nama.' mengajukan revisi aktifitas Baru dengan judul '.$data['aktifitas_nama'],
                    'notifikasi_status' => 1,
                    'notifikasi_url' => 'aktifitas_approval',
                    'notifikasi_penerima_user_id' => NULL,
                    'notifikasi_pengirim_user_id' => Auth::id(),
                    'notifikasi_pengirim_user_nama' => Auth::user()->user_nama,
                ]);
                
                return $aktifitas;
            });
            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }
}
