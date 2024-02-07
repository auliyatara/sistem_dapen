<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class RoleAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu_list = DB::table('menus')->get();
        
        // role access admin
        foreach($menu_list as $value){
            DB::table('role_accesses')->insert([
                'role_access_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'role_access_role_id' => '753847cfb629c0f9',
                'role_access_menu_id' => $value->menu_id,
            ]);
        }
    }
}
