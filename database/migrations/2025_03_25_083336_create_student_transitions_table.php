<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates a robust student_transitions table to track all movements
     * of students between classes throughout their academic journey.
     */
    public function up(): void
    {
        Schema::create('student_transitions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedInteger('student_id'); // Links to the student
            
            // Academic period information
            $table->year('from_academic_year'); // Starting academic year (e.g., 2023)
            $table->year('to_academic_year'); // Target academic year (e.g., 2024)
            $table->string('academic_period', 50)->nullable(); // Term/Semester identifier
            
            // Class information
            $table->unsignedInteger('from_class_id'); // Original class
            $table->unsignedInteger('from_section_id')->nullable(); // Original section 
            $table->unsignedInteger('to_class_id'); // Target class
            $table->unsignedInteger('to_section_id')->nullable(); // Target section
            
            // Transition details
            $table->enum('transition_type', ['promotion', 'demotion', 'repetition', 'graduation', 'transfer']); 
            $table->boolean('is_active')->default(true); // Whether this transition is current/active
            $table->text('reason')->nullable(); // Reason for the transition
            
            // Tracking information
            $table->unsignedInteger('created_by'); // User who created the transition
            $table->dateTime('effective_date'); // When this transition takes effect
            $table->timestamps(); // created_at and updated_at
            
            // Add indexes for better query performance
            $table->index(['student_id', 'from_academic_year', 'to_academic_year']);
            $table->index(['student_id', 'is_active']);
            $table->index(['from_class_id', 'to_class_id']);
            
            // Foreign key constraints
            $table->foreign('student_id')
                ->references('id')
                ->on('student_records')
                ->onDelete('cascade');
                
            $table->foreign('from_class_id')
                ->references('id')
                ->on('my_classes')
                ->onDelete('restrict');
                
            $table->foreign('to_class_id')
                ->references('id')
                ->on('my_classes')
                ->onDelete('restrict');
                
            $table->foreign('from_section_id')
                ->references('id')
                ->on('sections')
                ->onDelete('set null');
                
            $table->foreign('to_section_id')
                ->references('id')
                ->on('sections')
                ->onDelete('set null');
                
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_transitions');
    }
};
