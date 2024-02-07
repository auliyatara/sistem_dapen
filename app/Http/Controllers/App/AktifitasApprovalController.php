<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController as Base;
use Illuminate\Http\Request;

class AktifitasApprovalController extends Controller
{
    public function index(Base $Base){
        $operation = $Base->UserAccess('aktifitas_approval');
        if($operation['view']){
            return view('layout.Aktifitas Approval.index',$operation);
        }
        return abort(403);
    }
    
}
