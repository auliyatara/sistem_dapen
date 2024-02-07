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
        Schema::create('keuangan_bulans', function (Blueprint $table) {
            $table->string('keuangan_bulan_id',16)->primary();
            $table->string('keuangan_bulan_keuangan_detail_id',16);
            $table->integer('keuangan_bulan_bulan');
            $table->integer('keuangan_bulan_tahun');
            $table->bigInteger('keuangan_bulan_jumlah')->nullable();
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
        Schema::dropIfExists('keuangan_bulans');
    }
};
