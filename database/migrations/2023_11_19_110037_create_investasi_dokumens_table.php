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
        Schema::create('investasi_dokumens', function (Blueprint $table) {
            $table->string('investasi_dokumen_id',16)->primary();
            $table->string('investasi_dokumen_investasi_id',16);
            $table->string('investasi_dokumen_judul',100);
            $table->text('investasi_dokumen_file');
            $table->foreign('investasi_dokumen_investasi_id')->references('investasi_id')->on('investasis')->onDelete('cascade');
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
        Schema::dropIfExists('investasi_dokumens');
    }
};
