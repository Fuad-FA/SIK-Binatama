<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama');
            $table->enum('kategori', ['makanan_minuman', 'pembersih', 'lainnya']);
            $table->decimal('harga', 10, 2)->default(0);
            $table->boolean('harga_by_order')->default(false);
            $table->integer('stok')->default(0);
            $table->string('satuan')->default('pcs');
            $table->string('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};