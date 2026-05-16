<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SIK Rumah Sehat Binatama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --biru-tua : #1565C0;
            --biru-muda: #1E88E5;
            --kuning   : #FDD835;
        }
        body {
            background: linear-gradient(135deg, #0D47A1 0%, #1565C0 60%, #1976D2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-wrapper { width: 100%; max-width: 420px; padding: 16px; }

        .login-header {
            background: var(--kuning);
            border-radius: 16px 16px 0 0;
            padding: 28px 32px 20px;
            text-align: center;
        }
        .login-header .icon-box {
            width: 64px; height: 64px;
            background: var(--biru-tua);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
        }
        .login-header h4 { color: var(--biru-tua); font-weight: 800; font-size: 18px; margin-bottom: 2px; }
        .login-header p  { color: #555; font-size: 12px; margin: 0; }

        .login-body {
            background: #fff;
            border-radius: 0 0 16px 16px;
            padding: 28px 32px 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .role-badge {
            display: inline-block;
            background: var(--biru-tua);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            border: 1.5px solid #ddd;
            padding: 10px 14px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: var(--biru-muda);
            box-shadow: 0 0 0 3px rgba(30,136,229,0.15);
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            background: #f8f9fa;
            border: 1.5px solid #ddd;
            border-right: none;
            color: var(--biru-tua);
        }
        .input-group .form-control { border-radius: 0 8px 8px 0; border-left: none; }
        .input-group:focus-within .input-group-text { border-color: var(--biru-muda); }

        .btn-login {
            background: linear-gradient(135deg, var(--biru-muda), var(--biru-tua));
            border: none; border-radius: 8px;
            padding: 11px; font-size: 15px;
            font-weight: 600; color: #fff; width: 100%;
        }
        .btn-login:hover { opacity: 0.9; color: #fff; }

        .login-footer {
            text-align: center;
            margin-top: 16px;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-header">
        <div class="icon-box">
            <i class="bi bi-shield-lock-fill text-white fs-4"></i>
        </div>
        <h4>Rumah Sehat Binatama</h4>
        <p>SMK Kesehatan Binatama Yogyakarta</p>
    </div>
    <div class="login-body">
        <div class="text-center">
            <span class="role-badge">
                <i class="bi bi-person-gear me-1"></i>Administrator
            </span>
        </div>

        @if(session('error'))
            <div class="alert alert-danger py-2 mb-3" style="border-radius:8px;font-size:13px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success py-2 mb-3" style="border-radius:8px;font-size:13px;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.admin.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}"
                           placeholder="Username admin" autofocus>
                </div>
                @error('username')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" id="pwd"
                           class="form-control"
                           placeholder="Password">
                    <button type="button" class="btn btn-outline-secondary"
                            style="border-radius:0 8px 8px 0;"
                            onclick="togglePwd()">
                        <i class="bi bi-eye" id="pwd-icon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk sebagai Admin
            </button>
        </form>
    </div>
    <div class="login-footer">
        <i class="bi bi-shield-check me-1"></i>
        Akses terbatas — Hanya untuk Administrator<br>
        &copy; {{ date('Y') }} SMK Kesehatan Binatama
    </div>
</div>
<script>
function togglePwd() {
    const i = document.getElementById('pwd');
    const icon = document.getElementById('pwd-icon');
    i.type = i.type === 'password' ? 'text' : 'password';
    icon.className = i.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
</body>
</html>