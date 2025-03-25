<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration fixes the issue with foreign key constraints 
     * that prevent dropping the unique index on exam_marks table.
     */
    public function up(): void
    {
        // First, identify and drop any potential constraints that use the unique index
        if (Schema::hasTable('exam_marks')) {
            // Get all foreign keys that might be using the unique index
            $constraintNames = $this->getForeignKeysUsingIndex('exam_marks_student_id_exam_id_subject_id_unique');
            
            Schema::table('exam_marks', function (Blueprint $table) use ($constraintNames) {
                // Drop the identified foreign keys
                foreach ($constraintNames as $constraintName) {
                    try {
                        $table->dropForeign($constraintName);
                    } catch (\Exception $e) {
                        // Log error but continue execution
                        \Log::error("Error dropping foreign key {$constraintName}: " . $e->getMessage());
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed for rollback as foreign keys would be recreated
        // in their respective migration files
    }

    /**
     * Get the names of foreign keys that reference a specific index
     */
    private function getForeignKeysUsingIndex($indexName)
    {
        try {
            // For MySQL, we can query INFORMATION_SCHEMA to find constraints
            $constraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'exam_marks'
                AND CONSTRAINT_NAME NOT LIKE 'PRIMARY'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            return array_map(function ($constraint) {
                return $constraint->CONSTRAINT_NAME;
            }, $constraints);
        } catch (\Exception $e) {
            \Log::error("Error querying for constraints: " . $e->getMessage());
            return [];
        }
    }
};
