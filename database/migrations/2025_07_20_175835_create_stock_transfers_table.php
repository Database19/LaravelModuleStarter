<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique();
            $table->foreignId('source_warehouse_id')->constrained('warehouses');
            $table->foreignId('destination_warehouse_id')->constrained('warehouses');
            $table->date('transfer_date');
            $table->enum('status', ['draft', 'shipped', 'received'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_transfer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_transfer_items');
        Schema::dropIfExists('stock_transfers');
    }
};
