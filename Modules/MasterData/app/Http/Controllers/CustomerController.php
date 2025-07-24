<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('masterdata::customer.index', compact('customers'));
    }

    public function create()
    {
        return view('masterdata::customer.create');
    }

    public function store(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'type' => 'required|in:individual,company',
            'tax_id' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        // Tambahkan data created_by dan updated_by secara otomatis
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        Customer::create($validated);

        a('Berhasil', 'Customer Berhasil dibuat', 'success');
        return redirect()->route('master-data.customer.index');
    }

    public function edit(Customer $customer)
    {
        return view('masterdata::customer.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        // dd($request);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'type' => 'required|in:individual,company',
            'tax_id' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        // Tambahkan data updated_by secara otomatis
        $validated['updated_by'] = auth()->id();

        $customer->update($validated);

        a('Berhasil', 'Customer Berhasil terupdate', 'success');
        return redirect()->route('master-data.customer.index');
    }

    public function destroy(Customer $customer)
    {
        // Karena Anda menggunakan SoftDeletes, ini akan berjalan dengan benar
        $customer->delete();

        return redirect()->route('master-data.customer.index')
                         ->with('success', 'Customer telah dihapus.');
    }
}
