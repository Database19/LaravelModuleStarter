<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ComprehensiveSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Step 1: Clear cache dan reset semua data
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Clear existing data to prevent duplicates
            User::query()->delete();
            Company::query()->delete();
            Department::query()->delete();
            Position::query()->delete();
            Role::query()->delete();
            Permission::query()->delete();

            echo "🗑️  Cleared existing data\n";

            // Step 2: Create Permissions
            $this->createPermissions();

            // Step 3: Create Roles with Permissions
            $this->createRoles();

            // Step 4: Create Companies with Departments & Positions
            $this->createCompaniesWithStructure();

            // Step 5: Create Users (Super Admin + Company Users)
            $this->createUsers();

            echo "✅ ComprehensiveSeeder completed successfully!\n";
        });
    }

    private function createPermissions(): void
    {
        $permissions = [
            // Super Admin permissions
            'super-admin-access',
            'manage-companies',
            'manage-global-users',
            'system-monitor',

            // Module permissions
            'manage-dashboard',
            'manage-master-data',
            'manage-accounting',
            'manage-crm',
            'manage-documents',
            'manage-helpdesk',
            'manage-hr',
            'manage-inventory',
            'manage-maintenance',
            'manage-manufacturing',
            'manage-pos',
            'manage-projects',
            'manage-purchasing',
            'manage-quality-control',
            'manage-sales',
            'manage-warehouse',

            // ACL permissions
            'manage-roles',
            'manage-permissions',
            'manage-users',
            'view-reports',
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        echo "✅ Created " . count($permissions) . " permissions\n";
    }

    private function createRoles(): void
    {
        $roles = [
            'Super Admin' => [
                'super-admin-access', 'manage-companies', 'manage-global-users', 'system-monitor',
                'manage-dashboard', 'manage-master-data', 'manage-accounting', 'manage-crm',
                'manage-documents', 'manage-helpdesk', 'manage-hr', 'manage-inventory',
                'manage-maintenance', 'manage-manufacturing', 'manage-pos', 'manage-projects',
                'manage-purchasing', 'manage-quality-control', 'manage-sales', 'manage-warehouse',
                'manage-roles', 'manage-permissions', 'manage-users', 'view-reports', 'manage-settings'
            ],

            'Company Admin' => [
                'manage-dashboard', 'manage-master-data', 'manage-accounting', 'manage-crm',
                'manage-documents', 'manage-helpdesk', 'manage-hr', 'manage-inventory',
                'manage-maintenance', 'manage-manufacturing', 'manage-pos', 'manage-projects',
                'manage-purchasing', 'manage-quality-control', 'manage-sales', 'manage-warehouse',
                'manage-roles', 'manage-permissions', 'manage-users', 'view-reports', 'manage-settings'
            ],

            'Branch Manager' => [
                'manage-dashboard', 'manage-master-data', 'manage-crm', 'manage-hr',
                'manage-inventory', 'manage-pos', 'manage-sales', 'manage-warehouse',
                'manage-users', 'view-reports'
            ],

            'Sales Manager' => [
                'manage-dashboard', 'manage-crm', 'manage-sales', 'manage-pos', 'view-reports'
            ],

            'Sales Staff' => [
                'manage-dashboard', 'manage-crm', 'manage-sales', 'manage-pos'
            ],

            'Accounting Manager' => [
                'manage-dashboard', 'manage-accounting', 'manage-purchasing', 'view-reports'
            ],

            'Accounting Staff' => [
                'manage-dashboard', 'manage-accounting', 'manage-purchasing'
            ],

            'HR Manager' => [
                'manage-dashboard', 'manage-hr', 'manage-users', 'view-reports'
            ],

            'HR Staff' => [
                'manage-dashboard', 'manage-hr'
            ],

            'Warehouse Manager' => [
                'manage-dashboard', 'manage-inventory', 'manage-warehouse', 'view-reports'
            ],

            'Warehouse Staff' => [
                'manage-dashboard', 'manage-inventory', 'manage-warehouse'
            ],

            'Production Manager' => [
                'manage-dashboard', 'manage-manufacturing', 'manage-quality-control', 'view-reports'
            ],

            'Production Staff' => [
                'manage-dashboard', 'manage-manufacturing', 'manage-quality-control'
            ],

            'Project Manager' => [
                'manage-dashboard', 'manage-projects', 'manage-documents', 'view-reports'
            ],

            'Staff' => [
                'manage-dashboard'
            ]
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        echo "✅ Created " . count($roles) . " roles with permissions\n";
    }

    private function createCompaniesWithStructure(): void
    {
        $companies = [
            [
                'name' => 'PT. Inovasi Digital Nusantara',
                'domain' => 'inovasi.local',
                'departments' => [
                    ['name' => 'Management', 'code' => 'MGT', 'positions' => ['CEO', 'COO', 'CFO']],
                    ['name' => 'Sales & Marketing', 'code' => 'SAL', 'positions' => ['Sales Manager', 'Sales Staff', 'Marketing Staff']],
                    ['name' => 'Finance & Accounting', 'code' => 'FIN', 'positions' => ['Finance Manager', 'Accountant', 'Finance Staff']],
                    ['name' => 'Human Resources', 'code' => 'HRD', 'positions' => ['HR Manager', 'HR Staff', 'Recruiter']],
                    ['name' => 'Operations', 'code' => 'OPS', 'positions' => ['Operations Manager', 'Warehouse Staff', 'Production Staff']],
                    ['name' => 'IT & Development', 'code' => 'ITD', 'positions' => ['IT Manager', 'Developer', 'System Admin']],
                ]
            ],
            [
                'name' => 'CV. Maju Jaya Abadi',
                'domain' => 'majujaya.local',
                'departments' => [
                    ['name' => 'Management', 'code' => 'MGT', 'positions' => ['Director', 'Manager']],
                    ['name' => 'Sales', 'code' => 'SAL', 'positions' => ['Sales Manager', 'Sales Staff']],
                    ['name' => 'Finance', 'code' => 'FIN', 'positions' => ['Finance Manager', 'Accountant']],
                    ['name' => 'Operations', 'code' => 'OPS', 'positions' => ['Operations Manager', 'Staff']],
                ]
            ],
            [
                'name' => 'PT. Teknologi Maju Bersama',
                'domain' => 'tekno.local',
                'departments' => [
                    ['name' => 'Executive', 'code' => 'EXE', 'positions' => ['CEO', 'CTO', 'CMO']],
                    ['name' => 'Engineering', 'code' => 'ENG', 'positions' => ['Engineering Manager', 'Senior Developer', 'Junior Developer']],
                    ['name' => 'Product', 'code' => 'PRD', 'positions' => ['Product Manager', 'Product Owner', 'Business Analyst']],
                    ['name' => 'Support', 'code' => 'SUP', 'positions' => ['Support Manager', 'Support Staff', 'Technical Support']],
                ]
            ]
        ];

        foreach ($companies as $companyData) {
            $company = Company::create([
                'name' => $companyData['name'],
                'domain' => $companyData['domain'],
            ]);

            foreach ($companyData['departments'] as $departmentData) {
                $department = Department::create([
                    'name' => $departmentData['name'],
                    'code' => $companyData['domain'] . '_' . $departmentData['code'],
                    'description' => $departmentData['name'] . ' Department',
                    'company_id' => $company->id,
                    'is_active' => true,
                ]);

                foreach ($departmentData['positions'] as $positionName) {
                    Position::create([
                        'name' => $positionName,
                        'code' => $company->domain . '_' . str_replace(' ', '_', strtoupper($positionName)),
                        'description' => $positionName . ' position in ' . $departmentData['name'],
                        'department_id' => $department->id,
                        'company_id' => $company->id,
                        'base_salary' => $this->getBaseSalary($positionName),
                        'is_active' => true,
                    ]);
                }
            }

            echo "✅ Created company: {$company->name} with departments and positions\n";
        }
    }

    private function createUsers(): void
    {
        // Get first company for super admin
        $firstCompany = Company::first();

        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@erp.test',
            'password' => Hash::make('password'),
            'company_id' => $firstCompany->id,
            'is_super_admin' => true,
            'employee_id' => 'SA001',
            'employment_status' => 'active', // Changed from 'permanent' to 'active'
        ]);
        $superAdmin->assignRole('Super Admin');
        echo "✅ Created Super Admin: {$superAdmin->email}\n";

        // Create users for each company
        $companies = Company::with(['departments.positions'])->get();

        echo "🏢 Found " . $companies->count() . " companies to create users for\n";

        foreach ($companies as $company) {
            echo "📝 Creating users for company: {$company->name}\n";
            $this->createCompanyUsers($company);
        }
    }

    private function createCompanyUsers(Company $company): void
    {
        try {
            $companyUsers = [
            [
                'name' => 'Company Administrator',
                'email' => 'admin@' . $company->domain,
                'role' => 'Company Admin',
                'employee_id' => $company->domain . '_ADM001',
                'department' => 'Management'
            ],
            [
                'name' => 'Branch Manager',
                'email' => 'manager@' . $company->domain,
                'role' => 'Branch Manager',
                'employee_id' => $company->domain . '_MGR001',
                'department' => 'Management'
            ],
            [
                'name' => 'Sales Manager',
                'email' => 'sales.manager@' . $company->domain,
                'role' => 'Sales Manager',
                'employee_id' => $company->domain . '_SAL001',
                'department' => 'Sales'
            ],
            [
                'name' => 'Sales Staff',
                'email' => 'sales.staff@' . $company->domain,
                'role' => 'Sales Staff',
                'employee_id' => $company->domain . '_SAL002',
                'department' => 'Sales'
            ],
            [
                'name' => 'Finance Manager',
                'email' => 'finance.manager@' . $company->domain,
                'role' => 'Accounting Manager',
                'employee_id' => $company->domain . '_FIN001',
                'department' => 'Finance'
            ],
            [
                'name' => 'Accountant',
                'email' => 'accountant@' . $company->domain,
                'role' => 'Accounting Staff',
                'employee_id' => $company->domain . '_FIN002',
                'department' => 'Finance'
            ],
            [
                'name' => 'HR Manager',
                'email' => 'hr.manager@' . $company->domain,
                'role' => 'HR Manager',
                'employee_id' => $company->domain . '_HR001',
                'department' => 'Human Resources'
            ],
            [
                'name' => 'Operations Manager',
                'email' => 'ops.manager@' . $company->domain,
                'role' => 'Warehouse Manager',
                'employee_id' => $company->domain . '_OPS001',
                'department' => 'Operations'
            ]
        ];

        foreach ($companyUsers as $userData) {
            // Find department
            $department = $company->departments()
                ->where('name', 'like', '%' . $userData['department'] . '%')
                ->first();

            // Find position
            $position = null;
            if ($department) {
                $position = $department->positions()
                    ->where('name', 'like', '%' . explode(' ', $userData['name'])[0] . '%')
                    ->orWhere('name', 'like', '%' . $userData['role'] . '%')
                    ->first();
            }

            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'company_id' => $company->id,
                'employee_id' => $userData['employee_id'],
                'department_id' => $department?->id,
                'position_id' => $position?->id,
                'employment_status' => 'active',
                'hire_date' => now()->subDays(rand(30, 365)),
                'salary' => $position?->base_salary ?? 5000000,
                'is_super_admin' => false,
            ]);

            $user->assignRole($userData['role']);
        }        echo "✅ Created " . count($companyUsers) . " users for company: {$company->name}\n";
        } catch (\Exception $e) {
            echo "❌ Error creating users for company {$company->name}: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    private function getBaseSalary(string $positionName): float
    {
        $salaries = [
            'CEO' => 25000000,
            'COO' => 20000000,
            'CFO' => 20000000,
            'CTO' => 20000000,
            'CMO' => 18000000,
            'Director' => 22000000,
            'Manager' => 15000000,
            'Sales Manager' => 12000000,
            'Finance Manager' => 12000000,
            'HR Manager' => 10000000,
            'Operations Manager' => 10000000,
            'IT Manager' => 12000000,
            'Engineering Manager' => 15000000,
            'Product Manager' => 13000000,
            'Support Manager' => 8000000,
            'Senior Developer' => 10000000,
            'Developer' => 8000000,
            'Junior Developer' => 6000000,
            'Product Owner' => 9000000,
            'Business Analyst' => 8000000,
            'Accountant' => 7000000,
            'Sales Staff' => 6000000,
            'HR Staff' => 5500000,
            'Finance Staff' => 5500000,
            'Marketing Staff' => 6000000,
            'Warehouse Staff' => 4500000,
            'Production Staff' => 4500000,
            'Support Staff' => 4500000,
            'Technical Support' => 5000000,
            'System Admin' => 7000000,
            'Recruiter' => 6000000,
            'Staff' => 4000000,
        ];

        return $salaries[$positionName] ?? 4000000;
    }
}
