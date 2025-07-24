<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();

            $table->date('pay_period_start_date');
            $table->date('pay_period_end_date');
            $table->date('payment_date');

            // Komponen Gaji
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowances', 15, 2)->default(0); // Tunjangan
            $table->decimal('deductions', 15, 2)->default(0); // Potongan
            $table->decimal('tax_amount', 15, 2)->default(0); // PPh 21
            $table->decimal('net_salary', 15, 2); // Gaji Bersih (Take-home pay)

            $table->enum('status', ['Paid', 'Pending', 'Failed']);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
