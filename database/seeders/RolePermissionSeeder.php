<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Definisikan Izin per Modul
        $permissions = [
            'manage-accounting', 'manage-crm', 'manage-documents', 'manage-helpdesk',
            'manage-hr', 'manage-inventory', 'manage-maintenance', 'manage-manufacturing',
            'manage-pos', 'manage-projects', 'manage-purchasing', 'manage-quality-control',
            'manage-sales', 'manage-warehouse', 'manage-acl'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Buat Peran dan Berikan Izin
        $roles = [
            'Admin' => Permission::all()->pluck('name')->toArray(),
            'Sales Manager' => ['manage-sales', 'manage-crm'],
            'Sales Staff' => ['manage-sales', 'manage-crm'],
            'Accountant Manager' => ['manage-accounting', 'manage-purchasing'],
            'Accounting Staff' => ['manage-accounting', 'manage-purchasing'],
            'HR Manager' => ['manage-hr'],
            'HR Staff' => ['manage-hr'],
            'Warehouse Manager' => ['manage-inventory', 'manage-warehouse'],
            'Warehouse Staff' => ['manage-inventory', 'manage-warehouse'],
            'Production Manager' => ['manage-manufacturing', 'manage-quality-control'],
            'Production Staff' => ['manage-manufacturing', 'manage-quality-control'],
            'Project Manager' => ['manage-projects', 'manage-documents'],
            'Maintenance Staff' => ['manage-maintenance'],
            'POS Cashier' => ['manage-pos'],
            'Helpdesk Agent' => ['manage-helpdesk'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        // 3. Buat User Lengkap untuk Setiap Peran
        $users = [
            ['name' => 'Super Admin', 'email' => 'admin@erp.test', 'role' => 'Admin'],
            ['name' => 'Dede Febriansyah', 'email' => 'sales.manager@erp.test', 'role' => 'Sales Manager'],
            ['name' => 'Lukman Nul Hakim', 'email' => 'accounting.manager@erp.test', 'role' => 'Accountant Manager'],
            ['name' => 'Ghezak', 'email' => 'hr.manager@erp.test', 'role' => 'HR Manager'],
            ['name' => 'Andika Pratama', 'email' => 'warehouse.manager@erp.test', 'role' => 'Warehouse Manager'],
            ['name' => 'Wanda Ismata', 'email' => 'production.manager@erp.test', 'role' => 'Production Manager'],
            ['name' => 'Rifki Ramadhan', 'email' => 'project.manager@erp.test', 'role' => 'Project Manager'],

            ['name' => 'Anis Baswedan', 'email' => 'sales.staff@erp.test', 'role' => 'Sales Staff'],
            ['name' => 'Prabowo Subianto', 'email' => 'accounting.staff@erp.test', 'role' => 'Accounting Staff'],
            ['name' => 'Ustad ALim', 'email' => 'hr.staff@erp.test', 'role' => 'HR Staff'],
            ['name' => 'Mulyono', 'email' => 'warehouse.staff@erp.test', 'role' => 'Warehouse Staff'],
            ['name' => 'Jika aku jadi dia', 'email' => 'production.staff@erp.test', 'role' => 'Production Staff'],
            ['name' => 'Vina Astuti', 'email' => 'maintenance.staff@erp.test', 'role' => 'Maintenance Staff'],
            ['name' => 'Wati Halimah', 'email' => 'pos.cashier@erp.test', 'role' => 'POS Cashier'],
            ['name' => 'Yoga Permana', 'email' => 'helpdesk.agent@erp.test', 'role' => 'Helpdesk Agent'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'), // Password default untuk semua user
                ]
            );
            $user->syncRoles([$data['role']]);
        }
    }
}
