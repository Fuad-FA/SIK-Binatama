<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_produk', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $products = $query->orderBy('kategori')->orderBy('nama')->paginate(15);

        // Statistik ringkas
        $stats = [
            'total'    => Product::count(),
            'aktif'    => Product::where('is_active', true)->count(),
            'stok_min' => Product::where('is_active', true)->where('stok', '<=', 5)->count(),
        ];

        return view('admin.products.index', compact('products', 'stats'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|string|max:20|unique:products',
            'nama'        => 'required|string|max:100',
            'kategori'    => 'required|in:makanan_minuman,pembersih,lainnya',
            'harga'       => 'required_unless:harga_by_order,1|nullable|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'required|string|max:20',
            'keterangan'  => 'nullable|string|max:255',
        ], [
            'kode_produk.unique'    => 'Kode produk sudah digunakan.',
            'nama.required'         => 'Nama produk wajib diisi.',
            'kategori.required'     => 'Kategori wajib dipilih.',
            'harga.required_unless' => 'Harga wajib diisi jika bukan By Order.',
        ]);

        $isByOrder = $request->boolean('harga_by_order');

        $product = Product::create([
            'kode_produk'    => strtoupper($request->kode_produk),
            'nama'           => $request->nama,
            'kategori'       => $request->kategori,
            'harga'          => $isByOrder ? 0 : $request->harga,
            'harga_by_order' => $isByOrder,
            'stok'           => $request->stok,
            'satuan'         => $request->satuan,
            'keterangan'     => $request->keterangan,
            'is_active'      => true,
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'create_product',
            'description' => 'Menambah produk: ' . $product->nama,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk ' . $product->nama . ' berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'kode_produk' => ['required','string','max:20',
                              Rule::unique('products')->ignore($product->id)],
            'nama'        => 'required|string|max:100',
            'kategori'    => 'required|in:makanan_minuman,pembersih,lainnya',
            'harga'       => 'nullable|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'required|string|max:20',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $isByOrder = $request->boolean('harga_by_order');

        $product->update([
            'kode_produk'    => strtoupper($request->kode_produk),
            'nama'           => $request->nama,
            'kategori'       => $request->kategori,
            'harga'          => $isByOrder ? 0 : $request->harga,
            'harga_by_order' => $isByOrder,
            'stok'           => $request->stok,
            'satuan'         => $request->satuan,
            'keterangan'     => $request->keterangan,
            'is_active'      => $request->boolean('is_active', true),
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'update_product',
            'description' => 'Mengupdate produk: ' . $product->nama,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk ' . $product->nama . ' berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $nama = $product->nama;
        $product->delete();

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'delete_product',
            'description' => 'Menghapus produk: ' . $nama,
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk ' . $nama . ' berhasil dihapus.');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', 'Produk ' . $product->nama . ' berhasil ' . $status . '.');
    }

    // Update stok saja
    public function updateStok(Request $request, Product $product)
    {
        $request->validate([
            'stok' => 'required|integer|min:0',
        ]);

        $product->update(['stok' => $request->stok]);

        return redirect()->back()
            ->with('success', 'Stok ' . $product->nama . ' diperbarui menjadi ' . $request->stok . '.');
    }
}