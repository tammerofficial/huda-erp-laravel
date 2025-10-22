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
        // Create payrolls table
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('base_salary', 10, 2);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->decimal('overtime_amount', 10, 2)->default(0);
            $table->decimal('bonuses', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->date('payment_date')->nullable();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'cheque'])->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index('employee_id');
            $table->index('period_start');
            $table->index('period_end');
            $table->index('status');
            $table->index('payment_date');
            $table->index('created_by');
        });
        
        // Create employee_work_logs table
        Schema::create('employee_work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('production_stage_id')->nullable()->constrained('production_stages')->onDelete('set null');
            $table->date('date');
            $table->decimal('hours_worked', 8, 2);
            $table->decimal('hourly_rate', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->enum('work_type', ['production', 'administrative', 'overtime'])->default('production');
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('employee_id');
            $table->index('production_stage_id');
            $table->index('date');
            $table->index('work_type');
            $table->index('approved_by');
            $table->index('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_work_logs');
        Schema::dropIfExists('payrolls');
    }
};
