<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the book_inventories table for tracking individual book copies
     */
    public function up(): void
    {
        Schema::create('book_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('book_id');
            $table->string('barcode')->unique();
            $table->string('accession_number')->unique();
            $table->string('isbn')->nullable();
            $table->string('edition')->nullable();
            $table->string('publisher')->nullable();
            $table->string('publication_year')->nullable();
            $table->string('source')->nullable(); // Donation, Purchase, etc.
            $table->decimal('price', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->enum('condition', ['new', 'good', 'fair', 'poor', 'damaged', 'lost'])->default('good');
            $table->string('shelf_location')->nullable();
            $table->enum('status', ['available', 'issued', 'reserved', 'under_repair', 'lost', 'written_off'])->default('available');
            $table->date('status_changed_date')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('book_id')
                  ->references('id')
                  ->on('books')
                  ->onDelete('cascade');
                  
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
        
        // Create book inventory check table for auditing
        Schema::create('book_inventory_checks', function (Blueprint $table) {
            $table->id();
            $table->date('check_date');
            $table->string('check_type'); // Regular, Annual, etc.
            $table->unsignedInteger('conducted_by');
            $table->text('notes')->nullable();
            $table->integer('total_books_checked');
            $table->integer('books_found');
            $table->integer('books_missing');
            $table->integer('books_damaged');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('conducted_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
        
        // Create book inventory check details table
        Schema::create('book_inventory_check_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_check_id')->constrained('book_inventory_checks')->onDelete('cascade');
            $table->foreignId('book_inventory_id')->constrained('book_inventories')->onDelete('cascade');
            $table->enum('status', ['found', 'missing', 'damaged', 'wrong_location'])->default('found');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Ensure each book is recorded only once per inventory check
            $table->unique(['inventory_check_id', 'book_inventory_id'], 'unique_book_inventory_check');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_inventory_check_details');
        Schema::dropIfExists('book_inventory_checks');
        Schema::dropIfExists('book_inventories');
    }
}; 