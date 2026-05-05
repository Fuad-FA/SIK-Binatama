@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')

{{-- Header & Tombol Tambah --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Daftar Staf</h5>
        <p class="text-muted mb-0" style="font-size:13px;">
            Total {{ $users->total() }} akun terdaftar
        </p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-2"></i> Tambah Staf
    </a>
</div>

{{-- Filter & Search --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.users.index') }}"
              class="row g-2 align-items-end">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0"
                           placeholder="Cari nama, username, jabatan..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="guru"  {{ request('role') === 'guru'  ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ request('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel User --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#888;font-weight:600;">NAMA</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">USERNAME</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">ROLE</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">JABATAN</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">STATUS</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">LOGIN TERAKHIR</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    {{-- Avatar + Nama --}}
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:50%;
                                        background:{{ $user->role === 'guru' ? '#e8f5e9' : '#e3f2fd' }};
                                        display:flex;align-items:center;justify-content:center;
                                        font-weight:700;font-size:13px;
                                        color:{{ $user->role === 'guru' ? '#2E7D32' : '#1565C0' }};">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:14px;">
                                    {{ $user->name }}
                                </div>
                                <div class="text-muted" style="font-size:11px;">
                                    {{ $user->email ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Username --}}
                    <td>
                        <code style="background:#f0f4f8;padding:2px 8px;
                                     border-radius:4px;font-size:12px;">
                            {{ $user->username }}
                        </code>
                    </td>

                    {{-- Role --}}
                    <td>
                        <span class="badge px-3 py-1
                            {{ $user->role === 'guru' ? 'bg-success' : 'bg-primary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>

                    {{-- Jabatan --}}
                    <td style="font-size:13px;">{{ $user->jabatan ?? '-' }}</td>

                    {{-- Status --}}
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success-subtle text-success px-2 py-1">
                                <i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                <i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>Nonaktif
                            </span>
                        @endif
                        @if($user->must_change_password)
                            <span class="badge bg-warning-subtle text-warning px-2 py-1 ms-1">
                                <i class="bi bi-key-fill me-1"></i>Reset
                            </span>
                        @endif
                    </td>

                    {{-- Last Login --}}
                    <td style="font-size:12px;color:#888;">
                        {{ $user->last_login ? $user->last_login->diffForHumans() : 'Belum pernah' }}
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex gap-1">
                            {{-- Lihat --}}
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            {{-- Toggle Aktif --}}
                            <form action="{{ route('admin.users.toggle-active', $user) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                        onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                                    <i class="bi bi-{{ $user->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                </button>
                            </form>
                            {{-- Reset Password --}}
                            <form action="{{ route('admin.users.reset-password', $user) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary"
                                        title="Reset Password"
                                        onclick="return confirm('Reset password {{ $user->name }} ke default?')">
                                    <i class="bi bi-key"></i>
                                </button>
                            </form>
                            {{-- Hapus --}}
                            <form action="{{ route('admin.users.destroy', $user) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        title="Hapus"
                                        onclick="return confirm('Hapus akun {{ $user->name }}? Tindakan ini tidak bisa dibatalkan!')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                        Belum ada data staf.
                        <a href="{{ route('admin.users.create') }}">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $users->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection