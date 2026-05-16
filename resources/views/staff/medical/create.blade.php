@extends('layouts.app')
@section('title', 'Input Pemeriksaan')
@section('page-title', 'Input Hasil Pemeriksaan')

@section('content')

{{-- Alert cetak nota --}}
@if(session('print_nota'))
<div class="alert border-0 shadow-sm mb-4 d-flex align-items-center justify-content-between"
     style="border-radius:12px;background:#e8f5e9;border-left:4px solid var(--hijau) !important;">
    <div>
        <i class="bi bi-check-circle-fill me-2" style="color:var(--hijau);"></i>
        <strong>Transaksi berhasil!</strong> Cetak nota sebelum melanjutkan input pemeriksaan.
    </div>
    <a href="{{ session('print_nota') }}" target="_blank"
       class="btn btn-success btn-sm fw-semibold">
        <i class="bi bi-printer-fill me-2"></i>Cetak Nota PDF
    </a>
</div>
@endif

<div class="row justify-content-center">
<div class="col-lg-8">

{{-- Info transaksi terkait --}}
@if($transaction)
<div class="card border-0 shadow-sm mb-4"
     style="border-radius:12px;background:#e3f2fd;">
    <div class="card-body p-3">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-receipt fs-4" style="color:var(--biru-muda);"></i>
            <div>
                <div class="fw-bold" style="font-size:13px;">Berkaitan dengan transaksi:</div>
                <code style="font-size:13px;color:var(--biru-tua);">
                    {{ $transaction->no_transaksi }}
                </code>
                <span class="text-muted ms-2" style="font-size:12px;">
                    — {{ $transaction->items->pluck('nama_item')->join(', ') }}
                </span>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Pilih Pasien jika belum ada --}}
@if(!$patient)
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-header fw-bold py-3"
         style="background:#f8f9fa;border-radius:12px 12px 0 0;">
        <i class="bi bi-search me-2" style="color:var(--biru-muda);"></i>Cari & Pilih Pasien
    </div>
    <div class="card-body p-3">
        <div class="input-group">
            <span class="input-group-text bg-white">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" id="patient-search"
                   class="form-control border-start-0"
                   placeholder="Ketik nama atau No. RM pasien..."
                   autocomplete="off">
        </div>
        <div id="patient-results" class="mt-2"></div>
        <input type="hidden" id="selected-patient-id">
        <div id="selected-patient-info" class="mt-2"></div>
    </div>
</div>
@endif

{{-- Form Rekam Medis --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-clipboard2-pulse-fill me-2"></i>
        Form Pemeriksaan
        @if($patient)
            — {{ $patient->nama }}
            <span class="badge ms-2"
                  style="background:rgba(255,255,255,0.2);">
                {{ $patient->no_rm }}
            </span>
        @endif
    </div>
    <div class="card-body p-4">
        <form action="{{ route('staff.medical-records.store') }}" method="POST">
            @csrf

            <input type="hidden" name="patient_id"
                   value="{{ $patient?->id }}" id="form-patient-id">
            @if($transaction)
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
            @endif

            {{-- Tanggal --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Tanggal Periksa <span class="text-danger">*</span>
                </label>
                <input type="date" name="tanggal_periksa"
                       class="form-control @error('tanggal_periksa') is-invalid @enderror"
                       value="{{ old('tanggal_periksa', date('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}">
                @error('tanggal_periksa')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Keterangan field aktif/nonaktif --}}
            <div class="alert py-2 mb-4"
                 style="background:#fff8e1;border-left:3px solid var(--kuning);
                        border-radius:8px;font-size:12px;">
                <i class="bi bi-info-circle-fill me-1" style="color:var(--orange);"></i>
                Field <strong>berwarna</strong> = sesuai layanan yang dibeli.
                Field <strong>abu-abu</strong> = tidak ada di transaksi (bisa diisi jika diperlukan).
            </div>

            {{-- Grid Pemeriksaan --}}
            <div class="row g-3 mb-4">

                @php
                    $isActive = fn($field) => in_array($field, $activeFields);
                @endphp

                {{-- Gula Darah --}}
                <div class="col-md-6">
                    <div class="p-3 rounded h-100"
                         style="background:{{ $isActive('gula_darah') ? '#fff8e1' : '#f5f5f5' }};
                                border:1px solid {{ $isActive('gula_darah') ? '#ffe082' : '#e0e0e0' }};
                                opacity:{{ $isActive('gula_darah') ? '1' : '0.6' }};">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-droplet-fill me-1"
                               style="color:{{ $isActive('gula_darah') ? 'var(--orange)' : '#aaa' }};"></i>
                            Gula Darah
                            @if($isActive('gula_darah'))
                                <span class="badge bg-warning text-dark ms-1"
                                      style="font-size:9px;">Aktif</span>
                            @endif
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="gula_darah"
                                   class="form-control"
                                   value="{{ old('gula_darah') }}"
                                   placeholder="{{ $isActive('gula_darah') ? 'mis: 120' : '-' }}"
                                   step="0.01" min="0"
                                   {{ $isActive('gula_darah') ? '' : 'disabled style=background:#f0f0f0;cursor:not-allowed' }}
                                   oninput="checkNormal(this, 0, 200, 'status-gula')">
                            <span class="input-group-text">mg/dl</span>
                        </div>
                        <div id="status-gula" class="mt-1" style="font-size:11px;"></div>
                        <div class="text-muted mt-1" style="font-size:10px;">
                            Normal sewaktu: &lt;200 · Puasa: 70-110
                        </div>
                    </div>
                </div>

                {{-- Kolesterol --}}
                <div class="col-md-6">
                    <div class="p-3 rounded h-100"
                         style="background:{{ $isActive('kolesterol') ? '#f3e5f5' : '#f5f5f5' }};
                                border:1px solid {{ $isActive('kolesterol') ? '#ce93d8' : '#e0e0e0' }};
                                opacity:{{ $isActive('kolesterol') ? '1' : '0.6' }};">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-heart-fill me-1"
                               style="color:{{ $isActive('kolesterol') ? '#8E24AA' : '#aaa' }};"></i>
                            Kolesterol
                            @if($isActive('kolesterol'))
                                <span class="badge ms-1"
                                      style="background:#ce93d8;color:#fff;font-size:9px;">Aktif</span>
                            @endif
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="kolesterol"
                                   class="form-control"
                                   value="{{ old('kolesterol') }}"
                                   placeholder="{{ $isActive('kolesterol') ? 'mis: 180' : '-' }}"
                                   step="0.01" min="0"
                                   {{ $isActive('kolesterol') ? '' : 'disabled style=background:#f0f0f0;cursor:not-allowed' }}
                                   oninput="checkRange(this, 160, 200, 'status-kolesterol')">
                            <span class="input-group-text">mg/dl</span>
                        </div>
                        <div id="status-kolesterol" class="mt-1" style="font-size:11px;"></div>
                        <div class="text-muted mt-1" style="font-size:10px;">Normal: 160-200 mg/dl</div>
                    </div>
                </div>

                {{-- Asam Urat --}}
                <div class="col-md-6">
                    <div class="p-3 rounded h-100"
                         style="background:{{ $isActive('asam_urat') ? '#e8f5e9' : '#f5f5f5' }};
                                border:1px solid {{ $isActive('asam_urat') ? '#a5d6a7' : '#e0e0e0' }};
                                opacity:{{ $isActive('asam_urat') ? '1' : '0.6' }};">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-activity me-1"
                               style="color:{{ $isActive('asam_urat') ? 'var(--hijau)' : '#aaa' }};"></i>
                            Asam Urat
                            @if($isActive('asam_urat'))
                                <span class="badge bg-success ms-1" style="font-size:9px;">Aktif</span>
                            @endif
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="asam_urat"
                                   class="form-control"
                                   value="{{ old('asam_urat') }}"
                                   placeholder="{{ $isActive('asam_urat') ? 'mis: 5.5' : '-' }}"
                                   step="0.1" min="0"
                                   {{ $isActive('asam_urat') ? '' : 'disabled style=background:#f0f0f0;cursor:not-allowed' }}
                                   oninput="checkAsamUrat(this)">
                            <span class="input-group-text">mg/dl</span>
                        </div>
                        <div id="status-asam-urat" class="mt-1" style="font-size:11px;"></div>
                        <div class="text-muted mt-1" style="font-size:10px;">
                            Pria: 3,4-7 · Wanita: 2,4-6 mg/dl
                        </div>
                    </div>
                </div>

                {{-- Tensi --}}
                <div class="col-md-6">
                    <div class="p-3 rounded h-100"
                         style="background:{{ $isActive('tensi') ? '#e3f2fd' : '#f5f5f5' }};
                                border:1px solid {{ $isActive('tensi') ? '#90caf9' : '#e0e0e0' }};
                                opacity:{{ $isActive('tensi') ? '1' : '0.6' }};">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-speedometer2 me-1"
                               style="color:{{ $isActive('tensi') ? 'var(--biru-muda)' : '#aaa' }};"></i>
                            Tekanan Darah
                            @if($isActive('tensi'))
                                <span class="badge bg-primary ms-1" style="font-size:9px;">Aktif</span>
                            @endif
                        </label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="number" name="tensi_sistolik"
                                   class="form-control form-control-sm"
                                   value="{{ old('tensi_sistolik') }}"
                                   placeholder="Sistolik" min="0"
                                   {{ $isActive('tensi') ? '' : 'disabled style=background:#f0f0f0;cursor:not-allowed' }}
                                   oninput="checkTensi()">
                            <span class="fw-bold">/</span>
                            <input type="number" name="tensi_diastolik"
                                   class="form-control form-control-sm"
                                   value="{{ old('tensi_diastolik') }}"
                                   placeholder="Diastolik" min="0"
                                   {{ $isActive('tensi') ? '' : 'disabled style=background:#f0f0f0;cursor:not-allowed' }}
                                   oninput="checkTensi()">
                            <span class="input-group-text" style="font-size:11px;">mmHg</span>
                        </div>
                        <div id="status-tensi" class="mt-1" style="font-size:11px;"></div>
                        <div class="text-muted mt-1" style="font-size:10px;">Normal: 120/80 mmHg</div>
                    </div>
                </div>

                {{-- Suhu --}}
                <div class="col-md-4">
                    <div class="p-3 rounded"
                         style="background:{{ $isActive('suhu') ? '#fff3e0' : '#f5f5f5' }};
                                border:1px solid {{ $isActive('suhu') ? '#ffcc80' : '#e0e0e0' }};
                                opacity:{{ $isActive('suhu') ? '1' : '0.6' }};">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-thermometer-half me-1"
                               style="color:{{ $isActive('suhu') ? 'var(--orange)' : '#aaa' }};"></i>
                            Suhu Tubuh
                            @if($isActive('suhu'))
                                <span class="badge bg-warning text-dark ms-1"
                                      style="font-size:9px;">Aktif</span>
                            @endif
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="suhu" class="form-control"
                                   value="{{ old('suhu') }}"
                                   placeholder="{{ $isActive('suhu') ? '36.5' : '-' }}"
                                   step="0.1" min="30" max="45"
                                   {{ $isActive('suhu') ? '' : 'disabled style=background:#f0f0f0;cursor:not-allowed' }}>
                            <span class="input-group-text">°C</span>
                        </div>
                        <div class="text-muted mt-1" style="font-size:10px;">Normal: 36,1-37,2°C</div>
                    </div>
                </div>

                {{-- Nadi --}}
                <div class="col-md-4">
                    <div class="p-3 rounded"
                         style="background:{{ $isActive('nadi') ? '#fce4ec' : '#f5f5f5' }};
                                border:1px solid {{ $isActive('nadi') ? '#f48fb1' : '#e0e0e0' }};
                                opacity:{{ $isActive('nadi') ? '1' : '0.6' }};">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-heart-pulse me-1"
                               style="color:{{ $isActive('nadi') ? '#e53935' : '#aaa' }};"></i>
                            Nadi
                            @if($isActive('nadi'))
                                <span class="badge ms-1"
                                      style="background:#e53935;color:#fff;font-size:9px;">Aktif</span>
                            @endif
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="nadi" class="form-control"
                                   value="{{ old('nadi') }}"
                                   placeholder="{{ $isActive('nadi') ? '80' : '-' }}"
                                   min="0"
                                   {{ $isActive('nadi') ? '' : 'disabled style=background:#f0f0f0;cursor:not-allowed' }}>
                            <span class="input-group-text">x/mnt</span>
                        </div>
                        <div class="text-muted mt-1" style="font-size:10px;">Normal: 60-100 x/mnt</div>
                    </div>
                </div>

                {{-- Respirasi --}}
                <div class="col-md-4">
                    <div class="p-3 rounded"
                         style="background:{{ $isActive('respirasi') ? '#e0f7fa' : '#f5f5f5' }};
                                border:1px solid {{ $isActive('respirasi') ? '#80deea' : '#e0e0e0' }};
                                opacity:{{ $isActive('respirasi') ? '1' : '0.6' }};">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-wind me-1"
                               style="color:{{ $isActive('respirasi') ? 'var(--biru-muda)' : '#aaa' }};"></i>
                            Respirasi
                            @if($isActive('respirasi'))
                                <span class="badge bg-info ms-1" style="font-size:9px;">Aktif</span>
                            @endif
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="respirasi" class="form-control"
                                   value="{{ old('respirasi') }}"
                                   placeholder="{{ $isActive('respirasi') ? '20' : '-' }}"
                                   min="0"
                                   {{ $isActive('respirasi') ? '' : 'disabled style=background:#f0f0f0;cursor:not-allowed' }}>
                            <span class="input-group-text">x/mnt</span>
                        </div>
                        <div class="text-muted mt-1" style="font-size:10px;">Normal: 12-20 x/mnt</div>
                    </div>
                </div>

            </div>

            {{-- Catatan --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan Tambahan</label>
                <textarea name="catatan" class="form-control" rows="2"
                          placeholder="Catatan tambahan (opsional)"
                          maxlength="500">{{ old('catatan') }}</textarea>
            </div>

            @error('patient_id')
                <div class="alert alert-danger py-2" style="font-size:13px;">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ $message }}
                </div>
            @enderror

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success px-4 fw-semibold">
                    <i class="bi bi-check-circle me-2"></i>Simpan Hasil Pemeriksaan
                </button>
                @if($transaction)
                <a href="{{ route('staff.transactions.show', $transaction) }}"
                   class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Transaksi
                </a>
                @else
                <a href="{{ route('staff.patients.index') }}"
                   class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Batal
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection

@push('scripts')
<script>
// Autocomplete pasien
const patientSearch = document.getElementById('patient-search');
if (patientSearch) {
    let timer;
    patientSearch.addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(async () => {
            const q = this.value.trim();
            if (q.length < 2) return;
            const res  = await fetch(`{{ route('staff.patients.search') }}?q=` + encodeURIComponent(q));
            const data = await res.json();
            const box  = document.getElementById('patient-results');
            if (data.length === 0) {
                box.innerHTML = '<small class="text-muted">Pasien tidak ditemukan.</small>';
                return;
            }
            let html = '<div class="list-group mt-1">';
            data.forEach(p => {
                html += `<button type="button"
                    class="list-group-item list-group-item-action py-2"
                    onclick="selectPatient(${p.id},'${p.nama}','${p.no_rm}')">
                    <span class="badge bg-success-subtle text-success me-2">${p.no_rm}</span>
                    <strong>${p.nama}</strong>
                </button>`;
            });
            html += '</div>';
            box.innerHTML = html;
        }, 300);
    });
}

function selectPatient(id, nama, noRm) {
    document.getElementById('form-patient-id').value = id;
    document.getElementById('patient-results').innerHTML = '';
    document.getElementById('selected-patient-info').innerHTML = `
        <div class="alert alert-success py-2 mt-2" style="font-size:13px;">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>${nama}</strong>
            <span class="badge bg-success ms-2">${noRm}</span>
        </div>`;
    if (patientSearch) patientSearch.value = nama;
}

// Cek nilai normal
function checkNormal(input, min, max, statusId) {
    const val = parseFloat(input.value);
    const el  = document.getElementById(statusId);
    if (!val || isNaN(val)) { el.innerHTML = ''; return; }
    el.innerHTML = val < max
        ? '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Normal</span>'
        : '<span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Di atas normal</span>';
}

function checkRange(input, min, max, statusId) {
    const val = parseFloat(input.value);
    const el  = document.getElementById(statusId);
    if (!val || isNaN(val)) { el.innerHTML = ''; return; }
    if (val >= min && val <= max) {
        el.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Normal</span>';
    } else if (val < min) {
        el.innerHTML = '<span class="text-warning"><i class="bi bi-exclamation-circle-fill me-1"></i>Di bawah normal</span>';
    } else {
        el.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Di atas normal</span>';
    }
}

function checkAsamUrat(input) {
    const val = parseFloat(input.value);
    const el  = document.getElementById('status-asam-urat');
    if (!val || isNaN(val)) { el.innerHTML = ''; return; }
    if (val >= 3.4 && val <= 7) {
        el.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Normal (Pria)</span>';
    } else if (val >= 2.4 && val <= 6) {
        el.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Normal (Wanita)</span>';
    } else {
        el.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Di luar batas normal</span>';
    }
}

function checkTensi() {
    const s  = parseInt(document.querySelector('[name=tensi_sistolik]').value);
    const d  = parseInt(document.querySelector('[name=tensi_diastolik]').value);
    const el = document.getElementById('status-tensi');
    if (!s || !d) { el.innerHTML = ''; return; }
    if (s <= 120 && d <= 80) {
        el.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Normal</span>';
    } else if (s <= 139 || d <= 89) {
        el.innerHTML = '<span class="text-warning"><i class="bi bi-exclamation-circle-fill me-1"></i>Prehipertensi</span>';
    } else {
        el.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Hipertensi</span>';
    }
}
</script>
@endpush