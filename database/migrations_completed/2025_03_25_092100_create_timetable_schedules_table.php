<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the timetable_schedules table for scheduling classes in a timetable
     */
    public function up(): void
    {
        Schema::create('timetable_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timetable_id')->constrained('school_timetables')->onDelete('cascade');
            $table->foreignId('period_id')->constrained('timetable_periods')->onDelete('cascade');
            $table->unsignedInteger('subject_id')->nullable();
            $table->unsignedInteger('teacher_id')->nullable();
            $table->enum('weekday', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->string('classroom')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
            
            // Create a unique constraint to prevent duplicate schedules
            $table->unique(['timetable_id', 'period_id', 'weekday'], 'unique_timetable_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_schedules');
    }
}; 