<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_produk',
        'nama',
        'kategori',
        'harga',
        'harga_by_order',
        'stok',
        'satuan',
        'keterangan',
        'foto',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga'          => 'decimal:2',
            'harga_by_order' => 'boolean',
            'is_active'      => 'boolean',
        ];
    }

    // Relasi ke item transaksi
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'item_id')
                    ->where('item_type', 'product');
    }

    // Helper: label kategori dalam Bahasa Indonesia
    public function labelKategori(): string
    {
        return match($this->kategori) {
            'makanan_minuman' => 'Makanan & Minuman',
            'pembersih'       => 'Pembersih',
            default           => 'Lainnya',
        };
    }

    // Helper: format harga
    public function hargaFormatted(): string
    {
        if ($this->harga_by_order) return 'By Order';
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}