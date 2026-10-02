<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kpi_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_period_id')->constrained('kpi_periods')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->string('evidence_path')->nullable();
            $table->string('evidence_name')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kpi_tasks'); }
};
