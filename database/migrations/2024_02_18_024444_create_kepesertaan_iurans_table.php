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
        Schema::create('kepesertaan_iurans', function (Blueprint $table) {
            $table->string('kepesertaan_iuran_id',16)->primary();
            $table->string('kepesertaan_iuran_kepesertaan_id',16);
            $table->string('kepesertaan_iuran_type',25);
            $table->integer('kepesertaan_iuran_bulan');
            $table->integer('kepesertaan_iuran_tahun');
            $table->bigInteger('kepesertaan_iuran_jumlah')->nullable();
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
        Schema::dropIfExists('kepesertaan_iurans');
    }
};
