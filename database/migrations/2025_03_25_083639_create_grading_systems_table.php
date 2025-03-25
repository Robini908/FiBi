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
        Schema::create('grading_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('class_type_id')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('academic_term')->nullable(); // Term to which this grading system applies
            $table->string('academic_year')->nullable(); // Year to which this grading system applies
            $table->string('created_by')->nullable(); // Who created this system
            $table->integer('pass_mark')->nullable(); // Minimum mark to pass
            $table->timestamp('effective_date')->nullable(); // Date when the grading system becomes effective
            $table->text('rules')->nullable(); // Rules associated with the grading system
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('class_type_id')
                  ->references('id')
                  ->on('class_types')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_systems');
    }
};
