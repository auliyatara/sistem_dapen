<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController as Base;
use Illuminate\Http\Request;

class InvestasiController extends Controller
{
    public function index(Base $Base){
        $operation = $Base->UserAccess('investasi');
        if($operation['view']){
            return view('layout.Investasi.index',$operation);
        }
        return abort(403);
    }
}
