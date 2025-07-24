<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->get();
        return view('inventory::brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        Brand::create([
            'name' => $validated['name'],
            'logo_url' => $logoPath,
            'is_active' => $validated['is_active'] ?? true,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Merek baru telah berhasil ditambahkan.']);
    }

    public function edit(Brand $brand)
    {
        return response()->json($brand);
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        $logoPath = $brand->logo_url;
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($brand->logo_url) {
                Storage::disk('public')->delete($brand->logo_url);
            }
            // Unggah logo baru
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        $brand->update([
            'name' => $validated['name'],
            'logo_url' => $logoPath,
            'is_active' => $validated['is_active'] ?? $brand->is_active,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Data merek telah berhasil diperbarui.']);
    }

    public function destroy(Brand $brand)
    {
        // Hapus logo dari storage
        if ($brand->logo_url) {
            Storage::disk('public')->delete($brand->logo_url);
        }
        $brand->delete();
        alert()->success('Berhasil!', 'Merek telah dihapus.');
        return redirect()->route('inventory.brands.index');
    }
}
