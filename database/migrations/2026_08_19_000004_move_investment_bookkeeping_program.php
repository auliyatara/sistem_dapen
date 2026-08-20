<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('programs')
            ->where('program_type', 'program_bidang_keuangan')
            ->where('program_nama', 'Membukukan investasi yang akan, sedang dan sudah dilakukan oleh DPP UMM.')
            ->update(['program_type' => 'program_bidang_investasi']);
    }

    public function down(): void
    {
        DB::table('programs')
            ->where('program_type', 'program_bidang_investasi')
            ->where('program_nama', 'Membukukan investasi yang akan, sedang dan sudah dilakukan oleh DPP UMM.')
            ->update(['program_type' => 'program_bidang_keuangan']);
    }
};
