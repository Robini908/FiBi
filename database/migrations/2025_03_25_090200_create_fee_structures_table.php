<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the fee_structures table for managing school fees
     */
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('my_class_id'); // Using unsignedInteger to match my_classes.id type
            $table->integer('academic_year');
            $table->integer('term');
            $table->string('name');
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->boolean('is_mandatory')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('my_class_id')
                ->references('id')
                ->on('my_classes')
                ->cascadeOnDelete();
                
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            // Unique constraint for fee name within a class, academic year and term
            $table->unique(['my_class_id', 'name', 'academic_year', 'term'], 'unique_fee_per_class_year_term');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
}; 