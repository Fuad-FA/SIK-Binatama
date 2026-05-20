@extends('layouts.app')
@section('title', 'Dashboard Staf')
@section('page-title', 'Dashboard Staf')

@section('content')

{{-- Greeting --}}
<div class="mb-4">
    <h5 class="fw-bold mb-0">
        Selamat datang, {{ auth()->user()->name }}!
        <span class="badge ms-2 px-3 py-1
            {{ auth()->user()->role === 'guru' ? 'bg-success' : 'bg-primary' }}">
            {{ ucfirst(auth()->user()->role) }}
        </span>
    </h5>
    <p class="text-muted mb-0" style="font-size:13px;">
        {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
    </p>
</div>

{{-- Alert transaksi pending rekam medis --}}
{{-- @if(isset($pendingTransactions) && $pendingTransactions->count() > 0)
<div class="card border-0 shadow-sm mb-4"
     style="border-radius:12px;border-left:4px solid var(--orange) !important;">
    <div class="card-body p-3">
        <div class="fw-bold mb-2" style="color:var(--orange);font-size:14px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Ada {{ $pendingTransactions->count() }} transaksi yang belum diisi hasil pemeriksaannya!
        </div>
        @foreach($pendingTransactions as $pending)
        @php
            $itemNames = $pending->items->pluck('nama_item')->toArray();
            $fields    = implode(',', array_unique(array_filter([
                in_array(true, array_map(fn($n) => str_contains(strtolower($n), 'gula'), $itemNames)) ? 'gula_darah' : null,
                in_array(true, array_map(fn($n) => str_contains(strtolower($n), 'kolesterol'), $itemNames)) ? 'kolesterol' : null,
                in_array(true, array_map(fn($n) => str_contains(strtolower($n), 'asam'), $itemNames)) ? 'asam_urat' : null,
                in_array(true, array_map(fn($n) => str_contains(strtolower($n), 'tekanan') || str_contains(strtolower($n), 'tensi'), $itemNames)) ? 'tensi' : null,
                in_array(true, array_map(fn($n) => str_contains(strtolower($n), 'suhu'), $itemNames)) ? 'suhu' : null,
                in_array(true, array_map(fn($n) => str_contains(strtolower($n), 'nadi'), $itemNames)) ? 'nadi' : null,
                in_array(true, array_map(fn($n) => str_contains(strtolower($n), 'respirasi'), $itemNames)) ? 'respirasi' : null,
            ])));
        @endphp
        <div class="d-flex align-items-center gap-3 p-2 rounded mb-2"
             style="background:#fff8e1;">
            <div class="flex-grow-1">
                <div class="fw-semibold" style="font-size:13px;">
                    {{ $pending->patient->nama }}
                    <code style="font-size:11px;background:#ffe0b2;padding:1px 6px;
                                 border-radius:3px;color:var(--orange);">
                        {{ $pending->no_transaksi }}
                    </code>
                </div>
                <div class="text-muted" style="font-size:11px;">
                    {{ $pending->items->pluck('nama_item')->join(', ') }}
                    · {{ $pending->created_at->diffForHumans() }}
                </div>
            </div>
            <a href="{{ route('staff.medical-records.create', [
                            'patient_id'     => $pending->patient_id,
                            'transaction_id' => $pending->id,
                            'fields'         => $fields,
                       ]) }}"
               class="btn btn-warning btn-sm fw-semibold">
                <i class="bi bi-clipboard2-pulse me-1"></i>Input Sekarang
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif --}}

@if(isset($pendingTransactions) && $pendingTransactions->count() > 0)
<div class="card border-0 shadow-sm mb-4"
     style="border-radius:12px;border-left:4px solid var(--orange) !important;">
    <div class="card-body p-3">
        <div class="fw-bold mb-2" style="color:var(--orange);font-size:14px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Ada {{ $pendingTransactions->count() }} transaksi yang belum diisi hasil pemeriksaannya!
        </div>
        @foreach($pendingTransactions as $pending)
        @php
            $itemNames    = $pending->items->pluck('nama_item')->toArray();
            // $pendingFields = $this->getFieldsFromItems($itemNames);
            // Tidak bisa panggil $this di view, jadi hitung langsung
            $fieldsStr = implode(',', array_unique(array_filter([
                collect($itemNames)->contains(fn($n) => str_contains(strtolower($n), 'cek gula darah')) ? 'gula_darah' : null,
                collect($itemNames)->contains(fn($n) => str_contains(strtolower($n), 'cek kolesterol')) ? 'kolesterol' : null,
                collect($itemNames)->contains(fn($n) => str_contains(strtolower($n), 'cek asam urat')) ? 'asam_urat' : null,
                collect($itemNames)->contains(fn($n) => str_contains(strtolower($n), 'cek tekanan darah')) ? 'tensi' : null,
                collect($itemNames)->contains(fn($n) => str_contains(strtolower($n), 'cek suhu')) ? 'suhu' : null,
                collect($itemNames)->contains(fn($n) => str_contains(strtolower($n), 'cek nadi')) ? 'nadi' : null,
                collect($itemNames)->contains(fn($n) => str_contains(strtolower($n), 'cek respirasi')) ? 'respirasi' : null,
                collect($itemNames)->contains(fn($n) => str_contains(strtolower($n), 'cek bmi') || str_contains(strtolower($n), 'cek antropometri')) ? 'bmi' : null,
                collect($itemNames)->contains(fn($n) => str_contains(strtolower($n), 'konsultasi gizi')) ? 'catatan_gizi' : null,
                collect($itemNames)->contains(fn($n) => str_contains(strtolower($n), 'paket sehat')) ? 'gula_darah,kolesterol,asam_urat,tensi,suhu,nadi,respirasi' : null,
            ])));
        @endphp
        <div class="d-flex align-items-center gap-3 p-2 rounded mb-2"
             style="background:#fff8e1;">
            <div class="flex-grow-1">
                <div class="fw-semibold" style="font-size:13px;">
                    {{ $pending->patient->nama }}
                    <code style="font-size:11px;background:#ffe0b2;padding:1px 6px;
                                 border-radius:3px;color:var(--orange);">
                        {{ $pending->no_transaksi }}
                    </code>
                </div>
                <div class="text-muted" style="font-size:11px;">
                    {{ $pending->items->pluck('nama_item')->join(', ') }}
                    · {{ $pending->created_at->diffForHumans() }}
                </div>
            </div>
            <a href="{{ route('staff.medical-records.create', [
                            'patient_id'     => $pending->patient_id,
                            'transaction_id' => $pending->id,
                            'fields'         => $fieldsStr,
                       ]) }}"
               class="btn btn-warning btn-sm fw-semibold">
                <i class="bi bi-clipboard2-pulse me-1"></i>Input Sekarang
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#e3f2fd;border-radius:10px;padding:10px;flex-shrink:0;">
                    <i class="bi bi-person-plus-fill fs-4" style="color:var(--biru-muda);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:11px;">Pasien Saya</div>
                    <div class="fw-bold fs-3 lh-1">{{ $stats['input_pasien'] }}</div>
                    <div style="font-size:11px;color:#aaa;">bulan ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card green p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#e8f5e9;border-radius:10px;padding:10px;flex-shrink:0;">
                    <i class="bi bi-clipboard2-pulse-fill fs-4" style="color:var(--hijau);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:11px;">Pemeriksaan</div>
                    <div class="fw-bold fs-3 lh-1">{{ $stats['periksa_hari_ini'] }}</div>
                    <div style="font-size:11px;color:#aaa;">hari ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card yellow p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#fffde7;border-radius:10px;padding:10px;flex-shrink:0;">
                    <i class="bi bi-cart-fill fs-4" style="color:var(--orange);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:11px;">Transaksi</div>
                    <div class="fw-bold fs-3 lh-1">{{ $stats['transaksi_hari_ini'] }}</div>
                    <div style="font-size:11px;color:#aaa;">hari ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card orange p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#fff3e0;border-radius:10px;padding:10px;flex-shrink:0;">
                    <i class="bi bi-bar-chart-fill fs-4" style="color:var(--orange);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:11px;">Total Bulan Ini</div>
                    <div class="fw-bold lh-1" style="font-size:15px;">
                        {{ $stats['rekam_bulan_ini'] }} rekam
                    </div>
                    <div style="font-size:11px;color:#aaa;">
                        {{ $stats['transaksi_bulan_ini'] }} transaksi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-body p-3">
                <div class="fw-semibold mb-3" style="font-size:13px;color:#555;">
                    <i class="bi bi-lightning-fill me-2" style="color:var(--kuning);"></i>
                    Aksi Cepat
                {{-- </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('staff.patients.create') }}"
                       class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-2"></i>Daftarkan Pasien
                    </a>
                    <a href="{{ route('staff.medical-records.create') }}"
                       class="btn btn-success">
                        <i class="bi bi-clipboard2-pulse-fill me-2"></i>Input Pemeriksaan
                    </a>
                    <a href="{{ route('staff.transactions.create') }}"
                       class="btn btn-warning fw-semibold">
                        <i class="bi bi-cart-plus-fill me-2"></i>Buat Transaksi
                    </a>
                    <a href="{{ route('staff.patients.index') }}"
                       class="btn btn-outline-primary">
                        <i class="bi bi-search me-2"></i>Cari Pasien
                    </a>
                </div> --}}
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('staff.patients.create') }}"
                       class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-2"></i>Daftarkan Pasien
                    </a>
                    <a href="{{ route('staff.transactions.create') }}"
                       class="btn btn-success fw-semibold">
                        <i class="bi bi-cart-plus-fill me-2"></i>Transaksi & Pemeriksaan
                    </a>
                    <a href="{{ route('staff.patients.index') }}"
                       class="btn btn-outline-primary">
                        <i class="bi bi-search me-2"></i>Cari Pasien
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Aktivitas Terakhir --}}
<div class="row g-3">

    {{-- Pemeriksaan Terakhir --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white fw-bold py-3"
                 style="border-radius:12px 12px 0 0;border-bottom:1px solid #f0f0f0;font-size:14px;">
                <i class="bi bi-clipboard2-pulse-fill me-2" style="color:var(--hijau);"></i>
                Pemeriksaan Terakhir Saya
            </div>
            @if($recentRecords->isEmpty())
                <div class="text-center py-4 text-muted" style="font-size:13px;">
                    <i class="bi bi-clipboard2 d-block fs-3 mb-1 opacity-25"></i>
                    Belum ada pemeriksaan.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        @foreach($recentRecords as $rec)
                        <tr>
                            <td class="ps-3">
                                <div class="fw-semibold" style="font-size:13px;">
                                    {{ $rec->patient->nama }}
                                </div>
                                <div style="font-size:11px;color:#888;">
                                    {{ $rec->tanggal_periksa->format('d M Y') }}
                                </div>
                            </td>
                            <td style="font-size:12px;">
                                @if($rec->gula_darah)
                                    <span class="{{ $rec->gula_darah >= 200 ? 'text-danger' : 'text-success' }}">
                                        GD: {{ $rec->gula_darah }}
                                    </span>
                                @endif
                                @if($rec->tensi_sistolik)
                                    <span class="{{ $rec->tensi_sistolik > 120 ? 'text-danger' : 'text-success' }} ms-2">
                                        T: {{ $rec->tensi_sistolik }}/{{ $rec->tensi_diastolik }}
                                    </span>
                                @endif
                            </td>
                            <td style="font-size:11px;color:#aaa;">
                                {{ $rec->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Transaksi Terakhir --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white fw-bold py-3"
                 style="border-radius:12px 12px 0 0;border-bottom:1px solid #f0f0f0;font-size:14px;">
                <i class="bi bi-receipt me-2" style="color:var(--orange);"></i>
                Transaksi Terakhir Saya
            </div>
            @if($recentTransactions->isEmpty())
                <div class="text-center py-4 text-muted" style="font-size:13px;">
                    <i class="bi bi-receipt d-block fs-3 mb-1 opacity-25"></i>
                    Belum ada transaksi.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        @foreach($recentTransactions as $trx)
                        <tr>
                            <td class="ps-3">
                                <code style="font-size:11px;background:#f0f4f8;
                                             padding:2px 6px;border-radius:3px;">
                                    {{ $trx->no_transaksi }}
                                </code>
                                <div style="font-size:12px;color:#888;margin-top:1px;">
                                    {{ $trx->patient->nama }}
                                </div>
                            </td>
                            <td class="fw-bold" style="color:var(--hijau);font-size:13px;">
                                {{ $trx->totalFormatted() }}
                            </td>
                            <td style="font-size:11px;color:#aaa;">
                                {{ $trx->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection