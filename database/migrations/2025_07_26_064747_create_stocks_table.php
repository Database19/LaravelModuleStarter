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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_value', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            // Unique constraint to prevent duplicate product-warehouse combinations
            $table->unique(['product_id', 'warehouse_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
