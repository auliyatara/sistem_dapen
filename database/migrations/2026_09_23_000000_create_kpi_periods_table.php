<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kpi_periods', function (Blueprint $table) {
            $table->id();
            $table->string('role_id', 16);
            $table->string('type', 20);
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('number');
            $table->date('starts_at');
            $table->date('ends_at');
            $table->timestamps();
            $table->unique(['role_id', 'type', 'year', 'number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kpi_periods');
    }
};
