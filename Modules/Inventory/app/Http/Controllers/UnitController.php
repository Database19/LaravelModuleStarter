<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Menampilkan halaman index dengan data.
     */
    public function index(Request $request)
    {
        $query = Unit::query();
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%{$searchTerm}%")->orWhere('short_code', 'like', "%{$searchTerm}%"));
        }
        $units = $query->latest()->paginate(10);
        $totalUnits = Unit::count();
        $activeUnits = Unit::where('is_active', 1)->count();
        $inactiveUnits = $totalUnits - $activeUnits;
        return view('inventory::units.index', compact('units', 'totalUnits', 'activeUnits', 'inactiveUnits'));
    }

    /**
     * Menyimpan unit baru. Merespons dengan JSON.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:units,name',
            'short_code' => 'required|string|max:255|unique:units,short_code',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        Unit::create($data);

        return response()->json(['message' => 'Unit baru berhasil dibuat.']);
    }

    /**
     * Mengembalikan data unit untuk diedit dalam format JSON.
     */
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return response()->json($unit);
    }

    /**
     * Memperbarui unit yang ada. Merespons dengan JSON.
     */
    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:units,name,' . $unit->id,
            'short_code' => 'required|string|max:255|unique:units,short_code,' . $unit->id,
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['updated_by'] = Auth::id();

        $unit->update($data);

        return response()->json(['message' => 'Unit berhasil diperbarui.']);
    }

    /**
     * Menghapus unit.
     */
    public function destroy($id)
    {
        Unit::findOrFail($id)->delete();
        // Untuk AJAX, kita bisa kembalikan respons sukses, tapi reload halaman lebih sederhana.
        return redirect()->route('inventory.units.index')->with('success', 'Unit berhasil dihapus.');
    }
}
