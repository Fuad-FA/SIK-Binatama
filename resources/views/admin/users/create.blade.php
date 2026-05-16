@extends('layouts.app')
@section('title', 'Tambah Staf')
@section('page-title', 'Tambah Staf Baru')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-person-plus-fill me-2"></i>Form Tambah Staf
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.users.store') }}" method="POST" id="form-staf">
            @csrf

            {{-- 1. Nama Lengkap --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="Nama lengkap staf">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- 2. Role --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Role <span class="text-danger">*</span>
                </label>
                <select name="role" id="role-select"
                        class="form-select @error('role') is-invalid @enderror"
                        onchange="updateForm(this.value)">
                    <option value="">-- Pilih Role --</option>
                    <option value="guru"  {{ old('role') === 'guru'  ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- 3a. Username (hanya Guru) --}}
            <div class="mb-3" id="field-username"
                 style="{{ old('role') === 'guru' ? '' : 'display:none;' }}">
                <label class="form-label fw-semibold">
                    Username <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">@</span>
                    <input type="text" name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}"
                           placeholder="username_guru"
                           autocomplete="off">
                </div>
                @error('username')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
                <div class="form-text">Hanya huruf, angka, tanda hubung (-) dan underscore (_).</div>
            </div>

            {{-- 3b. Barcode QR (hanya Siswa) --}}
            <div class="mb-3" id="field-barcode"
                 style="{{ old('role') === 'siswa' ? '' : 'display:none;' }}">
                <label class="form-label fw-semibold">
                    Barcode / QR Code <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-qr-code"></i>
                    </span>
                    <input type="text" name="barcode"
                           class="form-control @error('barcode') is-invalid @enderror"
                           value="{{ old('barcode') }}"
                           placeholder="Kode dari kartu siswa">
                </div>
                @error('barcode')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
            </div>

            {{-- 4. Jabatan / Kelas --}}
            <div class="mb-3" id="field-jabatan"
                 style="{{ old('role') ? '' : 'display:none;' }}">
                <label class="form-label fw-semibold" id="label-jabatan">
                    Jabatan / Kelas
                </label>
                <input type="text" name="jabatan"
                       class="form-control"
                       value="{{ old('jabatan') }}"
                       id="input-jabatan"
                       placeholder="Contoh: Guru Farmasi / Kelas XI Keperawatan A">
            </div>

            {{-- 5. Password --}}
            <div class="mb-4" id="field-password"
                 style="{{ old('role') ? '' : 'display:none;' }}">
                <label class="form-label fw-semibold">
                    Password <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="password" name="password" id="password-input"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimal 5 karakter">
                    <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('password-input', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
                <div id="password-hint" class="form-text" style="display:none;">
                    Password default siswa: <code>siswa</code>
                    (wajib ganti saat login pertama)
                </div>
            </div>

            {{-- Tombol --}}
            <div class="d-flex gap-2" id="field-buttons" style="display:none!important;">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>Simpan
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Batal
                </a>
            </div>

            {{-- Tombol selalu tampil --}}
            <div class="d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>Simpan
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Batal
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
// Jalankan saat load jika ada old value
document.addEventListener('DOMContentLoaded', function() {
    const role = document.getElementById('role-select').value;
    if (role) updateForm(role);
});

function updateForm(role) {
    const fieldUsername = document.getElementById('field-username');
    const fieldBarcode  = document.getElementById('field-barcode');
    const fieldJabatan  = document.getElementById('field-jabatan');
    const fieldPassword = document.getElementById('field-password');
    const labelJabatan  = document.getElementById('label-jabatan');
    const inputJabatan  = document.getElementById('input-jabatan');
    const passwordInput = document.getElementById('password-input');
    const passwordHint  = document.getElementById('password-hint');

    if (role === 'guru') {
        fieldUsername.style.display = 'block';
        fieldBarcode.style.display  = 'none';
        fieldJabatan.style.display  = 'block';
        fieldPassword.style.display = 'block';
        labelJabatan.textContent    = 'Jabatan';
        inputJabatan.placeholder    = 'Contoh: Guru Farmasi, Guru Keperawatan';
        passwordInput.placeholder   = 'Minimal 5 karakter';
        passwordHint.style.display  = 'none';
        // Kosongkan barcode
        document.querySelector('[name=barcode]').value = '';
    } else if (role === 'siswa') {
        fieldUsername.style.display = 'none';
        fieldBarcode.style.display  = 'block';
        fieldJabatan.style.display  = 'block';
        fieldPassword.style.display = 'block';
        labelJabatan.textContent    = 'Kelas';
        inputJabatan.placeholder    = 'Contoh: XI Keperawatan A, XII Farmasi B';
        passwordInput.placeholder   = 'Default: siswa';
        passwordInput.value         = 'siswa';
        passwordHint.style.display  = 'block';
        // Kosongkan username
        document.querySelector('[name=username]') &&
            (document.querySelector('[name=username]').value = '');
    }
}

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText
        ? '<i class="bi bi-eye"></i>'
        : '<i class="bi bi-eye-slash"></i>';
}
</script>
@endpush