<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\menu;

class MenuController extends Controller
{
    public function index(){
        $operation = Menu::orderByRaw('ISNULL(menu_order), menu_order')->get();
        return $this->responseFirst($operation);
    }

    public function show($id){
        $distinct = $menu = DB::select("SELECT DISTINCT menu_level from menus order by menu_level desc");

        $operation = [];
        foreach($distinct as $value){
            $loop[$value->menu_level] = DB::select(" SELECT menu_id as id, menu_nama as text,menu_parent as parent_id,role_access_id as state,menu_crud FROM menus LEFT JOIN role_accesses ON menu_id = role_access_menu_id AND role_access_role_id = '".$id."' where menu_level = ".$value->menu_level." ORDER BY menu_order,menus.created_at");
        }

        foreach($loop as $kloop => $vloop){
            $checked = $vloop;
            if(count($loop)>1){
                unset($loop[$kloop]);

                foreach($checked as $kchecked=>$vchecked){
                    if($vchecked->state && $vchecked->menu_crud){
                        $vchecked->state = (object)[
                            "selected"=> true
                        ];
                    } else{
                        $vchecked->state = (object)[
                            "selected"=> false
                        ];
                    }

                    foreach($loop as $kunset=>$vunset){
                        $column = array_column($vunset, 'id');              
                        $foundKey = array_search($vchecked->parent_id, $column);

                        if($foundKey != ''){
                            $push = $vchecked;

                            $index = isset($loop[$kunset][$foundKey]->children)?count($loop[$kunset][$foundKey]->children):0;
                            $loop[$kunset][$foundKey]->children[$index] = $push; 
                        }
                    }
                }
            }  else{
                foreach($checked as $kchecked=>$vchecked){
                    if($vchecked->state && $vchecked->menu_crud){
                        $vchecked->state = (object)[
                            "selected"=> true
                        ];
                    } else{
                        $vchecked->state = (object)[
                            "selected"=> false
                        ];
                    }

                }
            } 
        }

        $operation = (object)[
            "text"=>"Sim Dana Pensiun",
            "children"=>$loop[1]
        ];
        // $operation = $loop;
        return $this->responseFirst($operation);
    }
}
