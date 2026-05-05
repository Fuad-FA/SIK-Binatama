@extends('layouts.app')

@section('title', 'Edit Staf')
@section('page-title', 'Edit Akun Staf')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-pencil-square me-2"></i> Edit Akun: {{ $user->name }}
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">@</span>
                    <input type="text" name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username', $user->username) }}">
                </div>
                @error('username')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select">
                    <option value="guru"  {{ old('role', $user->role) === 'guru'  ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ old('role', $user->role) === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Jabatan / Kelas</label>
                <input type="text" name="jabatan" class="form-control"
                       value="{{ old('jabatan', $user->jabatan) }}">
            </div>

            @if($user->role === 'siswa')
            <div class="mb-3">
                <label class="form-label fw-semibold">Barcode QR</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-qr-code"></i></span>
                    <input type="text" name="barcode"
                           class="form-control @error('barcode') is-invalid @enderror"
                           value="{{ old('barcode', $user->barcode) }}">
                </div>
                @error('barcode')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label fw-semibold">Password Baru</label>
                <div class="input-group">
                    <input type="password" name="password" id="edit-password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('edit-password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active"
                           id="is_active" value="1"
                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="is_active">
                        Akun Aktif
                    </label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
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
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
}
</script>
@endpush