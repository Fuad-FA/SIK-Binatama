<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #111;
            width: 72mm;
            padding: 4px 6px;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            padding-bottom: 6px;
            border-bottom: 2px solid #000;
            margin-bottom: 6px;
        }
        .nama-klinik {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .akreditasi {
            font-size: 8px;
            border: 1px solid #000;
            display: inline-block;
            padding: 0 6px;
            margin: 2px 0;
        }
        .sub-header {
            font-size: 8px;
            color: #444;
            line-height: 1.4;
        }

        /* ===== SECTION TITLE ===== */
        .section {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 2px 0;
            margin: 6px 0 4px;
        }

        /* ===== DATA ROWS (label : nilai) ===== */
        table.data {
            width: 100%;
            border-collapse: collapse;
        }
        table.data td {
            padding: 1.5px 0;
            font-size: 10px;
            vertical-align: top;
        }
        table.data td.lbl { width: 38%; color: #444; }
        table.data td.sep { width: 4%;  text-align: center; }
        table.data td.val { width: 58%; font-weight: bold; }

        /* ===== HASIL PEMERIKSAAN ===== */
        table.periksa {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        table.periksa td {
            padding: 2px 0;
            font-size: 10px;
            vertical-align: top;
            border-bottom: 1px dotted #ccc;
        }
        table.periksa td.p-label { width: 38%; color: #555; font-size: 9px; }
        table.periksa td.p-val   { width: 32%; font-weight: bold; }
        table.periksa td.p-status{ width: 30%; text-align: right; font-weight: bold; font-size: 9px; }

        /* ===== VITAL SIGN ===== */
        table.vital {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1px dotted #ccc;
            margin: 2px 0;
        }
        table.vital td {
            text-align: center;
            padding: 3px 0;
            width: 33.33%;
            border-right: 1px dotted #ccc;
        }
        table.vital td:last-child { border-right: none; }
        .v-lbl { font-size: 8px; color: #888; }
        .v-val { font-size: 11px; font-weight: bold; }

        /* ===== RINCIAN ITEM ===== */
        table.items {
            width: 100%;
            border-collapse: collapse;
        }
        table.items td {
            padding: 2px 0;
            font-size: 10px;
            border-bottom: 1px dotted #ddd;
            vertical-align: top;
        }
        table.items td.i-nama    { width: 50%; }
        table.items td.i-satuan  { width: 25%; text-align: right; color: #666; }
        table.items td.i-subtotal{ width: 25%; text-align: right; font-weight: bold; }

        /* ===== TOTAL ===== */
        .total-box {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 4px 0;
            margin: 4px 0;
        }
        table.total {
            width: 100%;
            border-collapse: collapse;
        }
        table.total td {
            font-size: 10px;
            padding: 1px 0;
        }
        table.total td.t-lbl  { width: 55%; }
        table.total td.t-val  { width: 45%; text-align: right; }
        .grand-lbl { font-size: 13px; font-weight: bold; }
        .grand-val { font-size: 13px; font-weight: bold; text-align: right; }

        /* ===== QR ===== */
        .qr-box {
            text-align: center;
            padding: 6px 0 4px;
            border-top: 1px dashed #000;
            margin-top: 6px;
        }
        .qr-box img { width: 75px; height: 75px; }
        .qr-rm   { font-size: 11px; font-weight: bold; letter-spacing: 2px; margin-top: 3px; }
        .qr-kode { font-size: 11px; font-weight: bold; letter-spacing: 3px; }
        .qr-hint { font-size: 7.5px; color: #666; margin-top: 2px; }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            border-top: 2px solid #000;
            padding-top: 5px;
            margin-top: 6px;
        }
        .thanks { font-size: 11px; font-weight: bold; }
        .info   { font-size: 8px; color: #555; margin-top: 2px; }

        /* Catatan */
        .catatan-box {
            font-size: 9.5px;
            padding: 2px 0 3px;
            border-bottom: 1px dotted #ccc;
        }
        .catatan-lbl { font-size: 8px; color: #888; margin-bottom: 1px; }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="nama-klinik">RUMAH SEHAT BINATAMA</div>
    <div class="akreditasi">TERAKREDITASI "A"</div>
    <div class="sub-header">
        Program Keahlian Keperawatan dan Farmasi<br>
        SMK Kesehatan Binatama Yogyakarta
    </div>
</div>

{{-- DATA PASIEN --}}
<div class="section">Data Pasien</div>
<table class="data">
    <tr>
        <td class="lbl">Nama</td>
        <td class="sep">:</td>
        <td class="val">{{ $transaction->patient->nama }}</td>
    </tr>
    <tr>
        <td class="lbl">No. RM</td>
        <td class="sep">:</td>
        <td class="val">{{ $transaction->patient->no_rm }}</td>
    </tr>
    <tr>
        <td class="lbl">Tgl. Periksa</td>
        <td class="sep">:</td>
        <td class="val">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <td class="lbl">Petugas</td>
        <td class="sep">:</td>
        <td class="val">{{ $transaction->user->name ?? '-' }}</td>
    </tr>
    <tr>
        <td class="lbl">No. Transaksi</td>
        <td class="sep">:</td>
        <td class="val" style="font-size:9px;">{{ $transaction->no_transaksi }}</td>
    </tr>
</table>

{{-- HASIL PEMERIKSAAN --}}
@if($transaction->medicalRecord)
@php $rec = $transaction->medicalRecord; @endphp
<div class="section">Hasil Pemeriksaan</div>

<table class="periksa">
@if($rec->gula_darah)
<tr>
    <td class="p-label">Gula Darah<br><span style="font-size:8px;color:#aaa;">&lt;200 mg/dl</span></td>
    <td class="p-val">{{ number_format($rec->gula_darah, 2) }} mg/dl</td>
    <td class="p-status">{{ $rec->gula_darah < 200 ? 'Normal' : 'Tinggi' }}</td>
</tr>
@endif
@if($rec->kolesterol)
<tr>
    <td class="p-label">Kolesterol<br><span style="font-size:8px;color:#aaa;">160-200 mg/dl</span></td>
    <td class="p-val">{{ number_format($rec->kolesterol, 2) }} mg/dl</td>
    <td class="p-status">{{ ($rec->kolesterol >= 160 && $rec->kolesterol <= 200) ? 'Normal' : 'Abnormal' }}</td>
</tr>
@endif
@if($rec->asam_urat)
<tr>
    <td class="p-label">Asam Urat<br><span style="font-size:8px;color:#aaa;">P:3,4-7 / W:2,4-6</span></td>
    <td class="p-val">{{ number_format($rec->asam_urat, 2) }} mg/dl</td>
    <td class="p-status">{{ ($rec->asam_urat >= 2.4 && $rec->asam_urat <= 7) ? 'Normal' : 'Abnormal' }}</td>
</tr>
@endif
@if($rec->tensi_sistolik)
@php
    $statusTensi = ($rec->tensi_sistolik <= 120 && $rec->tensi_diastolik <= 80)
        ? 'Normal' : (($rec->tensi_sistolik <= 139) ? 'Prehiper.' : 'Hipertensi');
@endphp
<tr>
    <td class="p-label">Tekanan Darah<br><span style="font-size:8px;color:#aaa;">120/80 mmHg</span></td>
    <td class="p-val">{{ $rec->tensi_sistolik }}/{{ $rec->tensi_diastolik }} mmHg</td>
    <td class="p-status">{{ $statusTensi }}</td>
</tr>
@endif
@if($rec->bmi)
<tr>
    <td class="p-label">BMI<br><span style="font-size:8px;color:#aaa;">{{ $rec->berat_badan }}kg / {{ $rec->tinggi_badan }}cm</span></td>
    <td class="p-val">{{ $rec->bmi }} kg/m²</td>
    <td class="p-status">{{ $rec->kategoriBmi() }}</td>
</tr>
@endif

</table>
@if($rec->bb || $rec->tb || $rec->lila || $rec->lingkar_kepala || $rec->lingkar_perut)
<div class="periksa-item" style="border-bottom:1px dotted #ccc;padding:3px 4px;">
    <div class="periksa-label">Antropometri</div>
    <table style="width:100%;font-size:9px;margin-top:2px;">
        @if($rec->bb)
        <tr>
            <td style="color:#666;width:50%;">Berat Badan</td>
            <td style="text-align:right;font-weight:bold;">{{ $rec->bb }} kg</td>
        </tr>
        @endif
        @if($rec->tb)
        <tr>
            <td style="color:#666;">Tinggi/Panjang Badan</td>
            <td style="text-align:right;font-weight:bold;">{{ $rec->tb }} cm</td>
        </tr>
        @endif
        @if($rec->lila)
        <tr>
            <td style="color:#666;">LiLA</td>
            <td style="text-align:right;font-weight:bold;">{{ $rec->lila }} cm</td>
        </tr>
        @endif
        @if($rec->lingkar_kepala)
        <tr>
            <td style="color:#666;">Lingkar Kepala</td>
            <td style="text-align:right;font-weight:bold;">{{ $rec->lingkar_kepala }} cm</td>
        </tr>
        @endif
        @if($rec->lingkar_perut)
        <tr>
            <td style="color:#666;">Lingkar Perut</td>
            <td style="text-align:right;font-weight:bold;">{{ $rec->lingkar_perut }} cm</td>
        </tr>
        @endif
    </table>
</div>
@endif



@if($rec->suhu || $rec->nadi || $rec->respirasi)
<table class="vital">
    <tr>
        @if($rec->suhu)
        <td><div class="v-lbl">Suhu</div><div class="v-val">{{ $rec->suhu }}°C</div></td>
        @endif
        @if($rec->nadi)
        <td><div class="v-lbl">Nadi</div><div class="v-val">{{ $rec->nadi }}/mnt</div></td>
        @endif
        @if($rec->respirasi)
        <td><div class="v-lbl">Respirasi</div><div class="v-val">{{ $rec->respirasi }}/mnt</div></td>
        @endif
    </tr>
</table>
@endif

@if($rec->catatan_gizi)
<div class="catatan-box">
    <div class="catatan-lbl">Catatan Konsultasi Gizi</div>
    {{ $rec->catatan_gizi }}
</div>
@endif

@if($rec->catatan)
<div class="catatan-box">
    <div class="catatan-lbl">Catatan</div>
    {{ $rec->catatan }}
</div>
@endif

@endif

{{-- RINCIAN LAYANAN --}}
<div class="section">Rincian Layanan & Produk</div>
<table class="items">
@foreach($transaction->items as $item)
<tr>
    <td class="i-nama">{{ $item->nama_item }}</td>
    <td class="i-satuan">
        @if($item->harga > 0)
            Rp {{ number_format($item->harga, 0, ',', '.') }}
        @else
            <span style="color:#555;font-size:9px;">Gratis</span>
        @endif
    </td>
    <td class="i-subtotal">
        @if($item->subtotal > 0)
            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
        @else
            —
        @endif
    </td>
</tr>
@endforeach
</table>

{{-- TOTAL --}}
<div class="total-box">
    <table class="total">
        @if(($transaction->diskon ?? 0) > 0)
        <tr>
            <td class="t-lbl">Subtotal</td>
            <td class="t-val">Rp {{ number_format($transaction->total + $transaction->diskon, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="t-lbl" style="color:#555;">Diskon</td>
            <td class="t-val" style="color:#555;">- Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td class="grand-lbl">TOTAL</td>
            <td class="grand-val">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
        </tr>
    </table>
    <div style="font-size:8.5px;color:#555;text-align:right;margin-top:2px;">
        Metode: {{ strtoupper($transaction->metode_bayar ?? 'CASH') }}
    </div>
</div>

{{-- QR CODE --}}
<div class="qr-box">
    <div style="font-size:7.5px;color:#666;margin-bottom:3px;">
        Scan untuk cek hasil pemeriksaan
    </div>
    <img src="data:image/svg+xml;base64,{{ $qrCode }}">
    <div class="qr-rm">{{ $transaction->patient->no_rm }}</div>
    <div class="qr-kode">{{ $transaction->patient->kode_unik }}</div>
    <div class="qr-hint">Gunakan No. RM + Kode untuk login portal pasien</div>
</div>

{{-- FOOTER --}}
<div class="footer">
    <div class="thanks">Terima Kasih Semoga Sehat Selalu</div>
    <div class="info">{{ config('app.name', 'SIK Rumah Sehat Binatama') }}</div>
    <div class="info">smkkesbinatama@gmail.com</div>
    <div class="info" style="margin-top:3px;font-size:7px;">
        Dicetak: {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

</body>
</html>