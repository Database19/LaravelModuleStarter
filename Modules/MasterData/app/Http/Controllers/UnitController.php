<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->paginate(15);
        return view('masterdata::units.index', compact('units'));
    }

    public function create()
    {
        return view('masterdata::units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'required|string|max:10|unique:units,short_code',
        ]);

        Unit::create($request->all());
        return redirect()->route('master-data.units.index')->with('success', 'Unit created successfully.');
    }

    public function show(Unit $unit)
    {
        return view('masterdata::units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        return view('masterdata::units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'required|string|max:10|unique:units,short_code,' . $unit->id,
        ]);

        $unit->update($request->all());
        return redirect()->route('master-data.units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('master-data.units.index')->with('success', 'Unit deleted successfully.');
    }
}
