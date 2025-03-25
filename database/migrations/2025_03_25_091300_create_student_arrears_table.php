<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the student_arrears table for tracking student fee balances
     */
    public function up(): void
    {
        Schema::create('student_arrears', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->decimal('amount', 15, 2);
            $table->integer('academic_year');
            $table->tinyInteger('term');
            $table->text('description')->nullable();
            $table->boolean('is_cleared')->default(false);
            $table->date('cleared_date')->nullable();
            $table->unsignedInteger('cleared_by')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('student_id')
                ->references('id')
                ->on('student_records')
                ->onDelete('cascade');
                
            $table->foreign('cleared_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
                
            $table->foreign('created_by')
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
        Schema::dropIfExists('student_arrears');
    }
}; 