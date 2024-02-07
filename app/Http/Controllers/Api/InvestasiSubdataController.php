<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\InvestasiSubdataBulan;
use App\Models\InvestasiSubdata;

class InvestasiSubdataController extends Controller
{
    public function show($id){
        $year = isset($_GET['year'])? $_GET['year']:date("Y");
        $operation = InvestasiSubdata::where('investasi_subdata_id',$id)->with([
            'investasi_subdata_nilai_wajar' => function ($query) use ($year) {
                $query->where('investasi_subdata_bulan_tahun', $year)->orderBy('investasi_subdata_bulan_bulan', 'asc');
            }
        ])->first();
        return $this->responseFirst($operation);
    }

    public function update(Request $request, $id){
        try {
            $data = $request->all();

            $operation = DB::transaction(function () use($data, $id) {
                $investasi_tahun = $data['investasi_subdata_tahun'];
                $investasi_bulan_list = $data['investasi_subdata_bulan_list'];
                unset($data['investasi_subdata_tahun']);unset($data['investasi_subdata_bulan_list']);

                InvestasiSubdataBulan::where('investasi_subdata_bulan_investasi_subdata_id',$id)->where('investasi_subdata_bulan_tahun',$investasi_tahun)->delete();
                foreach($investasi_bulan_list as $key=>$value){
                    $insert =[
                        "investasi_subdata_bulan_investasi_subdata_id" =>$id,
                        "investasi_subdata_bulan_bulan" =>$key+1,
                        "investasi_subdata_bulan_tahun" =>$investasi_tahun,
                        "investasi_subdata_bulan_nilai_wajar" =>$value,
                    ];

                    $process = InvestasiSubdataBulan::create($insert);
                }


                return InvestasiSubdata::find($id)->update($data);
            });

            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }
}
