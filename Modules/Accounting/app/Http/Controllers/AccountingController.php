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
            'default_accounts_receivable' => 'Akun Piutang Usaha',
            'default_sales_revenue' => 'Akun Pendapatan Penjualan',
            'default_accounts_payable' => 'Akun Utang Usaha',
            'default_inventory' => 'Akun Persediaan',
            'default_cogs' => 'Akun Harga Pokok Penjualan',
            'default_sales_discount' => 'Akun Diskon Penjualan',
            'default_vat_out' => 'Akun PPN Keluaran',
            'default_vat_in' => 'Akun PPN Masukan',
            'default_cash_payment' => 'Bank BCA (dan Lain Lain)',
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

        return back()->with('success', 'Pengaturan akuntansi berhasil disimpan.');
    }
}
