<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update management_type to match Master data
        Schema::table('schools', function (Blueprint $table) {
            // Change management_type enum values to match Master data
            $table->enum('management_type', ['Private', 'Govt', 'Aided'])->default('Private')->change();
            
            // Update subscription_plan to include 'free' and match Master aliases
            $table->enum('subscription_plan', ['Free Plan', 'Basic Plan', 'Premium Plan', 'Enterprise Plan', 'Trial Plan'])->default('Trial Plan')->change();
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Revert to original values
            $table->enum('management_type', ['private', 'government', 'aided'])->default('private')->change();
            
            $table->enum('subscription_plan', ['trial', 'basic', 'standard', 'premium', 'enterprise'])->default('trial')->change();
        });
    }
};
