<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::statement('ALTER TABLE kpi_tasks MODIFY progress DECIMAL(4,1) UNSIGNED NOT NULL DEFAULT 0');
    }
    public function down(): void {
        DB::statement('UPDATE kpi_tasks SET progress = FLOOR(progress)');
        DB::statement('ALTER TABLE kpi_tasks MODIFY progress TINYINT UNSIGNED NOT NULL DEFAULT 0');
    }
};
