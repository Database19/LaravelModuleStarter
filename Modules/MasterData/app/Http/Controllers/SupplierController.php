<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('masterdata::supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('masterdata::supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        // Tambahkan data created_by dan updated_by secara otomatis
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        Supplier::create($validated);

        return redirect()->route('master-data.supplier.index');
    }

    public function edit(Supplier $supplier)
    {
        return view('masterdata::supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('suppliers')->ignore($supplier->id)],
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        // Tambahkan data updated_by secara otomatis
        $validated['updated_by'] = auth()->id();

        $supplier->update($validated);

        return redirect()->route('master-data.supplier.index');
    }

    public function destroy(Supplier $supplier)
    {
        // Asumsi model Supplier menggunakan SoftDeletes
        $supplier->delete();

        return redirect()->route('master-data.supplier.index')
                         ->with('success', 'Supplier telah dihapus.');
    }
}
