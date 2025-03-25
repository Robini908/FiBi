<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the fee_allocations table for assigning fees to students
     */
    public function up(): void
    {
        Schema::create('fee_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->unsignedBigInteger('fee_structure_id');
            $table->decimal('amount', 15, 2)->nullable(); // Can override the standard fee amount
            $table->integer('academic_year');
            $table->tinyInteger('term');
            $table->boolean('is_mandatory')->default(true);
            $table->string('status')->default('active'); // active, waived, etc.
            $table->text('notes')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('student_id')
                ->references('id')
                ->on('student_records')
                ->onDelete('cascade');
                
            $table->foreign('fee_structure_id')
                ->references('id')
                ->on('fee_structures')
                ->onDelete('cascade');
                
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
                
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
                
            // Ensure each fee is allocated only once per student per term
            $table->unique(['student_id', 'fee_structure_id', 'academic_year', 'term'], 'unique_student_fee_allocation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_allocations');
    }
}; 