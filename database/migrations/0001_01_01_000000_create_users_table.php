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
        // =================================================================
        // STEP 1: Create all tables without foreign key constraints
        // =================================================================

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique()->nullable();
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->decimal('base_salary', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('employee_id')->unique()->nullable()->comment('Nomor Induk Pegawai');
            $table->string('phone_number')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->date('hire_date')->nullable()->comment('Tanggal Mulai Bekerja');
            $table->date('birth_date')->nullable()->comment('Tanggal Lahir');
            $table->enum('employment_status', ['active', 'inactive', 'terminated'])->default('active');
            $table->decimal('salary', 15, 2)->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable()->comment('Self-referencing key untuk atasan');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });

        // =================================================================
        // STEP 2: Add all foreign key constraints now that tables exist
        // =================================================================

        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
        });

        // Other tables (password resets, sessions)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order of creation, handling foreign keys
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');

        // Drop tables with foreign keys first
        Schema::dropIfExists('users');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('departments');
    }
};
