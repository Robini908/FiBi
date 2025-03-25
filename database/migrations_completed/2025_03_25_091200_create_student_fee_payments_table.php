<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the student_fee_payments table for tracking student payments
     */
    public function up(): void
    {
        Schema::create('student_fee_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->decimal('amount', 15, 2);
            $table->string('receipt_number')->unique();
            $table->date('payment_date');
            $table->integer('academic_year');
            $table->tinyInteger('term');
            $table->enum('payment_method', ['cash', 'cheque', 'bank_transfer', 'mpesa']);
            
            // Cheque details
            $table->string('cheque_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->date('cheque_date')->nullable();
            
            // Bank transfer details
            $table->string('bank_slip_number')->nullable();
            $table->string('bank_branch')->nullable();
            
            // Mobile money details
            $table->string('mpesa_transaction_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->dateTime('mpesa_transaction_time')->nullable();
            
            // Additional fields
            $table->text('notes')->nullable();
            $table->string('receipt_attachment')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->string('confirmed_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->text('confirmation_note')->nullable();
            
            // Cancellation fields
            $table->boolean('is_cancelled')->default(false);
            $table->string('cancelled_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Edit tracking
            $table->boolean('is_edited')->default(false);
            $table->string('edited_by')->nullable();
            $table->dateTime('edited_at')->nullable();
            $table->text('edit_reason')->nullable();
            
            // Foreign key
            $table->foreign('student_id')
                ->references('id')
                ->on('student_records')
                ->onDelete('cascade');
                
            $table->unsignedInteger('recorded_by')->nullable();
            $table->foreign('recorded_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_fee_payments');
    }
}; 