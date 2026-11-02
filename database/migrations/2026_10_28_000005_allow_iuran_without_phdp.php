<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $column = DB::selectOne("SHOW COLUMNS FROM kepesertaan_iurans LIKE 'kepesertaan_iuran_jumlah'");
        if ($column->Null === 'YES') return;
        $type = $column->Type;
        if (!preg_match('/^(?:bigint|int|mediumint|smallint|tinyint|decimal|double|float)(?:\([0-9,]+\))?(?: unsigned)?$/i', $type)) throw new \RuntimeException('Tipe PHDP tidak dikenali.');
        DB::statement("ALTER TABLE kepesertaan_iurans MODIFY kepesertaan_iuran_jumlah $type NULL DEFAULT NULL");
    }

    public function down()
    {
        // Preserve unknown PHDP; do not replace it with an invented salary.
    }
};
