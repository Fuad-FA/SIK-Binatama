<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('services')->orderBy('harga')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('kode')->get();
        return view('admin.packages.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'harga'      => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string|max:255',
            'service_ids'=> 'nullable|array',
            'service_ids.*' => 'exists:services,id',
        ]);

        $package = Package::create([
            'nama'      => $request->nama,
            'harga'     => $request->harga,
            'deskripsi' => $request->deskripsi,
            'is_active' => true,
        ]);

        if ($request->filled('service_ids')) {
            $package->services()->sync($request->service_ids);
        }

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'create_package',
            'description' => 'Menambah paket: ' . $package->nama,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket ' . $package->nama . ' berhasil ditambahkan!');
    }

    public function edit(Package $package)
    {
        $services        = Service::where('is_active', true)->orderBy('kode')->get();
        $selectedServices = $package->services->pluck('id')->toArray();
        return view('admin.packages.edit', compact('package', 'services', 'selectedServices'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'harga'      => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string|max:255',
            'service_ids'=> 'nullable|array',
            'service_ids.*' => 'exists:services,id',
        ]);

        $package->update([
            'nama'      => $request->nama,
            'harga'     => $request->harga,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $package->services()->sync($request->service_ids ?? []);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket ' . $package->nama . ' berhasil diperbarui!');
    }

    public function destroy(Package $package)
    {
        $nama = $package->nama;
        $package->services()->detach();
        $package->delete();
        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket ' . $nama . ' berhasil dihapus.');
    }

    public function toggleActive(Package $package)
    {
        $package->update(['is_active' => !$package->is_active]);
        $status = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', 'Paket ' . $package->nama . ' berhasil ' . $status . '.');
    }
}