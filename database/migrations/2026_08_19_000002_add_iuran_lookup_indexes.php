<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kepesertaan_iurans', function (Blueprint $table) {
            $table->index(
                ['kepesertaan_iuran_tahun', 'kepesertaan_iuran_type', 'kepesertaan_iuran_kepesertaan_id', 'kepesertaan_iuran_bulan'],
                'iuran_year_type_peserta_bulan_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('kepesertaan_iurans', function (Blueprint $table) {
            $table->dropIndex('iuran_year_type_peserta_bulan_index');
        });
    }
};
