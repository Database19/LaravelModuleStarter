<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Department;
use App\Models\Position;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\ProjectStatus;
use App\Models\TaskStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Multitenancy\Models\Tenant;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed untuk setiap company
        $companies = Company::all();

        foreach ($companies as $company) {
            // Set company sebagai current tenant
            Tenant::find($company->id)?->makeCurrent();

            $this->seedCompanyMasterData($company);
        }

        // Clear tenant context setelah seeding
        Tenant::forgetCurrent();
    }

    private function seedCompanyMasterData(Company $company): void
    {
        $adminUser = User::where('company_id', $company->id)
                        ->whereHas('roles', fn($q) => $q->where('name', 'Admin'))
                        ->first();

        if (!$adminUser) return;

        // 1. Seed Units
        $this->seedUnits($company, $adminUser);

        // 2. Seed Brands
        $this->seedBrands($company, $adminUser);

        // 3. Seed Product Categories
        $this->seedProductCategories($company, $adminUser);

        // 4. Seed Products
        $this->seedProducts($company, $adminUser);

        // 5. Seed Warehouses
        $this->seedWarehouses($company, $adminUser);

        // 6. Seed Departments & Positions
        $this->seedDepartmentsAndPositions($company, $adminUser);

        // 7. Seed Chart of Accounts
        $this->seedChartOfAccounts($company, $adminUser);

        // 8. Seed Customers
        $this->seedCustomers($company, $adminUser);

        // 9. Seed Suppliers
        $this->seedSuppliers($company, $adminUser);

        // 10. Seed Project & Task Statuses
        $this->seedProjectAndTaskStatuses($company, $adminUser);
    }

    private function seedUnits(Company $company, User $adminUser): void
    {
        $units = [
            ['name' => 'Pieces', 'short_code' => 'pcs'],
            ['name' => 'Kilogram', 'short_code' => 'kg'],
            ['name' => 'Gram', 'short_code' => 'gr'],
            ['name' => 'Liter', 'short_code' => 'ltr'],
            ['name' => 'Meter', 'short_code' => 'm'],
            ['name' => 'Centimeter', 'short_code' => 'cm'],
            ['name' => 'Box', 'short_code' => 'box'],
            ['name' => 'Pack', 'short_code' => 'pack'],
            ['name' => 'Dozen', 'short_code' => 'dzn'],
            ['name' => 'Ton', 'short_code' => 'ton'],
        ];

        foreach ($units as $unit) {
            Unit::create([
                'name' => $unit['name'],
                'short_code' => $unit['short_code'],
                'is_active' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }
    }

    private function seedBrands(Company $company, User $adminUser): void
    {
        $brands = [
            'Samsung', 'Apple', 'Microsoft', 'Canon', 'HP',
            'Dell', 'Logitech', 'Sony', 'LG', 'Generic'
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand,
                'is_active' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }
    }

    private function seedProductCategories(Company $company, User $adminUser): void
    {
        // Parent categories
        $electronics = ProductCategory::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices and accessories',
            'is_active' => true,
            'created_by' => $adminUser->id,
            'updated_by' => $adminUser->id,
            'company_id' => $company->id,
        ]);

        $office = ProductCategory::create([
            'name' => 'Office Supplies',
            'description' => 'Office stationery and supplies',
            'is_active' => true,
            'created_by' => $adminUser->id,
            'updated_by' => $adminUser->id,
            'company_id' => $company->id,
        ]);

        $rawMaterials = ProductCategory::create([
            'name' => 'Raw Materials',
            'description' => 'Manufacturing raw materials',
            'is_active' => true,
            'created_by' => $adminUser->id,
            'updated_by' => $adminUser->id,
            'company_id' => $company->id,
        ]);

        // Child categories
        $childCategories = [
            ['name' => 'Computers', 'parent_id' => $electronics->id],
            ['name' => 'Mobile Phones', 'parent_id' => $electronics->id],
            ['name' => 'Printers', 'parent_id' => $electronics->id],
            ['name' => 'Stationery', 'parent_id' => $office->id],
            ['name' => 'Paper Products', 'parent_id' => $office->id],
            ['name' => 'Steel', 'parent_id' => $rawMaterials->id],
            ['name' => 'Plastic', 'parent_id' => $rawMaterials->id],
        ];

        foreach ($childCategories as $category) {
            ProductCategory::create([
                'name' => $category['name'],
                'parent_id' => $category['parent_id'],
                'is_active' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }
    }

    private function seedProducts(Company $company, User $adminUser): void
    {
        $categories = ProductCategory::where('company_id', $company->id)->get();
        $brands = Brand::where('company_id', $company->id)->get();
        $unit = Unit::where('company_id', $company->id)->first();

        if ($categories->isEmpty() || $brands->isEmpty() || !$unit) return;

        $products = [
            ['sku' => 'LAPTOP-001', 'name' => 'Dell Laptop Inspiron 15', 'price' => 8500000, 'cost' => 7500000],
            ['sku' => 'PHONE-001', 'name' => 'Samsung Galaxy S24', 'price' => 12000000, 'cost' => 10500000],
            ['sku' => 'PRINTER-001', 'name' => 'HP LaserJet Pro', 'price' => 3500000, 'cost' => 3000000],
            ['sku' => 'PAPER-001', 'name' => 'A4 Paper (1 Ream)', 'price' => 65000, 'cost' => 50000],
            ['sku' => 'PEN-001', 'name' => 'Ballpoint Pen (Blue)', 'price' => 5000, 'cost' => 3000],
            ['sku' => 'STEEL-001', 'name' => 'Steel Plate 10mm', 'price' => 500000, 'cost' => 400000],
        ];

        foreach ($products as $index => $product) {
            Product::create([
                'sku' => $product['sku'],
                'barcode' => '123456789' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'name' => $product['name'],
                'description' => 'Sample product: ' . $product['name'],
                'product_category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
                'unit_id' => $unit->id,
                'type' => 'product',
                'price' => $product['price'],
                'cost' => $product['cost'],
                'quantity' => rand(10, 100),
                'min_stock' => rand(5, 15),
                'track_stock' => true,
                'is_active' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }
    }

    private function seedWarehouses(Company $company, User $adminUser): void
    {
        $warehouses = [
            ['name' => 'Main Warehouse', 'code' => 'WH-MAIN', 'location' => 'Jakarta Pusat'],
            ['name' => 'Secondary Warehouse', 'code' => 'WH-SEC', 'location' => 'Jakarta Timur'],
            ['name' => 'Finished Goods', 'code' => 'WH-FG', 'location' => 'Jakarta Selatan'],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create([
                'name' => $warehouse['name'],
                'code' => $warehouse['code'],
                'location' => $warehouse['location'],
                'is_active' => true,
                'manager_id' => $adminUser->id,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }
    }

    private function seedDepartmentsAndPositions(Company $company, User $adminUser): void
    {
        // Departments
        $departments = [
            ['name' => 'Finance & Accounting', 'code' => 'FIN'],
            ['name' => 'Sales & Marketing', 'code' => 'SAL'],
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Operations', 'code' => 'OPS'],
            ['name' => 'Information Technology', 'code' => 'IT'],
            ['name' => 'Production', 'code' => 'PRD'],
        ];

        foreach ($departments as $dept) {
            $department = Department::create([
                'name' => $dept['name'],
                'code' => $dept['code'],
                'description' => 'Department: ' . $dept['name'],
                'is_active' => true,
                'manager_id' => $adminUser->id,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);

            // Positions for each department
            $positions = [
                ['name' => 'Manager', 'code' => $dept['code'] . '-MGR'],
                ['name' => 'Senior Staff', 'code' => $dept['code'] . '-SR'],
                ['name' => 'Junior Staff', 'code' => $dept['code'] . '-JR'],
            ];

            foreach ($positions as $pos) {
                Position::create([
                    'name' => $pos['name'],
                    'code' => $pos['code'],
                    'description' => $pos['name'] . ' in ' . $dept['name'],
                    'department_id' => $department->id,
                    'base_salary' => match($pos['name']) {
                        'Manager' => 15000000,
                        'Senior Staff' => 10000000,
                        'Junior Staff' => 6000000,
                        default => 5000000
                    },
                    'is_active' => true,
                    'created_by' => $adminUser->id,
                    'updated_by' => $adminUser->id,
                    'company_id' => $company->id,
                ]);
            }
        }
    }

    private function seedChartOfAccounts(Company $company, User $adminUser): void
    {
        $accounts = [
            // ASSETS
            ['account_code' => '1000', 'name' => 'CURRENT ASSETS', 'type' => 'asset', 'sub_type' => 'current_asset'],
            ['account_code' => '1100', 'name' => 'Cash and Cash Equivalents', 'type' => 'asset', 'sub_type' => 'current_asset'],
            ['account_code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'asset', 'sub_type' => 'current_asset'],
            ['account_code' => '1300', 'name' => 'Inventory', 'type' => 'asset', 'sub_type' => 'current_asset'],
            ['account_code' => '1500', 'name' => 'FIXED ASSETS', 'type' => 'asset', 'sub_type' => 'fixed_asset'],
            ['account_code' => '1510', 'name' => 'Equipment', 'type' => 'asset', 'sub_type' => 'fixed_asset'],

            // LIABILITIES
            ['account_code' => '2000', 'name' => 'CURRENT LIABILITIES', 'type' => 'liability', 'sub_type' => 'current_liability'],
            ['account_code' => '2100', 'name' => 'Accounts Payable', 'type' => 'liability', 'sub_type' => 'current_liability'],
            ['account_code' => '2200', 'name' => 'Short-term Loans', 'type' => 'liability', 'sub_type' => 'current_liability'],

            // EQUITY
            ['account_code' => '3000', 'name' => 'OWNER\'S EQUITY', 'type' => 'equity', 'sub_type' => 'owner_equity'],
            ['account_code' => '3100', 'name' => 'Capital', 'type' => 'equity', 'sub_type' => 'owner_equity'],
            ['account_code' => '3200', 'name' => 'Retained Earnings', 'type' => 'equity', 'sub_type' => 'owner_equity'],

            // REVENUE
            ['account_code' => '4000', 'name' => 'OPERATING REVENUE', 'type' => 'revenue', 'sub_type' => 'operating_revenue'],
            ['account_code' => '4100', 'name' => 'Sales Revenue', 'type' => 'revenue', 'sub_type' => 'operating_revenue'],

            // EXPENSES
            ['account_code' => '5000', 'name' => 'COST OF GOODS SOLD', 'type' => 'expense', 'sub_type' => 'operating_expense'],
            ['account_code' => '5100', 'name' => 'Material Costs', 'type' => 'expense', 'sub_type' => 'operating_expense'],
            ['account_code' => '6000', 'name' => 'OPERATING EXPENSES', 'type' => 'expense', 'sub_type' => 'operating_expense'],
            ['account_code' => '6100', 'name' => 'Salaries & Wages', 'type' => 'expense', 'sub_type' => 'operating_expense'],
            ['account_code' => '6200', 'name' => 'Office Expenses', 'type' => 'expense', 'sub_type' => 'operating_expense'],
        ];

        foreach ($accounts as $account) {
            Account::create([
                'account_code' => $account['account_code'],
                'name' => $account['name'],
                'type' => $account['type'],
                'sub_type' => $account['sub_type'],
                'is_active' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }
    }

    private function seedCustomers(Company $company, User $adminUser): void
    {
        $customers = [
            ['name' => 'PT. Mitra Teknologi', 'email' => 'contact@mitratek.com', 'phone' => '021-1234567'],
            ['name' => 'CV. Sukses Mandiri', 'email' => 'info@suksesmandiri.co.id', 'phone' => '021-2345678'],
            ['name' => 'UD. Berkah Jaya', 'email' => 'admin@berkahjaya.com', 'phone' => '021-3456789'],
            ['name' => 'PT. Digital Solutions', 'email' => 'hello@digitalsol.id', 'phone' => '021-4567890'],
            ['name' => 'Toko Elektronik Maju', 'email' => 'toko@elektronik-maju.com', 'phone' => '021-5678901'],
        ];

        foreach ($customers as $customer) {
            Customer::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
                'address' => 'Jakarta, Indonesia',
                'customer_type' => 'corporate',
                'is_active' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }
    }

    private function seedSuppliers(Company $company, User $adminUser): void
    {
        $suppliers = [
            ['name' => 'PT. Supplier Utama', 'email' => 'sales@supplierutama.com', 'phone' => '021-1111111'],
            ['name' => 'CV. Distributor Terpercaya', 'email' => 'order@distributorterpercaya.co.id', 'phone' => '021-2222222'],
            ['name' => 'UD. Bahan Baku Prima', 'email' => 'admin@bahanbakuprima.com', 'phone' => '021-3333333'],
            ['name' => 'PT. Electronics Wholesale', 'email' => 'wholesale@electronics.id', 'phone' => '021-4444444'],
            ['name' => 'Toko Grosir Mandiri', 'email' => 'grosir@mandiri-toko.com', 'phone' => '021-5555555'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create([
                'name' => $supplier['name'],
                'email' => $supplier['email'],
                'phone' => $supplier['phone'],
                'address' => 'Jakarta, Indonesia',
                'tax_id' => '12.345.678.9-001.000',
                'bank_name' => 'Bank Mandiri',
                'bank_account' => '1234567890',
                'is_active' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }
    }

    private function seedProjectAndTaskStatuses(Company $company, User $adminUser): void
    {
        // Project Statuses
        $projectStatuses = [
            ['name' => 'Planning', 'color' => '#3B82F6'],
            ['name' => 'In Progress', 'color' => '#F59E0B'],
            ['name' => 'On Hold', 'color' => '#EF4444'],
            ['name' => 'Completed', 'color' => '#10B981'],
            ['name' => 'Cancelled', 'color' => '#6B7280'],
        ];

        foreach ($projectStatuses as $index => $status) {
            ProjectStatus::create([
                'name' => $status['name'],
                'color' => $status['color'],
                'order' => $index + 1,
                'is_active' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }

        // Task Statuses
        $taskStatuses = [
            ['name' => 'To Do', 'color' => '#6B7280'],
            ['name' => 'In Progress', 'color' => '#3B82F6'],
            ['name' => 'Review', 'color' => '#F59E0B'],
            ['name' => 'Done', 'color' => '#10B981'],
            ['name' => 'Blocked', 'color' => '#EF4444'],
        ];

        foreach ($taskStatuses as $index => $status) {
            TaskStatus::create([
                'name' => $status['name'],
                'color' => $status['color'],
                'order' => $index + 1,
                'is_active' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }
    }
}
