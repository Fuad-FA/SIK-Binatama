@extends('layouts.app')
@section('title', 'Import Data Staf')
@section('page-title', 'Import Data Staf')

@section('content')

<div class="row g-4">

{{-- Form Import --}}
<div class="col-lg-5">
    <div class="card border-0 shadow-sm" style="border-radius:12px;">
        <div class="card-header fw-bold text-white py-3"
             style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
            <i class="bi bi-file-earmark-arrow-up me-2"></i>Import dari Excel
        </div>
        <div class="card-body p-4">

            {{-- Info format file --}}
            <div class="alert py-2 mb-4"
                 style="background:#e3f2fd;border-left:3px solid var(--biru-muda);
                        border-radius:8px;font-size:13px;">
                <i class="bi bi-info-circle-fill me-2" style="color:var(--biru-muda);"></i>
                File harus berformat <strong>.xlsx / .xls</strong> dengan struktur:
                <ul class="mb-0 mt-1 ps-3">
                    <li>Sheet <strong>"GURU KARYAWAN"</strong>: kolom NO, NAMA, BARCODE, JABATAN</li>
                    <li>Sheet <strong>"SISWA"</strong>: kolom NO, BARCODE, NAMA</li>
                </ul>
            </div>

            <form action="{{ route('admin.import.post') }}" method="POST"
                  enctype="multipart/form-data">
                @csrf

                {{-- Upload file --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        File Excel <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="file"
                           class="form-control @error('file') is-invalid @enderror"
                           accept=".xlsx,.xls,.csv">
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Format: .xlsx atau .xls · Maks: 5MB</div>
                </div>

                {{-- Pilih sheet --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Import Sheet <span class="text-danger">*</span>
                    </label>
                    <select name="sheet"
                            class="form-select @error('sheet') is-invalid @enderror">
                        <option value="semua">Semua (Guru & Siswa)</option>
                        <option value="guru">Guru saja</option>
                        <option value="siswa">Siswa saja</option>
                    </select>
                    @error('sheet')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Dry run --}}
                <div class="mb-4">
                    <div class="form-check p-3 rounded"
                         style="background:#fff8e1;border:1px solid #ffe082;">
                        <input class="form-check-input" type="checkbox"
                               name="dry_run" id="dry_run" value="1">
                        <label class="form-check-label" for="dry_run">
                            <span class="fw-semibold" style="color:var(--orange);">
                                <i class="bi bi-eye me-1"></i>Mode Simulasi (Dry Run)
                            </span>
                            <div style="font-size:12px;color:#666;margin-top:2px;">
                                Cek dulu tanpa menyimpan data — aman untuk testing
                            </div>
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-upload me-2"></i>Mulai Import
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Password info --}}
    <div class="card border-0 shadow-sm mt-3" style="border-radius:12px;">
        <div class="card-body p-3">
            <div class="fw-semibold mb-2" style="font-size:13px;">
                <i class="bi bi-key-fill me-2" style="color:var(--orange);"></i>
                Password Default
            </div>
            <table class="table table-sm table-borderless mb-0" style="font-size:13px;">
                <tr>
                    <td class="text-muted">Guru</td>
                    <td><code>password</code> — wajib ganti saat login pertama</td>
                </tr>
                <tr>
                    <td class="text-muted">Siswa</td>
                    <td><code>siswa</code> — wajib ganti saat login pertama</td>
                </tr>
            </table>
        </div>
    </div>
</div>

{{-- Hasil Import --}}
<div class="col-lg-7">
    @if(session('import_results'))
    @php
        $results  = session('import_results');
        $isDryRun = session('dry_run');
        $totalOk  = ($results['guru']['berhasil'] ?? 0) + ($results['siswa']['berhasil'] ?? 0);
        $totalDup = ($results['guru']['duplikat'] ?? 0) + ($results['siswa']['duplikat'] ?? 0);
        $totalErr = ($results['guru']['error']    ?? 0) + ($results['siswa']['error']    ?? 0);
    @endphp

    {{-- Ringkasan --}}
    <div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
        <div class="card-header fw-bold py-3 text-white"
             style="background:{{ $isDryRun ? '#F57C00' : '#2E7D32' }};
                    border-radius:12px 12px 0 0;">
            <i class="bi bi-{{ $isDryRun ? 'eye' : 'check-circle' }}-fill me-2"></i>
            {{ $isDryRun ? 'Hasil Simulasi (Dry Run)' : 'Hasil Import' }}
        </div>
        <div class="card-body p-3">
            <div class="row g-3 text-center">
                <div class="col-4">
                    <div style="font-size:36px;font-weight:800;
                                color:{{ $isDryRun ? 'var(--orange)' : 'var(--hijau)' }};">
                        {{ $totalOk }}
                    </div>
                    <div class="text-muted" style="font-size:12px;">
                        {{ $isDryRun ? 'Akan diimport' : 'Berhasil' }}
                    </div>
                </div>
                <div class="col-4">
                    <div style="font-size:36px;font-weight:800;color:#888;">
                        {{ $totalDup }}
                    </div>
                    <div class="text-muted" style="font-size:12px;">Duplikat (dilewati)</div>
                </div>
                <div class="col-4">
                    <div style="font-size:36px;font-weight:800;color:#c62828;">
                        {{ $totalErr }}
                    </div>
                    <div class="text-muted" style="font-size:12px;">Error</div>
                </div>
            </div>

            {{-- Per sheet --}}
            <div class="row g-2 mt-2">
                @if($results['guru']['berhasil'] > 0 || $results['guru']['duplikat'] > 0)
                <div class="col-6">
                    <div class="p-2 rounded text-center"
                         style="background:#e8f5e9;font-size:12px;">
                        <div class="fw-bold" style="color:var(--hijau);">Guru/Karyawan</div>
                        <div>{{ $results['guru']['berhasil'] }} berhasil
                             · {{ $results['guru']['duplikat'] }} duplikat</div>
                    </div>
                </div>
                @endif
                @if($results['siswa']['berhasil'] > 0 || $results['siswa']['duplikat'] > 0)
                <div class="col-6">
                    <div class="p-2 rounded text-center"
                         style="background:#e3f2fd;font-size:12px;">
                        <div class="fw-bold" style="color:var(--biru-muda);">Siswa</div>
                        <div>{{ $results['siswa']['berhasil'] }} berhasil
                             · {{ $results['siswa']['duplikat'] }} duplikat</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Log detail --}}
    <div class="card border-0 shadow-sm" style="border-radius:12px;">
        <div class="card-header fw-bold py-2"
             style="background:#f8f9fa;border-radius:12px 12px 0 0;font-size:13px;">
            <i class="bi bi-list-check me-2"></i>Log Detail
            <span class="text-muted fw-normal">
                ({{ count($results['guru']['log']) + count($results['siswa']['log']) }} baris)
            </span>
        </div>
        <div class="card-body p-0">
            <div style="max-height:400px;overflow-y:auto;font-size:12px;font-family:monospace;">

                @if(!empty($results['guru']['log']))
                <div class="px-3 py-2 fw-bold"
                     style="background:#e8f5e9;color:var(--hijau);font-size:11px;
                            letter-spacing:1px;text-transform:uppercase;">
                    GURU / KARYAWAN
                </div>
                @foreach($results['guru']['log'] as $log)
                <div class="px-3 py-1 border-bottom d-flex align-items-center gap-2"
                     style="color:{{ $log['type'] === 'ok' ? '#2E7D32' :
                                     ($log['type'] === 'skip' ? '#888' :
                                     ($log['type'] === 'dry' ? '#F57C00' : '#c62828')) }};">
                    <i class="bi bi-{{ $log['type'] === 'ok' ? 'check-circle-fill' :
                                       ($log['type'] === 'skip' ? 'dash-circle' :
                                       ($log['type'] === 'dry' ? 'eye' : 'x-circle-fill')) }}"
                       style="flex-shrink:0;"></i>
                    {{ $log['msg'] }}
                </div>
                @endforeach
                @endif

                @if(!empty($results['siswa']['log']))
                <div class="px-3 py-2 fw-bold"
                     style="background:#e3f2fd;color:var(--biru-muda);font-size:11px;
                            letter-spacing:1px;text-transform:uppercase;">
                    SISWA
                </div>
                @foreach($results['siswa']['log'] as $log)
                <div class="px-3 py-1 border-bottom d-flex align-items-center gap-2"
                     style="color:{{ $log['type'] === 'ok' ? '#1565C0' :
                                     ($log['type'] === 'skip' ? '#888' :
                                     ($log['type'] === 'dry' ? '#F57C00' : '#c62828')) }};">
                    <i class="bi bi-{{ $log['type'] === 'ok' ? 'check-circle-fill' :
                                       ($log['type'] === 'skip' ? 'dash-circle' :
                                       ($log['type'] === 'dry' ? 'eye' : 'x-circle-fill')) }}"
                       style="flex-shrink:0;"></i>
                    {{ $log['msg'] }}
                </div>
                @endforeach
                @endif

            </div>
        </div>
    </div>
    @else
    {{-- Placeholder sebelum import --}}
    <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
        <div class="card-body d-flex flex-column align-items-center justify-content-center py-5 text-muted">
            <i class="bi bi-file-earmark-arrow-up fs-1 mb-3 opacity-25"></i>
            <div class="fw-semibold mb-1">Belum ada hasil import</div>
            <div style="font-size:13px;">Upload file Excel lalu klik "Mulai Import"</div>
        </div>
    </div>
    @endif
</div>

</div>
@endsection