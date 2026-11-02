<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('programs')
            ->whereIn('program_type', [
                'program_bidang_umum_administrasi',
                'program_bidang_investasi',
            ])
            ->delete();
    }

    public function down(): void
    {
        // Data program yang dihapus memang disiapkan untuk diisi manual,
        // sehingga tidak dibuat ulang saat rollback.
    }
};
