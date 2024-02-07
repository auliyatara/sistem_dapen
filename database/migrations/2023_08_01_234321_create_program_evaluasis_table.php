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
        Schema::create('program_evaluasis', function (Blueprint $table) {
            $table->string('program_evaluasi_id',16)->primary();
            $table->string('program_evaluasi_program_id',16);
            $table->text('program_evaluasi_nama');
            $table->timestamps();
            $table->foreign('program_evaluasi_program_id')->references('program_id')->on('programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_evaluasis');
    }
};
