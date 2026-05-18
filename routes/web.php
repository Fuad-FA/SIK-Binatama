<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;



// ============================================================
// LANDING PAGES
// ============================================================
Route::get('/sik-admin-secure', function() {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('landing.admin');
})->name('landing.admin');

Route::get('/staff', function() {
    if (auth()->check() && auth()->user()->role === 'guru') {
        return redirect()->route('staff.dashboard');
    }
    return view('landing.guru');
})->name('landing.guru');

Route::get('/student', function() {
    if (auth()->check() && auth()->user()->role === 'siswa') {
        return redirect()->route('staff.dashboard');
    }
    return view('landing.siswa');
})->name('landing.siswa');
// ============================================================
// PUBLIK — Halaman Login
// ============================================================
// Route::get('/', fn() => redirect()->route('login'));

// Route::get('/login', [LoginController::class, 'showForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login'])->name('login.post');
// Route::post('/login/qrcode', [LoginController::class, 'loginQR'])->name('login.qr');
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Route::get('/ganti-password', [LoginController::class, 'showChangePassword'])
//     ->name('password.change')->middleware('auth');
// Route::post('/ganti-password', [LoginController::class, 'updatePassword'])
//     ->name('password.update')->middleware('auth');

// Root redirect ke 404
Route::get('/', fn() => abort(404));

// URL lama /login -> 404
Route::get('/login', fn() => abort(404));

// ============================================================
// LOGIN ADMIN
// ============================================================
Route::get('/sik-admin-secure/login',
    [LoginController::class, 'showAdminForm'])->name('login.admin');
Route::post('/sik-admin-secure/login',
    [LoginController::class, 'loginAdmin'])->name('login.admin.post');

// ============================================================
// LOGIN GURU
// ============================================================
Route::get('/staff/login',
    [LoginController::class, 'showGuruForm'])->name('login.guru');
Route::post('/staff/login',
    [LoginController::class, 'loginGuru'])->name('login.guru.post');

// ============================================================
// LOGIN SISWA
// ============================================================
Route::get('/student/auth',
    [LoginController::class, 'showSiswaForm'])->name('login.siswa');
Route::post('/student/auth',
    [LoginController::class, 'loginSiswa'])->name('login.siswa.post');

// ============================================================
// LOGOUT & GANTI PASSWORD
// ============================================================
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')->middleware('auth');

Route::get('/ganti-password', [LoginController::class, 'showChangePassword'])
    ->name('password.change')->middleware('auth');
Route::post('/ganti-password', [LoginController::class, 'updatePassword'])
    ->name('password.update')->middleware('auth');

// Default Laravel auth route name (dibutuhkan middleware)
Route::get('/auth', fn() => abort(404))->name('login');

// ============================================================
// ADMIN
// ============================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('reports');

         // Export data staf
        Route::get('/users/export', [\App\Http\Controllers\Admin\UserController::class, 'export'])
            ->name('users.export');
            
        // Manajemen User
        Route::resource('/users', \App\Http\Controllers\Admin\UserController::class);
        Route::post('/users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])
            ->name('users.toggle-active');
        Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])
            ->name('users.reset-password');

            

        // Produk
        Route::resource('/products', \App\Http\Controllers\Admin\ProductController::class);
        Route::post('/products/{product}/toggle-active', [\App\Http\Controllers\Admin\ProductController::class, 'toggleActive'])
            ->name('products.toggle-active');
        Route::post('/products/{product}/stok', [\App\Http\Controllers\Admin\ProductController::class, 'updateStok'])
            ->name('products.stok');

        // Layanan
        Route::resource('/services', \App\Http\Controllers\Admin\ServiceController::class)->except(['show']);
        Route::post('/services/{service}/toggle-active', [\App\Http\Controllers\Admin\ServiceController::class, 'toggleActive'])
            ->name('services.toggle-active');

        // Paket
        Route::resource('/packages', \App\Http\Controllers\Admin\PackageController::class)->except(['show']);
        Route::post('/packages/{package}/toggle-active', [\App\Http\Controllers\Admin\PackageController::class, 'toggleActive'])
            ->name('packages.toggle-active');

            // Import data staf
        Route::get('/import', [\App\Http\Controllers\Admin\ImportController::class, 'showForm'])
            ->name('import');
        Route::post('/import', [\App\Http\Controllers\Admin\ImportController::class, 'import'])
            ->name('import.post');

            Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])
            ->name('reports.export');


            Route::post('/users/{user}/unlock', [\App\Http\Controllers\Admin\UserController::class, 'unlock'])
            ->name('users.unlock');

            Route::get('/activity-log', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])
            ->name('activity-log');
    });

// ============================================================
// STAF (ADMIN + GURU + SISWA)
// ============================================================
Route::middleware(['auth', 'role:admin,guru,siswa', 'check.first.login'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])
            ->name('dashboard');

        // Pasien
        Route::resource('/patients', \App\Http\Controllers\Staff\PatientController::class);
        Route::get('/patients-search', [\App\Http\Controllers\Staff\PatientController::class, 'search'])
            ->name('patients.search');

        // Rekam Medis
        Route::resource('/medical-records', \App\Http\Controllers\Staff\MedicalRecordController::class)
            ->except(['edit', 'update', 'destroy']);

        // Transaksi
        Route::resource('/transactions', \App\Http\Controllers\Staff\TransactionController::class)
            ->except(['edit', 'update', 'destroy']);
        Route::get('/transactions/{transaction}/nota', [\App\Http\Controllers\Staff\TransactionController::class, 'nota'])
            ->name('transactions.nota');
           
    });

// ============================================================
// PORTAL PASIEN
// ============================================================
// Route::prefix('portal')->name('patient.')->group(function () {
//     Route::get('/', [\App\Http\Controllers\Patient\PortalController::class, 'showLogin'])
//         ->name('login');
//     Route::post('/login', [\App\Http\Controllers\Patient\PortalController::class, 'login'])
//         ->name('login.post');
//     Route::post('/logout', [\App\Http\Controllers\Patient\PortalController::class, 'logout'])
//         ->name('logout')->middleware('patient.auth');

//     Route::middleware('patient.auth')->group(function () {
//         Route::get('/dashboard', [\App\Http\Controllers\Patient\PortalController::class, 'dashboard'])
//             ->name('dashboard');
//         Route::get('/riwayat', [\App\Http\Controllers\Patient\PortalController::class, 'records'])
//             ->name('records');
//     });
// });
// ============================================================
// PORTAL PASIEN
// ============================================================

// Landing pasien
Route::get('/portal', function () {
    if (session()->has('patient_id')) {
        return redirect()->route('patient.dashboard');
    }

    return view('landing.pasien');
})->name('landing.pasien');

// Login pasien
Route::get('/portal/login',
    [\App\Http\Controllers\Patient\PortalController::class, 'showLogin'])
    ->name('patient.login');

Route::post('/portal/login',
    [\App\Http\Controllers\Patient\PortalController::class, 'login'])
    ->name('patient.login.post');

// Logout pasien
Route::post('/portal/logout',
    [\App\Http\Controllers\Patient\PortalController::class, 'logout'])
    ->name('patient.logout')
    ->middleware('patient.auth');

// Area pasien setelah login
Route::middleware('patient.auth')->prefix('portal')->name('patient.')->group(function () {

    Route::get('/dashboard',
        [\App\Http\Controllers\Patient\PortalController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/riwayat',
        [\App\Http\Controllers\Patient\PortalController::class, 'records'])
        ->name('records');
});