@extends('layouts.app')
@section('title', 'Daftarkan Pasien')
@section('page-title', 'Daftarkan Pasien Baru')
@php
    use Illuminate\Support\Str;
@endphp
@section('content')
<div class="row">
<div class="col-lg-7">

{{-- Kotak cari pasien lama --}}
<div class="card border-0 shadow-sm mb-4"
     style="border-radius:12px;border-left:4px solid var(--kuning) !important;">
    <div class="card-body p-3">
        <div class="fw-semibold mb-2" style="font-size:13px;color:#555;">
            <i class="bi bi-search me-2" style="color:var(--orange);"></i>
            Cek terlebih dahulu — apakah pasien sudah pernah terdaftar?
        </div>
        <div class="input-group">
            <input type="text" id="search-existing"
                   class="form-control" placeholder="Ketik nama atau No. RM...">
            <button class="btn btn-warning fw-semibold" type="button"
                    onclick="searchExisting()">
                <i class="bi bi-search me-1"></i>Cari
            </button>
        </div>
        <div id="search-results" class="mt-2"></div>
    </div>
</div>

{{-- Alert duplikasi --}}
@if(session('error_duplikat'))
@php $dup = session('error_duplikat'); @endphp
<div class="alert alert-warning border-0 shadow-sm mb-4"
     style="border-radius:12px;border-left:4px solid var(--orange) !important;">
    <div class="fw-bold mb-2">
        <i class="bi bi-exclamation-triangle-fill me-2" style="color:var(--orange);"></i>
        Pasien sudah terdaftar!
    </div>
    <div class="d-flex align-items-center gap-3 p-2 rounded"
         style="background:#fff8e1;">
        <div style="width:40px;height:40px;border-radius:50%;background:var(--orange);
                    display:flex;align-items:center;justify-content:center;
                    color:#fff;font-weight:700;font-size:16px;flex-shrink:0;">
            {{ strtoupper(substr($dup->nama, 0, 1)) }}
        </div>
        <div class="flex-grow-1">
            <div class="fw-bold">{{ $dup->nama }}</div>
            <div style="font-size:12px;color:#555;">
                <code style="background:#ffe0b2;padding:1px 6px;border-radius:3px;color:var(--orange);">
                    {{ $dup->no_rm }}
                </code>
                @if($dup->tanggal_lahir)
                    · {{ $dup->tanggal_lahir->format('d M Y') }}
                @endif
                @if($dup->telepon)
                    · {{ $dup->telepon }}
                @endif
            </div>
        </div>
        <a href="{{ route('staff.patients.show', $dup) }}"
           class="btn btn-sm btn-warning fw-semibold">
            <i class="bi bi-eye me-1"></i>Lihat Data
        </a>
    </div>
</div>
@endif


{{-- Konfirmasi nama serupa --}}
@if(session('pasien_serupa') && session('pasien_serupa')->count() > 0)
<div class="card border-0 shadow-sm mb-4"
     style="border-radius:12px;border-left:4px solid var(--kuning) !important;">
    <div class="card-body p-3">
        <div class="fw-bold mb-1" style="color:var(--orange);">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            Ditemukan {{ session('pasien_serupa')->count() }} pasien dengan nama serupa!
        </div>
        <p class="text-muted mb-3" style="font-size:13px;">
            Cek daftar berikut — apakah pasien yang ingin Anda daftarkan sudah ada?
        </p>

        {{-- Daftar pasien serupa --}}
        <div class="list-group mb-3">
            @foreach(session('pasien_serupa') as $serupa)
            <a href="{{ route('staff.patients.show', $serupa) }}"
               target="_blank"
               class="list-group-item list-group-item-action py-2">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:36px;height:36px;border-radius:50%;
                                background:#e3f2fd;display:flex;align-items:center;
                                justify-content:center;font-weight:700;font-size:14px;
                                color:var(--biru-tua);flex-shrink:0;">
                        {{ strtoupper(substr($serupa->nama, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:13px;">
                            {{ $serupa->nama }}
                        </div>
                        <div style="font-size:11px;color:#888;">
                            <code style="background:#e8f5e9;padding:1px 6px;
                                         border-radius:3px;color:var(--hijau);">
                                {{ $serupa->no_rm }}
                            </code>
                            @if($serupa->tanggal_lahir)
                                · {{ $serupa->tanggal_lahir->format('d M Y') }}
                                ({{ (int)$serupa->tanggal_lahir->diffInYears(now()) }} tahun)
                            @endif
                            @if($serupa->telepon)
                                · {{ $serupa->telepon }}
                            @endif
                            @if($serupa->alamat)
                                · {{ Str::limit($serupa->alamat, 30) }}
                            @endif
                        </div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary"
                          style="font-size:10px;">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Lihat
                    </span>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pilihan konfirmasi --}}
        <div class="d-flex gap-2">
            <button type="button"
                    class="btn btn-success fw-semibold flex-grow-1"
                    onclick="konfirmasiDaftarBaru()">
                <i class="bi bi-person-plus-fill me-2"></i>
                Bukan pasien di atas, daftarkan sebagai pasien baru
            </button>
            <a href="{{ route('staff.patients.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Batal
            </a>
        </div>
    </div>
</div>
@endif

{{-- Form Pasien Baru --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-person-plus-fill me-2"></i>Form Pasien Baru
    </div>
    <div class="card-body p-4">
        <form action="{{ route('staff.patients.store') }}" method="POST">
            @csrf

            {{-- Flag konfirmasi (diisi via JavaScript) --}}
            <input type="hidden" name="sudah_konfirmasi" id="sudah_konfirmasi" value="0">

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text" name="nama"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama') }}" placeholder="Nama lengkap pasien"
                       autofocus>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           class="form-control @error('tanggal_lahir') is-invalid @enderror"
                           value="{{ old('tanggal_lahir') }}"
                           max="{{ date('Y-m-d') }}">
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected':'' }}>
                            Laki-laki
                        </option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected':'' }}>
                            Perempuan
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"
                          placeholder="Alamat lengkap (opsional)">{{ old('alamat') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">No. Telepon</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-telephone-fill"></i>
                    </span>
                    <input type="text" name="telepon" class="form-control"
                           value="{{ old('telepon') }}"
                           placeholder="08xxxxxxxxxx (opsional)">
                </div>
            </div>

            <div class="alert alert-info py-2" style="font-size:13px;">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>No. RM</strong> dan <strong>Kode Unik</strong> akan digenerate otomatis
                setelah form disimpan.
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>Daftarkan Pasien
                </button>
                <a href="{{ route('staff.patients.index') }}"
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

@push('scripts')
<script>
async function searchExisting() {
    const q = document.getElementById('search-existing').value.trim();
    if (q.length < 2) {
        document.getElementById('search-results').innerHTML =
            '<small class="text-muted">Ketik minimal 2 karakter.</small>';
        return;
    }

    const res  = await fetch(`{{ route('staff.patients.search') }}?q=` + encodeURIComponent(q));
    const data = await res.json();
    const box  = document.getElementById('search-results');

    if (data.length === 0) {
        box.innerHTML = '<small class="text-success"><i class="bi bi-check-circle me-1"></i>Pasien belum terdaftar. Silakan isi form di bawah.</small>';
        return;
    }

    let html = '<div class="list-group">';
    data.forEach(p => {
        html += `
        <a href="/staff/patients/${p.id}"
           class="list-group-item list-group-item-action py-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success">${p.no_rm}</span>
                <strong>${p.nama}</strong>
                <span class="text-muted ms-auto" style="font-size:12px;">
                    ${p.jenis_kelamin === 'L' ? 'Laki-laki' : p.jenis_kelamin === 'P' ? 'Perempuan' : '-'}
                    ${p.telepon ? ' · ' + p.telepon : ''}
                </span>
            </div>
        </a>`;
    });
    html += '</div>';
    box.innerHTML = html;
}

document.getElementById('search-existing').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); searchExisting(); }
});

function konfirmasiDaftarBaru() {
    // Set flag konfirmasi = 1 lalu submit form
    document.getElementById('sudah_konfirmasi').value = '1';
    document.querySelector('form[action="{{ route('staff.patients.store') }}"]').submit();
}
</script>
@endpush