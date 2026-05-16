<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pasien — Rumah Sehat Binatama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2E7D32">
    <style>
        /* :root {
            --hijau    : #2E7D32;
            --biru-tua : #1565C0;
            --kuning   : #FDD835;
            --orange   : #F57C00;
        }
        body {
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 50%, #388E3C 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .portal-wrapper { width: 100%; max-width: 420px; padding: 16px; }

        .portal-header {
            background: var(--kuning);
            border-radius: 16px 16px 0 0;
            padding: 24px 28px 16px;
            text-align: center;
        }
        .portal-header .icon-box {
            width: 60px; height: 60px;
            background: var(--hijau);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 10px;
        }
        .portal-header h4 { color: var(--hijau); font-weight: 800; font-size: 17px; margin-bottom: 2px; }
        .portal-header p  { color: #555; font-size: 12px; margin: 0; }

        .portal-body {
            background: #fff;
            border-radius: 0 0 16px 16px;
            padding: 24px 28px 28px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .form-control {
            border-radius: 8px;
            border: 1.5px solid #ddd;
            padding: 10px 14px;
            font-size: 15px;
        }
        .form-control:focus {
            border-color: var(--hijau);
            box-shadow: 0 0 0 3px rgba(46,125,50,0.15);
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            background: #f8f9fa;
            border: 1.5px solid #ddd;
            border-right: none;
            color: var(--hijau);
        }
        .input-group .form-control { border-radius: 0 8px 8px 0; border-left: none; }
        .input-group:focus-within .input-group-text { border-color: var(--hijau); }

        .btn-portal {
            background: linear-gradient(135deg, #2E7D32, #1B5E20);
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            width: 100%;
        }
        .btn-portal:hover { opacity: 0.9; color: #fff; }

        .portal-footer {
            text-align: center;
            margin-top: 16px;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
        }

        .hint-box {
            background: #f1f8e9;
            border: 1px dashed #81c784;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 12px;
            color: #2e7d32;
            margin-bottom: 16px;
        } */
:root {
    --hijau    : #2E7D32;
    --biru-tua : #1565C0;
    --kuning   : #FDD835;
    --orange   : #F57C00;
}

body {
    background: linear-gradient(135deg, #1565C0 0%, #1976D2 50%, #1E88E5 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', sans-serif;
}

.portal-wrapper {
    width: 100%;
    max-width: 420px;
    padding: 16px;
}

.portal-header {
    background: #E3F2FD;
    border-radius: 16px 16px 0 0;
    padding: 24px 28px 16px;
    text-align: center;
}

.portal-header .icon-box {
    width: 60px;
    height: 60px;
    background: #1565C0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
}

.portal-header h4 {
    color: #1565C0;
    font-weight: 800;
    font-size: 17px;
    margin-bottom: 2px;
}

.portal-header p {
    color: #555;
    font-size: 12px;
    margin: 0;
}

.portal-body {
    background: #fff;
    border-radius: 0 0 16px 16px;
    padding: 24px 28px 28px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.form-control {
    border-radius: 8px;
    border: 1.5px solid #ddd;
    padding: 10px 14px;
    font-size: 15px;
}

.form-control:focus {
    border-color: #1565C0;
    box-shadow: 0 0 0 3px rgba(21,101,192,0.15);
}

.input-group-text {
    border-radius: 8px 0 0 8px;
    background: #f8f9fa;
    border: 1.5px solid #ddd;
    border-right: none;
    color: #1565C0;
}

.input-group .form-control {
    border-radius: 0 8px 8px 0;
    border-left: none;
}

.input-group:focus-within .input-group-text {
    border-color: #1565C0;
}

.btn-portal {
    background: linear-gradient(135deg, #1E88E5, #1565C0);
    border: none;
    border-radius: 8px;
    padding: 11px;
    font-size: 15px;
    font-weight: 600;
    color: #fff;
    width: 100%;
}

.btn-portal:hover {
    opacity: 0.9;
    color: #fff;
}

.portal-footer {
    text-align: center;
    margin-top: 16px;
    color: rgba(255,255,255,0.8);
    font-size: 12px;
}

.hint-box {
    background: #e3f2fd;
    border: 1px dashed #90caf9;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 12px;
    color: #1565C0;
    margin-bottom: 16px;
}

    </style>
</head>
<body>

<div class="portal-wrapper">

    <div class="portal-header">
        <div class="icon-box">
            <i class="bi bi-person-heart text-white fs-4"></i>
        </div>
        <h4>Portal Pasien</h4>
        <p>Rumah Sehat Binatama</p>
    </div>

    <div class="portal-body">

        @if(session('error'))
            <div class="alert alert-danger py-2 mb-3" style="font-size:13px;border-radius:8px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success py-2 mb-3" style="font-size:13px;border-radius:8px;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="hint-box">
            <i class="bi bi-info-circle-fill me-1"></i>
            Gunakan <strong>No. RM</strong> dan <strong>Kode Unik</strong> yang tertera
            pada nota/struk pemeriksaan Anda.
        </div>

        <form action="{{ route('patient.login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">
                    No. Rekam Medis (No. RM)
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-card-text"></i>
                    </span>
                    <input type="text" name="no_rm"
                           class="form-control @error('no_rm') is-invalid @enderror"
                           value="{{ old('no_rm', request('rm')) }}"
                           placeholder="RM-202605-0001"
                           style="text-transform:uppercase;">
                </div>
                @error('no_rm')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">
                    Kode Unik
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-key-fill"></i>
                    </span>
                    <input type="text" name="kode_unik" id="kode-input"
                           class="form-control @error('kode_unik') is-invalid @enderror"
                           value="{{ old('kode_unik', request('kode')) }}"
                           placeholder="Contoh: 6A671634"
                           style="text-transform:uppercase;letter-spacing:3px;">
                    <button type="button" class="btn btn-outline-secondary"
                            style="border-radius:0 8px 8px 0;"
                            onclick="toggleKode()">
                        <i class="bi bi-eye" id="kode-icon"></i>
                    </button>
                </div>
                @error('kode_unik')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-portal">
                <i class="bi bi-box-arrow-in-right me-2"></i>Lihat Hasil Pemeriksaan
            </button>
        </form>

    </div>

    <div class="portal-footer">
        <i class="bi bi-shield-check me-1"></i>
        Data Anda aman & terlindungi<br>
        &copy; {{ date('Y') }} SMK Kesehatan Binatama Yogyakarta
    </div>

</div>

<script>
function toggleKode() {
    const input = document.getElementById('kode-input');
    const icon  = document.getElementById('kode-icon');
    const isPass = input.type === 'password';
    input.type = isPass ? 'text' : 'password';
    icon.className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
}
</script>

</body>
</html>