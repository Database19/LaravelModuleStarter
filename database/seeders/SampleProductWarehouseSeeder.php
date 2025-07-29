<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductCategory;
use App\Models\Unit;

class SampleProductWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample warehouses
        $warehouses = [
            [
                'name' => 'Main Warehouse',
                'code' => 'MW001',
                'location' => 'Jakarta, Indonesia',
                'is_active' => true,
                'company_id' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'name' => 'Secondary Warehouse',
                'code' => 'SW001',
                'location' => 'Surabaya, Indonesia',
                'is_active' => true,
                'company_id' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'name' => 'Distribution Center',
                'code' => 'DC001',
                'location' => 'Bandung, Indonesia',
                'is_active' => true,
                'company_id' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ]
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::firstOrCreate(['code' => $warehouse['code']], $warehouse);
        }

        // Check if ProductCategory model exists, if not skip
        try {
            $category = ProductCategory::firstOrCreate(
                ['name' => 'General Products'],
                [
                    'name' => 'General Products',
                    'description' => 'General product category',
                    'is_active' => true,
                    'company_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1
                ]
            );
        } catch (\Exception $e) {
            $category = null;
        }

        // Check if Unit model exists, if not skip
        try {
            $unit = Unit::firstOrCreate(
                ['name' => 'Pieces'],
                [
                    'name' => 'Pieces',
                    'symbol' => 'pcs',
                    'is_active' => true,
                    'company_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1
                ]
            );
        } catch (\Exception $e) {
            $unit = null;
        }

        // Create sample products
        $products = [
            [
                'name' => 'Sample Product A',
                'sku' => 'SP-001',
                'barcode' => '1234567890001',
                'description' => 'This is a sample product A for testing',
                'product_category_id' => $category?->id,
                'unit_id' => $unit?->id,
                'type' => 'product',
                'price' => 50000,
                'cost' => 30000,
                'quantity' => 100,
                'min_stock' => 10,
                'track_stock' => true,
                'is_active' => true,
                'company_id' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'name' => 'Sample Product B',
                'sku' => 'SP-002',
                'barcode' => '1234567890002',
                'description' => 'This is a sample product B for testing',
                'product_category_id' => $category?->id,
                'unit_id' => $unit?->id,
                'type' => 'product',
                'price' => 75000,
                'cost' => 45000,
                'quantity' => 150,
                'min_stock' => 15,
                'track_stock' => true,
                'is_active' => true,
                'company_id' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'name' => 'Sample Product C',
                'sku' => 'SP-003',
                'barcode' => '1234567890003',
                'description' => 'This is a sample product C for testing',
                'product_category_id' => $category?->id,
                'unit_id' => $unit?->id,
                'type' => 'product',
                'price' => 100000,
                'cost' => 60000,
                'quantity' => 200,
                'min_stock' => 20,
                'track_stock' => true,
                'is_active' => true,
                'company_id' => 1,
                'created_by' => 1,
                'updated_by' => 1
            ]
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['sku' => $product['sku']], $product);
        }

        $this->command->info('Sample products and warehouses created successfully!');
    }
}
