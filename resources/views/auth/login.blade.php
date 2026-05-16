<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIK Rumah Sehat Binatama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    {{-- QR Code Scanner --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        :root {
            --biru-tua : #1565C0;
            --biru-muda: #1E88E5;
            --kuning   : #FDD835;
            --hijau    : #2E7D32;
            --orange   : #F57C00;
        }

        body {
            background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1976D2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 16px;
        }

        /* Header kartu */
        .login-header {
            background: var(--kuning);
            border-radius: 16px 16px 0 0;
            padding: 28px 32px 20px;
            text-align: center;
        }

        .login-header .logo-icon {
            width: 64px; height: 64px;
            background: var(--biru-tua);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
        }

        .login-header h4 {
            color: var(--biru-tua);
            font-weight: 800;
            margin-bottom: 2px;
            font-size: 18px;
        }

        .login-header p {
            color: #555;
            font-size: 12px;
            margin: 0;
        }

        /* Body kartu */
        .login-body {
            background: #fff;
            border-radius: 0 0 16px 16px;
            padding: 28px 32px 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        /* Tab login */
        .login-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 0;
        }

        .login-tabs .nav-link.active {
            color: var(--biru-tua);
            border-bottom-color: var(--biru-tua);
            background: transparent;
        }

        /* Input */
        .form-control {
            border-radius: 8px;
            border: 1.5px solid #ddd;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.2s;
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

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
            border-left: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--biru-muda);
        }

        /* Tombol login */
        .btn-login {
            background: linear-gradient(135deg, var(--biru-muda), var(--biru-tua));
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            width: 100%;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-login:active { transform: translateY(0); }

        /* Footer kartu */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
        }

        .alert { border-radius: 8px; font-size: 13px; }

        /* QR scanner mock */
        .qr-scanner-box {
            border: 2px dashed #ddd;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .qr-scanner-box:hover {
            border-color: var(--biru-muda);
            background: #e3f2fd;
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- Header --}}
    <div class="login-header">
        <div class="logo-icon">
            <i class="bi bi-heart-pulse-fill text-white fs-4"></i>
        </div>
        <h4>Rumah Sehat Binatama</h4>
        <p>SMK Kesehatan Binatama Yogyakarta</p>
    </div>

    {{-- Body --}}
    <div class="login-body">

        {{-- Alert error --}}
        @if(session('error'))
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success mb-3">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Tab Admin/Guru vs Siswa --}}
        <ul class="nav login-tabs mb-4" id="loginTab">
            <li class="nav-item">
                <button class="nav-link active" id="tab-user"
                        onclick="switchTab('user')">
                    <i class="bi bi-person-fill me-1"></i> Admin / Guru
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-siswa"
                        onclick="switchTab('siswa')">
                    <i class="bi bi-qr-code me-1"></i> Siswa (QR)
                </button>
            </li>
        </ul>

        {{-- Form Login Admin/Guru --}}
        <div id="form-user">
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;color:#444;">
                        Username
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <input type="text" name="username"
                               class="form-control @error('username') is-invalid @enderror"
                               placeholder="Masukkan username"
                               value="{{ old('username') }}"
                               autocomplete="username" autofocus>
                    </div>
                    @error('username')
                        <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:13px;color:#444;">
                        Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" name="password" id="password-user"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Masukkan password">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                style="border-radius:0 8px 8px 0;border:1.5px solid #ddd;border-left:none;"
                                onclick="togglePassword('password-user', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:13px;">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
                </button>
            </form>
        </div>

        {{-- Form Login Siswa (QR Code) --}}
        <div id="form-siswa" style="display:none;">
            <form action="{{ route('login.qr') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;color:#444;">
                        Barcode / QR Code Siswa
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-qr-code"></i>
                        </span>
                        <input type="text" name="barcode" id="barcode-input"
                               class="form-control"
                               placeholder="Scan QR atau ketik barcode">
                    </div>
                    {{-- <div class="mt-2 qr-scanner-box" onclick="focusBarcode()">
                        <i class="bi bi-qr-code-scan fs-2 text-secondary mb-1"></i>
                        <p class="mb-0 text-secondary" style="font-size:12px;">
                            Klik di sini lalu scan QR Code<br>menggunakan kamera/scanner HP
                        </p>
                    </div> --}}
                    <div class="mt-2">
                        {{-- Tombol buka kamera --}}
                        <button type="button" id="btn-scan"
                                class="btn w-100 py-2"
                                style="background:#f8f9fa;border:2px dashed #90caf9;
                                       border-radius:8px;color:#555;"
                                onclick="startScan()">
                            <i class="bi bi-qr-code-scan fs-4 d-block mb-1"
                               style="color:var(--biru-muda);"></i>
                            <span style="font-size:12px;">
                                Klik untuk scan QR Code via kamera
                            </span>
                        </button>

                        {{-- Area kamera --}}
                        <div id="qr-reader-container" style="display:none;margin-top:8px;">
                            <div id="qr-reader" style="border-radius:8px;overflow:hidden;"></div>
                            <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-2"
                                    onclick="stopScan()">
                                <i class="bi bi-x-circle me-1"></i>Tutup Kamera
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:13px;color:#444;">
                        Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" name="password" id="password-siswa"
                               class="form-control"
                               placeholder="Password (default: siswa)">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                style="border-radius:0 8px 8px 0;border:1.5px solid #ddd;border-left:none;"
                                onclick="togglePassword('password-siswa', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <small class="text-muted">Password default: <code>siswa</code></small>
                </div>

                <button type="submit" class="btn btn-login"
                        style="background: linear-gradient(135deg, #2E7D32, #1B5E20);">
                    <i class="bi bi-qr-code me-2"></i> Masuk dengan QR
                </button>
            </form>
        </div>

    </div>

    {{-- Footer --}}
    <div class="login-footer">
        <i class="bi bi-shield-check me-1"></i>
        Sistem Informasi Kesehatan &copy; {{ date('Y') }}<br>
        SMK Kesehatan Binatama Yogyakarta
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function switchTab(tab) {
        const isUser = tab === 'user';
        document.getElementById('form-user').style.display   = isUser ? 'block' : 'none';
        document.getElementById('form-siswa').style.display  = isUser ? 'none'  : 'block';
        document.getElementById('tab-user').classList.toggle('active',  isUser);
        document.getElementById('tab-siswa').classList.toggle('active', !isUser);
    }

    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.innerHTML = isText
            ? '<i class="bi bi-eye"></i>'
            : '<i class="bi bi-eye-slash"></i>';
    }

    // function focusBarcode() {
    //     document.getElementById('barcode-input').focus();
    // }
    // Load library html5-qrcode dari CDN
// const script = document.createElement('script');
// script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
// document.head.appendChild(script);

// let html5QrCode = null;

// function startScan() {
//     document.getElementById('btn-scan').style.display = 'none';
//     document.getElementById('qr-reader-container').style.display = 'block';

//     html5QrCode = new Html5Qrcode("qr-reader");

//     html5QrCode.start(
//         { facingMode: "environment" }, // kamera belakang
//         {
//             fps: 10,
//             qrbox: { width: 220, height: 220 },
//         },
//         (decodedText) => {
//             // Berhasil scan
//             document.getElementById('barcode-input').value = decodedText;
//             stopScan();

//             // Flash success
//             document.getElementById('barcode-input').style.borderColor = '#2E7D32';
//             setTimeout(() => {
//                 document.getElementById('barcode-input').style.borderColor = '';
//             }, 2000);
//         },
//         (errorMessage) => {
//             // Scan belum berhasil, abaikan
//         }
//     ).catch(err => {
//         alert('Tidak bisa mengakses kamera: ' + err);
//         stopScan();
//     });
// }

// function stopScan() {
//     if (html5QrCode) {
//         html5QrCode.stop().then(() => {
//             html5QrCode.clear();
//             html5QrCode = null;
//         }).catch(() => {});
//     }
//     document.getElementById('qr-reader-container').style.display = 'none';
//     document.getElementById('btn-scan').style.display = 'block';
// }
let html5QrCode = null;
let isScanning  = false;

function startScan() {
    if (isScanning) return;

    document.getElementById('btn-scan').style.display = 'none';
    document.getElementById('qr-reader-container').style.display = 'block';

    html5QrCode = new Html5Qrcode("qr-reader");
    isScanning  = true;

    Html5Qrcode.getCameras().then(cameras => {
        if (!cameras || cameras.length === 0) {
            alert('Tidak ada kamera ditemukan.');
            stopScan();
            return;
        }

        // Pilih kamera belakang jika ada
        const cameraId = cameras.length > 1 ? cameras[1].id : cameras[0].id;

        html5QrCode.start(
            cameraId,
            {
                fps: 15,
                qrbox: { width: 200, height: 200 },
                aspectRatio: 1.0,
            },
            (decodedText) => {
                // Berhasil scan — masukkan ke field
                const input = document.getElementById('barcode-input');
                input.value = decodedText.trim().toUpperCase();

                // Highlight hijau
                input.style.borderColor = '#2E7D32';
                input.style.background  = '#f1f8e9';
                setTimeout(() => {
                    input.style.borderColor = '';
                    input.style.background  = '';
                }, 3000);

                stopScan();
            },
            () => {
                // Frame belum berhasil baca, abaikan
            }
        ).catch(err => {
            console.error(err);
            alert('Gagal membuka kamera: ' + err);
            stopScan();
        });

    }).catch(err => {
        alert('Tidak bisa mengakses kamera: ' + err);
        stopScan();
    });
}

function stopScan() {
    isScanning = false;
    if (html5QrCode) {
        html5QrCode.stop()
            .then(() => { html5QrCode.clear(); html5QrCode = null; })
            .catch(() => { html5QrCode = null; });
    }
    document.getElementById('qr-reader-container').style.display = 'none';
    document.getElementById('btn-scan').style.display = 'block';
}
</script>
</body>
</html>