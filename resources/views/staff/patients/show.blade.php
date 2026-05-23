@extends('layouts.app')
@section('title', 'Detail Pasien')
@section('page-title', 'Detail Pasien')

@section('content')


@php
    use Illuminate\Support\Str;
@endphp

{{-- Header Pasien --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-auto">
                <div style="width:72px;height:72px;border-radius:50%;
                            background:var(--biru-tua);display:flex;align-items:center;
                            justify-content:center;font-size:28px;font-weight:700;color:#fff;">
                    {{ strtoupper(substr($patient->nama, 0, 1)) }}
                </div>
            </div>
            <div class="col">
                <h4 class="fw-bold mb-1">{{ $patient->nama }}</h4>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <code style="background:#e8f5e9;padding:4px 12px;border-radius:6px;
                                 font-size:14px;color:var(--hijau);font-weight:700;">
                        {{ $patient->no_rm }}
                    </code>
                    @if($patient->jenis_kelamin === 'L')
                        <span class="badge bg-info-subtle text-info">Laki-laki</span>
                    @elseif($patient->jenis_kelamin === 'P')
                        <span class="badge" style="background:#fce4ec;color:#c2185b;">Perempuan</span>
                    @endif
                    @if($patient->tanggal_lahir)
                        <span class="text-muted" style="font-size:13px;">
                            <i class="bi bi-cake2 me-1"></i>
                            {{ $patient->tanggal_lahir->format('d M Y') }}
                            ({{ $patient->tanggal_lahir->diffInYears(now()) }} tahun)
                        </span>
                    @endif
                    @if($patient->telepon)
                        <span class="text-muted" style="font-size:13px;">
                            <i class="bi bi-telephone me-1"></i>{{ $patient->telepon }}
                        </span>
                    @endif
                </div>
                @if($patient->alamat)
                    <div class="text-muted mt-1" style="font-size:13px;">
                        <i class="bi bi-geo-alt me-1"></i>{{ $patient->alamat }}
                    </div>
                @endif
            </div>
            <div class="col-auto">
                {{-- Kode unik untuk pasien --}}
                <div class="text-center p-3 rounded"
                     style="background:#fff8e1;border:1px dashed var(--kuning);">
                    <div style="font-size:11px;color:#888;font-weight:600;">KODE AKSES PASIEN</div>
                    <div style="font-size:20px;font-weight:800;letter-spacing:3px;color:var(--orange);">
                        {{ $patient->kode_unik }}
                    </div>
                    <div style="font-size:10px;color:#aaa;">Gunakan + No. RM untuk login</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tombol Aksi --}}
{{-- <div class="d-flex gap-2 mb-4">
    <a href="{{ route('staff.medical-records.create') }}?patient_id={{ $patient->id }}"
       class="btn btn-success">
        <i class="bi bi-clipboard2-pulse-fill me-2"></i>Input Pemeriksaan Baru
    </a>
    <a href="{{ route('staff.patients.edit', $patient) }}"
       class="btn btn-outline-primary">
        <i class="bi bi-pencil me-2"></i>Edit Data Pasien
    </a>
    <a href="{{ route('staff.patients.index') }}"
       class="btn btn-outline-secondary ms-auto">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div> --}}

{{-- Tombol Aksi --}}
{{-- <div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('staff.medical-records.create') }}?patient_id={{ $patient->id }}"
       class="btn btn-success">
        <i class="bi bi-clipboard2-pulse-fill me-2"></i>Input Pemeriksaan Baru
    </a>
    <a href="{{ route('staff.transactions.create') }}?patient_id={{ $patient->id }}"
       class="btn btn-warning fw-semibold">
        <i class="bi bi-cart-plus-fill me-2"></i>Buat Transaksi
    </a>
    <a href="{{ route('staff.patients.edit', $patient) }}"
       class="btn btn-outline-primary">
        <i class="bi bi-pencil me-2"></i>Edit Data
    </a> --}}

    {{-- Tombol Aksi --}}
{{-- <div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('staff.transactions.create') }}?patient_id={{ $patient->id }}"
       class="btn btn-success fw-semibold">
        <i class="bi bi-cart-plus-fill me-2"></i>Transaksi & Pemeriksaan
    </a>
    <a href="{{ route('staff.patients.edit', $patient) }}"
       class="btn btn-outline-primary">
        <i class="bi bi-pencil me-2"></i>Edit Data
    </a> --}}


    {{-- Tombol Aksi --}}
{{-- <div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('staff.transactions.create') }}?patient_id={{ $patient->id }}"
       class="btn btn-success fw-semibold">
        <i class="bi bi-cart-plus-fill me-2"></i>Transaksi & Pemeriksaan
    </a>
    <a href="{{ route('staff.patients.edit', $patient) }}"
       class="btn btn-outline-primary">
        <i class="bi bi-pencil me-2"></i>Edit Data
    </a>
    @if(auth()->user()->role === 'admin')
    <form action="{{ route('staff.patients.destroy', $patient) }}"
          method="POST" class="d-inline ms-auto">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger"
                onclick="return confirm('Hapus data pasien {{ $patient->nama }}?\n\nTindakan ini tidak bisa dibatalkan!')">
            <i class="bi bi-trash me-2"></i>Hapus Pasien
        </button>
    </form>
    @else
    <a href="{{ route('staff.patients.index') }}"
       class="btn btn-outline-secondary ms-auto">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
    @endif
</div>
    {{-- Tombol Hapus — hanya admin --}}
    {{-- @if(auth()->user()->role === 'admin')
    <form action="{{ route('staff.patients.destroy', $patient) }}"
          method="POST" class="d-inline ms-auto">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger"
                onclick="return confirm('Hapus data pasien {{ $patient->nama }}?\n\nTindakan ini tidak bisa dibatalkan!')">
            <i class="bi bi-trash me-2"></i>Hapus Pasien
        </button>
    </form>
    @else
    <a href="{{ route('staff.patients.index') }}"
       class="btn btn-outline-secondary ms-auto">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
    @endif
</div> --}}

    {{-- Tombol Hapus — hanya admin --}}
    {{-- @if(auth()->user()->role === 'admin')
    <form action="{{ route('staff.patients.destroy', $patient) }}"
          method="POST" class="d-inline ms-auto">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger"
                onclick="return confirm('Hapus data pasien {{ $patient->nama }}?\n\nTindakan ini tidak bisa dibatalkan!')">
            <i class="bi bi-trash me-2"></i>Hapus Pasien
        </button>
    </form>
    @else
    <a href="{{ route('staff.patients.index') }}"
       class="btn btn-outline-secondary ms-auto">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
    @endif
</div> --}}

{{-- Tombol Aksi --}}
{{-- <div class="d-flex gap-2 mb-4 flex-wrap align-items-center">

    <a href="{{ route('staff.transactions.create') }}?patient_id={{ $patient->id }}"
       class="btn btn-success fw-semibold">
        <i class="bi bi-cart-plus-fill me-2"></i>
        Transaksi & Pemeriksaan
    </a>

    <a href="{{ route('staff.patients.edit', $patient) }}"
       class="btn btn-outline-primary">
        <i class="bi bi-pencil me-2"></i>
        Edit Data
    </a> --}}

    {{-- Admin --}}
    {{-- @if(auth()->user()->role === 'admin')

    <form action="{{ route('staff.patients.destroy', $patient) }}"
          method="POST"
          class="ms-auto">
        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn btn-outline-danger"
                onclick="return confirm('Hapus data pasien {{ $patient->nama }}?\n\nTindakan ini tidak bisa dibatalkan!')">
            <i class="bi bi-trash me-2"></i>
            Hapus Pasien
        </button>
    </form>

    @else

    <a href="{{ route('staff.patients.index') }}"
       class="btn btn-outline-secondary ms-auto">
        <i class="bi bi-arrow-left me-2"></i>
        Kembali
    </a>

    @endif

</div> --}}


{{-- Tombol Aksi --}}
<div class="d-flex gap-2 mb-4 flex-wrap">

    {{-- Transaksi & Pemeriksaan — hanya guru & siswa --}}
    @if(auth()->user()->role !== 'admin')
    <a href="{{ route('staff.transactions.create') }}?patient_id={{ $patient->id }}"
       class="btn btn-success fw-semibold">
        <i class="bi bi-cart-plus-fill me-2"></i>Transaksi & Pemeriksaan
    </a>
    @endif

    <a href="{{ route('staff.patients.edit', $patient) }}"
       class="btn btn-outline-primary">
        <i class="bi bi-pencil me-2"></i>Edit Data
    </a>

    {{-- Hapus — hanya admin --}}
    @if(auth()->user()->role === 'admin')
    <form action="{{ route('staff.patients.destroy', $patient) }}"
          method="POST" class="d-inline ms-auto">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger"
                onclick="return confirm('Hapus data pasien {{ $patient->nama }}?\n\nTindakan ini tidak bisa dibatalkan!')">
            <i class="bi bi-trash me-2"></i>Hapus Pasien
        </button>
    </form>
    @else
    <a href="{{ route('staff.patients.index') }}"
       class="btn btn-outline-secondary ms-auto">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
    @endif

</div>

{{-- Riwayat Pemeriksaan --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-header py-3 fw-bold"
         style="background:#f8f9fa;border-radius:12px 12px 0 0;">
        <i class="bi bi-clipboard2-pulse-fill me-2" style="color:var(--hijau);"></i>
        Riwayat Pemeriksaan
        <span class="badge ms-2"
              style="background:#e8f5e9;color:var(--hijau);">
            {{ $patient->medicalRecords->count() }} kali
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#888;font-weight:600;">TANGGAL</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">GULA DARAH</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">KOLESTEROL</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">ASAM URAT</th>
                    {{-- <th style="font-size:12px;color:#888;font-weight:600;">TENSI</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">PETUGAS</th> --}}
                    <th style="font-size:12px;color:#888;font-weight:600;">TENSI</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">BMI</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">CATATAN GIZI</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">PETUGAS</th>
                </tr>
            </thead>
            <tbody>
                {{-- @forelse($patient->medicalRecords->sortByDesc('tanggal_periksa') as $rec) --}}
                @forelse($patient->medicalRecords as $rec)
                <tr>
                    <td class="ps-4 fw-semibold" style="font-size:13px;">
                        {{ $rec->tanggal_periksa->format('d M Y') }}
                    </td>
                    {{-- Gula Darah --}}
                    <td>
                        @if($rec->gula_darah)
                            <span class="fw-semibold {{ $rec->gula_darah >= 200 ? 'text-danger' : 'text-success' }}">
                                {{ $rec->gula_darah }} mg/dl
                            </span>
                            @if($rec->gula_darah >= 200)
                                <i class="bi bi-exclamation-triangle-fill text-danger ms-1"
                                   style="font-size:11px;"></i>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    {{-- Kolesterol --}}
                    <td>
                        @if($rec->kolesterol)
                            @php $ok = $rec->kolesterol >= 160 && $rec->kolesterol <= 200; @endphp
                            <span class="fw-semibold {{ $ok ? 'text-success' : 'text-danger' }}">
                                {{ $rec->kolesterol }} mg/dl
                            </span>
                            @if(!$ok)
                                <i class="bi bi-exclamation-triangle-fill text-danger ms-1"
                                   style="font-size:11px;"></i>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    {{-- Asam Urat --}}
                    <td>
                        @if($rec->asam_urat)
                            <span class="fw-semibold
                                {{ $rec->statusAsamUrat() === 'Normal' ? 'text-success' : 'text-danger' }}">
                                {{ $rec->asam_urat }} mg/dl
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    {{-- Tensi --}}
                    {{-- <td>
                        @if($rec->tensi_sistolik)
                            <span class="fw-semibold
                                {{ $rec->statusTensi() === 'Normal' ? 'text-success' :
                                   ($rec->statusTensi() === 'Prehipertensi' ? 'text-warning' : 'text-danger') }}">
                                {{ $rec->tensi_sistolik }}/{{ $rec->tensi_diastolik }} mmHg
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#888;">
                        {{ $rec->user?->name ?? '-' }}
                    </td> --}}


                    {{-- Tensi --}}
<td>
    @if($rec->tensi_sistolik)
        <span class="fw-semibold
            {{ $rec->statusTensi() === 'Normal' ? 'text-success' :
               ($rec->statusTensi() === 'Prehipertensi' ? 'text-warning' : 'text-danger') }}">
            {{ $rec->tensi_sistolik }}/{{ $rec->tensi_diastolik }} mmHg
        </span>
    @else
        <span class="text-muted">-</span>
    @endif
</td>

{{-- BMI --}}
<td>
    @if($rec->bmi)
        <span style="color:{{ $rec->warnaBmi() }};font-weight:600;">
            {{ $rec->bmi }}
        </span>

        <small class="text-muted d-block" style="font-size:10px;">
            {{ $rec->kategoriBmi() }}
        </small>
    @else
        <span class="text-muted">-</span>
    @endif
</td>

{{-- Catatan Gizi --}}
<td>
    @if($rec->catatan_gizi)
        <span style="font-size:12px;">
            {{ Str::limit($rec->catatan_gizi, 50) }}
        </span>
    @else
        <span class="text-muted">-</span>
    @endif
</td>

<td style="font-size:12px;color:#888;">
    {{ $rec->user?->name ?? '-' }}
</td>
                </tr>
                @empty
                <tr>
                    {{-- <td colspan="6" class="text-center py-4 text-muted"> --}}
                        <td colspan="8" class="text-center py-4 text-muted">
                        <i class="bi bi-clipboard2 d-block mb-1 opacity-25 fs-3"></i>
                        Belum ada riwayat pemeriksaan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection