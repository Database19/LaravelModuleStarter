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
        Schema::create('account_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key'); // sales_account, purchase_account, etc.
            $table->string('business_type_key'); // trading, service, manufacturing, etc.
            $table->json('keywords'); // Array of keywords to match against account names/codes
            $table->integer('priority')->default(1); // Higher number = higher priority
            $table->text('description')->nullable(); // Description of recommendation
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for performance
            $table->index(['setting_key', 'business_type_key']);
            $table->index(['setting_key', 'is_active']);
            $table->index(['business_type_key', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_recommendations');
    }
};
