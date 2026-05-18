<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1976D2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-404 {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 24px;
            padding: 48px 40px;
            text-align: center;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .angka-404 {
            font-size: 96px;
            font-weight: 900;
            color: #FDD835;
            line-height: 1;
            text-shadow: 0 4px 20px rgba(253,216,53,0.4);
        }
        .icon-404 {
            font-size: 48px;
            color: rgba(255,255,255,0.3);
            margin: 8px 0;
        }
        .judul {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .deskripsi {
            color: rgba(255,255,255,0.65);
            font-size: 13px;
            margin-bottom: 28px;
            line-height: 1.6;
        }
        .btn-home {
            background: #FDD835;
            color: #1565C0;
            border: none;
            border-radius: 10px;
            padding: 10px 28px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            margin: 4px;
        }
        .btn-home:hover {
            background: #fff;
            color: #1565C0;
            transform: translateY(-2px);
        }
        .btn-back {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            padding: 10px 28px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            margin: 4px;
            cursor: pointer;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
        .brand {
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            margin-top: 24px;
        }
        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 20px 0;
        }
    </style>
</head>
<body>

<div class="card-404">
    {{-- Angka 404 --}}
    <div class="angka-404">404</div>
    <div class="icon-404">
        <i class="bi bi-compass"></i>
    </div>

    <div class="judul">Halaman Tidak Ditemukan</div>
    <div class="deskripsi">
        Halaman yang Anda cari tidak ada atau sudah dipindahkan.<br>
        Pastikan URL yang dimasukkan sudah benar.
    </div>

    <hr class="divider">

    {{-- Tombol aksi --}}
    @auth
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn-home">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
            </a>
        @else
            <a href="{{ route('staff.dashboard') }}" class="btn-home">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
        @endif
    @else
        <a href="{{ route('landing.admin') }}" class="btn-home">
            <i class="bi bi-house-fill me-2"></i>Halaman Utama
        </a>
    @endauth

    <button onclick="history.back()" class="btn-back">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </button>

    <div class="brand">
        <i class="bi bi-heart-pulse-fill me-1"></i>
        Rumah Sehat Binatama — SMK Kesehatan Binatama Yogyakarta
    </div>
</div>

</body>
</html>