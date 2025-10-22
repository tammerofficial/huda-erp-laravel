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
            // Personal Information
            $table->string('nationality')->nullable()->after('birth_date');
            $table->string('civil_id')->nullable()->after('nationality');
            $table->string('passport_number')->nullable()->after('civil_id');
            $table->date('passport_expiry')->nullable()->after('passport_number');
            $table->string('blood_type')->nullable()->after('passport_expiry');
            $table->string('emergency_contact_name')->nullable()->after('blood_type');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_phone');
            
            // Employment Details
            $table->date('probation_end_date')->nullable()->after('hire_date');
            $table->string('work_schedule')->nullable()->after('probation_end_date');
            $table->integer('vacation_days_entitled')->default(0)->after('work_schedule');
            $table->integer('vacation_days_used')->default(0)->after('vacation_days_entitled');
            $table->integer('sick_days_used')->default(0)->after('vacation_days_used');
            
            // Document Attachments
            $table->json('documents')->nullable()->after('sick_days_used');
            $table->string('profile_photo')->nullable()->after('documents');
            $table->string('id_card_front')->nullable()->after('profile_photo');
            $table->string('id_card_back')->nullable()->after('id_card_front');
            $table->string('passport_photo')->nullable()->after('id_card_back');
            $table->string('visa_photo')->nullable()->after('passport_photo');
            $table->string('contract_document')->nullable()->after('visa_photo');
            $table->string('medical_certificate')->nullable()->after('contract_document');
            $table->string('other_documents')->nullable()->after('medical_certificate');
            
            // Indexes
            $table->index('civil_id');
            $table->index('passport_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'nationality', 'civil_id', 'passport_number', 'passport_expiry',
                'blood_type', 'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
                'probation_end_date', 'work_schedule',
                'vacation_days_entitled', 'vacation_days_used', 'sick_days_used',
                'documents', 'profile_photo', 'id_card_front', 'id_card_back', 'passport_photo',
                'visa_photo', 'contract_document', 'medical_certificate', 'other_documents'
            ]);
        });
    }
};