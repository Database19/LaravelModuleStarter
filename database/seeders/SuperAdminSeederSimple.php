<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SuperAdminSeederSimple extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin Role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web'
        ]);

        // Create basic permissions
        $basicPermissions = [
            'manage-accounting',
            'manage-hr',
            'manage-inventory',
            'manage-sales',
            'manage-purchasing',
            'manage-manufacturing',
            'manage-warehouse',
            'manage-crm',
            'manage-master-data',
            'manage-pos',
            'manage-quality',
            'manage-helpdesk',
            'manage-projects',
            'manage-maintenance',
            'manage-documents',
            'manage-users',
            'manage-roles',
            'manage-permissions',
            'manage-settings',
            'manage-companies',
            'manage-tenants',
            'manage-acl',
            'manage-helper',
            'view-reports',
            'view-dashboard',
        ];

        // Create permissions
        foreach ($basicPermissions as $permission) {
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
                'company_id' => 1,
            ]
        );

        // Assign super admin role to user
        $superAdmin->assignRole($superAdminRole);

        $this->command->info('Super Admin created successfully!');
        $this->command->line('Email: superadmin@erp.com');
        $this->command->line('Password: superadmin123');
    }
}
