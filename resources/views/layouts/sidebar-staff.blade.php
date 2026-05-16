{{-- <div class="nav-section">Menu Utama</div>
<a href="{{ route('staff.dashboard') }}"
   class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<div class="nav-section">Pelayanan</div>
<a href="#" class="nav-link {{ request()->routeIs('staff.patients*') ? 'active' : '' }}">
    <i class="bi bi-person-vcard-fill"></i> Data Pasien
</a>
<a href="#" class="nav-link {{ request()->routeIs('staff.medical*') ? 'active' : '' }}">
    <i class="bi bi-clipboard2-pulse-fill"></i> Pemeriksaan
</a>
<a href="#" class="nav-link {{ request()->routeIs('staff.transactions*') ? 'active' : '' }}">
    <i class="bi bi-cart-fill"></i> Transaksi
</a>

<div class="nav-section">Riwayat</div>
<a href="#" class="nav-link">
    <i class="bi bi-clock-history"></i> Riwayat Input Saya
</a> --}}
{{-- <div class="nav-section">Menu Utama</div>
<a href="{{ route('staff.dashboard') }}"
   class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<div class="nav-section">Pelayanan</div>
<a href="{{ route('staff.patients.index') }}"
   class="nav-link {{ request()->routeIs('staff.patients*') ? 'active' : '' }}">
    <i class="bi bi-person-vcard-fill"></i> Data Pasien
</a>
<a href="{{ route('staff.medical-records.create') }}"
   class="nav-link {{ request()->routeIs('staff.medical-records*') ? 'active' : '' }}">
    <i class="bi bi-clipboard2-pulse-fill"></i> Input Pemeriksaan
</a> --}}
{{-- <a href="#" class="nav-link {{ request()->routeIs('staff.transactions*') ? 'active' : '' }}">
    <i class="bi bi-cart-fill"></i> Transaksi
</a> --}}
{{-- <a href="{{ route('staff.transactions.index') }}"
   class="nav-link {{ request()->routeIs('staff.transactions*') ? 'active' : '' }}">
    <i class="bi bi-cart-fill"></i> Transaksi
</a>

<div class="nav-section">Riwayat</div>
<a href="#" class="nav-link">
    <i class="bi bi-clock-history"></i> Riwayat Input Saya
</a> --}}

{{-- <div class="nav-section">Menu Utama</div>
<a href="{{ route('staff.dashboard') }}"
   class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<div class="nav-section">Pelayanan</div>
<a href="{{ route('staff.patients.index') }}"
   class="nav-link {{ request()->routeIs('staff.patients*') ? 'active' : '' }}">
    <i class="bi bi-person-vcard-fill"></i> Data Pasien
</a> --}}
{{-- <a href="{{ route('staff.medical-records.create') }}"
   class="nav-link {{ request()->routeIs('staff.medical-records.create') ? 'active' : '' }}">
    <i class="bi bi-clipboard2-pulse-fill"></i> Input Pemeriksaan
</a> --}}
{{-- <a href="{{ route('staff.transactions.create') }}"
   class="nav-link {{ request()->routeIs('staff.transactions.create') ? 'active' : '' }}">
    <i class="bi bi-cart-plus-fill"></i> Transaksi Baru
</a> --}}

{{-- <a href="{{ route('staff.transactions.create') }}"
   class="nav-link {{ request()->routeIs('staff.transactions.create') ? 'active' : '' }}">
    <i class="bi bi-cart-plus-fill"></i> Transaksi & Pemeriksaan
</a>
<a href="{{ route('staff.transactions.index') }}"
   class="nav-link {{ request()->routeIs('staff.transactions.index') ? 'active' : '' }}">
    <i class="bi bi-receipt"></i> Riwayat Transaksi
</a> --}}

<div class="nav-section">Menu Utama</div>
<a href="{{ route('staff.dashboard') }}"
   class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<div class="nav-section">Pelayanan</div>
<a href="{{ route('staff.patients.index') }}"
   class="nav-link {{ request()->routeIs('staff.patients*') ? 'active' : '' }}">
    <i class="bi bi-person-vcard-fill"></i> Data Pasien
</a>
<a href="{{ route('staff.transactions.create') }}"
   class="nav-link {{ request()->routeIs('staff.transactions.create') ? 'active' : '' }}">
    <i class="bi bi-cart-plus-fill"></i> Transaksi & Pemeriksaan
</a>
<a href="{{ route('staff.transactions.index') }}"
   class="nav-link {{ request()->routeIs('staff.transactions.index') ? 'active' : '' }}">
    <i class="bi bi-receipt"></i> Riwayat Transaksi
</a>