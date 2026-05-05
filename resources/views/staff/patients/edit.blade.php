@extends('layouts.app')
@section('title', 'Edit Pasien')
@section('page-title', 'Edit Data Pasien')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-pencil-square me-2"></i>Edit: {{ $patient->nama }}
    </div>
    <div class="card-body p-4">
        <form action="{{ route('staff.patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text" name="nama"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama', $patient->nama) }}">
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           class="form-control @error('tanggal_lahir') is-invalid @enderror"
                           value="{{ old('tanggal_lahir', $patient->tanggal_lahir?->format('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}">
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin', $patient->jenis_kelamin) === 'L' ? 'selected':'' }}>
                            Laki-laki
                        </option>
                        <option value="P" {{ old('jenis_kelamin', $patient->jenis_kelamin) === 'P' ? 'selected':'' }}>
                            Perempuan
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $patient->alamat) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">No. Telepon</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                    <input type="text" name="telepon" class="form-control"
                           value="{{ old('telepon', $patient->telepon) }}">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>Simpan
                </button>
                <a href="{{ route('staff.patients.show', $patient) }}"
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