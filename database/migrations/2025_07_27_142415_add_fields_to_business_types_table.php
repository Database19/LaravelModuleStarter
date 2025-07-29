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
        Schema::table('business_types', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('description');
            $table->string('color', 7)->default('#3B82F6')->after('icon');
            $table->integer('sort_order')->default(0)->after('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_types', function (Blueprint $table) {
            $table->dropColumn(['icon', 'color', 'sort_order']);
        });
    }
};
