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
        Schema::create('employee_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('event_type', [
                'birthday', 'anniversary', 'vacation', 'sick_leave', 'holiday', 
                'meeting', 'training', 'performance_review', 'contract_renewal',
                'probation_end', 'other'
            ]);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_type', ['yearly', 'monthly', 'weekly', 'daily'])->nullable();
            $table->string('color')->default('#3b82f6');
            $table->boolean('is_all_day')->default(false);
            $table->json('reminder_settings')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Indexes
            $table->index('employee_id');
            $table->index('event_date');
            $table->index('event_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_events');
    }
};