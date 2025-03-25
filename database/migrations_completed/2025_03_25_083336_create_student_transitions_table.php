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
        Schema::create('student_transitions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedInteger('student_id'); // Links to the student
            $table->year('transition_year'); // Academic year of the transition
            $table->enum('transition_type', ['promotion', 'demotion', 'repetition', 'graduation']); // Type of transition
            $table->unsignedInteger('target_class_id')->nullable(); // Target class (if applicable)
            $table->unsignedInteger('target_section_id')->nullable(); // Target section (if applicable)
            $table->text('reason')->nullable(); // Reason for the transition
            $table->unsignedInteger('decision_by'); // User who made the decision
            $table->dateTime('decision_date'); // Date and time of the decision
            $table->timestamps(); // created_at and updated_at
            
            // Foreign key constraints
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_transitions');
    }
};
