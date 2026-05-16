<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Transaction;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $userId = auth()->id();
    //     $today  = now()->toDateString();
    //     $bulan  = now()->month;
    //     $tahun  = now()->year;

    //     $stats = [
    //         // Pasien yang didaftarkan staf ini
    //         'input_pasien' => Patient::where('created_by', $userId)
    //             ->whereMonth('created_at', $bulan)
    //             ->whereYear('created_at', $tahun)
    //             ->count(),

    //         // Pemeriksaan yang diinput staf ini hari ini
    //         'periksa_hari_ini' => MedicalRecord::where('user_id', $userId)
    //             ->whereDate('created_at', $today)
    //             ->count(),

    //         // Transaksi yang dibuat staf ini hari ini
    //         'transaksi_hari_ini' => Transaction::where('user_id', $userId)
    //             ->whereDate('created_at', $today)
    //             ->count(),

    //         // Total rekam medis staf ini bulan ini
    //         'rekam_bulan_ini' => MedicalRecord::where('user_id', $userId)
    //             ->whereMonth('created_at', $bulan)
    //             ->whereYear('created_at', $tahun)
    //             ->count(),

    //         // Total transaksi staf ini bulan ini
    //         'transaksi_bulan_ini' => Transaction::where('user_id', $userId)
    //             ->whereMonth('created_at', $bulan)
    //             ->whereYear('created_at', $tahun)
    //             ->count(),

    //         // Total pasien bulan ini (semua, untuk konteks)
    //         'total_pasien_bulan' => Patient::whereMonth('created_at', $bulan)
    //             ->whereYear('created_at', $tahun)
    //             ->count(),
    //     ];

    //     // 5 aktivitas terakhir staf ini
    //     $recentRecords = MedicalRecord::with('patient')
    //         ->where('user_id', $userId)
    //         ->orderByDesc('created_at')
    //         ->limit(5)
    //         ->get();

    //     $recentTransactions = Transaction::with('patient')
    //         ->where('user_id', $userId)
    //         ->orderByDesc('created_at')
    //         ->limit(5)
    //         ->get();

    //     return view('staff.dashboard', compact(
    //         'stats', 'recentRecords', 'recentTransactions'
    //     ));
    // }

    public function index()
    {
        $userId = auth()->id();
        $today  = now()->toDateString();
        $bulan  = now()->month;
        $tahun  = now()->year;

        $stats = [
            'input_pasien'       => Patient::where('created_by', $userId)
                ->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count(),
            'periksa_hari_ini'   => MedicalRecord::where('user_id', $userId)
                ->whereDate('created_at', $today)->count(),
            'transaksi_hari_ini' => Transaction::where('user_id', $userId)
                ->whereDate('created_at', $today)->count(),
            'rekam_bulan_ini'    => MedicalRecord::where('user_id', $userId)
                ->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count(),
            'transaksi_bulan_ini'=> Transaction::where('user_id', $userId)
                ->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count(),
        ];

        // Cek transaksi yang belum ada rekam medisnya milik staf ini
        $pendingTransactions = Transaction::where('user_id', $userId)
            ->whereNull('medical_record_id')
            ->where('status', 'selesai')
            ->with('patient')
            ->orderByDesc('created_at')
            ->get()
            ->filter(function ($trx) {
                // Filter hanya transaksi yang punya layanan kesehatan
                $itemNames = $trx->items->pluck('nama_item')->toArray();
                $fields    = $this->getFieldsFromItems($itemNames);
                return !empty($fields);
            });

        $recentRecords = MedicalRecord::with('patient')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')->limit(5)->get();

        $recentTransactions = Transaction::with('patient')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')->limit(5)->get();

        return view('staff.dashboard', compact(
            'stats', 'recentRecords', 'recentTransactions', 'pendingTransactions'
        ));
    }

    // Helper mapping (sama seperti di TransactionController)
    // private function getFieldsFromItems(array $itemNames): array
    // {
    //     $mapping = [
    //         'gula_darah'   => ['cek gula', 'gula darah', 'glucose', 'gds', 'gdp'],
    //         'kolesterol'   => ['cek kolesterol', 'kolesterol', 'cholesterol'],
    //         'asam_urat'    => ['cek asam urat', 'asam urat', 'uric acid'],
    //         'tensi'        => ['cek tekanan darah', 'tekanan darah', 'cek tensi', 'tensi'],
    //         'suhu'         => ['cek suhu', 'suhu tubuh', 'temperatur'],
    //         'nadi'         => ['cek nadi', 'denyut nadi'],
    //         'respirasi'    => ['cek respirasi', 'respirasi', 'pernapasan'],
    //         'antropometri' => ['cek bmi', 'cek antropometri', 'bmi', 'antropometri'],
    //     ];

    //     $allVitalServices = [
    //         'paket sehat', 'sehat 1', 'sehat 2', 'sehat 3',
    //         'sehat 4', 'sehat 5', 'vital sign', 'pijat', 'totok',
    //         'infra red', 'senam',
    //     ];

    //     $fields = [];
    //     foreach ($itemNames as $nama) {
    //         $namaLower = strtolower(trim($nama));
    //         foreach ($allVitalServices as $vs) {
    //             if (str_contains($namaLower, $vs)) {
    //                 return ['gula_darah','kolesterol','asam_urat','tensi','suhu','nadi','respirasi'];
    //             }
    //         }
    //         foreach ($mapping as $field => $keywords) {
    //             foreach ($keywords as $kw) {
    //                 if (str_contains($namaLower, $kw)) {
    //                     $fields[] = $field;
    //                     break;
    //                 }
    //             }
    //         }
    //     }
    //     return array_unique($fields);
    // }

    private function getFieldsFromItems(array $itemNames): array
{
    $fields = [];

    // WHITELIST: hanya layanan ini yang memicu form rekam medis
    // Key = field, Value = nama layanan yang EKSAK (lowercase)
    $exactMapping = [
        'gula_darah' => ['cek gula darah'],
        'kolesterol' => ['cek kolesterol'],
        'asam_urat'  => ['cek asam urat'],
        'tensi'      => ['cek tekanan darah'],
        'suhu'       => ['cek suhu'],
        'nadi'       => ['cek nadi'],
        'respirasi'  => ['cek respirasi'],
    ];

    // Paket dengan field spesifik
    $paketMapping = [
        'paket sehat 1' => ['tensi', 'suhu', 'nadi', 'respirasi'],
        'paket sehat 2' => ['tensi', 'suhu', 'nadi', 'respirasi'],
        'paket sehat 3' => ['tensi', 'suhu', 'nadi', 'respirasi'],
        'paket sehat 4' => ['gula_darah', 'kolesterol', 'asam_urat',
                            'tensi', 'suhu', 'nadi', 'respirasi'],
        'paket sehat 5' => ['tensi', 'suhu', 'nadi', 'respirasi'],
    ];

    foreach ($itemNames as $nama) {
        $namaLower = strtolower(trim($nama));

        // Cek paket dulu
        $matchedPaket = false;
        foreach ($paketMapping as $paket => $paketFields) {
            if ($namaLower === $paket || str_contains($namaLower, $paket)) {
                $fields      = array_merge($fields, $paketFields);
                $matchedPaket = true;
                break;
            }
        }
        if ($matchedPaket) continue;

        // Cek exact mapping — harus cocok persis dengan nama layanan
        foreach ($exactMapping as $field => $names) {
            foreach ($names as $n) {
                if ($namaLower === $n) { // exact match
                    $fields[] = $field;
                    break;
                }
            }
        }
    }

    return array_unique($fields);
}
}