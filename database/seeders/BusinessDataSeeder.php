<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Task;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use App\Models\ProjectStatus;
use App\Models\TaskStatus;
use App\Models\Lead;
use App\Models\Opportunity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Multitenancy\Models\Tenant;

class BusinessDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed untuk setiap company
        $companies = Company::all();

        foreach ($companies as $company) {
            // Set company sebagai current tenant
            Tenant::find($company->id)?->makeCurrent();

            $this->seedCompanyBusinessData($company);
        }

        // Clear tenant context setelah seeding
        Tenant::forgetCurrent();
    }

    private function seedCompanyBusinessData(Company $company): void
    {
        $adminUser = User::where('company_id', $company->id)
                        ->whereHas('roles', fn($q) => $q->where('name', 'Admin'))
                        ->first();

        if (!$adminUser) return;

        // 1. Seed Additional Employees
        $this->seedEmployees($company, $adminUser);

        // 2. Seed CRM Data (Leads & Opportunities)
        $this->seedCRMData($company, $adminUser);

        // 3. Seed Projects & Tasks
        $this->seedProjectsAndTasks($company, $adminUser);

        // 4. Seed Sales Orders
        $this->seedSalesOrders($company, $adminUser);

        // 5. Seed Purchase Orders
        $this->seedPurchaseOrders($company, $adminUser);
    }

    private function seedEmployees(Company $company, User $adminUser): void
    {
        $employees = [
            [
                'name' => 'Sarah Manager',
                'email' => 'sarah.manager@' . str_replace(['http://', 'https://'], '', $company->domain),
                'role' => 'Sales Manager',
                'department' => 'Sales & Marketing',
                'employee_id_number' => 'EMP-' . $company->id . '-001',
                'job_title' => 'Sales Manager',
                'salary' => 12000000,
            ],
            [
                'name' => 'John Accountant',
                'email' => 'john.accountant@' . str_replace(['http://', 'https://'], '', $company->domain),
                'role' => 'Accounting Staff',
                'department' => 'Finance & Accounting',
                'employee_id_number' => 'EMP-' . $company->id . '-002',
                'job_title' => 'Senior Accountant',
                'salary' => 9000000,
            ],
            [
                'name' => 'Lisa HR',
                'email' => 'lisa.hr@' . str_replace(['http://', 'https://'], '', $company->domain),
                'role' => 'HR Manager',
                'department' => 'Human Resources',
                'employee_id_number' => 'EMP-' . $company->id . '-003',
                'job_title' => 'HR Manager',
                'salary' => 10000000,
            ],
            [
                'name' => 'Mike Operations',
                'email' => 'mike.ops@' . str_replace(['http://', 'https://'], '', $company->domain),
                'role' => 'Operations Staff',
                'department' => 'Operations',
                'employee_id_number' => 'EMP-' . $company->id . '-004',
                'job_title' => 'Operations Supervisor',
                'salary' => 8000000,
            ],
        ];

        foreach ($employees as $empData) {
            // Create User
            $user = User::create([
                'name' => $empData['name'],
                'email' => $empData['email'],
                'password' => Hash::make('password'),
                'company_id' => $company->id,
                'employment_status' => 'active',
                'hire_date' => now()->subMonths(rand(1, 24)),
                'salary' => $empData['salary'],
            ]);

            // Assign Role
            $user->assignRole($empData['role']);

            // Create Employee Profile
            Employee::create([
                'user_id' => $user->id,
                'employee_id_number' => $empData['employee_id_number'],
                'job_title' => $empData['job_title'],
                'hire_date' => $user->hire_date, // Use hire_date instead of join_date
                'basic_salary' => $empData['salary'],
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);
        }
    }

    private function seedCRMData(Company $company, User $adminUser): void
    {
        $salesUser = User::where('company_id', $company->id)
                         ->whereHas('roles', fn($q) => $q->whereIn('name', ['Sales Manager', 'Admin']))
                         ->first() ?? $adminUser;

        // Create Leads
        $leads = [
            [
                'name' => 'Ahmad Wijaya',
                'company_name' => 'PT. Teknologi Maju',
                'email' => 'ahmad@teknologimaju.com',
                'phone' => '081234567890',
                'source' => 'Website',
                'status' => 'new',
                'notes' => 'Interested in ERP solution for manufacturing'
            ],
            [
                'name' => 'Siti Nurhaliza',
                'company_name' => 'CV. Berkah Digital',
                'email' => 'siti@berkahdigital.co.id',
                'phone' => '081234567891',
                'source' => 'Referral',
                'status' => 'contacted',
                'notes' => 'Looking for accounting module only'
            ],
            [
                'name' => 'Budi Santoso',
                'company_name' => 'UD. Sukses Bersama',
                'email' => 'budi@suksesbersama.com',
                'phone' => '081234567892',
                'source' => 'Social Media',
                'status' => 'qualified',
                'notes' => 'Ready to purchase, waiting for quotation'
            ],
        ];

        foreach ($leads as $leadData) {
            Lead::create([
                'name' => $leadData['name'],
                'company_name' => $leadData['company_name'],
                'email' => $leadData['email'],
                'phone' => $leadData['phone'],
                'source' => $leadData['source'],
                'status' => $leadData['status'],
                'notes' => $leadData['notes'],
                'owner_id' => $salesUser->id,
                'company_id' => $company->id,
            ]);
        }

        // Create Opportunities from qualified leads
        $customers = Customer::where('company_id', $company->id)->take(3)->get();

        foreach ($customers as $index => $customer) {
            Opportunity::create([
                'name' => 'ERP Implementation - ' . $customer->name,
                'customer_id' => $customer->id,
                'expected_value' => rand(50000000, 200000000),
                'expected_closing_date' => now()->addMonths(rand(1, 6)),
                'stage' => ['prospecting', 'proposal', 'negotiation'][array_rand(['prospecting', 'proposal', 'negotiation'])],
                'owner_id' => $salesUser->id,
                'company_id' => $company->id,
            ]);
        }
    }

    private function seedProjectsAndTasks(Company $company, User $adminUser): void
    {
        $projectManager = User::where('company_id', $company->id)
                             ->whereHas('roles', fn($q) => $q->whereIn('name', ['Project Manager', 'Admin']))
                             ->first() ?? $adminUser;

        $customers = Customer::where('company_id', $company->id)->take(2)->get();
        $projectStatus = ProjectStatus::where('company_id', $company->id)->first();
        $taskStatus = TaskStatus::where('company_id', $company->id)->first();

        if (!$projectStatus || !$taskStatus) return;

        foreach ($customers as $index => $customer) {
            $project = Project::create([
                'code' => 'PRJ-' . $company->id . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'name' => 'ERP Implementation for ' . $customer->name,
                'description' => 'Complete ERP system implementation including training and data migration',
                'customer_id' => $customer->id,
                'manager_id' => $projectManager->id,
                'project_status_id' => $projectStatus->id,
                'start_date' => now()->subMonths(rand(1, 3)),
                'end_date' => now()->addMonths(rand(3, 12)),
                'budget' => rand(100000000, 500000000),
                'actual_cost' => rand(50000000, 100000000),
                'progress' => rand(10, 80),
                'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);

            // Add project members
            $teamMembers = User::where('company_id', $company->id)->take(3)->get();
            foreach ($teamMembers as $member) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $member->id,
                    'role' => ['member', 'lead'][array_rand(['member', 'lead'])],
                    'joined_date' => $project->start_date,
                    'is_active' => true,
                    'created_by' => $adminUser->id,
                    'updated_by' => $adminUser->id,
                    'company_id' => $company->id,
                ]);
            }

            // Add tasks to project
            $tasks = [
                'Requirements Analysis',
                'System Design',
                'Development Phase 1',
                'Testing & QA',
                'User Training',
                'Go Live & Support'
            ];

            foreach ($tasks as $taskIndex => $taskName) {
                Task::create([
                    'project_id' => $project->id,
                    'title' => $taskName,
                    'description' => 'Detailed work for: ' . $taskName,
                    'assignee_id' => $teamMembers->random()->id,
                    'task_status_id' => $taskStatus->id,
                    'due_date' => now()->addDays(($taskIndex + 1) * 30),
                    'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
                    'created_by' => $adminUser->id,
                    'updated_by' => $adminUser->id,
                    'company_id' => $company->id,
                ]);
            }
        }
    }

    private function seedSalesOrders(Company $company, User $adminUser): void
    {
        $salesUser = User::where('company_id', $company->id)
                         ->whereHas('roles', fn($q) => $q->whereIn('name', ['Sales Manager', 'Admin']))
                         ->first() ?? $adminUser;

        $customers = Customer::where('company_id', $company->id)->take(3)->get();
        $products = Product::where('company_id', $company->id)->take(5)->get();

        if ($customers->isEmpty() || $products->isEmpty()) return;

        foreach ($customers as $index => $customer) {
            $salesOrder = SalesOrder::create([
                'order_number' => 'SO-' . $company->id . '-' . date('Y') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'user_id' => $salesUser->id,
                'order_date' => now()->subDays(rand(1, 30)),
                'delivery_date' => now()->addDays(rand(7, 30)),
                'status' => ['draft', 'confirmed', 'shipped', 'delivered'][array_rand(['draft', 'confirmed', 'shipped', 'delivered'])],
                'subtotal' => 0, // Will be calculated after items
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
                'notes' => 'Sample sales order for ' . $customer->name,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);

            // Add order items
            $selectedProducts = $products->random(rand(2, 4));
            $subtotal = 0;

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 10);
                $unitPrice = $product->price;
                $totalPrice = $quantity * $unitPrice;
                $subtotal += $totalPrice;

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount_amount' => 0,
                    'total_price' => $totalPrice,
                    'created_by' => $adminUser->id,
                    'updated_by' => $adminUser->id,
                    'company_id' => $company->id,
                ]);
            }

            // Update sales order totals
            $taxAmount = $subtotal * 0.11; // 11% tax
            $totalAmount = $subtotal + $taxAmount;

            $salesOrder->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]);
        }
    }

    private function seedPurchaseOrders(Company $company, User $adminUser): void
    {
        $purchasingUser = User::where('company_id', $company->id)
                             ->whereHas('roles', fn($q) => $q->whereIn('name', ['Purchasing Manager', 'Admin']))
                             ->first() ?? $adminUser;

        $suppliers = Supplier::where('company_id', $company->id)->take(3)->get();
        $products = Product::where('company_id', $company->id)->take(5)->get();

        if ($suppliers->isEmpty() || $products->isEmpty()) return;

        foreach ($suppliers as $index => $supplier) {
            $purchaseOrder = PurchaseOrder::create([
                'order_number' => 'PO-' . $company->id . '-' . date('Y') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'supplier_id' => $supplier->id,
                'user_id' => $purchasingUser->id,
                'order_date' => now()->subDays(rand(1, 20)),
                'expected_delivery_date' => now()->addDays(rand(7, 30)),
                'status' => ['draft', 'ordered', 'received', 'completed'][array_rand(['draft', 'ordered', 'received', 'completed'])],
                'subtotal' => 0, // Will be calculated after items
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
                'notes' => 'Sample purchase order from ' . $supplier->name,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
                'company_id' => $company->id,
            ]);

            // Add order items
            $selectedProducts = $products->random(rand(2, 3));
            $subtotal = 0;

            foreach ($selectedProducts as $product) {
                $quantity = rand(5, 50);
                $unitPrice = $product->cost; // Use cost price for purchase
                $totalPrice = $quantity * $unitPrice;
                $subtotal += $totalPrice;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount_amount' => 0,
                    'total_price' => $totalPrice,
                    'created_by' => $adminUser->id,
                    'updated_by' => $adminUser->id,
                    'company_id' => $company->id,
                ]);
            }

            // Update purchase order totals
            $taxAmount = $subtotal * 0.11; // 11% tax
            $totalAmount = $subtotal + $taxAmount;

            $purchaseOrder->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]);
        }
    }
}
