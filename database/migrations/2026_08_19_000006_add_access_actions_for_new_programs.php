<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $programs = [
            'program_bidang_umum_administrasi' => 'umum_administrasi',
            'program_bidang_investasi' => 'investasi',
        ];

        $sourceProgram = DB::table('menus')->where('menu_kode', 'program_bidang_keuangan')->first();

        foreach ($programs as $programKode => $suffix) {
            $program = DB::table('menus')->where('menu_kode', $programKode)->first();
            if (!$program || !$sourceProgram) {
                continue;
            }

            foreach (['view' => 'View Data', 'edit' => 'Edit Data'] as $action => $name) {
                $sourceAction = DB::table('menus')
                    ->where('menu_parent', $sourceProgram->menu_id)
                    ->where('menu_kode', $action . '_program_bidang_keuangan')
                    ->first();

                $actionKode = $action . '_program_bidang_' . $suffix;
                $actionMenu = DB::table('menus')->where('menu_kode', $actionKode)->first();

                if (!$actionMenu) {
                    $actionId = Str::lower(Str::random(16));
                    DB::table('menus')->insert([
                        'menu_id' => $actionId,
                        'menu_kode' => $actionKode,
                        'menu_nama' => $name,
                        'menu_route' => $actionKode,
                        'menu_level' => 4,
                        'menu_order' => $action === 'view' ? 1 : 2,
                        'menu_parent' => $program->menu_id,
                        'menu_has_child' => false,
                        'menu_status' => 1,
                        'menu_icon' => '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $actionMenu = (object) ['menu_id' => $actionId];
                }

                if (!$sourceAction) {
                    continue;
                }

                foreach (DB::table('role_accesses')->where('role_access_menu_id', $sourceAction->menu_id)->pluck('role_access_role_id') as $roleId) {
                    DB::table('role_accesses')->updateOrInsert(
                        [
                            'role_access_role_id' => $roleId,
                            'role_access_menu_id' => $actionMenu->menu_id,
                        ],
                        [
                            'role_access_id' => Str::lower(Str::random(16)),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }

    public function down(): void
    {
        DB::table('menus')->whereIn('menu_kode', [
            'view_program_bidang_umum_administrasi',
            'edit_program_bidang_umum_administrasi',
            'view_program_bidang_investasi',
            'edit_program_bidang_investasi',
        ])->delete();
    }
};
