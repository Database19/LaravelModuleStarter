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
        Schema::create('pos_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no')->unique();
            $table->enum('type', ['sale', 'return'])->default('sale');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('cashier_id')->constrained('users')->onDelete('cascade');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'partial_refund', 'refunded'])->default('unpaid');
            $table->datetime('transaction_date');
            $table->text('notes')->nullable();
            $table->foreignId('reference_transaction_id')->nullable()->constrained('pos_transactions')->onDelete('set null');
            $table->timestamps();

            $table->index(['transaction_date', 'status']);
            $table->index(['customer_id', 'transaction_date']);
            $table->index(['cashier_id', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_transactions');
    }
};
