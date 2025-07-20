<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manufacturing_orders', function (Blueprint $table) {
            $table->id();
            $table->string('mo_number')->unique();
            // Produk Jadi yang akan diproduksi
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            // Resep (BOM) yang digunakan
            $table->foreignId('bom_id')->constrained('boms')->cascadeOnDelete();

            $table->decimal('quantity_to_produce', 15, 2);
            $table->decimal('quantity_produced', 15, 2)->default(0);

            $table->date('start_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Cancelled']);
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manufacturing_orders');
    }
};
