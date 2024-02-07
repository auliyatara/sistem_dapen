<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KeuanganBulan;
use Illuminate\Support\Facades\DB;

class KeuanganBulanController extends Controller
{
    public function show(Request $request,$id){
        $data=$request->all();
        $operation = KeuanganBulan::where('keuangan_bulan_keuangan_detail_id',$id)->where('keuangan_bulan_tahun',$data['year'])->orderby('keuangan_bulan_bulan')->get();
        return $this->response($operation);
    }

    public function store(Request $request){
        try {            
            $check = $request->validate([
                'keuangan_detail_id' => 'required',
                'keuangan_tahun' => 'required',
            ]);
            $data = $request->all();
            $operation = DB::transaction(function () use($data) {
                KeuanganBulan::where('keuangan_bulan_keuangan_detail_id',$data['keuangan_detail_id'])->where('keuangan_bulan_tahun',$data['keuangan_tahun'])->delete();   
                foreach($data['keuangan_bulan_list'] as $key=>$value){
                    $insert =[
                        "keuangan_bulan_keuangan_detail_id" =>$data['keuangan_detail_id'],
                        "keuangan_bulan_bulan" =>$key+1,
                        "keuangan_bulan_tahun" =>$data['keuangan_tahun'],
                        "keuangan_bulan_jumlah" =>$value,
                    ];

                    $process = KeuanganBulan::create($insert);
                }

                return $process;
            });

            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }
}
