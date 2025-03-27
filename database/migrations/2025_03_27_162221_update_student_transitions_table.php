<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration updates the existing student_transitions table to use the improved schema
     * for better tracking of student class movements throughout academic years.
     */
    public function up(): void
    {
        // First, check if the student_transitions table exists
        if (Schema::hasTable('student_transitions')) {
            // Backup existing data if there's any
            $existingTransitions = DB::table('student_transitions')->get();
            
            // Drop the existing table - we're doing a complete rebuild due to significant schema changes
            Schema::dropIfExists('student_transitions');
        }
        
        // Create the table with the improved schema
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
            $table->index(['student_id', 'from_academic_year', 'to_academic_year'], 'st_academic_years_idx');
            $table->index(['student_id', 'is_active'], 'st_active_idx');
            $table->index(['from_class_id', 'to_class_id'], 'st_class_transition_idx');
            
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
        
        // If we had existing data, migrate it to the new schema
        if (isset($existingTransitions) && count($existingTransitions) > 0) {
            foreach ($existingTransitions as $oldTransition) {
                DB::table('student_transitions')->insert([
                    'student_id' => $oldTransition->student_id,
                    'from_academic_year' => $oldTransition->transition_year ?? now()->year,
                    'to_academic_year' => $oldTransition->transition_type === 'repetition' 
                                      ? ($oldTransition->transition_year ?? now()->year) 
                                      : ($oldTransition->transition_year ?? now()->year) + 1,
                    'from_class_id' => DB::table('student_records')->where('id', $oldTransition->student_id)->value('my_class_id') ?? 1,
                    'from_section_id' => DB::table('student_records')->where('id', $oldTransition->student_id)->value('section_id'),
                    'to_class_id' => $oldTransition->target_class_id ?? 1,
                    'to_section_id' => $oldTransition->target_section_id,
                    'transition_type' => $oldTransition->transition_type,
                    'is_active' => true,
                    'reason' => $oldTransition->reason,
                    'created_by' => $oldTransition->decision_by ?? 1,
                    'effective_date' => $oldTransition->decision_date ?? now(),
                    'created_at' => $oldTransition->created_at ?? now(),
                    'updated_at' => $oldTransition->updated_at ?? now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the improved table
        Schema::dropIfExists('student_transitions');
        
        // Re-create the original table schema - this is not a perfect reversal
        // but at least provides a way back to the original structure if needed
        Schema::create('student_transitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->year('transition_year');
            $table->enum('transition_type', ['promotion', 'demotion', 'repetition', 'graduation']);
            $table->unsignedInteger('target_class_id')->nullable();
            $table->unsignedInteger('target_section_id')->nullable();
            $table->text('reason')->nullable();
            $table->unsignedInteger('decision_by');
            $table->dateTime('decision_date');
            $table->timestamps();
            
            $table->foreign('student_id')
                ->references('id')
                ->on('student_records')
                ->onDelete('cascade');
            
            $table->foreign('target_class_id')
                ->references('id')
                ->on('my_classes')
                ->onDelete('set null');
                
            $table->foreign('target_section_id')
                ->references('id')
                ->on('sections')
                ->onDelete('set null');
                
            $table->foreign('decision_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
