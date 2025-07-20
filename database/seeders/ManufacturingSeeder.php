<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;

class ManufacturingSeeder extends Seeder
{
    public function run(): void
    {
        $production_user = User::where('email', 'production@erp.test')->first();
        $inventory_user = User::where('email', 'inventory@erp.test')->first();
        $now = now();

        // --- 1. Ambil Produk Jadi dan Komponen yang Sudah Ada ---
        $laptop_probook = Product::where('sku', 'ELE-001')->first();
        $microcontroller = Product::where('sku', 'KOM-001')->first();

        // --- 2. Buat Komponen Baru yang Dibutuhkan untuk Resep ---
        // (Untuk tujuan demo, kita tambahkan langsung di sini)
        $casing = Product::create(['sku' => 'KOM-002', 'name' => 'Casing Laptop 14 inch', 'product_category_id' => 10, 'price' => 3500000 ,'cost' => 350000, 'quantity' => 200, 'unit_of_measurement' => 'pcs', 'created_by' => $inventory_user->id, 'updated_by' => $inventory_user->id]);
        $lcd_screen = Product::create(['sku' => 'KOM-003', 'name' => 'Layar LCD 14 inch', 'product_category_id' => 10, 'price' => 3500000 ,'cost' => 1200000, 'quantity' => 150, 'unit_of_measurement' => 'pcs', 'created_by' => $inventory_user->id, 'updated_by' => $inventory_user->id]);
        $battery = Product::create(['sku' => 'KOM-004', 'name' => 'Baterai Laptop 4-cell', 'product_category_id' => 10, 'price' => 3500000 ,'cost' => 600000, 'quantity' => 100, 'unit_of_measurement' => 'pcs', 'created_by' => $inventory_user->id, 'updated_by' => $inventory_user->id]);

        // --- 3. Buat Resep (Bill of Materials) ---
        $bom_id = DB::table('boms')->insertGetId([
            'product_id' => $laptop_probook->id,
            'name' => 'Resep Standard Laptop ProBook 14 G9',
            'description' => 'Resep untuk memproduksi 1 unit Laptop ProBook',
            'created_by' => $production_user->id,
            'updated_by' => $production_user->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('bom_items')->insert([
            ['bom_id' => $bom_id, 'component_product_id' => $casing->id, 'quantity' => 1],
            ['bom_id' => $bom_id, 'component_product_id' => $lcd_screen->id, 'quantity' => 1],
            ['bom_id' => $bom_id, 'component_product_id' => $battery->id, 'quantity' => 1],
            ['bom_id' => $bom_id, 'component_product_id' => $microcontroller->id, 'quantity' => 2],
        ]);

        // --- 4. Buat Perintah Produksi (Manufacturing Orders) ---
        DB::table('manufacturing_orders')->insert([
            // MO yang sudah selesai
            [
                'mo_number' => 'MO-2025-001',
                'product_id' => $laptop_probook->id,
                'bom_id' => $bom_id,
                'quantity_to_produce' => 10,
                'quantity_produced' => 10,
                'start_date' => now()->subDays(10),
                'completed_date' => now()->subDays(5),
                'status' => 'Completed',
                'created_by' => $production_user->id, 'updated_by' => $production_user->id, 'created_at' => $now, 'updated_at' => $now
            ],
            // MO yang sedang berjalan
            [
                'mo_number' => 'MO-2025-002',
                'product_id' => $laptop_probook->id,
                'bom_id' => $bom_id,
                'quantity_to_produce' => 20,
                'quantity_produced' => 5, // Baru selesai 5
                'start_date' => now()->subDays(2),
                'completed_date' => null,
                'status' => 'In Progress',
                'created_by' => $production_user->id, 'updated_by' => $production_user->id, 'created_at' => $now, 'updated_at' => $now
            ],
        ]);
    }
}
