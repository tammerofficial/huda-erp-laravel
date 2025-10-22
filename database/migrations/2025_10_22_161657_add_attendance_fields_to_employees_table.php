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
        Schema::table('employees', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('employees', 'salary_type')) {
                $table->string('salary_type')->default('monthly'); // monthly, per_piece, hourly
            }
            if (!Schema::hasColumn('employees', 'rate_per_hour')) {
                $table->decimal('rate_per_hour', 8, 3)->nullable();
            }
            if (!Schema::hasColumn('employees', 'rate_per_piece')) {
                $table->decimal('rate_per_piece', 8, 3)->nullable();
            }
            if (!Schema::hasColumn('employees', 'attendance_device_id')) {
                $table->string('attendance_device_id')->nullable(); // ZKTeco ID
            }
            if (!Schema::hasColumn('employees', 'efficiency_rating')) {
                $table->decimal('efficiency_rating', 3, 2)->default(1.00); // 1.00 = 100%
            }
            if (!Schema::hasColumn('employees', 'current_workload')) {
                $table->integer('current_workload')->default(0); // عدد المهام الحالية
            }
            if (!Schema::hasColumn('employees', 'qr_image_path')) {
                $table->string('qr_image_path')->nullable();
            }
            if (!Schema::hasColumn('employees', 'qr_enabled')) {
                $table->boolean('qr_enabled')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'salary_type', 'rate_per_hour', 'rate_per_piece', 
                'attendance_device_id', 'efficiency_rating', 
                'current_workload', 'qr_code', 'qr_image_path', 'qr_enabled'
            ]);
        });
    }
};
