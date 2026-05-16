<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa — SIK Rumah Sehat Binatama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        :root {
            --biru-tua : #1565C0;
            --biru-muda: #1E88E5;
            --aksen    : #42A5F5;
        }
        body {
            background: linear-gradient(135deg, #1976D2 0%, #1E88E5 50%, #64B5F6 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-wrapper { width: 100%; max-width: 420px; padding: 16px; }
        .login-header {
            background: linear-gradient(135deg, #1E88E5, #1565C0);
            border-radius: 16px 16px 0 0;
            padding: 28px 32px 20px; text-align: center;
        }
        .login-header .icon-box {
            width: 64px; height: 64px; background: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
        }
        .login-header h4 { color: #fff; font-weight: 800; font-size: 18px; margin-bottom: 2px; }
        .login-header p  { color: rgba(255,255,255,0.75); font-size: 12px; margin: 0; }
        .login-body {
            background: #fff;
            border-radius: 0 0 16px 16px;
            padding: 28px 32px 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .role-badge {
            display: inline-block;
            background: var(--biru-muda);
            color: #fff; font-size: 11px; font-weight: 700;
            padding: 3px 12px; border-radius: 20px;
            letter-spacing: 1px; text-transform: uppercase;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px; border: 1.5px solid #ddd;
            padding: 10px 14px; font-size: 14px;
        }
        .form-control:focus {
            border-color: var(--biru-muda);
            box-shadow: 0 0 0 3px rgba(30,136,229,0.15);
        }
        .input-group-text {
            border-radius: 8px 0 0 8px; background: #f0f7ff;
            border: 1.5px solid #ddd; border-right: none;
            color: var(--biru-muda);
        }
        .input-group .form-control { border-radius: 0 8px 8px 0; border-left: none; }
        .btn-scan-box {
            border: 2px dashed #90caf9; border-radius: 8px;
            padding: 16px; text-align: center;
            background: #f0f7ff; cursor: pointer;
            transition: all 0.2s;
        }
        .btn-scan-box:hover { border-color: var(--biru-muda); background: #e3f2fd; }
        .btn-login {
            background: linear-gradient(135deg, var(--biru-muda), var(--biru-tua));
            border: none; border-radius: 8px; padding: 11px;
            font-size: 15px; font-weight: 600; color: #fff; width: 100%;
        }
        .btn-login:hover { opacity: 0.9; color: #fff; }
        .login-footer {
            text-align: center; margin-top: 16px;
            color: rgba(255,255,255,0.75); font-size: 12px;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-header">
        <div class="icon-box">
            <i class="bi bi-qr-code-scan fs-3" style="color:var(--biru-muda);"></i>
        </div>
        <h4>Rumah Sehat Binatama</h4>
        <p>SMK Kesehatan Binatama Yogyakarta</p>
    </div>
    <div class="login-body">
        <div class="text-center">
            <span class="role-badge">
                <i class="bi bi-person-fill me-1"></i>Portal Siswa
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

        <form action="{{ route('login.siswa.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">
                    Barcode / QR Code
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-qr-code"></i></span>
                    <input type="text" name="barcode" id="barcode-input"
                           class="form-control"
                           placeholder="Scan atau ketik barcode" autofocus>
                </div>
                <div class="mt-2 btn-scan-box" id="btn-scan" onclick="startScan()">
                    <i class="bi bi-qr-code-scan fs-3 d-block mb-1"
                       style="color:var(--biru-muda);"></i>
                    <span style="font-size:12px;color:#555;">
                        Klik untuk scan QR Code via kamera
                    </span>
                </div>
                <div id="qr-reader-container" style="display:none;margin-top:8px;">
                    <div id="qr-reader" style="border-radius:8px;overflow:hidden;"></div>
                    <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2"
                            onclick="stopScan()">
                        <i class="bi bi-x-circle me-1"></i>Tutup Kamera
                    </button>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" id="pwd"
                           class="form-control"
                           placeholder="Password (default: siswa)">
                    <button type="button" class="btn btn-outline-secondary"
                            style="border-radius:0 8px 8px 0;"
                            onclick="togglePwd()">
                        <i class="bi bi-eye" id="pwd-icon"></i>
                    </button>
                </div>
                <small class="text-muted">Password default: <code>siswa</code></small>
            </div>
            <button type="submit" class="btn-login">
                <i class="bi bi-qr-code me-2"></i>Masuk sebagai Siswa
            </button>
        </form>
    </div>
    <div class="login-footer">
        <i class="bi bi-shield-check me-1"></i>
        Portal Siswa — SMK Kesehatan Binatama &copy; {{ date('Y') }}
    </div>
</div>
<script>
let html5QrCode = null;
let isScanning  = false;

function startScan() {
    if (isScanning) return;
    document.getElementById('btn-scan').style.display = 'none';
    document.getElementById('qr-reader-container').style.display = 'block';
    html5QrCode = new Html5Qrcode("qr-reader");
    isScanning  = true;
    Html5Qrcode.getCameras().then(cameras => {
        if (!cameras || cameras.length === 0) { alert('Tidak ada kamera.'); stopScan(); return; }
        const cameraId = cameras.length > 1 ? cameras[1].id : cameras[0].id;
        html5QrCode.start(cameraId, { fps: 15, qrbox: { width: 200, height: 200 } },
            (decoded) => {
                document.getElementById('barcode-input').value = decoded.trim().toUpperCase();
                document.getElementById('barcode-input').style.borderColor = '#1565C0';
                stopScan();
            }, () => {}
        ).catch(err => { alert('Gagal: ' + err); stopScan(); });
    }).catch(err => { alert('Kamera error: ' + err); stopScan(); });
}

function stopScan() {
    isScanning = false;
    if (html5QrCode) {
        html5QrCode.stop().then(() => { html5QrCode.clear(); html5QrCode = null; }).catch(() => {});
    }
    document.getElementById('qr-reader-container').style.display = 'none';
    document.getElementById('btn-scan').style.display = 'block';
}

function togglePwd() {
    const i = document.getElementById('pwd');
    const icon = document.getElementById('pwd-icon');
    i.type = i.type === 'password' ? 'text' : 'password';
    icon.className = i.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
</body>
</html>