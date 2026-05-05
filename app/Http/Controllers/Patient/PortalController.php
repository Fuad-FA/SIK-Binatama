<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PortalController extends Controller
{
    // Halaman login pasien
    public function showLogin()
    {
        if (Session::has('patient_id')) {
            return redirect()->route('patient.dashboard');
        }
        return view('patient.login');
    }

    // Proses login pasien
    public function login(Request $request)
    {
        $request->validate([
            'no_rm'     => 'required|string',
            'kode_unik' => 'required|string',
        ], [
            'no_rm.required'     => 'No. Rekam Medis wajib diisi.',
            'kode_unik.required' => 'Kode unik wajib diisi.',
        ]);

        $patient = Patient::where('no_rm', strtoupper($request->no_rm))
            ->where('kode_unik', strtoupper($request->kode_unik))
            ->first();

        if (!$patient) {
            return back()->withInput($request->only('no_rm'))
                ->with('error', 'No. RM atau Kode Unik salah. Cek kembali nota pemeriksaan Anda.');
        }

        Session::put('patient_id', $patient->id);
        Session::put('patient_name', $patient->nama);

        return redirect()->route('patient.dashboard');
    }

    // Logout pasien
    public function logout()
    {
        Session::forget(['patient_id', 'patient_name']);
        return redirect()->route('patient.login')
            ->with('success', 'Anda berhasil keluar.');
    }

    // Dashboard pasien
    public function dashboard()
    {
        $patient = Patient::with([
            'medicalRecords' => fn($q) => $q->orderByDesc('tanggal_periksa'),
        ])->findOrFail(Session::get('patient_id'));

        $latestRecord = $patient->medicalRecords->first();
        $totalVisit   = $patient->medicalRecords->count();

        return view('patient.dashboard', compact('patient', 'latestRecord', 'totalVisit'));
    }

    // Semua riwayat pemeriksaan
    public function records()
    {
        $patient = Patient::with([
            'medicalRecords' => fn($q) => $q->with('user')->orderByDesc('tanggal_periksa'),
        ])->findOrFail(Session::get('patient_id'));

        return view('patient.records', compact('patient'));
    }
}