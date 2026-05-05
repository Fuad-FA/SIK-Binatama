<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// ============================================================
// PUBLIK — Halaman Login
// ============================================================
Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [LoginController::class, 'showForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post');

Route::post('/login/qrcode', [LoginController::class, 'loginQR'])
    ->name('login.qr');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Ganti password (wajib untuk siswa pertama kali login)
Route::get('/ganti-password', [LoginController::class, 'showChangePassword'])
    ->name('password.change')
    ->middleware('auth');

Route::post('/ganti-password', [LoginController::class, 'updatePassword'])
    ->name('password.update')
    ->middleware('auth');

// ============================================================
// ADMIN
// ============================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Route::get('/dashboard', function () {
        //     return view('admin.dashboard');
        // })->name('dashboard');

Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Laporan
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('reports');

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
        Route::resource('/services', \App\Http\Controllers\Admin\ServiceController::class)
            ->except(['show']);
        Route::post('/services/{service}/toggle-active', [\App\Http\Controllers\Admin\ServiceController::class, 'toggleActive'])
            ->name('services.toggle-active');

        // Paket
        Route::resource('/packages', \App\Http\Controllers\Admin\PackageController::class)
            ->except(['show']);
        Route::post('/packages/{package}/toggle-active', [\App\Http\Controllers\Admin\PackageController::class, 'toggleActive'])
            ->name('packages.toggle-active');
    });


// ============================================================
// STAF (GURU & SISWA)
// ============================================================
// Route::middleware(['auth', 'role:guru,siswa', 'check.first.login'])
//     ->prefix('staff')
//     ->name('staff.')
//     ->group(function () {
//         Route::get('/dashboard', function () {
//             return view('staff.dashboard');
//         })->name('dashboard');
//     });

// ============================================================
// STAF (GURU & SISWA)
// ============================================================
Route::middleware(['auth', 'role:guru,siswa', 'check.first.login'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('staff.dashboard');
        })->name('dashboard');

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
Route::prefix('portal')->name('patient.')->group(function () {

    // Login (publik)
    Route::get('/',       [\App\Http\Controllers\Patient\PortalController::class, 'showLogin'])
        ->name('login');
    Route::post('/login', [\App\Http\Controllers\Patient\PortalController::class, 'login'])
        ->name('login.post');
    Route::post('/logout',[\App\Http\Controllers\Patient\PortalController::class, 'logout'])
        ->name('logout')->middleware('patient.auth');

    // Dashboard & riwayat (butuh login pasien)
    Route::middleware('patient.auth')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Patient\PortalController::class, 'dashboard'])
            ->name('dashboard');
        Route::get('/riwayat',   [\App\Http\Controllers\Patient\PortalController::class, 'records'])
            ->name('records');
    });
});