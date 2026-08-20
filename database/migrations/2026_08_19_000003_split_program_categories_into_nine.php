<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const PROGRAM_PARENT = '7283h2131h31b512';

    public function up(): void
    {
        $menus = [
            'program_direktur' => ['Program Direktur', 31],
            'program_wakil_investasi' => ['Program Wakil Direktur Investasi dan Manajemen Risiko', 32],
            'program_wakil_keuangan' => ['Program Wakil Direktur Bidang Keuangan dan Akuntansi', 33],
            'program_wakil_kepatuhan_tata_kelola_sdm' => ['Program Wakil Direktur Bidang Kepatuhan, Tata Kelola dan SDM', 34],
            'program_bidang_keuangan' => ['Program Bidang Keuangan', 35],
            'program_bidang_kepesertaan' => ['Program Bidang Kepesertaan', 36],
            'program_bidang_sistem_informasi' => ['Program Bidang Sistem Informasi dan Digitalisasi Tata Kelola', 37],
            'program_bidang_umum_administrasi' => ['Program Bidang Umum dan Administrasi', 38],
            'program_bidang_investasi' => ['Program Bidang Investasi', 39],
        ];

        // Kategori tata kelola lama menjadi tanggung jawab Wakil Direktur Kepatuhan,
        // Tata Kelola dan SDM.
        DB::table('programs')
            ->where('program_type', 'program_bidang_tata_kelola')
            ->update(['program_type' => 'program_wakil_kepatuhan_tata_kelola_sdm']);

        DB::table('menus')
            ->where('menu_kode', 'program_bidang_tata_kelola')
            ->update(['menu_kode' => 'program_wakil_kepatuhan_tata_kelola_sdm']);

        // Pecah data yang sebelumnya digabung dalam Bidang Keuangan, Investasi, dan Umum.
        DB::table('programs')
            ->where('program_type', 'program_bidang_keuangan')
            ->whereIn('program_nama', [
                'Membukukan investasi yang akan, sedang dan sudah dilakukan oleh DPP UMM',
                'Membuat laporan hasil pengembangan investasi DPP UMM.',
                'Membuat laporan portofolio investasi setiap bulan.',
                'Memantau perkembangan investasi yang dimiliki DPP UMM.',
                'Melaporkan laporan investasi dan kegiatan operasional secara akurat.',
            ])
            ->update(['program_type' => 'program_bidang_investasi']);

        DB::table('programs')
            ->where('program_type', 'program_bidang_keuangan')
            ->whereIn('program_nama', [
                'Melaksanakan tugas perkantoran.',
                'Melaksanakan tugas tatalaksana dan kerumahtanggaan kantor.',
            ])
            ->update(['program_type' => 'program_bidang_umum_administrasi']);

        foreach ($menus as $kode => [$nama, $order]) {
            $existing = DB::table('menus')->where('menu_kode', $kode)->first();

            if ($existing) {
                DB::table('menus')->where('menu_id', $existing->menu_id)->update([
                    'menu_nama' => $nama,
                    'menu_route' => $kode,
                    'menu_level' => 2,
                    'menu_parent' => self::PROGRAM_PARENT,
                    'menu_order' => $order,
                    'menu_status' => 1,
                    'updated_at' => now(),
                ]);
                continue;
            }

            $sourceMenu = DB::table('menus')->where('menu_kode', 'program_bidang_keuangan')->first();
            $menuId = Str::lower(Str::random(16));

            DB::table('menus')->insert([
                'menu_id' => $menuId,
                'menu_kode' => $kode,
                'menu_nama' => $nama,
                'menu_route' => $kode,
                'menu_level' => 2,
                'menu_order' => $order,
                'menu_parent' => self::PROGRAM_PARENT,
                'menu_has_child' => false,
                'menu_status' => 1,
                'menu_icon' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Semua role yang sebelumnya mendapat akses ke program gabungan
            // tetap dapat mengakses kategori baru hasil pemisahan ini.
            if ($sourceMenu) {
                $roles = DB::table('role_accesses')
                    ->where('role_access_menu_id', $sourceMenu->menu_id)
                    ->pluck('role_access_role_id');

                foreach ($roles as $roleId) {
                    DB::table('role_accesses')->updateOrInsert(
                        [
                            'role_access_role_id' => $roleId,
                            'role_access_menu_id' => $menuId,
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
        DB::table('programs')
            ->where('program_type', 'program_wakil_kepatuhan_tata_kelola_sdm')
            ->update(['program_type' => 'program_bidang_tata_kelola']);

        DB::table('programs')
            ->whereIn('program_type', ['program_bidang_investasi', 'program_bidang_umum_administrasi'])
            ->update(['program_type' => 'program_bidang_keuangan']);

        DB::table('menus')->whereIn('menu_kode', [
            'program_wakil_kepatuhan_tata_kelola_sdm',
            'program_bidang_umum_administrasi',
            'program_bidang_investasi',
        ])->delete();
    }
};
