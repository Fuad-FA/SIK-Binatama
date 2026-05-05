<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['kode' => 'SVC-001', 'nama' => 'Pijat Full Body 45 Menit',    'harga' => 65000, 'durasi_menit' => 45],
            ['kode' => 'SVC-002', 'nama' => 'Totok Wajah 30 Menit',        'harga' => 25000, 'durasi_menit' => 30],
            ['kode' => 'SVC-003', 'nama' => 'Pijat Kaki dengan Alat',      'harga' => 25000, 'durasi_menit' => 30],
            ['kode' => 'SVC-004', 'nama' => 'Pijat di Kasur Panas',        'harga' => 15000, 'durasi_menit' => 30],
            ['kode' => 'SVC-005', 'nama' => 'Terapi Infra Red',            'harga' => 20000, 'durasi_menit' => 30],
            ['kode' => 'SVC-006', 'nama' => 'Senam Lansia',                'harga' => 15000, 'durasi_menit' => 60],
            ['kode' => 'SVC-007', 'nama' => 'Cek Asam Urat',              'harga' => 15000, 'durasi_menit' => null],
            ['kode' => 'SVC-008', 'nama' => 'Cek Kolesterol',             'harga' => 25000, 'durasi_menit' => null],
            ['kode' => 'SVC-009', 'nama' => 'Cek Gula Darah',             'harga' => 10000, 'durasi_menit' => null],
            ['kode' => 'SVC-010', 'nama' => 'Konsultasi Gizi',            'harga' => 10000, 'durasi_menit' => 30],
            ['kode' => 'SVC-011', 'nama' => 'Cek BMI',                    'harga' => 15000, 'durasi_menit' => null],
            ['kode' => 'SVC-012', 'nama' => 'Cek Antropometri',           'harga' => 5000,  'durasi_menit' => null],
            ['kode' => 'SVC-013', 'nama' => 'Cek Tekanan Darah',          'harga' => 0,     'durasi_menit' => null],
            ['kode' => 'SVC-014', 'nama' => 'Cek Suhu',                   'harga' => 0,     'durasi_menit' => null],
            ['kode' => 'SVC-015', 'nama' => 'Cek Nadi',                   'harga' => 0,     'durasi_menit' => null],
            ['kode' => 'SVC-016', 'nama' => 'Cek Respirasi',              'harga' => 0,     'durasi_menit' => null],
            ['kode' => 'SVC-017', 'nama' => 'Herbal Drink',               'harga' => 0,     'durasi_menit' => null],
            ['kode' => 'SVC-018', 'nama' => 'Pelayanan Informasi Obat',   'harga' => 0,     'durasi_menit' => null],
        ];

        foreach ($services as $service) {
            Service::create(array_merge($service, ['is_active' => true]));
        }
    }
}