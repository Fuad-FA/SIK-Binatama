{{-- <div class="nav-section">Menu Utama</div>
<a href="{{ route('admin.dashboard') }}"
   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<div class="nav-section">Data</div>
<a href="{{ route('staff.patients.index') }}"
   class="nav-link {{ request()->routeIs('staff.patients*') ? 'active' : '' }}">
    <i class="bi bi-person-lines-fill"></i> Data Pasien
</a>

<div class="nav-section">Master Data</div>
<a href="{{ route('admin.users.index') }}"
   class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
    <i class="bi bi-people-fill"></i> Manajemen User
</a>
<a href="{{ route('admin.import') }}"
   class="nav-link {{ request()->routeIs('admin.import*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-arrow-up-fill"></i> Import Data Staf
</a>
<a href="{{ route('admin.products.index') }}"
   class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
    <i class="bi bi-box-seam-fill"></i> Produk
</a>
<a href="{{ route('admin.services.index') }}"
   class="nav-link {{ request()->routeIs('admin.services*') || request()->routeIs('admin.packages*') ? 'active' : '' }}">
    <i class="bi bi-heart-pulse-fill"></i> Layanan & Paket
</a>


<div class="nav-section">Laporan</div>
<a href="{{ route('admin.reports') }}"
   class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-fill"></i> Laporan Bulanan
</a>

<a href="{{ route('admin.activity-log') }}"
   class="nav-link {{ request()->routeIs('admin.activity-log') ? 'active' : '' }}">
    <i class="bi bi-clock-history"></i> Riwayat Aktivitas
</a> --}}

<div class="nav-section">Menu Utama</div>
<a href="{{ route('admin.dashboard') }}"
   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<div class="nav-section">Data</div>
<a href="{{ route('staff.patients.index') }}"
   class="nav-link {{ request()->routeIs('staff.patients*') ? 'active' : '' }}">
    <i class="bi bi-person-lines-fill"></i> Data Pasien
</a>

<div class="nav-section">Master Data</div>
<a href="{{ route('admin.users.index') }}"
   class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
    <i class="bi bi-people-fill"></i> Manajemen User
</a>
<a href="{{ route('admin.import') }}"
   class="nav-link {{ request()->routeIs('admin.import*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-arrow-up-fill"></i> Import Data Staf
</a>
<a href="{{ route('admin.products.index') }}"
   class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
    <i class="bi bi-box-seam-fill"></i> Produk
</a>
<a href="{{ route('admin.services.index') }}"
   class="nav-link {{ request()->routeIs('admin.services*') || request()->routeIs('admin.packages*') ? 'active' : '' }}">
    <i class="bi bi-heart-pulse-fill"></i> Layanan & Paket
</a>

<div class="nav-section">Laporan</div>
<a href="{{ route('admin.reports') }}"
   class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-fill"></i> Laporan Bulanan
</a>
<a href="{{ route('admin.activity-log') }}"
   class="nav-link {{ request()->routeIs('admin.activity-log*') ? 'active' : '' }}">
    <i class="bi bi-clock-history"></i> Riwayat Aktivitas
</a>