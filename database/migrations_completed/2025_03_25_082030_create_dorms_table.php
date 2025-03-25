<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the dorms table with all columns
     * consolidated from multiple migrations.
     */
    public function up(): void
    {
        Schema::create('dorms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->string('description')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->unsignedInteger('occupancy')->nullable();
            $table->string('dorm_master')->nullable();
            $table->unsignedInteger('dorm_master_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('session')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dorms');
    }
};
