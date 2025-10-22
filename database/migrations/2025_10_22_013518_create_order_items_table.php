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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->text('custom_requirements')->nullable(); // Special requirements for the item
            $table->string('size')->nullable(); // Size if applicable
            $table->string('color')->nullable(); // Color if applicable
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('order_id');
            $table->index('product_id');
            $table->index('quantity');
            $table->index('unit_price');
            $table->index('total_price');
            $table->index('size');
            $table->index('color');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
