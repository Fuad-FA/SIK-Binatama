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



{{-- Alert error kosong --}}
@if(session('error'))
<div class="alert border-0 shadow-sm mb-4 d-flex align-items-start gap-3"
     style="border-radius:12px;background:#ffebee;border-left:4px solid #c62828 !important;">
    <i class="bi bi-exclamation-triangle-fill mt-1"
       style="color:#c62828;font-size:18px;flex-shrink:0;"></i>

    <div>
        <div class="fw-bold mb-1" style="color:#c62828;">
            Tidak bisa menyimpan!
        </div>

        <div style="font-size:13px;color:#555;">
            {{ session('error') }}
        </div>

        @if(session('error_kosong'))
        <div class="mt-2 p-2 rounded"
             style="background:#fff3f3;font-size:12px;color:#666;">
            <i class="bi bi-lightbulb-fill me-1"
               style="color:#F57C00;"></i>

            <strong>Tips:</strong>
            Jika hasil pemeriksaan memang nol,
            masukkan angka <code>0</code> pada field tersebut.
            Jangan biarkan semua field kosong.
        </div>
        @endif
    </div>
</div>
@endif

{{-- Pilih Pasien jika belum ada --}}
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

            @if(!empty($activeFields))
<input type="hidden"
       name="fields"
       value="{{ implode(',', $activeFields) }}">
@endif

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

            {{-- BMI --}}
@if(in_array('bmi', $activeFields))
<div class="col-12 mt-3">
    <div class="p-3 rounded"
         style="background:#e8f5e9;border:1px solid #a5d6a7;">
        <div class="fw-semibold mb-2" style="font-size:13px;">
            <i class="bi bi-calculator me-1" style="color:var(--hijau);"></i>
            Cek BMI
            <span class="badge bg-success ms-1" style="font-size:9px;">Aktif</span>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" style="font-size:12px;">Berat Badan</label>
                <div class="input-group input-group-sm">
                    <input type="number" name="berat_badan" id="berat_badan"
                           class="form-control"
                           value="{{ old('berat_badan') }}"
                           placeholder="mis: 65" step="0.1" min="1"
                           oninput="hitungBMI()">
                    <span class="input-group-text">kg</span>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:12px;">Tinggi Badan</label>
                <div class="input-group input-group-sm">
                    <input type="number" name="tinggi_badan" id="tinggi_badan"
                           class="form-control"
                           value="{{ old('tinggi_badan') }}"
                           placeholder="mis: 165" step="0.1" min="1"
                           oninput="hitungBMI()">
                    <span class="input-group-text">cm</span>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size:12px;">BMI (otomatis)</label>
                <div class="input-group input-group-sm">
                    <input type="number" name="bmi" id="bmi-result"
                           class="form-control"
                           placeholder="Terisi otomatis"
                           readonly
                           style="background:#f0f0f0;">
                    <span class="input-group-text">kg/m²</span>
                </div>
                <div id="bmi-kategori" class="mt-1" style="font-size:12px;font-weight:600;"></div>
            </div>
        </div>
        <div class="mt-2" style="font-size:10px;color:#666;">
            Kurus &lt;18.5 · Normal 18.5–24.9 · Gemuk 25–29.9 · Obesitas ≥30
        </div>
    </div>
</div>
@endif

{{-- Konsultasi Gizi --}}
@if(in_array('catatan_gizi', $activeFields))
<div class="col-12 mt-3">
    <div class="p-3 rounded"
         style="background:#f3e5f5;border:1px solid #ce93d8;">
        <label class="fw-semibold mb-2 d-block" style="font-size:13px;">
            <i class="bi bi-chat-text-fill me-1" style="color:#8E24AA;"></i>
            Catatan Konsultasi Gizi
            <span class="badge ms-1"
                  style="background:#ce93d8;color:#fff;font-size:9px;">Aktif</span>
        </label>
        <textarea name="catatan_gizi" class="form-control" rows="4"
                  placeholder="Tuliskan hasil konsultasi gizi, saran diet, rekomendasi makanan, dll..."
                  maxlength="2000">{{ old('catatan_gizi') }}</textarea>
        <div class="text-muted mt-1" style="font-size:10px;">
            Maks. 2000 karakter — hasil konsultasi ini akan tampil di portal pasien
        </div>
    </div>
</div>
@endif

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

function hitungBMI() {
    const berat  = parseFloat(document.getElementById('berat_badan').value);
    const tinggi = parseFloat(document.getElementById('tinggi_badan').value);
    const result = document.getElementById('bmi-result');
    const label  = document.getElementById('bmi-kategori');

    if (!berat || !tinggi || tinggi <= 0) {
        result.value = '';
        label.textContent = '';
        return;
    }

    const tinggiM = tinggi / 100;
    const bmi     = (berat / (tinggiM * tinggiM)).toFixed(2);
    result.value  = bmi;

    // Kategori dan warna
    let kategori, warna;
    if (bmi < 18.5) {
        kategori = 'Kurus'; warna = '#1976D2';
    } else if (bmi < 25) {
        kategori = 'Normal ✓'; warna = '#2E7D32';
    } else if (bmi < 30) {
        kategori = 'Gemuk'; warna = '#F57C00';
    } else {
        kategori = 'Obesitas'; warna = '#c62828';
    }

    label.textContent = kategori;
    label.style.color = warna;
}

// Validasi form sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {

    const activeInputs = document.querySelectorAll(
        '.p-3.rounded:not([style*="opacity:0"]) input:not([disabled]):not([readonly]),' +
        '.p-3.rounded:not([style*="opacity:0"]) textarea:not([disabled])'
    );

    let adaYangDiisi = false;

    activeInputs.forEach(input => {
        if (input.value && input.value.trim() !== '') {
            adaYangDiisi = true;
        }
    });

    if (!adaYangDiisi && activeInputs.length > 0) {

        e.preventDefault();

        // Tampilkan peringatan
        let alertBox = document.getElementById('client-validation-alert');

        if (!alertBox) {

            alertBox = document.createElement('div');

            alertBox.id = 'client-validation-alert';

            alertBox.style.cssText = `
                position:fixed;
                top:20px;
                left:50%;
                transform:translateX(-50%);
                z-index:9999;
                min-width:320px;
                max-width:480px;
                background:#ffebee;
                border:1px solid #ef9a9a;
                border-radius:12px;
                padding:14px 18px;
                box-shadow:0 8px 24px rgba(0,0,0,0.15);
                display:flex;
                align-items:flex-start;
                gap:12px;
            `;

            alertBox.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill"
                   style="color:#c62828;font-size:20px;flex-shrink:0;margin-top:2px;"></i>

                <div>
                    <div style="font-weight:700;color:#c62828;margin-bottom:4px;">
                        Hasil pemeriksaan tidak boleh kosong!
                    </div>

                    <div style="font-size:13px;color:#555;">
                        Isi minimal satu field yang tersedia.<br>
                        Jika hasilnya nol, masukkan angka <strong>0</strong>.
                    </div>
                </div>

                <button onclick="this.parentElement.remove()"
                        style="background:none;
                               border:none;
                               color:#999;
                               font-size:18px;
                               cursor:pointer;
                               margin-left:auto;
                               padding:0;
                               line-height:1;">
                    ×
                </button>
            `;

            document.body.appendChild(alertBox);
        }

        // Highlight field kosong
        activeInputs.forEach(input => {

            if (!input.value || input.value.trim() === '') {

                input.style.borderColor = '#c62828';
                input.style.background  = '#fff5f5';

                setTimeout(() => {
                    input.style.borderColor = '';
                    input.style.background  = '';
                }, 4000);
            }
        });

        // Auto remove alert
        setTimeout(() => {
            if (alertBox) alertBox.remove();
        }, 5000);

        // Scroll ke atas
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
});
</script>
@endpush