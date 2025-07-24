<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with('manager')->latest()->paginate(10);
        return view('warehouse::index', compact('warehouses'));
    }

    public function create()
    {
        $users = User::all(); // Untuk pilihan manager
        return view('warehouse::create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:warehouses,code',
            'location' => 'required|string',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean',
        ]);

        Warehouse::create($validated);

        return redirect()->route('warehouse.warehouses.index');
    }

    public function edit(Warehouse $warehouse)
    {
        $users = User::all();
        return view('warehouse::edit', compact('warehouse', 'users'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:warehouses,code,' . $warehouse->id,
            'location' => 'required|string',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean',
        ]);

        $warehouse->update($validated);
        return redirect()->route('warehouse.warehouses.index');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouse.warehouses.index');
    }
}
