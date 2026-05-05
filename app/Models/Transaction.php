<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_transaksi',
        'patient_id',
        'user_id',
        'medical_record_id',
        'subtotal',
        'diskon',
        'total',
        'metode_bayar',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'diskon'   => 'decimal:2',
            'total'    => 'decimal:2',
        ];
    }

    // Auto-generate no_transaksi saat dibuat
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Transaction $transaction) {
            $transaction->no_transaksi = self::generateNoTransaksi();
        });
    }

    // Format: TRX-20250502-0001
    private static function generateNoTransaksi(): string
    {
        $prefix = 'TRX-' . date('Ymd') . '-';
        $last   = self::where('no_transaksi', 'like', $prefix . '%')
                      ->orderByDesc('no_transaksi')
                      ->value('no_transaksi');
        $number = $last ? (intval(substr($last, -4)) + 1) : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Relasi ke pasien
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relasi ke staf yang input
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke rekam medis
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    // Relasi ke item-item transaksi
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    // Helper: format total
    public function totalFormatted(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}