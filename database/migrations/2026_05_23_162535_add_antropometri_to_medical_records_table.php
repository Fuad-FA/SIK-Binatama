<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('medical_records', function (Blueprint $table) {
        $table->decimal('bb', 5, 2)->nullable()->after('bmi');           // Berat Badan
        $table->decimal('tb', 5, 2)->nullable()->after('bb');            // Tinggi/Panjang Badan
        $table->decimal('lila', 5, 2)->nullable()->after('tb');          // Lingkar Lengan Atas
        $table->decimal('lingkar_kepala', 5, 2)->nullable()->after('lila');
        $table->decimal('lingkar_perut', 5, 2)->nullable()->after('lingkar_kepala');
    });
}

public function down(): void
{
    Schema::table('medical_records', function (Blueprint $table) {
        $table->dropColumn(['bb', 'tb', 'lila', 'lingkar_kepala', 'lingkar_perut']);
    });
}
};
