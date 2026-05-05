<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Daftar semua user (staf)
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('jabatan', 'like', '%' . $request->search . '%');
            });
        }

        // Filter role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    // Form tambah user
    public function create()
    {
        return view('admin.users.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users|alpha_dash',
            'email'    => 'nullable|email|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:guru,siswa',
            'jabatan'  => 'nullable|string|max:100',
            'barcode'  => 'nullable|string|unique:users',
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, - dan _.',
            'password.min'      => 'Password minimal 6 karakter.',
            'role.required'     => 'Role wajib dipilih.',
        ]);

        $user = User::create([
            'name'                 => $request->name,
            'username'             => strtolower($request->username),
            'email'                => $request->email,
            'password'             => Hash::make($request->password),
            'role'                 => $request->role,
            'jabatan'              => $request->jabatan,
            'barcode'              => $request->barcode,
            'is_active'            => true,
            'must_change_password' => $request->role === 'siswa',
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'create_user',
            'description' => 'Membuat akun baru: ' . $user->name . ' (' . $user->role . ')',
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun ' . $user->name . ' berhasil dibuat!');
    }

    // Detail user (lihat password plaintext untuk admin)
    public function show(User $user)
    {
        if ($user->role === 'admin') abort(403);
        return view('admin.users.show', compact('user'));
    }

    // Form edit user
    public function edit(User $user)
    {
        if ($user->role === 'admin') abort(403);
        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        if ($user->role === 'admin') abort(403);

        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => ['required','string','max:50','alpha_dash',
                           Rule::unique('users')->ignore($user->id)],
            'email'    => ['nullable','email',
                           Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|in:guru,siswa',
            'jabatan'  => 'nullable|string|max:100',
            'barcode'  => ['nullable','string',
                           Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name'      => $request->name,
            'username'  => strtolower($request->username),
            'email'     => $request->email,
            'role'      => $request->role,
            'jabatan'   => $request->jabatan,
            'barcode'   => $request->barcode,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password']             = Hash::make($request->password);
            $data['must_change_password'] = false;
        }

        $user->update($data);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'update_user',
            'description' => 'Mengupdate akun: ' . $user->name,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun ' . $user->name . ' berhasil diperbarui!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        if ($user->role === 'admin') abort(403);

        $name = $user->name;
        $user->delete();

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'delete_user',
            'description' => 'Menghapus akun: ' . $name,
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun ' . $name . ' berhasil dihapus.');
    }

    // Toggle aktif/nonaktif
    public function toggleActive(User $user)
    {
        if ($user->role === 'admin') abort(403);

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', 'Akun ' . $user->name . ' berhasil ' . $status . '.');
    }

    // Reset password ke default
    public function resetPassword(User $user)
    {
        if ($user->role === 'admin') abort(403);

        $default = $user->role === 'siswa' ? 'siswa' : 'guru123';

        $user->update([
            'password'             => Hash::make($default),
            'must_change_password' => true,
        ]);

        return redirect()->back()
            ->with('success', 'Password ' . $user->name . ' direset ke "' . $default . '".');
    }
}