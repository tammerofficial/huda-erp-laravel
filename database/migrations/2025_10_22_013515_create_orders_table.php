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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->date('order_date');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->enum('status', ['pending', 'on-hold', 'in-production', 'completed', 'cancelled', 'delivered'])->default('pending');
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->enum('payment_type', ['cash', 'credit', 'bank_transfer', 'card'])->nullable();
            $table->string('woo_id')->nullable()->unique(); // WooCommerce ID
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('status');
            $table->index('order_date');
            $table->index('customer_id');
            $table->index('created_by');
            $table->index('payment_status');
            $table->index('delivery_date');
            $table->index('priority');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
