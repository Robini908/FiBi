<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the school_timetables table for managing class schedules
     */
    public function up(): void
    {
        // School Timetable Record - stores basic information about a timetable
        Schema::create('school_timetables', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('section_id')->nullable();
            $table->string('academic_term'); // First, Second, Third
            $table->string('academic_session'); // e.g., 2024-2025
            $table->boolean('is_active')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->foreign('class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->unique(['class_id', 'section_id', 'academic_term', 'academic_session'], 'unique_school_timetable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_timetables');
    }
}; 