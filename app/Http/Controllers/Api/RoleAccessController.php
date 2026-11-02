<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoleAccess;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\menu;
use App\Models\MenuStorage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class RoleAccessController extends Controller
{
    public function index(){
        $user = User::find(Auth::id());

        $distinct = DB::select("SELECT DISTINCT menu_level from menus where menu_level<=2 order by menu_level desc");
        $loop = [];

        foreach($distinct as $value){
            $loop[$value->menu_level] = DB::table('menus')
                ->where('menu_level', $value->menu_level)
                ->where(function ($query) use ($user) {
                    $query->whereIn('menu_id', function ($access) use ($user) {
                        $access->select('role_access_menu_id')->from('role_accesses')
                            ->where('role_access_role_id', $user->user_role_id);
                    })->orWhere(function ($parents) use ($user) {
                        $parents->where('menu_level', 1)->where('menu_has_child', 1)
                            ->whereIn('menu_id', function ($children) use ($user) {
                                $children->select('child.menu_parent')->from('menus as child')
                                    ->join('role_accesses as access', 'child.menu_id', '=', 'access.role_access_menu_id')
                                    ->where('child.menu_level', 2)
                                    ->where('access.role_access_role_id', $user->user_role_id);
                            });
                    })->orWhereIn('menu_kode', ['kepesertaan', 'kepesertaan_link', 'iuran', 'total'])
                        ->orWhereIn('menu_id', function ($parents) {
                            $parents->select('visible.menu_parent')->from('menus as visible')
                                ->whereIn('visible.menu_kode', ['kepesertaan', 'kepesertaan_link', 'iuran', 'total']);
                        });
                })
                ->orderBy('menu_order')->orderBy('menus.created_at')->get()->all();
        }

        foreach($loop as $kloop => $vloop){
            $checked = $vloop;
            if(count($loop)>1){
                unset($loop[$kloop]);
                foreach($checked as $kchecked=>$vchecked){
                    foreach($loop as $kunset=>$vunset){
                        $column = array_column($vunset, 'menu_id');              
                        $foundKey = array_search($vchecked->menu_parent, $column);

                        if($foundKey !== false){
                            $push = $vchecked;
                            $index = isset($loop[$kunset][$foundKey]->children)?count($loop[$kunset][$foundKey]->children):0;
                            $loop[$kunset][$foundKey]->children[$index] = $push; 
                        }
                    }
                }
            } 
        }

        $operation = $loop[1] ?? [];
        return $this->response($operation);
    }

    public function show($id){
        $operation = RoleAccess::where('role_access_role_id',$id)->get();
        return $this->response($operation);
    }

    public function store(Request $request){
        try {            
            $data = $request->all();

            $check = $request->validate([
                'role_id' => 'required',
            ], [
                'role_id.required' => 'Anda Belum Mengisi Hak akses',
            ]);

            $decode = base64_decode(json_encode($data['role_id'],true));
            $data['role_id'] = explode(',',$decode);

            $operation = DB::transaction(function () use($data) {
                RoleAccess::where('role_access_role_id',$data['user_id'])->delete();

                $reinsert = [];
                foreach($data['role_id'] as $value){
                    if($value!="j1_1"){
                        $menu = Menu::find($value);
                        if($menu['menu_nama'] == "View Data"){
                            if(!in_array($menu['menu_parent'], $data['role_id'])){
                                array_push($reinsert, $menu['menu_parent']);
                            }
                        }

                        $push['role_access_role_id'] = $data['user_id'];
                        $push['role_access_menu_id'] = $value;
                        $return = RoleAccess::create($push);
                    }       
                }

                foreach($reinsert as $value){
                        $push['role_access_role_id'] = $data['user_id'];
                        $push['role_access_menu_id'] = $value;
                        $return = RoleAccess::create($push);   
                }

                $menuStorage = MenuStorage::first();
                $menuStorageUpdate['menu_storage_value'] = Str::substr((Uuid::uuid4())->getHex(), 0, 16);
                MenuStorage::find($menuStorage['menu_storage_id'])->update($menuStorageUpdate);
                return $return;
            });
            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }
}
