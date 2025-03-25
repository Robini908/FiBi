<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the parent_details table with all columns
     * consolidated from multiple migrations.
     */
    public function up(): void
    {
        Schema::create('parent_details', function (Blueprint $table) {
            $table->string('parent_id_no')->primary();  // Unique parent ID (primary key)
            $table->unsignedInteger('user_id')->nullable();
            $table->string('parent_first_name');
            $table->string('parent_middle_name')->nullable();
            $table->string('parent_last_name');
            $table->string('parent_phone_number');
            $table->string('parent_email')->unique();
            $table->string('parent_password');
            $table->timestamps();
            
            // Foreign key constraint
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
        Schema::dropIfExists('parent_details');
    }
};
