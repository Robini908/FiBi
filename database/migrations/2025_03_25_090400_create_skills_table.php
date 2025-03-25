<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the skills table for tracking student competencies
     */
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('skill_type'); // e.g., 'Academic', 'Co-curricular', 'Social', etc.
            $table->string('class_type')->nullable(); // Optional class type association
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Create the pivot table for student skills
        Schema::create('skill_student', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('skill_id');
            $table->unsignedInteger('student_id');
            $table->integer('rating')->nullable(); // Optional rating (1-5, etc.)
            $table->text('remarks')->nullable();
            $table->unsignedInteger('teacher_id')->nullable(); // Teacher who evaluated the skill
            $table->date('evaluation_date')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('skill_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');
                
            $table->foreign('student_id')
                ->references('id')
                ->on('student_records')
                ->onDelete('cascade');
                
            $table->foreign('teacher_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
                
            // Ensure a student doesn't have duplicate skills
            $table->unique(['skill_id', 'student_id'], 'unique_student_skill');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_student');
        Schema::dropIfExists('skills');
    }
}; 