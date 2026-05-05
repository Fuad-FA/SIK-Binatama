@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-plus-circle-fill me-2"></i> Form Tambah Produk
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        Kode Produk <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="kode_produk"
                           class="form-control @error('kode_produk') is-invalid @enderror"
                           value="{{ old('kode_produk') }}"
                           placeholder="PRD-019" style="text-transform:uppercase;">
                    @error('kode_produk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">
                        Nama Produk <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}" placeholder="Nama lengkap produk">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Kategori <span class="text-danger">*</span>
                    </label>
                    <select name="kategori"
                            class="form-select @error('kategori') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="makanan_minuman"
                            {{ old('kategori') === 'makanan_minuman' ? 'selected' : '' }}>
                            Makanan & Minuman
                        </option>
                        <option value="pembersih"
                            {{ old('kategori') === 'pembersih' ? 'selected' : '' }}>
                            Pembersih
                        </option>
                        <option value="lainnya"
                            {{ old('kategori') === 'lainnya' ? 'selected' : '' }}>
                            Lainnya
                        </option>
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control"
                           value="{{ old('keterangan') }}"
                           placeholder="Deskripsi singkat produk">
                </div>

                {{-- Harga --}}
                <div class="col-12">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox"
                               name="harga_by_order" id="harga_by_order" value="1"
                               {{ old('harga_by_order') ? 'checked' : '' }}
                               onchange="toggleHarga(this.checked)">
                        <label class="form-check-label fw-semibold" for="harga_by_order">
                            Harga By Order (harga ditentukan saat transaksi)
                        </label>
                    </div>
                    <div id="harga-field" style="{{ old('harga_by_order') ? 'display:none;' : '' }}">
                        <label class="form-label fw-semibold">
                            Harga <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text fw-semibold">Rp</span>
                            <input type="number" name="harga"
                                   class="form-control @error('harga') is-invalid @enderror"
                                   value="{{ old('harga', 0) }}" min="0" step="500">
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Stok Awal <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="stok"
                           class="form-control @error('stok') is-invalid @enderror"
                           value="{{ old('stok', 0) }}" min="0">
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Satuan <span class="text-danger">*</span>
                    </label>
                    <select name="satuan" class="form-select">
                        <option value="pcs"   {{ old('satuan') === 'pcs'   ? 'selected' : '' }}>pcs</option>
                        <option value="botol" {{ old('satuan') === 'botol' ? 'selected' : '' }}>botol</option>
                        <option value="sachet"{{ old('satuan') === 'sachet'? 'selected' : '' }}>sachet</option>
                        <option value="kg"    {{ old('satuan') === 'kg'    ? 'selected' : '' }}>kg</option>
                        <option value="liter" {{ old('satuan') === 'liter' ? 'selected' : '' }}>liter</option>
                        <option value="jerigen"{{ old('satuan')=== 'jerigen'?'selected':'' }}>jerigen</option>
                    </select>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i> Simpan Produk
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