<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Models\KepesertaanDokumen;
use Illuminate\Support\Facades\Storage;

class KepesertaanDokumenController extends Controller
{
    public function show($id){
        $checkdokumenlist = KepesertaanDokumen::where('kepesertaan_dokumen_kepesertaan_id',$id)->get();
        $checkdokumen = KepesertaanDokumen::where('kepesertaan_dokumen_id',$id)->first();
        $operation = $checkdokumenlist->count()==0?$checkdokumen:$checkdokumenlist;
        return $this->responseFirst($operation);
    }

    public function store(Request $request){
        try {
            $request->validate([
                'kepesertaan_dokumen_judul' => 'required',
                'file' => ['sometimes','mimes:jpeg,png,jpg,gif,svg,pdf'],
            ]);

            $data = $request->all();
            $data['kepesertaan_dokumen_file']= 'Kepesertaan Dokumen'.time().'.'.request()->file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('kepesertaan_dokumen/', $request->file('file'), $data['kepesertaan_dokumen_file']);
            $request->file('file')->move(public_path('storage/kepesertaan_dokumen'), $data['kepesertaan_dokumen_file']);

            $operation = KepesertaanDokumen::create($data);
            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }

    public function destroy($id){
        try {  
            $find = KepesertaanDokumen::find($id);
            $path = 'kepesertaan_dokumen/';
            Storage::disk('public')->delete($path.$find['kepesertaan_dokumen_file']);
            Storage::delete($path.$find['kepesertaan_dokumen_file']);
            $operation = $find->delete();  
            return $this->responseDelete($operation);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }
}
