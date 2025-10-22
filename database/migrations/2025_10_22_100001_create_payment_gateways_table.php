<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // KNET, Visa, MasterCard, etc.
            $table->string('type'); // credit_card, debit_card, online
            $table->string('provider'); // payment provider name
            $table->decimal('transaction_fee_percentage', 5, 2)->default(0);
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
};

