<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the sections table with all necessary columns
     * consolidated from multiple migrations.
     */
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('teacher_id')->nullable();
            $table->string('session_year')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['name', 'my_class_id']);
            
            // Foreign key constraints
            $table->foreign('my_class_id')
                ->references('id')
                ->on('my_classes')
                ->onDelete('cascade');
                
            $table->foreign('teacher_id')
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
        Schema::dropIfExists('sections');
    }
};
