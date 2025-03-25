<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the staff_attendance_records table for tracking staff attendance
     */
    public function up(): void
    {
        Schema::create('staff_attendance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('staff_id');
            $table->date('attendance_date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'on_leave', 'official_duty'])->default('present');
            $table->string('leave_type')->nullable(); // e.g., sick, casual, annual, maternity, etc.
            $table->text('remarks')->nullable();
            $table->unsignedInteger('marked_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('staff_id')->references('id')->on('staff_records')->onDelete('cascade');
            $table->foreign('marked_by')->references('id')->on('users')->onDelete('set null');
            
            // Ensure unique staff attendance for each date
            $table->unique(['staff_id', 'attendance_date'], 'unique_staff_attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendance_records');
    }
}; 