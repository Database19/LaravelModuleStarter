<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage-sales',
            'manage-accounting',
            'manage-inventory',
            'manage-maintenance',
            'manage-production',
            'manage-hr',
            'manage-procurement',
            'manage-qc',
            'manage-audit',
            'manage-user'
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // Role
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $salesManager = Role::firstOrCreate(['name' => 'Sales Manager']);
        $accountant = Role::firstOrCreate(['name' => 'Accountant']);
        $inventoryClerk = Role::firstOrCreate(['name' => 'Inventory Clerk']);
        $maintenance = Role::firstOrCreate(['name' => 'Maintenance']);
        $production = Role::firstOrCreate(['name' => 'Production']);
        $hr = Role::firstOrCreate(['name' => 'Human Resources']);
        $qc = Role::firstOrCreate(['name' => 'Quality Control']);
        $procurement = Role::firstOrCreate(['name' => 'Procurement']);
        $audit = Role::firstOrCreate(['name' => 'Audit']);
        $user = Role::firstOrCreate(['name' => 'User']);

        // $admin = User::find(1);
        // assign

        // Assign permissions to roles
        $admin->givePermissionTo($permissions);
        $salesManager->givePermissionTo('manage-sales');
        $accountant->givePermissionTo('manage-accounting');
        $inventoryClerk->givePermissionTo('manage-inventory');
        $maintenance->givePermissionTo('manage-maintenance');
        $production->givePermissionTo('manage-production');
        $hr->givePermissionTo('manage-hr');
        $qc->givePermissionTo('manage-qc');
        $procurement->givePermissionTo('manage-procurement');
        $audit->givePermissionTo('manage-audit');
        $user->givePermissionTo('manage-user');
    }
}
