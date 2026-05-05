@extends('layouts.app')
@section('title', 'Manajemen Produk')
@section('page-title', 'Manajemen Produk')

@section('content')

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#e3f2fd;border-radius:10px;padding:10px;">
                    <i class="bi bi-box-seam-fill fs-4" style="color:var(--biru-muda);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Total Produk</div>
                    <div class="fw-bold fs-4">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card stat-card green p-3">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#e8f5e9;border-radius:10px;padding:10px;">
                    <i class="bi bi-check-circle-fill fs-4" style="color:var(--hijau);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Produk Aktif</div>
                    <div class="fw-bold fs-4">{{ $stats['aktif'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card stat-card orange p-3">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#fff3e0;border-radius:10px;padding:10px;">
                    <i class="bi bi-exclamation-triangle-fill fs-4" style="color:var(--orange);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:12px;">Stok Menipis</div>
                    <div class="fw-bold fs-4">{{ $stats['stok_min'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Header & Tombol --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Daftar Produk</h5>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle-fill me-2"></i> Tambah Produk
    </a>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.products.index') }}"
              class="row g-2 align-items-end">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0"
                           placeholder="Cari nama, kode produk..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="makanan_minuman" {{ request('kategori') === 'makanan_minuman' ? 'selected' : '' }}>
                        Makanan & Minuman
                    </option>
                    <option value="pembersih" {{ request('kategori') === 'pembersih' ? 'selected' : '' }}>
                        Pembersih
                    </option>
                    <option value="lainnya" {{ request('kategori') === 'lainnya' ? 'selected' : '' }}>
                        Lainnya
                    </option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel-fill"></i>
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#888;font-weight:600;">PRODUK</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">KODE</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">KATEGORI</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">HARGA</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">STOK</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">STATUS</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    {{-- Nama & keterangan --}}
                    <td class="ps-4">
                        <div class="fw-semibold" style="font-size:14px;">{{ $product->nama }}</div>
                        @if($product->keterangan)
                            <div class="text-muted" style="font-size:11px;">{{ $product->keterangan }}</div>
                        @endif
                    </td>

                    {{-- Kode --}}
                    <td>
                        <code style="background:#f0f4f8;padding:2px 8px;
                                     border-radius:4px;font-size:12px;">
                            {{ $product->kode_produk }}
                        </code>
                    </td>

                    {{-- Kategori --}}
                    <td>
                        @php
                            $katStyle = match($product->kategori) {
                                'makanan_minuman' => 'bg-success-subtle text-success',
                                'pembersih'       => 'bg-info-subtle text-info',
                                default           => 'bg-secondary-subtle text-secondary',
                            };
                        @endphp
                        <span class="badge {{ $katStyle }} px-2 py-1">
                            {{ $product->labelKategori() }}
                        </span>
                    </td>

                    {{-- Harga --}}
                    <td class="fw-semibold" style="font-size:13px;
                        color:{{ $product->harga_by_order ? '#888' : 'var(--orange)' }};">
                        {{ $product->hargaFormatted() }}
                    </td>

                    {{-- Stok --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold
                                {{ $product->stok <= 5 ? 'text-danger' : ($product->stok <= 10 ? 'text-warning' : 'text-success') }}">
                                {{ $product->stok }}
                            </span>
                            <span class="text-muted" style="font-size:11px;">{{ $product->satuan }}</span>
                            {{-- Quick update stok --}}
                            <button class="btn btn-sm btn-outline-secondary py-0 px-1"
                                    style="font-size:11px;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#stokModal{{ $product->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </td>

                    {{-- Status --}}
                    <td>
                        @if($product->is_active)
                            <span class="badge bg-success-subtle text-success px-2 py-1">
                                <i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                <i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>Nonaktif
                            </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.toggle-active', $product) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm {{ $product->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        title="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                        onclick="return confirm('{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }} produk ini?')">
                                    <i class="bi bi-{{ $product->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.products.destroy', $product) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        title="Hapus"
                                        onclick="return confirm('Hapus produk {{ $product->nama }}?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Modal Update Stok --}}
                <div class="modal fade" id="stokModal{{ $product->id }}" tabindex="-1">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content" style="border-radius:12px;">
                            <div class="modal-header py-2"
                                 style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
                                <h6 class="modal-title text-white mb-0">
                                    <i class="bi bi-box-seam me-2"></i>Update Stok
                                </h6>
                                <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-3">
                                <p class="text-muted mb-2" style="font-size:13px;">
                                    {{ $product->nama }}
                                </p>
                                <form action="{{ route('admin.products.stok', $product) }}"
                                      method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="number" name="stok"
                                               class="form-control text-center fw-bold"
                                               value="{{ $product->stok }}" min="0">
                                        <span class="input-group-text">{{ $product->satuan }}</span>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mt-2">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-box-seam fs-1 d-block mb-2 opacity-25"></i>
                        Belum ada produk.
                        <a href="{{ route('admin.products.create') }}">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $products->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection