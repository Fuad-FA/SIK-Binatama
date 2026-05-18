<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulan = now()->month;
        $tahun = now()->year;

        // Statistik utama
        $stats = [
            'total_pasien'       => Patient::count(),
            'pasien_bulan_ini'   => Patient::whereMonth('created_at', $bulan)
                                          ->whereYear('created_at', $tahun)->count(),
            'total_pemeriksaan'  => MedicalRecord::count(),
            'periksa_bulan_ini'  => MedicalRecord::whereMonth('created_at', $bulan)
                                                 ->whereYear('created_at', $tahun)->count(),
            'total_transaksi'    => Transaction::where('status', 'selesai')->count(),
            'transaksi_bulan_ini'=> Transaction::where('status', 'selesai')
                                              ->whereMonth('created_at', $bulan)
                                              ->whereYear('created_at', $tahun)->count(),
            'pendapatan_total'   => Transaction::where('status', 'selesai')->sum('total'),
            'pendapatan_bulan'   => Transaction::where('status', 'selesai')
                                              ->whereMonth('created_at', $bulan)
                                              ->whereYear('created_at', $tahun)->sum('total'),
            'total_staf'         => User::where('role', '!=', 'admin')->count(),
            'staf_aktif'         => User::where('role', '!=', 'admin')
                                       ->where('is_active', true)->count(),
        ];

        // Grafik pendapatan 6 bulan terakhir
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $label = $date->locale('id')->isoFormat('MMM YY');
            $total = Transaction::where('status', 'selesai')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total');
            $pasien = Patient::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $chartData[] = [
                'label'   => $label,
                'total'   => (float) $total,
                'pasien'  => (int) $pasien,
            ];
        }

        // Top 5 staf paling aktif bulan ini
        // $topStaf = User::where('role', '!=', 'admin')
        //     ->withCount([
        //         'patients as pasien_count' => fn($q) =>
        //             $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
        //         'transactions as trx_count' => fn($q) =>
        //             $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
        //     ])
        //     ->orderByDesc('trx_count')
        //     ->limit(5)
        //     ->get();
$topStaf = User::where('role', '!=', 'admin')
    ->withCount([
        'patients as pasien_count' => fn($q) =>
            $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
        'medicalRecords as rekam_count' => fn($q) =>
            $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
        'transactions as trx_count' => fn($q) =>
            $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
    ])
    ->orderByDesc('trx_count')
    ->limit(5)
    ->get();
        // Pasien terbaru
        $recentPatients = Patient::with('creator')
            ->orderByDesc('created_at')->limit(5)->get();

        // Transaksi terbaru
        $recentTransactions = Transaction::with(['patient', 'user'])
            ->where('status', 'selesai')
            ->orderByDesc('created_at')->limit(5)->get();

            $akunTerkunci = User::whereNotNull('locked_at')
    ->orderByDesc('locked_at')
    ->get();


// Produk dengan stok menipis (stok <= 10)
        $stokMenipis = \App\Models\Product::where('is_active', true)
            ->where('stok', '<=', 10)
            ->where('stok', '>', 0)
            ->orderBy('stok')
            ->get();

        // Produk habis (stok = 0)
        $stokHabis = \App\Models\Product::where('is_active', true)
            ->where('stok', 0)
            ->get();


            // Activity log terbaru
        $activityLogs = \App\Models\ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'chartData', 'topStaf', 'akunTerkunci',  'stokMenipis', 'stokHabis', 'activityLogs',
            'recentPatients', 'recentTransactions'
        ));
    }
}