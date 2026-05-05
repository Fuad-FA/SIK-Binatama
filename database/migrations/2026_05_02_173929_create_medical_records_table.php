<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('gula_darah', 6, 2)->nullable();
            $table->decimal('kolesterol', 6, 2)->nullable();
            $table->decimal('asam_urat', 5, 2)->nullable();
            $table->integer('tensi_sistolik')->nullable();
            $table->integer('tensi_diastolik')->nullable();
            $table->decimal('suhu', 4, 1)->nullable();
            $table->integer('nadi')->nullable();
            $table->integer('respirasi')->nullable();
            $table->text('catatan')->nullable();
            $table->date('tanggal_periksa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};