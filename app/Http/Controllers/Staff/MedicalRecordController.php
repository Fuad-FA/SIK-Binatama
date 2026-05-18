<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['patient', 'user'])
            ->where('user_id', auth()->id());

        if ($request->filled('search')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('no_rm', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_periksa', $request->tanggal);
        }

        $records = $query->orderByDesc('tanggal_periksa')
                         ->orderByDesc('created_at')
                         ->paginate(15);

        return view('staff.medical.index', compact('records'));
    }

    // public function create(Request $request)
    // {
    //     // Bisa dipanggil dengan ?patient_id=X dari halaman detail pasien
    //     $patient = $request->filled('patient_id')
    //         ? Patient::findOrFail($request->patient_id)
    //         : null;

    //     return view('staff.medical.create', compact('patient'));
    // }
//     public function create(Request $request)
// {
//     $patient = $request->filled('patient_id')
//         ? Patient::findOrFail($request->patient_id)
//         : null;

//     // Ambil transaction_id dan fields dari URL jika ada
//     $transaction   = null;
//     $activeFields  = [];

//     if ($request->filled('transaction_id')) {
//         $transaction  = \App\Models\Transaction::with('items')
//             ->findOrFail($request->transaction_id);
//         $patient      = $transaction->patient;
//     }

//     if ($request->filled('fields')) {
//         $activeFields = explode(',', $request->fields);
//     }

//     // Jika tidak ada fields dari URL, aktifkan semua
//     if (empty($activeFields)) {
//         $activeFields = [
//             'gula_darah','kolesterol','asam_urat','tensi',
//             'suhu','nadi','respirasi','antropometri'
//         ];
//     }

//     return view('staff.medical.create', compact(
//         'patient', 'transaction', 'activeFields'
//     ));
// }

public function create(Request $request)
{
    // Jika tidak ada transaction_id, arahkan ke transaksi dulu
    if (!$request->filled('transaction_id') && !$request->filled('patient_id')) {
        return redirect()->route('staff.transactions.create')
            ->with('warning', 'Silakan buat transaksi terlebih dahulu.');
    }

    $patient = $request->filled('patient_id')
        ? Patient::findOrFail($request->patient_id)
        : null;

    $transaction  = null;
    $activeFields = [];

    if ($request->filled('transaction_id')) {
        $transaction  = \App\Models\Transaction::with('items')
            ->findOrFail($request->transaction_id);
        $patient      = $transaction->patient;
    }

    if ($request->filled('fields')) {
        $activeFields = explode(',', $request->fields);
    }

    if (empty($activeFields)) {
        $activeFields = [
            'gula_darah','kolesterol','asam_urat','tensi',
            'suhu','nadi','respirasi'
        ];
    }

    return view('staff.medical.create', compact(
        'patient', 'transaction', 'activeFields'
    ));
}


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'patient_id'    => 'required|exists:patients,id',
    //         'tanggal_periksa' => 'required|date',
    //         'gula_darah'    => 'nullable|numeric|min:0|max:9999',
    //         'kolesterol'    => 'nullable|numeric|min:0|max:9999',
    //         'asam_urat'     => 'nullable|numeric|min:0|max:99',
    //         'tensi_sistolik'  => 'nullable|integer|min:0|max:999',
    //         'tensi_diastolik' => 'nullable|integer|min:0|max:999',
    //         'suhu'          => 'nullable|numeric|min:30|max:45',
    //         'nadi'          => 'nullable|integer|min:0|max:300',
    //         'respirasi'     => 'nullable|integer|min:0|max:100',
    //         'catatan'       => 'nullable|string|max:500',
    //     ], [
    //         'patient_id.required' => 'Pasien wajib dipilih.',
    //         'tanggal_periksa.required' => 'Tanggal periksa wajib diisi.',
    //     ]);

    //     $record = MedicalRecord::create([
    //         'patient_id'      => $request->patient_id,
    //         'user_id'         => auth()->id(),
    //         'gula_darah'      => $request->gula_darah,
    //         'kolesterol'      => $request->kolesterol,
    //         'asam_urat'       => $request->asam_urat,
    //         'tensi_sistolik'  => $request->tensi_sistolik,
    //         'tensi_diastolik' => $request->tensi_diastolik,
    //         'suhu'            => $request->suhu,
    //         'nadi'            => $request->nadi,
    //         'respirasi'       => $request->respirasi,
    //         'catatan'         => $request->catatan,
    //         'tanggal_periksa' => $request->tanggal_periksa,
    //     ]);

    //     ActivityLog::create([
    //         'user_id'     => auth()->id(),
    //         'action'      => 'create_medical_record',
    //         'description' => 'Input rekam medis pasien: ' . $record->patient->nama,
    //         'ip_address'  => $request->ip(),
    //     ]);

    //     return redirect()->route('staff.patients.show', $record->patient_id)
    //         ->with('success', 'Hasil pemeriksaan berhasil disimpan!');
    // }

// 

public function store(Request $request)
{
    $request->validate([
        'patient_id'      => 'required|exists:patients,id',
        'tanggal_periksa' => 'required|date',
        'gula_darah'      => 'nullable|numeric|min:0|max:9999',
        'kolesterol'      => 'nullable|numeric|min:0|max:9999',
        'asam_urat'       => 'nullable|numeric|min:0|max:99',
        'tensi_sistolik'  => 'nullable|integer|min:0|max:999',
        'tensi_diastolik' => 'nullable|integer|min:0|max:999',
        'suhu'            => 'nullable|numeric|min:30|max:45',
        'nadi'            => 'nullable|integer|min:0|max:300',
        'respirasi'       => 'nullable|integer|min:0|max:100',
        'berat_badan'     => 'nullable|numeric|min:1|max:500',
        'tinggi_badan'    => 'nullable|numeric|min:1|max:300',
        'catatan_gizi'    => 'nullable|string|max:2000',
        'catatan'         => 'nullable|string|max:500',
    ]);

    // Hitung BMI otomatis jika berat & tinggi diisi
    $bmi = null;
    if ($request->filled('berat_badan') && $request->filled('tinggi_badan')) {
        $tinggiM = $request->tinggi_badan / 100;
        $bmi     = round($request->berat_badan / ($tinggiM * $tinggiM), 2);
    }

    $record = MedicalRecord::create([
        'patient_id'      => $request->patient_id,
        'user_id'         => auth()->id(),
        'tanggal_periksa' => $request->tanggal_periksa,
        'gula_darah'      => $request->gula_darah,
        'kolesterol'      => $request->kolesterol,
        'asam_urat'       => $request->asam_urat,
        'tensi_sistolik'  => $request->tensi_sistolik,
        'tensi_diastolik' => $request->tensi_diastolik,
        'suhu'            => $request->suhu,
        'nadi'            => $request->nadi,
        'respirasi'       => $request->respirasi,
        'berat_badan'     => $request->berat_badan,
        'tinggi_badan'    => $request->tinggi_badan,
        'bmi'             => $bmi,
        'catatan_gizi'    => $request->catatan_gizi,
        'catatan'         => $request->catatan,
    ]);

    // if ($request->filled('transaction_id')) {
    //     \App\Models\Transaction::where('id', $request->transaction_id)
    //         ->update(['medical_record_id' => $record->id]);
    //     session()->forget('pending_medical');
    // }

    // ActivityLog::create([
    //     'user_id'     => auth()->id(),
    //     'action'      => 'create_medical_record',
    //     'description' => 'Input rekam medis pasien: ' . $record->patient->nama,
    //     'ip_address'  => $request->ip(),
    // ]);

    // return redirect()->route('staff.patients.show', $record->patient_id)
    //     ->with('success', 'Hasil pemeriksaan berhasil disimpan!');

    // Hubungkan rekam medis ke transaksi jika ada
    if ($request->filled('transaction_id')) {
        \App\Models\Transaction::where('id', $request->transaction_id)
            ->update(['medical_record_id' => $record->id]);
        session()->forget('pending_medical');

        // Redirect ke detail transaksi agar bisa cetak nota
        return redirect()
            ->route('staff.transactions.show', $request->transaction_id)
            ->with('success', 'Hasil pemeriksaan berhasil disimpan! Silakan cetak nota.');
    }

    // Jika tidak ada transaksi (input manual), redirect ke detail pasien
    return redirect()->route('staff.patients.show', $record->patient_id)
        ->with('success', 'Hasil pemeriksaan berhasil disimpan!');
}

    public function show(MedicalRecord $medicalRecord)
    {
        // Staf hanya lihat rekam medis yang dia input
        // if ($medicalRecord->user_id !== auth()->id()) {
        //     abort(403);
        // }
        $medicalRecord->load(['patient', 'user']);
        return view('staff.medical.show', compact('medicalRecord'));
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        abort(403, 'Rekam medis tidak dapat dihapus.');
    }
}