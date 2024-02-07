<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Notifikasi;
use App\Models\Role;


class NotifikasiController extends Controller
{
    public function index(){
        $role_id = Auth::user()->user_role_id;
        $role = Role::where('role_id',$role_id)->first();
        if($role['role_name'] == 'Direktur'){
            $operation = Notifikasi::where('notifikasi_status',1)->whereNull('notifikasi_penerima_user_id')->orderBy('created_at', 'asc')->get();
        } else{
            $id = Auth::user()->user_id;

            $operation = Notifikasi::where('notifikasi_status',1)->where('notifikasi_penerima_user_id',$id)->orderBy('created_at', 'asc')->get();
        }
            
        return $this->response($operation);
    }

    
    public function update(Request $request, $id){
        try {
            $data['notifikasi_status'] = 0;
            $operation = Notifikasi::find($id)->update($data);
            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }
}
