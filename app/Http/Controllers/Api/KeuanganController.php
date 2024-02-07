<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use App\Models\Keuangan;
use App\Models\KeuanganDetail;


class KeuanganController extends Controller
{
    public function index(){
        $year = isset($_GET['year'])? $_GET['year']:year(now());
        $operation = Keuangan::with([
            'keuangan_detail' => function ($query) use ($year) {
                $query->with(['keuangan_bulan' => function ($query) use ($year) {
                    $query->where('keuangan_bulan_tahun', $year)
                          ->orderBy('keuangan_bulan_bulan', 'asc');
                }]);
            }
        ])->orderBy('created_at', 'asc')->get();
        return $this->response($operation);
    }

    public function show($id){
        $operation = Keuangan::where('Keuangan_id',$id)->with('keuangan_detail')->first();
        return $this->responseFirst($operation);
    }

    public function store(Request $request){
        try {            
            $check = $request->validate([
                'keuangan_nama' => 'required',
            ]);
            $data = $request->all();
            $data['keuangan_id'] = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
            $operation = DB::transaction(function () use($data) {
                foreach($data['keuangan_detail_nama'] as $key=>$value){
                    $insert =[
                        "keuangan_detail_keuangan_id" =>$data['keuangan_id'],
                        "keuangan_detail_nama" =>$value,
                    ];

                    KeuanganDetail::create($insert);
                }
                unset($data['keuangan_detail_nama']);unset($data['keuangan_detail_id']);
                return Keuangan::create($data);
            });

            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }


    public function update(Request $request, $id){
        try {
            $check = $request->validate([
                'keuangan_nama' => 'required',
            ]);
            $data = $request->all();
            $operation = DB::transaction(function () use($data, $id) {
                if(isset($data['keuangan_detail_nama'])){   
                    $list_id = array_values(array_filter($data['keuangan_detail_id']));
                    $delete = KeuanganDetail::where('keuangan_detail_keuangan_id', $data['keuangan_id'])->whereNotIn('keuangan_detail_id', $list_id)->get();
    
                    if($delete){
                        foreach($delete as $loop){
                            KeuanganDetail::find($loop['keuangan_detail_id'])->delete();
                        }   
                    }
                    
                    foreach($data['keuangan_detail_nama'] as $key=>$value){
                        $check = KeuanganDetail::where('keuangan_detail_id', $data['keuangan_detail_id'][$key])->first();
    
                        if($check){
                            $check = $check->toArray();
                            $check['keuangan_detail_nama'] = $value;
                            KeuanganDetail::find($data['keuangan_detail_id'][$key])->update($check);
                        } else{
                            $insert =[
                                "keuangan_detail_keuangan_id" =>$data['keuangan_id'],
                                "keuangan_detail_nama" =>$value,
                            ];
                            KeuanganDetail::create($insert);
                        }
                    }
    
                } else{
                    KeuanganDetail::where('keuangan_detail_keuangan_id', $id)->delete();
                }
                unset($data['keuangan_detail_nama']);unset($data['keuangan_detail_id']);unset($data['list_dokumen_id']);
                return Keuangan::find($id)->update($data);
            });
            
            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }


    public function destroy($id){
        try {  
            $operation = Keuangan::find($id)->delete();        
            return $this->responseDelete($operation);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }
}
