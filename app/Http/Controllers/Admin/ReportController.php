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


    public function export(Request $request)
{
    $bulan  = $request->get('bulan', now()->month);
    $tahun  = $request->get('tahun', now()->year);
    $format = $request->get('format', 'xlsx');

    $namaBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');

    // Ambil semua data untuk laporan
    $transaksi = \App\Models\Transaction::with(['patient', 'user', 'items'])
        ->whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->orderBy('created_at')
        ->get();

    $pasienBaru = \App\Models\Patient::whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->count();

    $totalPendapatan = $transaksi->sum('total');

    // Performa staf
    $stafPerforma = \App\Models\User::where('role', '!=', 'admin')
        ->withCount([
            'medicalRecords as rekam_count' => fn($q) =>
                $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
            'transactions as trx_count' => fn($q) =>
                $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
            'patients as pasien_count' => fn($q) =>
                $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
        ])
        ->get()
        ->filter(fn($s) => $s->rekam_count > 0 || $s->trx_count > 0 || $s->pasien_count > 0)
        ->sortByDesc('trx_count')
        ->values();

    // Ringkasan per layanan/produk
    $itemSummary = \App\Models\TransactionItem::whereHas('transaction', fn($q) =>
        $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun))
        ->selectRaw('nama_item, SUM(qty) as total_qty, SUM(subtotal) as total_pendapatan')
        ->groupBy('nama_item')
        ->orderByDesc('total_pendapatan')
        ->get();

    if ($format === 'pdf') {
        return $this->exportPdf(
            $transaksi, $stafPerforma, $itemSummary,
            $namaBulan, $totalPendapatan, $pasienBaru, $bulan, $tahun
        );
    }

    return $this->exportXlsx(
        $transaksi, $stafPerforma, $itemSummary,
        $namaBulan, $totalPendapatan, $pasienBaru
    );
}

private function exportXlsx($transaksi, $stafPerforma, $itemSummary, $namaBulan, $totalPendapatan, $pasienBaru)
{
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $spreadsheet->getProperties()
        ->setTitle('Laporan Bulanan ' . $namaBulan)
        ->setCreator('SIK Rumah Sehat Binatama');

    // ===== SHEET 1: RINGKASAN =====
    $sheet1 = $spreadsheet->getActiveSheet();
    $sheet1->setTitle('Ringkasan');

    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                   'startColor' => ['rgb' => '1565C0']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    ];
    $subHeaderStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                   'startColor' => ['rgb' => '1976D2']],
    ];
    $titleStyle = [
        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1565C0']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    ];

    // Judul
    $sheet1->mergeCells('A1:F1');
    $sheet1->setCellValue('A1', 'LAPORAN BULANAN RUMAH SEHAT BINATAMA');
    $sheet1->getStyle('A1')->applyFromArray($titleStyle);

    $sheet1->mergeCells('A2:F2');
    $sheet1->setCellValue('A2', 'Periode: ' . $namaBulan);
    $sheet1->getStyle('A2')->getAlignment()->setHorizontal('center');
    $sheet1->getStyle('A2')->getFont()->setItalic(true);

    // Ringkasan stats
    $sheet1->mergeCells('A4:F4');
    $sheet1->setCellValue('A4', 'RINGKASAN');
    $sheet1->getStyle('A4')->applyFromArray($subHeaderStyle);

    $sheet1->setCellValue('A5', 'Total Pendapatan');
    $sheet1->setCellValue('B5', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'));
    $sheet1->setCellValue('A6', 'Total Transaksi');
    $sheet1->setCellValue('B6', $transaksi->count() . ' transaksi');
    $sheet1->setCellValue('A7', 'Pasien Baru');
    $sheet1->setCellValue('B7', $pasienBaru . ' orang');
    $sheet1->getStyle('B5')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('002E7D32'));

    // Performa staf
    $sheet1->mergeCells('A9:F9');
    $sheet1->setCellValue('A9', 'PERFORMA STAF');
    $sheet1->getStyle('A9')->applyFromArray($subHeaderStyle);

    $sheet1->setCellValue('A10', 'No');
    $sheet1->setCellValue('B10', 'Nama Staf');
    $sheet1->setCellValue('C10', 'Role');
    $sheet1->setCellValue('D10', 'Pasien Baru');
    $sheet1->setCellValue('E10', 'Rekam Medis');
    $sheet1->setCellValue('F10', 'Transaksi');
    $sheet1->getStyle('A10:F10')->applyFromArray($headerStyle);

    $row = 11;
    foreach ($stafPerforma as $i => $staf) {
        $sheet1->setCellValue('A' . $row, $i + 1);
        $sheet1->setCellValue('B' . $row, $staf->name);
        $sheet1->setCellValue('C' . $row, ucfirst($staf->role));
        $sheet1->setCellValue('D' . $row, $staf->pasien_count);
        $sheet1->setCellValue('E' . $row, $staf->rekam_count);
        $sheet1->setCellValue('F' . $row, $staf->trx_count);
        if ($i % 2 === 1) {
            $sheet1->getStyle("A{$row}:F{$row}")->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('F5F5F5');
        }
        $row++;
    }

    // Ringkasan layanan
    $row += 2;
    $sheet1->mergeCells("A{$row}:F{$row}");
    $sheet1->setCellValue("A{$row}", 'RINGKASAN LAYANAN & PRODUK');
    $sheet1->getStyle("A{$row}")->applyFromArray($subHeaderStyle);
    $row++;

    $sheet1->setCellValue("A{$row}", 'No');
    $sheet1->setCellValue("B{$row}", 'Layanan / Produk');
    $sheet1->setCellValue("C{$row}", 'Total Terjual');
    $sheet1->setCellValue("D{$row}", 'Total Pendapatan');
    $sheet1->getStyle("A{$row}:D{$row}")->applyFromArray($headerStyle);
    $row++;

    foreach ($itemSummary as $i => $item) {
        $sheet1->setCellValue("A{$row}", $i + 1);
        $sheet1->setCellValue("B{$row}", $item->nama_item);
        $sheet1->setCellValue("C{$row}", $item->total_qty . 'x');
        $sheet1->setCellValue("D{$row}", 'Rp ' . number_format($item->total_pendapatan, 0, ',', '.'));
        $row++;
    }

    // Auto width
    foreach (range('A', 'F') as $col) {
        $sheet1->getColumnDimension($col)->setAutoSize(true);
    }

    // ===== SHEET 2: DETAIL TRANSAKSI =====
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Detail Transaksi');

    $sheet2->mergeCells('A1:G1');
    $sheet2->setCellValue('A1', 'DETAIL TRANSAKSI — ' . $namaBulan);
    $sheet2->getStyle('A1')->applyFromArray($titleStyle);

    $sheet2->setCellValue('A3', 'No');
    $sheet2->setCellValue('B3', 'No. Transaksi');
    $sheet2->setCellValue('C3', 'Tanggal');
    $sheet2->setCellValue('D3', 'Pasien');
    $sheet2->setCellValue('E3', 'Petugas');
    $sheet2->setCellValue('F3', 'Layanan / Produk');
    $sheet2->setCellValue('G3', 'Total');
    $sheet2->getStyle('A3:G3')->applyFromArray($headerStyle);

    $row = 4;
    foreach ($transaksi as $i => $trx) {
        $items = $trx->items->pluck('nama_item')->join(', ');
        $sheet2->setCellValue("A{$row}", $i + 1);
        $sheet2->setCellValue("B{$row}", $trx->no_transaksi);
        $sheet2->setCellValue("C{$row}", $trx->created_at->format('d/m/Y H:i'));
        $sheet2->setCellValue("D{$row}", $trx->patient->nama ?? '-');
        $sheet2->setCellValue("E{$row}", $trx->user->name ?? '-');
        $sheet2->setCellValue("F{$row}", $items);
        $sheet2->setCellValue("G{$row}", 'Rp ' . number_format($trx->total, 0, ',', '.'));
        if ($i % 2 === 1) {
            $sheet2->getStyle("A{$row}:G{$row}")->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('F5F5F5');
        }
        $row++;
    }

    // Total baris
    $sheet2->setCellValue("F{$row}", 'TOTAL');
    $sheet2->setCellValue("G{$row}", 'Rp ' . number_format($totalPendapatan, 0, ',', '.'));
    $sheet2->getStyle("F{$row}:G{$row}")->getFont()->setBold(true);

    foreach (range('A', 'G') as $col) {
        $sheet2->getColumnDimension($col)->setAutoSize(true);
    }

    // Output file
    $writer  = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $tmpFile = tempnam(sys_get_temp_dir(), 'laporan_');
    $writer->save($tmpFile);

    $filename = 'Laporan_Bulanan_' . str_replace(' ', '_', $namaBulan) . '.xlsx';

    return response()->download($tmpFile, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ])->deleteFileAfterSend(true);
}

private function exportPdf($transaksi, $stafPerforma, $itemSummary, $namaBulan, $totalPendapatan, $pasienBaru, $bulan, $tahun)
{
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan-bulanan', [
        'namaBulan'       => $namaBulan,
        'transaksi'       => $transaksi,
        'stafPerforma'    => $stafPerforma,
        'itemSummary'     => $itemSummary,
        'totalPendapatan' => $totalPendapatan,
        'pasienBaru'      => $pasienBaru,
        'bulan'           => $bulan,
        'tahun'           => $tahun,
    ]);

    $pdf->setPaper('A4', 'portrait');

    $filename = 'Laporan_Bulanan_' . str_replace(' ', '_', $namaBulan) . '.pdf';
    return $pdf->download($filename);
}
}