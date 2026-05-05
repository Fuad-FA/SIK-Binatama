<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Service;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        // Paket Sehat 1
        $paket1 = Package::create([
            'nama'      => 'Paket Sehat 1',
            'harga'     => 85000,
            'deskripsi' => 'Pijat full body, totok wajah, cek vital sign, herbal drink',
            'is_active' => true,
        ]);
        $paket1->services()->attach(
            Service::whereIn('kode', [
                'SVC-001','SVC-002','SVC-013','SVC-014',
                'SVC-015','SVC-016','SVC-017','SVC-018'
            ])->pluck('id')
        );

        // Paket Sehat 2
        $paket2 = Package::create([
            'nama'      => 'Paket Sehat 2',
            'harga'     => 40000,
            'deskripsi' => 'Terapi infra red, pijat kaki, cek vital sign',
            'is_active' => true,
        ]);
        $paket2->services()->attach(
            Service::whereIn('kode', [
                'SVC-005','SVC-003','SVC-013','SVC-014',
                'SVC-015','SVC-016','SVC-018'
            ])->pluck('id')
        );

        // Paket Sehat 3
        $paket3 = Package::create([
            'nama'      => 'Paket Sehat 3',
            'harga'     => 35000,
            'deskripsi' => 'Pijat kasur panas, pijat kaki, cek vital sign',
            'is_active' => true,
        ]);
        $paket3->services()->attach(
            Service::whereIn('kode', [
                'SVC-004','SVC-003','SVC-013','SVC-014',
                'SVC-015','SVC-016','SVC-018'
            ])->pluck('id')
        );

        // Paket Sehat 4
        $paket4 = Package::create([
            'nama'      => 'Paket Sehat 4',
            'harga'     => 70000,
            'deskripsi' => 'Konsultasi gizi, cek darah lengkap, cek BMI, cek vital sign',
            'is_active' => true,
        ]);
        $paket4->services()->attach(
            Service::whereIn('kode', [
                'SVC-010','SVC-007','SVC-008','SVC-009',
                'SVC-011','SVC-012','SVC-013','SVC-014',
                'SVC-015','SVC-016','SVC-018'
            ])->pluck('id')
        );

        // Paket Sehat 5
        $paket5 = Package::create([
            'nama'      => 'Paket Sehat 5',
            'harga'     => 25000,
            'deskripsi' => 'Senam lansia, cek BMI, cek vital sign',
            'is_active' => true,
        ]);
        $paket5->services()->attach(
            Service::whereIn('kode', [
                'SVC-006','SVC-011','SVC-013','SVC-014',
                'SVC-015','SVC-016','SVC-018'
            ])->pluck('id')
        );
    }
}