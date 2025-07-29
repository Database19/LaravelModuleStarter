<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('department')->latest()->paginate(15);
        return view('masterdata::positions.index', compact('positions'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('masterdata::positions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        Position::create($request->all());
        return redirect()->route('master-data.positions.index')->with('success', 'Position created successfully.');
    }

    public function show(Position $position)
    {
        $position->load('department');
        return view('masterdata::positions.show', compact('position'));
    }

    public function edit(Position $position)
    {
        $departments = Department::where('is_active', true)->get();
        return view('masterdata::positions.edit', compact('position', 'departments'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        $position->update($request->all());
        return redirect()->route('master-data.positions.index')->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('master-data.positions.index')->with('success', 'Position deleted successfully.');
    }
}
