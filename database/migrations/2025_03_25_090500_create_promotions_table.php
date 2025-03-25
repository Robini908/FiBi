<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the promotions table for tracking student grade progressions
     */
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('from_class');
            $table->unsignedInteger('from_section')->nullable();
            $table->unsignedInteger('to_class');
            $table->unsignedInteger('to_section')->nullable();
            $table->string('academic_year_from'); // e.g., '2023-2024'
            $table->string('academic_year_to'); // e.g., '2024-2025'
            $table->string('status'); // 'promoted', 'not promoted', 'graduated', etc.
            $table->unsignedInteger('promoted_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('student_id')
                ->references('id')
                ->on('student_records')
                ->onDelete('cascade');
                
            $table->foreign('from_class')
                ->references('id')
                ->on('my_classes')
                ->onDelete('cascade');
                
            $table->foreign('from_section')
                ->references('id')
                ->on('sections')
                ->onDelete('set null');
                
            $table->foreign('to_class')
                ->references('id')
                ->on('my_classes')
                ->onDelete('cascade');
                
            $table->foreign('to_section')
                ->references('id')
                ->on('sections')
                ->onDelete('set null');
                
            $table->foreign('promoted_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
}; 