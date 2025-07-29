<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountRecommendationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recommendations = [
            // Sales Account Recommendations
            [
                'setting_key' => 'sales_account',
                'business_type_key' => 'trading',
                'keywords' => ['penjualan', 'sales', 'dagang', '4-1'],
                'priority' => 10,
                'description' => 'Akun penjualan untuk perusahaan dagang',
            ],
            [
                'setting_key' => 'sales_account',
                'business_type_key' => 'service',
                'keywords' => ['pendapatan jasa', 'jasa', 'service revenue', '4-1'],
                'priority' => 10,
                'description' => 'Akun pendapatan jasa untuk perusahaan jasa',
            ],
            [
                'setting_key' => 'sales_account',
                'business_type_key' => 'manufacturing',
                'keywords' => ['penjualan produk', 'finished goods sales', '4-1'],
                'priority' => 10,
                'description' => 'Akun penjualan produk manufaktur',
            ],
            [
                'setting_key' => 'sales_account',
                'business_type_key' => 'retail',
                'keywords' => ['penjualan eceran', 'retail sales', '4-1'],
                'priority' => 10,
                'description' => 'Akun penjualan retail',
            ],
            [
                'setting_key' => 'sales_account',
                'business_type_key' => 'restaurant',
                'keywords' => ['penjualan makanan', 'food sales', 'beverage sales', '4-1'],
                'priority' => 10,
                'description' => 'Akun penjualan makanan dan minuman',
            ],

            // Purchase Account Recommendations
            [
                'setting_key' => 'purchase_account',
                'business_type_key' => 'trading',
                'keywords' => ['pembelian barang', 'purchase', 'merchandise', '5-1'],
                'priority' => 10,
                'description' => 'Akun pembelian barang dagang',
            ],
            [
                'setting_key' => 'purchase_account',
                'business_type_key' => 'service',
                'keywords' => ['beban operasional', 'operational expense', '6-1'],
                'priority' => 10,
                'description' => 'Akun beban operasional perusahaan jasa',
            ],
            [
                'setting_key' => 'purchase_account',
                'business_type_key' => 'manufacturing',
                'keywords' => ['bahan baku', 'raw material', 'material cost', '5-1'],
                'priority' => 10,
                'description' => 'Akun pembelian bahan baku manufaktur',
            ],
            [
                'setting_key' => 'purchase_account',
                'business_type_key' => 'restaurant',
                'keywords' => ['pembelian bahan makanan', 'food cost', 'ingredient', '5-1'],
                'priority' => 10,
                'description' => 'Akun pembelian bahan makanan',
            ],

            // Inventory Account Recommendations
            [
                'setting_key' => 'inventory_account',
                'business_type_key' => 'trading',
                'keywords' => ['persediaan barang', 'inventory', 'merchandise inventory', '1-3'],
                'priority' => 10,
                'description' => 'Akun persediaan barang dagang',
            ],
            [
                'setting_key' => 'inventory_account',
                'business_type_key' => 'manufacturing',
                'keywords' => ['persediaan bahan baku', 'raw material inventory', '1-3'],
                'priority' => 10,
                'description' => 'Akun persediaan bahan baku',
            ],
            [
                'setting_key' => 'inventory_account',
                'business_type_key' => 'retail',
                'keywords' => ['persediaan merchandise', 'stock barang', '1-3'],
                'priority' => 10,
                'description' => 'Akun persediaan merchandise retail',
            ],
            [
                'setting_key' => 'inventory_account',
                'business_type_key' => 'restaurant',
                'keywords' => ['persediaan bahan makanan', 'food inventory', '1-3'],
                'priority' => 10,
                'description' => 'Akun persediaan bahan makanan',
            ],

            // Accounts Receivable - Universal
            [
                'setting_key' => 'accounts_receivable_account',
                'business_type_key' => 'all',
                'keywords' => ['piutang', 'receivable', 'tagihan', '1-1-2'],
                'priority' => 10,
                'description' => 'Akun piutang dagang',
            ],

            // Accounts Payable - Universal
            [
                'setting_key' => 'accounts_payable_account',
                'business_type_key' => 'all',
                'keywords' => ['hutang', 'payable', 'utang', '2-1-1'],
                'priority' => 10,
                'description' => 'Akun hutang dagang',
            ],

            // Cash Account - Universal
            [
                'setting_key' => 'cash_account',
                'business_type_key' => 'all',
                'keywords' => ['kas', 'cash', 'tunai', '1-1-001'],
                'priority' => 10,
                'description' => 'Akun kas di tangan',
            ],

            // Bank Account - Universal
            [
                'setting_key' => 'bank_account',
                'business_type_key' => 'all',
                'keywords' => ['bank', 'rekening', 'giro', '1-1-002'],
                'priority' => 10,
                'description' => 'Akun bank/rekening giro',
            ],

            // Cost of Goods Sold
            [
                'setting_key' => 'cogs_account',
                'business_type_key' => 'trading',
                'keywords' => ['harga pokok penjualan', 'cogs', 'hpp', '5-2'],
                'priority' => 10,
                'description' => 'Akun harga pokok penjualan',
            ],
            [
                'setting_key' => 'cogs_account',
                'business_type_key' => 'manufacturing',
                'keywords' => ['harga pokok produksi', 'cost of production', '5-2'],
                'priority' => 10,
                'description' => 'Akun harga pokok produksi',
            ],
            [
                'setting_key' => 'cogs_account',
                'business_type_key' => 'restaurant',
                'keywords' => ['harga pokok makanan', 'food cost', '5-2'],
                'priority' => 10,
                'description' => 'Akun harga pokok makanan',
            ],

            // Tax Accounts
            [
                'setting_key' => 'sales_tax_account',
                'business_type_key' => 'all',
                'keywords' => ['pajak penjualan', 'ppn keluaran', 'output tax', '2-2'],
                'priority' => 10,
                'description' => 'Akun pajak penjualan/PPN keluaran',
            ],
            [
                'setting_key' => 'purchase_tax_account',
                'business_type_key' => 'all',
                'keywords' => ['pajak pembelian', 'ppn masukan', 'input tax', '1-4'],
                'priority' => 10,
                'description' => 'Akun pajak pembelian/PPN masukan',
            ],

            // Expense Accounts
            [
                'setting_key' => 'expense_account',
                'business_type_key' => 'all',
                'keywords' => ['beban operasional', 'expense', 'operational cost', '6-1'],
                'priority' => 10,
                'description' => 'Akun beban operasional umum',
            ],

            // Additional specific recommendations for other business types
            [
                'setting_key' => 'sales_account',
                'business_type_key' => 'construction',
                'keywords' => ['pendapatan kontrak', 'contract revenue', '4-1'],
                'priority' => 10,
                'description' => 'Akun pendapatan kontrak konstruksi',
            ],
            [
                'setting_key' => 'sales_account',
                'business_type_key' => 'healthcare',
                'keywords' => ['pendapatan jasa medis', 'medical revenue', '4-1'],
                'priority' => 10,
                'description' => 'Akun pendapatan jasa medis',
            ],
            [
                'setting_key' => 'sales_account',
                'business_type_key' => 'education',
                'keywords' => ['pendapatan spp', 'tuition fee', 'education revenue', '4-1'],
                'priority' => 10,
                'description' => 'Akun pendapatan SPP/biaya pendidikan',
            ],
        ];

        foreach ($recommendations as $recommendation) {
            // Convert keywords array to JSON string
            if (isset($recommendation['keywords']) && is_array($recommendation['keywords'])) {
                $recommendation['keywords'] = json_encode($recommendation['keywords']);
            }

            DB::table('account_recommendations')->updateOrInsert([
                'setting_key' => $recommendation['setting_key'],
                'business_type_key' => $recommendation['business_type_key'],
            ], array_merge($recommendation, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
