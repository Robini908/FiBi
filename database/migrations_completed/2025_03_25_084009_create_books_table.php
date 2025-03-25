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
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->unsignedInteger('my_class_id')->nullable(); // Class for which this book is intended
            $table->string('description')->nullable();
            $table->string('author')->nullable();
            $table->string('book_type')->nullable(); // e.g., Textbook, Novel, Reference
            $table->string('url')->nullable(); // URL for digital copy if available
            $table->string('location')->nullable(); // Physical location in the library
            $table->integer('total_copies')->nullable(); // Total number of copies available
            $table->integer('issued_copies')->nullable(); // Number of copies currently issued
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('my_class_id')
                  ->references('id')
                  ->on('my_classes')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
