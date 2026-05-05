<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@sik-binatama.sch.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'jabatan'  => 'Administrator Sistem',
            'is_active' => true,
            'must_change_password' => false,
        ]);
    }
}