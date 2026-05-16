@extends('layouts.app')
@section('title', 'Transaksi Baru')
@section('page-title', 'Transaksi Baru')

@section('content')

<form action="{{ route('staff.transactions.store') }}" method="POST" id="trx-form">
@csrf

<div class="row g-4">

{{-- Kolom Kiri: Pilih Pasien & Item --}}
<div class="col-lg-7">

    {{-- Pilih Pasien --}}
    {{-- <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
        <div class="card-header fw-bold py-3"
             style="background:var(--biru-tua);border-radius:12px 12px 0 0;color:#fff;">
            <i class="bi bi-person-fill me-2"></i>Pasien
        </div>
        <div class="card-body p-3">
            @if($patient)
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                @if($medicalRecord)
                    <input type="hidden" name="medical_record_id" value="{{ $medicalRecord->id }}">
                @endif
                <div class="d-flex align-items-center gap-3 p-2 rounded"
                     style="background:#e8f5e9;">
                    <div style="width:44px;height:44px;border-radius:50%;background:var(--hijau);
                                display:flex;align-items:center;justify-content:center;
                                color:#fff;font-weight:700;font-size:18px;">
                        {{ strtoupper(substr($patient->nama, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ $patient->nama }}</div>
                        <div style="font-size:12px;color:#555;">
                            <code style="background:#c8e6c9;padding:1px 6px;border-radius:3px;">
                                {{ $patient->no_rm }}
                            </code>
                            @if($medicalRecord)
                                <span class="badge bg-success ms-1">+ Rekam Medis Terlampir</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('staff.transactions.create') }}"
                       class="btn btn-sm btn-outline-secondary ms-auto">Ganti</a>
                </div>
            @else
                <div class="input-group mb-2">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="patient-search"
                           class="form-control border-start-0"
                           placeholder="Cari pasien berdasarkan nama atau No. RM..."
                           autocomplete="off">
                </div>
                <div id="patient-results"></div>
                <input type="hidden" name="patient_id" id="patient-id-input">
                <div id="patient-selected"></div>
            @endif
        </div>
    </div> --}}


    Pilih Pasien
    {{-- Pilih Pasien --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
        <div class="card-header fw-bold py-3"
             style="background:var(--biru-tua);border-radius:12px 12px 0 0;color:#fff;">
            <i class="bi bi-person-fill me-2"></i>Pilih Pasien
        </div>
        <div class="card-body p-3">
            @if($patient)
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                @if($medicalRecord)
                    <input type="hidden" name="medical_record_id" value="{{ $medicalRecord->id }}">
                @endif
                <div class="d-flex align-items-center gap-3 p-2 rounded"
                     style="background:#e8f5e9;">
                    <div style="width:44px;height:44px;border-radius:50%;background:var(--hijau);
                                display:flex;align-items:center;justify-content:center;
                                color:#fff;font-weight:700;font-size:18px;">
                        {{ strtoupper(substr($patient->nama, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ $patient->nama }}</div>
                        <code style="font-size:11px;background:#c8e6c9;padding:1px 6px;
                                     border-radius:3px;color:var(--hijau);">
                            {{ $patient->no_rm }}
                        </code>
                    </div>
                    <a href="{{ route('staff.transactions.create') }}"
                       class="btn btn-sm btn-outline-secondary ms-auto">Ganti</a>
                </div>
            @else
                {{-- Search box --}}
                <div class="input-group mb-2">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="patient-search"
                           class="form-control border-start-0"
                           placeholder="Ketik nama atau No. RM pasien..."
                           autocomplete="off">
                </div>

                {{-- Hasil pencarian --}}
                <div id="patient-results"></div>

                {{-- Tombol daftarkan baru — muncul setelah search tidak ketemu --}}
                <div id="btn-pasien-baru-wrapper" style="display:none;" class="mt-2">
                    <div class="alert py-2 mb-2"
                         style="background:#fff3e0;border-left:3px solid var(--orange);
                                border-radius:8px;font-size:13px;">
                        <i class="bi bi-info-circle me-1" style="color:var(--orange);"></i>
                        Pasien tidak ditemukan.
                    </div>
                    <a href="{{ route('staff.patients.create') }}?redirect_transaksi=1"
                       class="btn btn-success w-100 fw-semibold">
                        <i class="bi bi-person-plus-fill me-2"></i>
                        Daftarkan Pasien Baru
                    </a>
                </div>

                {{-- Pasien terpilih --}}
                <div id="patient-selected"></div>
                <input type="hidden" name="patient_id" id="patient-id-input">
            @endif
        </div>
    </div>

    {{-- Pilih Layanan --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
        <div class="card-header fw-semibold py-2"
             style="background:#e8f5e9;border-radius:12px 12px 0 0;">
            <i class="bi bi-heart-pulse-fill me-2" style="color:var(--hijau);"></i>
            Layanan & Paket
        </div>
        <div class="card-body p-3">
            {{-- Paket --}}
            {{-- @foreach($packages as $pkg)
            <div class="form-check mb-2 p-2 rounded"
                 style="background:#f8f9fa;border:1px solid #eee;">
                <input class="form-check-input" type="checkbox"
                       id="pkg{{ $pkg->id }}"
                       onchange="addItem('package', {{ $pkg->id }}, '{{ addslashes($pkg->nama) }}', {{ $pkg->harga }}, this)">
                <label class="form-check-label d-flex justify-content-between w-100"
                       for="pkg{{ $pkg->id }}" style="cursor:pointer;">
                    <span>
                        <span class="badge bg-success-subtle text-success me-1">Paket</span>
                        {{ $pkg->nama }}
                    </span>
                    <span class="fw-bold" style="color:var(--orange);">
                        Rp {{ number_format($pkg->harga, 0, ',', '.') }}
                    </span>
                </label>
            </div>
            @endforeach --}}
{{-- Paket --}}
            @foreach($packages as $pkg)
            <div class="mb-2 rounded" style="border:1px solid #e0e0e0;overflow:hidden;">
                {{-- Header paket --}}
                <div class="d-flex align-items-center gap-2 p-2"
                     style="background:#f8f9fa;cursor:pointer;"
                     onclick="togglePaket({{ $pkg->id }})">
                    {{-- Checkbox --}}
                    <input class="form-check-input mt-0 flex-shrink-0" type="checkbox"
                           id="pkg{{ $pkg->id }}"
                           onchange="addItem('package', {{ $pkg->id }}, '{{ addslashes($pkg->nama) }}', {{ $pkg->harga }}, this)"
                           onclick="event.stopPropagation()">
                    {{-- Nama & Harga --}}
                    <label class="flex-grow-1 d-flex justify-content-between align-items-center"
                           for="pkg{{ $pkg->id }}"
                           style="cursor:pointer;margin:0;"
                           onclick="event.stopPropagation();document.getElementById('pkg{{ $pkg->id }}').click()">
                        <span>
                            <span class="badge bg-success-subtle text-success me-1"
                                  style="font-size:10px;">Paket</span>
                            <span class="fw-semibold" style="font-size:13px;">{{ $pkg->nama }}</span>
                        </span>
                        <span class="fw-bold" style="color:var(--orange);">
                            Rp {{ number_format($pkg->harga, 0, ',', '.') }}
                        </span>
                    </label>
                    {{-- Toggle icon --}}
                    <i class="bi bi-chevron-down text-muted"
                       id="icon-pkg{{ $pkg->id }}"
                       style="font-size:12px;transition:transform 0.2s;flex-shrink:0;"
                       onclick="event.stopPropagation();togglePaket({{ $pkg->id }})"></i>
                </div>

                {{-- Detail isi paket (collapsed by default) --}}
                <div id="detail-pkg{{ $pkg->id }}"
                     style="display:none;background:#fff;padding:8px 12px;
                            border-top:1px solid #e0e0e0;">
                    <div class="text-muted mb-1" style="font-size:10px;font-weight:600;text-transform:uppercase;">
                        Termasuk layanan:
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($pkg->services as $svc)
                        <span class="badge px-2 py-1"
                              style="background:#e3f2fd;color:var(--biru-tua);font-size:11px;font-weight:500;">
                            <i class="bi bi-check2 me-1"></i>{{ $svc->nama }}
                        </span>
                        @endforeach
                    </div>
                    @if($pkg->deskripsi)
                    <div class="text-muted mt-1" style="font-size:11px;">
                        <i class="bi bi-info-circle me-1"></i>{{ $pkg->deskripsi }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            <hr class="my-2">

            {{-- Layanan Satuan --}}
            {{-- @foreach($services as $svc)
            <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox"
                       id="svc{{ $svc->id }}"
                       onchange="addItem('service', {{ $svc->id }}, '{{ addslashes($svc->nama) }}', {{ $svc->harga }}, this)">
                <label class="form-check-label d-flex justify-content-between w-100"
                       for="svc{{ $svc->id }}" style="cursor:pointer;font-size:13px;">
                    <span>{{ $svc->nama }}</span>
                    <span style="color:var(--orange);">
                        Rp {{ number_format($svc->harga, 0, ',', '.') }}
                    </span>
                </label>
            </div>
            @endforeach --}}

            {{-- Layanan Satuan --}}
            @foreach($services as $svc)
            <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox"
                       id="svc{{ $svc->id }}"
                       onchange="addItem('service', {{ $svc->id }}, '{{ addslashes($svc->nama) }}', {{ $svc->harga }}, this)">
                <label class="form-check-label d-flex justify-content-between w-100"
                       for="svc{{ $svc->id }}" style="cursor:pointer;font-size:13px;">
                    <span>{{ $svc->nama }}</span>
                    @if($svc->harga > 0)
                        <span style="color:var(--orange);">
                            Rp {{ number_format($svc->harga, 0, ',', '.') }}
                        </span>
                    @else
                        <span class="badge bg-success-subtle text-success"
                              style="font-size:10px;">Gratis</span>
                    @endif
                </label>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Pilih Produk --}}
    <div class="card border-0 shadow-sm" style="border-radius:12px;">
        <div class="card-header fw-semibold py-2"
             style="background:#e3f2fd;border-radius:12px 12px 0 0;">
            <i class="bi bi-box-seam-fill me-2" style="color:var(--biru-muda);"></i>
            Produk
        </div>
        <div class="card-body p-3" style="max-height:300px;overflow-y:auto;">
            @foreach($products->groupBy('kategori') as $kat => $items)
            <div class="text-muted fw-semibold mb-1 mt-2" style="font-size:11px;text-transform:uppercase;">
                {{ $kat === 'makanan_minuman' ? 'Makanan & Minuman' : ucfirst($kat) }}
            </div>
            @foreach($items as $prod)
            <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox"
                       id="prod{{ $prod->id }}"
                       onchange="addItem('product', {{ $prod->id }}, '{{ addslashes($prod->nama) }}', {{ $prod->harga }}, this)">
                <label class="form-check-label d-flex justify-content-between w-100"
                       for="prod{{ $prod->id }}" style="cursor:pointer;font-size:13px;">
                    <span>
                        {{ $prod->nama }}
                        <span class="text-muted">(stok: {{ $prod->stok }})</span>
                    </span>
                    <span style="color:var(--orange);">
                        Rp {{ number_format($prod->harga, 0, ',', '.') }}
                    </span>
                </label>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>

</div>

{{-- Kolom Kanan: Ringkasan --}}
<div class="col-lg-5">
    <div class="card border-0 shadow-sm" style="border-radius:12px;position:sticky;top:80px;">
        <div class="card-header fw-bold text-white py-3"
             style="background:var(--biru-tua);border-radius:12px 12px 0 0;">
            <i class="bi bi-receipt me-2"></i>Ringkasan Transaksi
        </div>
        <div class="card-body p-3">

            {{-- Daftar item dipilih --}}
            <div id="items-list" style="min-height:100px;">
                <div id="empty-msg" class="text-center text-muted py-4" style="font-size:13px;">
                    <i class="bi bi-cart d-block fs-2 mb-2 opacity-25"></i>
                    Belum ada item dipilih
                </div>
            </div>

            {{-- Hidden inputs untuk items --}}
            <div id="hidden-items"></div>

            <hr>

            {{-- Subtotal --}}
            <div class="d-flex justify-content-between mb-2" style="font-size:14px;">
                <span class="text-muted">Subtotal</span>
                <span class="fw-semibold" id="display-subtotal">Rp 0</span>
            </div>

            {{-- Diskon --}}
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="text-muted" style="font-size:14px;white-space:nowrap;">Diskon (Rp)</span>
                <div class="input-group input-group-sm ms-auto" style="max-width:160px;">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="diskon" id="diskon-input"
                           class="form-control text-end"
                           value="0" min="0" oninput="updateTotal()">
                </div>
            </div>

            <hr>

            {{-- Total --}}
            <div class="d-flex justify-content-between mb-3">
                <span class="fw-bold fs-5">TOTAL</span>
                <span class="fw-bold fs-5" id="display-total"
                      style="color:var(--hijau);">Rp 0</span>
            </div>

            {{-- Metode Bayar --}}
            <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded"
                 style="background:#e8f5e9;">
                <i class="bi bi-cash-coin fs-5" style="color:var(--hijau);"></i>
                <span class="fw-semibold" style="font-size:14px;">Pembayaran Cash</span>
            </div>

            <button type="submit" class="btn btn-success w-100 fw-bold py-2"
                    id="submit-btn" disabled>
                <i class="bi bi-check-circle-fill me-2"></i>Selesaikan Transaksi
            </button>

            <a href="{{ route('staff.patients.index') }}"
               class="btn btn-outline-secondary w-100 mt-2">
                <i class="bi bi-arrow-left me-2"></i>Batal
            </a>
        </div>
    </div>
</div>

</div>
</form>

@endsection

@push('scripts')
<script>
    function togglePaket(id) {
    const detail = document.getElementById('detail-pkg' + id);
    const icon   = document.getElementById('icon-pkg' + id);
    const isOpen = detail.style.display !== 'none';
    detail.style.display = isOpen ? 'none' : 'block';
    icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
}

// Tab pasien: cari lama vs daftar baru
// function switchPatientTab(tab) {
//     document.getElementById('panel-cari').style.display = tab === 'cari' ? 'block' : 'none';
//     document.getElementById('panel-baru').style.display = tab === 'baru' ? 'block' : 'none';
//     document.getElementById('tab-cari').classList.toggle('active', tab === 'cari');
//     document.getElementById('tab-baru').classList.toggle('active', tab === 'baru');
// }

// Daftarkan pasien baru via AJAX langsung dari form transaksi
// async function daftarkanPasienBaru() {
//     const nama    = document.getElementById('new-nama').value.trim();
//     const tgl     = document.getElementById('new-tgl').value;
//     const jk      = document.getElementById('new-jk').value;
//     const telepon = document.getElementById('new-telepon').value.trim();
//     const status  = document.getElementById('new-patient-status');

//     if (!nama) {
//         status.innerHTML = '<div class="alert alert-danger py-1 mt-1" style="font-size:12px;">Nama wajib diisi.</div>';
//         return;
//     }

//     status.innerHTML = '<div class="text-muted" style="font-size:12px;"><i class="bi bi-hourglass-split me-1"></i>Mendaftarkan...</div>';

//     try {
//         const res = await fetch('{{ route("staff.patients.store") }}', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
//                 'Accept': 'application/json',
//             },
//             body: JSON.stringify({
//                 nama, tanggal_lahir: tgl,
//                 jenis_kelamin: jk, telepon,
//                 sudah_konfirmasi: 1,
//                 _ajax: 1
//             })
//         });

//         const data = await res.json();

//         if (data.success) {
//             selectPatient(data.patient.id, data.patient.nama, data.patient.no_rm);
//             status.innerHTML = `<div class="alert alert-success py-1 mt-1" style="font-size:12px;">
//                 <i class="bi bi-check-circle-fill me-1"></i>
//                 Pasien terdaftar! No. RM: <strong>${data.patient.no_rm}</strong>
//             </div>`;
//             switchPatientTab('cari');
//         } else {
//             status.innerHTML = `<div class="alert alert-danger py-1 mt-1" style="font-size:12px;">${data.message}</div>`;
//         }
//     } catch (e) {
//         status.innerHTML = '<div class="alert alert-danger py-1 mt-1" style="font-size:12px;">Gagal mendaftarkan pasien.</div>';
//     }
// }
// let items     = {};
// let subtotal  = 0;



let items    = {};
let subtotal = 0;

function fmt(n) {
    return 'Rp ' + parseInt(n).toLocaleString('id-ID');
}

function addItem(type, id, nama, harga, checkbox) {
    const key = type + '_' + id;

    if (checkbox.checked) {
        items[key] = { type, id, nama, harga: parseFloat(harga), qty: 1 };
    } else {
        delete items[key];
    }

    renderItems();
}

function changeQty(key, qty) {
    if (items[key]) {
        items[key].qty = Math.max(1, parseInt(qty) || 1);
        renderItems();
    }
}

function removeItem(key) {
    const item = items[key];
    if (item) {
        const prefix = item.type === 'service' ? 'svc'
                     : item.type === 'package'  ? 'pkg' : 'prod';
        const cb = document.getElementById(prefix + item.id);
        if (cb) cb.checked = false;
    }
    delete items[key];
    renderItems();
}

function renderItems() {
    const list      = document.getElementById('items-list');
    const hidden    = document.getElementById('hidden-items');
    const submitBtn = document.getElementById('submit-btn');
    const keys      = Object.keys(items);

    // Cek pasien sudah dipilih
    const patientInput = document.getElementById('patient-id-input');
    const patientVal   = patientInput ? patientInput.value : '{{ $patient?->id ?? "" }}';
    const hasPasien    = !!patientVal;

    if (keys.length === 0) {
        list.innerHTML = `
            <div class="text-center text-muted py-4" style="font-size:13px;">
                <i class="bi bi-cart d-block fs-2 mb-2 opacity-25"></i>
                Belum ada item dipilih
            </div>`;
        hidden.innerHTML = '';
        subtotal = 0;
        updateTotal();
        submitBtn.disabled = true;
        return;
    }

    // Ada item — aktifkan tombol jika pasien juga sudah dipilih
    submitBtn.disabled = !hasPasien;

    subtotal = 0;
    let html  = '';
    let hInput = '';

    keys.forEach((key, i) => {
        const item = items[key];
        const sub  = item.harga * item.qty;
        subtotal  += sub;

        const hargaStr = item.harga > 0 ? fmt(item.harga) : 'Gratis';

        html += `
        <div class="d-flex align-items-start gap-2 mb-2 pb-2
                    ${i < keys.length - 1 ? 'border-bottom' : ''}">
            <div class="flex-grow-1">
                <div style="font-size:13px;font-weight:600;">${item.nama}</div>
                <div style="font-size:11px;color:${item.harga > 0 ? 'var(--orange)' : '#2E7D32'};">
                    ${hargaStr}
                    × <input type="number" min="1" value="${item.qty}"
                        onchange="changeQty('${key}', this.value)"
                        style="width:50px;border:1px solid #ddd;border-radius:4px;
                               padding:1px 4px;font-size:11px;text-align:center;">
                </div>
            </div>
            <div class="text-end">
                <div style="font-size:13px;font-weight:700;
                            color:${item.harga > 0 ? 'var(--hijau)' : '#2E7D32'};">
                    ${item.harga > 0 ? fmt(sub) : 'Gratis'}
                </div>
                <button type="button" onclick="removeItem('${key}')"
                        style="background:none;border:none;color:#e53935;
                               font-size:12px;cursor:pointer;padding:0;">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>
        </div>`;

        hInput += `
        <input type="hidden" name="items[${i}][type]"  value="${item.type}">
        <input type="hidden" name="items[${i}][id]"    value="${item.id}">
        <input type="hidden" name="items[${i}][qty]"   value="${item.qty}">
        <input type="hidden" name="items[${i}][harga]" value="${item.harga}">`;
    });

    list.innerHTML   = html;
    hidden.innerHTML = hInput;
    updateTotal();
}

function updateTotal() {
    const diskon  = parseFloat(document.getElementById('diskon-input').value) || 0;
    const total   = Math.max(0, subtotal - diskon);
    document.getElementById('display-subtotal').textContent = fmt(subtotal);
    document.getElementById('display-total').textContent    = fmt(total);
}

// Update tombol submit saat pasien dipilih
function selectPatient(id, nama, noRm) {
    document.getElementById('patient-id-input').value = id;
    document.getElementById('patient-results').innerHTML = '';
    document.getElementById('btn-pasien-baru-wrapper').style.display = 'none';
    if (patientSearch) patientSearch.value = '';

    document.getElementById('patient-selected').innerHTML = `
        <div class="d-flex align-items-center gap-3 p-2 rounded mt-2"
             style="background:#e8f5e9;">
            <div style="width:40px;height:40px;border-radius:50%;background:var(--hijau);
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-weight:700;font-size:16px;">
                ${nama.charAt(0).toUpperCase()}
            </div>
            <div>
                <div class="fw-bold">${nama}</div>
                <code style="font-size:11px;background:#c8e6c9;padding:1px 6px;
                             border-radius:3px;color:var(--hijau);">${noRm}</code>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary ms-auto"
                    onclick="gantiPasien()">Ganti</button>
        </div>`;

    renderItems(); // update status tombol submit setelah pasien dipilih
}

function gantiPasien() {
    document.getElementById('patient-id-input').value = '';
    document.getElementById('patient-selected').innerHTML = '';
    if (patientSearch) patientSearch.value = '';
    renderItems();
}

// Autocomplete cari pasien
const patientSearch = document.getElementById('patient-search');
if (patientSearch) {
    let timer;
    patientSearch.addEventListener('input', function() {
        clearTimeout(timer);
        const q = this.value.trim();

        if (q.length < 2) {
            document.getElementById('patient-results').innerHTML = '';
            const btnBaru = document.getElementById('btn-pasien-baru-wrapper');
            if (btnBaru) btnBaru.style.display = 'none';
            return;
        }

        timer = setTimeout(async () => {
            const res  = await fetch(`{{ route('staff.patients.search') }}?q=` + encodeURIComponent(q));
            const data = await res.json();
            const box  = document.getElementById('patient-results');
            const btnBaru = document.getElementById('btn-pasien-baru-wrapper');

            if (data.length === 0) {
                box.innerHTML = '';
                if (btnBaru) btnBaru.style.display = 'block';
                return;
            }

            if (btnBaru) btnBaru.style.display = 'none';

            let html = '<div class="list-group mt-1">';
            data.forEach(p => {
                const tlp = p.telepon ? ` · ${p.telepon}` : '';
                html += `
                <button type="button"
                        class="list-group-item list-group-item-action py-2"
                        onclick="selectPatient(${p.id}, '${p.nama.replace(/'/g, "\\'")}', '${p.no_rm}')">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px;height:32px;border-radius:50%;background:#e3f2fd;
                                    display:flex;align-items:center;justify-content:center;
                                    font-weight:700;font-size:13px;color:var(--biru-tua);
                                    flex-shrink:0;">
                            ${p.nama.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:13px;">${p.nama}</div>
                            <span class="badge bg-success-subtle text-success"
                                  style="font-size:10px;">${p.no_rm}</span>
                            <span class="text-muted" style="font-size:11px;">${tlp}</span>
                        </div>
                    </div>
                </button>`;
            });
            html += '</div>';
            box.innerHTML = html;
        }, 300);
    });
}

</script>
@endpush