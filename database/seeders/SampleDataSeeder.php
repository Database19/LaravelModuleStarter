<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🚀 Starting SampleDataSeeder...\n";

        $this->createSampleMasterData();
        $this->createSampleTransactionData();

        echo "✅ SampleDataSeeder completed successfully!\n";
    }

    private function createSampleMasterData(): void
    {
        echo "📦 Creating sample master data...\n";

        // Get companies
        $companies = Company::all();

        foreach ($companies as $company) {
            $this->createCompanySampleData($company);
        }
    }

    private function createCompanySampleData(Company $company): void
    {
        echo "  🏢 Creating sample data for {$company->name}...\n";

        // Get first user for this company to use as creator
        $firstUser = $company->users()->first();
        if (!$firstUser) {
            echo "    ⚠️ No users found for company {$company->name}, skipping...\n";
            return;
        }

        // Create Brand if not exists
        $brands = [
            ['name' => 'Samsung', 'logo_url' => null],
            ['name' => 'Apple', 'logo_url' => null],
            ['name' => 'Microsoft', 'logo_url' => null],
            ['name' => 'Dell', 'logo_url' => null],
            ['name' => 'HP', 'logo_url' => null],
        ];

        foreach ($brands as $brandData) {
            Brand::firstOrCreate([
                'name' => $brandData['name'],
                'company_id' => $company->id
            ], array_merge($brandData, [
                'is_active' => true,
                'created_by' => $firstUser->id,
                'updated_by' => $firstUser->id,
                'company_id' => $company->id,
            ]));
        }

        // Create Units if not exists
        $units = [
            ['name' => 'Piece', 'short_code' => 'pcs'],
            ['name' => 'Kilogram', 'short_code' => 'kg'],
            ['name' => 'Box', 'short_code' => 'box'],
            ['name' => 'Set', 'short_code' => 'set'],
            ['name' => 'Meter', 'short_code' => 'm'],
        ];

        foreach ($units as $unitData) {
            Unit::firstOrCreate([
                'short_code' => $unitData['short_code'],
                'company_id' => $company->id
            ], array_merge($unitData, [
                'is_active' => true,
                'created_by' => $firstUser->id,
                'updated_by' => $firstUser->id,
                'company_id' => $company->id,
            ]));
        }

        // Create Product Categories
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Produk elektronik dan gadget'],
            ['name' => 'Komputer & Laptop', 'description' => 'Komputer, laptop, dan aksesoris'],
            ['name' => 'Furniture', 'description' => 'Meja, kursi, dan perabotan kantor'],
            ['name' => 'Alat Tulis Kantor', 'description' => 'Stationery dan keperluan kantor'],
        ];

        foreach ($categories as $categoryData) {
            ProductCategory::firstOrCreate([
                'name' => $categoryData['name'],
                'company_id' => $company->id
            ], array_merge($categoryData, [
                'is_active' => true,
                'created_by' => $firstUser->id,
                'updated_by' => $firstUser->id,
                'company_id' => $company->id,
            ]));
        }

        // Create Warehouses
        $warehouses = [
            [
                'name' => 'Gudang Utama ' . $company->domain,
                'code' => strtoupper($company->domain) . '_GU01',
                'location' => 'Jakarta Pusat',
                'is_active' => true,
            ],
            [
                'name' => 'Gudang Cabang ' . $company->domain,
                'code' => strtoupper($company->domain) . '_GC01',
                'location' => 'Jakarta Timur',
                'is_active' => true,
            ],
        ];

        foreach ($warehouses as $warehouseData) {
            Warehouse::firstOrCreate([
                'name' => $warehouseData['name'],
                'company_id' => $company->id
            ], array_merge($warehouseData, [
                'created_by' => $firstUser->id,
                'updated_by' => $firstUser->id,
                'company_id' => $company->id,
            ]));
        }

        // Create Products
        $products = [
            [
                'name' => 'Smartphone Samsung Galaxy S24',
                'sku' => $company->domain . '_SAMS24',
                'description' => 'Smartphone flagship terbaru dari Samsung',
                'category' => 'Elektronik',
                'brand' => 'Samsung',
                'unit' => 'pcs',
                'purchase_price' => 8000000,
                'selling_price' => 10000000,
                'stock' => 50
            ],
            [
                'name' => 'Laptop Dell Inspiron 15',
                'sku' => $company->domain . '_DELL15',
                'description' => 'Laptop untuk kebutuhan bisnis dan pribadi',
                'category' => 'Komputer & Laptop',
                'brand' => 'Dell',
                'unit' => 'pcs',
                'purchase_price' => 6000000,
                'selling_price' => 7500000,
                'stock' => 25
            ],
            [
                'name' => 'Meja Kantor Executive',
                'sku' => $company->domain . '_DESK01',
                'description' => 'Meja kantor berkualitas tinggi',
                'category' => 'Furniture',
                'brand' => 'HP', // Just for example
                'unit' => 'pcs',
                'purchase_price' => 1500000,
                'selling_price' => 2000000,
                'stock' => 10
            ],
        ];

        foreach ($products as $productData) {
            $category = ProductCategory::where('name', $productData['category'])
                ->where('company_id', $company->id)->first();
            $brand = Brand::where('name', $productData['brand'])
                ->where('company_id', $company->id)->first();
            $unit = Unit::where('short_code', $productData['unit'])
                ->where('company_id', $company->id)->first();

            Product::firstOrCreate([
                'sku' => $productData['sku'],
                'company_id' => $company->id
            ], [
                'name' => $productData['name'],
                'sku' => $productData['sku'],
                'description' => $productData['description'],
                'product_category_id' => $category?->id,
                'brand_id' => $brand?->id,
                'unit_id' => $unit?->id,
                'cost' => $productData['purchase_price'],
                'price' => $productData['selling_price'],
                'quantity' => $productData['stock'],
                'min_stock' => 5,
                'type' => 'product',
                'track_stock' => true,
                'is_active' => true,
                'created_by' => $firstUser->id,
                'updated_by' => $firstUser->id,
                'company_id' => $company->id,
            ]);
        }

        // Create Customers
        $customers = [
            [
                'name' => 'PT. Teknologi Digital Indonesia',
                'email' => 'info@teknodigital.co.id',
                'phone' => '021-1234567',
                'address' => 'Jl. Sudirman No. 123, Jakarta',
                'company_name' => 'PT. Teknologi Digital Indonesia',
                'type' => 'company',
                'tax_id' => '01.234.567.8-901.000',
            ],
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@email.com',
                'phone' => '0812-3456-7890',
                'address' => 'Jl. Merdeka No. 45, Bandung',
                'type' => 'individual',
            ],
            [
                'name' => 'CV. Maju Bersama',
                'email' => 'admin@majubersama.com',
                'phone' => '022-9876543',
                'address' => 'Jl. Asia Afrika No. 100, Bandung',
                'company_name' => 'CV. Maju Bersama',
                'type' => 'company',
                'tax_id' => '02.345.678.9-012.000',
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::firstOrCreate([
                'email' => $customerData['email'],
                'company_id' => $company->id
            ], array_merge($customerData, [
                'is_active' => true,
                'created_by' => $firstUser->id,
                'updated_by' => $firstUser->id,
                'company_id' => $company->id,
            ]));
        }

        // Create Suppliers
        $suppliers = [
            [
                'name' => 'PT. Elektronik Supplier',
                'contact_person' => 'Budi Santoso',
                'email' => 'supplier@elektronik.co.id',
                'phone' => '021-7654321',
                'address' => 'Jl. Industri No. 89, Jakarta',
                'tax_id' => '03.456.789.0-123.000',
                'bank_name' => 'Bank Mandiri',
                'bank_account' => '1234567890',
            ],
            [
                'name' => 'CV. Furniture Supplier',
                'contact_person' => 'Siti Aminah',
                'email' => 'admin@furniture-supplier.com',
                'phone' => '022-1357924',
                'address' => 'Jl. Furniture No. 67, Bandung',
                'tax_id' => '04.567.890.1-234.000',
                'bank_name' => 'Bank BCA',
                'bank_account' => '0987654321',
            ],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::firstOrCreate([
                'email' => $supplierData['email'],
                'company_id' => $company->id
            ], array_merge($supplierData, [
                'is_active' => true,
                'created_by' => $firstUser->id,
                'updated_by' => $firstUser->id,
                'company_id' => $company->id,
            ]));
        }
    }

    private function createSampleTransactionData(): void
    {
        echo "💰 Creating sample transaction data...\n";

        // Note: Transaction data creation would require more complex logic
        // For now, we'll create basic structure and let users know
        echo "  ℹ️  Transaction data seeding requires proper product and customer relationships\n";
        echo "  ℹ️  Consider implementing specific transaction seeders for each module\n";
    }
}
