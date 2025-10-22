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
        Schema::table('orders', function (Blueprint $table) {
            // Shipping fields
            $table->decimal('shipping_cost', 10, 2)->nullable()->after('final_amount');
            $table->string('shipping_country')->nullable()->after('shipping_cost');
            $table->decimal('order_weight', 8, 2)->nullable()->after('shipping_country');
            
            // Cost breakdown fields
            $table->decimal('material_cost', 10, 2)->nullable()->after('order_weight');
            $table->decimal('labor_cost', 10, 2)->nullable()->after('material_cost');
            $table->decimal('overhead_cost', 10, 2)->nullable()->after('labor_cost');
            $table->decimal('total_cost', 10, 2)->nullable()->after('overhead_cost');
            $table->decimal('profit_margin', 5, 2)->nullable()->after('total_cost')->comment('Profit margin percentage');
            
            // Analytics fields
            $table->string('utm_source')->nullable()->after('profit_margin');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('referrer')->nullable()->after('utm_campaign');
            
            // Indexes for performance
            $table->index('shipping_country');
            $table->index('utm_source');
            $table->index('utm_medium');
            $table->index('profit_margin');
            $table->index('total_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['shipping_country']);
            $table->dropIndex(['utm_source']);
            $table->dropIndex(['utm_medium']);
            $table->dropIndex(['profit_margin']);
            $table->dropIndex(['total_cost']);
            
            $table->dropColumn([
                'shipping_cost',
                'shipping_country',
                'order_weight',
                'material_cost',
                'labor_cost',
                'overhead_cost',
                'total_cost',
                'profit_margin',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'referrer',
            ]);
        });
    }
};
