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
        Schema::create('program_pelaksanaans', function (Blueprint $table) {
            $table->string('program_pelaksanaan_id',16)->primary();
            $table->string('program_pelaksanaan_program_id',16);
            $table->text('program_pelaksanaan_nama');
            $table->timestamps();
            $table->foreign('program_pelaksanaan_program_id')->references('program_id')->on('programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_pelaksanaans');
    }
};
