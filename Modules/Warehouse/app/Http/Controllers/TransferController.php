<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class TransferController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('warehouse::transfers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warehouse::transfers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'transfer_number' => 'required|string|max:255|unique:stock_transfers',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transfer_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
        ]);

        // Process stock transfer creation
        return redirect()->route('warehouse.transfers.index')
            ->with('success', 'Stock transfer created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('warehouse::transfers.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('warehouse::transfers.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'transfer_number' => 'required|string|max:255|unique:stock_transfers,transfer_number,' . $id,
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transfer_date' => 'required|date',
            'status' => 'required|in:draft,pending,in_transit,completed,cancelled',
        ]);

        // Process stock transfer update
        return redirect()->route('warehouse.transfers.index')
            ->with('success', 'Stock transfer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process stock transfer deletion
        return redirect()->route('warehouse.transfers.index')
            ->with('success', 'Stock transfer deleted successfully.');
    }
}
