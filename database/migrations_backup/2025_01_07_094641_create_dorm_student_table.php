<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dorm_student', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('dorm_id');
            $table->year('year'); // Track the year/session
            $table->timestamps();
            
            $table->foreign('student_id')
                  ->references('id')
                  ->on('student_records')
                  ->onDelete('cascade');
                  
            $table->foreign('dorm_id')
                  ->references('id')
                  ->on('dorms')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dorm_student');
    }
};
