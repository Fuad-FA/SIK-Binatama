<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemeriksaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --hijau:#2E7D32; --kuning:#FDD835; --orange:#F57C00; }
        body { font-family:'Segoe UI',sans-serif; background:#f0f4f8; }
        .topbar {
            background: linear-gradient(135deg, #1B5E20, #2E7D32);
            padding: 12px 20px;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar .title { color:#fff; font-weight:700; font-size:15px; }
        .record-card {
            background:#fff;
            border-radius:12px;
            margin:0 16px 12px;
            box-shadow:0 2px 8px rgba(0,0,0,0.06);
            overflow:hidden;
        }
        .record-header {
            background: linear-gradient(135deg, #1B5E20, #2E7D32);
            padding:10px 16px;
            color:#fff;
        }
        .result-row {
            display:flex;
            justify-content:space-between;
            padding:8px 16px;
            border-bottom:1px solid #f5f5f5;
            font-size:13px;
        }
        .result-row:last-child { border-bottom:none; }
        .normal   { color:#2E7D32; font-weight:700; }
        .abnormal { color:#c62828; font-weight:700; }
        .badge-n  { background:#e8f5e9;color:#2E7D32;padding:2px 8px;border-radius:10px;font-size:10px; }
        .badge-a  { background:#ffebee;color:#c62828;padding:2px 8px;border-radius:10px;font-size:10px; }
        .nav-bottom {
            position:fixed;bottom:0;left:0;right:0;
            background:#fff;border-top:1px solid #eee;
            display:flex;z-index:100;
        }
        .nav-bottom a {
            flex:1;text-align:center;padding:10px 0 6px;
            color:#aaa;text-decoration:none;font-size:10px;
        }
        .nav-bottom a.active { color:var(--hijau); }
        .nav-bottom a i { display:block;font-size:20px;margin-bottom:2px; }
        .content-area { padding:16px 0 70px; }
    </style>
</head>
<body>

<div class="topbar d-flex align-items-center gap-3">
    <a href="{{ route('patient.dashboard') }}"
       style="color:#fff;text-decoration:none;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <div class="title">Riwayat Pemeriksaan</div>
        <div style="font-size:11px;color:rgba(255,255,255,0.7);">
            {{ $patient->nama }} · {{ $patient->no_rm }}
        </div>
    </div>
</div>

<div class="content-area">

    @if($patient->medicalRecords->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-clipboard2 fs-1 d-block mb-2 opacity-25"></i>
            Belum ada riwayat pemeriksaan.
        </div>
    @endif

    @foreach($patient->medicalRecords as $rec)
    <div class="record-card">
        <div class="record-header d-flex justify-content-between align-items-center">
            <div>
                <div style="font-weight:700;font-size:14px;">
                    {{ $rec->tanggal_periksa->format('d M Y') }}
                </div>
                <div style="font-size:11px;opacity:0.8;">
                    Petugas: {{ $rec->user?->name ?? '-' }}
                </div>
            </div>
            <div style="background:rgba(255,255,255,0.2);padding:4px 10px;
                        border-radius:8px;font-size:11px;">
                Kunjungan ke-{{ $loop->iteration }}
            </div>
        </div>

        @if($rec->gula_darah)
        <div class="result-row">
            <span style="color:#666;">Gula Darah</span>
            <div class="text-end">
                <span class="{{ $rec->gula_darah >= 200 ? 'abnormal':'normal' }}">
                    {{ $rec->gula_darah }} mg/dl
                </span>
                <span class="{{ $rec->gula_darah >= 200 ? 'badge-a':'badge-n' }} ms-1">
                    {{ $rec->gula_darah >= 200 ? 'Tinggi':'Normal' }}
                </span>
            </div>
        </div>
        @endif

        @if($rec->kolesterol)
        @php $kOk = $rec->kolesterol >= 160 && $rec->kolesterol <= 200; @endphp
        <div class="result-row">
            <span style="color:#666;">Kolesterol</span>
            <div class="text-end">
                <span class="{{ $kOk ? 'normal':'abnormal' }}">{{ $rec->kolesterol }} mg/dl</span>
                <span class="{{ $kOk ? 'badge-n':'badge-a' }} ms-1">
                    {{ $kOk ? 'Normal':'Tidak Normal' }}
                </span>
            </div>
        </div>
        @endif

        @if($rec->asam_urat)
        <div class="result-row">
            <span style="color:#666;">Asam Urat</span>
            <span class="normal">{{ $rec->asam_urat }} mg/dl</span>
        </div>
        @endif

        @if($rec->tensi_sistolik)
        @php $tOk = $rec->tensi_sistolik <= 120 && $rec->tensi_diastolik <= 80; @endphp
        <div class="result-row">
            <span style="color:#666;">Tekanan Darah</span>
            <div class="text-end">
                <span class="{{ $tOk ? 'normal':'abnormal' }}">
                    {{ $rec->tensi_sistolik }}/{{ $rec->tensi_diastolik }} mmHg
                </span>
                <span class="{{ $tOk ? 'badge-n':'badge-a' }} ms-1">
                    {{ $rec->statusTensi() }}
                </span>
            </div>
        </div>
        @endif

        @if($rec->suhu || $rec->nadi || $rec->respirasi)
        <div class="result-row" style="background:#fafafa;">
            @if($rec->suhu)
            <div class="text-center">
                <div style="font-size:10px;color:#999;">Suhu</div>
                <div style="font-weight:600;">{{ $rec->suhu }}°C</div>
            </div>
            @endif
            @if($rec->nadi)
            <div class="text-center">
                <div style="font-size:10px;color:#999;">Nadi</div>
                <div style="font-weight:600;">{{ $rec->nadi }}/mnt</div>
            </div>
            @endif
            @if($rec->respirasi)
            <div class="text-center">
                <div style="font-size:10px;color:#999;">Respirasi</div>
                <div style="font-weight:600;">{{ $rec->respirasi }}/mnt</div>
            </div>
            @endif
        </div>
        @endif

        @if($rec->catatan)
        <div class="result-row">
            <span style="color:#666;">Catatan</span>
            <span style="color:#555;font-size:12px;max-width:60%;text-align:right;">
                {{ $rec->catatan }}
            </span>
        </div>
        @endif
    </div>
    @endforeach

</div>

<div class="nav-bottom">
    <a href="{{ route('patient.dashboard') }}">
        <i class="bi bi-house-fill"></i>Beranda
    </a>
    <a href="{{ route('patient.records') }}" class="active">
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