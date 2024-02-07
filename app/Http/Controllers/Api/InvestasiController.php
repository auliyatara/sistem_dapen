<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Investasi;
use App\Models\InvestasiDokumen;
use App\Models\InvestasiBulan;
use App\Models\InvestasiSubdata;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class InvestasiController extends Controller
{
    public function index(){
        $year = isset($_GET['year'])? $_GET['year']:date("Y");
        $operation = Investasi::with([
            'investasi_nilai_wajar' => function ($query) use ($year) {
                $query->where('investasi_bulan_tahun', $year)->orderBy('investasi_bulan_bulan', 'asc');
            }
        ])->with('investasi_subdata')->with([
            'investasi_subdata.investasi_subdata_nilai_wajar' => function ($query) use ($year) {
                $query->where('investasi_subdata_bulan_tahun', $year)->orderBy('investasi_subdata_bulan_bulan', 'asc');
            }
        ])->orderBy('created_at', 'asc')->get();
        return $this->response($operation);
    }

    public function show($id){
        $year = isset($_GET['year'])? $_GET['year']:date("Y");
        $operation = Investasi::where('investasi_id',$id)->with('investasi_dokumen')->with('investasi_subdata')->with([
            'investasi_nilai_wajar' => function ($query) use ($year) {
                $query->where('investasi_bulan_tahun', $year)->orderBy('investasi_bulan_bulan', 'asc');
            }
        ])->first();
        return $this->responseFirst($operation);
    }

    public function store(Request $request){
        try {
            $data = $request->all();            
            $data['investasi_id'] = Str::substr((Uuid::uuid4())->getHex(), 0, 16);

            $operation = DB::transaction(function () use($data) {
                $subdata_id = $data['investasi_subdata_id'];
                $subdata_nama = $data['investasi_subdata_nama'];
                unset($data['investasi_subdata_id']);unset($data['investasi_subdata_nama']);
                
                $investasi_tahun = $data['investasi_tahun'];
                $investasi_bulan_list = $data['investasi_bulan_list'];
                unset($data['investasi_tahun']);unset($data['investasi_bulan_list']);

                $dokumen_file = isset($data['list_file_dokumen'])?$data['list_file_dokumen']:array();
                $dokumen_nama = isset($data['list_dokumen_nama'])?$data['list_dokumen_nama']:array();
                unset($data['list_file_dokumen']);unset($data['list_dokumen_nama']);unset($data['list_dokumen_id']);

                $investasi = Investasi::create($data);

                foreach($dokumen_nama as $key=>$value){
                    $insert_dokumen =[
                        "investasi_dokumen_investasi_id" =>$data['investasi_id'],
                        "investasi_dokumen_judul" =>$value,
                    ];

                    if(isset($dokumen_file[$key])){
                        $insert_dokumen['investasi_dokumen_file']= $value.time().'.'.$dokumen_file[$key]->getClientOriginalExtension();
                        Storage::disk('public')->putFileAs('investasi_dokumen/', $dokumen_file[$key], $insert_dokumen['investasi_dokumen_file']);
                        $dokumen_file[$key]->move(public_path('storage/investasi_dokumen'), $insert_dokumen['investasi_dokumen_file']);
                    } else{
                        $insert_dokumen['investasi_dokumen_file'] = '';
                    }

                    InvestasiDokumen::create($insert_dokumen);
                }

                foreach($investasi_bulan_list as $key=>$value){
                    $insert =[
                        "investasi_bulan_investasi_id" =>$data['investasi_id'],
                        "investasi_bulan_bulan" =>$key+1,
                        "investasi_bulan_tahun" =>$investasi_tahun,
                        "investasi_bulan_nilai_wajar" =>$value,
                    ];

                    $process = InvestasiBulan::create($insert);
                }

                foreach($subdata_nama as $key=>$value){
                    $insert_subdata =[
                        "investasi_subdata_investasi_id" =>$data['investasi_id'],
                        "investasi_subdata_nama" =>$value
                    ];

                    InvestasiSubdata::create($insert_subdata);
                }
                return $investasi;
            });

            
            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }

    public function update(Request $request, $id){
        try {
            $data = $request->all();
            $operation = DB::transaction(function () use($data, $id) {
                $dokumen_id = isset($data['list_dokumen_id'])?$data['list_dokumen_id']:array();
                $dokumen_file = isset($data['list_file_dokumen'])?$data['list_file_dokumen']:array();
                $dokumen_nama = isset($data['list_dokumen_nama'])?$data['list_dokumen_nama']:array();
                unset($data['list_file_dokumen']);unset($data['list_dokumen_nama']);unset($data['list_dokumen_id']);

                $investasi_tahun = $data['investasi_tahun'];
                $investasi_bulan_list = $data['investasi_bulan_list'];
                unset($data['investasi_tahun']);unset($data['investasi_bulan_list']);

                $subdata_id = $data['investasi_subdata_id'];
                $subdata_nama = $data['investasi_subdata_nama'];
                unset($data['investasi_subdata_id']);unset($data['investasi_subdata_nama']);

                if($dokumen_nama){    
                    $list_id = array_values(array_filter($dokumen_id));
                    $deleteDoc = InvestasiDokumen::where('investasi_dokumen_investasi_id', $data['investasi_id'])->whereNotIn('investasi_dokumen_id', $list_id)->get();
    
                    if($deleteDoc){
                        foreach($deleteDoc as $loop){
                            $path = 'investasi_dokumen/';
                            Storage::disk('public')->delete($path.$loop['investasi_dokumen_file']);
                            Storage::delete($path.$loop['investasi_dokumen_file']);
                            InvestasiDokumen::find($loop['investasi_dokumen_id'])->delete();
                        }   
                    } 
    
                    foreach($dokumen_nama as $key=>$value){
                        $check = InvestasiDokumen::where('investasi_dokumen_id', $dokumen_id[$key])->first();

                        if($check){
                            $check = $check->toArray();
                            $check['investasi_dokumen_judul'] = $value;
                            if($dokumen_file){
                                if(array_key_exists($key, $dokumen_file)){
                                    $path = 'investasi_dokumen/';
                                    Storage::disk('public')->delete($path.$check['investasi_dokumen_file']);
                                    Storage::delete($path.$check['investasi_dokumen_file']);
                                    
                                    $check['investasi_dokumen_file']= $value.time().'.'.$dokumen_file[$key]->getClientOriginalExtension();
                                    Storage::disk('public')->putFileAs('investasi_dokumen/', $dokumen_file[$key], $check['investasi_dokumen_file']);
                                    $dokumen_file[$key]->move(public_path('storage/investasi_dokumen'), $check['investasi_dokumen_file']);
                                }
                            }
                            InvestasiDokumen::find($dokumen_id[$key])->update($check);
                        } else{
                            $insert_dokumen =[
                                "investasi_dokumen_investasi_id" =>$data['investasi_id'],
                                "investasi_dokumen_judul" =>$value,
                            ];
        
                            if(isset($dokumen_file[$key])){
                                $insert_dokumen['investasi_dokumen_file']= $value.time().'.'.$dokumen_file[$key]->getClientOriginalExtension();
                                Storage::disk('public')->putFileAs('investasi_dokumen/', $dokumen_file[$key], $insert_dokumen['investasi_dokumen_file']);
                                $dokumen_file[$key]->move(public_path('storage/investasi_dokumen'), $insert_dokumen['investasi_dokumen_file']);
                            } else{
                                $insert_dokumen['investasi_dokumen_file'] = '';
                            }
                            InvestasiDokumen::create($insert_dokumen);
                        }
                    }
                } else{
                    InvestasiDokumen::where('investasi_dokumen_investasi_id', $data['investasi_id'])->delete();
                }

                if($subdata_nama){    
                    $list_id = array_values(array_filter($subdata_id));
                    $deleteDoc = InvestasiSubdata::where('investasi_subdata_investasi_id', $data['investasi_id'])->whereNotIn('investasi_subdata_id', $list_id)->get();
    
                    if($deleteDoc){
                        foreach($deleteDoc as $loop){
                            InvestasiSubdata::find($loop['investasi_subdata_id'])->delete();
                        }   
                    } 
    
                    foreach($subdata_nama as $key=>$value){
                        $check = InvestasiSubdata::where('investasi_subdata_id', $subdata_id[$key])->first();

                        if($check){
                            $check = $check->toArray();
                            $check['investasi_subdata_nama'] = $value;
                            InvestasiSubdata::find($subdata_id[$key])->update($check);
                        } else{
                            $insert_subdata =[
                                "investasi_subdata_investasi_id" =>$data['investasi_id'],
                                "investasi_subdata_nama" =>$value
                            ];
        
                            InvestasiSubdata::create($insert_subdata);
                        }
                    }
                } else{
                    InvestasiSubdata::where('investasi_subdata_investasi_id', $data['investasi_id'])->delete();
                }

                InvestasiBulan::where('investasi_bulan_investasi_id',$id)->where('investasi_bulan_tahun',$investasi_tahun)->delete();
                foreach($investasi_bulan_list as $key=>$value){
                    $insert =[
                        "investasi_bulan_investasi_id" =>$id,
                        "investasi_bulan_bulan" =>$key+1,
                        "investasi_bulan_tahun" =>$investasi_tahun,
                        "investasi_bulan_nilai_wajar" =>$value,
                    ];

                    $process = InvestasiBulan::create($insert);
                }


                return Investasi::find($id)->update($data);
            });

            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }

    public function destroy($id){
        try {  
            $operation = DB::transaction(function () use($id) {
                InvestasiDokumen::where('investasi_dokumen_investasi_id', $id)->delete();
                InvestasiBulan::where('investasi_bulan_investasi_id',$id)->delete();
                return Investasi::find($id)->delete();   
            });
                   
            return $this->responseDelete($operation);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }
}
