@extends('layouts.app')
@section('title', 'Tambah Paket')
@section('page-title', 'Tambah Paket Layanan')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-gift-fill me-2"></i>Form Tambah Paket
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.packages.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                <input type="text" name="nama"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama') }}" placeholder="Paket Sehat 6">
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
                @error('harga')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <input type="text" name="deskripsi" class="form-control"
                       value="{{ old('deskripsi') }}" placeholder="Keterangan singkat paket">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Pilih Layanan yang Termasuk
                    <span class="text-muted fw-normal" style="font-size:12px;">
                        (bisa pilih lebih dari satu)
                    </span>
                </label>
                <div class="border rounded p-3" style="max-height:300px;overflow-y:auto;border-radius:8px!important;">
                    @foreach($services as $svc)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox"
                               name="service_ids[]" value="{{ $svc->id }}"
                               id="svc{{ $svc->id }}"
                               {{ in_array($svc->id, old('service_ids', [])) ? 'checked':'' }}>
                        <label class="form-check-label d-flex justify-content-between"
                               for="svc{{ $svc->id }}">
                            <span>
                                <code style="font-size:11px;background:#f0f4f8;padding:1px 6px;border-radius:3px;">
                                    {{ $svc->kode }}
                                </code>
                                <span class="ms-2">{{ $svc->nama }}</span>
                            </span>
                            @if($svc->harga > 0)
                                <span style="color:var(--orange);font-size:12px;font-weight:600;">
                                    {{ $svc->hargaFormatted() }}
                                </span>
                            @endif
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>Simpan Paket
                </button>
                <a href="{{ route('admin.packages.index') }}"
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