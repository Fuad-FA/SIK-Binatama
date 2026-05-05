<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 10px;
        color: #000;
        width: 100%;
    }

    .center { text-align: center; }
    .bold   { font-weight: bold; }
    .right  { text-align: right; }

    .header {
        text-align: center;
        padding: 8px 4px;
        border-bottom: 2px solid #000;
        margin-bottom: 6px;
    }
    .header .title {
        font-size: 13px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .header .subtitle {
        font-size: 8px;
        color: #333;
        margin-top: 2px;
    }
    .header .akreditasi {
        font-size: 8px;
        background: #000;
        color: #fff;
        padding: 1px 6px;
        display: inline-block;
        margin: 2px 0;
    }

    .section-title {
        font-weight: bold;
        font-size: 9px;
        background: #eee;
        padding: 2px 4px;
        margin: 4px 0 2px;
        text-transform: uppercase;
    }

    /* .row {
        display: flex;
        justify-content: space-between;
        padding: 1px 4px;
        font-size: 9px;
    } */
     .row {
    display: flex;
    justify-content: space-between;
    padding: 2px 4px;
    font-size: 9px;
    gap: 8px;
}

    .divider { border-top: 1px dashed #999; margin: 4px 0; }
    .divider-solid { border-top: 1px solid #000; margin: 4px 0; }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
    }
    td { padding: 2px 4px; vertical-align: top; }

    .item-nama { width: 60%; }
    .item-harga { width: 20%; text-align: right; }
    .item-sub   { width: 20%; text-align: right; font-weight: bold; }

    .total-row {
        border-top: 1px solid #000;
        font-weight: bold;
    }
    .total-big {
        font-size: 13px;
        font-weight: bold;
    }

    .nilai-normal {
        font-size: 8px;
        color: #555;
    }
    .abnormal { color: #c00; font-weight: bold; }
    .normal   { color: #090; }

    .footer {
        text-align: center;
        margin-top: 8px;
        padding-top: 6px;
        border-top: 2px solid #000;
        font-size: 9px;
    }

    .qr-box {
        text-align: center;
        margin: 6px 0;
        padding: 4px;
        border: 1px dashed #999;
    }
    .qr-box img {
        width: 90px;
        height: 90px;
    }
    .no-rm-big {
        font-size: 14px;
        font-weight: bold;
        letter-spacing: 2px;
    }
    .kode-unik {
        font-size: 12px;
        font-weight: bold;
        letter-spacing: 3px;
        color: #555;
    }
</style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="title">Rumah Sehat Binatama</div>
    <div class="akreditasi">TERAKREDITASI "A"</div>
    <div class="subtitle">Program Keahlian Keperawatan dan Farmasi</div>
    <div class="subtitle">SMK Kesehatan Binatama Yogyakarta</div>
</div>

{{-- DATA PASIEN --}}
<div class="section-title">Data Pasien</div>
<div class="row"><span>Nama</span><span class="bold">{{ $transaction->patient->nama }}</span></div>
<div class="row"><span>No. RM</span><span class="bold">{{ $transaction->patient->no_rm }}</span></div>
<div class="row"><span>Tgl. Periksa</span><span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span></div>
<div class="row"><span>Petugas</span><span>{{ $transaction->user?->name }}</span></div>
<div class="row"><span>No. Transaksi</span><span>{{ $transaction->no_transaksi }}</span></div>

{{-- HASIL PEMERIKSAAN --}}
@if($transaction->medicalRecord)
@php $rec = $transaction->medicalRecord; @endphp
<div class="section-title">Hasil Pemeriksaan</div>

@if($rec->gula_darah)
<div class="row">
    <span>Gula Darah</span>
    <span class="{{ $rec->gula_darah >= 200 ? 'abnormal' : 'normal' }}">
        {{ $rec->gula_darah }} mg/dl
    </span>
</div>
<div class="row nilai-normal">
    <span></span><span>Normal: &lt;200 mg/dl</span>
</div>
@endif

@if($rec->kolesterol)
<div class="row">
    <span>Kolesterol</span>
    <span class="{{ ($rec->kolesterol >= 160 && $rec->kolesterol <= 200) ? 'normal' : 'abnormal' }}">
        {{ $rec->kolesterol }} mg/dl
    </span>
</div>
<div class="row nilai-normal"><span></span><span>Normal: 160-200 mg/dl</span></div>
@endif

@if($rec->asam_urat)
<div class="row">
    <span>Asam Urat</span>
    <span>{{ $rec->asam_urat }} mg/dl</span>
</div>
<div class="row nilai-normal">
    <span></span><span>Pria: 3,4-7 · Wanita: 2,4-6 mg/dl</span>
</div>
@endif

@if($rec->tensi_sistolik)
<div class="row">
    <span>Tensi</span>
    <span class="{{ ($rec->tensi_sistolik <= 120 && $rec->tensi_diastolik <= 80) ? 'normal' : 'abnormal' }}">
        {{ $rec->tensi_sistolik }}/{{ $rec->tensi_diastolik }} mmHg
    </span>
</div>
<div class="row nilai-normal"><span></span><span>Normal: 120/80 mmHg</span></div>
@endif

@if($rec->suhu)
<div class="row"><span>Suhu</span><span>{{ $rec->suhu }} °C</span></div>
@endif
@if($rec->nadi)
<div class="row"><span>Nadi</span><span>{{ $rec->nadi }} x/mnt</span></div>
@endif
@if($rec->respirasi)
<div class="row"><span>Respirasi</span><span>{{ $rec->respirasi }} x/mnt</span></div>
@endif
@endif

{{-- ITEM TRANSAKSI --}}
<div class="section-title">Rincian Layanan & Produk</div>
<table>
    @foreach($transaction->items as $item)
    <tr>
        <td class="item-nama">{{ $item->nama_item }}
            @if($item->qty > 1)<br><span style="color:#666;">{{ $item->qty }}x</span>@endif
        </td>
        <td class="item-harga">
            Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
        </td>
        <td class="item-sub">
            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
        </td>
    </tr>
    @endforeach
    <tr class="total-row">
        <td colspan="2">Subtotal</td>
        <td class="right">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
    </tr>
    @if($transaction->diskon > 0)
    <tr>
        <td colspan="2">Diskon</td>
        <td class="right" style="color:#c00;">
            -Rp {{ number_format($transaction->diskon, 0, ',', '.') }}
        </td>
    </tr>
    @endif
    <tr style="border-top:2px solid #000;">
        <td colspan="2" class="bold total-big">TOTAL</td>
        <td class="right total-big">
            Rp {{ number_format($transaction->total, 0, ',', '.') }}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:9px;padding-top:2px;">
            Metode: CASH
        </td>
    </tr>
</table>

{{-- QR CODE --}}
<div class="qr-box">
    <div style="font-size:8px;margin-bottom:3px;">Scan untuk cek hasil pemeriksaan</div>
    <img src="data:image/svg+xml;base64,{{ $qrCode }}">
    <div class="no-rm-big">{{ $transaction->patient->no_rm }}</div>
    <div class="kode-unik">{{ $transaction->patient->kode_unik }}</div>
    <div style="font-size:7px;color:#777;margin-top:2px;">
        Gunakan No. RM + Kode di atas untuk login portal pasien
    </div>
</div>

{{-- FOOTER --}}
<div class="footer">
    <div class="bold">Terima Kasih Semoga Sehat Selalu</div>
    <div style="margin-top:2px;color:#666;">
        Info: smkkesbinatama@gmail.com
    </div>
</div>

</body>
</html>