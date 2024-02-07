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
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id',16)->primary();
            $table->string('user_username',100);
            $table->string('user_nama',100);
            $table->string('user_tempat_lahir',100)->nullable();
            $table->date('user_tanggal_lahir')->nullable();
            $table->string('user_kontak',20)->nullable();
            $table->string('user_alamat',200)->nullable();
            $table->string('password',100);
            $table->string('user_email',100);
            $table->string('user_foto',100)->nullable();
            $table->string('user_role_id',16);
            $table->rememberToken()->nullable();
            $table->integer('user_status');
            $table->timestamps();
            $table->foreign('user_role_id')->references('role_id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
