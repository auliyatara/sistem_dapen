<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Role;
use App\Models\Menu;
use App\Models\User;
use App\Models\RoleAccess;


class RoleController extends Controller
{
    public function index(){
        $operation = Role::where('role_status',1)->get();
        return $this->response($operation);
    }

    public function show($kode){
        $check = Menu::where('menu_kode',$kode)->first();
        $auth = Auth::user()->user_role_id;

        $operation = RoleAccess::where('role_access_menu_id',$check['menu_id'])->where('role_access_role_id',$auth)->get();
        return $this->response($operation);
    }

    public function read($id){
        $operation = Role::find($id)->toArray();
        return $this->response($operation);
    }

    public function store(Request $request){
        try {            
            $data = $request->all();
            $check = $request->validate([
                'role_name' => 'required',
            ], [
                'role_name.required' => 'Anda Belum Mengisi Nama Hak akses',
            ]);

            $operation = Role::create($data);
            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }

    public function update(Request $request, $id){
        try {
            $check = $request->validate([
                'role_name' => 'required',
            ], [
                'role_name.required' => 'Anda Belum Mengisi Nama Hak akses',
            ]);

            $data = $request->all();
            unset($data['_token']);
            
            $operation = Role::where('role_id',$id)->update($data);
            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }

}
