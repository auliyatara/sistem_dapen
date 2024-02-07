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
        Schema::create('kepesertaans', function (Blueprint $table) {
            $table->string('kepesertaan_id',16)->primary();
            $table->string('kepesertaan_nama',100);
            $table->string('kepesertaan_jenis_kelamin',50);
            $table->string('kepesertaan_nip',50);
            $table->string('kepesertaan_tempat_lahir',50);
            $table->date('kepesertaan_tanggal_lahir');
            $table->string('kepesertaan_status_kepesertaan',150);
            $table->string('kepesertaan_jenis_pensiun',150)->nullable();
            $table->string('kepesertaan_foto',150)->nullable();
            $table->string('kepesertaan_status_jabatan',150);
            $table->date('kepesertaan_terhitung_mulai_tanggal');
            $table->string('kepesertaan_telphone_1',50);
            $table->string('kepesertaan_telphone_2',50);
            $table->string('kepesertaan_email_1',100);
            $table->string('kepesertaan_email_2',100);
            $table->string('kepesertaan_alamat',100);
            $table->string('kepesertaan_link',200)->nullable();
            $table->string('kepesertaan_jenis_pensiun_sekaligus',200)->nullable();
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
        Schema::dropIfExists('kepesertaans');
    }
};
