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
        Schema::create('aktifitas_dokumens', function (Blueprint $table) {
            $table->string('aktifitas_dokumen_id',16)->primary();
            $table->string('aktifitas_dokumen_aktifitas_id',16);
            $table->string('aktifitas_dokumen_judul',100);
            $table->integer('aktifitas_dokumen_status')->default(0);
            $table->text('aktifitas_dokumen_file');
            $table->text('aktifitas_dokumen_komentar')->nullable();
            $table->foreign('aktifitas_dokumen_aktifitas_id')->references('aktifitas_id')->on('aktifitas')->onDelete('cascade');
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
        Schema::dropIfExists('aktifitas_dokumens');
    }
};
