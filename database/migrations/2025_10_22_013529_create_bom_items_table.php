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
        Schema::create('bom_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained('bill_of_materials')->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 3); // Quantity needed
            $table->string('unit'); // Unit of measurement
            $table->decimal('unit_cost', 10, 2); // Cost per unit
            $table->decimal('total_cost', 10, 2); // Calculated total cost
            $table->text('notes')->nullable();
            $table->integer('sequence_order')->default(1); // Order in BOM
            $table->timestamps();
            
            // Indexes for performance
            $table->index('bom_id');
            $table->index('material_id');
            $table->index('sequence_order');
            $table->index('unit_cost');
            $table->index('total_cost');
            $table->index('quantity');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_items');
    }
};
