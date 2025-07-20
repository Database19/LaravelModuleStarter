<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bom_items', function (Blueprint $table) {
            $table->id();
            // Relasi ke resep induknya
            $table->foreignId('bom_id')->constrained('boms')->cascadeOnDelete();
            // Produk komponen/bahan baku yang dibutuhkan
            $table->foreignId('component_product_id')->constrained('products')->cascadeOnDelete();
            // Jumlah komponen yang dibutuhkan untuk membuat 1 unit Produk Jadi
            $table->decimal('quantity', 10, 4);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bom_items');
    }
};
