<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_rm',
        'kode_unik',
        'nama',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'telepon',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    // Auto-generate no_rm dan kode_unik saat pasien baru dibuat
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Patient $patient) {
            $patient->no_rm     = self::generateNoRM();
            $patient->kode_unik = self::generateKodeUnik();
        });
    }

    // Format: RM-202505-0001
    private static function generateNoRM(): string
    {
        $prefix = 'RM-' . date('Ym') . '-';
        $last   = self::where('no_rm', 'like', $prefix . '%')
                      ->orderByDesc('no_rm')
                      ->value('no_rm');
        $number = $last ? (intval(substr($last, -4)) + 1) : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Kode unik 8 karakter huruf kapital untuk login pasien
    private static function generateKodeUnik(): string
    {
        do {
            $kode = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (self::where('kode_unik', $kode)->exists());

        return $kode;
    }

    // Relasi ke user yang menginput
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke rekam medis
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    // Relasi ke transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Helper: ambil rekam medis terakhir
    public function latestRecord()
    {
        return $this->hasOne(MedicalRecord::class)->latestOfMany();
    }
}