<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boms', function (Blueprint $table) {
            $table->id();
            // Produk Jadi yang akan dibuat menggunakan resep ini
            $table->foreignId('product_id')->unique()->constrained('products')->cascadeOnDelete();
            $table->string('name'); // Nama resep, cth: "Resep Laptop ProBook"
            $table->text('description')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boms');
    }
};
