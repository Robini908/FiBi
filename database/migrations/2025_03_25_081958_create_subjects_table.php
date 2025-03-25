<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the subjects table with all columns
     * consolidated from multiple migrations.
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject_name', 100);
            $table->string('subject_code', 100);
            $table->string('abbreviation', 100);
            $table->enum('type', ['compulsory', 'elective'])->default('elective');
            $table->foreignId('category_id')->constrained('subject_categories')->onDelete('cascade');
            $table->unsignedInteger('prerequisite_id')->nullable();
            $table->timestamps();
            
            // Self-referencing foreign key constraint
            $table->foreign('prerequisite_id')
                ->references('id')
                ->on('subjects')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
