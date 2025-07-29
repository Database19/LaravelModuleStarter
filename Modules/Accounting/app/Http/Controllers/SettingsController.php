<?php

namespace Modules\Accounting\Http\Controllers;

use App\Models\Account;
use App\Models\AccountingSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $settings = AccountingSetting::first() ?? new AccountingSetting();

        // Optional: daftar bidang usaha
        $businessTypes = [
            'all' => 'Semua Bidang Usaha',
            'retail' => 'Retail',
            'jasa' => 'Jasa',
            'manufaktur' => 'Manufaktur',
            'konstruksi' => 'Konstruksi',
        ];

        $currentBusinessType = $request->get('business_type', 'all');

        $accounts = collect();
        if ($currentBusinessType) {
            $accounts = Account::getAccountsByTypeForBusiness($currentBusinessType);
        }

        return view('accounting::settings.index', compact(
            'settings',
            'businessTypes',
            'currentBusinessType',
            'accounts'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'currency' => 'required|string|max:10',
            'decimal_places' => 'required|integer|min:0|max:4',
            'date_format' => 'required|string|max:20',
            'fiscal_year_start' => 'required|date_format:Y-m-d',
            'enable_multi_currency' => 'boolean',
            'default_tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $settings = AccountingSetting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            AccountingSetting::create($validated);
        }

        alert()->success('Berhasil!', 'Pengaturan akuntansi berhasil disimpan.');
        return redirect()->route('accounting.settings.index');
    }
}
