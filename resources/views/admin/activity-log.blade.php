@extends('layouts.app')
@section('title', 'Riwayat Aktivitas')
@section('page-title', 'Riwayat Aktivitas Sistem')

@section('content')

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold" style="font-size:12px;">Pengguna</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">Semua pengguna</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}"
                            {{ request('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ ucfirst($u->role) }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px;">Aksi</label>
                <select name="action" class="form-select form-select-sm">
                    <option value="">Semua aksi</option>
                    <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                    <option value="create_patient" {{ request('action') === 'create_patient' ? 'selected' : '' }}>Input Pasien</option>
                    <option value="create_medical" {{ request('action') === 'create_medical' ? 'selected' : '' }}>Input Rekam Medis</option>
                    <option value="create_transaction" {{ request('action') === 'create_transaction' ? 'selected' : '' }}>Transaksi</option>
                    <option value="unlock" {{ request('action') === 'unlock' ? 'selected' : '' }}>Buka Kunci Akun</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px;">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm"
                       value="{{ request('tanggal') }}"
                       max="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Log --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header py-3 fw-bold"
         style="background:#f8f9fa;border-radius:12px 12px 0 0;">
        <i class="bi bi-clock-history me-2" style="color:var(--biru-muda);"></i>
        Log Aktivitas
        <span class="badge ms-2" style="background:#e3f2fd;color:var(--biru-muda);">
            {{ $logs->total() }} entri
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:11px;color:#888;font-weight:600;">WAKTU</th>
                    <th style="font-size:11px;color:#888;font-weight:600;">PENGGUNA</th>
                    <th style="font-size:11px;color:#888;font-weight:600;">AKSI</th>
                    <th style="font-size:11px;color:#888;font-weight:600;">KETERANGAN</th>
                    <th style="font-size:11px;color:#888;font-weight:600;">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                @php
                    $badgeColor = match(true) {
                        str_contains($log->action, 'login')       => '#e8f5e9|#2E7D32',
                        str_contains($log->action, 'logout')      => '#f3e5f5|#7B1FA2',
                        str_contains($log->action, 'create')      => '#e3f2fd|#1565C0',
                        str_contains($log->action, 'delete')      => '#ffebee|#c62828',
                        str_contains($log->action, 'update')      => '#fff8e1|#F57C00',
                        str_contains($log->action, 'unlock')      => '#fff3e0|#E65100',
                        default                                    => '#f5f5f5|#555',
                    };
                    [$bg, $fg] = explode('|', $badgeColor);
                @endphp
                <tr>
                    <td class="ps-4" style="font-size:12px;white-space:nowrap;">
                        <div class="fw-semibold">{{ $log->created_at->format('d M Y') }}</div>
                        <div class="text-muted" style="font-size:11px;">
                            {{ $log->created_at->format('H:i:s') }}
                        </div>
                    </td>
                    <td>
                        @if($log->user)
                        <div class="fw-semibold" style="font-size:13px;">
                            {{ $log->user->name }}
                        </div>
                        <span class="badge {{ $log->user->role === 'admin' ? 'bg-warning text-dark' :
                                             ($log->user->role === 'guru' ? 'bg-success' : 'bg-primary') }}"
                              style="font-size:9px;">
                            {{ ucfirst($log->user->role) }}
                        </span>
                        @else
                        <span class="text-muted" style="font-size:12px;">Sistem</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge px-2 py-1"
                              style="background:{{ $bg }};color:{{ $fg }};font-size:10px;">
                            {{ str_replace('_', ' ', strtoupper($log->action)) }}
                        </span>
                    </td>
                    <td style="font-size:12px;max-width:300px;">
                        {{ $log->description ?? '-' }}
                    </td>
                    <td style="font-size:11px;color:#aaa;font-family:monospace;">
                        {{ $log->ip_address ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="bi bi-clock-history d-block fs-3 mb-1 opacity-25"></i>
                        Tidak ada aktivitas yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white py-3" style="border-radius:0 0 12px 12px;">
        {{ $logs->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection