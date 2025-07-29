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
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->string('check_number')->unique();
            $table->enum('type', ['incoming', 'in_process', 'final', 'customer_return']);
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('batch_number')->nullable();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->onDelete('set null');
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_orders')->onDelete('set null');
            $table->foreignId('inspector_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('quality_standard_id')->nullable(); // Remove foreign key for now
            $table->integer('sample_size');
            $table->integer('defect_count')->default(0);
            $table->decimal('defect_rate', 5, 2)->default(0);
            $table->enum('result', ['pass', 'fail', 'conditional']);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected']);
            $table->datetime('inspection_date');
            $table->text('notes')->nullable();
            $table->json('inspection_data')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['product_id', 'inspection_date']);
            $table->index(['inspector_id', 'inspection_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_checks');
    }
};
