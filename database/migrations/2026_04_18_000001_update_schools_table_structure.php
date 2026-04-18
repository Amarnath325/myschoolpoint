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
            // Convert enum/string columns that should store m_id (integers)
            // These columns already exist as int(11) in the table, so no change needed
            
            // Fix JSON columns - ensure they're proper JSON/TEXT type
            $table->json('classes_available')->nullable()->change();
            $table->json('streams_available')->nullable()->change();
            $table->json('medium_of_instruction')->nullable()->change();
            
            // Ensure gallery and certificates are JSON
            $table->json('school_gallery')->nullable()->change();
            
            // Ensure status is integer (1 = active, 0 = inactive)
            // Already correct in table
            
            // Ensure subscription dates are date type
            // Already correct in table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Revert changes if needed
            $table->string('classes_available')->nullable()->change();
            $table->string('streams_available')->nullable()->change();
            $table->string('medium_of_instruction')->nullable()->change();
            $table->string('school_gallery')->nullable()->change();
        });
    }
};
