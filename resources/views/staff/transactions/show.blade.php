@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')

<div class="row justify-content-center">
<div class="col-lg-8">

{{-- Header --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="text-muted mb-1" style="font-size:12px;">NO. TRANSAKSI</div>
                <h4 class="fw-bold mb-1">{{ $transaction->no_transaksi }}</h4>
                <div style="font-size:13px;color:#888;">
                    {{ $transaction->created_at->format('d M Y, H:i') }} WIB
                    · Petugas: {{ $transaction->user?->name }}
                </div>
            </div>
            <span class="badge bg-success px-3 py-2 fs-6">
                <i class="bi bi-check-circle me-1"></i>Selesai
            </span>
        </div>
    </div>
</div>

{{-- Pasien --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-header fw-semibold py-2"
         style="background:#f8f9fa;border-radius:12px 12px 0 0;">
        <i class="bi bi-person-fill me-2" style="color:var(--biru-muda);"></i>Data Pasien
    </div>
    <div class="card-body p-3">
        <div class="d-flex align-items-center gap-3">
            <div style="width:48px;height:48px;border-radius:50%;background:var(--biru-tua);
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-weight:700;font-size:20px;">
                {{ strtoupper(substr($transaction->patient->nama, 0, 1)) }}
            </div>
            <div>
                <div class="fw-bold fs-6">{{ $transaction->patient->nama }}</div>
                <div style="font-size:12px;color:#888;">
                    <code style="background:#e8f5e9;padding:2px 8px;border-radius:4px;
                                 color:var(--hijau);">
                        {{ $transaction->patient->no_rm }}
                    </code>
                    @if($transaction->patient->telepon)
                        · {{ $transaction->patient->telepon }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Item Transaksi --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-header fw-semibold py-2"
         style="background:#f8f9fa;border-radius:12px 12px 0 0;">
        <i class="bi bi-list-check me-2" style="color:var(--hijau);"></i>
        Detail Item ({{ $transaction->items->count() }} item)
    </div>
    <div class="table-responsive">
        <table class="table table-borderless mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#888;font-weight:600;">ITEM</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">TIPE</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">HARGA</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">QTY</th>
                    <th class="text-end pe-4"
                        style="font-size:12px;color:#888;font-weight:600;">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                <tr>
                    <td class="ps-4 fw-semibold" style="font-size:14px;">
                        {{ $item->nama_item }}
                    </td>
                    <td>
                        @php
                            $tipeStyle = match($item->item_type) {
                                'service' => 'bg-success-subtle text-success',
                                'package' => 'bg-primary-subtle text-primary',
                                default   => 'bg-warning-subtle text-warning',
                            };
                            $tipeLabel = match($item->item_type) {
                                'service' => 'Layanan',
                                'package' => 'Paket',
                                default   => 'Produk',
                            };
                        @endphp
                        <span class="badge {{ $tipeStyle }}">{{ $tipeLabel }}</span>
                    </td>
                    <td style="font-size:13px;">
                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                    </td>
                    <td style="font-size:13px;">{{ $item->qty }}x</td>
                    <td class="text-end pe-4 fw-semibold" style="color:var(--orange);">
                        {{ $item->subtotalFormatted() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="border-top:2px solid #eee;">
                <tr>
                    <td colspan="4" class="text-end fw-semibold ps-4">Subtotal</td>
                    <td class="text-end pe-4 fw-semibold">
                        Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                @if($transaction->diskon > 0)
                <tr>
                    <td colspan="4" class="text-end" style="color:var(--orange);">
                        Diskon
                    </td>
                    <td class="text-end pe-4" style="color:var(--orange);">
                        - Rp {{ number_format($transaction->diskon, 0, ',', '.') }}
                    </td>
                </tr>
                @endif
                <tr style="background:#e8f5e9;">
                    <td colspan="4" class="text-end fw-bold fs-5 ps-4">TOTAL</td>
                    <td class="text-end pe-4 fw-bold fs-5"
                        style="color:var(--hijau);">
                        {{ $transaction->totalFormatted() }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Tombol Aksi --}}
<div class="d-flex gap-2">
    <a href="{{ route('staff.transactions.nota', $transaction) }}"
       target="_blank" class="btn btn-success px-4 fw-semibold">
        <i class="bi bi-printer-fill me-2"></i>Cetak Nota PDF
    </a>
    <a href="{{ route('staff.transactions.index') }}"
       class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
    <a href="{{ route('staff.transactions.create') }}?patient_id={{ $transaction->patient_id }}"
       class="btn btn-primary px-4 ms-auto">
        <i class="bi bi-plus-circle me-2"></i>Transaksi Baru
    </a>
</div>

</div>
</div>
@endsection