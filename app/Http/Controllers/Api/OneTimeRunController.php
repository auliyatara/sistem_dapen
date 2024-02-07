<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use App\Models\menu;

class OneTimeRunController extends Controller
{
    public function index(){
        return "Comment This Line To Run";
        $menu = Menu::all();
        $operation = DB::transaction(function () use($menu) {
            foreach($menu as $value){
                if(!$value['menu_has_child']){
                    $insert = Menu::create([
                        'menu_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                        'menu_kode' => 'view_'.$value['menu_kode'],
                        'menu_nama' => 'View Data',
                        'menu_level' => 4,
                        'menu_order' => 1,
                        'menu_parent' => $value['menu_id'],
                        'menu_has_child'=>false,
                        'menu_status' => 1,
                        'menu_icon' => '',
                        'menu_crud' => 1,
                        'menu_route' => ''
                    ]);

                    $update = Menu::create([
                        'menu_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                        'menu_kode' => 'edit_'.$value['menu_kode'],
                        'menu_nama' => 'Edit Data',
                        'menu_level' => 4,
                        'menu_order' => 2,
                        'menu_parent' => $value['menu_id'],
                        'menu_has_child'=>false,
                        'menu_status' => 1,
                        'menu_icon' => '',
                        'menu_crud' => 1,
                        'menu_route' => ''
                    ]);
                }
               
            }
           
            return $insert;
        });

        
        return $this->responseFirst($operation);
    }
}
