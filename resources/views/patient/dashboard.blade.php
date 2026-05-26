<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien — Rumah Sehat Binatama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2E7D32">
    <style>
        :root {
            --hijau    : #2E7D32;
            --biru-tua : #1565C0;
            --kuning   : #FDD835;
            --orange   : #F57C00;
        }
        body { font-family:'Segoe UI',sans-serif; background:#f0f4f8; }

        .topbar {
            background: linear-gradient(135deg, #1B5E20, #2E7D32);
            padding: 12px 20px;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar .brand { color: var(--kuning); font-weight: 800; font-size: 15px; }
        .topbar .sub   { color: rgba(255,255,255,0.7); font-size: 11px; }

        .patient-card {
            background: linear-gradient(135deg, #1B5E20, #2E7D32);
            border-radius: 16px;
            padding: 24px;
            color: #fff;
            margin: 16px;
        }
        .patient-card .no-rm {
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-block;
            margin-top: 4px;
        }
        .patient-card .kode {
            background: var(--kuning);
            color: var(--hijau);
            padding: 6px 16px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 3px;
            display: inline-block;
            margin-top: 8px;
        }

        .stat-pill {
            background: #fff;
            border-radius: 12px;
            padding: 12px 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .result-card {
            background: #fff;
            border-radius: 12px;
            margin: 0 16px 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .result-card .rc-header {
            padding: 12px 16px;
            font-weight: 700;
            font-size: 13px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .result-item {
            padding: 10px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f8f8f8;
            font-size: 13px;
        }
        .result-item:last-child { border-bottom: none; }
        .result-label { color: #666; }
        .result-value { font-weight: 700; }
        .normal   { color: #2E7D32; }
        .abnormal { color: #c62828; }
        .result-badge-normal   { background: #e8f5e9; color: #2E7D32; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .result-badge-abnormal { background: #ffebee; color: #c62828; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }



        .card-record{
    background:#fff;
    border-radius:12px;
    margin:0 16px 16px;
    box-shadow:0 2px 8px rgba(0,0,0,0.06);
    overflow:hidden;
}

.record-header{
    background:linear-gradient(135deg,#1B5E20,#2E7D32);
    color:#fff;
    padding:14px 16px;
}

.record-item{
    padding:12px 16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #f3f3f3;
}

.record-item:last-child{
    border-bottom:none;
}

.record-label{
    font-size:13px;
    color:#666;
}

.record-value{
    font-weight:700;
    font-size:13px;
}

.record-item .badge{
    font-size:10px;
    border-radius:20px;
    font-weight:600;
}

.record-item .text-end{
    display:flex;
    flex-direction:column;
    align-items:flex-end;
    gap:4px;
}

.record-item .record-value{
    line-height:1.3;
}


        .nav-bottom {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: #fff;
            border-top: 1px solid #eee;
            display: flex;
            z-index: 100;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.06);
        }
        .nav-bottom a {
            flex: 1;
            text-align: center;
            padding: 10px 0 6px;
            color: #aaa;
            text-decoration: none;
            font-size: 10px;
        }
        .nav-bottom a.active { color: var(--hijau); }
        .nav-bottom a i { display: block; font-size: 20px; margin-bottom: 2px; }

        .content-area { padding-bottom: 70px; }
    </style>
</head>
<body>

{{-- Topbar --}}
<div class="topbar d-flex align-items-center justify-content-between">
    <div>
        <div class="brand">
            <i class="bi bi-heart-pulse-fill me-1"></i>Rumah Sehat Binatama
        </div>
        <div class="sub">Portal Pasien</div>
    </div>
    <form action="{{ route('patient.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm"
                style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);">
            <i class="bi bi-box-arrow-right"></i>
        </button>
    </form>
</div>

<div class="content-area">

    {{-- Kartu Pasien --}}
    <div class="patient-card">
        <div style="font-size:12px;opacity:0.8;">Selamat datang,</div>
        <div style="font-size:20px;font-weight:800;">{{ $patient->nama }}</div>
        <div class="no-rm">
            <i class="bi bi-card-text me-1"></i>{{ $patient->no_rm }}
        </div>
        <div style="font-size:11px;opacity:0.7;margin-top:6px;">
            @if($patient->tanggal_lahir)
                {{ $patient->tanggal_lahir->format('d M Y') }} ·
                {{ (int) $patient->tanggal_lahir->diffInYears(now()) }} tahun ·
            @endif
            {{ $patient->jenis_kelamin === 'L' ? 'Laki-laki' : ($patient->jenis_kelamin === 'P' ? 'Perempuan' : '') }}
        </div>
    </div>

    {{-- Statistik --}}
    <div class="px-4 mb-3">
        <div class="row g-2">
            <div class="col-6">
                <div class="stat-pill text-center">
                    <div style="font-size:28px;font-weight:800;color:var(--hijau);">
                        {{ $totalVisit }}
                    </div>
                    <div style="font-size:12px;color:#888;">Total Kunjungan</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-pill text-center">
                    <div style="font-size:28px;font-weight:800;color:var(--orange);">
                        {{ $latestRecord ? $latestRecord->tanggal_periksa->format('d/m') : '-' }}
                    </div>
                    <div style="font-size:12px;color:#888;">Terakhir Periksa</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hasil Pemeriksaan Terakhir --}}
    {{-- @if($latestRecord)
    <div class="result-card">
        <div class="rc-header"
             style="background:#f1f8e9;">
            <i class="bi bi-clipboard2-pulse-fill" style="color:var(--hijau);"></i>
            Hasil Pemeriksaan Terakhir
            <span class="ms-auto" style="font-size:11px;color:#888;font-weight:400;">
                {{ $latestRecord->tanggal_periksa->format('d M Y') }}
            </span>
        </div>

        @if($latestRecord->gula_darah)
        <div class="result-item">
            <div>
                <div class="result-label">Gula Darah</div>
                <div style="font-size:10px;color:#aaa;">Normal: &lt;200 mg/dl</div>
            </div>
            <div class="text-end">
                <div class="result-value {{ $latestRecord->gula_darah >= 200 ? 'abnormal' : 'normal' }}">
                    {{ $latestRecord->gula_darah }} mg/dl
                </div>
                @if($latestRecord->gula_darah >= 200)
                    <span class="result-badge-abnormal">Tinggi</span>
                @else
                    <span class="result-badge-normal">Normal</span>
                @endif
            </div>
        </div>
        @endif

        @if($latestRecord->kolesterol)
        <div class="result-item">
            <div>
                <div class="result-label">Kolesterol</div>
                <div style="font-size:10px;color:#aaa;">Normal: 160-200 mg/dl</div>
            </div>
            <div class="text-end">
                @php $kolOk = $latestRecord->kolesterol >= 160 && $latestRecord->kolesterol <= 200; @endphp
                <div class="result-value {{ $kolOk ? 'normal' : 'abnormal' }}">
                    {{ $latestRecord->kolesterol }} mg/dl
                </div>
                <span class="{{ $kolOk ? 'result-badge-normal' : 'result-badge-abnormal' }}">
                    {{ $kolOk ? 'Normal' : 'Tidak Normal' }}
                </span>
            </div>
        </div>
        @endif

        @if($latestRecord->asam_urat)
        <div class="result-item">
            <div>
                <div class="result-label">Asam Urat</div>
                <div style="font-size:10px;color:#aaa;">
                    Pria: 3,4-7 · Wanita: 2,4-6 mg/dl
                </div>
            </div>
            <div class="text-end">
                <div class="result-value">{{ $latestRecord->asam_urat }} mg/dl</div>
                <span class="result-badge-normal">Lihat detail</span>
            </div>
        </div>
        @endif

        @if($latestRecord->tensi_sistolik)
        <div class="result-item">
            <div>
                <div class="result-label">Tekanan Darah</div>
                <div style="font-size:10px;color:#aaa;">Normal: 120/80 mmHg</div>
            </div>
            <div class="text-end">
                @php
                    $tensiOk = $latestRecord->tensi_sistolik <= 120 && $latestRecord->tensi_diastolik <= 80;
                @endphp
                <div class="result-value {{ $tensiOk ? 'normal' : 'abnormal' }}">
                    {{ $latestRecord->tensi_sistolik }}/{{ $latestRecord->tensi_diastolik }} mmHg
                </div>
                <span class="{{ $tensiOk ? 'result-badge-normal' : 'result-badge-abnormal' }}">
                    {{ $latestRecord->statusTensi() }}
                </span>
            </div>
        </div>
        @endif

        @if($latestRecord->suhu || $latestRecord->nadi || $latestRecord->respirasi)
        <div class="result-item" style="background:#fafafa;">
            @if($latestRecord->suhu)
            <div class="text-center">
                <div class="result-label">Suhu</div>
                <div class="result-value">{{ $latestRecord->suhu }}°C</div>
            </div>
            @endif
            @if($latestRecord->nadi)
            <div class="text-center">
                <div class="result-label">Nadi</div>
                <div class="result-value">{{ $latestRecord->nadi }} x/mnt</div>
            </div>
            @endif
            @if($latestRecord->respirasi)
            <div class="text-center">
                <div class="result-label">Respirasi</div>
                <div class="result-value">{{ $latestRecord->respirasi }} x/mnt</div>
            </div>
            @endif
        </div>
        @endif

        <div class="p-3 text-center">
            <a href="{{ route('patient.records') }}"
               class="btn btn-sm btn-outline-success">
                <i class="bi bi-clock-history me-1"></i>Lihat Semua Riwayat
            </a>
        </div>
    </div>
    @else
    <div class="result-card">
        <div class="text-center py-4 text-muted">
            <i class="bi bi-clipboard2 fs-1 d-block mb-2 opacity-25"></i>
            <div style="font-size:13px;">Belum ada riwayat pemeriksaan.</div>
        </div>
    </div>
    @endif --}}


    {{-- Hasil Pemeriksaan Terakhir --}}
@if($latestRecord)
<div class="card-record mb-3">
    <div class="record-header d-flex justify-content-between align-items-center">
        <div>
            <div class="fw-bold" style="font-size:15px;">
                {{ $latestRecord->tanggal_periksa->format('d M Y') }}
            </div>
            <div style="font-size:12px;opacity:0.8;">
                Petugas: {{ $latestRecord->user?->name ?? '-' }}
            </div>
        </div>
        <span class="badge"
              style="background:rgba(255,255,255,0.2);font-size:11px;">
            Pemeriksaan Terakhir
        </span>
    </div>

    {{-- Gula Darah --}}
    @if($latestRecord->gula_darah)
    <div class="record-item">
        <span class="record-label">Gula Darah</span>
        <div class="text-end">
            <span class="record-value">{{ number_format($latestRecord->gula_darah, 2) }} mg/dl</span>
            <span class="badge ms-1 px-2 py-1"
                  style="background:{{ $latestRecord->gula_darah < 200 ? '#e8f5e9' : '#ffebee' }};
                         color:{{ $latestRecord->gula_darah < 200 ? '#2E7D32' : '#c62828' }};
                         font-size:10px;">
                {{ $latestRecord->gula_darah < 200 ? 'Normal' : 'Tinggi' }}
            </span>
        </div>
    </div>
    @endif

    {{-- Kolesterol --}}
    @if($latestRecord->kolesterol)
    @php $kolOk = $latestRecord->kolesterol >= 160 && $latestRecord->kolesterol <= 200; @endphp
    <div class="record-item">
        <span class="record-label">Kolesterol</span>
        <div class="text-end">
            <span class="record-value">{{ number_format($latestRecord->kolesterol, 2) }} mg/dl</span>
            <span class="badge ms-1 px-2 py-1"
                  style="background:{{ $kolOk ? '#e8f5e9' : '#ffebee' }};
                         color:{{ $kolOk ? '#2E7D32' : '#c62828' }};
                         font-size:10px;">
                {{ $kolOk ? 'Normal' : 'Abnormal' }}
            </span>
        </div>
    </div>
    @endif

    {{-- Asam Urat --}}
    @if($latestRecord->asam_urat)
    @php $auOk = $latestRecord->asam_urat >= 2.4 && $latestRecord->asam_urat <= 7; @endphp
    <div class="record-item">
        <span class="record-label">Asam Urat</span>
        <div class="text-end">
            <span class="record-value">{{ number_format($latestRecord->asam_urat, 2) }} mg/dl</span>
            <span class="badge ms-1 px-2 py-1"
                  style="background:{{ $auOk ? '#e8f5e9' : '#ffebee' }};
                         color:{{ $auOk ? '#2E7D32' : '#c62828' }};font-size:10px;">
                {{ $auOk ? 'Normal' : 'Abnormal' }}
            </span>
        </div>
    </div>
    @endif

    {{-- Tekanan Darah --}}
    @if($latestRecord->tensi_sistolik)
    @php
        $tensiOk = $latestRecord->tensi_sistolik <= 120 && $latestRecord->tensi_diastolik <= 80;
        $tensiStatus = $tensiOk ? 'Normal'
            : ($latestRecord->tensi_sistolik <= 139 ? 'Prehipertensi' : 'Hipertensi');
        $tensiWarna = $tensiOk ? '#2E7D32'
            : ($latestRecord->tensi_sistolik <= 139 ? '#F57C00' : '#c62828');
        $tensiBg = $tensiOk ? '#e8f5e9'
            : ($latestRecord->tensi_sistolik <= 139 ? '#fff8e1' : '#ffebee');
    @endphp
    <div class="record-item">
        <span class="record-label">Tekanan Darah</span>
        <div class="text-end">
            <span class="record-value">
                {{ $latestRecord->tensi_sistolik }}/{{ $latestRecord->tensi_diastolik }} mmHg
            </span>
            <span class="badge ms-1 px-2 py-1"
                  style="background:{{ $tensiBg }};color:{{ $tensiWarna }};font-size:10px;">
                {{ $tensiStatus }}
            </span>
        </div>
    </div>
    @endif

    {{-- Suhu, Nadi, Respirasi --}}
    @if($latestRecord->suhu || $latestRecord->nadi || $latestRecord->respirasi)
    {{-- <div class="d-flex justify-content-between py-2"
         style="border-bottom:1px solid #f0f0f0;"> --}}

         <div class="d-flex justify-content-between py-2"style="border-bottom:1px solid #f0f0f0;">
        @if($latestRecord->suhu)
        <div class="text-center flex-grow-1">
            <div style="font-size:10px;color:#aaa;">Suhu</div>
            <div class="fw-bold" style="font-size:14px;">{{ $latestRecord->suhu }}°C</div>
        </div>
        @endif
        @if($latestRecord->nadi)
        <div class="text-center flex-grow-1"
             style="border-left:1px solid #f0f0f0;border-right:1px solid #f0f0f0;">
            <div style="font-size:10px;color:#aaa;">Nadi</div>
            <div class="fw-bold" style="font-size:14px;">{{ $latestRecord->nadi }}/mnt</div>
        </div>
        @endif
        @if($latestRecord->respirasi)
        <div class="text-center flex-grow-1">
            <div style="font-size:10px;color:#aaa;">Respirasi</div>
            <div class="fw-bold" style="font-size:14px;">{{ $latestRecord->respirasi }}/mnt</div>
        </div>
        @endif
    </div>
    @endif

    {{-- BMI --}}
    @if($latestRecord->bmi)
    <div class="record-item">
        <span class="record-label">BMI</span>
        <div class="text-end">
            <span class="record-value" style="color:{{ $latestRecord->warnaBmi() }};">
                {{ $latestRecord->bmi }} kg/m²
            </span>
            <span class="badge ms-1 px-2 py-1"
                  style="background:{{ $latestRecord->warnaBmi() }};
                         color:#fff;font-size:10px;">
                {{ $latestRecord->kategoriBmi() }}
            </span>
        </div>
    </div>
    @endif

    
{{-- @if(
    $latestRecord &&
    (
        $latestRecord->bb ||
        $latestRecord->tb ||
        $latestRecord->lila ||
        $latestRecord->lingkar_kepala ||
        $latestRecord->lingkar_perut
    )
)
<div class="py-2 border-bottom">
    <div class="text-muted mb-1" style="font-size:11px;">Antropometri</div>
    <div class="row g-1" style="font-size:12px;">
        @if($latestRecord->bb)
        <div class="col-6">
            <span class="text-muted">Berat Badan:</span>
            <strong>{{ $latestRecord->bb }} kg</strong>
        </div>
        @endif
        @if($latestRecord->tb)
        <div class="col-6">
            <span class="text-muted">Tinggi Badan:</span>
            <strong>{{ $latestRecord->tb }} cm</strong>
        </div>
        @endif
        @if($latestRecord->lila)
        <div class="col-6">
            <span class="text-muted">LiLA:</span>
            <strong>{{ $latestRecord->lila }} cm</strong>
        </div>
        @endif
        @if($latestRecord->lingkar_kepala)
        <div class="col-6">
            <span class="text-muted">Lingkar Kepala:</span>
            <strong>{{ $latestRecord->lingkar_kepala }} cm</strong>
        </div>
        @endif
        @if($latestRecord->lingkar_perut)
        <div class="col-6">
            <span class="text-muted">Lingkar Perut:</span>
            <strong>{{ $latestRecord->lingkar_perut }} cm</strong>
        </div>
        @endif
    </div>
</div>
@endif --}}


@if(
    $latestRecord &&
    (
        $latestRecord->bb ||
        $latestRecord->tb ||
        $latestRecord->lila ||
        $latestRecord->lingkar_kepala ||
        $latestRecord->lingkar_perut
    )
)

<div class="record-item d-block">

    <div class="record-label mb-2 fw-semibold">
        Antropometri
    </div>

    <div class="row g-2">

        @if($latestRecord->bb)
        <div class="col-6">
            <div class="small text-muted">Berat Badan</div>
            <div class="fw-bold">{{ $latestRecord->bb }} kg</div>
        </div>
        @endif

        @if($latestRecord->tb)
        <div class="col-6">
            <div class="small text-muted">Tinggi Badan</div>
            <div class="fw-bold">{{ $latestRecord->tb }} cm</div>
        </div>
        @endif

        @if($latestRecord->lila)
        <div class="col-6">
            <div class="small text-muted">LiLA</div>
            <div class="fw-bold">{{ $latestRecord->lila }} cm</div>
        </div>
        @endif

        @if($latestRecord->lingkar_kepala)
        <div class="col-6">
            <div class="small text-muted">Lingkar Kepala</div>
            <div class="fw-bold">{{ $latestRecord->lingkar_kepala }} cm</div>
        </div>
        @endif

        @if($latestRecord->lingkar_perut)
        <div class="col-6">
            <div class="small text-muted">Lingkar Perut</div>
            <div class="fw-bold">{{ $latestRecord->lingkar_perut }} cm</div>
        </div>
        @endif

    </div>

</div>
@endif


    {{-- Berat & Tinggi --}}
    @if($latestRecord->berat_badan || $latestRecord->tinggi_badan)
    <div class="record-item">
        <span class="record-label">Berat / Tinggi</span>
        <span class="record-value">
            {{ $latestRecord->berat_badan ?? '-' }} kg /
            {{ $latestRecord->tinggi_badan ?? '-' }} cm
        </span>
    </div>
    @endif

    {{-- Catatan Konsultasi Gizi --}}
    {{-- @if($latestRecord->catatan_gizi)
    <div class="py-2" style="border-bottom:1px solid #f0f0f0;">
        <div style="font-size:11px;color:#aaa;margin-bottom:4px;">
            Catatan Konsultasi Gizi
        </div>
        <div class="p-2 rounded" style="background:#f3e5f5;font-size:13px;color:#333;">
            {{ $latestRecord->catatan_gizi }}
        </div>
    </div>
    @endif --}}


    {{-- Catatan Konsultasi Gizi --}}
@if($latestRecord->catatan_gizi)
<div class="record-item d-block">

    <div class="record-label mb-2">
        Catatan Konsultasi Gizi
    </div>

    <div class="p-3 rounded-3"
         style="background:#f3e5f5;font-size:13px;color:#333;line-height:1.5;">
        {{ $latestRecord->catatan_gizi }}
    </div>

</div>
@endif

    {{-- Catatan Tambahan --}}
    {{-- @if($latestRecord->catatan)
    <div class="record-item">
        <span class="record-label">Catatan</span>
        <span class="record-value" style="font-size:12px;text-align:right;max-width:60%;">
            {{ $latestRecord->catatan }}
        </span>
    </div>
    @endif --}}

    {{-- Catatan Tambahan --}}
@if($latestRecord->catatan)
<div class="record-item align-items-start">

    <div class="record-label">
        Catatan
    </div>

    <div class="record-value text-end"
         style="font-size:12px;max-width:65%;line-height:1.5;">
        {{ $latestRecord->catatan }}
    </div>

</div>
@endif

</div>

{{-- <a href="{{ route('patient.records') }}"
   class="btn w-100 py-2 fw-semibold mb-3"
   style="background:#e8f5e9;color:#2E7D32;border-radius:10px;">
    <i class="bi bi-clock-history me-2"></i>Lihat Semua Riwayat
</a> --}}

{{-- <a href="{{ route('patient.records') }}"
       class="btn w-100 py-2 fw-semibold"
       style="background:#e8f5e9;color:#2E7D32;border-radius:10px;">
        <i class="bi bi-clock-history me-2"></i>
        Lihat Semua Riwayat
    </a> --}}


<div class="px-3 mb-3">
    <a href="{{ route('patient.records') }}"
       class="btn w-100 py-2 fw-semibold"
       style="background:#e8f5e9;color:#2E7D32;border-radius:10px;">
        <i class="bi bi-clock-history me-2"></i>
        Lihat Semua Riwayat
    </a>
</div>

@else
<div class="text-center py-4 text-muted">
    <i class="bi bi-clipboard2 d-block fs-2 mb-2 opacity-25"></i>
    <div style="font-size:13px;">Belum ada data pemeriksaan.</div>
</div>
@endif

</div>

{{-- Bottom Navigation --}}
<div class="nav-bottom">
    <a href="{{ route('patient.dashboard') }}" class="active">
        <i class="bi bi-house-fill"></i>Beranda
    </a>
    <a href="{{ route('patient.records') }}">
        <i class="bi bi-clipboard2-pulse"></i>Riwayat
    </a>
    <form action="{{ route('patient.logout') }}" method="POST" style="flex:1;">
        @csrf
        <button type="submit" style="width:100%;background:none;border:none;
                color:#aaa;font-size:10px;padding:10px 0 6px;">
            <i class="bi bi-box-arrow-right d-block" style="font-size:20px;margin-bottom:2px;"></i>
            Keluar
        </button>
    </form>
</div>

</body>
</html>