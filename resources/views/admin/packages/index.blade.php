@extends('layouts.app')
@section('title', 'Paket Layanan')
@section('page-title', 'Layanan & Paket')

@section('content')

{{-- Tab navigasi --}}
<ul class="nav nav-tabs mb-4" style="border-bottom:2px solid #e0e0e0;">
    <li class="nav-item">
        <a class="nav-link fw-semibold text-muted"
           href="{{ route('admin.services.index') }}">
            <i class="bi bi-heart-pulse-fill me-2"></i>Layanan Satuan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active fw-semibold"
           style="color:var(--biru-tua);border-bottom:3px solid var(--biru-tua);border-radius:0;"
           href="{{ route('admin.packages.index') }}">
            <i class="bi bi-gift-fill me-2"></i>Paket Layanan
        </a>
    </li>
</ul>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Daftar Paket Layanan</h5>
    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle-fill me-2"></i>Tambah Paket
    </a>
</div>

<div class="row g-3">
    @forelse($packages as $package)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100"
             style="border-radius:12px;border-left:4px solid {{ $package->is_active ? 'var(--hijau)' : '#ccc' }} !important;">
            <div class="card-body p-4">
                {{-- Header paket --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $package->nama }}</h6>
                        @if($package->deskripsi)
                            <p class="text-muted mb-0" style="font-size:12px;">
                                {{ $package->deskripsi }}
                            </p>
                        @endif
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-5" style="color:var(--orange);">
                            {{ $package->hargaFormatted() }}
                        </div>
                        @if($package->is_active)
                            <span class="badge bg-success-subtle text-success" style="font-size:10px;">Aktif</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger" style="font-size:10px;">Nonaktif</span>
                        @endif
                    </div>
                </div>

                {{-- Daftar layanan dalam paket --}}
                <div class="mb-3">
                    <div class="text-muted fw-semibold mb-2" style="font-size:11px;">
                        TERMASUK LAYANAN:
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($package->services as $svc)
                            <span class="badge px-2 py-1"
                                  style="background:#e3f2fd;color:var(--biru-tua);font-size:11px;">
                                {{ $svc->nama }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.packages.edit', $package) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form action="{{ route('admin.packages.toggle-active', $package) }}"
                          method="POST" class="d-inline">
                        @csrf
                        <button type="submit"
                                class="btn btn-sm {{ $package->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                onclick="return confirm('{{ $package->is_active ? 'Nonaktifkan' : 'Aktifkan' }} paket ini?')">
                            <i class="bi bi-{{ $package->is_active ? 'pause-circle' : 'play-circle' }} me-1"></i>
                            {{ $package->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.packages.destroy', $package) }}"
                          method="POST" class="d-inline ms-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Hapus paket {{ $package->nama }}?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted">
            <i class="bi bi-gift fs-1 d-block mb-2 opacity-25"></i>
            Belum ada paket layanan.
            <a href="{{ route('admin.packages.create') }}">Tambah sekarang</a>
        </div>
    </div>
    @endforelse
</div>

@endsection