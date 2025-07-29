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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('period'); // e.g., 'Q1 2025', 'Annual 2025'
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('used_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
