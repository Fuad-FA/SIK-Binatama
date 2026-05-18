@extends('layouts.app')
@section('title', 'Laporan Bulanan')
@section('page-title', 'Laporan Bulanan')

@section('content')

{{-- Filter Bulan & Tahun --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Bulan</label>
                <select name="bulan" class="form-select">
                    @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ $bulan == $num ? 'selected':'' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold" style="font-size:13px;">Tahun</label>
                <select name="tahun" class="form-select">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected':'' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel-fill me-1"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

@php $namaBulan = $bulanList[$bulan] . ' ' . $tahun; @endphp

<h6 class="fw-bold mb-3" style="color:var(--biru-tua);">
    <i class="bi bi-calendar-month me-2"></i>Rekap: {{ $namaBulan }}
</h6>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3 text-center">
            <div style="font-size:32px;font-weight:800;color:var(--biru-muda);">{{ $rekap['total_pasien_baru'] }}</div>
            <div class="text-muted" style="font-size:12px;">Pasien Baru</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card green p-3 text-center">
            <div style="font-size:32px;font-weight:800;color:var(--hijau);">{{ $rekap['total_pemeriksaan'] }}</div>
            <div class="text-muted" style="font-size:12px;">Pemeriksaan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card yellow p-3 text-center">
            <div style="font-size:32px;font-weight:800;color:var(--orange);">{{ $rekap['total_transaksi'] }}</div>
            <div class="text-muted" style="font-size:12px;">Transaksi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card orange p-3 text-center">
            <div style="font-size:20px;font-weight:800;color:var(--orange);">Rp {{ number_format($rekap['total_pendapatan'], 0, ',', '.') }}</div>
            <div class="text-muted" style="font-size:12px;">Total Pendapatan</div>
        </div>
    </div>
</div>



{{-- Tombol Export --}}
<div class="d-flex gap-2 mt-3">
    <a href="{{ route('admin.reports.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}"
       class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
    </a>
    <a href="{{ route('admin.reports.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
       class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
    </a>
</div>

{{-- Performa Staf --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-header fw-bold py-3" style="background:#f8f9fa;border-radius:12px 12px 0 0;">
        <i class="bi bi-trophy-fill me-2" style="color:var(--kuning);"></i> Performa Staf — {{ $namaBulan }}
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            {{-- <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#888;font-weight:600;">#</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">NAMA STAF</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">ROLE</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">PASIEN DIINPUT</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">TRANSAKSI</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">AKTIVITAS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stafPerforma as $i => $staf)
                <tr>
                    <td class="ps-4">
                        <div style="width:28px;height:28px;border-radius:50%; background:{{ ['#FDD835','#e0e0e0','#f4c47e'][$i] ?? '#e3f2fd' }}; display:flex;align-items:center;justify-content:center; font-weight:800;font-size:12px;color:#555;">{{ $i + 1 }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:14px;">{{ $staf->name }}</div>
                        <div style="font-size:11px;color:#888;">{{ $staf->jabatan ?? '-' }}</div>
                    </td>
                    <td><span class="badge {{ $staf->role === 'guru' ? 'bg-success' : 'bg-primary' }}">{{ ucfirst($staf->role) }}</span></td>
                    <td><span class="fw-bold" style="color:var(--biru-muda);">{{ $staf->pasien_count }}</span><span class="text-muted" style="font-size:12px;"> pasien</span></td>
                    <td><span class="fw-bold" style="color:var(--orange);">{{ $staf->trx_count }}</span><span class="text-muted" style="font-size:12px;"> transaksi</span></td>
                    <td>
                        @php
                            $total = $staf->pasien_count + $staf->trx_count;
                            $maxVal = $stafPerforma->max(fn($s) => $s->pasien_count + $s->trx_count);
                            $pct = $maxVal > 0 ? ($total / $maxVal * 100) : 0;
                        @endphp
                        <div style="background:#f0f0f0;border-radius:4px;height:8px;width:120px;">
                            <div style="background:var(--biru-muda);border-radius:4px; height:8px;width:{{ $pct }}%;"></div>
                        </div>
                        <div style="font-size:10px;color:#aaa;margin-top:2px;">{{ $total }} aktivitas</div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada aktivitas staf.</td></tr>
                @endforelse --}}
                <thead style="background:#f8f9fa;">
    <tr>
        <th class="ps-4" style="font-size:12px;color:#888;font-weight:600;">#</th>
        <th style="font-size:12px;color:#888;font-weight:600;">NAMA STAF</th>
        <th style="font-size:12px;color:#888;font-weight:600;">ROLE</th>
        <th style="font-size:12px;color:#888;font-weight:600;">PASIEN BARU</th>
        <th style="font-size:12px;color:#888;font-weight:600;">REKAM MEDIS</th>
        <th style="font-size:12px;color:#888;font-weight:600;">TRANSAKSI</th>
        <th style="font-size:12px;color:#888;font-weight:600;">AKTIVITAS</th>
    </tr>
</thead>
<tbody>
    @forelse($stafPerforma as $i => $staf)
    <tr>
        <td class="ps-4">
            <div style="width:28px;height:28px;border-radius:50%;
                        background:{{ ['#FDD835','#e0e0e0','#f4c47e'][$i] ?? '#e3f2fd' }};
                        display:flex;align-items:center;justify-content:center;
                        font-weight:800;font-size:12px;color:#555;">
                {{ $i + 1 }}
            </div>
        </td>
        <td>
            <div class="fw-semibold" style="font-size:14px;">{{ $staf->name }}</div>
            <div style="font-size:11px;color:#888;">{{ $staf->jabatan ?? '-' }}</div>
        </td>
        <td>
            <span class="badge {{ $staf->role === 'guru' ? 'bg-success' : 'bg-primary' }}">
                {{ ucfirst($staf->role) }}
            </span>
        </td>
        <td>
            <span class="fw-bold" style="color:var(--biru-muda);">
                {{ $staf->pasien_count }}
            </span>
            <span class="text-muted" style="font-size:12px;"> pasien</span>
        </td>
        <td>
            <span class="fw-bold" style="color:var(--hijau);">
                {{ $staf->rekam_count }}
            </span>
            <span class="text-muted" style="font-size:12px;"> rekam</span>
        </td>
        <td>
            <span class="fw-bold" style="color:var(--orange);">
                {{ $staf->trx_count }}
            </span>
            <span class="text-muted" style="font-size:12px;"> transaksi</span>
        </td>
        <td>
            @php
                $total  = $staf->pasien_count + $staf->rekam_count + $staf->trx_count;
                $maxVal = $stafPerforma->max(fn($s) => $s->pasien_count + $s->rekam_count + $s->trx_count);
                $pct    = $maxVal > 0 ? ($total / $maxVal * 100) : 0;
            @endphp
            <div style="background:#f0f0f0;border-radius:4px;height:8px;width:100px;">
                <div style="background:var(--biru-muda);border-radius:4px;
                            height:8px;width:{{ $pct }}%;"></div>
            </div>
            <div style="font-size:10px;color:#aaa;margin-top:2px;">
                {{ $total }} aktivitas
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="text-center py-4 text-muted">
            <i class="bi bi-person-x d-block fs-3 mb-1 opacity-25"></i>
            Tidak ada aktivitas staf pada bulan ini.
        </td>
    </tr>
    @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Data Harian --}}
@if($harian->count() > 0)
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold py-3" style="background:#f8f9fa;border-radius:12px 12px 0 0;">
        <i class="bi bi-calendar-week me-2" style="color:var(--hijau);"></i> Ringkasan Harian — {{ $namaBulan }}
    </div>
    <div class="card-body p-3">
        <canvas id="harianChart" height="80"></canvas>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#888;">TANGGAL</th>
                    <th style="font-size:12px;color:#888;">TRANSAKSI</th>
                    <th style="font-size:12px;color:#888;">PENDAPATAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($harian as $h)
                <tr>
                    <td class="ps-4" style="font-size:13px;">{{ \Carbon\Carbon::parse($h->tanggal)->format('d M Y') }}</td>
                    <td><span class="badge" style="background:#e3f2fd;color:var(--biru-tua);">{{ $h->jumlah }} transaksi</span></td>
                    <td class="fw-semibold" style="color:var(--hijau);">Rp {{ number_format($h->pendapatan, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@if($harian->count() > 0)
const harianData = @json($harian);
const ctx2 = document.getElementById('harianChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: harianData.map(d => {
            const dt = new Date(d.tanggal);
            return dt.getDate() + '/' + (dt.getMonth()+1);
        }),
        datasets: [{
            label: 'Pendapatan',
            data: harianData.map(d => d.pendapatan),
            backgroundColor: 'rgba(46,125,50,0.7)',
            borderColor: '#1B5E20',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => 'Rp ' + parseInt(ctx.raw).toLocaleString('id-ID') } }
        },
        scales: {
            y: {
                ticks: { callback: v => 'Rp ' + (v/1000).toFixed(0) + 'K', font: { size: 10 } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: { ticks: { font: { size: 10 } } }
        }
    }
});
@endif
</script>
@endpush