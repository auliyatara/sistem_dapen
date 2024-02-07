<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController as Base;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class KepesertaanController extends Controller
{    
    public function index(Base $Base){
        $operation = $Base->UserAccess('kepesertaan');
        if($operation['view']){
            return view('layout.Kepesertaan.index',$operation);
        }
        return abort(403);
    }
}
