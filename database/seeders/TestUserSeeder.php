<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

        // Create test user
        $user = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'company_id' => 1, // Default company
                'is_super_admin' => true,
            ]
        );

        // Assign Super Admin role
        if (!$user->hasRole('Super Admin')) {
            $user->assignRole('Super Admin');
        }

        $this->command->info('Test user created: admin@test.com / password');
    }
}
