<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Aktifitas;
use App\Models\User;
use App\Models\Role;


class ApprovalAktifitasController extends Controller
{
    public function show($filter){
        // $operation = Aktifitas::where('aktifitas_status',$filter)->orderBy('created_at', 'desc')->get();
        $operation = Aktifitas::orderBy('created_at', 'desc')->get();

        foreach($operation as $k=>$v){
            $finduser = User::find($v['aktifitas_user_id']);
            $operation[$k]['user_nama'] = $finduser['user_nama'];
            $operation[$k]['role_name'] = Role::find($finduser['user_role_id'])['role_name'];
        }
        return $this->responseFirst($operation);
    }
}
