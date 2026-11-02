<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void { DB::statement('ALTER TABLE kpi_periods MODIFY number SMALLINT UNSIGNED NOT NULL'); }
    public function down(): void { /* Keep capacity for existing daily periods. */ }
};
