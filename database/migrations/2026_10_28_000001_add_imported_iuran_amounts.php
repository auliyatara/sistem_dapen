<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::table('kepesertaan_iurans', function (Blueprint $table) {
        $table->decimal('imported_iuran_peserta', 20, 4)->nullable();
        $table->decimal('imported_iuran_pk', 20, 4)->nullable();
    }); }
    public function down(): void { Schema::table('kepesertaan_iurans', function (Blueprint $table) { $table->dropColumn(['imported_iuran_peserta', 'imported_iuran_pk']); }); }
};
