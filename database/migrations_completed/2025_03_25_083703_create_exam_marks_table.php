<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedInteger('student_id'); // Matches student_records
            $table->unsignedInteger('exam_id'); // Matches exams
            $table->unsignedInteger('subject_id'); // Matches subjects
            $table->unsignedBigInteger('grading_range_id')->nullable(); // Matches grading_ranges
            $table->float('marks')->nullable();
            $table->string('special_grade', 1)->nullable(); // For special grades like 'X', 'Z', etc.
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
                  
            $table->foreign('subject_id')
                  ->references('id')
                  ->on('subjects')
                  ->onDelete('cascade');
                  
            $table->foreign('grading_range_id')
                  ->references('id')
                  ->on('grading_ranges')
                  ->onDelete('set null');
                  
            // Unique constraint to prevent duplicate marks entries
            $table->unique(['student_id', 'exam_id', 'subject_id'], 'exam_marks_student_id_exam_id_subject_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_marks');
    }
};
