<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company; // 1. Impor model Company
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage-accounting', 'manage-crm', 'manage-documents', 'manage-helpdesk',
            'manage-hr', 'manage-inventory', 'manage-maintenance', 'manage-manufacturing',
            'manage-pos', 'manage-projects', 'manage-purchasing', 'manage-quality-control',
            'manage-sales', 'manage-warehouse', 'manage-acl'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'Super Admin' => Permission::all()->pluck('name')->toArray(),
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
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        // --- Bagian 2: Buat Company terlebih dahulu ---
        Company::query()->delete();
        $companies = [
            [
                'name' => 'PT. Inovasi Digital Nusantara',
                'domain' => 'inovasi.localhost',
                'users' => [
                    ['name' => 'Admin Inovasi', 'email_prefix' => 'admin', 'role' => 'Admin'],
                    ['name' => 'Dede Febriansyah', 'email_prefix' => 'dede.f', 'role' => 'Sales Manager'],
                    ['name' => 'Anis Baswedan', 'email_prefix' => 'anis.b', 'role' => 'Sales Staff'],
                    ['name' => 'Ghezak', 'email_prefix' => 'ghezak', 'role' => 'HR Manager'],
                ]
            ],
            [
                'name' => 'CV. Maju Jaya Abadi',
                'domain' => 'majujaya.localhost',
                'users' => [
                    ['name' => 'Admin Maju Jaya', 'email_prefix' => 'admin', 'role' => 'Admin'],
                    ['name' => 'Lukman Nul Hakim', 'email_prefix' => 'lukman.h', 'role' => 'Accountant Manager'],
                    ['name' => 'Prabowo Subianto', 'email_prefix' => 'prabowo.s', 'role' => 'Accounting Staff'],
                    ['name' => 'Andika Pratama', 'email_prefix' => 'andika.p', 'role' => 'Warehouse Manager'],
                ]
            ],
        ];

        $firstCompany = null;
        foreach ($companies as $companyData) {
            // Buat perusahaan (tenant) baru
            $company = Company::create([
                'name' => $companyData['name'],
                'domain' => $companyData['domain'],
            ]);

            // Set first company untuk super admin
            if (!$firstCompany) {
                $firstCompany = $company;
            }

            // Buat pengguna untuk perusahaan ini
            foreach ($companyData['users'] as $userData) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email_prefix'] . '@' . $companyData['domain'],
                    'password' => Hash::make('password'),
                    'company_id' => $company->id, // <-- INI KUNCINYA
                ]);

                $user->syncRoles([$userData['role']]);
            }
        }

        // --- Bagian 3: Buat Super Admin Global (Terikat ke company pertama sebagai default) ---
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@erp.test'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'company_id' => $firstCompany->id, // Assign ke company pertama sebagai default
                'is_super_admin' => true, // Flag super admin
            ]
        );
        $superAdmin->syncRoles(['Super Admin']);

        // Buat role Super Admin jika belum ada
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->syncPermissions(Permission::all());
    }
}
