<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name');
            $table->string('asset_code')->unique();
            $table->text('description')->nullable();

            $table->date('purchase_date');
            $table->decimal('purchase_cost', 15, 2);
            $table->unsignedInteger('useful_life')->comment('Masa manfaat dalam bulan');
            $table->decimal('salvage_value', 15, 2)->default(0)->comment('Nilai sisa');

            $table->decimal('monthly_depreciation', 15, 2); // (Cost - Salvage) / Useful Life

            // Relasi ke Akun Akuntansi
            $table->foreignId('asset_account_id')->constrained('accounts'); // Akun Aset (e.g., Kendaraan, Gedung)
            $table->foreignId('accumulated_depreciation_account_id')->constrained('accounts'); // Akun Akumulasi Penyusutan
            $table->foreignId('depreciation_expense_account_id')->constrained('accounts'); // Akun Beban Penyusutan

            $table->enum('status', ['active', 'disposed', 'fully_depreciated'])->default('active');
            $table->date('disposal_date')->nullable();

            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};
