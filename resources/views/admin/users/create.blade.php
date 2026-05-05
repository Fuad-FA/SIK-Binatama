@extends('layouts.app')

@section('title', 'Tambah Staf')
@section('page-title', 'Tambah Staf Baru')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-person-plus-fill me-2"></i> Form Tambah Staf
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            {{-- Nama Lengkap --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Nama lengkap staf">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Username --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Username <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">@</span>
                    <input type="text" name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}"
                           placeholder="username_unik" autocomplete="off">
                </div>
                @error('username')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
                <div class="form-text">Hanya huruf, angka, tanda hubung (-) dan underscore (_).</div>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="email@sekolah.sch.id (opsional)">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Role --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Role <span class="text-danger">*</span>
                </label>
                <select name="role" id="role-select"
                        class="form-select @error('role') is-invalid @enderror"
                        onchange="toggleBarcodeField(this.value)">
                    <option value="">-- Pilih Role --</option>
                    <option value="guru"  {{ old('role') === 'guru'  ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jabatan --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Jabatan / Kelas</label>
                <input type="text" name="jabatan"
                       class="form-control"
                       value="{{ old('jabatan') }}"
                       placeholder="Contoh: Guru Farmasi / Kelas XI Keperawatan A">
            </div>

            {{-- Barcode (khusus siswa) --}}
            <div class="mb-3" id="barcode-field"
                 style="{{ old('role') === 'siswa' ? '' : 'display:none;' }}">
                <label class="form-label fw-semibold">
                    Barcode QR <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-qr-code"></i>
                    </span>
                    <input type="text" name="barcode"
                           class="form-control @error('barcode') is-invalid @enderror"
                           value="{{ old('barcode') }}"
                           placeholder="Kode unik dari kartu siswa">
                </div>
                @error('barcode')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    Password default siswa: <code>siswa</code> (wajib ganti saat login pertama)
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Password <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="password" name="password" id="password-input"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimal 6 karakter">
                    <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('password-input', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i> Simpan
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection

@push('scripts')
<script>
function toggleBarcodeField(role) {
    const field = document.getElementById('barcode-field');
    const passwordInput = document.getElementById('password-input');
    if (role === 'siswa') {
        field.style.display = 'block';
        passwordInput.placeholder = 'Default: siswa';
        passwordInput.value = 'siswa';
    } else {
        field.style.display = 'none';
        passwordInput.placeholder = 'Minimal 6 karakter';
        passwordInput.value = '';
    }
}

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
}
</script>
@endpush