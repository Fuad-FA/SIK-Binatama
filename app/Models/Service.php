<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'harga',
        'durasi_menit',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga'     => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relasi ke paket layanan (many-to-many)
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_services');
    }

    // Helper: format harga
    public function hargaFormatted(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}