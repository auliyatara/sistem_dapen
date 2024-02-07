<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'role_id' => '753847cfb629c0f9',
                'role_name' => 'Admin',
                'role_status' => 1,
            ],[
                'role_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'role_name' => 'Wakil Direktur Investasi, Manajemen Risiko, dan Umum',
                'role_status' => 1,
            ],[
                'role_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'role_name' => 'Wakil Direktur Keuangan dan Akuntansi',
                'role_status' => 1,
            ],[
                'role_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'role_name' => 'Bidang Keuangan, Investasi dan Umum',
                'role_status' => 1,
            ],[
                'role_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'role_name' => 'Bidang Tata Kelola, Pengendalian Internal, Kepatuhan dan Manajemen Risiko',
                'role_status' => 1,
            ],[
                'role_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'role_name' => 'Bidang Tata Kelola, Pengendalian Internal, Kepatuhan dan Manajemen Risiko',
                'role_status' => 1,
            ],[
                'role_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'role_name' => 'Bidang Kepesertaan dan Sistem Informasi',
                'role_status' => 1,
            ],[
                'role_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'role_name' => 'Bidang Sistem Informasi dan Digitalisai Tata Kelola',
                'role_status' => 1,
            ]
        ]);
    }
}
