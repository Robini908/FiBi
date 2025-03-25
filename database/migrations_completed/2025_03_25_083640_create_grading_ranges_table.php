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
        Schema::create('grading_ranges', function (Blueprint $table) {
            $table->id();
            $table->integer('range_from');
            $table->integer('range_to');
            $table->string('grade');
            $table->unsignedBigInteger('grading_system_id');
            $table->unsignedInteger('subject_id')->nullable(); // For subject-specific grading
            $table->string('remark')->nullable(); // Description of the grade (e.g., Excellent, Good)
            $table->string('gpa')->nullable(); // Grade point equivalent
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('grading_system_id')
                  ->references('id')
                  ->on('grading_systems')
                  ->onDelete('cascade');
                  
            $table->foreign('subject_id')
                  ->references('id')
                  ->on('subjects')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_ranges');
    }
};
