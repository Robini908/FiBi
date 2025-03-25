<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This pivot table links exams to classes and sections, defining which classes
     * and sections are included in a particular exam.
     */
    public function up(): void
    {
        Schema::create('exam_class_section', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('section_id')->nullable();
            $table->date('exam_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('exam_id')
                  ->references('id')
                  ->on('exams')
                  ->onDelete('cascade');
                  
            $table->foreign('class_id')
                  ->references('id')
                  ->on('my_classes')
                  ->onDelete('cascade');
                  
            $table->foreign('section_id')
                  ->references('id')
                  ->on('sections')
                  ->onDelete('set null');

            // Unique constraint to prevent duplicate entries
            $table->unique(['exam_id', 'class_id', 'section_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_class_section');
    }
};
