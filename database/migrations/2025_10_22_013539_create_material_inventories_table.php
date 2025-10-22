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
        Schema::create('material_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('reorder_level')->default(0);
            $table->integer('max_level')->nullable();
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('material_id');
            $table->index('warehouse_id');
            $table->index('quantity');
            $table->index('reorder_level');
            $table->index('max_level');
            $table->index('last_updated');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_inventories');
    }
};
