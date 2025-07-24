<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with('owner')->latest()->paginate(15);
        return view('crm::leads.index', compact('leads'));
    }

    /**
     * Menampilkan form untuk membuat lead baru.
     */
    public function create()
    {
        $users = User::all(); // Untuk pilihan owner/salesperson
        return view('crm::leads.create', compact('users'));
    }

    /**
     * Menyimpan lead baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:leads,email',
            'phone' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:new,contacted,qualified,lost',
            'owner_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        Lead::create($validated);

        return redirect()->route('crm.leads.index')->with('success', 'Lead baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu lead.
     */
    public function show(Lead $lead)
    {
        $lead->load('owner');
        return view('crm::leads.show', compact('lead'));
    }

    /**
     * Menampilkan form untuk mengedit lead.
     */
    public function edit(Lead $lead)
    {
        $users = User::all();
        return view('crm::leads.edit', compact('lead', 'users'));
    }

    /**
     * Memperbarui data lead di database.
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('leads')->ignore($lead->id)],
            'phone' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:new,contacted,qualified,lost',
            'owner_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $lead->update($validated);

        return redirect()->route('crm.leads.show', $lead)->with('success', 'Data lead berhasil diperbarui.');
    }

    /**
     * Menghapus lead dari database.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('crm.leads.index')->with('success', 'Lead berhasil dihapus.');
    }

    public function convert(Lead $lead)
    {
        if ($lead->status == 'qualified') {
            return back()->with('info', 'Lead ini sudah pernah dikualifikasi.');
        }

        DB::beginTransaction();
        try {
            // 1. Cari atau buat Customer baru dari data Lead
            $customer = Customer::firstOrCreate(
                ['email' => $lead->email],
                [
                    'name' => $lead->name,
                    'company_name' => $lead->company_name,
                    'phone' => $lead->phone,
                    'type' => $lead->company_name ? 'company' : 'individual',
                    'is_active' => true,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]
            );

            // 2. Buat Opportunity baru
            $opportunity = Opportunity::create([
                'name' => 'Opportunity dari ' . $lead->name,
                'lead_id' => $lead->id,
                'customer_id' => $customer->id,
                'owner_id' => $lead->owner_id,
                'stage' => 'prospecting',
                // Anda bisa menambahkan form untuk mengisi expected_value di sini
            ]);

            // 3. Update status Lead
            $lead->update(['status' => 'qualified']);

            DB::commit();
            // Arahkan ke halaman opportunity yang baru dibuat
            return redirect()->route('crm.opportunities.show', $opportunity)
                             ->with('success', 'Lead berhasil dikonversi menjadi Opportunity.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengkonversi lead: ' . $e->getMessage());
        }
    }
}
