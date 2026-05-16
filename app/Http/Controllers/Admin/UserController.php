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
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name'     => 'required|string|max:100',
    //         'username' => 'required|string|max:50|unique:users|alpha_dash',
    //         'email'    => 'nullable|email|unique:users',
    //         'password' => 'required|string|min:6',
    //         'role'     => 'required|in:guru,siswa',
    //         'jabatan'  => 'nullable|string|max:100',
    //         'barcode'  => 'nullable|string|unique:users',
    //     ], [
    //         'name.required'     => 'Nama wajib diisi.',
    //         'username.required' => 'Username wajib diisi.',
    //         'username.unique'   => 'Username sudah digunakan.',
    //         'username.alpha_dash' => 'Username hanya boleh huruf, angka, - dan _.',
    //         'password.min'      => 'Password minimal 6 karakter.',
    //         'role.required'     => 'Role wajib dipilih.',
    //     ]);

    //     $user = User::create([
    //         'name'                 => $request->name,
    //         'username'             => strtolower($request->username),
    //         'email'                => $request->email,
    //         'password'             => Hash::make($request->password),
    //         'role'                 => $request->role,
    //         'jabatan'              => $request->jabatan,
    //         'barcode'              => $request->barcode,
    //         'is_active'            => true,
    //         'must_change_password' => $request->role === 'siswa',
    //     ]);

    //     ActivityLog::create([
    //         'user_id'     => auth()->id(),
    //         'action'      => 'create_user',
    //         'description' => 'Membuat akun baru: ' . $user->name . ' (' . $user->role . ')',
    //         'ip_address'  => $request->ip(),
    //     ]);

    //     return redirect()->route('admin.users.index')
    //         ->with('success', 'Akun ' . $user->name . ' berhasil dibuat!');
    // }

    public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:100',
        'username' => 'required_if:role,guru|nullable|string|max:50|alpha_dash|unique:users',
        'password' => 'required|string|min:5',
        'role'     => 'required|in:guru,siswa',
        'jabatan'  => 'nullable|string|max:100',
        'barcode'  => 'required_if:role,siswa|nullable|string|unique:users',
    ], [
        'name.required'       => 'Nama wajib diisi.',
        'username.required_if'=> 'Username wajib diisi untuk Guru.',
        'username.unique'     => 'Username sudah digunakan.',
        'username.alpha_dash' => 'Username hanya boleh huruf, angka, - dan _.',
        'password.required'   => 'Password wajib diisi.',
        'password.min'        => 'Password minimal 5 karakter.',
        'role.required'       => 'Role wajib dipilih.',
        'barcode.required_if' => 'Barcode wajib diisi untuk Siswa.',
        'barcode.unique'      => 'Barcode sudah digunakan.',
    ]);

    $user = User::create([
        'name'                 => $request->name,
        'username'             => $request->role === 'guru'
                                  ? strtolower($request->username)
                                  : strtolower($request->barcode), // siswa pakai barcode sbg username
        'email'                => null,
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
    // public function update(Request $request, User $user)
    // {
    //     if ($user->role === 'admin') abort(403);

    //     $request->validate([
    //         'name'     => 'required|string|max:100',
    //         'username' => ['required','string','max:50','alpha_dash',
    //                        Rule::unique('users')->ignore($user->id)],
    //         'email'    => ['nullable','email',
    //                        Rule::unique('users')->ignore($user->id)],
    //         'role'     => 'required|in:guru,siswa',
    //         'jabatan'  => 'nullable|string|max:100',
    //         'barcode'  => ['nullable','string',
    //                        Rule::unique('users')->ignore($user->id)],
    //         'password' => 'nullable|string|min:6',
    //     ]);

    //     $data = [
    //         'name'      => $request->name,
    //         'username'  => strtolower($request->username),
    //         'email'     => $request->email,
    //         'role'      => $request->role,
    //         'jabatan'   => $request->jabatan,
    //         'barcode'   => $request->barcode,
    //         'is_active' => $request->boolean('is_active', true),
    //     ];

    //     // Hanya update password jika diisi
    //     if ($request->filled('password')) {
    //         $data['password']             = Hash::make($request->password);
    //         $data['must_change_password'] = false;
    //     }

    //     $user->update($data);

    //     ActivityLog::create([
    //         'user_id'     => auth()->id(),
    //         'action'      => 'update_user',
    //         'description' => 'Mengupdate akun: ' . $user->name,
    //         'ip_address'  => $request->ip(),
    //     ]);

    //     return redirect()->route('admin.users.index')
    //         ->with('success', 'Akun ' . $user->name . ' berhasil diperbarui!');
    // }

    public function update(Request $request, User $user)
{
    if ($user->role === 'admin') abort(403);

    $request->validate([
        'name'     => 'required|string|max:100',
        'username' => ['nullable','string','max:50','alpha_dash',
                       Rule::unique('users')->ignore($user->id)],
        'password' => 'nullable|string|min:5',
        'role'     => 'required|in:guru,siswa',
        'jabatan'  => 'nullable|string|max:100',
        'barcode'  => ['nullable','string',
                       Rule::unique('users')->ignore($user->id)],
    ], [
        'password.min' => 'Password minimal 5 karakter.',
    ]);

    $data = [
        'name'      => $request->name,
        'username'  => $request->role === 'guru'
                       ? strtolower($request->username)
                       : ($request->barcode ? strtolower($request->barcode) : $user->username),
        'email'     => null,
        'role'      => $request->role,
        'jabatan'   => $request->jabatan,
        'barcode'   => $request->barcode,
        'is_active' => $request->boolean('is_active', true),
    ];

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
    public function export(Request $request)
{
    $query = User::where('role', '!=', 'admin');

    // Ikuti filter yang aktif
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('username', 'like', '%' . $request->search . '%')
              ->orWhere('jabatan', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    $users = $query->orderBy('role')->orderBy('name')->get();

    // Tentukan nama file dan judul sheet
    $roleLabel = match($request->role) {
        'guru'  => 'Guru',
        'siswa' => 'Siswa',
        default => 'Guru_dan_Siswa',
    };

    $format   = $request->get('format', 'xlsx');
    $filename = 'Data_Staf_' . $roleLabel . '_' . date('Ymd_His');

    if ($format === 'csv') {
        return $this->exportCsv($users, $filename, $request->role);
    }

    return $this->exportXlsx($users, $filename, $roleLabel);
}

private function exportXlsx($users, string $filename, string $roleLabel)
{
    // Buat file Excel manual dengan PhpSpreadsheet via DomPDF tidak tersedia
    // Gunakan maatwebsite/excel yang sudah terinstall
    $data = $this->buildExportData($users);

    // Buat response Excel sederhana menggunakan output buffer
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data Staf ' . $roleLabel);

    // Style header
    $headerStyle = [
        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1565C0']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']]],
    ];

    // Tulis header
    $headers = $data[0];
    foreach ($headers as $col => $header) {
        $cell = chr(65 + $col) . '1';
        $sheet->setCellValue($cell, $header);
    }
    $sheet->getStyle('A1:' . chr(64 + count($headers)) . '1')->applyFromArray($headerStyle);

    // Tulis data
    foreach (array_slice($data, 1) as $rowIdx => $row) {
        $excelRow = $rowIdx + 2;
        foreach ($row as $col => $value) {
            $sheet->setCellValue(chr(65 + $col) . $excelRow, $value);
        }
        // Warna zebra
        if ($rowIdx % 2 === 1) {
            $sheet->getStyle('A' . $excelRow . ':' . chr(64 + count($headers)) . $excelRow)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('F5F5F5');
        }
    }

    // Auto width kolom
    foreach (range('A', chr(64 + count($headers))) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Output
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $tmpFile = tempnam(sys_get_temp_dir(), 'excel_');
    $writer->save($tmpFile);

    return response()->download($tmpFile, $filename . '.xlsx', [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ])->deleteFileAfterSend(true);
}

private function exportCsv($users, string $filename)
{
    $data = $this->buildExportData($users);

    $callback = function() use ($data) {
        $file = fopen('php://output', 'w');
        // BOM untuk Excel agar UTF-8 terbaca dengan benar
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        foreach ($data as $row) {
            fputcsv($file, $row, ',');
        }
        fclose($file);
    };

    return response()->stream($callback, 200, [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
    ]);
}

private function buildExportData($users): array
{
    $rows = [];

    // Header
    $rows[] = [
        'No', 'Nama Lengkap', 'Username', 'Role',
        'Jabatan / Kelas', 'Barcode QR', 'Status',
        'Login Terakhir', 'Terdaftar'
    ];

    // Data
    foreach ($users as $i => $user) {
        $rows[] = [
            $i + 1,
            $user->name,
            $user->username ?? '-',
            ucfirst($user->role),
            $user->jabatan ?? '-',
            $user->barcode ?? '-',
            $user->is_active ? 'Aktif' : 'Nonaktif',
            $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Belum pernah',
            $user->created_at->format('d/m/Y'),
        ];
    }

    return $rows;
}
}