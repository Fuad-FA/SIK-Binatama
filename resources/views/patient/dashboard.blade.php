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
    @if($latestRecord)
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