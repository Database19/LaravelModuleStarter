<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index()
    {
        $opportunities = Opportunity::with(['customer', 'owner'])->latest()->paginate(15);
        return view('crm::opportunities.index', compact('opportunities'));
    }

    /**
     * Menampilkan form untuk membuat opportunity baru.
     */
    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $users = User::all();
        return view('crm::opportunities.create', compact('customers', 'users'));
    }

    /**
     * Menyimpan opportunity baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'owner_id' => 'required|exists:users,id',
            'expected_value' => 'nullable|numeric|min:0',
            'expected_closing_date' => 'nullable|date',
            'stage' => 'required|in:prospecting,proposal,negotiation,won,lost',
        ]);

        Opportunity::create($validated);

        return redirect()->route('crm.opportunities.index')->with('success', 'Opportunity baru berhasil dibuat.');
    }

    /**
     * Menampilkan detail satu opportunity.
     */
    public function show(Opportunity $opportunity)
    {
        $opportunity->load(['customer', 'owner', 'lead']);
        return view('crm::opportunities.show', compact('opportunity'));
    }

    /**
     * Menampilkan form untuk mengedit opportunity.
     */
    public function edit(Opportunity $opportunity)
    {
        $customers = Customer::where('is_active', true)->get();
        $users = User::all();
        return view('crm::opportunities.edit', compact('opportunity', 'customers', 'users'));
    }

    /**
     * Memperbarui data opportunity di database.
     */
    public function update(Request $request, Opportunity $opportunity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'owner_id' => 'required|exists:users,id',
            'expected_value' => 'nullable|numeric|min:0',
            'expected_closing_date' => 'nullable|date',
            'stage' => 'required|in:prospecting,proposal,negotiation,won,lost',
        ]);

        $opportunity->update($validated);

        return redirect()->route('crm.opportunities.show', $opportunity)->with('success', 'Data opportunity berhasil diperbarui.');
    }

    /**
     * Menghapus opportunity dari database.
     */
    public function destroy(Opportunity $opportunity)
    {
        $opportunity->delete();
        return redirect()->route('crm.opportunities.index')->with('success', 'Opportunity berhasil dihapus.');
    }

    /**
     * Menandai opportunity sebagai 'Won' dan mengarahkan ke pembuatan Sales Order.
     */
    public function markAsWon(Opportunity $opportunity)
    {
        $opportunity->update(['stage' => 'won']);

        // Arahkan ke form pembuatan Sales Order dengan data customer yang sudah terisi
        return redirect()->route('sales.orders.create', ['customer_id' => $opportunity->customer_id])
                         ->with('success', 'Opportunity dimenangkan! Silakan buat Sales Order.');
    }

    /**
     * Menandai opportunity sebagai 'Lost'.
     */
    public function markAsLost(Opportunity $opportunity)
    {
        $opportunity->update(['stage' => 'lost']);
        return back()->with('success', 'Opportunity ditandai sebagai "Lost".');
    }
}
