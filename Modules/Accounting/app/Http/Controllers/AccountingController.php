<?php
namespace Modules\Accounting\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Account;
use App\Models\AccountingSetting;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index()
    {
        // Daftar semua kunci pengaturan yang dibutuhkan
        $settingKeys = [
            // --- Grup Penjualan (Sales) ---
            'Grup Penjualan' => [
                'default_accounts_receivable' => 'Akun Piutang Usaha (A/R)',
                'default_sales_revenue' => 'Akun Pendapatan Penjualan',
                'default_sales_discount' => 'Akun Diskon Penjualan',
                'default_vat_out' => 'Akun PPN Keluaran',
            ],

            // --- Grup Pembelian (Purchasing) ---
            'Grup Pembelian' => [
                'default_accounts_payable' => 'Akun Utang Usaha (A/P)',
                'default_vat_in' => 'Akun PPN Masukan',
            ],

            // --- Grup Inventaris & Gudang ---
            'Grup Inventaris & Gudang' => [
                'default_inventory_account' => 'Akun Persediaan Barang',
                'default_cogs_account' => 'Akun Harga Pokok Penjualan (HPP)',
                'default_inventory_adjustment_account' => 'Akun Penyesuaian Persediaan (untuk selisih stok)',
            ],

            // --- Grup Sumber Daya Manusia (HR) ---
            'Grup Sumber Daya Manusia' => [
                'default_salary_expense_account' => 'Akun Beban Gaji',
                'default_salary_payable_account' => 'Akun Utang Gaji',
                'default_tax_payable_account' => 'Akun Utang PPh 21',
            ],

            // --- Grup Kas & Bank ---
            'Grup Kas & Bank' => [
                'default_bank_account_for_payment' => 'Akun Bank/Kas Default untuk Pembayaran Keluar',
                'default_bank_account_for_receipt' => 'Akun Bank/Kas Default untuk Penerimaan Masuk',
            ],
        ];

        // Ambil pengaturan yang sudah ada dari database
        $settings = AccountingSetting::pluck('value', 'key');

        // Ambil semua akun untuk mengisi dropdown
        $accounts = Account::orderBy('account_code')->get();

        return view('accounting::settings.index', compact('settingKeys', 'settings', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|exists:accounts,id' // Validasi setiap value adalah ID akun yang valid
        ]);

        foreach ($request->settings as $key => $value) {
            if ($value) { // Hanya simpan jika ada value yang dipilih
                AccountingSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return back();
    }
}
