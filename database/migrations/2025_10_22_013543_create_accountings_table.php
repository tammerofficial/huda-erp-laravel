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
        Schema::create('accountings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['revenue', 'expense', 'asset', 'liability', 'equity']);
            $table->string('category'); // sales, salary, material_cost, etc.
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->string('reference_type')->nullable(); // order, invoice, purchase_order, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('date');
            $table->index('type');
            $table->index('category');
            $table->index('created_by');
            $table->index('reference_type');
            $table->index('reference_id');
            $table->index('amount');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountings');
    }
};
