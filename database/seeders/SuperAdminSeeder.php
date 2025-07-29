<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Create Super Admin Role
            $superAdminRole = Role::firstOrCreate([
                'name' => 'superadmin',
                'guard_name' => 'web'
            ]);

            // Create all module permissions
            $permissions = [
                // Accounting
                'manage-accounting',
                'view-accounts',
                'create-accounts',
                'edit-accounts',
                'delete-accounts',
                'view-journal-entries',
                'create-journal-entries',
                'edit-journal-entries',
                'delete-journal-entries',
                'view-budgets',
                'create-budgets',
                'edit-budgets',
                'delete-budgets',
                'view-fixed-assets',
                'create-fixed-assets',
                'edit-fixed-assets',
                'delete-fixed-assets',
                'manage-accounting-settings',

                // Human Resource
                'manage-hr',
                'view-employees',
                'create-employees',
                'edit-employees',
                'delete-employees',
                'view-payrolls',
                'create-payrolls',
                'edit-payrolls',
                'delete-payrolls',
                'view-attendance',
                'create-attendance',
                'edit-attendance',
                'delete-attendance',
                'view-leaves',
                'create-leaves',
                'edit-leaves',
                'delete-leaves',

                // Inventory
                'manage-inventory',
                'view-products',
                'create-products',
                'edit-products',
                'delete-products',
                'view-stocks',
                'create-stocks',
                'edit-stocks',
                'delete-stocks',
                'view-stock-transfers',
                'create-stock-transfers',
                'edit-stock-transfers',
                'delete-stock-transfers',
                'view-stock-counts',
                'create-stock-counts',
                'edit-stock-counts',
                'delete-stock-counts',

                // Sales
                'manage-sales',
                'view-customers',
                'create-customers',
                'edit-customers',
                'delete-customers',
                'view-sales-orders',
                'create-sales-orders',
                'edit-sales-orders',
                'delete-sales-orders',
                'view-customer-payments',
                'create-customer-payments',
                'edit-customer-payments',
                'delete-customer-payments',

                // Purchasing
                'manage-purchasing',
                'view-suppliers',
                'create-suppliers',
                'edit-suppliers',
                'delete-suppliers',
                'view-purchase-orders',
                'create-purchase-orders',
                'edit-purchase-orders',
                'delete-purchase-orders',
                'view-supplier-payments',
                'create-supplier-payments',
                'edit-supplier-payments',
                'delete-supplier-payments',

                // Manufacturing
                'manage-manufacturing',
                'view-boms',
                'create-boms',
                'edit-boms',
                'delete-boms',
                'view-manufacturing-orders',
                'create-manufacturing-orders',
                'edit-manufacturing-orders',
                'delete-manufacturing-orders',
                'view-production',
                'create-production',
                'edit-production',
                'delete-production',

                // Warehouse
                'manage-warehouse',
                'view-warehouses',
                'create-warehouses',
                'edit-warehouses',
                'delete-warehouses',
                'view-warehouse-transfers',
                'create-warehouse-transfers',
                'edit-warehouse-transfers',
                'delete-warehouse-transfers',

                // CRM
                'manage-crm',
                'view-leads',
                'create-leads',
                'edit-leads',
                'delete-leads',
                'view-deals',
                'create-deals',
                'edit-deals',
                'delete-deals',
                'view-activities',
                'create-activities',
                'edit-activities',
                'delete-activities',
                'view-contacts',
                'create-contacts',
                'edit-contacts',
                'delete-contacts',

                // Master Data
                'manage-master-data',
                'view-categories',
                'create-categories',
                'edit-categories',
                'delete-categories',
                'view-units',
                'create-units',
                'edit-units',
                'delete-units',
                'view-taxes',
                'create-taxes',
                'edit-taxes',
                'delete-taxes',
                'view-currencies',
                'create-currencies',
                'edit-currencies',
                'delete-currencies',

                // Point of Sales
                'manage-pos',
                'view-pos-transactions',
                'create-pos-transactions',
                'edit-pos-transactions',
                'delete-pos-transactions',
                'view-pos-payments',
                'create-pos-payments',
                'edit-pos-payments',
                'delete-pos-payments',
                'view-pos-returns',
                'create-pos-returns',
                'edit-pos-returns',
                'delete-pos-returns',
                'view-pos-receipts',
                'create-pos-receipts',
                'edit-pos-receipts',
                'delete-pos-receipts',

                // Quality Control
                'manage-quality',
                'view-quality-checks',
                'create-quality-checks',
                'edit-quality-checks',
                'delete-quality-checks',
                'view-quality-standards',
                'create-quality-standards',
                'edit-quality-standards',
                'delete-quality-standards',
                'view-quality-inspections',
                'create-quality-inspections',
                'edit-quality-inspections',
                'delete-quality-inspections',

                // Helpdesk
                'manage-helpdesk',
                'view-tickets',
                'create-tickets',
                'edit-tickets',
                'delete-tickets',
                'view-ticket-comments',
                'create-ticket-comments',
                'edit-ticket-comments',
                'delete-ticket-comments',
                'assign-tickets',
                'manage-knowledge-base',

                // Project Management
                'manage-projects',
                'view-projects',
                'create-projects',
                'edit-projects',
                'delete-projects',
                'view-tasks',
                'create-tasks',
                'edit-tasks',
                'delete-tasks',
                'view-timetrack',
                'create-timetrack',
                'edit-timetrack',
                'delete-timetrack',

                // Maintenance
                'manage-maintenance',
                'view-maintenance-orders',
                'create-maintenance-orders',
                'edit-maintenance-orders',
                'delete-maintenance-orders',
                'view-equipment',
                'create-equipment',
                'edit-equipment',
                'delete-equipment',

                // Document Management
                'manage-documents',
                'view-documents',
                'create-documents',
                'edit-documents',
                'delete-documents',
                'view-document-versions',
                'create-document-versions',
                'edit-document-versions',
                'delete-document-versions',

                // System Administration
                'manage-users',
                'manage-roles',
                'manage-permissions',
                'manage-settings',
                'manage-companies',
                'manage-tenants',
                'view-reports',
                'view-dashboard',
                'manage-backups',
                'manage-system-logs',
            ];

            // Create permissions
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web'
                ]);
            }

            // Assign all permissions to super admin role
            $allPermissions = Permission::all();
            $superAdminRole->syncPermissions($allPermissions);

            // Create super admin user
            $superAdmin = User::firstOrCreate(
                ['email' => 'superadmin@erp.com'],
                [
                    'name' => 'Super Administrator',
                    'email' => 'superadmin@erp.com',
                    'password' => Hash::make('superadmin123'),
                    'email_verified_at' => now(),
                    'is_super_admin' => true,
                    'employee_id' => 'SA001',
                    'phone_number' => '+62812345678',
                    'hire_date' => now(),
                    'birth_date' => '1990-01-01',
                    'gender' => 'male',
                    'address' => 'Jakarta, Indonesia',
                    'employment_status' => 'active',
                    'salary' => 15000000.00,
                    'company_id' => 1, // Default company
                    'department_id' => null,
                    'position_id' => null,
                    'manager_id' => null,
                ]
            );

            // Assign super admin role to user
            $superAdmin->assignRole($superAdminRole);

            // Create additional admin roles for different departments
            $departmentRoles = [
                'admin' => [
                    'name' => 'Admin',
                    'permissions' => $allPermissions->pluck('name')->toArray()
                ],
                'accounting-manager' => [
                    'name' => 'Accounting Manager',
                    'permissions' => array_filter($permissions, function($perm) {
                        return str_contains($perm, 'accounting') || str_contains($perm, 'view-');
                    })
                ],
                'hr-manager' => [
                    'name' => 'HR Manager',
                    'permissions' => array_filter($permissions, function($perm) {
                        return str_contains($perm, 'hr') || str_contains($perm, 'employee') ||
                               str_contains($perm, 'payroll') || str_contains($perm, 'attendance') ||
                               str_contains($perm, 'leave') || str_contains($perm, 'view-');
                    })
                ],
                'sales-manager' => [
                    'name' => 'Sales Manager',
                    'permissions' => array_filter($permissions, function($perm) {
                        return str_contains($perm, 'sales') || str_contains($perm, 'customer') ||
                               str_contains($perm, 'crm') || str_contains($perm, 'lead') ||
                               str_contains($perm, 'deal') || str_contains($perm, 'view-');
                    })
                ],
                'purchasing-manager' => [
                    'name' => 'Purchasing Manager',
                    'permissions' => array_filter($permissions, function($perm) {
                        return str_contains($perm, 'purchasing') || str_contains($perm, 'supplier') ||
                               str_contains($perm, 'purchase') || str_contains($perm, 'view-');
                    })
                ],
                'warehouse-manager' => [
                    'name' => 'Warehouse Manager',
                    'permissions' => array_filter($permissions, function($perm) {
                        return str_contains($perm, 'warehouse') || str_contains($perm, 'inventory') ||
                               str_contains($perm, 'stock') || str_contains($perm, 'product') ||
                               str_contains($perm, 'view-');
                    })
                ],
                'production-manager' => [
                    'name' => 'Production Manager',
                    'permissions' => array_filter($permissions, function($perm) {
                        return str_contains($perm, 'manufacturing') || str_contains($perm, 'bom') ||
                               str_contains($perm, 'production') || str_contains($perm, 'quality') ||
                               str_contains($perm, 'view-');
                    })
                ],
                'support-manager' => [
                    'name' => 'Support Manager',
                    'permissions' => array_filter($permissions, function($perm) {
                        return str_contains($perm, 'helpdesk') || str_contains($perm, 'ticket') ||
                               str_contains($perm, 'knowledge') || str_contains($perm, 'view-');
                    })
                ],
                'project-manager' => [
                    'name' => 'Project Manager',
                    'permissions' => array_filter($permissions, function($perm) {
                        return str_contains($perm, 'project') || str_contains($perm, 'task') ||
                               str_contains($perm, 'timetrack') || str_contains($perm, 'view-');
                    })
                ],
                'quality-manager' => [
                    'name' => 'Quality Manager',
                    'permissions' => array_filter($permissions, function($perm) {
                        return str_contains($perm, 'quality') || str_contains($perm, 'inspection') ||
                               str_contains($perm, 'view-');
                    })
                ],
                'pos-operator' => [
                    'name' => 'POS Operator',
                    'permissions' => array_filter($permissions, function($perm) {
                        return str_contains($perm, 'pos') || str_contains($perm, 'view-products') ||
                               str_contains($perm, 'view-customers');
                    })
                ],
                'employee' => [
                    'name' => 'Employee',
                    'permissions' => [
                        'view-dashboard',
                        'view-own-attendance',
                        'create-leave',
                        'view-own-payroll'
                    ]
                ]
            ];

            // Create department roles
            foreach ($departmentRoles as $roleKey => $roleData) {
                $role = Role::firstOrCreate([
                    'name' => $roleKey,
                    'guard_name' => 'web'
                ]);

                // Assign permissions to role
                $rolePermissions = [];
                foreach ($roleData['permissions'] as $permName) {
                    $permission = Permission::where('name', $permName)->first();
                    if ($permission) {
                        $rolePermissions[] = $permission;
                    }
                }
                $role->syncPermissions($rolePermissions);
            }

            // Create sample users for different roles
            $sampleUsers = [
                [
                    'name' => 'Admin User',
                    'email' => 'admin@erp.com',
                    'password' => Hash::make('admin123'),
                    'role' => 'admin',
                    'employee_id' => 'AD001',
                    'is_super_admin' => false,
                ],
                [
                    'name' => 'Accounting Manager',
                    'email' => 'accounting@erp.com',
                    'password' => Hash::make('accounting123'),
                    'role' => 'accounting-manager',
                    'employee_id' => 'AC001',
                    'is_super_admin' => false,
                ],
                [
                    'name' => 'HR Manager',
                    'email' => 'hr@erp.com',
                    'password' => Hash::make('hr123'),
                    'role' => 'hr-manager',
                    'employee_id' => 'HR001',
                    'is_super_admin' => false,
                ],
                [
                    'name' => 'Sales Manager',
                    'email' => 'sales@erp.com',
                    'password' => Hash::make('sales123'),
                    'role' => 'sales-manager',
                    'employee_id' => 'SM001',
                    'is_super_admin' => false,
                ],
                [
                    'name' => 'Warehouse Manager',
                    'email' => 'warehouse@erp.com',
                    'password' => Hash::make('warehouse123'),
                    'role' => 'warehouse-manager',
                    'employee_id' => 'WM001',
                    'is_super_admin' => false,
                ],
            ];

            foreach ($sampleUsers as $userData) {
                $role = $userData['role'];
                unset($userData['role']);

                $userData['email_verified_at'] = now();
                $userData['phone_number'] = '+62812345678';
                $userData['hire_date'] = now();
                $userData['birth_date'] = '1990-01-01';
                $userData['gender'] = 'male';
                $userData['address'] = 'Jakarta, Indonesia';
                $userData['employment_status'] = 'active';
                $userData['salary'] = 8000000.00;
                $userData['company_id'] = 1; // Default company
                $userData['department_id'] = null;
                $userData['position_id'] = null;
                $userData['manager_id'] = null;

                $user = User::firstOrCreate(
                    ['email' => $userData['email']],
                    $userData
                );

                $user->assignRole($role);
            }
        });

        $this->command->info('Super Admin and role system created successfully!');
        $this->command->line('Super Admin Login:');
        $this->command->line('Email: superadmin@erp.com');
        $this->command->line('Password: superadmin123');
        $this->command->line('');
        $this->command->line('Additional Users:');
        $this->command->line('Admin: admin@erp.com / admin123');
        $this->command->line('Accounting: accounting@erp.com / accounting123');
        $this->command->line('HR: hr@erp.com / hr123');
        $this->command->line('Sales: sales@erp.com / sales123');
        $this->command->line('Warehouse: warehouse@erp.com / warehouse123');
    }
}
