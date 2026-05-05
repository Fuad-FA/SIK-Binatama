@extends('layouts.app')
@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-pencil-square me-2"></i> Edit: {{ $product->nama }}
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Produk <span class="text-danger">*</span></label>
                    <input type="text" name="kode_produk"
                           class="form-control @error('kode_produk') is-invalid @enderror"
                           value="{{ old('kode_produk', $product->kode_produk) }}"
                           style="text-transform:uppercase;">
                    @error('kode_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $product->nama) }}">
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror">
                        <option value="makanan_minuman" {{ old('kategori', $product->kategori) === 'makanan_minuman' ? 'selected':'' }}>Makanan & Minuman</option>
                        <option value="pembersih"       {{ old('kategori', $product->kategori) === 'pembersih'       ? 'selected':'' }}>Pembersih</option>
                        <option value="lainnya"         {{ old('kategori', $product->kategori) === 'lainnya'         ? 'selected':'' }}>Lainnya</option>
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control"
                           value="{{ old('keterangan', $product->keterangan) }}">
                </div>

                <div class="col-12">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox"
                               name="harga_by_order" id="harga_by_order" value="1"
                               {{ old('harga_by_order', $product->harga_by_order) ? 'checked':'' }}
                               onchange="toggleHarga(this.checked)">
                        <label class="form-check-label fw-semibold" for="harga_by_order">
                            Harga By Order
                        </label>
                    </div>
                    <div id="harga-field"
                         style="{{ old('harga_by_order', $product->harga_by_order) ? 'display:none;':'' }}">
                        <label class="form-label fw-semibold">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text fw-semibold">Rp</span>
                            <input type="number" name="harga"
                                   class="form-control @error('harga') is-invalid @enderror"
                                   value="{{ old('harga', $product->harga) }}" min="0" step="500">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stok"
                           class="form-control @error('stok') is-invalid @enderror"
                           value="{{ old('stok', $product->stok) }}" min="0">
                    @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                    <select name="satuan" class="form-select">
                        @foreach(['pcs','botol','sachet','kg','liter','jerigen'] as $s)
                            <option value="{{ $s }}"
                                {{ old('satuan', $product->satuan) === $s ? 'selected':'' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active"
                               id="is_active" value="1"
                               {{ old('is_active', $product->is_active) ? 'checked':'' }}>
                        <label class="form-check-label fw-semibold" for="is_active">
                            Produk Aktif
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="btn btn-outline-secondary px-4">
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
function toggleHarga(isByOrder) {
    document.getElementById('harga-field').style.display = isByOrder ? 'none' : 'block';
}
</script>
@endpush