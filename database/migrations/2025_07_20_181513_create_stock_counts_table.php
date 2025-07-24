<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->id();
            $table->string('count_number')->unique();
            $table->foreignId('warehouse_id')->constrained();
            $table->date('count_date');
            $table->enum('status', ['draft', 'counting', 'completed'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('stock_count_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_count_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('system_quantity')->comment('Stok di sistem saat perhitungan dimulai');
            $table->unsignedInteger('counted_quantity')->nullable()->comment('Stok hasil hitung fisik');
            $table->integer('variance')->virtualAs('counted_quantity - system_quantity')->comment('Selisih');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_count_items');
        Schema::dropIfExists('stock_counts');
    }
};
