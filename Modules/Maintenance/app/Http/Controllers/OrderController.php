<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('maintenance::orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('maintenance::orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'order_number' => 'required|string|max:255|unique:maintenance_orders',
            'equipment_id' => 'required|exists:equipment,id',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'scheduled_date' => 'required|date',
        ]);

        // Process maintenance order creation
        return redirect()->route('maintenance.orders.index')
            ->with('success', 'Maintenance order created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('maintenance::orders.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('maintenance::orders.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'order_number' => 'required|string|max:255|unique:maintenance_orders,order_number,' . $id,
            'equipment_id' => 'required|exists:equipment,id',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        // Process maintenance order update
        return redirect()->route('maintenance.orders.index')
            ->with('success', 'Maintenance order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process maintenance order deletion
        return redirect()->route('maintenance.orders.index')
            ->with('success', 'Maintenance order deleted successfully.');
    }
}
