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
        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('version')->default('1.0');
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->text('description')->nullable();
            $table->decimal('total_cost', 10, 2)->nullable(); // Calculated total cost
            $table->boolean('is_default')->default(false); // Default BOM for the product
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('product_id');
            $table->index('status');
            $table->index('is_default');
            $table->index('created_by');
            $table->index('version');
            $table->index('total_cost');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_of_materials');
    }
};
