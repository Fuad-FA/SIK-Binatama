<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #1565C0;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            color: #1565C0;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .header h2 {
            font-size: 13px;
            color: #555;
            font-weight: normal;
        }
        .header .period {
            font-size: 12px;
            color: #1976D2;
            font-weight: bold;
            margin-top: 4px;
        }

        /* Section title */
        .section-title {
            background: #1565C0;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
            padding: 5px 10px;
            margin: 16px 0 8px;
            letter-spacing: 0.5px;
        }

        /* Stats boxes */
        .stats-row {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }
        .stat-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            border: 1px solid #e0e0e0;
            padding: 10px;
            background: #f8f9fa;
        }
        .stat-box .val {
            font-size: 18px;
            font-weight: bold;
            color: #1565C0;
        }
        .stat-box .lbl {
            font-size: 10px;
            color: #888;
            margin-top: 2px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10px;
        }
        table thead tr {
            background: #1976D2;
            color: #fff;
        }
        table thead th {
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
        }
        table tbody tr:nth-child(even) {
            background: #f5f5f5;
        }
        table tbody td {
            padding: 5px 8px;
            border-bottom: 1px solid #eeeeee;
        }
        table tfoot tr {
            background: #e3f2fd;
            font-weight: bold;
        }
        table tfoot td {
            padding: 6px 8px;
        }

        /* Footer */
        .footer {
            margin-top: 24px;
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
            text-align: center;
            color: #aaa;
            font-size: 9px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .text-green { color: #2E7D32; }
        .text-blue { color: #1565C0; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <h1>RUMAH SEHAT BINATAMA</h1>
    <h2>SMK Kesehatan Binatama Yogyakarta</h2>
    <div class="period">LAPORAN BULANAN — {{ strtoupper($namaBulan) }}</div>
</div>

{{-- Ringkasan Statistik --}}
<div class="section-title">RINGKASAN</div>
<div class="stats-row">
    <div class="stat-box">
        <div class="val">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        <div class="lbl">Total Pendapatan</div>
    </div>
    <div class="stat-box">
        <div class="val">{{ $transaksi->count() }}</div>
        <div class="lbl">Total Transaksi</div>
    </div>
    <div class="stat-box">
        <div class="val">{{ $pasienBaru }}</div>
        <div class="lbl">Pasien Baru</div>
    </div>
</div>

{{-- Performa Staf --}}
<div class="section-title">PERFORMA STAF</div>
<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="35%">Nama Staf</th>
            <th width="15%">Role</th>
            <th width="15%" class="text-center">Pasien Baru</th>
            <th width="15%" class="text-center">Rekam Medis</th>
            <th width="15%" class="text-center">Transaksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($stafPerforma as $i => $staf)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $staf->name }}</td>
            <td>{{ ucfirst($staf->role) }}</td>
            <td class="text-center">{{ $staf->pasien_count }}</td>
            <td class="text-center">{{ $staf->rekam_count }}</td>
            <td class="text-center">{{ $staf->trx_count }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada aktivitas staf pada bulan ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Ringkasan Layanan --}}
<div class="section-title">RINGKASAN LAYANAN & PRODUK</div>
<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="55%">Layanan / Produk</th>
            <th width="15%" class="text-center">Terjual</th>
            <th width="25%" class="text-right">Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itemSummary as $i => $item)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $item->nama_item }}</td>
            <td class="text-center">{{ $item->total_qty }}x</td>
            <td class="text-right">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right bold">TOTAL PENDAPATAN</td>
            <td class="text-right bold text-blue">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>

{{-- Page break sebelum detail transaksi --}}
<div class="page-break"></div>

{{-- Detail Transaksi --}}
<div class="header">
    <h1>RUMAH SEHAT BINATAMA</h1>
    <div class="period">DETAIL TRANSAKSI — {{ strtoupper($namaBulan) }}</div>
</div>

<div class="section-title">DAFTAR TRANSAKSI</div>
<table>
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="18%">No. Transaksi</th>
            <th width="13%">Tanggal</th>
            <th width="18%">Pasien</th>
            <th width="15%">Petugas</th>
            <th width="20%">Layanan</th>
            <th width="12%" class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaksi as $i => $trx)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td style="font-size:9px;">{{ $trx->no_transaksi }}</td>
            <td>{{ $trx->created_at->format('d/m/Y') }}</td>
            <td>{{ $trx->patient->nama ?? '-' }}</td>
            <td>{{ $trx->user->name ?? '-' }}</td>
            <td style="font-size:9px;">
                {{ $trx->items->pluck('nama_item')->join(', ') }}
            </td>
            <td class="text-right">
                Rp {{ number_format($trx->total, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" class="text-right bold">TOTAL</td>
            <td class="text-right bold text-blue">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>

{{-- Footer --}}
<div class="footer">
    Dicetak pada {{ now()->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') }} &nbsp;·&nbsp;
    SIK Rumah Sehat Binatama &nbsp;·&nbsp; SMK Kesehatan Binatama Yogyakarta
</div>

</body>
</html>