<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            // Relasi satu-ke-satu dengan tabel users
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

            $table->string('employee_id_number')->unique()->nullable()->comment('Nomor Induk Karyawan');
            $table->string('job_title')->nullable();
            $table->foreignId('department_id')->nullable()->constrained(); // Asumsi Anda punya tabel 'departments'
            $table->date('hire_date')->nullable()->comment('Tanggal Mulai Bekerja');
            $table->date('termination_date')->nullable()->comment('Tanggal Berhenti');

            // Informasi untuk Payroll
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();

            // Informasi Pribadi Tambahan
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->text('address_ktp')->nullable();
            $table->text('address_domicile')->nullable();

            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
