<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\InformasiTambahan;


class InformasiTambahanController extends Controller
{
    public function index(){
        $operation = InformasiTambahan::all();
        return $this->response($operation);
    }

    public function show($id){
        $operation = InformasiTambahan::where('informasi_id',$id)->first();
        return $this->responseFirst($operation);
    }

    public function store(Request $request){
        try {            
            $check = $request->validate([
                'informasi_judul' => 'required|',
                'informasi_deskripsi' => 'required|max:200',
                'informasi_tanggal' => 'required',
            ]);

            $data = $request->all();
            $operation = InformasiTambahan::create($data);
            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }


    public function update(Request $request, $id){
        try {
            $check = $request->validate([
                'informasi_judul' => 'required|',
                'informasi_deskripsi' => 'required|max:200',
                'informasi_tanggal' => 'required',
            ]);

            $data = $request->all();
            unset($data['_token']);
            $operation = InformasiTambahan::where('informasi_id',$id)->update($data);
            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }


    public function destroy($id){
        try {  
            $operation = InformasiTambahan::find($id)->delete();        
            return $this->responseDelete($operation);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }
}
