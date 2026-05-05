<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with(['creator', 'medicalRecords', 'transactions']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('no_rm', 'like', '%' . $request->search . '%')
                  ->orWhere('telepon', 'like', '%' . $request->search . '%');
            });
        }

        // Staf hanya lihat pasien yang dia input
        if (auth()->user()->role !== 'admin') {
            $query->where('created_by', auth()->id());
        }

        $patients = $query->orderByDesc('created_at')->paginate(15);

        return view('staff.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('staff.patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'alamat'        => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date|before:today',
            'jenis_kelamin' => 'nullable|in:L,P',
            'telepon'       => 'nullable|string|max:20',
        ], [
            'nama.required'          => 'Nama pasien wajib diisi.',
            'tanggal_lahir.before'   => 'Tanggal lahir tidak valid.',
        ]);

        $patient = Patient::create([
            'nama'          => $request->nama,
            'alamat'        => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'telepon'       => $request->telepon,
            'created_by'    => auth()->id(),
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'create_patient',
            'description' => 'Input pasien baru: ' . $patient->nama . ' (' . $patient->no_rm . ')',
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('staff.patients.show', $patient)
            ->with('success', 'Pasien ' . $patient->nama . ' berhasil didaftarkan! No. RM: ' . $patient->no_rm);
    }

    public function show(Patient $patient)
    {
        // Staf hanya bisa lihat pasien miliknya
        if (auth()->user()->role !== 'admin' && $patient->created_by !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses data pasien ini.');
        }

        $patient->load(['medicalRecords.user', 'transactions.items', 'creator']);
        return view('staff.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        if (auth()->user()->role !== 'admin' && $patient->created_by !== auth()->id()) {
            abort(403);
        }
        return view('staff.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        if (auth()->user()->role !== 'admin' && $patient->created_by !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'nama'          => 'required|string|max:100',
            'alamat'        => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date|before:today',
            'jenis_kelamin' => 'nullable|in:L,P',
            'telepon'       => 'nullable|string|max:20',
        ]);

        $patient->update($request->only([
            'nama', 'alamat', 'tanggal_lahir', 'jenis_kelamin', 'telepon'
        ]));

        return redirect()->route('staff.patients.show', $patient)
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Patient $patient)
    {
        abort(403, 'Data pasien tidak dapat dihapus.');
    }

    // AJAX: cari pasien untuk autocomplete
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');

        $patients = Patient::where('nama', 'like', '%' . $keyword . '%')
            ->orWhere('no_rm', 'like', '%' . $keyword . '%')
            ->limit(10)
            ->get(['id', 'no_rm', 'nama', 'tanggal_lahir', 'jenis_kelamin', 'telepon']);

        return response()->json($patients);
    }
}