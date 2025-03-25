<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the book_categories table for organizing library books
     */
    public function up(): void
    {
        Schema::create('book_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('color_code', 10)->nullable(); // For color-coding in the UI
            $table->integer('max_borrow_days')->default(14); // Default borrowing period in days
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
        
        // Add category_id to books table
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id');
            
            // Add foreign key constraint
            $table->foreign('category_id')
                  ->references('id')
                  ->on('book_categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove category_id from books table
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
        
        Schema::dropIfExists('book_categories');
    }
}; 