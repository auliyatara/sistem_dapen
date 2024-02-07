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
        Schema::create('kepesertaan_dokumens', function (Blueprint $table) {
            $table->string('kepesertaan_dokumen_id',16)->primary();
            $table->string('kepesertaan_dokumen_kepesertaan_id',16);
            $table->string('kepesertaan_dokumen_judul',100);
            $table->text('kepesertaan_dokumen_file');
            $table->foreign('kepesertaan_dokumen_kepesertaan_id')->references('kepesertaan_id')->on('kepesertaans')->onDelete('cascade');
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
        Schema::dropIfExists('kepesertaan_dokumens');
    }
};
