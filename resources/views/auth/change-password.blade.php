@extends('layouts.app')

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
@endsection