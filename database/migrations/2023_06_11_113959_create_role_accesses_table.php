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
        Schema::create('role_accesses', function (Blueprint $table) {
            $table->string('role_access_id',16)->primary();
            $table->string('role_access_role_id',16);
            $table->string('role_access_menu_id',16);
            $table->timestamps();
            $table->foreign('role_access_role_id')->references('role_id')->on('roles')->onDelete('cascade');
            $table->foreign('role_access_menu_id')->references('menu_id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_accesses');
    }
};
