<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the student_results table which stores
     * aggregated results for each student for a specific exam.
     */
    public function up(): void
    {
        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id'); // Link to student
            $table->unsignedInteger('exam_id'); // Link to exam
            $table->integer('total_marks')->default(0);
            $table->integer('total_points')->default(0);
            $table->float('mean_score', 5, 2)->default(0);
            $table->string('mean_grade')->nullable();
            $table->integer('position')->nullable(); // Position in the whole class/year
            $table->integer('stream_position')->nullable(); // Position in the stream/section
            $table->unsignedInteger('class_id')->nullable(); // Class ID
            $table->unsignedInteger('section_id')->nullable(); // Section ID
            $table->text('teacher_remarks')->nullable(); // Remarks by teacher
            $table->text('principal_remarks')->nullable(); // Remarks by principal
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('student_id')
                  ->references('id')
                  ->on('student_records')
                  ->onDelete('cascade');
                  
            $table->foreign('exam_id')
                  ->references('id')
                  ->on('exams')
                  ->onDelete('cascade');
                  
            $table->foreign('class_id')
                  ->references('id')
                  ->on('my_classes')
                  ->onDelete('set null');
                  
            $table->foreign('section_id')
                  ->references('id')
                  ->on('sections')
                  ->onDelete('set null');
                  
            // Ensure each student has only one result record per exam
            $table->unique(['student_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_results');
    }
};
