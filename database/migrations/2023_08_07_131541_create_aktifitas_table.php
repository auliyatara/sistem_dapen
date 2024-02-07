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
        Schema::create('aktifitas', function (Blueprint $table) {
            $table->string('aktifitas_id',16)->primary();
            $table->string('aktifitas_nama',100);
            $table->string('aktifitas_file',150);
            $table->text('aktifitas_komentar')->nullable();
            $table->integer('aktifitas_status')->default(0);
            $table->string('aktifitas_user_id',16);
            $table->timestamps();
            $table->foreign('aktifitas_user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aktifitas');
    }
};
