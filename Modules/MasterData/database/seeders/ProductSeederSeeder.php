<?php

namespace Modules\MasterData\Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeederSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCategory::create(['name' => 'Elektronik']);

        Product::create([
            'name' => 'Printer Canon',
            'sku' => 'PRN001',
            'category_id' => 1,
            'unit' => 'pcs',
            'price' => 1500000,
            'description' => 'Printer warna',
        ]);

    }
}
