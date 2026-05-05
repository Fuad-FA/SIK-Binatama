<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->enum('role', ['admin', 'guru', 'siswa'])->default('guru')->after('email');
            $table->string('barcode')->nullable()->after('role');
            $table->string('jabatan')->nullable()->after('barcode');
            $table->boolean('is_active')->default(true)->after('jabatan');
            $table->boolean('must_change_password')->default(false)->after('is_active');
            $table->timestamp('last_login')->nullable()->after('must_change_password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'role', 'barcode', 'jabatan',
                'is_active', 'must_change_password', 'last_login'
            ]);
        });
    }
};