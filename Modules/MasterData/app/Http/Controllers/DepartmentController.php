<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->paginate(15);
        return view('masterdata::departments.index', compact('departments'));
    }

    public function create()
    {
        return view('masterdata::departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Department::create($request->all());
        return redirect()->route('master-data.departments.index')->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        return view('masterdata::departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('masterdata::departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department->update($request->all());
        return redirect()->route('master-data.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('master-data.departments.index')->with('success', 'Department deleted successfully.');
    }
}
