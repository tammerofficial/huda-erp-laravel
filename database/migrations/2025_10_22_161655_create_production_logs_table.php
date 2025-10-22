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
        Schema::create('production_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('production_stage_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('pieces_completed')->default(0);
            $table->decimal('rate_per_piece', 8, 3)->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->integer('duration_minutes')->nullable(); // محسوب تلقائياً
            $table->decimal('earnings', 10, 3)->default(0); // محسوب تلقائياً
            $table->decimal('expected_duration', 5, 2)->nullable(); // بالدقائق
            $table->decimal('efficiency_rate', 5, 2)->nullable(); // نسبة مئوية
            $table->string('quality_status')->default('pending'); // pending, approved, rejected
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['employee_id', 'start_time']);
            $table->index('production_stage_id');
            $table->index('quality_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_logs');
    }
};
