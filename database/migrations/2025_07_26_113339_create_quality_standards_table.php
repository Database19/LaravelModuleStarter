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
        Schema::create('quality_standards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->onDelete('cascade');
            $table->enum('applicable_stage', ['incoming', 'in_process', 'final', 'all']);
            $table->json('parameters'); // Store quality parameters as JSON
            $table->integer('sample_size_min')->default(1);
            $table->integer('sample_size_max')->default(10);
            $table->decimal('acceptable_defect_rate', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['is_active', 'applicable_stage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_standards');
    }
};
