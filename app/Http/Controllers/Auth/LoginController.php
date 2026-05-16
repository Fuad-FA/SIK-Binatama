<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // ============================================================
    // ADMIN LOGIN
    // ============================================================
    public function showAdminForm()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login-admin');
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where(function($q) use ($request) {
            $q->where('username', $request->username)
              ->orWhere('email', $request->username);
        })->where('role', 'admin')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        if (!$user->is_active) {
            return back()->with('error', 'Akun Anda dinonaktifkan.');
        }

        Auth::login($user, $request->boolean('remember'));
        $user->update(['last_login' => now()]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'login_admin',
            'description' => 'Admin login dari IP ' . $request->ip(),
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.dashboard');
    }

    // ============================================================
    // GURU LOGIN
    // ============================================================
    public function showGuruForm()
    {
        if (Auth::check() && Auth::user()->role === 'guru') {
            return redirect()->route('staff.dashboard');
        }
        return view('auth.login-guru');
    }

    public function loginGuru(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where(function($q) use ($request) {
            $q->where('username', $request->username)
              ->orWhere('email', $request->username);
        })->where('role', 'guru')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        if (!$user->is_active) {
            return back()->with('error', 'Akun Anda dinonaktifkan. Hubungi administrator.');
        }

        Auth::login($user, $request->boolean('remember'));
        $user->update(['last_login' => now()]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'login_guru',
            'description' => 'Guru login dari IP ' . $request->ip(),
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('staff.dashboard');
    }

    // ============================================================
    // SISWA LOGIN
    // ============================================================
    public function showSiswaForm()
    {
        if (Auth::check() && Auth::user()->role === 'siswa') {
            return redirect()->route('staff.dashboard');
        }
        return view('auth.login-siswa');
    }

    public function loginSiswa(Request $request)
    {
        $request->validate([
            'barcode'  => 'required|string',
            'password' => 'required|string',
        ], [
            'barcode.required'  => 'Barcode wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('barcode', $request->barcode)
                    ->where('role', 'siswa')
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Barcode atau password salah.');
        }

        if (!$user->is_active) {
            return back()->with('error', 'Akun Anda dinonaktifkan. Hubungi administrator.');
        }

        Auth::login($user);
        $user->update(['last_login' => now()]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'login_siswa',
            'description' => 'Siswa login via QR dari IP ' . $request->ip(),
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('staff.dashboard');
    }

    // ============================================================
    // LOGOUT
    // ============================================================
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            ActivityLog::create([
                'user_id'     => $user->id,
                'action'      => 'logout',
                'description' => 'Logout dari sistem',
                'ip_address'  => $request->ip(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login sesuai role terakhir
        $role = $user?->role ?? 'admin';
        return match($role) {
            'admin'  => redirect()->route('login.admin')->with('success', 'Anda berhasil logout.'),
            'guru'   => redirect()->route('login.guru')->with('success', 'Anda berhasil logout.'),
            'siswa'  => redirect()->route('login.siswa')->with('success', 'Anda berhasil logout.'),
            default  => redirect()->route('login.admin'),
        };
    }

    // ============================================================
    // GANTI PASSWORD (Siswa wajib ganti saat pertama login)
    // ============================================================
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    // public function updatePassword(Request $request)
    // {
    //     $request->validate([
    //         'password' => 'required|string|min:6|confirmed',
    //     ], [
    //         'password.required'  => 'Password baru wajib diisi.',
    //         'password.min'       => 'Password minimal 6 karakter.',
    //         'password.confirmed' => 'Konfirmasi password tidak cocok.',
    //     ]);

    //     Auth::user()->update([
    //         'password'             => Hash::make($request->password),
    //         'must_change_password' => false,
    //     ]);

    //     return redirect()->route('staff.dashboard')
    //         ->with('success', 'Password berhasil diubah!');
    // }
    public function updatePassword(Request $request)
{
    $request->validate([
        'password' => 'required|string|min:6|confirmed',
    ], [
        'password.required'  => 'Password baru wajib diisi.',
        'password.min'       => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    $user = Auth::user();

    $user->update([
        'password'             => Hash::make($request->password),
        'must_change_password' => false,
    ]);

    // Refresh session
    $request->session()->regenerate();

    return redirect()
        ->route('staff.dashboard')
        ->with('success', 'Password berhasil diubah!');
}
}