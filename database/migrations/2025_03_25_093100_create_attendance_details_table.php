<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the attendance_details table for tracking individual student attendance status
     */
    public function up(): void
    {
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_record_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('student_id');
            $table->enum('status', ['present', 'absent', 'late', 'excused', 'sick', 'on_leave', 'other'])->default('present');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->integer('minutes_late')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('student_id')->references('id')->on('student_records')->onDelete('cascade');
            
            // Ensure unique student attendance for each attendance record
            $table->unique(['attendance_record_id', 'student_id'], 'unique_student_attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_details');
    }
}; 