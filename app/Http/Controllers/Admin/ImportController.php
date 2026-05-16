<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    public function showForm()
    {
        return view('admin.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file'  => 'required|file|mimes:xlsx,xls,csv|max:5120',
            'sheet' => 'required|in:guru,siswa,semua',
        ], [
            'file.required' => 'File wajib diupload.',
            'file.mimes'    => 'Format file harus xlsx, xls, atau csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
            'sheet.required'=> 'Pilih sheet yang akan diimport.',
        ]);

        // $path        = $request->file('file')->store('imports', 'local');
        // $fullPath    = storage_path('app/' . $path);
        $file = $request->file('file');

$filename = time() . '_' . $file->getClientOriginalName();

$file->move(storage_path('app/imports'), $filename);

$fullPath = storage_path('app/imports/' . $filename);
        $sheet       = $request->sheet;
        $isDryRun    = $request->boolean('dry_run');

        try {
            $spreadsheet = IOFactory::load($fullPath);
        } catch (\Exception $e) {
            return back()->with('error', 'File tidak bisa dibaca: ' . $e->getMessage());
        }

        $results = [
            'guru'  => ['berhasil' => 0, 'duplikat' => 0, 'error' => 0, 'log' => []],
            'siswa' => ['berhasil' => 0, 'duplikat' => 0, 'error' => 0, 'log' => []],
        ];

        if ($sheet === 'guru' || $sheet === 'semua') {
            $this->importGuru($spreadsheet, $isDryRun, $results['guru']);
        }

        if ($sheet === 'siswa' || $sheet === 'semua') {
            $this->importSiswa($spreadsheet, $isDryRun, $results['siswa']);
        }

        // Hapus file setelah diproses
        // \Storage::disk('local')->delete($path);
        if (file_exists($fullPath)) {
    unlink($fullPath);
}

        return back()
            ->with('import_results', $results)
            ->with('dry_run', $isDryRun)
            ->with('success', $isDryRun
                ? 'Simulasi selesai — tidak ada data yang disimpan.'
                : 'Import selesai!');
    }

    // private function importGuru($spreadsheet, bool $isDryRun, array &$result): void
    // {
    //     try {
    //         $ws   = $spreadsheet->getSheetByName('GURU KARYAWAN');
    //         $rows = $ws->toArray(null, true, true, false);
    //     } catch (\Exception $e) {
    //         $result['log'][] = ['type' => 'error', 'msg' => 'Sheet GURU KARYAWAN tidak ditemukan.'];
    //         return;
    //     }

    //     foreach (array_slice($rows, 1) as $row) {
    //         $nama    = trim($row[1] ?? '');
    //         $jabatan = trim($row[3] ?? '');

    //         if (empty($nama) || $nama === 'NAMA') continue;

    //         $username = $this->generateUsername($nama);

    //         $existsByName = User::where('name', $nama)->where('role', 'guru')->exists();
    //         if ($existsByName) {
    //             $result['duplikat']++;
    //             $result['log'][] = ['type' => 'skip', 'msg' => "$nama (duplikat)"];
    //             continue;
    //         }

    //         if (!$isDryRun) {
    //             try {
    //                 User::create([
    //                     'name'                 => $nama,
    //                     'username'             => $username,
    //                     'email'                => null,
    //                     'password'             => Hash::make('password'),
    //                     'role'                 => 'guru',
    //                     'jabatan'              => $jabatan ?: 'Guru',
    //                     'barcode'              => null,
    //                     'is_active'            => true,
    //                     'must_change_password' => true,
    //                 ]);
    //                 $result['berhasil']++;
    //                 $result['log'][] = ['type' => 'ok', 'msg' => "$nama → @$username ($jabatan)"];
    //             } catch (\Exception $e) {
    //                 $result['error']++;
    //                 $result['log'][] = ['type' => 'error', 'msg' => "$nama: " . $e->getMessage()];
    //             }
    //         } else {
    //             $result['berhasil']++;
    //             $result['log'][] = ['type' => 'dry', 'msg' => "[SIMULASI] $nama → @$username ($jabatan)"];
    //         }
    //     }
    // }

    // private function importSiswa($spreadsheet, bool $isDryRun, array &$result): void
    // {
    //     try {
    //         $ws   = $spreadsheet->getSheetByName('SISWA');
    //         $rows = $ws->toArray(null, true, true, false);
    //     } catch (\Exception $e) {
    //         $result['log'][] = ['type' => 'error', 'msg' => 'Sheet SISWA tidak ditemukan.'];
    //         return;
    //     }

    //     foreach (array_slice($rows, 1) as $row) {
    //         $barcode = trim($row[1] ?? '');
    //         $nama    = trim($row[2] ?? '');

    //         if (empty($nama) || empty($barcode) || $nama === 'NAMA') continue;

    //         $username = strtolower($barcode);

    //         $existsByBarcode = User::where('barcode', $barcode)->exists();
    //         if ($existsByBarcode) {
    //             $result['duplikat']++;
    //             $result['log'][] = ['type' => 'skip', 'msg' => "$nama [$barcode] (duplikat)"];
    //             continue;
    //         }

    //         if (!$isDryRun) {
    //             try {
    //                 User::create([
    //                     'name'                 => $nama,
    //                     'username'             => $username,
    //                     'email'                => null,
    //                     'password'             => Hash::make('siswa'),
    //                     'role'                 => 'siswa',
    //                     'jabatan'              => null,
    //                     'barcode'              => $barcode,
    //                     'is_active'            => true,
    //                     'must_change_password' => true,
    //                 ]);
    //                 $result['berhasil']++;
    //                 $result['log'][] = ['type' => 'ok', 'msg' => "$nama [$barcode]"];
    //             } catch (\Exception $e) {
    //                 $result['error']++;
    //                 $result['log'][] = ['type' => 'error', 'msg' => "$nama: " . $e->getMessage()];
    //             }
    //         } else {
    //             $result['berhasil']++;
    //             $result['log'][] = ['type' => 'dry', 'msg' => "[SIMULASI] $nama [$barcode]"];
    //         }
    //     }
    // }

private function importGuru($spreadsheet, bool $isDryRun, array &$result): void
{
    // Cek sheet ada atau tidak — null check dulu
    $ws = $spreadsheet->getSheetByName('GURU KARYAWAN');

    if ($ws === null) {
        $result['log'][] = [
            'type' => 'error',
            'msg'  => 'Sheet "GURU KARYAWAN" tidak ditemukan. Pastikan nama sheet sesuai.'
        ];
        $result['error']++;
        return;
    }

    $rows = $ws->toArray(null, true, true, false);

    // Cek struktur header
    $header = array_map('trim', $rows[0] ?? []);
    if (!in_array('NAMA', $header) && !in_array('Nama', $header)) {
        $result['log'][] = [
            'type' => 'error',
            'msg'  => 'Struktur kolom tidak sesuai. Header yang ditemukan: ' . implode(', ', $header)
        ];
        $result['error']++;
        return;
    }

    // Sisa kode sama seperti sebelumnya...
    foreach (array_slice($rows, 1) as $row) {
        $nama    = trim($row[1] ?? '');
        $jabatan = trim($row[3] ?? '');

        if (empty($nama) || $nama === 'NAMA') continue;

        $username = $this->generateUsername($nama);

        $existsByName = User::where('name', $nama)->where('role', 'guru')->exists();
        if ($existsByName) {
            $result['duplikat']++;
            $result['log'][] = ['type' => 'skip', 'msg' => "$nama (duplikat)"];
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
                $result['berhasil']++;
                $result['log'][] = ['type' => 'ok', 'msg' => "$nama → @$username ($jabatan)"];
            } catch (\Exception $e) {
                $result['error']++;
                $result['log'][] = ['type' => 'error', 'msg' => "$nama: " . $e->getMessage()];
            }
        } else {
            $result['berhasil']++;
            $result['log'][] = ['type' => 'dry', 'msg' => "[SIMULASI] $nama → @$username ($jabatan)"];
        }
    }
}

private function importSiswa($spreadsheet, bool $isDryRun, array &$result): void
{
    // Cek sheet ada atau tidak
    $ws = $spreadsheet->getSheetByName('SISWA');

    if ($ws === null) {
        $result['log'][] = [
            'type' => 'error',
            'msg'  => 'Sheet "SISWA" tidak ditemukan. Pastikan nama sheet sesuai.'
        ];
        $result['error']++;
        return;
    }

    $rows = $ws->toArray(null, true, true, false);

    // Cek struktur header
    $header = array_map('trim', $rows[0] ?? []);
    if (!in_array('BARCODE', $header) && !in_array('Barcode', $header)) {
        $result['log'][] = [
            'type' => 'error',
            'msg'  => 'Struktur kolom tidak sesuai. Header yang ditemukan: ' . implode(', ', $header)
        ];
        $result['error']++;
        return;
    }

    // Sisa kode sama
    foreach (array_slice($rows, 1) as $row) {
        $barcode = trim($row[1] ?? '');
        $nama    = trim($row[2] ?? '');

        if (empty($nama) || empty($barcode) || $nama === 'NAMA') continue;

        $username = strtolower($barcode);

        $existsByBarcode = User::where('barcode', $barcode)->exists();
        if ($existsByBarcode) {
            $result['duplikat']++;
            $result['log'][] = ['type' => 'skip', 'msg' => "$nama [$barcode] (duplikat)"];
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
                    'jabatan'              => null,
                    'barcode'              => $barcode,
                    'is_active'            => true,
                    'must_change_password' => true,
                ]);
                $result['berhasil']++;
                $result['log'][] = ['type' => 'ok', 'msg' => "$nama [$barcode]"];
            } catch (\Exception $e) {
                $result['error']++;
                $result['log'][] = ['type' => 'error', 'msg' => "$nama: " . $e->getMessage()];
            }
        } else {
            $result['berhasil']++;
            $result['log'][] = ['type' => 'dry', 'msg' => "[SIMULASI] $nama [$barcode]"];
        }
    }
}

    private function generateUsername(string $nama): string
    {
        $username = strtolower(trim($nama));
        $username = str_replace(' ', '_', $username);
        $username = preg_replace('/[^a-z0-9_\-]/', '', $username);
        $username = preg_replace('/_+/', '_', $username);
        $username = trim($username, '_');

        $base    = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . '_' . $counter;
            $counter++;
        }
        return $username;
    }
}