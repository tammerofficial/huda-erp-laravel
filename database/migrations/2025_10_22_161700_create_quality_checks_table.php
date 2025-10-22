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
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('inspector_id')->constrained('employees');
            $table->string('status'); // pending, passed, failed
            $table->integer('items_checked')->default(0);
            $table->integer('items_passed')->default(0);
            $table->integer('items_failed')->default(0);
            $table->json('defects')->nullable(); // قائمة العيوب
            $table->text('notes')->nullable();
            $table->dateTime('inspection_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_checks');
    }
};
