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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('unit'); // meter, kg, piece, etc.
            $table->decimal('unit_cost', 10, 2);
            $table->string('category')->nullable(); // fabric, thread, button, zipper, etc.
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('reorder_level')->default(0);
            $table->integer('max_stock')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('category');
            $table->index('supplier_id');
            $table->index('is_active');
            $table->index('reorder_level');
            $table->index('max_stock');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
