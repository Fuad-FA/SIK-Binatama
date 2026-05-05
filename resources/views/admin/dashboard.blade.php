@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')

{{-- Stat Cards Baris 1 --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#e3f2fd;border-radius:10px;padding:10px;flex-shrink:0;">
                    <i class="bi bi-people-fill fs-4" style="color:var(--biru-muda);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:11px;">Total Pasien</div>
                    <div class="fw-bold fs-3 lh-1">{{ $stats['total_pasien'] }}</div>
                    <div style="font-size:11px;color:var(--hijau);">
                        +{{ $stats['pasien_bulan_ini'] }} bulan ini
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card green p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#e8f5e9;border-radius:10px;padding:10px;flex-shrink:0;">
                    <i class="bi bi-clipboard2-pulse-fill fs-4" style="color:var(--hijau);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:11px;">Pemeriksaan</div>
                    <div class="fw-bold fs-3 lh-1">{{ $stats['total_pemeriksaan'] }}</div>
                    <div style="font-size:11px;color:var(--hijau);">
                        +{{ $stats['periksa_bulan_ini'] }} bulan ini
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card yellow p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#fffde7;border-radius:10px;padding:10px;flex-shrink:0;">
                    <i class="bi bi-receipt fs-4" style="color:var(--orange);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:11px;">Transaksi</div>
                    <div class="fw-bold fs-3 lh-1">{{ $stats['total_transaksi'] }}</div>
                    <div style="font-size:11px;color:var(--orange);">
                        +{{ $stats['transaksi_bulan_ini'] }} bulan ini
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card orange p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div style="background:#fff3e0;border-radius:10px;padding:10px;flex-shrink:0;">
                    <i class="bi bi-cash-coin fs-4" style="color:var(--orange);"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:11px;">Pendapatan</div>
                    <div class="fw-bold lh-1" style="font-size:16px;">
                        Rp {{ number_format($stats['pendapatan_total'], 0, ',', '.') }}
                    </div>
                    <div style="font-size:11px;color:var(--orange);">
                        Rp {{ number_format($stats['pendapatan_bulan'], 0, ',', '.') }} bulan ini
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Grafik + Top Staf --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-header bg-white fw-bold py-3" style="border-radius:12px 12px 0 0;border-bottom:1px solid #f0f0f0;">
                <i class="bi bi-bar-chart-fill me-2" style="color:var(--biru-muda);"></i>
                Tren Pendapatan & Pasien (6 Bulan Terakhir)
            </div>
            <div class="card-body p-3">
                <canvas id="trendChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-header bg-white fw-bold py-3" style="border-radius:12px 12px 0 0;border-bottom:1px solid #f0f0f0;">
                <i class="bi bi-trophy-fill me-2" style="color:var(--kuning);"></i>
                Top Staf Bulan Ini
            </div>
            <div class="card-body p-0">
                @forelse($topStaf as $i => $staf)
                <div class="d-flex align-items-center gap-3 px-3 py-2 {{ $loop->last ? '' : 'border-bottom' }}">
                    <div style="width:28px;height:28px;border-radius:50%; background:{{ ['#FDD835','#e0e0e0','#CD7F32'][$i] ?? '#e3f2fd' }}; display:flex;align-items:center;justify-content:center; font-weight:800;font-size:13px;color:#555;flex-shrink:0;">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:13px;">{{ Str::limit($staf->name, 18) }}</div>
                        <div style="font-size:11px;color:#888;">{{ ucfirst($staf->role) }}</div>
                    </div>
                    <div class="text-end">
                        <div style="font-size:12px;font-weight:700;color:var(--orange);">{{ $staf->trx_count }} trx</div>
                        <div style="font-size:10px;color:#aaa;">{{ $staf->pasien_count }} pasien</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:13px;">
                    <i class="bi bi-person-x d-block mb-1 opacity-25 fs-3"></i> Belum ada aktivitas bulan ini
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Tabel Terbaru --}}
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between" style="border-radius:12px 12px 0 0;border-bottom:1px solid #f0f0f0;">
                <span><i class="bi bi-person-plus-fill me-2" style="color:var(--biru-muda);"></i>Pasien Terbaru</span>
                <a href="{{ route('staff.patients.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        @foreach($recentPatients as $p)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:32px;height:32px;border-radius:50%; background:#e3f2fd;display:flex;align-items:center; justify-content:center;font-weight:700;font-size:12px; color:var(--biru-tua);flex-shrink:0;">
                                        {{ strtoupper(substr($p->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="font-size:13px;">{{ $p->nama }}</div>
                                        <code style="font-size:10px;background:#e8f5e9; padding:1px 6px;border-radius:3px;color:var(--hijau);">{{ $p->no_rm }}</code>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:11px;color:#aaa;">{{ $p->created_at->diffForHumans() }}</td>
                            <td style="font-size:11px;color:#888;">{{ $p->creator?->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between" style="border-radius:12px 12px 0 0;border-bottom:1px solid #f0f0f0;">
                <span><i class="bi bi-receipt me-2" style="color:var(--orange);"></i>Transaksi Terbaru</span>
                <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">Lihat Laporan</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        @foreach($recentTransactions as $trx)
                        <tr>
                            <td class="ps-3">
                                <code style="font-size:11px;background:#f0f4f8; padding:2px 6px;border-radius:3px;">{{ $trx->no_transaksi }}</code>
                                <div style="font-size:12px;color:#888;margin-top:1px;">{{ $trx->patient->nama }}</div>
                            </td>
                            <td class="fw-bold" style="color:var(--hijau);font-size:13px;">{{ $trx->totalFormatted() }}</td>
                            <td style="font-size:11px;color:#aaa;">{{ $trx->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartData = @json($chartData);
const ctx = document.getElementById('trendChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.map(d => d.label),
        datasets: [
            {
                label: 'Pendapatan (Rp)',
                data: chartData.map(d => d.total),
                backgroundColor: 'rgba(30,136,229,0.7)',
                borderColor: '#1565C0',
                borderWidth: 1,
                borderRadius: 4,
                yAxisID: 'y',
            },
            {
                label: 'Pasien Baru',
                data: chartData.map(d => d.pasien),
                type: 'line',
                borderColor: '#2E7D32',
                backgroundColor: 'rgba(46,125,50,0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#2E7D32',
                pointRadius: 4,
                fill: true,
                tension: 0.3,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { font: { size: 11 } } },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        if (ctx.datasetIndex === 0) {
                            return ' Rp ' + parseInt(ctx.raw).toLocaleString('id-ID');
                        }
                        return ' ' + ctx.raw + ' pasien';
                    }
                }
            }
        },
        scales: {
            y: {
                type: 'linear', position: 'left',
                ticks: {
                    callback: v => 'Rp ' + (v/1000).toFixed(0) + 'K',
                    font: { size: 10 }
                },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            y1: {
                type: 'linear', position: 'right',
                ticks: { font: { size: 10 } },
                grid: { drawOnChartArea: false }
            },
            x: { ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush