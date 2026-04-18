<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id('menu_id');
            $table->foreignId('menu_p_id')->nullable()->constrained('menus', 'menu_id')->onDelete('cascade');
            $table->foreignId('menu_route_type_id')->nullable();
            $table->string('menu_name', 127)->nullable();
            $table->string('menu_icon', 50)->nullable();
            $table->boolean('menu_status')->default(true);
            $table->integer('menu_sub_status')->default(0);
            $table->string('menu_route', 255)->nullable();
            $table->string('menu_group', 55)->nullable();
            $table->integer('menu_sequence')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('menu_p_id');
            $table->index('menu_group');
            $table->index('menu_status');
            $table->index('menu_sequence');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};