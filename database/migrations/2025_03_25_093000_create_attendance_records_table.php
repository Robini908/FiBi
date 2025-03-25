<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the attendance_records table for tracking student attendance
     */
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->date('attendance_date');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('marked_by');
            $table->text('remarks')->nullable();
            $table->string('academic_year', 20);
            $table->tinyInteger('term');
            $table->enum('session_type', ['morning', 'afternoon', 'whole_day'])->default('whole_day');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
            $table->foreign('marked_by')->references('id')->on('users')->onDelete('cascade');
            
            // Ensure unique attendance record for a class, section, and date combination
            $table->unique(['attendance_date', 'class_id', 'section_id', 'session_type'], 'unique_attendance_record');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
}; 