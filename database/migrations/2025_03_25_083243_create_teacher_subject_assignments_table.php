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
        Schema::create('teacher_subject_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('teacher_id'); // Teacher (User) ID
            $table->unsignedInteger('subject_id'); // Subject ID
            $table->unsignedInteger('class_id'); // Class ID
            $table->unsignedInteger('section_id')->nullable(); // Section ID (nullable for class-wide assignments)
            $table->string('academic_year_id', 20)->nullable(); // Academic year using string format
            $table->string('academic_term')->nullable(); // Academic term (First, Second, Third)
            $table->boolean('is_primary')->default(true); // Whether this is the primary teacher for this subject
            $table->boolean('is_active')->default(true); // Whether this assignment is active
            $table->text('notes')->nullable(); // Additional notes or details
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate assignments
            $table->unique(['teacher_id', 'subject_id', 'class_id', 'section_id', 'academic_year_id', 'academic_term'], 'unique_teacher_subject_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subject_assignments');
    }
};
