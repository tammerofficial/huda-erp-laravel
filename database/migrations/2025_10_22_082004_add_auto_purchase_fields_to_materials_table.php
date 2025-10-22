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
        Schema::table('materials', function (Blueprint $table) {
            $table->boolean('auto_purchase_enabled')->default(true)->after('is_active');
            $table->integer('auto_purchase_quantity')->default(50)->after('auto_purchase_enabled');
            $table->integer('min_stock_level')->default(5)->after('auto_purchase_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['auto_purchase_enabled', 'auto_purchase_quantity', 'min_stock_level']);
        });
    }
};
