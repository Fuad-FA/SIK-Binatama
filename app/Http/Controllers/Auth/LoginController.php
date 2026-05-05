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
    // Tampilkan halaman login
    public function showForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    // Proses login username + password (Admin & Guru)
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cari user berdasarkan username atau email
        $user = User::where('username', $request->username)
                    ->orWhere('email', $request->username)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        if (!$user->is_active) {
            return back()->withInput($request->only('username'))
                ->with('error', 'Akun Anda dinonaktifkan. Hubungi administrator.');
        }

        Auth::login($user, $request->boolean('remember'));

        // Update last login
        $user->update(['last_login' => now()]);

        // Catat activity log
        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'login',
            'description' => 'Login berhasil dari IP ' . $request->ip(),
            'ip_address'  => $request->ip(),
        ]);

        return $this->redirectByRole($user->role);
    }

    // Proses login QR Code (Siswa)
    public function loginQR(Request $request)
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
            'action'      => 'login_qr',
            'description' => 'Login via QR Code dari IP ' . $request->ip(),
            'ip_address'  => $request->ip(),
        ]);

        return $this->redirectByRole($user->role);
    }

    // Logout
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

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }

    // Halaman ganti password (wajib untuk siswa pertama login)
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    // Proses ganti password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        Auth::user()->update([
            'password'             => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('staff.dashboard')
            ->with('success', 'Password berhasil diubah!');
    }

    // Arahkan user ke dashboard sesuai role
    private function redirectByRole(string $role)
    {
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('staff.dashboard'),
        };
    }
}