<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pasien — Rumah Sehat Binatama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',sans-serif; min-height:100vh; position:relative; display:flex; flex-direction:column; }
        .bg-photo { position:fixed; inset:0; background-image:url('/images/bg-landing.jpg'); background-size:cover; background-position:center; z-index:0; }
        /* Overlay dikurangi opacity agar foto gedung SMK terlihat */
        .bg-overlay { position:fixed; inset:0; background:linear-gradient(135deg,rgba(13,71,161,0.55) 0%,rgba(21,101,192,0.50) 50%,rgba(30,136,229,0.45) 100%); z-index:1; }
        .content-wrap { position:relative; z-index:2; display:flex; flex-direction:column; min-height:100vh; }
        .top-bar { padding:16px 32px; display:flex; align-items:center; justify-content:space-between; background:rgba(0,0,0,0.35); backdrop-filter:blur(6px); }
        .brand-name { color:#fff; font-weight:800; font-size:15px; }
        .brand-sub { color:rgba(255,255,255,0.75); font-size:11px; }
        .hero { flex:1; display:flex; align-items:center; justify-content:center; padding:32px 16px; }
        .hero-card { background:rgba(10,40,100,0.60); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.22); border-radius:24px; padding:40px 36px 36px; text-align:center; max-width:460px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,0.45); }
        .logo-wrap { width:90px; height:90px; margin:0 auto 20px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 20px rgba(0,0,0,0.25); overflow:hidden; }
        .logo-wrap img { width:75px; height:75px; object-fit:contain; }
        .logo-fallback { font-size:36px; color:#1565C0; }
        .hero-title { color:#fff; font-size:24px; font-weight:800; margin-bottom:4px; }
        .hero-sub { color:rgba(255,255,255,0.75); font-size:13px; margin-bottom:4px; }
        .role-chip { display:inline-block; background:#E3F2FD; color:#1565C0; font-size:10px; font-weight:800; padding:3px 14px; border-radius:20px; letter-spacing:1px; text-transform:uppercase; margin-bottom:24px; }
        .feature-list { background:rgba(255,255,255,0.10); border-radius:12px; padding:14px 18px; margin-bottom:16px; text-align:left; }
        .feature-item { color:rgba(255,255,255,0.90); font-size:13px; padding:3px 0; display:flex; align-items:center; gap:10px; }
        .feature-item i { color:#90CAF9; }
        .hint-box { background:rgba(255,255,255,0.14); border-radius:8px; padding:10px 14px; margin-bottom:16px; color:rgba(255,255,255,0.95); font-size:12px; border-left:3px solid #90CAF9; text-align:left; }
        .btn-masuk { display:block; width:100%; background:#E3F2FD; color:#1565C0; border:none; border-radius:12px; padding:13px; font-size:15px; font-weight:800; text-decoration:none; transition:all 0.2s; }
        .btn-masuk:hover { background:#fff; color:#1565C0; transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.35); }
        .footer { text-align:center; padding:14px; color:rgba(255,255,255,0.55); font-size:11px; background:rgba(0,0,0,0.30); }
    </style>
</head>
<body>
<div class="bg-photo"></div>
<div class="bg-overlay"></div>
<div class="content-wrap">
    <div class="top-bar">
        <div>
            <div class="brand-name"><i class="bi bi-heart-pulse-fill me-1"></i>Rumah Sehat Binatama</div>
            <div class="brand-sub">SMK Kesehatan Binatama Yogyakarta</div>
        </div>
        <div style="color:rgba(255,255,255,0.6);font-size:11px;">Sistem Informasi Kesehatan</div>
    </div>
    <div class="hero">
        <div class="hero-card">
            <div class="logo-wrap">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="/images/logo.png" alt="Logo">
                @else
                    <i class="bi bi-person-heart logo-fallback"></i>
                @endif
            </div>
            <div class="hero-title">Portal Pasien</div>
            <div class="hero-sub">Rumah Sehat Binatama</div>
            <div class="role-chip"><i class="bi bi-person-fill me-1"></i>Akses Pasien</div>
            <div class="feature-list">
                <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Lihat riwayat pemeriksaan</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Cek hasil gula darah & kolesterol</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Pantau tekanan darah & asam urat</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Akses kapan saja & di mana saja</div>
            </div>
            <div class="hint-box">
                <i class="bi bi-info-circle-fill me-1"></i>
                Gunakan <strong>No. RM</strong> dan <strong>Kode Unik</strong>
                yang tertera pada nota pemeriksaan Anda untuk login.
            </div>
            <a href="{{ route('patient.login') }}" class="btn-masuk">
                <i class="bi bi-box-arrow-in-right me-2"></i>Lihat Hasil Pemeriksaan
            </a>
        </div>
    </div>
    <div class="footer">&copy; {{ date('Y') }} SMK Kesehatan Binatama Yogyakarta &nbsp;·&nbsp; Sistem Informasi Kesehatan</div>
</div>
</body>
</html>