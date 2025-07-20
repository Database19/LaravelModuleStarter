<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel untuk Pelanggan (Customers)
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('company_name')->nullable();
            $table->enum('type', ['individual', 'company'])->default('individual');
            $table->string('tax_id')->nullable()->comment('NPWP untuk Indonesia');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel untuk Pemasok (Suppliers)
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('tax_id')->nullable()->comment('NPWP untuk Indonesia');
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // == MODUL INVENTARIS & GUDANG ==

        // Tabel untuk Gudang (Warehouses)
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->comment('Kode gudang untuk identifikasi');
            $table->text('location');
            $table->boolean('is_active')->default(true);
            $table->foreignId('manager_id')->nullable()->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk Brand
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('logo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk Unit
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Contoh: Piece, Kilogram, Box');
            $table->string('short_code')->unique()->comment('Contoh: pcs, kg, box');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk Kategori Produk
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk Produk (Items/Services)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->comment('Stock Keeping Unit');
            $table->string('barcode')->unique()->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('product_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->enum('type', ['product', 'service'])->default('product');
            $table->decimal('price', 15, 2)->comment('Harga jual');
            $table->decimal('cost', 15, 2)->comment('Harga beli/modal');
            $table->unsignedInteger('quantity')->default(0)->comment('Total kuantitas di semua gudang');
            $table->unsignedInteger('min_stock')->default(0)->comment('Minimum stock untuk alert');
            $table->boolean('track_stock')->default(true);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel untuk melacak stok per gudang (Pivot Table)
        Schema::create('warehouse_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('rack_location')->nullable(); // e.g., A-01-02
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->unique(['warehouse_id', 'product_id']);
        });

        // Tabel untuk pergerakan stok (masuk/keluar)
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer']);
            $table->integer('quantity');
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->text('reason')->nullable();
            $table->foreignId('user_id')->comment('User yang bertanggung jawab')->constrained();
            $table->morphs('reference'); // Polymorphic relation to SalesOrder, PurchaseOrder, etc.
            $table->timestamp('movement_date');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // == MODUL PENJUALAN & POS ==

        // Tabel untuk Pesanan Penjualan (Sales Orders)
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('user_id')->comment('Sales person')->constrained();
            $table->foreignId('warehouse_id')->nullable()->constrained();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['draft', 'confirmed', 'processing', 'shipped', 'completed', 'cancelled']);
            $table->timestamp('order_date');
            $table->timestamp('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel item untuk Pesanan Penjualan (Pivot)
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // == MODUL PEMBELIAN ==

        // Tabel untuk Pesanan Pembelian (Purchase Orders)
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('user_id')->comment('Purchasing staff')->constrained();
            $table->foreignId('warehouse_id')->nullable()->constrained();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['draft', 'ordered', 'received', 'completed', 'cancelled']);
            $table->timestamp('order_date');
            $table->timestamp('expected_delivery_date')->nullable();
            $table->timestamp('received_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel item untuk Pesanan Pembelian (Pivot)
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('received_quantity')->default(0);
            $table->decimal('unit_cost', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // == MODUL AKUNTANSI ==

        // Tabel untuk Bagan Akun (Chart of Accounts)
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code')->unique();
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->enum('sub_type', ['current_asset', 'fixed_asset', 'current_liability', 'long_term_liability', 'owner_equity', 'operating_revenue', 'other_revenue', 'operating_expense', 'other_expense'])->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('accounts');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk Jurnal Transaksi
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('journal_number')->unique();
            $table->date('date');
            $table->text('description');
            $table->decimal('total_debit', 15, 2);
            $table->decimal('total_credit', 15, 2);
            $table->morphs('referenceable'); // Polymorphic relation to Invoice, Bill, etc.
            $table->foreignId('user_id')->constrained();
            $table->boolean('is_posted')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk detail Jurnal (Debit/Kredit)
        Schema::create('journal_entry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->foreignId('account_id')->constrained();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // == MODUL SDM (HR) ==

        // Tabel untuk Departemen
        // Schema::create('departments', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('code')->unique();
        //     $table->text('description')->nullable();
        //     $table->foreignId('manager_id')->nullable()->constrained('users');
        //     $table->foreignId('parent_id')->nullable()->constrained('departments');
        //     $table->boolean('is_active')->default(true);
        //     $table->foreignId('created_by')->constrained('users');
        //     $table->foreignId('updated_by')->constrained('users');
        //     $table->timestamps();
        // });

        // Tabel untuk Jabatan/Posisi
        // Schema::create('positions', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('code')->unique();
        //     $table->text('description')->nullable();
        //     $table->foreignId('department_id')->constrained();
        //     $table->decimal('base_salary', 15, 2)->nullable();
        //     $table->boolean('is_active')->default(true);
        //     $table->foreignId('created_by')->constrained('users');
        //     $table->foreignId('updated_by')->constrained('users');
        //     $table->timestamps();
        // });

        // Tabel untuk Karyawan (bisa diperluas dari tabel users atau tabel terpisah)
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('employee_id')->unique()->nullable()->after('email');
        //     $table->foreignId('department_id')->nullable()->after('password')->constrained();
        //     $table->foreignId('position_id')->nullable()->after('department_id')->constrained();
        //     $table->string('phone_number')->nullable()->after('position_id');
        //     $table->date('hire_date')->nullable()->after('phone_number');
        //     $table->date('birth_date')->nullable()->after('hire_date');
        //     $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
        //     $table->text('address')->nullable()->after('gender');
        //     $table->enum('employment_status', ['active', 'inactive', 'terminated'])->default('active')->after('address');
        //     $table->decimal('salary', 15, 2)->nullable()->after('employment_status');
        //     $table->foreignId('manager_id')->nullable()->after('salary')->constrained('users');
        // });

        // == MODUL PROYEK ==

        // Tabel untuk Status Proyek
        Schema::create('project_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk Proyek
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->foreignId('manager_id')->comment('Project Manager')->constrained('users');
            $table->foreignId('project_status_id')->constrained();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->decimal('actual_cost', 15, 2)->default(0);
            $table->tinyInteger('progress')->default(0)->comment('Progress dalam persen 0-100');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk Tim Proyek
        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->enum('role', ['member', 'lead', 'manager'])->default('member');
            $table->date('joined_date');
            $table->date('left_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->unique(['project_id', 'user_id']);
        });

        // Tabel untuk Status Tugas
        Schema::create('task_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk Tugas dalam Proyek
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assignee_id')->nullable()->constrained('users');
            $table->foreignId('task_status_id')->constrained();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->tinyInteger('progress')->default(0)->comment('Progress dalam persen 0-100');
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->decimal('actual_hours', 8, 2)->default(0);
            $table->foreignId('parent_id')->nullable()->constrained('tasks');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk Komentar Tugas
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->text('comment');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel untuk Attachment/File
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->string('path');
            $table->morphs('attachable');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus dalam urutan terbalik untuk menghindari masalah foreign key
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_statuses');
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_statuses');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['position_id']);
            $table->dropForeign(['manager_id']);
            $table->dropColumn([
                'employee_id', 'department_id', 'position_id', 'phone_number',
                'hire_date', 'birth_date', 'gender', 'address',
                'employment_status', 'salary', 'manager_id'
            ]);
        });

        Schema::dropIfExists('positions');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('journal_entry_items');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('sales_order_items');
        Schema::dropIfExists('sales_orders');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('warehouse_stock');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('units');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('customers');
    }
};
