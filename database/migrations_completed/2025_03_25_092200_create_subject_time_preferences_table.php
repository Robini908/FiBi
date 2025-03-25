<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the subject_time_preferences table for specifying preferred times for subjects
     */
    public function up(): void
    {
        Schema::create('subject_time_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('class_id')->nullable();
            $table->string('time_preference'); // morning, midday, afternoon
            $table->integer('preference_level')->default(0); // 0-10 with 10 being highest
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            // Create a unique constraint
            $table->unique(['subject_id', 'class_id', 'time_preference'], 'unique_subject_time_preference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_time_preferences');
    }
}; 