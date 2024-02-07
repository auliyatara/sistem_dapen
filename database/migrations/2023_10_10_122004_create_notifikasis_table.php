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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->string('notifikasi_id',16)->primary();
            $table->string('notifikasi_judul',100);
            $table->string('notifikasi_keterangan',100);
            $table->integer('notifikasi_status');
            $table->string('notifikasi_url',100);
            $table->string('notifikasi_penerima_user_id',16)->nullable();
            $table->string('notifikasi_pengirim_user_id',16);
            $table->string('notifikasi_pengirim_user_nama',100);
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
        Schema::dropIfExists('notifikasis');
    }
};
