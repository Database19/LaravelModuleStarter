<?php

namespace Modules\Manufacturing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class WorkcenterController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manufacturing::workcenters.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manufacturing::workcenters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:workcenters',
            'capacity' => 'required|numeric|min:1',
            'efficiency' => 'required|numeric|min:0|max:100',
            'cost_per_hour' => 'required|numeric|min:0',
        ]);

        // Process workcenter creation
        return redirect()->route('manufacturing.workcenters.index')
            ->with('success', 'Workcenter created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('manufacturing::workcenters.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('manufacturing::workcenters.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:workcenters,code,' . $id,
            'capacity' => 'required|numeric|min:1',
            'efficiency' => 'required|numeric|min:0|max:100',
            'cost_per_hour' => 'required|numeric|min:0',
        ]);

        // Process workcenter update
        return redirect()->route('manufacturing.workcenters.index')
            ->with('success', 'Workcenter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process workcenter deletion
        return redirect()->route('manufacturing.workcenters.index')
            ->with('success', 'Workcenter deleted successfully.');
    }
}
