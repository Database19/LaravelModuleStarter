<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\ProjectStatus;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PondasiAwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            'super_admin' => User::where('email', 'admin@erp.test')->first(),
            'sales' => User::where('email', 'sales.manager@erp.test')->first(),
            'accountant' => User::where('email', 'accounting.manager@erp.test')->first(),
            'hr' => User::where('email', 'hr.manager@erp.test')->first(),
            'inventory' => User::where('email', 'warehouse.manager@erp.test')->first(),
            'maintenance' => User::where('email', 'maintenance.staff@erp.test')->first(),
            'production' => User::where('email', 'production.manager@erp.test')->first(),
            'pos' => User::where('email', 'pos.cashier@erp.test')->first(),
            'project' => User::where('email', 'project.manager@erp.test')->first(),
            'helpdesk' => User::where('email', 'helpdesk.agent@erp.test')->first(),
        ];

        $now = now();
        $accounts = [
            // 100-00-000: ASET
            // --- 110-00-000: ASET LANCAR ---
            ['account_code' => '111-01-001', 'name' => 'Kas Kecil (Petty Cash)', 'type' => 'asset'],
            ['account_code' => '111-01-002', 'name' => 'Kas di Brankas', 'type' => 'asset'],
            ['account_code' => '112-01-001', 'name' => 'Bank BCA', 'type' => 'asset'],
            ['account_code' => '112-01-002', 'name' => 'Bank Mandiri', 'type' => 'asset'],
            ['account_code' => '113-01-001', 'name' => 'Piutang Usaha', 'type' => 'asset'],
            ['account_code' => '113-02-001', 'name' => 'Penyisihan Piutang Tak Tertagih', 'type' => 'asset'], // Akun Kontra Aset
            ['account_code' => '114-01-001', 'name' => 'Persediaan Barang Jadi', 'type' => 'asset'],
            ['account_code' => '114-01-002', 'name' => 'Persediaan Bahan Baku', 'type' => 'asset'],
            ['account_code' => '115-01-001', 'name' => 'Sewa Dibayar di Muka', 'type' => 'asset'],
            ['account_code' => '115-01-002', 'name' => 'Asuransi Dibayar di Muka', 'type' => 'asset'],
            ['account_code' => '116-01-001', 'name' => 'Perlengkapan Kantor', 'type' => 'asset'],
            ['account_code' => '117-01-001', 'name' => 'PPN Masukan', 'type' => 'asset'],

            // --- 120-00-000: ASET TETAP ---
            ['account_code' => '121-01-001', 'name' => 'Tanah', 'type' => 'asset'],
            ['account_code' => '122-01-001', 'name' => 'Gedung dan Bangunan', 'type' => 'asset'],
            ['account_code' => '122-02-001', 'name' => 'Akumulasi Penyusutan Gedung', 'type' => 'asset'], // Akun Kontra Aset
            ['account_code' => '123-01-001', 'name' => 'Kendaraan', 'type' => 'asset'],
            ['account_code' => '123-02-001', 'name' => 'Akumulasi Penyusutan Kendaraan', 'type' => 'asset'], // Akun Kontra Aset
            ['account_code' => '124-01-001', 'name' => 'Peralatan Kantor', 'type' => 'asset'],
            ['account_code' => '124-02-001', 'name' => 'Akumulasi Penyusutan Peralatan Kantor', 'type' => 'asset'], // Akun Kontra Aset
            ['account_code' => '125-01-001', 'name' => 'Mesin Produksi', 'type' => 'asset'],
            ['account_code' => '125-02-001', 'name' => 'Akumulasi Penyusutan Mesin Produksi', 'type' => 'asset'], // Akun Kontra Aset

            // --- 130-00-000: ASET TIDAK BERWUJUD ---
            ['account_code' => '131-01-001', 'name' => 'Goodwill', 'type' => 'asset'],
            ['account_code' => '132-01-001', 'name' => 'Hak Paten', 'type' => 'asset'],
            ['account_code' => '132-02-001', 'name' => 'Akumulasi Amortisasi Hak Paten', 'type' => 'asset'], // Akun Kontra Aset

            // 200-00-000: LIABILITAS
            // --- 210-00-000: LIABILITAS JANGKA PENDEK ---
            ['account_code' => '211-01-001', 'name' => 'Utang Usaha', 'type' => 'liability'],
            ['account_code' => '212-01-001', 'name' => 'Utang Gaji', 'type' => 'liability'],
            ['account_code' => '213-01-001', 'name' => 'Utang Pajak (PPh 21, 23, 25)', 'type' => 'liability'],
            ['account_code' => '213-01-002', 'name' => 'PPN Keluaran', 'type' => 'liability'],
            ['account_code' => '214-01-001', 'name' => 'Beban yang Masih Harus Dibayar', 'type' => 'liability'],
            ['account_code' => '215-01-001', 'name' => 'Pendapatan Diterima di Muka', 'type' => 'liability'],
            ['account_code' => '216-01-001', 'name' => 'Utang Bank Jangka Pendek', 'type' => 'liability'],

            // --- 220-00-000: LIABILITAS JANGKA PANJANG ---
            ['account_code' => '221-01-001', 'name' => 'Utang Bank Jangka Panjang', 'type' => 'liability'],
            ['account_code' => '222-01-001', 'name' => 'Utang Obligasi', 'type' => 'liability'],

            // 300-00-000: EKUITAS
            ['account_code' => '311-01-001', 'name' => 'Modal Disetor', 'type' => 'equity'],
            ['account_code' => '312-01-001', 'name' => 'Agio Saham', 'type' => 'equity'],
            ['account_code' => '321-01-001', 'name' => 'Laba Ditahan', 'type' => 'equity'],
            ['account_code' => '331-01-001', 'name' => 'Prive / Dividen', 'type' => 'equity'], // Akun Kontra Ekuitas
            ['account_code' => '399-01-001', 'name' => 'Ikhtisar Laba Rugi', 'type' => 'equity'], // Akun sementara untuk penutupan buku

            // 400-00-000: PENDAPATAN
            // --- 410-00-000: PENDAPATAN USAHA ---
            ['account_code' => '411-01-001', 'name' => 'Pendapatan Penjualan Barang', 'type' => 'revenue'],
            ['account_code' => '412-01-001', 'name' => 'Pendapatan Jasa', 'type' => 'revenue'],
            ['account_code' => '413-01-001', 'name' => 'Retur Penjualan dan Pengurangan Harga', 'type' => 'revenue'], // Akun Kontra Pendapatan
            ['account_code' => '414-01-001', 'name' => 'Diskon Penjualan', 'type' => 'revenue'], // Akun Kontra Pendapatan

            // --- 420-00-000: PENDAPATAN DI LUAR USAHA ---
            ['account_code' => '421-01-001', 'name' => 'Pendapatan Bunga', 'type' => 'revenue'],
            ['account_code' => '422-01-001', 'name' => 'Pendapatan Sewa', 'type' => 'revenue'],
            ['account_code' => '423-01-001', 'name' => 'Keuntungan Penjualan Aset', 'type' => 'revenue'],

            // 500-00-000: HARGA POKOK PENJUALAN (HPP)
            ['account_code' => '511-01-001', 'name' => 'Harga Pokok Penjualan', 'type' => 'expense'],
            ['account_code' => '512-01-001', 'name' => 'Biaya Angkut Pembelian', 'type' => 'expense'],

            // 600-00-000: BEBAN OPERASIONAL
            // --- 610-00-000: BEBAN UMUM & ADMINISTRASI ---
            ['account_code' => '611-01-001', 'name' => 'Beban Gaji dan Upah', 'type' => 'expense'],
            ['account_code' => '612-01-001', 'name' => 'Beban Listrik, Air, dan Internet', 'type' => 'expense'],
            ['account_code' => '613-01-001', 'name' => 'Beban Sewa Kantor', 'type' => 'expense'],
            ['account_code' => '614-01-001', 'name' => 'Beban Asuransi', 'type' => 'expense'],
            ['account_code' => '615-01-001', 'name' => 'Beban Perlengkapan Kantor', 'type' => 'expense'],
            ['account_code' => '616-01-001', 'name' => 'Beban Penyusutan Aset Tetap', 'type' => 'expense'],
            ['account_code' => '617-01-001', 'name' => 'Beban Amortisasi Aset Tak Berwujud', 'type' => 'expense'],
            ['account_code' => '618-01-001', 'name' => 'Beban Kerugian Piutang', 'type' => 'expense'],

            // --- 620-00-000: BEBAN PENJUALAN & PEMASARAN ---
            ['account_code' => '621-01-001', 'name' => 'Beban Iklan dan Promosi', 'type' => 'expense'],
            ['account_code' => '622-01-001', 'name' => 'Beban Komisi Penjualan', 'type' => 'expense'],
            ['account_code' => '623-01-001', 'name' => 'Beban Pengiriman Penjualan', 'type' => 'expense'],
            ['account_code' => '624-01-001', 'name' => 'Beban Perjalanan Dinas Penjualan', 'type' => 'expense'],

            // 700-00-000: BEBAN DI LUAR USAHA
            ['account_code' => '711-01-001', 'name' => 'Beban Bunga', 'type' => 'expense'],
            ['account_code' => '712-01-001', 'name' => 'Beban Administrasi Bank', 'type' => 'expense'],
            ['account_code' => '713-01-001', 'name' => 'Kerugian Penjualan Aset', 'type' => 'expense'],

            // 800-00-000: PAJAK
            ['account_code' => '811-01-001', 'name' => 'Beban Pajak Penghasilan', 'type' => 'expense'],
        ];

        // Akun dibuat oleh Accountant
        foreach ($accounts as $account) {
            DB::table('accounts')->insertOrIgnore([
                'account_code' => $account['account_code'],
                'name' => $account['name'],
                'type' => $account['type'],
                'is_active' => true,
                'created_by' => $users['accountant']->id,
                'updated_by' => $users['accountant']->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Customer dibuat oleh Sales
        // DB::table('customers')->insertOrIgnore([
        //     ['name' => 'PT. Pelanggan Jaya', 'email' => 'pelanggan@example.com', 'phone' => '0811111111', 'address' => 'Jl. Pelanggan No.1', 'company_name' => 'Pelanggan Jaya', 'created_by' => $users['sales']->id, 'updated_by' => $users['sales']->id, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        // // Supplier dibuat oleh Inventory/Purchasing
        // DB::table('suppliers')->insertOrIgnore([
        //     ['name' => 'Supplier Makmur', 'contact_person' => 'Budi', 'email' => 'supplier@example.com', 'phone' => '0822222222', 'address' => 'Jl. Supplier No.2', 'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        // // Gudang dibuat oleh Inventory
        // DB::table('warehouses')->insertOrIgnore([
        //     ['name' => 'Gudang Pusat', 'location' => 'Jakarta', 'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        // Kategori produk dibuat oleh Inventory
        DB::table('product_categories')->insertOrIgnore([
            // Kategori Barang Jadi (Finished Goods)
            [
                'name' => 'Elektronik',
                'description' => 'Perangkat elektronik konsumen dan komponennya.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Pakaian dan Aksesoris',
                'description' => 'Produk garmen, alas kaki, dan aksesoris fashion.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Makanan dan Minuman',
                'description' => 'Produk makanan kemasan dan minuman.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Alat Tulis Kantor (ATK)',
                'description' => 'Perlengkapan untuk kebutuhan operasional kantor.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Mebel dan Furnitur',
                'description' => 'Produk perabotan rumah tangga dan kantor.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Kesehatan dan Kecantikan',
                'description' => 'Produk obat-obatan, suplemen, dan kosmetik.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Suku Cadang Otomotif',
                'description' => 'Komponen dan suku cadang untuk kendaraan bermotor.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],

            // Kategori Bahan Baku (Raw Materials)
            [
                'name' => 'Bahan Baku Makanan',
                'description' => 'Bahan mentah untuk diolah menjadi produk makanan.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Kain dan Tekstil',
                'description' => 'Bahan mentah untuk produksi garmen.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Komponen Elektronik',
                'description' => 'Bahan mentah untuk perakitan perangkat elektronik.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],

            // Kategori Jasa (Services)
            [
                'name' => 'Jasa Perawatan',
                'description' => 'Layanan pemeliharaan dan perbaikan aset atau produk.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Jasa Konsultasi',
                'description' => 'Layanan konsultasi profesional.',
                'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now
            ],
        ]);


        $units = [
            ['name' => 'Unit', 'short_code' => 'unit'],
            ['name' => 'Pieces', 'short_code' => 'pcs'],
            ['name' => 'Pack', 'short_code' => 'pack'],
            ['name' => 'Rim', 'short_code' => 'rim'],
            ['name' => 'Layanan', 'short_code' => 'layanan'],
            ['name' => 'Box', 'short_code' => 'box'],
            ['name' => 'Kilogram', 'short_code' => 'kg'],
        ];

        // Tambahkan data umum ke setiap baris
        foreach ($units as &$unit) {
            $unit['is_active'] = true;
            $unit['created_by'] = $users['inventory']->id;
            $unit['updated_by'] = $users['inventory']->id;
            $unit['created_at'] = $now;
            $unit['updated_at'] = $now;
        }

        // Gunakan insertOrIgnore untuk menghindari error jika data sudah ada
        DB::table('units')->insertOrIgnore($units);

        $brands = [
            ['name' => 'HP', 'logo_url' => null],
            ['name' => 'Logitech', 'logo_url' => null],
            ['name' => 'Sinar Dunia', 'logo_url' => null],
            ['name' => 'Generic', 'logo_url' => null], // Untuk produk tanpa merek
        ];

        // Tambahkan data umum ke setiap baris
        foreach ($brands as &$brand) {
            $brand['is_active'] = true;
            $brand['created_by'] = $users['inventory']->id;
            $brand['updated_by'] = $users['inventory']->id;
            $brand['created_at'] = $now;
            $brand['updated_at'] = $now;
        }

        // Gunakan insertOrIgnore untuk menghindari error jika data sudah ada
        DB::table('brands')->insertOrIgnore($brands);


        // $units = Unit::pluck('id', 'short_code');
        // $brands = Brand::pluck('id', 'name');
        // Produk dibuat oleh Inventory
        // DB::table('products')->insert([
        //     [
        //         'sku' => 'ELE-001',
        //         'barcode' => '8991234567011',
        //         'name' => 'Laptop ProBook 14 G9',
        //         'description' => 'Laptop bisnis dengan prosesor i5, RAM 8GB, SSD 512GB.',
        //         'product_category_id' => 1, // Elektronik
        //         'brand_id' => $brands['HP'] ?? null,
        //         'unit_id' => $units['unit'] ?? null,
        //         'type' => 'product',
        //         'price' => 12500000,
        //         'cost' => 9800000,
        //         'quantity' => 50,
        //         'min_stock' => 10,
        //         'track_stock' => 1, // 1 = true
        //         'is_active' => 1,   // 1 = true
        //         'created_by' => $users['inventory']->id,
        //         'updated_by' => $users['inventory']->id,
        //         'created_at' => $now,
        //         'updated_at' => $now,
        //         'deleted_at' => null,
        //     ],
        //     [
        //         'sku' => 'ELE-002',
        //         'barcode' => '8991234567028',
        //         'name' => 'Mouse Wireless Logitech M331',
        //         'description' => 'Mouse optik nirkabel senyap dengan koneksi USB.',
        //         'product_category_id' => 1, // Elektronik
        //         'brand_id' => $brands['Logitech'] ?? null,
        //         'unit_id' => $units['pcs'] ?? null,
        //         'type' => 'product',
        //         'price' => 250000,
        //         'cost' => 180000,
        //         'quantity' => 200,
        //         'min_stock' => 50,
        //         'track_stock' => 1,
        //         'is_active' => 1,
        //         'created_by' => $users['inventory']->id,
        //         'updated_by' => $users['inventory']->id,
        //         'created_at' => $now,
        //         'updated_at' => $now,
        //         'deleted_at' => null,
        //     ],
        //     [
        //         'sku' => 'PKN-001',
        //         'barcode' => '8991234567035',
        //         'name' => 'Kemeja Katun Pria Lengan Panjang',
        //         'description' => 'Kemeja formal pria warna putih, bahan katun premium.',
        //         'product_category_id' => 2, // Pakaian dan Aksesoris
        //         'brand_id' => $brands['Generic'] ?? null,
        //         'unit_id' => $units['pcs'] ?? null,
        //         'type' => 'product',
        //         'price' => 350000,
        //         'cost' => 220000,
        //         'quantity' => 150,
        //         'min_stock' => 30,
        //         'track_stock' => 1,
        //         'is_active' => 1,
        //         'created_by' => $users['inventory']->id,
        //         'updated_by' => $users['inventory']->id,
        //         'created_at' => $now,
        //         'updated_at' => $now,
        //         'deleted_at' => null,
        //     ],
        //     [
        //         'sku' => 'ATK-001',
        //         'barcode' => '8991234567042',
        //         'name' => 'Kertas HVS A4 80gsm (1 Rim)',
        //         'description' => 'Kertas fotokopi ukuran A4, ketebalan 80gsm, isi 500 lembar.',
        //         'product_category_id' => 4, // Alat Tulis Kantor (ATK)
        //         'brand_id' => $brands['Sinar Dunia'] ?? null,
        //         'unit_id' => $units['rim'] ?? null,
        //         'type' => 'product',
        //         'price' => 55000,
        //         'cost' => 42000,
        //         'quantity' => 500,
        //         'min_stock' => 100,
        //         'track_stock' => 1,
        //         'is_active' => 1,
        //         'created_by' => $users['inventory']->id,
        //         'updated_by' => $users['inventory']->id,
        //         'created_at' => $now,
        //         'updated_at' => $now,
        //         'deleted_at' => null,
        //     ],
        //     [
        //         'sku' => 'JSA-001',
        //         'barcode' => null, // Jasa tidak punya barcode
        //         'name' => 'Jasa Service AC per Unit',
        //         'description' => 'Layanan pembersihan dan pengecekan AC Split.',
        //         'product_category_id' => 11, // Jasa Perawatan
        //         'brand_id' => null, // Jasa tidak punya merek
        //         'unit_id' => $units['layanan'] ?? null,
        //         'type' => 'service',
        //         'price' => 150000,
        //         'cost' => 70000,
        //         'quantity' => 0, // Jasa tidak punya kuantitas
        //         'min_stock' => 0,
        //         'track_stock' => 0, // 0 = false, stok tidak dilacak
        //         'is_active' => 1,
        //         'created_by' => $users['inventory']->id,
        //         'updated_by' => $users['inventory']->id,
        //         'created_at' => $now,
        //         'updated_at' => $now,
        //         'deleted_at' => null,
        //     ],
        // ]);

        // Stok gudang di-handle oleh Inventory
        // DB::table('warehouse_stock')->insertOrIgnore([
        //     ['warehouse_id' => 1, 'product_id' => 1, 'quantity' => 50, 'rack_location' => 'A1-01', 'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        // Pergerakan stok awal di-handle oleh Inventory
        // DB::table('stock_movements')->insertOrIgnore([
        //     ['product_id' => 1, 'warehouse_id' => 1, 'type' => 'in', 'quantity' => 50, 'reason' => 'Initial stock', 'user_id' => $users['inventory']->id, 'reference_type' => 'App\\Models\\PurchaseOrder', 'reference_id' => 1, 'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        // Menambahkan customer kedua
        // DB::table('customers')->insertOrIgnore([
        //     ['name' => 'CV. Mitra Usaha', 'email' => 'mitra@example.com', 'phone' => '089876543210', 'address' => 'Jl. Niaga No. 15', 'company_name' => 'Mitra Usaha', 'created_by' => $users['sales']->id, 'updated_by' => $users['sales']->id, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        // Sales order dibuat oleh Sales
        // Sales order dibuat oleh Sales
        // DB::table('sales_orders')->insertOrIgnore([
        //     // Pesanan 1: Beberapa item, status confirmed
        //     [
        //         'order_number' => 'SO-2025-001', 'customer_id' => 1, 'user_id' => $users['sales']->id,
        //         'total_amount' => 25500000, 'tax_amount' => 2805000, 'discount_amount' => 0,
        //         'status' => 'confirmed', 'order_date' => now()->subDays(10),
        //         'created_by' => $users['sales']->id, 'updated_by' => $users['sales']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)
        //     ],
        //     // Pesanan 2: Ada diskon, status completed
        //     [
        //         'order_number' => 'SO-2025-002', 'customer_id' => 2, 'user_id' => $users['sales']->id,
        //         'total_amount' => 1700000, 'tax_amount' => 187000, 'discount_amount' => 50000,
        //         'status' => 'completed', 'order_date' => now()->subDays(5),
        //         'created_by' => $users['sales']->id, 'updated_by' => $users['sales']->id, 'created_at' => now()->subDays(5), 'updated_at' => now()->subDays(5)
        //     ],
        //     // Pesanan 3: Pesanan jasa, status pending
        //     [
        //         'order_number' => 'SO-2025-003', 'customer_id' => 1, 'user_id' => $users['sales']->id,
        //         'total_amount' => 450000, 'tax_amount' => 49500, 'discount_amount' => 0,
        //         'status' => 'pending', 'order_date' => $now,
        //         'created_by' => $users['sales']->id, 'updated_by' => $users['sales']->id, 'created_at' => $now, 'updated_at' => $now
        //     ],
        // ]);

        // // Item SO juga dibuat oleh Sales
        // DB::table('sales_order_items')->insertOrIgnore([
        //     // Item untuk Sales Order ID 1
        //     ['sales_order_id' => 1, 'product_id' => 1, 'quantity' => 2, 'unit_price' => 12500000, 'total_price' => 25000000, 'created_by' => $users['sales']->id, 'updated_by' => $users['sales']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],
        //     ['sales_order_id' => 1, 'product_id' => 2, 'quantity' => 2, 'unit_price' => 250000, 'total_price' => 500000, 'created_by' => $users['sales']->id, 'updated_by' => $users['sales']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],

        //     // Item untuk Sales Order ID 2
        //     ['sales_order_id' => 2, 'product_id' => 5, 'quantity' => 10, 'unit_price' => 55000, 'total_price' => 550000, 'created_by' => $users['sales']->id, 'updated_by' => $users['sales']->id, 'created_at' => now()->subDays(5), 'updated_at' => now()->subDays(5)],
        //     ['sales_order_id' => 2, 'product_id' => 6, 'quantity' => 1, 'unit_price' => 1200000, 'total_price' => 1200000, 'created_by' => $users['sales']->id, 'updated_by' => $users['sales']->id, 'created_at' => now()->subDays(5), 'updated_at' => now()->subDays(5)],

        //     // Item untuk Sales Order ID 3 (Jasa)
        //     ['sales_order_id' => 3, 'product_id' => 8, 'quantity' => 3, 'unit_price' => 150000, 'total_price' => 450000, 'created_by' => $users['sales']->id, 'updated_by' => $users['sales']->id, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        // Menambahkan supplier kedua
        // DB::table('suppliers')->insertOrIgnore([
        //     ['name' => 'PT. Distributor Nasional', 'contact_person' => 'Citra', 'email' => 'distributor@example.com', 'phone' => '0833333333', 'address' => 'Jl. Grosir No. 88', 'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        // Purchase order dibuat oleh Inventory/Purchasing
        // Purchase order dibuat oleh Inventory/Purchasing
        // DB::table('purchase_orders')->insertOrIgnore([
        //     // PO 1: Pembelian beberapa item dari Supplier 1, status 'received'
        //     [
        //         'order_number' => 'PO-2025-001', 'supplier_id' => 1, 'user_id' => $users['inventory']->id,
        //         'total_amount' => 33000000, 'status' => 'received',
        //         'order_date' => now()->subDays(15), 'expected_delivery_date' => now()->subDays(10),
        //         'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => now()->subDays(15), 'updated_at' => now()->subDays(10)
        //     ],
        //     // PO 2: Pembelian ATK dari Supplier 2, status 'ordered' (masih dalam pengiriman)
        //     [
        //         'order_number' => 'PO-2025-002', 'supplier_id' => 2, 'user_id' => $users['inventory']->id,
        //         'total_amount' => 8400000, 'status' => 'ordered',
        //         'order_date' => now()->subDays(2), 'expected_delivery_date' => now()->addDays(5),
        //         'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2)
        //     ],
        //     // PO 3: Pembelian laptop dari Supplier 1, status 'completed' (pesanan selesai)
        //     [
        //         'order_number' => 'PO-2025-003', 'supplier_id' => 1, 'user_id' => $users['inventory']->id,
        //         'total_amount' => 196000000, 'status' => 'completed',
        //         'order_date' => now()->subMonth(), 'expected_delivery_date' => now()->subMonth()->addDays(7),
        //         'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => now()->subMonth(), 'updated_at' => now()->subMonth()->addDays(8)
        //     ],
        // ]);

        // Item PO juga dibuat oleh Inventory/Purchasing
        // DB::table('purchase_order_items')->insertOrIgnore([
        //     // Item untuk Purchase Order ID 1
        //     ['purchase_order_id' => 1, 'product_id' => 7, 'quantity' => 500, 'unit_cost' => 30000, 'total_cost' => 15000000, 'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => now()->subDays(15), 'updated_at' => now()->subDays(15)],
        //     ['purchase_order_id' => 1, 'product_id' => 2, 'quantity' => 100, 'unit_cost' => 180000, 'total_cost' => 18000000, 'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => now()->subDays(15), 'updated_at' => now()->subDays(15)],

        //     // Item untuk Purchase Order ID 2
        //     ['purchase_order_id' => 2, 'product_id' => 5, 'quantity' => 200, 'unit_cost' => 42000, 'total_cost' => 8400000, 'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2)],

        //     // Item untuk Purchase Order ID 3
        //     ['purchase_order_id' => 3, 'product_id' => 1, 'quantity' => 20, 'unit_cost' => 9800000, 'total_cost' => 196000000, 'created_by' => $users['inventory']->id, 'updated_by' => $users['inventory']->id, 'created_at' => now()->subMonth(), 'updated_at' => now()->subMonth()],
        // ]);

        // Jurnal dibuat oleh Accountant
        // Jurnal Umum dibuat oleh Accountant
        // DB::table('journal_entries')->insertOrIgnore([
        //     // Jurnal 1: Pencatatan Pendapatan dari Sales Order SO-2025-001
        //     [
        //         'date' => now()->subDays(10),
        //         'description' => 'Pencatatan pendapatan penjualan kredit dari SO-2025-001 kepada PT. Pelanggan Jaya',
        //         'referenceable_type' => 'App\\Models\\SalesOrder',
        //         'referenceable_id' => 1,
        //         'user_id' => $users['accountant']->id,
        //         'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id,
        //         'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)
        //     ],
        //     // Jurnal 2: Pencatatan HPP (COGS) untuk Sales Order SO-2025-001
        //     [
        //         'date' => now()->subDays(10),
        //         'description' => 'Pencatatan Harga Pokok Penjualan untuk SO-2025-001',
        //         'referenceable_type' => 'App\\Models\\SalesOrder',
        //         'referenceable_id' => 1,
        //         'user_id' => $users['accountant']->id,
        //         'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id,
        //         'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)
        //     ],
        //     // Jurnal 3: Pencatatan Pembelian Aset dari Purchase Order PO-2025-001
        //     [
        //         'date' => now()->subDays(10), // Tanggal barang diterima
        //         'description' => 'Pencatatan pembelian barang secara kredit dari PO-2025-001 dari Supplier Makmur',
        //         'referenceable_type' => 'App\\Models\\PurchaseOrder',
        //         'referenceable_id' => 1,
        //         'user_id' => $users['accountant']->id,
        //         'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id,
        //         'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)
        //     ],
        //     // Jurnal 4: Pencatatan Pembayaran Gaji Karyawan
        //     [
        //         'date' => now()->subDays(1),
        //         'description' => 'Pembayaran gaji karyawan periode Juni 2025 via Bank Mandiri',
        //         'referenceable_type' => null,
        //         'referenceable_id' => null,
        //         'user_id' => $users['accountant']->id,
        //         'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id,
        //         'created_at' => now()->subDays(1), 'updated_at' => now()->subDays(1)
        //     ],
        // ]);

        // // Item Jurnal dibuat oleh Accountant
        // DB::table('journal_entry_items')->insertOrIgnore([
        //     // === Item untuk Jurnal ID 1 (Pendapatan SO-001) ===
        //     // Debit: Piutang Usaha (Total tagihan ke pelanggan)
        //     ['journal_entry_id' => 1, 'account_id' => 5, 'debit' => 28305000, 'credit' => 0, 'description' => 'Piutang dari PT. Pelanggan Jaya', 'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],
        //     // Credit: Pendapatan Penjualan (Nilai penjualan sebelum PPN)
        //     ['journal_entry_id' => 1, 'account_id' => 31, 'debit' => 0, 'credit' => 25500000, 'description' => 'Pendapatan penjualan barang', 'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],
        //     // Credit: PPN Keluaran (Pajak yang dipungut dari penjualan)
        //     ['journal_entry_id' => 1, 'account_id' => 24, 'debit' => 0, 'credit' => 2805000, 'description' => 'PPN 11% atas penjualan', 'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],

        //     // === Item untuk Jurnal ID 2 (HPP SO-001) ===
        //     // Debit: Harga Pokok Penjualan (Biaya modal barang yang terjual)
        //     ['journal_entry_id' => 2, 'account_id' => 36, 'debit' => 19960000, 'credit' => 0, 'description' => 'HPP atas penjualan laptop (2x9.8jt) dan mouse (2x180rb)', 'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],
        //     // Credit: Persediaan Barang Jadi (Mengurangi nilai persediaan)
        //     ['journal_entry_id' => 2, 'account_id' => 7, 'debit' => 0, 'credit' => 19960000, 'description' => 'Pengurangan stok barang jadi', 'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],

        //     // === Item untuk Jurnal ID 3 (Pembelian PO-001) ===
        //     // Debit: Persediaan Barang Jadi (Menambah nilai persediaan)
        //     ['journal_entry_id' => 3, 'account_id' => 7, 'debit' => 33000000, 'credit' => 0, 'description' => 'Penambahan stok dari PO-2025-001', 'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],
        //     // Debit: PPN Masukan (Pajak yang bisa dikreditkan dari pembelian)
        //     ['journal_entry_id' => 3, 'account_id' => 12, 'debit' => 3630000, 'credit' => 0, 'description' => 'PPN 11% atas pembelian', 'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],
        //     // Credit: Utang Usaha (Total kewajiban ke supplier)
        //     ['journal_entry_id' => 3, 'account_id' => 21, 'debit' => 0, 'credit' => 36630000, 'description' => 'Utang kepada Supplier Makmur', 'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id, 'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)],

        //     // === Item untuk Jurnal ID 4 (Beban Gaji) ===
        //     // Debit: Beban Gaji dan Upah
        //     ['journal_entry_id' => 4, 'account_id' => 41, 'debit' => 50000000, 'credit' => 0, 'description' => 'Beban gaji Juni 2025', 'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id, 'created_at' => now()->subDays(1), 'updated_at' => now()->subDays(1)],
        //     // Credit: Bank Mandiri (Mengurangi kas di bank)
        //     ['journal_entry_id' => 4, 'account_id' => 4, 'debit' => 0, 'credit' => 50000000, 'description' => 'Pembayaran via Bank Mandiri', 'created_by' => $users['accountant']->id, 'updated_by' => $users['accountant']->id, 'created_at' => now()->subDays(1), 'updated_at' => now()->subDays(1)],
        // ]);

        // Departemen dibuat oleh HR
        DB::table('departments')->insertOrIgnore([
            [
                'name' => 'Keuangan & Akuntansi',
                'manager_id' => $users['accountant']->id,
                'created_by' => $users['hr']->id, 'updated_by' => $users['hr']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Penjualan & Pemasaran',
                'manager_id' => $users['sales']->id,
                'created_by' => $users['hr']->id, 'updated_by' => $users['hr']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Sumber Daya Manusia (HR)',
                'manager_id' => $users['hr']->id,
                'created_by' => $users['hr']->id, 'updated_by' => $users['hr']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Teknologi Informasi (IT)',
                'manager_id' => $users['super_admin']->id,
                'created_by' => $users['hr']->id, 'updated_by' => $users['hr']->id, 'created_at' => $now, 'updated_at' => $now
            ],
            [
                'name' => 'Operasional & Gudang',
                'manager_id' => $users['inventory']->id,
                'created_by' => $users['hr']->id, 'updated_by' => $users['hr']->id, 'created_at' => $now, 'updated_at' => $now
            ],
        ]);

       // Proyek dibuat oleh user Project

    //    $statuses = [
    //         ['name' => 'Baru', 'color' => '#0d6efd', 'order' => 1],
    //         ['name' => 'Dalam Pengerjaan', 'color' => '#ffc107', 'order' => 2],
    //         ['name' => 'Selesai', 'color' => '#198754', 'order' => 3],
    //         ['name' => 'Ditahan', 'color' => '#6c757d', 'order' => 4],
    //         ['name' => 'Dibatalkan', 'color' => '#dc3545', 'order' => 5],
    //     ];

    //     foreach ($statuses as &$status) {
    //         $status['is_active'] = true;
    //         $status['created_by'] = $users['project']->id;
    //         $status['updated_by'] = $users['project']->id;
    //         $status['created_at'] = $now;
    //         $status['updated_at'] = $now;
    //     }

        // DB::table('project_statuses')->insert($statuses);
        // DB::table('projects')->insertOrIgnore([
        //     [
        //         'name' => 'Implementasi Sistem ERP',
        //         'description' => 'Migrasi dan implementasi sistem ERP baru untuk internal perusahaan.',
        //         'customer_id' => null, // Proyek Internal
        //         'manager_id' => $users['project']->id,
        //         'start_date' => now()->subMonths(2), 'end_date' => now()->addMonths(3),
        //         'status' => 'in_progress',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now()->subMonths(2), 'updated_at' => now()
        //     ],
        //     [
        //         'name' => 'Pengembangan Website E-commerce',
        //         'description' => 'Membuat platform e-commerce untuk pelanggan CV. Mitra Usaha.',
        //         'customer_id' => 2, // CV. Mitra Usaha
        //         'manager_id' => $users['project']->id,
        //         'start_date' => now()->subMonths(4), 'end_date' => now()->subMonth(),
        //         'status' => 'completed',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now()->subMonths(4), 'updated_at' => now()->subMonth()
        //     ],
        //     [
        //         'name' => 'Kampanye Pemasaran Digital Q4 2025',
        //         'description' => 'Perencanaan dan eksekusi kampanye digital untuk akhir tahun.',
        //         'customer_id' => null, // Proyek Internal
        //         'manager_id' => $users['sales']->id,
        //         'start_date' => now()->addMonths(2), 'end_date' => now()->addMonths(5),
        //         'status' => 'planned',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now(), 'updated_at' => now()
        //     ],
        // ]);

        // Task dibuat oleh user Project
        // DB::table('tasks')->insertOrIgnore([
            // === Tugas untuk Proyek 1: Implementasi Sistem ERP (ID: 1) ===
        //     [
        //         'project_id' => 1, 'title' => 'Analisis Kebutuhan Pengguna',
        //         'description' => 'Wawancara dengan setiap kepala departemen untuk mengumpulkan kebutuhan sistem.',
        //         'assignee_id' => $users['project']->id, 'due_date' => now()->subMonths(1), 'status' => 'completed',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now()->subMonths(2), 'updated_at' => now()->subMonths(1)
        //     ],
        //     [
        //         'project_id' => 1, 'title' => 'Pengembangan Modul Akuntansi',
        //         'description' => 'Mengembangkan fitur jurnal umum, laporan laba rugi, dan neraca.',
        //         'assignee_id' => $users['accountant']->id, 'due_date' => now()->addWeeks(2), 'status' => 'in_progress',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now()->subMonths(1), 'updated_at' => now()
        //     ],
        //     [
        //         'project_id' => 1, 'title' => 'Pengembangan Modul Inventaris',
        //         'description' => 'Mengembangkan fitur manajemen produk, stok, dan purchase order.',
        //         'assignee_id' => $users['inventory']->id, 'due_date' => now()->addWeeks(3), 'status' => 'in_progress',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now()->subMonths(1), 'updated_at' => now()
        //     ],
        //     [
        //         'project_id' => 1, 'title' => 'UAT (User Acceptance Test)',
        //         'description' => 'Pengujian sistem oleh pengguna akhir dari setiap departemen.',
        //         'assignee_id' => $users['project']->id, 'due_date' => now()->addMonths(2), 'status' => 'todo',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now(), 'updated_at' => now()
        //     ],

        //     // === Tugas untuk Proyek 2: Pengembangan Website E-commerce (ID: 2) ===
        //     [
        //         'project_id' => 2, 'title' => 'Desain UI/UX Mockup',
        //         'description' => 'Membuat mockup dan prototipe untuk alur pengguna di website.',
        //         'assignee_id' => $users['project']->id, 'due_date' => now()->subMonths(3), 'status' => 'completed',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now()->subMonths(4), 'updated_at' => now()->subMonths(3)
        //     ],
        //     [
        //         'project_id' => 2, 'title' => 'Pengembangan Frontend & Backend',
        //         'description' => 'Slice UI/UX ke HTML/CSS/JS dan membuat API untuk manajemen produk.',
        //         'assignee_id' => $users['super_admin']->id, 'due_date' => now()->subMonths(2), 'status' => 'completed',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now()->subMonths(3), 'updated_at' => now()->subMonths(2)
        //     ],
        //     [
        //         'project_id' => 2, 'title' => 'Deployment ke Server Produksi',
        //         'description' => 'Melakukan deployment website ke server live.',
        //         'assignee_id' => $users['super_admin']->id, 'due_date' => now()->subMonth(), 'status' => 'completed',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now()->subMonths(2), 'updated_at' => now()->subMonth()
        //     ],

        //     // === Tugas untuk Proyek 3: Kampanye Pemasaran Digital Q4 (ID: 3) ===
        //     [
        //         'project_id' => 3, 'title' => 'Riset Kata Kunci & Target Audiens',
        //         'description' => 'Menganalisis kata kunci potensial dan demografi target untuk iklan.',
        //         'assignee_id' => $users['sales']->id, 'due_date' => now()->addMonths(2)->addWeeks(2), 'status' => 'todo',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now(), 'updated_at' => now()
        //     ],
        //     [
        //         'project_id' => 3, 'title' => 'Pembuatan Konten Iklan',
        //         'description' => 'Membuat materi visual dan copy-writing untuk kampanye.',
        //         'assignee_id' => $users['sales']->id, 'due_date' => now()->addMonths(3), 'status' => 'todo',
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => now(), 'updated_at' => now()
        //     ],
        // ]);

        // $projectManager = User::where('email', 'project.manager@example.com')->first() ?? $users['project'];
        // $teamMember1 = User::where('email', 'sales.executive@example.com')->first() ?? $users['project'];
        // $teamMember2 = User::where('email', 'accountant@example.com')->first() ?? $users['project'];
        // $customer = Customer::first();
        // $statusInProgress = ProjectStatus::where('name', 'Dalam Pengerjaan')->first();
        // $statusNew = ProjectStatus::where('name', 'Baru')->first();

        // // =================== PROYEK 1 ===================
        // $project1Id = DB::table('projects')->insertGetId([
        //     'code' => 'PROJ-2025-001',
        //     'name' => 'Pengembangan Sistem ERP Internal',
        //     'description' => 'Proyek untuk membangun dan mengimplementasikan sistem ERP baru untuk seluruh departemen.',
        //     'customer_id' => null, // Proyek internal
        //     'manager_id' => $users['project']->id,
        //     'project_status_id' => $statusInProgress->id,
        //     'start_date' => Carbon::now()->subMonths(2),
        //     'end_date' => Carbon::now()->addMonths(6),
        //     'budget' => 500000000,
        //     'progress' => 25,
        //     'priority' => 'high',
        //     'created_by' => $users['project']->id,
        //     'updated_by' => $users['project']->id,
        //     'created_at' => $now,
        //     'updated_at' => $now,
        // ]);

        // Tambahkan anggota tim untuk Proyek 1
        // DB::table('project_members')->insert([
        //     [
        //         'project_id' => $project1Id,
        //         'user_id' => $users['project']->id,
        //         'role' => 'manager',
        //         'joined_date' => Carbon::now()->subMonths(2),
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => $now, 'updated_at' => $now,
        //     ],
        //     [
        //         'project_id' => $project1Id,
        //         'user_id' => $teamMember1->id,
        //         'role' => 'member',
        //         'joined_date' => Carbon::now()->subMonths(2),
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => $now, 'updated_at' => $now,
        //     ],
        //     [
        //         'project_id' => $project1Id,
        //         'user_id' => $teamMember2->id,
        //         'role' => 'member',
        //         'joined_date' => Carbon::now()->subMonths(1),
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => $now, 'updated_at' => $now,
        //     ],
        // ]);


        // =================== PROYEK 2 ===================
        // $project2Id = DB::table('projects')->insertGetId([
        //     'code' => 'PROJ-2025-002',
        //     'name' => 'Implementasi Website E-commerce Klien A',
        //     'description' => 'Membangun platform e-commerce untuk Klien A dari awal hingga akhir.',
        //     'customer_id' => $customer->id ?? null,
        //     'manager_id' => $users['project']->id,
        //     'project_status_id' => $statusNew->id,
        //     'start_date' => Carbon::now()->addWeek(),
        //     'end_date' => Carbon::now()->addMonths(4),
        //     'budget' => 250000000,
        //     'progress' => 0,
        //     'priority' => 'medium',
        //     'created_by' => $users['project']->id,
        //     'updated_by' => $users['project']->id,
        //     'created_at' => $now,
        //     'updated_at' => $now,
        // ]);

         // Tambahkan anggota tim untuk Proyek 2
        //  DB::table('project_members')->insert([
        //     [
        //         'project_id' => $project2Id,
        //         'user_id' => $users['project']->id,
        //         'role' => 'manager',
        //         'joined_date' => Carbon::now(),
        //         'created_by' => $users['project']->id, 'updated_by' => $users['project']->id, 'created_at' => $now, 'updated_at' => $now,
        //     ]
        // ]);
    }
}
