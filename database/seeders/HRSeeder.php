<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;

class HRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user yang relevan
        $hr_user = User::where('email', 'hr@erp.test')->first();
        $sales_user = User::where('email', 'sales@erp.test')->first();
        $accountant_user = User::where('email', 'accounting@erp.test')->first();

        // Ambil departemen yang relevan
        $sales_dept = Department::where('name', 'Penjualan & Pemasaran')->first();
        $finance_dept = Department::where('name', 'Keuangan & Akuntansi')->first();

        // === Buat Data Karyawan ===
        DB::table('employees')->insert([
            [
                'user_id' => $sales_user->id,
                'department_id' => $sales_dept->id,
                'employee_id_number' => 'EMP-001',
                'job_title' => 'Sales Manager',
                'join_date' => '2023-01-15',
                'employment_status' => 'Full-time',
                'phone_number' => '081211112222',
                'address' => 'Jl. Penjualan No. 1, Jakarta',
                'date_of_birth' => '1990-05-20',
                'basic_salary' => 15000000,
                'created_by' => $hr_user->id,
                'updated_by' => $hr_user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $accountant_user->id,
                'department_id' => $finance_dept->id,
                'employee_id_number' => 'EMP-002',
                'job_title' => 'Staf Akuntansi',
                'join_date' => '2024-02-20',
                'employment_status' => 'Full-time',
                'phone_number' => '081233334444',
                'address' => 'Jl. Keuangan No. 2, Depok',
                'date_of_birth' => '1995-11-10',
                'basic_salary' => 8000000,
                'created_by' => $hr_user->id,
                'updated_by' => $hr_user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Ambil data employee yang baru dibuat untuk seeder payroll
        $sales_employee = DB::table('employees')->where('employee_id_number', 'EMP-001')->first();
        $accountant_employee = DB::table('employees')->where('employee_id_number', 'EMP-002')->first();

        // === Buat Data Riwayat Gaji (Payroll) ===
        DB::table('payrolls')->insert([
            // Gaji bulan lalu untuk Sales Manager
            [
                'employee_id' => $sales_employee->id,
                'pay_period_start_date' => now()->subMonth()->startOfMonth(),
                'pay_period_end_date' => now()->subMonth()->endOfMonth(),
                'payment_date' => now()->subMonth()->setDay(25),
                'basic_salary' => 15000000,
                'allowances' => 5000000, // Tunjangan jabatan & komisi
                'deductions' => 0,
                'tax_amount' => 1500000,
                'net_salary' => 18500000, // (15jt + 5jt) - 1.5jt
                'status' => 'Paid',
                'created_by' => $hr_user->id,
                'updated_by' => $hr_user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Gaji bulan lalu untuk Staf Akuntansi
            [
                'employee_id' => $accountant_employee->id,
                'pay_period_start_date' => now()->subMonth()->startOfMonth(),
                'pay_period_end_date' => now()->subMonth()->endOfMonth(),
                'payment_date' => now()->subMonth()->setDay(25),
                'basic_salary' => 8000000,
                'allowances' => 500000, // Tunjangan makan & transport
                'deductions' => 150000, // Potongan BPJS
                'tax_amount' => 250000,
                'net_salary' => 8100000, // (8jt + 500rb) - (150rb + 250rb)
                'status' => 'Paid',
                'created_by' => $hr_user->id,
                'updated_by' => $hr_user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
