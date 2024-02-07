<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController as Base;

class KepesertaanLinkController extends Controller
{    
    public function index(Base $Base){
        $operation = $Base->UserAccess('kepesertaan_link');
        if($operation['view']){
            return view('layout.KepesertaanLink.index',$operation);
        }
        return abort(403);
    }
}
