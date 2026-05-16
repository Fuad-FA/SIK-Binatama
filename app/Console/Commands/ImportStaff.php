<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportStaff extends Command
{
    protected $signature   = 'staff:import {file : Path ke file Excel}
                                           {--sheet= : Nama sheet (GURU KARYAWAN / SISWA / semua)}
                                           {--dry-run : Simulasi tanpa simpan ke database}';

    protected $description = 'Import data guru dan siswa dari file Excel';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File tidak ditemukan: $file");
            return 1;
        }

        $isDryRun = $this->option('dry-run');
        $sheet    = $this->option('sheet') ?? 'semua';

        if ($isDryRun) {
            $this->warn('=== MODE DRY-RUN: data tidak akan disimpan ===');
        }

        $spreadsheet = IOFactory::load($file);

        if ($sheet === 'semua' || $sheet === 'GURU KARYAWAN') {
            $this->importGuru($spreadsheet, $isDryRun);
        }

        if ($sheet === 'semua' || $sheet === 'SISWA') {
            $this->importSiswa($spreadsheet, $isDryRun);
        }

        $this->info('');
        $this->info('✅ Import selesai!');
        return 0;
    }

    private function importGuru($spreadsheet, bool $isDryRun): void
    {
        $this->info('');
        $this->info('📋 Memproses sheet: GURU KARYAWAN...');

        try {
            $ws = $spreadsheet->getSheetByName('GURU KARYAWAN');
        } catch (\Exception $e) {
            $this->error('Sheet GURU KARYAWAN tidak ditemukan.');
            return;
        }

        $berhasil = 0;
        $duplikat = 0;
        $error    = 0;
        $rows     = $ws->toArray(null, true, true, false);

        // Skip baris header (baris 1)
        foreach (array_slice($rows, 1) as $idx => $row) {
            // Kolom: 0=NO, 1=NAMA, 2=BARCODE, 3=JABATAN
            $nama    = trim($row[1] ?? '');
            $barcode = trim($row[2] ?? '');
            $jabatan = trim($row[3] ?? '');

            // Skip baris kosong
            if (empty($nama) || $nama === 'NAMA') continue;

            // Generate username dari nama: huruf kecil, spasi → underscore, hapus karakter aneh
            $username = $this->generateUsername($nama);

            // Cek duplikat
            $existsByUsername = User::where('username', $username)->exists();
            $existsByName     = User::where('name', $nama)
                                    ->where('role', 'guru')->exists();

            if ($existsByUsername || $existsByName) {
                $this->line("  <comment>SKIP</comment> (duplikat): $nama → @$username");
                $duplikat++;
                continue;
            }

            if (!$isDryRun) {
                try {
                    User::create([
                        'name'                 => $nama,
                        'username'             => $username,
                        'email'                => null,
                        'password'             => Hash::make('password'),
                        'role'                 => 'guru',
                        'jabatan'              => $jabatan ?: 'Guru',
                        'barcode'              => null,
                        'is_active'            => true,
                        'must_change_password' => true,
                    ]);
                    $this->line("  <info>OK</info> $nama → @$username ($jabatan)");
                    $berhasil++;
                } catch (\Exception $e) {
                    $this->line("  <error>ERROR</error> $nama: " . $e->getMessage());
                    $error++;
                }
            } else {
                $this->line("  <info>[DRY]</info> $nama → @$username ($jabatan)");
                $berhasil++;
            }
        }

        $this->info("  Guru: $berhasil berhasil, $duplikat duplikat, $error error");
    }

    private function importSiswa($spreadsheet, bool $isDryRun): void
    {
        $this->info('');
        $this->info('📋 Memproses sheet: SISWA...');

        try {
            $ws = $spreadsheet->getSheetByName('SISWA');
        } catch (\Exception $e) {
            $this->error('Sheet SISWA tidak ditemukan.');
            return;
        }

        $berhasil = 0;
        $duplikat = 0;
        $error    = 0;
        $rows     = $ws->toArray(null, true, true, false);

        // Skip baris header
        foreach (array_slice($rows, 1) as $idx => $row) {
            // Kolom: 0=NO, 1=BARCODE, 2=NAMA
            $barcode = trim($row[1] ?? '');
            $nama    = trim($row[2] ?? '');

            // Skip baris kosong
            if (empty($nama) || empty($barcode) || $nama === 'NAMA') continue;

            // Username siswa = barcode lowercase
            $username = strtolower($barcode);

            // Cek duplikat berdasarkan barcode atau nama+role
            $existsByBarcode  = User::where('barcode', $barcode)->exists();
            $existsByUsername = User::where('username', $username)->exists();

            if ($existsByBarcode || $existsByUsername) {
                $this->line("  <comment>SKIP</comment> (duplikat): $nama [$barcode]");
                $duplikat++;
                continue;
            }

            if (!$isDryRun) {
                try {
                    User::create([
                        'name'                 => $nama,
                        'username'             => $username,
                        'email'                => null,
                        'password'             => Hash::make('siswa'),
                        'role'                 => 'siswa',
                        'jabatan'              => null, // kelas tidak ada di Excel
                        'barcode'              => $barcode,
                        'is_active'            => true,
                        'must_change_password' => true,
                    ]);
                    $this->line("  <info>OK</info> $nama [$barcode]");
                    $berhasil++;
                } catch (\Exception $e) {
                    $this->line("  <error>ERROR</error> $nama: " . $e->getMessage());
                    $error++;
                }
            } else {
                $this->line("  <info>[DRY]</info> $nama [$barcode]");
                $berhasil++;
            }
        }

        $this->info("  Siswa: $berhasil berhasil, $duplikat duplikat, $error error");
    }

    private function generateUsername(string $nama): string
    {
        // Huruf kecil semua
        $username = strtolower($nama);
        // Spasi → underscore
        $username = str_replace(' ', '_', $username);
        // Hapus karakter selain huruf, angka, underscore, tanda hubung
        $username = preg_replace('/[^a-z0-9_\-]/', '', $username);
        // Hapus underscore berlebih
        $username = preg_replace('/_+/', '_', $username);
        $username = trim($username, '_');

        // Pastikan unik — jika sudah ada, tambahkan angka
        $base    = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . '_' . $counter;
            $counter++;
        }

        return $username;
    }
}