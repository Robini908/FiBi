<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the timetable_periods table for defining time slots in a timetable
     */
    public function up(): void
    {
        Schema::create('timetable_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timetable_id')->constrained('school_timetables')->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('period_name')->nullable();
            $table->integer('period_order')->default(0);
            $table->enum('period_type', ['lesson', 'break', 'lunch', 'activity', 'prep', 'weekend', 'other'])->default('lesson');
            $table->boolean('is_break')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Create a unique constraint to prevent duplicate periods
            $table->unique(['timetable_id', 'period_order'], 'unique_period_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_periods');
    }
}; 