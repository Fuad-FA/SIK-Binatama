<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MedicalRecord;
use App\Models\Package;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['patient', 'user', 'items'])
            ->where('user_id', auth()->id());

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('no_transaksi', 'like', '%' . $request->search . '%')
                  ->orWhereHas('patient', function ($p) use ($request) {
                      $p->where('nama', 'like', '%' . $request->search . '%')
                        ->orWhere('no_rm', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $transactions = $query->orderByDesc('created_at')->paginate(15);

        return view('staff.transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        $patient       = null;
        $medicalRecord = null;

        if ($request->filled('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        if ($request->filled('medical_record_id')) {
            $medicalRecord = MedicalRecord::with('patient')
                ->findOrFail($request->medical_record_id);
            $patient = $medicalRecord->patient;
        }

        // $services = Service::where('is_active', true)
        //     ->where('harga', '>', 0)
        //     ->orderBy('kode')->get();
        $services = Service::where('is_active', true)
    ->orderBy('kode')->get(); // hapus filter harga > 0

        // $packages = Package::where('is_active', true)
        //     ->orderBy('harga')->get();
        $packages = Package::where('is_active', true)
    ->with('services')  // tambahkan with('services')
    ->orderBy('harga')->get();

        $products = Product::where('is_active', true)
            ->where('harga_by_order', false)
            ->where('stok', '>', 0)
            ->orderBy('kategori')->orderBy('nama')->get();

        return view('staff.transactions.create', compact(
            'patient', 'medicalRecord', 'services', 'packages', 'products'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items'      => 'required|array|min:1',
            'items.*.type'  => 'required|in:service,package,product',
            'items.*.id'    => 'required|integer',
            'items.*.qty'   => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'diskon'     => 'nullable|numeric|min:0',
        ], [
            'patient_id.required' => 'Pasien wajib dipilih.',
            'items.required'      => 'Pilih minimal satu layanan atau produk.',
            'items.min'           => 'Pilih minimal satu layanan atau produk.',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $nama      = '';
                $hargaSat  = (float) $item['harga'];
                $qty       = (int) $item['qty'];

                if ($item['type'] === 'service') {
                    $svc  = Service::findOrFail($item['id']);
                    $nama = $svc->nama;
                } elseif ($item['type'] === 'package') {
                    $pkg  = Package::findOrFail($item['id']);
                    $nama = $pkg->nama;
                } else {
                    $prod = Product::findOrFail($item['id']);
                    $nama = $prod->nama;
                    // Kurangi stok
                    $prod->decrement('stok', $qty);
                }

                $sub       = $hargaSat * $qty;
                $subtotal += $sub;

                $itemsData[] = [
                    'item_type'    => $item['type'],
                    'item_id'      => $item['id'],
                    'nama_item'    => $nama,
                    'harga_satuan' => $hargaSat,
                    'qty'          => $qty,
                    'subtotal'     => $sub,
                ];
            }

            $diskon = (float) ($request->diskon ?? 0);
            $total  = max(0, $subtotal - $diskon);

            $transaction = Transaction::create([
                'patient_id'        => $request->patient_id,
                'user_id'           => auth()->id(),
                'medical_record_id' => $request->medical_record_id ?: null,
                'subtotal'          => $subtotal,
                'diskon'            => $diskon,
                'total'             => $total,
                'metode_bayar'      => 'cash',
                'status'            => 'selesai',
            ]);

            foreach ($itemsData as $item) {
                $transaction->items()->create($item);
            }

            ActivityLog::create([
                'user_id'     => auth()->id(),
                'action'      => 'create_transaction',
                'description' => 'Transaksi ' . $transaction->no_transaksi .
                                 ' untuk pasien ' . $transaction->patient->nama,
                'ip_address'  => $request->ip(),
            ]);

            // DB::commit();

            // return redirect()->route('staff.transactions.show', $transaction)
            //     ->with('success', 'Transaksi ' . $transaction->no_transaksi . ' berhasil disimpan!');
            DB::commit();

            // Cek apakah ada layanan kesehatan (perlu rekam medis)
            $itemNames  = array_column($itemsData, 'nama_item');
            $activeFields = $this->getFieldsFromItems($itemNames);

// DEBUG sementara — hapus setelah fix
            \Illuminate\Support\Facades\Log::info('Item names: ' . json_encode($itemNames));
            \Illuminate\Support\Facades\Log::info('Active fields: ' . json_encode($activeFields));

            // Kalau ada field rekam medis yang relevan, redirect ke form rekam
            if (!empty($activeFields)) {
                return redirect()
                    ->route('staff.medical-records.create', [
                        'patient_id'     => $transaction->patient_id,
                        'transaction_id' => $transaction->id,
                        'fields'         => implode(',', $activeFields),
                    ])
                    ->with('success', 'Transaksi ' . $transaction->no_transaksi .
                           ' berhasil! Silakan input hasil pemeriksaan.')
                    ->with('print_nota', route('staff.transactions.nota', $transaction));
            }

            // Kalau tidak ada layanan kesehatan (hanya beli produk), langsung ke detail
            return redirect()->route('staff.transactions.show', $transaction)
                ->with('success', 'Transaksi ' . $transaction->no_transaksi . ' berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        // Kalau ada field rekam medis yang relevan, redirect ke form rekam
            if (!empty($activeFields)) {
                // Simpan ke session agar bisa diakses lagi jika klik kembali
                session(['pending_medical' => [
                    'transaction_id' => $transaction->id,
                    'patient_id'     => $transaction->patient_id,
                    'fields'         => implode(',', $activeFields),
                    'no_transaksi'   => $transaction->no_transaksi,
                    'patient_nama'   => $transaction->patient->nama,
                ]]);

                return redirect()
                    ->route('staff.medical-records.create', [
                        'patient_id'     => $transaction->patient_id,
                        'transaction_id' => $transaction->id,
                        'fields'         => implode(',', $activeFields),
                    ])
                    ->with('success', 'Transaksi ' . $transaction->no_transaksi .
                           ' berhasil! Silakan input hasil pemeriksaan.')
                    ->with('print_nota', route('staff.transactions.nota', $transaction));
            }
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);

        $transaction->load(['patient', 'user', 'items', 'medicalRecord']);
        return view('staff.transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        abort(403, 'Transaksi tidak dapat dihapus.');
    }

    // Generate nota PDF
    // public function nota(Transaction $transaction)
    // {
    //     if ($transaction->user_id !== auth()->id()) abort(403);

    //     $transaction->load(['patient', 'user', 'items', 'medicalRecord']);

    //     // Generate QR code
    //     $qrUrl  = url('/portal?rm=' . $transaction->patient->no_rm .
    //                    '&kode=' . $transaction->patient->kode_unik);
    //     $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
    //                 ->size(120)->generate($qrUrl);

    //     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.nota', [
    //         'transaction' => $transaction,
    //         'qrCode'      => base64_encode($qrCode),
    //         'qrUrl'       => $qrUrl,
    //     ]);

    //     // Ukuran thermal 80mm
    //     $pdf->setPaper([0, 0, 226.77, 700], 'portrait');

    //     return $pdf->stream('nota-' . $transaction->no_transaksi . '.pdf');
    // }

public function nota(Transaction $transaction)
{
    if ($transaction->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
        abort(403);
    }

    $transaction->load(['patient', 'user', 'items', 'medicalRecord']);

    // Cek apakah transaksi ini memerlukan rekam medis
    $itemNames    = $transaction->items->pluck('nama_item')->toArray();
    $activeFields = $this->getFieldsFromItems($itemNames);

    // Jika ada layanan kesehatan tapi rekam medis belum diisi
    if (!empty($activeFields) && !$transaction->medical_record_id) {
        return redirect()
            ->route('staff.medical-records.create', [
                'patient_id'     => $transaction->patient_id,
                'transaction_id' => $transaction->id,
                'fields'         => implode(',', $activeFields),
            ])
            ->with('warning', 'Hasil pemeriksaan harus diisi dulu sebelum cetak nota!')
            ->with('print_nota', route('staff.transactions.nota', $transaction));
    }

    // Generate QR code
    $qrUrl  = url('/portal?rm=' . $transaction->patient->no_rm .
                   '&kode=' . $transaction->patient->kode_unik);
    $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(120)->generate($qrUrl);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.nota', [
        'transaction' => $transaction,
        'qrCode'      => base64_encode($qrCode),
        'qrUrl'       => $qrUrl,
    ]);

    $pdf->setPaper([0, 0, 226.77, 700], 'portrait');

    return $pdf->stream('nota-' . $transaction->no_transaksi . '.pdf');
}


    // Mapping kode layanan ke field rekam medis
// private function getFieldsFromItems(array $itemNames): array
// {
//     $fields = [];

//     $mapping = [
//         'gula_darah'      => ['gula darah', 'cek gula', 'glucose'],
//         'kolesterol'      => ['kolesterol', 'cek kolesterol', 'cholesterol'],
//         'asam_urat'       => ['asam urat', 'cek asam urat', 'uric acid'],
//         'tensi'           => ['tekanan darah', 'tensi', 'cek tekanan', 'blood pressure'],
//         'suhu'            => ['suhu', 'cek suhu', 'temperatur'],
//         'nadi'            => ['nadi', 'cek nadi', 'pulse'],
//         'respirasi'       => ['respirasi', 'cek respirasi', 'pernapasan'],
//         'antropometri'    => ['bmi', 'antropometri', 'cek bmi', 'berat badan'],
//     ];

//     // Layanan/paket yang include semua vital sign
//     $allVitalServices = [
//         'paket sehat', 'sehat 1', 'sehat 2', 'sehat 3',
//         'sehat 4', 'sehat 5', 'vital sign'
//     ];

//     foreach ($itemNames as $nama) {
//         $namaLower = strtolower($nama);

//         // Cek apakah paket yang include semua vital
//         foreach ($allVitalServices as $vs) {
//             if (str_contains($namaLower, $vs)) {
//                 // Aktifkan semua field vital sign
//                 return ['gula_darah','kolesterol','asam_urat','tensi',
//                         'suhu','nadi','respirasi','antropometri'];
//             }
//         }

//         // Cek mapping per layanan
//         foreach ($mapping as $field => $keywords) {
//             foreach ($keywords as $kw) {
//                 if (str_contains($namaLower, $kw)) {
//                     $fields[] = $field;
//                     break;
//                 }
//             }
//         }
//     }

//     return array_unique($fields);
// }

// private function getFieldsFromItems(array $itemNames): array
// {
//     $fields = [];

//     $mapping = [
//         'gula_darah'   => ['gula', 'glucose', 'gds', 'gdp'],
//         'kolesterol'   => ['kolesterol', 'cholesterol'],
//         'asam_urat'    => ['asam urat', 'uric', 'asam'],
//         'tensi'        => ['tekanan', 'tensi', 'darah', 'blood pressure'],
//         'suhu'         => ['suhu', 'temperatur', 'temp'],
//         'nadi'         => ['nadi', 'pulse', 'denyut'],
//         'respirasi'    => ['respirasi', 'nafas', 'pernapasan'],
//         'antropometri' => ['bmi', 'antropometri', 'berat', 'tinggi'],
//     ];

//     $allVitalServices = [
//         'paket sehat', 'sehat 1', 'sehat 2', 'sehat 3',
//         'sehat 4', 'sehat 5', 'vital', 'infra red',
//         'pijat', 'totok', 'senam',
//     ];

//     foreach ($itemNames as $nama) {
//         $namaLower = strtolower(trim($nama));

//         // Paket yang sudah include semua vital sign
//         foreach ($allVitalServices as $vs) {
//             if (str_contains($namaLower, $vs)) {
//                 return [
//                     'gula_darah', 'kolesterol', 'asam_urat', 'tensi',
//                     'suhu', 'nadi', 'respirasi'
//                 ];
//             }
//         }

//         // Mapping per keyword
//         foreach ($mapping as $field => $keywords) {
//             foreach ($keywords as $kw) {
//                 if (str_contains($namaLower, $kw)) {
//                     $fields[] = $field;
//                     break;
//                 }
//             }
//         }
//     }

//     return array_unique($fields);
// }
// private function getFieldsFromItems(array $itemNames): array
// {
//     $fields = [];

//     $mapping = [
//         'gula_darah'   => ['cek gula', 'gula darah', 'glucose', 'gds', 'gdp'],
//         'kolesterol'   => ['cek kolesterol', 'kolesterol', 'cholesterol'],
//         'asam_urat'    => ['cek asam urat', 'asam urat', 'uric acid'],
//         'tensi'        => ['cek tekanan darah', 'tekanan darah', 'cek tensi', 'tensi'],
//         'suhu'         => ['cek suhu', 'suhu tubuh', 'temperatur'],
//         'nadi'         => ['cek nadi', 'denyut nadi'],
//         'respirasi'    => ['cek respirasi', 'respirasi', 'pernapasan'],
//         'antropometri' => ['cek bmi', 'cek antropometri', 'bmi', 'antropometri'],
//     ];

//     $allVitalServices = [
//         'paket sehat', 'sehat 1', 'sehat 2', 'sehat 3',
//         'sehat 4', 'sehat 5', 'vital sign',
//         'pijat', 'totok', 'infra red', 'senam',
//     ];

//     foreach ($itemNames as $nama) {
//         $namaLower = strtolower(trim($nama));

//         // Cek paket yang include semua vital
//         foreach ($allVitalServices as $vs) {
//             if (str_contains($namaLower, $vs)) {
//                 return [
//                     'gula_darah', 'kolesterol', 'asam_urat', 'tensi',
//                     'suhu', 'nadi', 'respirasi'
//                 ];
//             }
//         }

//         // Mapping per keyword — gunakan exact phrase, bukan substring pendek
//         foreach ($mapping as $field => $keywords) {
//             foreach ($keywords as $kw) {
//                 // Harus cocok sebagai phrase, bukan substring
//                 if (str_contains($namaLower, $kw)) {
//                     $fields[] = $field;
//                     break;
//                 }
//             }
//         }
//     }

//     return array_unique($fields);
// }

private function getFieldsFromItems(array $itemNames): array
{
    $fields = [];

    // WHITELIST: hanya layanan ini yang memicu form rekam medis
    // Key = field, Value = nama layanan yang EKSAK (lowercase)
    $exactMapping = [
        'gula_darah' => ['cek gula darah'],
        'kolesterol' => ['cek kolesterol'],
        'asam_urat'  => ['cek asam urat'],
        'tensi'      => ['cek tekanan darah'],
        'suhu'       => ['cek suhu'],
        'nadi'       => ['cek nadi'],
        'respirasi'  => ['cek respirasi'],
    ];

    // Paket dengan field spesifik
    $paketMapping = [
        'paket sehat 1' => ['tensi', 'suhu', 'nadi', 'respirasi'],
        'paket sehat 2' => ['tensi', 'suhu', 'nadi', 'respirasi'],
        'paket sehat 3' => ['tensi', 'suhu', 'nadi', 'respirasi'],
        'paket sehat 4' => ['gula_darah', 'kolesterol', 'asam_urat',
                            'tensi', 'suhu', 'nadi', 'respirasi'],
        'paket sehat 5' => ['tensi', 'suhu', 'nadi', 'respirasi'],
    ];

    foreach ($itemNames as $nama) {
        $namaLower = strtolower(trim($nama));

        // Cek paket dulu
        $matchedPaket = false;
        foreach ($paketMapping as $paket => $paketFields) {
            if ($namaLower === $paket || str_contains($namaLower, $paket)) {
                $fields      = array_merge($fields, $paketFields);
                $matchedPaket = true;
                break;
            }
        }
        if ($matchedPaket) continue;

        // Cek exact mapping — harus cocok persis dengan nama layanan
        foreach ($exactMapping as $field => $names) {
            foreach ($names as $n) {
                if ($namaLower === $n) { // exact match
                    $fields[] = $field;
                    break;
                }
            }
        }
    }

    return array_unique($fields);
}
}