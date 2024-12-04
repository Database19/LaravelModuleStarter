<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('materials')->insert([
            [
                'material_code' => 'MAT-001',
                'name' => 'Steel Plate',
                'description' => 'High quality steel plate',
                'unit_of_measure' => 'kg',
                'material_type' => 'raw',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'material_code' => 'MAT-002',
                'name' => 'Aluminum Sheet',
                'description' => 'Lightweight aluminum sheet',
                'unit_of_measure' => 'm2',
                'material_type' => 'raw',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Products
        DB::table('products')->insert([
            [
                'product_code' => 'PROD-001',
                'name' => 'Steel Beam',
                'description' => 'Structural steel beam',
                'category_id' => null,
                'unit_of_measure' => 'unit',
                'price' => 150.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Bill of Materials
        DB::table('bill_of_materials')->insert([
            [
                'product_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // BOM Details
        DB::table('bom_details')->insert([
            [
                'bom_id' => 1,
                'material_id' => 1,
                'quantity' => 10.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bom_id' => 1,
                'material_id' => 2,
                'quantity' => 20.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Suppliers
        DB::table('suppliers')->insert([
            [
                'supplier_code' => 'SUP-001',
                'name' => 'Supplier A',
                'address' => '123 Main Street',
                'contact_person' => 'John Doe',
                'contact_phone' => '1234567890',
                'email' => 'supplier_a@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Machines
        DB::table('machines')->insert([
            [
                'machine_code' => 'MCH-001',
                'name' => 'CNC Machine',
                'description' => 'High precision CNC machine',
                'purchase_date' => '2022-01-15',
                'maintenance_schedule' => 'Monthly',
                'status' => 'operational',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Work Centers
        DB::table('work_centers')->insert([
            [
                'work_center_code' => 'WC-001',
                'name' => 'Assembly Line 1',
                'description' => 'Main assembly line',
                'machine_id' => 1,
                'location' => 'Factory A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Warehouses
        DB::table('warehouses')->insert([
            [
                'warehouse_code' => 'WH-001',
                'name' => 'Main Warehouse',
                'location' => 'Industrial Area',
                'capacity' => 1000.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Customers
        DB::table('customers')->insert([
            [
                'customer_code' => 'CUST-001',
                'name' => 'Customer A',
                'address' => '456 Elm Street',
                'contact_person' => 'Jane Smith',
                'contact_phone' => '0987654321',
                'email' => 'customer_a@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
