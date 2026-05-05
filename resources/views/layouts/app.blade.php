<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIK Rumah Sehat Binatama')</title>

    {{-- Bootstrap 5 + Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1565C0">

    <style>
        :root {
            --biru-muda  : #1E88E5;
            --biru-tua   : #1565C0;
            --hijau      : #2E7D32;
            --kuning     : #FDD835;
            --orange     : #F57C00;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--biru-tua) 0%, #0D47A1 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar .brand {
            padding: 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .sidebar .brand-title {
            color: var(--kuning);
            font-weight: 700;
            font-size: 14px;
            line-height: 1.3;
        }

        .sidebar .brand-sub {
            color: rgba(255,255,255,0.7);
            font-size: 11px;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 16px;
            border-radius: 8px;
            margin: 2px 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: var(--biru-muda);
            color: #fff;
            font-weight: 600;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 8px;
        }

        .sidebar .nav-section {
            color: rgba(255,255,255,0.4);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 16px 24px 4px;
        }

        /* Main content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Topbar */
        .topbar {
            background: #fff;
            border-bottom: 2px solid var(--kuning);
            padding: 12px 24px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        /* Card stats */
        .stat-card {
            border: none;
            border-radius: 12px;
            border-left: 4px solid var(--biru-muda);
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: transform 0.2s;
        }

        .stat-card:hover { transform: translateY(-2px); }
        .stat-card.green  { border-left-color: var(--hijau); }
        .stat-card.yellow { border-left-color: var(--kuning); }
        .stat-card.orange { border-left-color: var(--orange); }

        /* Tombol utama */
        .btn-primary {
            background-color: var(--biru-muda);
            border-color: var(--biru-muda);
        }
        .btn-primary:hover {
            background-color: var(--biru-tua);
            border-color: var(--biru-tua);
        }

        /* Badge role */
        .badge-admin  { background-color: var(--orange);   color: #fff; }
        .badge-guru   { background-color: var(--hijau);    color: #fff; }
        .badge-siswa  { background-color: var(--biru-muda);color: #fff; }

        /* Alert */
        .alert { border-radius: 10px; border: none; }

        /* Responsive mobile */
        @media (max-width: 768px) {
            .sidebar      { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
@auth
<div class="sidebar" id="sidebar">
    <div class="brand d-flex align-items-center gap-2">
        <div style="background:var(--kuning);border-radius:8px;padding:6px 8px;">
            <i class="bi bi-heart-pulse-fill" style="color:var(--biru-tua);font-size:18px;"></i>
        </div>
        <div>
            <div class="brand-title">Rumah Sehat Binatama</div>
            <div class="brand-sub">SMK Kesehatan Binatama</div>
        </div>
    </div>

    <nav class="mt-2">
        @if(auth()->user()->isAdmin())
            @include('layouts.sidebar-admin')
        @else
            @include('layouts.sidebar-staff')
        @endif
    </nav>

    {{-- Info user di bawah sidebar --}}
    <div style="position:absolute;bottom:0;left:0;right:0;padding:12px 16px;
                border-top:1px solid rgba(255,255,255,0.15);background:rgba(0,0,0,0.2);">
        <div class="d-flex align-items-center gap-2">
            <div style="width:32px;height:32px;border-radius:50%;
                        background:var(--kuning);display:flex;align-items:center;
                        justify-content:center;font-weight:700;color:var(--biru-tua);font-size:13px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div style="color:#fff;font-size:12px;font-weight:600;line-height:1.2;">
                    {{ Str::limit(auth()->user()->name, 20) }}
                </div>
                <div style="color:rgba(255,255,255,0.5);font-size:10px;">
                    {{ ucfirst(auth()->user()->role) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endauth

{{-- MAIN CONTENT --}}
<div class="{{ auth()->check() ? 'main-content' : '' }}">

    {{-- TOPBAR --}}
    @auth
    <div class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none"
                    onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h6 class="mb-0 fw-bold" style="color:var(--biru-tua);">
                @yield('page-title', 'Dashboard')
            </h6>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-{{ auth()->user()->role }} px-3 py-2">
                {{ ucfirst(auth()->user()->role) }}
            </span>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-md-inline">Logout</span>
                </button>
            </form>
        </div>
    </div>
    @endauth

    {{-- KONTEN HALAMAN --}}
    <div class="{{ auth()->check() ? 'p-4' : '' }}">

        {{-- Alert pesan --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- PWA Service Worker --}}
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js');
    }
</script>

@stack('scripts')
</body>
</html>