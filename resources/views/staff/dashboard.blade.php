@extends('layouts.app')

@section('title', 'Dashboard Staf')
@section('page-title', 'Dashboard Staf')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#e3f2fd;border-radius:10px;padding:10px;">
                    <i class="bi bi-person-plus-fill fs-4" style="color:var(--biru-muda);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Input Pasien</div>
                    <div class="fw-bold fs-4">0</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card green p-3">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#e8f5e9;border-radius:10px;padding:10px;">
                    <i class="bi bi-clipboard2-pulse-fill fs-4" style="color:var(--hijau);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Pemeriksaan Hari Ini</div>
                    <div class="fw-bold fs-4">0</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card yellow p-3">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#fffde7;border-radius:10px;padding:10px;">
                    <i class="bi bi-cart-fill fs-4" style="color:var(--orange);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Transaksi Hari Ini</div>
                    <div class="fw-bold fs-4">0</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card orange p-3">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#fff3e0;border-radius:10px;padding:10px;">
                    <i class="bi bi-person-badge-fill fs-4" style="color:var(--orange);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Role Saya</div>
                    <div class="fw-bold" style="font-size:14px;">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-4 text-center py-5">
        <i class="bi bi-check-circle-fill fs-1" style="color:var(--hijau);"></i>
        <h5 class="mt-3 fw-bold">Selamat datang, {{ auth()->user()->name }}!</h5>
        <p class="text-muted">Gunakan menu di sidebar untuk mulai melayani pasien.</p>
    </div>
</div>
@endsection