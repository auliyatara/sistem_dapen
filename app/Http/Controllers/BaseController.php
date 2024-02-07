<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BaseController
{
    public function UserAccess($menu_kode){
        $user = User::find(Auth::id());
        $menu = DB::select("SELECT menu_nama
        FROM menus
        INNER JOIN role_accesses ON menu_id = role_access_menu_id
        WHERE role_access_role_id = '".$user['user_role_id']."'
        AND menu_parent = (SELECT menu_id FROM menus WHERE menu_kode ='".$menu_kode."')");
        
        $view=false;
        $edit=false;
        foreach($menu as $value){
            if($value->menu_nama == 'View Data'){
                $view=true;
            } else if ($value->menu_nama == 'Edit Data'){
                $edit=true;
            } 
        }

        $operation = [
            "view"=>$view,
            "edit"=>$edit
        ];
        return $operation;
    }
}
