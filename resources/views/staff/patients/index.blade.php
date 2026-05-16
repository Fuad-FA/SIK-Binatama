@extends('layouts.app')
@section('title', 'Data Pasien')
@section('page-title', 'Data Pasien')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Daftar Pasien</h5>
        <p class="text-muted mb-0" style="font-size:13px;">
            Total {{ $patients->total() }} pasien yang Anda input
        </p>
    </div>
    <a href="{{ route('staff.patients.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-2"></i>Pasien Baru
    </a>
</div>

{{-- Search --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('staff.patients.index') }}"
              class="d-flex gap-2">
            <div class="input-group flex-grow-1">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0"
                       placeholder="Cari nama pasien, No. RM, telepon..."
                       value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary px-4">Cari</button>
            @if(request('search'))
                <a href="{{ route('staff.patients.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#888;font-weight:600;">PASIEN</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">NO. RM</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">JENIS KELAMIN</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">TELEPON</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">PEMERIKSAAN</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:50%;
                                        background:#e3f2fd;display:flex;align-items:center;
                                        justify-content:center;font-weight:700;font-size:13px;
                                        color:var(--biru-tua);">
                                {{ strtoupper(substr($patient->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:14px;">
                                    {{ $patient->nama }}
                                </div>
                                <div class="text-muted" style="font-size:11px;">
                                    {{ $patient->tanggal_lahir
                                        ? (int) $patient->tanggal_lahir->diffInYears(now()) . ' tahun'
                                        : 'Usia tidak diketahui' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <code style="background:#e8f5e9;padding:3px 8px;border-radius:4px;
                                     font-size:12px;color:var(--hijau);">
                            {{ $patient->no_rm }}
                        </code>
                    </td>
                    <td style="font-size:13px;">
                        @if($patient->jenis_kelamin === 'L')
                            <span class="badge bg-info-subtle text-info">
                                <i class="bi bi-gender-male me-1"></i>Laki-laki
                            </span>
                        @elseif($patient->jenis_kelamin === 'P')
                            <span class="badge bg-pink-subtle" style="background:#fce4ec;color:#c2185b;">
                                <i class="bi bi-gender-female me-1"></i>Perempuan
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td style="font-size:13px;">{{ $patient->telepon ?? '-' }}</td>
                    <td>
                        <span class="badge px-2 py-1"
                              style="background:#e3f2fd;color:var(--biru-tua);">
                            {{ $patient->medicalRecords->count() }} kali
                        </span>
                    </td>
                    {{-- <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('staff.patients.show', $patient) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            <a href="{{ route('staff.medical-records.create') }}?patient_id={{ $patient->id }}"
                               class="btn btn-sm btn-outline-success"
                               title="Input Pemeriksaan Baru">
                                <i class="bi bi-clipboard2-pulse"></i>
                            </a>
                        </div>
                    </td> --}}

{{-- <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('staff.patients.show', $patient) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            <a href="{{ route('staff.medical-records.create') }}?patient_id={{ $patient->id }}"
                               class="btn btn-sm btn-outline-success"
                               title="Input Pemeriksaan Baru">
                                <i class="bi bi-clipboard2-pulse"></i>
                            </a> --}}
                            {{-- Hapus hanya untuk admin --}}
                            {{-- @if(auth()->user()->role === 'admin')
                            <form action="{{ route('staff.patients.destroy', $patient) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Hapus Pasien"
                                        onclick="return confirm('Hapus pasien {{ $patient->nama }}?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td> --}}

<td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('staff.patients.show', $patient) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            <a href="{{ route('staff.transactions.create') }}?patient_id={{ $patient->id }}"
                               class="btn btn-sm btn-success"
                               title="Transaksi & Pemeriksaan">
                                <i class="bi bi-cart-plus-fill"></i>
                            </a>
                            {{-- Hapus hanya untuk admin --}}
                            @if(auth()->user()->role === 'admin')
                            <form action="{{ route('staff.patients.destroy', $patient) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Hapus Pasien"
                                        onclick="return confirm('Hapus pasien {{ $patient->nama }}?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>


                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                        Belum ada pasien. <a href="{{ route('staff.patients.create') }}">Daftarkan pasien baru</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $patients->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@endsection