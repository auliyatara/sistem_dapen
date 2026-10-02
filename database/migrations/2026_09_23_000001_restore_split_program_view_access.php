<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        DB::transaction(function () {
            $source = DB::table('menus')->where('menu_kode', 'program_bidang_keuangan')->first();
            if (!$source) {
                return;
            }
            $view = DB::table('menus')->where('menu_parent', $source->menu_id)->where('menu_nama', 'View Data')->first();
            if (!$view) {
                return;
            }
            $roles = DB::table('role_accesses')->where('role_access_menu_id', $view->menu_id)
                ->distinct()->pluck('role_access_role_id');
            foreach (['program_bidang_investasi', 'program_bidang_umum_administrasi'] as $code) {
                $menu = DB::table('menus')->where('menu_kode', $code)->first();
                if (!$menu) {
                    continue;
                }
                $action = DB::table('menus')->where('menu_parent', $menu->menu_id)->where('menu_nama', 'View Data')->first();
                if (!$action) {
                    continue;
                }
                foreach ($roles as $roleId) {
                    foreach ([$menu->menu_id, $action->menu_id] as $menuId) {
                        $access = ['role_access_role_id' => $roleId, 'role_access_menu_id' => $menuId];
                        if (!DB::table('role_accesses')->where($access)->exists()) {
                            DB::table('role_accesses')->insert(array_merge($access, [
                                'role_access_id' => Str::lower(Str::random(16)),
                                'created_at' => now(), 'updated_at' => now(),
                            ]));
                        }
                    }
                }
            }
        });
    }

    public function down(): void
    {
        // Preserve access: restored grants cannot be distinguished from later administrator choices.
    }
};
