<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop the old tables if they exist
        Schema::dropIfExists('time_tables');
        Schema::dropIfExists('time_slots');
        Schema::dropIfExists('time_table_records');
        Schema::dropIfExists('timetable_entries');
        Schema::dropIfExists('timetable_records');

        // Step 2: Create new tables with clear naming conventions
        
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

        // Timetable Period - represents a time period/slot in the schedule
        Schema::create('timetable_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timetable_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('period_name')->nullable(); // e.g., "Period 1", "Lunch", "Break"
            $table->integer('period_order')->default(0); // For sorting periods in order
            $table->timestamps();
            
            $table->foreign('timetable_id')->references('id')->on('school_timetables')->onDelete('cascade');
            $table->unique(['timetable_id', 'period_order'], 'unique_period_order');
            $table->unique(['timetable_id', 'start_time', 'end_time'], 'unique_period_time');
        });

        // Timetable Schedule - contains the actual schedule entries (subject, teacher, location, etc.)
        Schema::create('timetable_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timetable_id');
            $table->unsignedBigInteger('period_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('teacher_id')->nullable();
            $table->enum('weekday', [
                'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
            ]);
            $table->string('classroom')->nullable(); // Location of the class
            $table->text('notes')->nullable(); // Additional information
            $table->boolean('is_recurring')->default(true); // Whether this is a recurring schedule or one-time
            $table->date('specific_date')->nullable(); // For non-recurring schedules
            $table->timestamps();
            
            $table->foreign('timetable_id')->references('id')->on('school_timetables')->onDelete('cascade');
            $table->foreign('period_id')->references('id')->on('timetable_periods')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
            
            // Ensure we don't have duplicate entries for the same time slot
            $table->unique([
                'timetable_id', 'period_id', 'weekday', 
                'specific_date'
            ], 'unique_timetable_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_schedules');
        Schema::dropIfExists('timetable_periods');
        Schema::dropIfExists('school_timetables');
    }
};
