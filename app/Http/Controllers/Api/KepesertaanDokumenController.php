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
        \Illuminate\Support\Facades\Gate::authorize('manage-kepesertaan');
        $request->validate([
            'kepesertaan_dokumen_judul' => 'required|string|max:100',
            'kepesertaan_dokumen_kepesertaan_id' => 'required|exists:kepesertaans,kepesertaan_id',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,gif,webp,doc,docx,xls,xlsx|max:20480',
        ]);
        $created = [];
        try {
            $operation = DB::transaction(function () use ($request, &$created) {
                $data = $request->only(['kepesertaan_dokumen_judul', 'kepesertaan_dokumen_kepesertaan_id']);
                $data['kepesertaan_dokumen_file'] = \App\Services\ParticipantUpload::store($request->file('file'), 'kepesertaan_dokumen', $created);
                return KepesertaanDokumen::create($data);
            });
            return $this->responseCreate($operation);
        } catch (\Throwable $e) {
            \App\Services\ParticipantUpload::remove($created);
            return $this->responseCreate($e->getMessage(),true);
        }
    }

    public function destroy($id){
        \Illuminate\Support\Facades\Gate::authorize('manage-kepesertaan');
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
