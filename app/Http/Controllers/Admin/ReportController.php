<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // Rekap bulan ini
        $rekap = [
            'total_pasien_baru'  => Patient::whereMonth('created_at', $bulan)
                                          ->whereYear('created_at', $tahun)->count(),
            'total_pemeriksaan'  => MedicalRecord::whereMonth('created_at', $bulan)
                                                 ->whereYear('created_at', $tahun)->count(),
            'total_transaksi'    => Transaction::where('status', 'selesai')
                                              ->whereMonth('created_at', $bulan)
                                              ->whereYear('created_at', $tahun)->count(),
            'total_pendapatan'   => Transaction::where('status', 'selesai')
                                              ->whereMonth('created_at', $bulan)
                                              ->whereYear('created_at', $tahun)->sum('total'),
        ];

        // Performa staf
        // $stafPerforma = User::where('role', '!=', 'admin')
        //     ->withCount([
        //         'patients as pasien_count' => fn($q) =>
        //             $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
        //         'transactions as trx_count' => fn($q) =>
        //             $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
        //     ])
        //     ->having('pasien_count', '>', 0)
        //     ->orHaving('trx_count', '>', 0)
        //     ->orderByDesc('trx_count')
        //     ->get();
$stafPerforma = User::where('role', '!=', 'admin')
    ->withCount([
        // Rekam medis yang diinput staf ini bulan ini
        'medicalRecords as rekam_count' => fn($q) =>
            $q->whereMonth('created_at', $bulan)
              ->whereYear('created_at', $tahun),
        // Transaksi yang dibuat staf ini bulan ini
        'transactions as trx_count' => fn($q) =>
            $q->whereMonth('created_at', $bulan)
              ->whereYear('created_at', $tahun),
        // Pasien baru yang didaftarkan staf ini bulan ini
        'patients as pasien_count' => fn($q) =>
            $q->whereMonth('created_at', $bulan)
              ->whereYear('created_at', $tahun),
    ])
    ->get()
    ->filter(fn($s) => $s->rekam_count > 0 || $s->trx_count > 0 || $s->pasien_count > 0)
    ->sortByDesc('trx_count')
    ->values();
        // Data per hari dalam bulan ini
        $harian = Transaction::where('status', 'selesai')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah, SUM(total) as pendapatan')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Daftar bulan & tahun untuk filter
        $bulanList = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
        ];
        $tahunList = range(now()->year, now()->year - 2);

        return view('admin.reports', compact(
            'rekap', 'stafPerforma', 'harian',
            'bulan', 'tahun', 'bulanList', 'tahunList'
        ));
    }
}