{{-- @extends('layouts.app')

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header text-white fw-bold"
                 style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
                <i class="bi bi-shield-lock-fill me-2"></i>
                Ganti Password
            </div>
            <div class="card-body p-4">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Anda harus mengganti password default sebelum melanjutkan.
                </div>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Minimal 6 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                               class="form-control"
                               placeholder="Ulangi password baru">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="bi bi-check-circle me-2"></i> Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password — SIK Rumah Sehat Binatama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1565C0 0%, #1976D2 50%, #42A5F5 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .card-wrap {
            width: 100%; max-width: 420px; padding: 16px;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            border-radius: 16px 16px 0 0;
            padding: 28px 32px 20px;
            text-align: center;
        }
        .card-header-custom .icon-box {
            width: 64px; height: 64px; background: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
        }
        .card-header-custom h4 { color: #fff; font-weight: 800; font-size: 18px; margin-bottom: 2px; }
        .card-header-custom p  { color: rgba(255,255,255,0.75); font-size: 12px; margin: 0; }
        .card-body-custom {
            background: #fff;
            border-radius: 0 0 16px 16px;
            padding: 28px 32px 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .alert-warning-custom {
            background: #fff8e1;
            border-left: 3px solid #FDD835;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            color: #555;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px; border: 1.5px solid #ddd;
            padding: 10px 14px; font-size: 14px;
        }
        .form-control:focus {
            border-color: #1E88E5;
            box-shadow: 0 0 0 3px rgba(30,136,229,0.15);
        }
        .input-group-text {
            border-radius: 8px 0 0 8px; background: #f0f7ff;
            border: 1.5px solid #ddd; border-right: none; color: #1565C0;
        }
        .input-group .form-control { border-radius: 0 8px 8px 0; border-left: none; }
        .btn-submit {
            background: linear-gradient(135deg, #1E88E5, #1565C0);
            border: none; border-radius: 8px; padding: 11px;
            font-size: 15px; font-weight: 600; color: #fff; width: 100%;
        }
        .btn-submit:hover { opacity: 0.9; color: #fff; }

        /* Password strength */
        .strength-bar {
            height: 4px; border-radius: 2px;
            background: #e0e0e0; margin-top: 6px;
        }
        .strength-fill {
            height: 100%; border-radius: 2px;
            transition: all 0.3s;
        }
        .strength-text { font-size: 11px; margin-top: 3px; }
    </style>
</head>
<body>
<div class="card-wrap">
    <div class="card-header-custom">
        <div class="icon-box">
            <i class="bi bi-key-fill fs-3" style="color:#1565C0;"></i>
        </div>
        <h4>Ganti Password</h4>
        <p>Rumah Sehat Binatama</p>
    </div>
    <div class="card-body-custom">

        {{-- Info wajib ganti --}}
        @if(auth()->user()->must_change_password)
        <div class="alert-warning-custom">
            <i class="bi bi-exclamation-triangle-fill me-2" style="color:#F57C00;"></i>
            <strong>Password harus diganti</strong> sebelum menggunakan sistem.
            Password default tidak aman untuk digunakan terus-menerus.
        </div>
        @endif

        {{-- Info user --}}
        <div class="d-flex align-items-center gap-3 p-2 rounded mb-4"
             style="background:#e3f2fd;">
            <div style="width:40px;height:40px;border-radius:50%;background:#1565C0;
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-weight:700;font-size:16px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="fw-bold" style="font-size:13px;">{{ auth()->user()->name }}</div>
                <span class="badge {{ auth()->user()->role === 'guru' ? 'bg-success' : 'bg-primary' }}"
                      style="font-size:10px;">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger py-2 mb-3" style="border-radius:8px;font-size:13px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            {{-- Password Baru --}}
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">
                    Password Baru <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" id="new-password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimal 6 karakter"
                           oninput="checkStrength(this.value)">
                    <button type="button" class="btn btn-outline-secondary"
                            style="border-radius:0 8px 8px 0;"
                            onclick="togglePwd('new-password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
                {{-- Strength indicator --}}
                <div class="strength-bar">
                    <div class="strength-fill" id="strength-fill"
                         style="width:0%;background:#ccc;"></div>
                </div>
                <div class="strength-text text-muted" id="strength-text"></div>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">
                    Konfirmasi Password <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation"
                           id="confirm-password"
                           class="form-control"
                           placeholder="Ulangi password baru"
                           oninput="checkMatch()">
                    <button type="button" class="btn btn-outline-secondary"
                            style="border-radius:0 8px 8px 0;"
                            onclick="togglePwd('confirm-password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div id="match-status" class="mt-1" style="font-size:12px;"></div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-check-circle me-2"></i>Simpan Password Baru
            </button>
        </form>
    </div>
</div>

<script>
function togglePwd(inputId, btn) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
}

function checkStrength(val) {
    const fill = document.getElementById('strength-fill');
    const text = document.getElementById('strength-text');
    let score = 0;
    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { pct: '20%', color: '#c62828', label: 'Sangat Lemah' },
        { pct: '40%', color: '#e53935', label: 'Lemah' },
        { pct: '60%', color: '#F57C00', label: 'Cukup' },
        { pct: '80%', color: '#43A047', label: 'Kuat' },
        { pct: '100%', color: '#2E7D32', label: 'Sangat Kuat' },
    ];

    if (val.length === 0) {
        fill.style.width = '0%';
        text.textContent = '';
        return;
    }

    const lvl = levels[Math.min(score - 1, 4)] || levels[0];
    fill.style.width  = lvl.pct;
    fill.style.background = lvl.color;
    text.textContent  = lvl.label;
    text.style.color  = lvl.color;
}

function checkMatch() {
    const pwd     = document.getElementById('new-password').value;
    const confirm = document.getElementById('confirm-password').value;
    const status  = document.getElementById('match-status');
    if (!confirm) { status.textContent = ''; return; }
    if (pwd === confirm) {
        status.textContent = '✓ Password cocok';
        status.style.color = '#2E7D32';
    } else {
        status.textContent = '✗ Password tidak cocok';
        status.style.color = '#c62828';
    }
}
</script>
</body>
</html>