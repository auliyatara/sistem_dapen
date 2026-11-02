<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $column = DB::selectOne("SHOW COLUMNS FROM kepesertaans LIKE 'kepesertaan_nik'");
        preg_match('/^(?:var)?char\((\d+)\)$/i', $column->Type, $match);
        $length = max(50, (int) ($match[1] ?? 50));
        DB::statement("ALTER TABLE kepesertaans MODIFY kepesertaan_nik VARCHAR($length) NULL DEFAULT NULL");
    }

    public function down()
    {
        // Retain missing NIK values rather than generating fictitious identities.
    }
};
