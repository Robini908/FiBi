<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates the student_records table with all columns
     * consolidated from multiple migrations.
     */
    public function up(): void
    {
        Schema::create('student_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('parent_id_no')->nullable(); // Foreign key column for parent
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('section_id');
            $table->string('adm_no', 30)->unique()->nullable();           
            $table->unsignedInteger('dorm_id')->nullable();
            $table->string('year_admitted')->nullable();
            $table->integer('kcpe')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('gender');
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('nationality')->nullable();
            $table->string('state')->nullable();
            $table->string('town')->nullable();
            $table->string('upi_number')->unique()->nullable();
            $table->unsignedInteger('bg_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('unverified');
            $table->text('disapproval_reason')->nullable();
            $table->string('student_password')->nullable();
            
            // Suspension fields (renamed from expulsion fields)
            $table->boolean('is_suspended')->default(false);
            $table->text('suspension_reason')->nullable();
            $table->unsignedInteger('suspended_by')->nullable();
            $table->text('notification_content')->nullable();
            $table->timestamp('suspension_date')->nullable();
            $table->enum('suspension_type', ['dismissal', 'withdrawal', 'permanent_exclusion'])->nullable();
            $table->timestamp('suspension_end_date')->nullable();
            
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('parent_id_no')
                ->references('parent_id_no')
                ->on('parent_details')
                ->onDelete('set null');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
                
            $table->foreign('my_class_id')
                ->references('id')
                ->on('my_classes')
                ->onDelete('restrict');
                
            $table->foreign('section_id')
                ->references('id')
                ->on('sections')
                ->onDelete('restrict');
                
            $table->foreign('dorm_id')
                ->references('id')
                ->on('dorms')
                ->onDelete('set null');
                
            $table->foreign('bg_id')
                ->references('id')
                ->on('blood_groups')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_records');
    }
};
