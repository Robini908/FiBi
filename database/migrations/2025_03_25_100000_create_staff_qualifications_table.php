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
        Schema::create('staff_qualifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('staff_id');
            $table->string('qualification_name');
            $table->string('institution');
            $table->string('qualification_type'); // degree, diploma, certificate, etc.
            $table->string('field_of_study');
            $table->year('year_obtained');
            $table->string('document_url')->nullable(); // URL to uploaded certification document
            $table->boolean('is_verified')->default(false);
            $table->unsignedInteger('verified_by')->nullable();
            $table->timestamp('verification_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('staff_id')
                  ->references('id')
                  ->on('staff_records')
                  ->onDelete('cascade');
                  
            $table->foreign('verified_by')
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
        Schema::dropIfExists('staff_qualifications');
    }
}; 