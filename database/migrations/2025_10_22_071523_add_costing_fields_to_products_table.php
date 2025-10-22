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
        Schema::table('products', function (Blueprint $table) {
            // Costing fields
            $table->decimal('labor_cost_percentage', 5, 2)->nullable()->after('cost')->comment('Labor cost as percentage of material cost');
            $table->decimal('overhead_cost_percentage', 5, 2)->nullable()->after('labor_cost_percentage')->comment('Overhead cost as percentage of material cost');
            $table->decimal('suggested_price', 10, 2)->nullable()->after('overhead_cost_percentage')->comment('Calculated price based on cost + margin');
            $table->timestamp('last_cost_calculation_date')->nullable()->after('suggested_price');
            
            // Indexes
            $table->index('suggested_price');
            $table->index('last_cost_calculation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['suggested_price']);
            $table->dropIndex(['last_cost_calculation_date']);
            
            $table->dropColumn([
                'labor_cost_percentage',
                'overhead_cost_percentage',
                'suggested_price',
                'last_cost_calculation_date',
            ]);
        });
    }
};
