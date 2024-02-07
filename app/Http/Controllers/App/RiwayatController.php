<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController as Base;

use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Base $Base){
        $operation = $Base->UserAccess('riwayat');
        if($operation['view']){
            return view('layout.Riwayat.index',$operation);
        }
        return abort(403);
    }
}
