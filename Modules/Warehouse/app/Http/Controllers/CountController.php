<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CountController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('warehouse::counts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warehouse::counts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'count_number' => 'required|string|max:255|unique:stock_counts',
            'warehouse_id' => 'required|exists:warehouses,id',
            'count_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.system_quantity' => 'required|numeric|min:0',
            'items.*.actual_quantity' => 'required|numeric|min:0',
        ]);

        // Process stock count creation
        return redirect()->route('warehouse.counts.index')
            ->with('success', 'Stock count created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('warehouse::counts.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('warehouse::counts.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'count_number' => 'required|string|max:255|unique:stock_counts,count_number,' . $id,
            'warehouse_id' => 'required|exists:warehouses,id',
            'count_date' => 'required|date',
            'status' => 'required|in:draft,in_progress,completed,cancelled',
        ]);

        // Process stock count update
        return redirect()->route('warehouse.counts.index')
            ->with('success', 'Stock count updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process stock count deletion
        return redirect()->route('warehouse.counts.index')
            ->with('success', 'Stock count deleted successfully.');
    }
}
