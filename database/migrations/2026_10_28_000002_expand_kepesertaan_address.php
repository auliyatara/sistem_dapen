<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE kepesertaans MODIFY kepesertaan_alamat TEXT NOT NULL');
    }

    public function down()
    {
        // Keep full addresses: narrowing this column could discard imported data.
    }
};
