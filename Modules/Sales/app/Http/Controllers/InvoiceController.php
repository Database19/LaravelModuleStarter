<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sales::invoices.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sales::invoices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'invoice_number' => 'required|string|max:255|unique:sales_invoices',
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Process invoice creation
        return redirect()->route('sales.invoices.index')
            ->with('success', 'Sales invoice created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('sales::invoices.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('sales::invoices.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'invoice_number' => 'required|string|max:255|unique:sales_invoices,invoice_number,' . $id,
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
        ]);

        // Process invoice update
        return redirect()->route('sales.invoices.index')
            ->with('success', 'Sales invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Process invoice deletion
        return redirect()->route('sales.invoices.index')
            ->with('success', 'Sales invoice deleted successfully.');
    }
}
