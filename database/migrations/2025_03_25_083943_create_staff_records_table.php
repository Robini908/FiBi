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
        Schema::create('staff_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('code', 100)->nullable()->unique();
            $table->string('emp_date')->nullable(); // Employment date
            $table->string('qualification')->nullable(); // Highest qualification
            $table->text('experience')->nullable(); // Previous experience
            $table->string('departments')->nullable(); // Departments the staff is associated with
            $table->boolean('is_active')->default(true); // Whether the staff is currently active
            $table->timestamps();
            
            // Foreign key constraints
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
        Schema::dropIfExists('staff_records');
    }
};
