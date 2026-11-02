<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE kepesertaans MODIFY kepesertaan_tanggal_lahir DATE NULL, MODIFY kepesertaan_terhitung_mulai_tanggal DATE NULL, MODIFY kepesertaan_terhitung_mulai_tanggal_pensiun DATE NULL, MODIFY kepesertaan_gaji_pokok BIGINT NULL');
    }

    public function down()
    {
        // Preserve missing values rather than inventing dates or salary amounts.
    }
};
