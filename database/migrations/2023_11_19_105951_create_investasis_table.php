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
        Schema::create('investasis', function (Blueprint $table) {
            $table->string('investasi_id',16)->primary();
            $table->string('investasi_jenis',100);
            $table->integer('investasi_nilai_perolehan');
            $table->date('investasi_tanggal_nilai_perolehan',100);
            $table->text('investasi_hasil_pemantauan');
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
        Schema::dropIfExists('investasis');
    }
};
