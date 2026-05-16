@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')

<div class="row justify-content-center">
<div class="col-lg-8">

{{-- Header --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="text-muted mb-1" style="font-size:12px;">NO. TRANSAKSI</div>
                <h4 class="fw-bold mb-1">{{ $transaction->no_transaksi }}</h4>
                <div style="font-size:13px;color:#888;">
                    {{ $transaction->created_at->format('d M Y, H:i') }} WIB
                    · Petugas: {{ $transaction->user?->name }}
                </div>
            </div>
            <span class="badge bg-success px-3 py-2 fs-6">
                <i class="bi bi-check-circle me-1"></i>Selesai
            </span>
        </div>
    </div>
</div>

{{-- Pasien --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-header fw-semibold py-2"
         style="background:#f8f9fa;border-radius:12px 12px 0 0;">
        <i class="bi bi-person-fill me-2" style="color:var(--biru-muda);"></i>Data Pasien
    </div>
    <div class="card-body p-3">
        <div class="d-flex align-items-center gap-3">
            <div style="width:48px;height:48px;border-radius:50%;background:var(--biru-tua);
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-weight:700;font-size:20px;">
                {{ strtoupper(substr($transaction->patient->nama, 0, 1)) }}
            </div>
            <div>
                <div class="fw-bold fs-6">{{ $transaction->patient->nama }}</div>
                <div style="font-size:12px;color:#888;">
                    <code style="background:#e8f5e9;padding:2px 8px;border-radius:4px;
                                 color:var(--hijau);">
                        {{ $transaction->patient->no_rm }}
                    </code>
                    @if($transaction->patient->telepon)
                        · {{ $transaction->patient->telepon }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Item Transaksi --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-header fw-semibold py-2"
         style="background:#f8f9fa;border-radius:12px 12px 0 0;">
        <i class="bi bi-list-check me-2" style="color:var(--hijau);"></i>
        Detail Item ({{ $transaction->items->count() }} item)
    </div>
    <div class="table-responsive">
        <table class="table table-borderless mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#888;font-weight:600;">ITEM</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">TIPE</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">HARGA</th>
                    <th style="font-size:12px;color:#888;font-weight:600;">QTY</th>
                    <th class="text-end pe-4"
                        style="font-size:12px;color:#888;font-weight:600;">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                <tr>
                    <td class="ps-4 fw-semibold" style="font-size:14px;">
                        {{ $item->nama_item }}
                    </td>
                    <td>
                        @php
                            $tipeStyle = match($item->item_type) {
                                'service' => 'bg-success-subtle text-success',
                                'package' => 'bg-primary-subtle text-primary',
                                default   => 'bg-warning-subtle text-warning',
                            };
                            $tipeLabel = match($item->item_type) {
                                'service' => 'Layanan',
                                'package' => 'Paket',
                                default   => 'Produk',
                            };
                        @endphp
                        <span class="badge {{ $tipeStyle }}">{{ $tipeLabel }}</span>
                    </td>
                    <td style="font-size:13px;">
                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                    </td>
                    <td style="font-size:13px;">{{ $item->qty }}x</td>
                    <td class="text-end pe-4 fw-semibold" style="color:var(--orange);">
                        {{ $item->subtotalFormatted() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="border-top:2px solid #eee;">
                <tr>
                    <td colspan="4" class="text-end fw-semibold ps-4">Subtotal</td>
                    <td class="text-end pe-4 fw-semibold">
                        Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                @if($transaction->diskon > 0)
                <tr>
                    <td colspan="4" class="text-end" style="color:var(--orange);">
                        Diskon
                    </td>
                    <td class="text-end pe-4" style="color:var(--orange);">
                        - Rp {{ number_format($transaction->diskon, 0, ',', '.') }}
                    </td>
                </tr>
                @endif
                <tr style="background:#e8f5e9;">
                    <td colspan="4" class="text-end fw-bold fs-5 ps-4">TOTAL</td>
                    <td class="text-end pe-4 fw-bold fs-5"
                        style="color:var(--hijau);">
                        {{ $transaction->totalFormatted() }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Tombol Aksi --}}
<div class="d-flex gap- flex-wrap">
    {{-- <a href="{{ route('staff.transactions.nota', $transaction) }}"
       target="_blank" class="btn btn-success px-4 fw-semibold">
        <i class="bi bi-printer-fill me-2"></i>Cetak Nota PDF
    </a>
    <a href="{{ route('staff.transactions.index') }}"
       class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
    <a href="{{ route('staff.transactions.create') }}?patient_id={{ $transaction->patient_id }}"
       class="btn btn-primary px-4 ms-auto">
        <i class="bi bi-plus-circle me-2"></i>Transaksi Baru
    </a> --}}
    {{-- @php
        // Cek apakah transaksi ini perlu rekam medis
        $itemNamesShow = $transaction->items->pluck('nama_item')->toArray();
        $needsMedical  = false;
        $medKeywords   = ['gula','kolesterol','asam urat','tekanan','tensi','suhu','nadi',
                          'respirasi','bmi','antropometri','pijat','totok','infra red','senam','paket sehat'];
        foreach ($itemNamesShow as $n) {
            foreach ($medKeywords as $kw) {
                if (str_contains(strtolower($n), $kw)) {
                    $needsMedical = true;
                    break 2;
                }
            }
        }
    @endphp --}}

    @php
    $itemNamesShow = $transaction->items->pluck('nama_item')->toArray();

    // Whitelist EKSAK layanan yang memerlukan rekam medis
    $medicalServices = [
        'cek gula darah', 'cek kolesterol', 'cek asam urat',
        'cek tekanan darah', 'cek suhu', 'cek nadi', 'cek respirasi',
        'paket sehat 1', 'paket sehat 2', 'paket sehat 3',
        'paket sehat 4', 'paket sehat 5',
    ];

    $needsMedical = false;
    foreach ($itemNamesShow as $n) {
        $nl = strtolower(trim($n));
        foreach ($medicalServices as $ms) {
            if ($nl === $ms || str_contains($nl, $ms)) {
                $needsMedical = true;
                break 2;
            }
        }
    }
@endphp

    @if(!$needsMedical || $transaction->medical_record_id)
        {{-- Rekam medis sudah ada atau tidak diperlukan — bisa cetak --}}
        <a href="{{ route('staff.transactions.nota', $transaction) }}"
           target="_blank" class="btn btn-success px-4 fw-semibold">
            <i class="bi bi-printer-fill me-2"></i>Cetak Nota PDF
        </a>
    @else
        {{-- Rekam medis belum diisi — tombol menuju form rekam medis --}}
        @php
            $fieldsShow = [];
            $mappingShow = [
                'gula_darah'   => ['cek gula','gula darah'],
                'kolesterol'   => ['cek kolesterol','kolesterol'],
                'asam_urat'    => ['cek asam urat','asam urat'],
                'tensi'        => ['cek tekanan darah','tekanan darah','tensi'],
                'suhu'         => ['cek suhu','suhu tubuh'],
                'nadi'         => ['cek nadi','nadi'],
                'respirasi'    => ['cek respirasi','respirasi'],
                'antropometri' => ['cek bmi','antropometri','bmi'],
            ];
            $allVitalsShow = ['paket sehat','sehat 1','sehat 2','sehat 3','sehat 4','sehat 5','pijat','totok','infra red','senam'];
            foreach ($itemNamesShow as $n) {
                $nl = strtolower($n);
                foreach ($allVitalsShow as $vs) {
                    if (str_contains($nl, $vs)) {
                        $fieldsShow = ['gula_darah','kolesterol','asam_urat','tensi','suhu','nadi','respirasi'];
                        break 2;
                    }
                }
                foreach ($mappingShow as $field => $kws) {
                    foreach ($kws as $kw) {
                        if (str_contains($nl, $kw)) { $fieldsShow[] = $field; break; }
                    }
                }
            }
            $fieldsShow = array_unique($fieldsShow);
        @endphp
        <a href="{{ route('staff.medical-records.create', [
                        'patient_id'     => $transaction->patient_id,
                        'transaction_id' => $transaction->id,
                        'fields'         => implode(',', $fieldsShow),
                   ]) }}"
           class="btn btn-warning px-4 fw-semibold">
            <i class="bi bi-clipboard2-pulse-fill me-2"></i>
            Isi Hasil Pemeriksaan Dulu
        </a>
        <span class="text-muted ms-1" style="font-size:12px;">
            (nota bisa dicetak setelah pemeriksaan diisi)
        </span>
    @endif
</div>

</div>
</div>
@endsection