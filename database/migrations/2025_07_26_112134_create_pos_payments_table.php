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
        Schema::create('pos_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_transaction_id')->constrained('pos_transactions')->onDelete('cascade');
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'ewallet', 'qris']);
            $table->decimal('amount', 15, 2);
            $table->decimal('received_amount', 15, 2)->nullable(); // For cash payments
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->string('reference_no')->nullable(); // Card/transfer reference
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->json('payment_details')->nullable(); // Store additional payment info
            $table->timestamp('payment_date');
            $table->timestamps();

            $table->index(['pos_transaction_id', 'payment_method']);
            $table->index(['payment_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_payments');
    }
};
