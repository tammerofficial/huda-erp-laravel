<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('payment_gateway_id')->constrained()->onDelete('cascade');
            $table->morphs('payable'); // invoice, order, etc.
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->string('currency', 3)->default('KWD');
            $table->string('status'); // pending, completed, failed, refunded
            $table->string('reference_number')->nullable();
            $table->text('response_data')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
};

