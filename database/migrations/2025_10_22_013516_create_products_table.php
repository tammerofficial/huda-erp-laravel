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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('cost', 10, 2)->nullable(); // Production cost
            $table->string('category')->nullable();
            $table->string('image_url')->nullable();
            $table->string('woo_id')->nullable()->unique(); // WooCommerce ID
            $table->enum('product_type', ['standard', 'custom', 'service'])->default('standard');
            $table->boolean('is_active')->default(true);
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->string('unit')->default('piece'); // piece, meter, kg, etc.
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('specifications')->nullable(); // JSON for custom specifications
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('category');
            $table->index('product_type');
            $table->index('is_active');
            $table->index('stock_quantity');
            $table->index('reorder_level');
            $table->index('price');
            $table->index('woo_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
