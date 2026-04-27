<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Service::withCount('orders')->latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create() { return view('admin.services.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'description'    => 'nullable|string',
            'unit'           => 'required|in:kg,pcs,item',
            'price_per_unit' => 'required|numeric|min:0',
        ]);

        Service::create($request->all());
        return redirect()->route('admin.services.index')
            ->with('success', 'Service berhasil ditambahkan!');
    }

    public function edit(Service $service) { return view('admin.services.edit', compact('service')); }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'unit'           => 'required|in:kg,pcs,item',
            'price_per_unit' => 'required|numeric|min:0',
        ]);

        $service->update($request->all());
        return redirect()->route('admin.services.index')
            ->with('success', 'Service berhasil diupdate!');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Service berhasil dihapus!');
    }
}