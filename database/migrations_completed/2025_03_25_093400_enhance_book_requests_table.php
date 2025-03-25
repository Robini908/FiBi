<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration enhances the book_requests table with additional functionality
     */
    public function up(): void
    {
        Schema::table('book_requests', function (Blueprint $table) {
            // Add new columns
            $table->date('due_date')->nullable()->after('end_date');
            $table->boolean('is_overdue')->default(false)->after('returned');
            $table->integer('days_overdue')->default(0)->after('is_overdue');
            $table->decimal('fine_amount', 10, 2)->default(0)->after('days_overdue');
            $table->boolean('fine_paid')->default(false)->after('fine_amount');
            $table->date('fine_paid_date')->nullable()->after('fine_paid');
            $table->string('fine_receipt_number')->nullable()->after('fine_paid_date');
            $table->unsignedInteger('fine_received_by')->nullable()->after('fine_receipt_number');
            $table->boolean('is_renewed')->default(false)->after('fine_received_by');
            $table->integer('renewal_count')->default(0)->after('is_renewed');
            $table->date('last_renewal_date')->nullable()->after('renewal_count');
            
            // Add foreign key for fine_received_by
            $table->foreign('fine_received_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
        
        // Create a new table for book reservations
        Schema::create('book_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('book_id');
            $table->unsignedInteger('user_id');
            $table->date('reservation_date');
            $table->date('expiry_date');
            $table->enum('status', ['pending', 'available', 'claimed', 'expired', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('book_id')
                  ->references('id')
                  ->on('books')
                  ->onDelete('cascade');
                  
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop book_reservations table
        Schema::dropIfExists('book_reservations');
        
        // Remove added columns from book_requests
        Schema::table('book_requests', function (Blueprint $table) {
            $table->dropForeign(['fine_received_by']);
            $table->dropColumn([
                'due_date',
                'is_overdue',
                'days_overdue',
                'fine_amount',
                'fine_paid',
                'fine_paid_date',
                'fine_receipt_number',
                'fine_received_by',
                'is_renewed',
                'renewal_count',
                'last_renewal_date'
            ]);
        });
    }
}; 