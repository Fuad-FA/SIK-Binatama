@extends('layouts.app')
@section('title', 'Input Pemeriksaan')
@section('page-title', 'Input Hasil Pemeriksaan')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

{{-- Pilih Pasien (jika belum ada dari query) --}}
@if(!$patient)
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-header fw-bold py-3"
         style="background:#f8f9fa;border-radius:12px 12px 0 0;">
        <i class="bi bi-search me-2" style="color:var(--biru-muda);"></i>
        Cari & Pilih Pasien
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

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header fw-bold text-white py-3"
         style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
        <i class="bi bi-clipboard2-pulse-fill me-2"></i>
        Form Pemeriksaan
        @if($patient)
            — {{ $patient->nama }}
            <span class="badge ms-2"
                  style="background:rgba(255,255,255,0.2);font-weight:600;">
                {{ $patient->no_rm }}
            </span>
        @endif
    </div>
    <div class="card-body p-4">
        <form action="{{ route('staff.medical-records.store') }}" method="POST"
              id="medical-form">
            @csrf

            <input type="hidden" name="patient_id"
                   value="{{ $patient?->id }}" id="form-patient-id">

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

            {{-- Grid Hasil Pemeriksaan --}}
            <div class="row g-3 mb-4">

                {{-- Gula Darah --}}
                <div class="col-md-6">
                    <div class="p-3 rounded" style="background:#fff8e1;border:1px solid #ffe082;">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-droplet-fill me-1" style="color:var(--orange);"></i>
                            Gula Darah
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="gula_darah"
                                   class="form-control"
                                   value="{{ old('gula_darah') }}"
                                   placeholder="mis: 120" step="0.01" min="0"
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
                    <div class="p-3 rounded" style="background:#f3e5f5;border:1px solid #ce93d8;">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-heart-fill me-1" style="color:#8E24AA;"></i>
                            Kolesterol
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="kolesterol"
                                   class="form-control"
                                   value="{{ old('kolesterol') }}"
                                   placeholder="mis: 180" step="0.01" min="0"
                                   oninput="checkRange(this, 160, 200, 'status-kolesterol')">
                            <span class="input-group-text">mg/dl</span>
                        </div>
                        <div id="status-kolesterol" class="mt-1" style="font-size:11px;"></div>
                        <div class="text-muted mt-1" style="font-size:10px;">
                            Normal: 160-200 mg/dl
                        </div>
                    </div>
                </div>

                {{-- Asam Urat --}}
                <div class="col-md-6">
                    <div class="p-3 rounded" style="background:#e8f5e9;border:1px solid #a5d6a7;">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-activity me-1" style="color:var(--hijau);"></i>
                            Asam Urat
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="asam_urat"
                                   class="form-control"
                                   value="{{ old('asam_urat') }}"
                                   placeholder="mis: 5.5" step="0.1" min="0"
                                   oninput="checkAsamUrat(this)">
                            <span class="input-group-text">mg/dl</span>
                        </div>
                        <div id="status-asam-urat" class="mt-1" style="font-size:11px;"></div>
                        <div class="text-muted mt-1" style="font-size:10px;">
                            Normal Pria: 3,4-7 · Wanita: 2,4-6 mg/dl
                        </div>
                    </div>
                </div>

                {{-- Tensi --}}
                <div class="col-md-6">
                    <div class="p-3 rounded" style="background:#e3f2fd;border:1px solid #90caf9;">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">
                            <i class="bi bi-speedometer2 me-1" style="color:var(--biru-muda);"></i>
                            Tekanan Darah (Tensi)
                        </label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="number" name="tensi_sistolik"
                                   class="form-control form-control-sm"
                                   value="{{ old('tensi_sistolik') }}"
                                   placeholder="Sistolik" min="0"
                                   oninput="checkTensi()">
                            <span class="fw-bold">/</span>
                            <input type="number" name="tensi_diastolik"
                                   class="form-control form-control-sm"
                                   value="{{ old('tensi_diastolik') }}"
                                   placeholder="Diastolik" min="0"
                                   oninput="checkTensi()">
                            <span class="input-group-text" style="font-size:12px;">mmHg</span>
                        </div>
                        <div id="status-tensi" class="mt-1" style="font-size:11px;"></div>
                        <div class="text-muted mt-1" style="font-size:10px;">Normal: 120/80 mmHg</div>
                    </div>
                </div>

                {{-- Suhu --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:13px;">
                        <i class="bi bi-thermometer-half me-1" style="color:var(--orange);"></i>
                        Suhu Tubuh
                    </label>
                    <div class="input-group input-group-sm">
                        <input type="number" name="suhu" class="form-control"
                               value="{{ old('suhu') }}"
                               placeholder="36.5" step="0.1" min="30" max="45">
                        <span class="input-group-text">°C</span>
                    </div>
                    <div class="text-muted mt-1" style="font-size:10px;">Normal: 36,1-37,2°C</div>
                </div>

                {{-- Nadi --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:13px;">
                        <i class="bi bi-heart-pulse me-1" style="color:#e53935;"></i>
                        Nadi
                    </label>
                    <div class="input-group input-group-sm">
                        <input type="number" name="nadi" class="form-control"
                               value="{{ old('nadi') }}"
                               placeholder="80" min="0">
                        <span class="input-group-text">x/mnt</span>
                    </div>
                    <div class="text-muted mt-1" style="font-size:10px;">Normal: 60-100 x/mnt</div>
                </div>

                {{-- Respirasi --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:13px;">
                        <i class="bi bi-wind me-1" style="color:var(--biru-muda);"></i>
                        Respirasi
                    </label>
                    <div class="input-group input-group-sm">
                        <input type="number" name="respirasi" class="form-control"
                               value="{{ old('respirasi') }}"
                               placeholder="20" min="0">
                        <span class="input-group-text">x/mnt</span>
                    </div>
                    <div class="text-muted mt-1" style="font-size:10px;">Normal: 12-20 x/mnt</div>
                </div>
            </div>

            {{-- Catatan --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan Tambahan</label>
                <textarea name="catatan" class="form-control" rows="2"
                          placeholder="Catatan tambahan hasil pemeriksaan (opsional)"
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
                <a href="{{ route('staff.patients.index') }}"
                   class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection

@push('scripts')
<script>
// Cari pasien autocomplete
const searchInput = document.getElementById('patient-search');
if (searchInput) {
    let timer;
    searchInput.addEventListener('input', function() {
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
            let html = '<div class="list-group">';
            data.forEach(p => {
                html += `
                <button type="button" class="list-group-item list-group-item-action py-2"
                        onclick="selectPatient(${p.id}, '${p.nama}', '${p.no_rm}')">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success-subtle text-success">${p.no_rm}</span>
                        <strong>${p.nama}</strong>
                        <span class="text-muted ms-auto" style="font-size:12px;">
                            ${p.jenis_kelamin === 'L' ? 'Laki-laki' : p.jenis_kelamin === 'P' ? 'Perempuan' : '-'}
                        </span>
                    </div>
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
        <div class="alert alert-success py-2" style="font-size:13px;">
            <i class="bi bi-check-circle-fill me-2"></i>
            Pasien dipilih: <strong>${nama}</strong>
            <span class="badge bg-success ms-2">${noRm}</span>
        </div>`;
    if (searchInput) searchInput.value = nama;
}

// Cek nilai normal
function checkNormal(input, min, max, statusId) {
    const val = parseFloat(input.value);
    const el  = document.getElementById(statusId);
    if (!val || isNaN(val)) { el.innerHTML = ''; return; }
    if (val < max) {
        el.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Normal</span>';
    } else {
        el.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Di atas normal</span>';
    }
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
    // Default cek range laki-laki (3.4-7)
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