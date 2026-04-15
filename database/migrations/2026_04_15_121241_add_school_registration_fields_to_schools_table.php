<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Basic School Information
            $table->string('school_code')->nullable()->after('business_name');
            $table->enum('management_type', ['private', 'government', 'aided'])->default('private')->after('school_type');
            
            // Location Details
            $table->string('full_address')->nullable()->after('address');
            $table->string('latitude')->nullable()->after('pincode');
            $table->string('longitude')->nullable()->after('latitude');
            
            // Contact Details
            $table->string('contact_number')->nullable()->after('mobile');
            
            // Affiliation Details
            $table->string('affiliation_number')->nullable()->after('affiliation_board');
            $table->enum('affiliation_status', ['active', 'pending', 'expired'])->default('pending')->after('affiliation_number');
            
            // Academic Structure
            $table->json('classes_available')->nullable()->after('affiliation_status');
            $table->json('streams_available')->nullable()->after('classes_available');
            $table->json('medium_of_instruction')->nullable()->after('streams_available');
            
            // Infrastructure Details
            $table->boolean('has_labs')->default(false)->after('medium_of_instruction');
            $table->boolean('has_library')->default(false)->after('has_labs');
            $table->boolean('has_sports')->default(false)->after('has_library');
            $table->boolean('has_hostel')->default(false)->after('has_sports');
            $table->boolean('has_transport')->default(false)->after('has_hostel');
            
            // About School
            $table->text('about_school')->nullable()->after('has_transport');
            
            // Indexes
            $table->index('school_code');
            $table->index('affiliation_number');
            $table->index('management_type');
            $table->index('affiliation_status');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'school_code', 'management_type', 'full_address', 'latitude', 'longitude',
                'contact_number', 'affiliation_number', 'affiliation_status', 'classes_available',
                'streams_available', 'medium_of_instruction', 'has_labs', 'has_library',
                'has_sports', 'has_hostel', 'has_transport', 'about_school'
            ]);
        });
    }
};