<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the pivot table between grading systems and subjects.
     */
    public function up(): void
    {
        Schema::create('grading_system_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grading_system_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('subject_id');
            $table->text('additional_rules')->nullable();
            $table->boolean('override_parent_rules')->default(false);
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('subject_id')
                  ->references('id')
                  ->on('subjects')
                  ->onDelete('cascade');
                  
            // Unique constraint to prevent duplicate entries
            $table->unique(['grading_system_id', 'subject_id'], 'gs_subject_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_system_subject');
    }
};
