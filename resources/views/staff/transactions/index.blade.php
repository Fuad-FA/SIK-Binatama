@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Transaksi Saya</h5>
        <p class="text-muted mb-0" style="font-size:13px;">
            Total {{ $transactions->total() }} transaksi
        </p>
    </div>
    <a href="{{ route('staff.transactions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle-fill me-2"></i>Transaksi Baru
    </a>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0"
                           placeholder="Cari No. Transaksi, nama pasien..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal" class="form-control"
                       value="{{ request('tanggal') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                <a href="{{ route('staff.transactions.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#888;font-weight:600;">NO. TRANSAKSI</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">PASIEN</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">ITEM</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">TOTAL</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">TANGGAL</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr>
                    <td class="ps-4">
                        <code style="background:#f0f4f8;padding:3px 8px;border-radius:4px;font-size:12px;">
                            {{ $trx->no_transaksi }}
                        </code>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:14px;">
                            {{ $trx->patient->nama }}
                        </div>
                        <div style="font-size:11px;color:#888;">
                            {{ $trx->patient->no_rm }}
                        </div>
                    </td>
                    <td>
                        <span class="badge"
                              style="background:#e3f2fd;color:var(--biru-tua);">
                            {{ $trx->items->count() }} item
                        </span>
                    </td>
                    <td class="fw-bold" style="color:var(--hijau);">
                        {{ $trx->totalFormatted() }}
                        @if($trx->diskon > 0)
                            <div style="font-size:11px;color:var(--orange);">
                                Diskon: Rp {{ number_format($trx->diskon, 0, ',', '.') }}
                            </div>
                        @endif
                    </td>
                    <td style="font-size:13px;color:#888;">
                        {{ $trx->created_at->format('d M Y, H:i') }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('staff.transactions.show', $trx) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('staff.transactions.nota', $trx) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-success" title="Cetak Nota">
                                <i class="bi bi-printer"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-receipt fs-1 d-block mb-2 opacity-25"></i>
                        Belum ada transaksi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@endsection