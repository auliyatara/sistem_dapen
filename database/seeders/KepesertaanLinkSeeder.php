<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class KepesertaanLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kepesertaan_links')->insert([
            [
                'kepesertaan_link_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'kepesertaan_link_nama' => 'Peserta',
                'kepesertaan_link_url' => '',
            ],
            [
                'kepesertaan_link_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'kepesertaan_link_nama' => 'Pensiunan',
                'kepesertaan_link_url' => '',
            ],
            [
                'kepesertaan_link_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
                'kepesertaan_link_nama' => 'Pensiun Ditunda',
                'kepesertaan_link_url' => '',
            ]
        ]);
    }
}
