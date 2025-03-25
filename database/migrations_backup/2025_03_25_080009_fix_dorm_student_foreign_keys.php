<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the table exists
        if (Schema::hasTable('dorm_student')) {
            // Drop any existing foreign keys
            Schema::table('dorm_student', function (Blueprint $table) {
                if ($this->foreignKeyExists('dorm_student', 'dorm_student_student_id_foreign')) {
                    $table->dropForeign(['student_id']);
                }
                
                if ($this->foreignKeyExists('dorm_student', 'dorm_student_dorm_id_foreign')) {
                    $table->dropForeign(['dorm_id']);
                }
            });
            
            // Add the foreign key constraints - keeping the existing column types
            Schema::table('dorm_student', function (Blueprint $table) {
                // Add foreign key constraints using the correct types
                // Note: We're using the existing column types (unsignedInteger) to match the parent tables
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign keys if they exist
        if (Schema::hasTable('dorm_student')) {
            Schema::table('dorm_student', function (Blueprint $table) {
                if ($this->foreignKeyExists('dorm_student', 'dorm_student_student_id_foreign')) {
                    $table->dropForeign(['student_id']);
                }
                
                if ($this->foreignKeyExists('dorm_student', 'dorm_student_dorm_id_foreign')) {
                    $table->dropForeign(['dorm_id']);
                }
            });
        }
    }
    
    /**
     * Check if a foreign key exists on a table
     * 
     * @param string $table
     * @param string $foreignKey
     * @return bool
     */
    private function foreignKeyExists($table, $foreignKey)
    {
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '{$table}'
            AND CONSTRAINT_NAME = '{$foreignKey}'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");
        
        return count($constraints) > 0;
    }
};
