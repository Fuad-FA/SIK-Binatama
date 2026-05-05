@extends('layouts.app')
@section('title', 'Tambah Layanan')
@section('page-title', 'Tambah Layanan')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-plus-circle-fill me-2"></i>Form Tambah Layanan
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.services.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode <span class="text-danger">*</span></label>
                <input type="text" name="kode"
                       class="form-control @error('kode') is-invalid @enderror"
                       value="{{ old('kode') }}" placeholder="SVC-019"
                       style="text-transform:uppercase;">
                @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Layanan <span class="text-danger">*</span></label>
                <input type="text" name="nama"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama') }}" placeholder="Nama layanan">
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text fw-semibold">Rp</span>
                    <input type="number" name="harga"
                           class="form-control @error('harga') is-invalid @enderror"
                           value="{{ old('harga', 0) }}" min="0" step="500">
                </div>
                <div class="form-text">Isi 0 jika layanan ini gratis atau sudah termasuk paket.</div>
                @error('harga')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Durasi (menit)</label>
                <input type="number" name="durasi_menit" class="form-control"
                       value="{{ old('durasi_menit') }}" min="1"
                       placeholder="Kosongkan jika tidak ada durasi pasti">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>Simpan
                </button>
                <a href="{{ route('admin.services.index') }}"
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