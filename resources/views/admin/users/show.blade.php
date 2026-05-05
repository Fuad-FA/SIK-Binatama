@extends('layouts.app')

@section('title', 'Detail Staf')
@section('page-title', 'Detail Akun Staf')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3 d-flex justify-content-between align-items-center"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <span><i class="bi bi-person-badge-fill me-2"></i> Detail Akun</span>
        <a href="{{ route('admin.users.edit', $user) }}"
           class="btn btn-sm btn-warning fw-semibold">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
    </div>

    <div class="card-body p-4">
        {{-- Avatar besar --}}
        <div class="text-center mb-4">
            <div style="width:80px;height:80px;border-radius:50%;
                        background:{{ $user->role === 'guru' ? '#e8f5e9' : '#e3f2fd' }};
                        display:flex;align-items:center;justify-content:center;
                        font-size:32px;font-weight:700;margin:0 auto 12px;
                        color:{{ $user->role === 'guru' ? '#2E7D32' : '#1565C0' }};">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <span class="badge px-3 py-2
                {{ $user->role === 'guru' ? 'bg-success' : 'bg-primary' }} fs-6">
                {{ ucfirst($user->role) }}
            </span>
        </div>

        {{-- Info detail --}}
        <table class="table table-borderless" style="font-size:14px;">
            <tr>
                <td class="text-muted fw-semibold" style="width:40%;">Username</td>
                <td>
                    <code style="background:#f0f4f8;padding:3px 10px;border-radius:4px;">
                        {{ $user->username }}
                    </code>
                </td>
            </tr>
            <tr>
                <td class="text-muted fw-semibold">Email</td>
                <td>{{ $user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-muted fw-semibold">Jabatan / Kelas</td>
                <td>{{ $user->jabatan ?? '-' }}</td>
            </tr>
            @if($user->role === 'siswa')
            <tr>
                <td class="text-muted fw-semibold">Barcode QR</td>
                <td>
                    <code style="background:#fff3e0;padding:3px 10px;border-radius:4px;color:#E65100;">
                        {{ $user->barcode ?? '-' }}
                    </code>
                </td>
            </tr>
            @endif
            <tr>
                <td class="text-muted fw-semibold">Status Akun</td>
                <td>
                    @if($user->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Nonaktif</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="text-muted fw-semibold">Status Password</td>
                <td>
                    @if($user->must_change_password)
                        <span class="badge bg-warning text-dark">Harus Ganti Password</span>
                    @else
                        <span class="badge bg-success">Normal</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="text-muted fw-semibold">Login Terakhir</td>
                <td>{{ $user->last_login ? $user->last_login->format('d M Y, H:i') : 'Belum pernah login' }}</td>
            </tr>
            <tr>
                <td class="text-muted fw-semibold">Dibuat</td>
                <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
            </tr>
        </table>

        {{-- Aksi --}}
        <div class="d-flex gap-2 mt-3">
            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                @csrf
                <button type="submit"
                        class="btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                        onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                    <i class="bi bi-{{ $user->is_active ? 'pause-circle' : 'play-circle' }} me-1"></i>
                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-secondary"
                        onclick="return confirm('Reset password ke default?')">
                    <i class="bi bi-key me-1"></i> Reset Password
                </button>
            </form>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary ms-auto">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

</div>
</div>
@endsection