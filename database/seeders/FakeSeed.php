<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FakeSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedDepartments();
            $this->seedProductCategories();
            $this->seedCustomersAndSuppliers();
            $this->seedEmployees();
        });
    }

    private function seedDepartments(): void
    {
        // Clear existing data to avoid duplication
        Department::query()->delete();

        $adminUserId = User::first()?->id ?? 1;

        $departments = [
            ['name' => 'Sales & Marketing', 'code' => 'SLS-MKT'],
            ['name' => 'Finance & Accounting', 'code' => 'FIN-ACC'],
            ['name' => 'Human Resources', 'code' => 'HRD'],
            ['name' => 'Information Technology', 'code' => 'ITD'],
            ['name' => 'Operations', 'code' => 'OPS'],
            ['name' => 'Production', 'code' => 'PRD'],
        ];

        foreach ($departments as $dept) {
            Department::create([
                'name' => $dept['name'],
                'code' => $dept['code'],
                'description' => 'Departemen ' . $dept['name'],
                'is_active' => true,
                'created_by' => $adminUserId,
                'updated_by' => $adminUserId,
                'company_id' => 1,
            ]);
        }
    }

    private function seedProductCategories(): void
    {
        ProductCategory::query()->delete();

        $adminUserId = User::first()?->id ?? 1;

        // Create Parent Categories
        $stationery = ProductCategory::create([
            'name' => 'Alat Tulis Kantor (ATK)',
            'description' => 'Semua kebutuhan alat tulis untuk kantor.',
            'is_active' => true,
            'created_by' => $adminUserId,
            'updated_by' => $adminUserId,
            'company_id' => 1,
        ]);

        $electronics = ProductCategory::create([
            'name' => 'Elektronik Kantor',
            'description' => 'Peralatan elektronik untuk menunjang pekerjaan.',
            'is_active' => true,
            'created_by' => $adminUserId,
            'updated_by' => $adminUserId,
            'company_id' => 1,
        ]);

        $rawMaterials = ProductCategory::create([
            'name' => 'Bahan Baku Produksi',
            'description' => 'Material mentah untuk proses manufaktur.',
            'is_active' => true,
            'created_by' => $adminUserId,
            'updated_by' => $adminUserId,
            'company_id' => 1,
        ]);

        // Create Child Categories
        $childCategories = [
            [
                'name' => 'Buku & Kertas',
                'parent_id' => $stationery->id,
            ],
            [
                'name' => 'Pena & Pensil',
                'parent_id' => $stationery->id,
            ],
            [
                'name' => 'Komputer & Laptop',
                'parent_id' => $electronics->id,
            ],
            [
                'name' => 'Plastik & Resin',
                'parent_id' => $rawMaterials->id,
            ],
        ];

        foreach ($childCategories as $category) {
            ProductCategory::create([
                'name' => $category['name'],
                'parent_id' => $category['parent_id'],
                'is_active' => true,
                'created_by' => $adminUserId,
                'updated_by' => $adminUserId,
                'company_id' => 1,
            ]);
        }
    }

    private function seedCustomersAndSuppliers(): void
    {
        Customer::query()->delete();
        Supplier::query()->delete();

        for ($i = 0; $i < 50; $i++) {
            // Create Customer
            $customerType = fake('id_ID')->randomElement(['individual', 'company']);
            $companyName = ($customerType === 'company') ? fake('id_ID')->company() : null;
            $customerName = fake('id_ID')->name();

            Customer::create([
                'name' => $customerName,
                'email' => fake('id_ID')->unique()->safeEmail(),
                'phone' => fake('id_ID')->phoneNumber(),
                'address' => fake('id_ID')->address(),
                'company_name' => $companyName,
                'type' => $customerType,
                'tax_id' => fake('id_ID')->numerify('##.###.###.#-###.###'), // NPWP format
                'is_active' => fake('id_ID')->boolean(90), // 90% chance of being active
                'created_by' => 1,
                'updated_by' => 1,
                'company_id' => 1,
            ]);

            // Create Supplier
            $supplierName = fake('id_ID')->name();

            Supplier::create([
                'name' => $supplierName,
                'contact_person' => fake('id_ID')->name(),
                'email' => fake('id_ID')->unique()->safeEmail(),
                'phone' => fake('id_ID')->phoneNumber(),
                'address' => fake('id_ID')->address(),
                'tax_id' => fake('id_ID')->numerify('##.###.###.#-###.###'),
                'bank_name' => fake('id_ID')->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI', 'CIMB Niaga']),
                'bank_account' => fake('id_ID')->numerify('##########'),
                'is_active' => fake('id_ID')->boolean(90),
                'created_by' => 1,
                'updated_by' => 1,
                'company_id' => 1,
            ]);
        }
    }

    private function seedEmployees(): void
    {
        $adminUserId = User::first()?->id ?? 1;
        $departmentIds = Department::pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            $userName = fake('id_ID')->name();

            // Create User first
            $user = User::create([
                'name' => $userName,
                'email' => fake('id_ID')->unique()->safeEmail(),
                'password' => Hash::make('password'), // Default password
                'email_verified_at' => now(),
            ]);

            // Create Employee data linked to the new User
            $hireDate = fake('id_ID')->dateTimeBetween('-5 years', 'now');

            $user->employee()->create([
                'employee_id_number' => 'EMP-' . fake('id_ID')->unique()->numerify('#####'),
                'job_title' => fake('id_ID')->jobTitle(),
                'department_id' => fake('id_ID')->randomElement($departmentIds),
                'hire_date' => $hireDate,
                'termination_date' => null, // Assume new employees are still active
                'basic_salary' => fake('id_ID')->numberBetween(4000000, 15000000),
                'bank_name' => fake('id_ID')->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI']),
                'bank_account_number' => fake('id_ID')->numerify('##########'),
                'bank_account_holder' => $userName,
                'place_of_birth' => fake('id_ID')->city(),
                'date_of_birth' => fake('id_ID')->dateTimeBetween('-40 years', '-20 years'),
                'gender' => fake('id_ID')->randomElement(['male', 'female']),
                'marital_status' => fake('id_ID')->randomElement(['single', 'married']),
                'address_ktp' => fake('id_ID')->address(),
                'address_domicile' => fake('id_ID')->address(),
                'created_by' => $adminUserId,
                'updated_by' => $adminUserId,
                'company_id' => 1,
            ]);
        }
    }
}
