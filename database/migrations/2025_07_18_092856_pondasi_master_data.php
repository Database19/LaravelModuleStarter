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
        // Schema::create('brands', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name')->unique();
        //     $table->string('logo_url')->nullable();
        //     $table->timestamps();
        // });

        // Schema::create('units', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name')->comment('Contoh: Piece, Kilogram, Box');
        //     $table->string('short_code')->unique()->comment('Contoh: pcs, kg, box');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
