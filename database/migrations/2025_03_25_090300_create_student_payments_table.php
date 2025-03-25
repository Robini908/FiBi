<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the student_payments table for tracking fee payments
     */
    public function up(): void
    {
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->unsignedBigInteger('fee_structure_id')->nullable();
            $table->string('payment_type'); // e.g., 'Tuition', 'Library', 'Transportation', etc.
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('ref_no')->nullable(); // Payment reference number
            $table->string('payment_method'); // e.g., 'Cash', 'Bank Transfer', 'Mobile Money', etc.
            $table->string('academic_year');
            $table->integer('term');
            $table->string('description')->nullable();
            $table->string('status')->default('completed'); // 'completed', 'pending', 'failed', etc.
            $table->unsignedInteger('recorded_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('student_id')
                ->references('id')
                ->on('student_records')
                ->onDelete('cascade');
                
            $table->foreign('fee_structure_id')
                ->references('id')
                ->on('fee_structures')
                ->onDelete('set null');
                
            $table->foreign('recorded_by')
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
        Schema::dropIfExists('student_payments');
    }
}; 