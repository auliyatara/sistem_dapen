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
        Schema::create('investasi_subdatas', function (Blueprint $table) {
            $table->string('investasi_subdata_id',16)->primary();
            $table->string('investasi_subdata_investasi_id',16);
            $table->text('investasi_subdata_nama');
            $table->integer('investasi_subdata_nilai_perolehan')->nullable();
            $table->date('investasi_subdata_tanggal_nilai_perolehan',100)->nullable();
            $table->text('investasi_subdata_hasil_pemantauan')->nullable();
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
        Schema::dropIfExists('investasi_subdatas');
    }
};
