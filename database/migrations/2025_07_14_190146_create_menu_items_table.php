<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('parent_id')->nullable();
            $table->string('name')->comment('Teks yang ditampilkan di menu, e.g., "Akuntansi"');
            $table->string('route')->comment('Nama rute Laravel, e.g., "accounting.index"');
            $table->string('permission_name')->comment('Nama izin yang diperlukan untuk melihat menu ini');
            $table->text('icon_svg')->nullable()->comment('Kode SVG lengkap untuk ikon');
            $table->string('group')->comment('Grup menu, e.g., "Core Modules"');
            $table->integer('status')->nullable();
            $table->integer('order')->default(0)->comment('Urutan tampilan di dalam grup');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
