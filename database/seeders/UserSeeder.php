<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_id' => Str::substr((Uuid::uuid4())->getHex(), 0, 16),
            'user_username' => 'user',
            'user_nama' => 'John Doe',
            'password' => Hash::make('@user12345'),
            'user_email' => 'mail@mail.com',
            'user_role_id' => '753847cfb629c0f9',
            'user_status' => 1,
        ]);
    }
}
