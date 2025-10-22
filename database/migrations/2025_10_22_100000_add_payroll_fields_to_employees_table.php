<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('overtime_rate', 10, 2)->default(0)->after('salary');
            $table->decimal('bonus_rate', 10, 2)->default(0)->after('overtime_rate');
            $table->string('payment_method')->default('bank_transfer')->after('bonus_rate');
            $table->string('bank_account')->nullable()->after('payment_method');
            $table->string('bank_name')->nullable()->after('bank_account');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['overtime_rate', 'bonus_rate', 'payment_method', 'bank_account', 'bank_name']);
        });
    }
};

