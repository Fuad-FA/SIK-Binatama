<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('kode')->paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'         => 'required|string|max:20|unique:services',
            'nama'         => 'required|string|max:100',
            'harga'        => 'required|numeric|min:0',
            'durasi_menit' => 'nullable|integer|min:1',
        ], [
            'kode.unique'   => 'Kode layanan sudah digunakan.',
            'nama.required' => 'Nama layanan wajib diisi.',
        ]);

        $service = Service::create([
            'kode'         => strtoupper($request->kode),
            'nama'         => $request->nama,
            'harga'        => $request->harga,
            'durasi_menit' => $request->durasi_menit,
            'is_active'    => true,
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'create_service',
            'description' => 'Menambah layanan: ' . $service->nama,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan ' . $service->nama . ' berhasil ditambahkan!');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'kode'         => ['required','string','max:20',
                               Rule::unique('services')->ignore($service->id)],
            'nama'         => 'required|string|max:100',
            'harga'        => 'required|numeric|min:0',
            'durasi_menit' => 'nullable|integer|min:1',
        ]);

        $service->update([
            'kode'         => strtoupper($request->kode),
            'nama'         => $request->nama,
            'harga'        => $request->harga,
            'durasi_menit' => $request->durasi_menit,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan ' . $service->nama . ' berhasil diperbarui!');
    }

    public function destroy(Service $service)
    {
        $nama = $service->nama;
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan ' . $nama . ' berhasil dihapus.');
    }

    public function toggleActive(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        $status = $service->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', 'Layanan ' . $service->nama . ' berhasil ' . $status . '.');
    }
}