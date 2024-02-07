<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController as Base;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class KeuanganController extends Controller
{    
    public function index(Base $Base){
        $operation = $Base->UserAccess('keuangan');
        if($operation['view']){
            return view('layout.Keuangan.index',$operation);
        }
        return abort(403);
    }
}
