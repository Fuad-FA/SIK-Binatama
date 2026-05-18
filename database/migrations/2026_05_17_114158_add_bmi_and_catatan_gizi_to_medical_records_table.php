<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('medical_records', function (Blueprint $table) {
        $table->decimal('berat_badan', 5, 2)->nullable()->after('respirasi');
        $table->decimal('tinggi_badan', 5, 2)->nullable()->after('berat_badan');
        $table->decimal('bmi', 5, 2)->nullable()->after('tinggi_badan');
        $table->text('catatan_gizi')->nullable()->after('bmi');
    });
}

public function down(): void
{
    Schema::table('medical_records', function (Blueprint $table) {
        $table->dropColumn(['berat_badan', 'tinggi_badan', 'bmi', 'catatan_gizi']);
    });
}
};
