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
        Schema::create('menus', function (Blueprint $table) {
            $table->string('menu_id',16)->primary();
            $table->string('menu_kode',100);
            $table->string('menu_nama',100);
            $table->string('menu_route',100)->nullable();
            $table->integer('menu_level');
            $table->integer('menu_order')->nullable();
            $table->string('menu_parent',16)->nullable();
            $table->boolean('menu_has_child')->default(false); 
            $table->integer('menu_status');
            $table->string('menu_icon',100)->nullable();
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
        Schema::dropIfExists('menus');
    }
};
