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
        Schema::create('production_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained()->onDelete('cascade');
            $table->string('stage_name'); // cutting, sewing, embroidery, ironing, quality_check
            $table->enum('status', ['pending', 'in-progress', 'completed', 'skipped'])->default('pending');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('duration_minutes')->nullable(); // Calculated duration
            $table->text('notes')->nullable();
            $table->json('quality_checks')->nullable(); // JSON for quality check results
            $table->integer('sequence_order')->default(1); // Order of stages
            $table->timestamps();
            
            // Indexes for performance
            $table->index('production_order_id');
            $table->index('status');
            $table->index('employee_id');
            $table->index('sequence_order');
            $table->index('stage_name');
            $table->index('start_time');
            $table->index('end_time');
            $table->index('duration_minutes');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_stages');
    }
};
