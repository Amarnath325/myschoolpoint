<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('masters', function (Blueprint $table) {
            $table->id('m_id');
            $table->string('m_group', 100)->index();
            $table->string('m_name', 255);
            $table->string('m_alias_name', 255)->nullable();
            $table->string('m_type', 50)->nullable()->index();
            $table->json('m_other')->nullable();
            $table->text('m_description')->nullable();
            $table->timestamps();
            
            // Composite indexes for better performance
            $table->index(['m_group', 'm_type']);
            $table->index(['m_group', 'm_name']);
            $table->index('created_at');
            
            // Unique constraint to prevent duplicates
            $table->unique(['m_group', 'm_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('masters');
    }
};
