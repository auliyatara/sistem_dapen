<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informasi_tambahans', function (Blueprint $table) {
            $table->string('informasi_id',16)->primary();
            $table->date('informasi_tanggal');
            $table->text('informasi_judul');
            $table->text('informasi_deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('informasi_tambahans');
    }
};
