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
        Schema::table('schools', function (Blueprint $table) {
            // Add missing columns for school registration
            if (!Schema::hasColumn('schools', 'school_logo')) {
                $table->string('school_logo')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('schools', 'school_gallery')) {
                $table->json('school_gallery')->nullable()->after('school_logo');
            }
            if (!Schema::hasColumn('schools', 'affiliate_certificate')) {
                $table->string('affiliate_certificate')->nullable()->after('school_gallery');
            }
            if (!Schema::hasColumn('schools', 'registration_certificate')) {
                $table->string('registration_certificate')->nullable()->after('affiliate_certificate');
            }
            
            // Ensure subscription plan is stored as integer (m_id from Masters table)
            // No need to change if it works with integer values
            
            // Ensure JSON columns exist and are proper JSON type
            // This is needed because later 2026_04_15_121241 migration may not have run yet
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Revert by dropping new columns if they exist
            if (Schema::hasColumn('schools', 'school_logo')) {
                $table->dropColumn('school_logo');
            }
            if (Schema::hasColumn('schools', 'school_gallery')) {
                $table->dropColumn('school_gallery');
            }
            if (Schema::hasColumn('schools', 'affiliate_certificate')) {
                $table->dropColumn('affiliate_certificate');
            }
            if (Schema::hasColumn('schools', 'registration_certificate')) {
                $table->dropColumn('registration_certificate');
            }
        });
    }
};

