<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasColumn('kepesertaans', 'kepesertaan_nik')) {
            Schema::table('kepesertaans', function (Blueprint $table) {
                $table->string('kepesertaan_nik', 16)->nullable()->index();
            });
        }
    }

    public function down()
    {
        Schema::table('kepesertaans', function (Blueprint $table) { $table->dropColumn('kepesertaan_nik'); });
    }
};
