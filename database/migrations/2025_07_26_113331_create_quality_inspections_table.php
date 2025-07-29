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
        Schema::create('quality_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quality_check_id')->constrained('quality_checks')->onDelete('cascade');
            $table->string('parameter_name'); // e.g., "Weight", "Dimension", "Color", "Hardness"
            $table->string('parameter_type'); // 'numeric', 'text', 'boolean', 'selection'
            $table->text('expected_value'); // Expected/standard value
            $table->text('actual_value'); // Measured/observed value
            $table->string('unit')->nullable(); // Unit of measurement (kg, mm, etc.)
            $table->decimal('tolerance_min', 10, 4)->nullable();
            $table->decimal('tolerance_max', 10, 4)->nullable();
            $table->boolean('is_critical')->default(false);
            $table->enum('result', ['pass', 'fail', 'na']); // Result for this parameter
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['quality_check_id', 'parameter_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_inspections');
    }
};
