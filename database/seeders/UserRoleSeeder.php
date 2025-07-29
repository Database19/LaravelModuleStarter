<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create additional roles
        $roles = [
            'admin',
            'accounting-manager',
            'hr-manager',
            'sales-manager',
            'warehouse-manager',
            'pos-operator',
            'employee'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Create sample users
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@erp.com', 'role' => 'admin'],
            ['name' => 'Accounting Manager', 'email' => 'accounting@erp.com', 'role' => 'accounting-manager'],
            ['name' => 'HR Manager', 'email' => 'hr@erp.com', 'role' => 'hr-manager'],
            ['name' => 'Sales Manager', 'email' => 'sales@erp.com', 'role' => 'sales-manager'],
            ['name' => 'Warehouse Manager', 'email' => 'warehouse@erp.com', 'role' => 'warehouse-manager'],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $userData['password'] = Hash::make('password123');
            $userData['email_verified_at'] = now();
            $userData['company_id'] = 1;
            $userData['is_super_admin'] = false;
            $userData['employee_id'] = strtoupper(substr($userData['name'], 0, 2)) . '001';
            $userData['phone_number'] = '+62812345678';
            $userData['hire_date'] = now();
            $userData['birth_date'] = '1990-01-01';
            $userData['gender'] = 'male';
            $userData['address'] = 'Jakarta, Indonesia';
            $userData['employment_status'] = 'active';
            $userData['salary'] = 8000000.00;

            $user = User::firstOrCreate(['email' => $userData['email']], $userData);
            $user->assignRole($role);
        }

        $this->command->info('Additional users created successfully!');
    }
}
