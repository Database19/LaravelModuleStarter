<?php

require_once __DIR__ . '/vendor/autoload.php';

use Modules\Inventory\Models\Stock;
use App\Models\Product;
use App\Models\Warehouse;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $product = Product::first();
    $warehouse = Warehouse::first();

    if ($product && $warehouse) {
        $stock = Stock::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 100,
            'unit_price' => 25000,
            'total_value' => 2500000,
            'notes' => 'Initial stock entry',
            'company_id' => 1,
            'created_by' => 1
        ]);
        echo "Stock created with ID: {$stock->id}\n";
        echo "Product: {$product->name}\n";
        echo "Warehouse: {$warehouse->name}\n";
    } else {
        echo "No products or warehouses found\n";
        echo "Products count: " . Product::count() . "\n";
        echo "Warehouses count: " . Warehouse::count() . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
