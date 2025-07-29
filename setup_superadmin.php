<?php

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

// Create manage-acl permission if not exists
$permission = Permission::firstOrCreate(['name' => 'manage-acl']);

// Create Super Admin role if not exists
$superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

// Assign permission to role
$superAdminRole->givePermissionTo($permission);

// Find or create super admin user
$superAdmin = User::where('is_super_admin', true)->first();

if (!$superAdmin) {
    $superAdmin = User::create([
        'name' => 'Super Administrator',
        'email' => 'superadmin@example.com',
        'password' => bcrypt('password'),
        'is_super_admin' => true,
    ]);
}

// Assign role to super admin
$superAdmin->assignRole($superAdminRole);

echo "Super Admin setup completed!\n";
echo "Email: " . $superAdmin->email . "\n";
echo "Permissions: " . $superAdmin->getAllPermissions()->pluck('name')->implode(', ') . "\n";
