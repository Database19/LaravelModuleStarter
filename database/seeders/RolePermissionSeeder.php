<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'manage-maintenance'
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

        // $admin = User::find(1);
        // assign

        // Assign permissions to roles
        $admin->givePermissionTo($permissions);
        $salesManager->givePermissionTo('manage-sales');
        $accountant->givePermissionTo('manage-accounting');
        $inventoryClerk->givePermissionTo('manage-inventory');
        $maintenance->givePermissionTo('manage-maintenance');
    }
}
