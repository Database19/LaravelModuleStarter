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
            // --- Grup Aset Tetap ---
            'Grup Aset Tetap' => [
                'default_fixed_assets_account' => 'Akun Aset Tetap',
                'default_depreciation_expense_account' => 'Akun Beban Depresiasi',
                'default_accumulated_depreciation_account' => 'Akun Akumulasi Depresiasi',
                'default_gain_loss_on_disposal' => 'Akun Keuntungan/Kerugian Pelepasan Aset', // Saat jual aset
            ],

            // --- Grup Utang Jangka Panjang ---
            'Grup Utang Jangka Panjang' => [
                'default_long_term_debt_account' => 'Akun Utang Jangka Panjang',
                'default_interest_expense_account' => 'Akun Beban Bunga', // Beban bunga pinjaman
            ],

            // --- Grup Ekuitas ---
            'Grup Ekuitas' => [
                'default_retained_earnings_account' => 'Akun Laba Ditahan',
                'default_dividends_payable_account' => 'Akun Utang Dividen', // Jika ada dividen
            ],

            // --- Grup Pendapatan Lain-lain ---
            'Grup Pendapatan Lain-lain' => [
                'default_interest_income_account' => 'Akun Pendapatan Bunga',
                'default_rental_income_account' => 'Akun Pendapatan Sewa',
                'default_other_income_account' => 'Akun Pendapatan Lain-lain',
            ],

            // --- Grup Beban Lain-lain ---
            'Grup Beban Lain-lain' => [
                'default_late_payment_fees_expense' => 'Akun Beban Keterlambatan Pembayaran',
                'default_other_expense_account' => 'Akun Beban Lain-lain',
            ],

            // --- Grup Manufaktur (Jika ada modul Manufaktur) ---
            'Grup Manufaktur' => [
                'default_raw_materials_inventory' => 'Akun Persediaan Bahan Baku',
                'default_work_in_process_inventory' => 'Akun Persediaan Barang Dalam Proses',
                'default_direct_labor_expense' => 'Akun Beban Tenaga Kerja Langsung',
                'default_factory_overhead_expense' => 'Akun Beban Overhead Pabrik',
            ],

            // --- Grup Pembelian (Purchasing) ---
            'Grup Pembelian' => [
                'default_accounts_payable' => 'Akun Utang Usaha (A/P)',
                'default_vat_in' => 'Akun PPN Masukan',
                'default_purchase_discount' => 'Akun Diskon Pembelian', // Jika ada diskon pembelian
                'default_freight_in' => 'Akun Biaya Pengiriman Pembelian', // Biaya angkut pembelian
            ],

            // --- Grup Penjualan (Sales) ---
            'Grup Penjualan' => [
                'default_accounts_receivable' => 'Akun Piutang Usaha (A/R)',
                'default_sales_revenue' => 'Akun Pendapatan Penjualan',
                'default_sales_discount' => 'Akun Diskon Penjualan',
                'default_vat_out' => 'Akun PPN Keluaran',
                'default_freight_revenue' => 'Akun Pendapatan Pengiriman', // Jika ada biaya pengiriman
                'default_sales_returns' => 'Akun Retur Penjualan', // Jika ada retur
            ],

            // --- Grup Inventaris & Gudang ---
            'Grup Inventaris & Gudang' => [
                'default_inventory_account' => 'Akun Persediaan Barang',
                'default_cogs_account' => 'Akun Harga Pokok Penjualan (HPP)',
                'default_inventory_adjustment_account' => 'Akun Penyesuaian Persediaan (untuk selisih stok)',
                'default_write_off_account' => 'Akun Penghapusan Persediaan', // Untuk barang rusak/usang
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
