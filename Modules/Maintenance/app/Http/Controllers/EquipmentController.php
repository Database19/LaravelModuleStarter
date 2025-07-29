<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class EquipmentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('maintenance::equipment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('maintenance::equipment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:equipment',
            'type' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);

        // Process equipment creation
        return redirect()->route('maintenance.equipment.index')
            ->with('success', 'Equipment created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('maintenance::equipment.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('maintenance::equipment.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:equipment,code,' . $id,
            'type' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);

        // Process equipment update
        return redirect()->route('maintenance.equipment.index')
            ->with('success', 'Equipment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process equipment deletion
        return redirect()->route('maintenance.equipment.index')
            ->with('success', 'Equipment deleted successfully.');
    }
}
