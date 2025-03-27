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
        Schema::table('student_arrears', function (Blueprint $table) {
            // Add class_id column after student_id if it doesn't exist
            if (!Schema::hasColumn('student_arrears', 'class_id')) {
                $table->unsignedInteger('class_id')->after('student_id');
                
                // Add foreign key relationship
                $table->foreign('class_id')
                    ->references('id')
                    ->on('my_classes')
                    ->cascadeOnDelete();
            }
            
            // Rename academic_year to previous_year if it exists and previous_year doesn't
            if (Schema::hasColumn('student_arrears', 'academic_year') && !Schema::hasColumn('student_arrears', 'previous_year')) {
                $table->renameColumn('academic_year', 'previous_year');
            }
            
            // Rename term to previous_term if it exists and previous_term doesn't
            if (Schema::hasColumn('student_arrears', 'term') && !Schema::hasColumn('student_arrears', 'previous_term')) {
                $table->renameColumn('term', 'previous_term');
            }
            
            // Rename cleared_date to cleared_at if it exists and cleared_at doesn't
            if (Schema::hasColumn('student_arrears', 'cleared_date') && !Schema::hasColumn('student_arrears', 'cleared_at')) {
                $table->renameColumn('cleared_date', 'cleared_at');
            }
            
            // Add updated_by column if it doesn't exist
            if (!Schema::hasColumn('student_arrears', 'updated_by')) {
                $table->unsignedInteger('updated_by')->nullable()->after('created_by');
                
                // Add foreign key relationship
                $table->foreign('updated_by')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_arrears', function (Blueprint $table) {
            // Drop foreign key for class_id if it exists
            if (Schema::hasColumn('student_arrears', 'class_id')) {
                $table->dropForeign(['class_id']);
                $table->dropColumn('class_id');
            }
            
            // Rename previous_year back to academic_year if it exists
            if (Schema::hasColumn('student_arrears', 'previous_year') && !Schema::hasColumn('student_arrears', 'academic_year')) {
                $table->renameColumn('previous_year', 'academic_year');
            }
            
            // Rename previous_term back to term if it exists
            if (Schema::hasColumn('student_arrears', 'previous_term') && !Schema::hasColumn('student_arrears', 'term')) {
                $table->renameColumn('previous_term', 'term');
            }
            
            // Rename cleared_at back to cleared_date if it exists
            if (Schema::hasColumn('student_arrears', 'cleared_at') && !Schema::hasColumn('student_arrears', 'cleared_date')) {
                $table->renameColumn('cleared_at', 'cleared_date');
            }
            
            // Drop updated_by column if it exists
            if (Schema::hasColumn('student_arrears', 'updated_by')) {
                $table->dropForeign(['updated_by']);
                $table->dropColumn('updated_by');
            }
        });
    }
};
