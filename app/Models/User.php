<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // protected $fillable = [
    //     'name',
    //     'username',
    //     'email',
    //     'password',
    //     'role',
    //     'barcode',
    //     'jabatan',
    //     'is_active',
    //     'must_change_password',
    //     'last_login',
        
    // ];

    protected $fillable = [
    'name',
    'username',
    'email',
    'password',
    'role',
    'barcode',
    'jabatan',
    'is_active',
    'must_change_password',
    'last_login',
    'login_attempts',
    'locked_at',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'last_login'        => 'datetime',
    //         'password'          => 'hashed',
    //         'is_active'         => 'boolean',
    //         'must_change_password' => 'boolean',
    //     ];
    // }

protected function casts(): array
{
    return [
        'email_verified_at'    => 'datetime',
        'last_login'           => 'datetime',
        'locked_at'            => 'datetime',
        'password'             => 'hashed',
        'is_active'            => 'boolean',
        'must_change_password' => 'boolean',
    ];
}

    // Relasi: satu user bisa input banyak pasien
    public function patients()
    {
        return $this->hasMany(Patient::class, 'created_by');
    }

    // Relasi: satu user bisa punya banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
// Relasi ke rekam medis yang diinput user ini
public function medicalRecords()
{
    return $this->hasMany(MedicalRecord::class);
}
    // Relasi: satu user bisa punya banyak activity log
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Helper: cek apakah user adalah admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Helper: cek apakah user adalah staf (guru atau siswa)
    public function isStaff(): bool
    {
        return in_array($this->role, ['guru', 'siswa']);
    }

    // Cek apakah akun sedang dikunci
public function isLocked(): bool
{
    return $this->locked_at !== null;
}

// Tambah percobaan login
public function incrementLoginAttempts(): void
{
    $this->increment('login_attempts');

    // refresh data terbaru dari database
    $this->refresh();

    if ($this->login_attempts >= 3) {
        $this->update([
            'locked_at' => now()
        ]);
    }
}

// Reset percobaan login setelah berhasil login
public function resetLoginAttempts(): void
{
    $this->update([
        'login_attempts' => 0,
        'locked_at'      => null,
    ]);
}
}