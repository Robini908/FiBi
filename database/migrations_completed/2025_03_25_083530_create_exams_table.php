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
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('term'); // First, Second, or Third term
            $table->string('year'); // Academic year
            $table->unsignedInteger('grading_system_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(false);
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->timestamps();
            
            // Create a non-unique index instead of a unique one for term and year
            $table->index(['term', 'year'], 'exams_term_year_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
