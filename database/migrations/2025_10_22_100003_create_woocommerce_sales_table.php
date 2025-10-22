<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('woocommerce_sales', function (Blueprint $table) {
            $table->id();
            $table->string('wc_order_id')->unique();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_email');
            $table->string('customer_name');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->decimal('production_cost', 15, 2)->default(0);
            $table->decimal('profit', 15, 2)->default(0);
            $table->string('status');
            $table->string('payment_method')->nullable();
            $table->timestamp('order_date');
            $table->json('items')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('woocommerce_sales');
    }
};

