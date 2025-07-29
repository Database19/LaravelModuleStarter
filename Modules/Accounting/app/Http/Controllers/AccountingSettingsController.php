<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountingSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountingSettingsController extends Controller
{
    /**
     * Get business types from database
     */
    private function fetchBusinessTypes()
    {
        try {
            return DB::table('business_types')
                     ->where('is_active', true)
                     ->orderBy('sort_order')
                     ->orderBy('name')
                     ->get()
                     ->map(function ($item) {
                         return [
                             'value' => $item->key,
                             'label' => $item->name,
                             'description' => $item->description,
                             'icon' => $item->icon,
                             'color' => $item->color
                         ];
                     });
        } catch (\Exception $e) {
            a('Gagal', $e->getMessage(), 'error');
        }
    }

    /**
     * Get account recommendations for business type and setting key
     */
    private function fetchAccountRecommendations($businessTypeKey, $settingKey)
    {
        try {
            return DB::table('account_recommendations')
                     ->where('business_type_key', $businessTypeKey)
                     ->where('setting_key', $settingKey)
                     ->where('is_active', true)
                     ->orderBy('priority', 'desc')
                     ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Display main accounting settings page
     */
    public function index()
    {
        // Setting keys grouped by category
        $settingKeys = [
            'Penjualan' => [
                'sales_account' => 'Akun Penjualan',
                'sales_tax_account' => 'Akun Pajak Penjualan',
                'sales_discount_account' => 'Akun Diskon Penjualan',
                'accounts_receivable_account' => 'Akun Piutang Dagang',
            ],
            'Pembelian' => [
                'purchase_account' => 'Akun Pembelian',
                'purchase_tax_account' => 'Akun Pajak Pembelian',
                'purchase_discount_account' => 'Akun Diskon Pembelian',
                'accounts_payable_account' => 'Akun Hutang Dagang',
            ],
            'Inventori' => [
                'inventory_account' => 'Akun Persediaan',
                'cogs_account' => 'Akun Harga Pokok Penjualan',
                'inventory_adjustment_account' => 'Akun Penyesuaian Persediaan',
            ],
            'Kas & Bank' => [
                'cash_account' => 'Akun Kas',
                'bank_account' => 'Akun Bank',
                'petty_cash_account' => 'Akun Kas Kecil',
            ],
            'Biaya' => [
                'expense_account' => 'Akun Biaya Umum',
                'depreciation_account' => 'Akun Penyusutan',
                'bad_debt_account' => 'Akun Piutang Tak Tertagih',
            ]
        ];

        // Use dummy data if database is not accessible
        try {
            $accounts = Account::where('is_active', true)->get();
        } catch (\Exception $e) {
            a('Gagal', $e->getMessage(), 'error');
        }

        // Get business types for dropdown
        $businessTypes = $this->fetchBusinessTypes();

        // Get saved settings - simplify to just key => account_id mapping
        try {
            $settingsData = AccountingSetting::all()->keyBy('key');
            $settings = [];
            foreach ($settingsData as $key => $setting) {
                $settings[$key] = $setting->value; // Just store the account ID
            }
        } catch (\Exception $e) {
            // Fallback to session if model doesn't exist
            $settings = session('accounting_settings', []);
        }

        return view('accounting::settings.index', compact('settingKeys', 'accounts', 'settings', 'businessTypes'));
    }

    /**
     * Display chart of accounts settings page
     */
    public function chartOfAccounts()
    {
        $businessTypes = $this->fetchBusinessTypes();
        $currentBusinessType = session('selected_business_type', 'all');

        $accounts = Account::getAccountsByTypeForBusiness($currentBusinessType);

        return view('accounting::settings.accounts', compact('businessTypes', 'currentBusinessType', 'accounts'));
    }

    /**
     * Update business type selection
     */
    public function updateBusinessType(Request $request)
    {
        $request->validate([
            'business_type' => 'required|string|exists:business_types,key'
        ]);

        // Store selected business type in session
        session(['selected_business_type' => $request->business_type]);

        // Get accounts for selected business type
        $accounts = Account::getAccountsByTypeForBusiness($request->business_type);

        return response()->json([
            'success' => true,
            'message' => 'Bidang usaha berhasil dipilih',
            'accounts' => $accounts->map(function($typeAccounts, $type) {
                return [
                    'type' => $type,
                    'accounts' => $typeAccounts->map(function($account) {
                        return [
                            'id' => $account->id,
                            'account_code' => $account->account_code,
                            'name' => $account->name,
                            'business_type' => $account->business_type
                        ];
                    })
                ];
            })->values()
        ]);
    }

    /**
     * Get accounts for specific business type (AJAX)
     */
    public function getAccountsForBusinessType(Request $request)
    {
        $businessType = $request->get('business_type', 'all');

        // Use dummy data if database is not accessible
        try {
            // Get accounts filtered by business type
            if ($businessType === 'all') {
                $accounts = Account::where('is_active', true)->get();
            } else {
                $accounts = Account::where('is_active', true)
                                 ->where(function($query) use ($businessType) {
                                     $query->where('business_type', $businessType)
                                           ->orWhere('business_type', 'all')
                                           ->orWhereNull('business_type');
                                 })
                                 ->get();
            }
        } catch (\Exception $e) {
            a('Gagal', $e->getMessage(), 'error');
        }

        // Format accounts for dropdown
        $formattedAccounts = $accounts->map(function($account) {
            return [
                'id' => $account->id,
                'account_code' => $account->account_code,
                'name' => $account->name,
                'business_type' => $account->business_type ?? 'all',
                'type' => $account->type,
                'display_text' => "({$account->account_code}) {$account->name}"
            ];
        });

        return response()->json([
            'success' => true,
            'accounts' => $formattedAccounts,
            'total' => $formattedAccounts->count()
        ]);
    }

    /**
     * Get business types for dropdown (AJAX)
     */
    public function getBusinessTypes()
    {
        $businessTypes = $this->fetchBusinessTypes();

        return response()->json([
            'success' => true,
            'business_types' => $businessTypes
        ]);
    }

    /**
     * Get account recommendations for business type and setting key (AJAX)
     */
    public function getAccountRecommendations(Request $request)
    {
        $businessTypeKey = $request->get('business_type');
        $settingKey = $request->get('setting_key');

        if (!$businessTypeKey) {
            return response()->json([
                'success' => false,
                'message' => 'Business type is required'
            ], 400);
        }

        // If setting_key is provided, return single recommendation
        if ($settingKey) {
            $recommendation = $this->fetchAccountRecommendations($businessTypeKey, $settingKey);

            if ($recommendation) {
                $keywords = $recommendation->keywords ? json_decode($recommendation->keywords, true) : [];

                return response()->json([
                    'success' => true,
                    'recommendation' => [
                        'setting_key' => $recommendation->setting_key,
                        'business_type_key' => $recommendation->business_type_key,
                        'keywords' => $keywords,
                        'priority' => $recommendation->priority,
                        'description' => $recommendation->description
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No recommendation found'
            ], 404);
        }

        // If no setting_key, return all recommendations for business type
        try {
            $recommendations = DB::table('account_recommendations')
                             ->where('business_type_key', $businessTypeKey)
                             ->where('is_active', true)
                             ->orderBy('priority', 'desc')
                             ->get();

            $formattedRecommendations = [];
            foreach ($recommendations as $recommendation) {
                $keywords = $recommendation->keywords ? json_decode($recommendation->keywords, true) : [];
                $formattedRecommendations[$recommendation->setting_key] = [
                    'setting_key' => $recommendation->setting_key,
                    'business_type_key' => $recommendation->business_type_key,
                    'keywords' => $keywords,
                    'priority' => $recommendation->priority,
                    'description' => $recommendation->description
                ];
            }

            return response()->json([
                'success' => true,
                'recommendations' => $formattedRecommendations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching recommendations: ' . $e->getMessage()
            ], 500);
        }
    }    /**
     * Store accounting settings
     */
    public function store(Request $request)
    {
        // Validate the settings
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|exists:accounts,id'
        ]);

        // Store settings in database or session
        // For now, we'll store in session
        session(['accounting_settings' => $validated['settings']]);
        a('Berhasil', 'Pengaturan akuntansi berhasil disimpan!', 'success');
        // Save each setting as a row in accounting_settings table
        $companyId = Auth::user()->company_id;
        foreach ($validated['settings'] as $key => $accountId) {
            if ($accountId) {
                DB::table('accounting_settings')->updateOrInsert(
                    ['key' => $key, 'company_id' => $companyId],
                    ['value' => $accountId, 'updated_at' => now()]
                );
            } else {
                // If value is null, remove the setting
                DB::table('accounting_settings')
                    ->where('key', $key)
                    ->where('company_id', $companyId)
                    ->delete();
            }
        }

        return redirect()->back();
    }

    /**
     * Reset to default business type
     */
    public function resetBusinessType()
    {
        session()->forget('selected_business_type');

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan bidang usaha direset ke default'
        ]);
    }
}
