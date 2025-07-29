<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(15);
        return view('masterdata::warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('masterdata::warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Warehouse::create($request->all());
        return redirect()->route('master-data.warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        return view('masterdata::warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('masterdata::warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $warehouse->update($request->all());
        return redirect()->route('master-data.warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('master-data.warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }
}
