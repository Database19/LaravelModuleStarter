<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('inventory::adjustments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventory::adjustments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'adjustment_type' => 'required|in:increase,decrease',
            'quantity' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255',
        ]);

        // Process adjustment creation
        return redirect()->route('inventory.adjustments.index')
            ->with('success', 'Stock adjustment created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('inventory::adjustments.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('inventory::adjustments.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'adjustment_type' => 'required|in:increase,decrease',
            'quantity' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255',
        ]);

        // Process adjustment update
        return redirect()->route('inventory.adjustments.index')
            ->with('success', 'Stock adjustment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process adjustment deletion
        return redirect()->route('inventory.adjustments.index')
            ->with('success', 'Stock adjustment deleted successfully.');
    }
}
