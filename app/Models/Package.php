<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'harga',
        'deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga'     => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relasi ke layanan (many-to-many)
    public function services()
    {
        return $this->belongsToMany(Service::class, 'package_services');
    }

    // Helper: format harga
    public function hargaFormatted(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}