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

        $services = Service::where('is_active', true)
            ->where('harga', '>', 0)
            ->orderBy('kode')->get();

        $packages = Package::where('is_active', true)
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

            DB::commit();

            return redirect()->route('staff.transactions.show', $transaction)
                ->with('success', 'Transaksi ' . $transaction->no_transaksi . ' berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
    public function nota(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);

        $transaction->load(['patient', 'user', 'items', 'medicalRecord']);

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

        // Ukuran thermal 80mm
        $pdf->setPaper([0, 0, 226.77, 700], 'portrait');

        return $pdf->stream('nota-' . $transaction->no_transaksi . '.pdf');
    }
}