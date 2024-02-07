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
        Schema::create('investasi_bulans', function (Blueprint $table) {
            $table->string('investasi_bulan_id',16)->primary();
            $table->string('investasi_bulan_investasi_id',16);
            $table->integer('investasi_bulan_bulan');
            $table->integer('investasi_bulan_tahun');
            $table->bigInteger('investasi_bulan_nilai_wajar')->nullable();
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
        Schema::dropIfExists('investasi_bulans');
    }
};
