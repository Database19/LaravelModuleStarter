<?php

namespace Modules\Manufacturing\Http\Controllers;

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
        return view('manufacturing::orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manufacturing::orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'mo_number' => 'required|string|max:255|unique:manufacturing_orders',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'finish_date' => 'required|date|after:start_date',
        ]);

        // Process manufacturing order creation
        return redirect()->route('manufacturing.orders.index')
            ->with('success', 'Manufacturing order created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('manufacturing::orders.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('manufacturing::orders.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'mo_number' => 'required|string|max:255|unique:manufacturing_orders,mo_number,' . $id,
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'finish_date' => 'required|date|after:start_date',
        ]);

        // Process manufacturing order update
        return redirect()->route('manufacturing.orders.index')
            ->with('success', 'Manufacturing order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process manufacturing order deletion
        return redirect()->route('manufacturing.orders.index')
            ->with('success', 'Manufacturing order deleted successfully.');
    }
}
