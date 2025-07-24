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
        Schema::create('company_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('module_key'); // e.g., 'sales', 'hr', 'manufacturing'
            $table->date('expires_at')->nullable(); // Untuk langganan berbatas waktu
            $table->timestamps();

            $table->unique(['company_id', 'module_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_subscriptions');
    }
};
