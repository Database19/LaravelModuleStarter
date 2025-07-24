<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('source')->nullable()->comment('Sumber lead: Website, Pameran, etc.');
            $table->enum('status', ['new', 'contacted', 'qualified', 'lost'])->default('new');
            $table->foreignId('owner_id')->comment('Salesperson yg bertanggung jawab')->constrained('users');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama deal, e.g., Proyek Instalasi PT ABC');
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained();
            $table->decimal('expected_value', 15, 2)->default(0);
            $table->date('expected_closing_date')->nullable();
            $table->enum('stage', ['prospecting', 'proposal', 'negotiation', 'won', 'lost'])->default('prospecting');
            $table->foreignId('owner_id')->comment('Salesperson yg bertanggung jawab')->constrained('users');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('leads');
    }
};
