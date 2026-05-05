@extends('layouts.app')
@section('title', 'Layanan & Paket')
@section('page-title', 'Layanan & Paket')

@section('content')

{{-- Tab navigasi Layanan / Paket --}}
<ul class="nav nav-tabs mb-4" style="border-bottom:2px solid #e0e0e0;">
    <li class="nav-item">
        <a class="nav-link active fw-semibold"
           style="color:var(--biru-tua);border-bottom:3px solid var(--biru-tua);border-radius:0;"
           href="{{ route('admin.services.index') }}">
            <i class="bi bi-heart-pulse-fill me-2"></i>Layanan Satuan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-semibold text-muted"
           href="{{ route('admin.packages.index') }}">
            <i class="bi bi-gift-fill me-2"></i>Paket Layanan
        </a>
    </li>
</ul>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Daftar Layanan Satuan</h5>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle-fill me-2"></i>Tambah Layanan
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#888;font-weight:600;">KODE</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">NAMA LAYANAN</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">HARGA</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">DURASI</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">STATUS</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                <tr>
                    <td class="ps-4">
                        <code style="background:#f0f4f8;padding:2px 8px;border-radius:4px;font-size:12px;">
                            {{ $service->kode }}
                        </code>
                    </td>
                    <td class="fw-semibold" style="font-size:14px;">{{ $service->nama }}</td>
                    {{-- <td style="color:var(--orange);font-weight:600;">
                        {{ $service->harga > 0 ? $service->hargaFormatted() : '<span class="text-muted fw-normal" style="font-size:12px;">Gratis / Termasuk paket</span>' }}
                    </td> --}}
                    <td>
                        @if($service->harga > 0)
                            <span style="color:var(--orange);font-weight:600;">
                                {{ $service->hargaFormatted() }}
                            </span>
                        @else
                            <span class="text-muted" style="font-size:12px;">Gratis / Termasuk paket</span>
                        @endif
                    </td>
                    <td style="font-size:13px;color:#888;">
                        {{ $service->durasi_menit ? $service->durasi_menit . ' menit' : '-' }}
                    </td>
                    <td>
                        @if($service->is_active)
                            <span class="badge bg-success-subtle text-success px-2 py-1">
                                <i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                <i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>Nonaktif
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.services.edit', $service) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.services.toggle-active', $service) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm {{ $service->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        onclick="return confirm('{{ $service->is_active ? 'Nonaktifkan' : 'Aktifkan' }} layanan ini?')">
                                    <i class="bi bi-{{ $service->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.services.destroy', $service) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus layanan {{ $service->nama }}?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-heart-pulse fs-1 d-block mb-2 opacity-25"></i>
                        Belum ada layanan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($services->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $services->links() }}
        </div>
    @endif
</div>

@endsection