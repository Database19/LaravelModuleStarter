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
        Schema::create('business_types', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // trading, service, manufacturing, etc.
            $table->string('name'); // Display name
            $table->text('description')->nullable(); // Description of business type
            $table->string('icon')->nullable(); // Icon class or SVG
            $table->string('color')->default('#3B82F6'); // Color theme for UI
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // For ordering
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_types');
    }
};
