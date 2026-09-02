<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User; 
use Illuminate\Support\Facades\DB;

class DapenDatabaseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function data_uji_coba_berhasil_masuk_ke_database()
    {
        // 1. Matikan fungsi otomatis pencatatan activity log khusus untuk pengujian ini
        if (class_exists(\Spatie\Activitylog\ActivityLogger::class)) {
            activity()->disableLogging();
        }

        // 2. Ambil data role pertama dari phpMyAdmin
        $existingRole = DB::table('roles')->first();

        // 3. Jika tabel roles kosong, kita buatkan data tiruan
        if (!$existingRole) {
            DB::table('roles')->insert([
                'role_id'     => 'ROLE01',
                'role_name'   => 'Admin Test',
                'role_status' => 1
            ]);
            $roleId = 'ROLE01';
        } else {
            $roleId = $existingRole->role_id;
        }

        // 4. Mengisi semua kolom wajib tabel users
        $dataUji = [
            'user_id'       => 'USR001',            
            'user_username' => 'peserta_uji',       
            'user_nama'     => 'Budi Santoso',      
            'password'      => bcrypt('password'),  
            'user_email'    => 'budi@example.com',  
            'user_role_id'  => $roleId, 
            'user_status'   => 1,                   
        ];

        // 5. Jalankan perintah simpan data via Model Laravel
        User::create($dataUji);

        // 6. Pastikan data berhasil masuk dan terbaca di database phpMyAdmin
        $this->assertDatabaseHas('users', [
            'user_username' => 'peserta_uji',
            'user_email'    => 'budi@example.com'
        ]);
    }
}
