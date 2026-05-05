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
    <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
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
            @foreach($packages as $pkg)
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
            @endforeach

            <hr class="my-2">

            {{-- Layanan Satuan --}}
            @foreach($services as $svc)
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
let items     = {};
let subtotal  = 0;

function fmt(n) {
    return 'Rp ' + parseInt(n).toLocaleString('id-ID');
}

function addItem(type, id, nama, harga, checkbox) {
    const key = type + '_' + id;

    if (checkbox.checked) {
        items[key] = { type, id, nama, harga, qty: 1 };
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

function renderItems() {
    const list    = document.getElementById('items-list');
    const hidden  = document.getElementById('hidden-items');
    const emptyMsg = document.getElementById('empty-msg');
    const submitBtn = document.getElementById('submit-btn');

    const keys = Object.keys(items);

    if (keys.length === 0) {
        list.innerHTML = `<div id="empty-msg" class="text-center text-muted py-4" style="font-size:13px;">
            <i class="bi bi-cart d-block fs-2 mb-2 opacity-25"></i>Belum ada item dipilih</div>`;
        hidden.innerHTML = '';
        subtotal = 0;
        updateTotal();
        submitBtn.disabled = true;
        return;
    }

    submitBtn.disabled = false;
    subtotal = 0;
    let html   = '';
    let hInput = '';

    keys.forEach((key, i) => {
        const item = items[key];
        const sub  = item.harga * item.qty;
        subtotal  += sub;

        html += `
        <div class="d-flex align-items-start gap-2 mb-2 pb-2
                    ${i < keys.length-1 ? 'border-bottom' : ''}">
            <div class="flex-grow-1">
                <div style="font-size:13px;font-weight:600;">${item.nama}</div>
                <div style="font-size:11px;color:var(--orange);">
                    ${fmt(item.harga)} × <input type="number" min="1" value="${item.qty}"
                        onchange="changeQty('${key}', this.value)"
                        style="width:50px;border:1px solid #ddd;border-radius:4px;
                               padding:1px 4px;font-size:11px;text-align:center;">
                </div>
            </div>
            <div class="text-end">
                <div style="font-size:13px;font-weight:700;color:var(--hijau);">
                    ${fmt(sub)}
                </div>
                <button type="button" onclick="removeItem('${key}')"
                        style="background:none;border:none;color:#e53935;font-size:12px;
                               cursor:pointer;padding:0;">
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

function removeItem(key) {
    // Uncheck checkbox
    const item = items[key];
    if (item) {
        const cbId = item.type === 'service' ? 'svc' : (item.type === 'package' ? 'pkg' : 'prod');
        const cb = document.getElementById(cbId + item.id);
        if (cb) cb.checked = false;
    }
    delete items[key];
    renderItems();
}

function updateTotal() {
    const diskon = parseFloat(document.getElementById('diskon-input').value) || 0;
    const total  = Math.max(0, subtotal - diskon);
    document.getElementById('display-subtotal').textContent = fmt(subtotal);
    document.getElementById('display-total').textContent    = fmt(total);
}

// Cari pasien (jika tidak ada patient dari URL)
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
    document.getElementById('patient-id-input').value = id;
    document.getElementById('patient-results').innerHTML = '';
    document.getElementById('patient-selected').innerHTML = `
        <div class="alert alert-success py-2 mt-2" style="font-size:13px;">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>${nama}</strong>
            <span class="badge bg-success ms-2">${noRm}</span>
        </div>`;
    if (patientSearch) patientSearch.value = nama;
}
</script>
@endpush