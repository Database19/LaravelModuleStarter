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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'waiting_customer', 'resolved', 'closed'])->default('open');
            $table->enum('type', ['technical', 'billing', 'general', 'feature_request', 'bug_report'])->default('general');
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade'); // Who created the ticket
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Assigned support agent
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null'); // Related customer
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->datetime('due_date')->nullable();
            $table->datetime('resolved_at')->nullable();
            $table->datetime('closed_at')->nullable();
            $table->integer('satisfaction_rating')->nullable(); // 1-5 rating
            $table->text('satisfaction_comment')->nullable();
            $table->json('custom_fields')->nullable(); // Store additional fields as JSON
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index(['assigned_to', 'status']);
            $table->index(['requester_id', 'created_at']);
            $table->index(['customer_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
