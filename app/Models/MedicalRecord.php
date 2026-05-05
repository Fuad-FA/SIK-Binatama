<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'gula_darah',
        'kolesterol',
        'asam_urat',
        'tensi_sistolik',
        'tensi_diastolik',
        'suhu',
        'nadi',
        'respirasi',
        'catatan',
        'tanggal_periksa',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_periksa' => 'date',
            'gula_darah'      => 'decimal:2',
            'kolesterol'      => 'decimal:2',
            'asam_urat'       => 'decimal:2',
        ];
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

    // Relasi ke transaksi
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    // Helper: cek status gula darah
    public function statusGulaDarah(): string
    {
        if (!$this->gula_darah) return 'Tidak diukur';
        if ($this->gula_darah < 200) return 'Normal';
        return 'Tinggi';
    }

    // Helper: cek status kolesterol
    public function statusKolesterol(): string
    {
        if (!$this->kolesterol) return 'Tidak diukur';
        if ($this->kolesterol >= 160 && $this->kolesterol <= 200) return 'Normal';
        if ($this->kolesterol < 160) return 'Rendah';
        return 'Tinggi';
    }

    // Helper: cek status asam urat berdasarkan jenis kelamin
    public function statusAsamUrat(): string
    {
        if (!$this->asam_urat) return 'Tidak diukur';
        $jk = $this->patient->jenis_kelamin ?? 'L';
        if ($jk === 'L') {
            return ($this->asam_urat >= 3.4 && $this->asam_urat <= 7) ? 'Normal' : 'Tidak Normal';
        }
        return ($this->asam_urat >= 2.4 && $this->asam_urat <= 6) ? 'Normal' : 'Tidak Normal';
    }

    // Helper: cek status tensi
    public function statusTensi(): string
    {
        if (!$this->tensi_sistolik) return 'Tidak diukur';
        if ($this->tensi_sistolik <= 120 && $this->tensi_diastolik <= 80) return 'Normal';
        if ($this->tensi_sistolik <= 139 || $this->tensi_diastolik <= 89) return 'Prehipertensi';
        return 'Hipertensi';
    }
}