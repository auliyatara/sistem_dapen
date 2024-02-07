<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            [
                'menu_id' => '2391j231hn323412',
                'menu_kode' => 'dashboard',
                'menu_nama' => 'Dashboard',
                'menu_level' => 1,
                'menu_order' => 1,
                'menu_parent' => '',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'dashboard'
            ],
            [
                'menu_id' => '7283h2131h31b512',
                'menu_kode' => 'program',
                'menu_nama' => 'Program',
                'menu_level' => 1,
                'menu_order' => 2,
                'menu_parent' => '',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'program'
            ],
            [
                'menu_id' => '987dt2131h31w253',
                'menu_kode' => 'aktifitas',
                'menu_nama' => 'Aktifitas',
                'menu_level' => 1,
                'menu_order' => 3,
                'menu_parent' => '',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'aktifitas'
            ],
            [
                'menu_id' => '98y653131h31w253',
                'menu_kode' => 'aktifitas_approval',
                'menu_nama' => 'Approval Aktifitas',
                'menu_level' => 1,
                'menu_order' => 4,
                'menu_parent' => '',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'aktifitas_approval'
            ],
            [
                'menu_id' => '8716289i1029d723',
                'menu_kode' => 'riwayat',
                'menu_nama' => 'Riwayat Aktifitas',
                'menu_level' => 1,
                'menu_order' => 5,
                'menu_parent' => '',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'riwayat'
            ],
            [
                'menu_id' => '0852349i1029d723',
                'menu_kode' => 'informasi_tambahan',
                'menu_nama' => 'Informasi Umum',
                'menu_level' => 1,
                'menu_order' => 6,
                'menu_parent' => '',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'informasi_tambahan'
            ],
            [
                'menu_id' => '2413549i1029d723',
                'menu_kode' => 'kepesertaan',
                'menu_nama' => 'Kepesertaan',
                'menu_level' => 1,
                'menu_order' => 7,
                'menu_parent' => '',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'kepesertaan'
            ],
            [
                'menu_id' => '2315549i1029d723',
                'menu_kode' => 'kepesertaan_link',
                'menu_nama' => 'Kepesertaan Link URL',
                'menu_level' => 1,
                'menu_order' => 8,
                'menu_parent' => '',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'kepesertaan_link'
            ],
            [
                'menu_id' => '51t3er7572e2326a',
                'menu_kode' => 'investasi',
                'menu_nama' => 'Investasi',
                'menu_level' => 1,
                'menu_order' => 9,
                'menu_parent' => '',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'investasi'

            ],
            [
                'menu_id' => '9w02917572e2326a',
                'menu_kode' => 'keuangan',
                'menu_nama' => 'Akuntansi Dan Keuangan',
                'menu_level' => 1,
                'menu_order' => 10,
                'menu_parent' => '',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'keuangan'

            ],
            [
                'menu_id' => '0fecca7572e2326a',
                'menu_kode' => 'access',
                'menu_nama' => 'Manajemen Aplikasi',
                'menu_level' => 1,
                'menu_order' => 20,
                'menu_parent' => '',
                'menu_has_child'=>true,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => ''

            ],
        ]);

        DB::table('menus')->insert([
                [
                    'menu_id' => '36596a7446379c26',
                    'menu_kode' => 'user',
                    'menu_nama' => 'User',
                    'menu_level' => 2,
                    'menu_order' => 22,
                    'menu_parent' => '0fecca7572e2326a',
                    'menu_has_child'=>false,
                    'menu_status' => 1,
                    'menu_icon' => '',
                    'menu_route' => 'users'
                ],
        ]);

        DB::table('menus')->insert([
            [
                'menu_id' => '87696a7446379b57',
                'menu_kode' => 'role_user',
                'menu_nama' => 'Hak Akses',
                'menu_level' => 2,
                'menu_order' => 21,
                'menu_parent' => '0fecca7572e2326a',
                'menu_has_child'=>false,
                'menu_status' => 1,
                'menu_icon' => '',
                'menu_route' => 'roleaccess'
            ],
    ]);

    //     DB::table('menus')->insert([
    //         [
    //             'menu_id' => '1604eb6c31bb84c4',
    //             'menu_kode' => 'user_view',
    //             'menu_nama' => 'View User',
    //             'menu_level' => 3,
    //             'menu_order' => '9.1.1',
    //             'menu_parent' => '36596a7446379c26',
    //             'menu_has_child'=>false,
    //             'menu_status' => 1,
    //             'menu_icon' => '',
    //         ],
    //         [
    //             'menu_id' => '2604eb6c31bb84c4',
    //             'menu_kode' => 'user_edit',
    //             'menu_nama' => 'Edit User',
    //             'menu_level' => 3,
    //             'menu_order' => '9.1.2',
    //             'menu_parent' => '36596a7446379c26',
    //             'menu_has_child'=>false,
    //             'menu_status' => 1,
    //             'menu_icon' => '',
    //         ],
    // ]);

            DB::table('menus')->insert([
                [
                    'menu_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                    'menu_kode' => 'program_direktur',
                    'menu_nama' => 'Program Direktur',
                    'menu_level' => 2,
                    'menu_parent' => '7283h2131h31b512',
                    'menu_has_child'=>false,
                    'menu_status' => 1,
                    'menu_icon' => '',
                    'menu_route' => 'program_direktur'
                ],[
                    'menu_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                    'menu_kode' => 'program_wakil_investasi',
                    'menu_nama' => 'Program Wakil Direktur Investasi, Manajemen Risiko, dan Umum',
                    'menu_level' => 2,
                    'menu_parent' => '7283h2131h31b512',
                    'menu_has_child'=>false,
                    'menu_status' => 1,
                    'menu_icon' => '',
                    'menu_route' => 'program_wakil_investasi'
                ],[
                    'menu_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                    'menu_kode' => 'program_wakil_keuangan',
                    'menu_nama' => 'Program Wakil Direktur Keuangan dan Akuntansi',
                    'menu_level' => 2,
                    'menu_parent' => '7283h2131h31b512',
                    'menu_has_child'=>false,
                    'menu_status' => 1,
                    'menu_icon' => '',
                    'menu_route' => 'program_wakil_keuangan'
                ],[
                    'menu_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                    'menu_kode' => 'program_bidang_keuangan',
                    'menu_nama' => 'Program Bidang Keuangan, Investasi dan Umum',
                    'menu_level' => 2,
                    'menu_parent' => '7283h2131h31b512',
                    'menu_has_child'=>false,
                    'menu_status' => 1,
                    'menu_icon' => '',
                    'menu_route' => 'program_wakil_keuangan'
                ],[
                    'menu_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                    'menu_kode' => 'program_bidang_tata_kelola',
                    'menu_nama' => 'Program Bidang Tata Kelola, Pengendalian Internal, Kepatuhan dan Manajemen Risiko',
                    'menu_level' => 2,
                    'menu_parent' => '7283h2131h31b512',
                    'menu_has_child'=>false,
                    'menu_status' => 1,
                    'menu_icon' => '',
                    'menu_route' => 'program_bidang_tata_kelola'
                ],[
                    'menu_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                    'menu_kode' => 'program_bidang_kepesertaan',
                    'menu_nama' => 'Program Bidang Kepesertaan dan Sistem Informasi',
                    'menu_level' => 2,
                    'menu_parent' => '7283h2131h31b512',
                    'menu_has_child'=>false,
                    'menu_status' => 1,
                    'menu_icon' => '',
                    'menu_route' => 'program_bidang_tata_kelola'
                ],[
                    'menu_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                    'menu_kode' => 'program_bidang_sistem_informasi',
                    'menu_nama' => 'Bidang Sistem Informasi dan Digitalisasi Tata Kelola',
                    'menu_level' => 2,
                    'menu_parent' => '7283h2131h31b512',
                    'menu_has_child'=>false,
                    'menu_status' => 1,
                    'menu_icon' => '',
                    'menu_route' => 'program_bidang_sistem_informasi'
                ],

                
            ]);
    }
}
