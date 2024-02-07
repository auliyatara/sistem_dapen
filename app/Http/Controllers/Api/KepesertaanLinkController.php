<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\KepesertaanLink;

class KepesertaanLinkController extends Controller
{
    public function index(){
        $operation = KepesertaanLink::all();
        return $this->response($operation);
    }

    public function update(Request $request, $id){
        try {
            $data = $request->all();
            foreach($data['kepesertaan_link_id'] as $k=>$v){
                $update['kepesertaan_link_url'] = $data['kepesertaan_link_url'][$k];
                $operation = KepesertaanLink::find($v)->update($update);
            }
            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }
}
