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
    public function up()
    {
        // Since we've updated the original migration to use the correct column types,
        // we need to make sure we handle any existing data correctly.
        
        // First, check if the table exists
        if (Schema::hasTable('dorm_student')) {
            // Check if it has the old column structure 
            if (Schema::hasColumn('dorm_student', 'student_record_id')) {
                // Create a new table with the correct structure
                Schema::create('dorm_student_new', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('student_id')->constrained('student_records')->onDelete('cascade');
                    $table->foreignId('dorm_id')->constrained('dorms')->onDelete('cascade');
                    $table->year('year');
                    $table->timestamps();
                });
                
                // Copy data if needed, converting IDs as necessary
                DB::statement("INSERT INTO dorm_student_new (student_id, dorm_id, year, created_at, updated_at)
                              SELECT student_record_id, dorm_id, year, created_at, updated_at 
                              FROM dorm_student");
                
                // Drop the old table
                Schema::drop('dorm_student');
                
                // Rename the new table to the original name
                Schema::rename('dorm_student_new', 'dorm_student');
            } else {
                // If it has the correct column names but possibly wrong types
                // Drop foreign keys if they exist
                Schema::table('dorm_student', function (Blueprint $table) {
                    if ($this->foreignKeyExists('dorm_student', 'dorm_student_student_id_foreign')) {
                        $table->dropForeign(['student_id']);
                    }
                    
                    if ($this->foreignKeyExists('dorm_student', 'dorm_student_dorm_id_foreign')) {
                        $table->dropForeign(['dorm_id']);
                    }
                });
                
                // Recreate with correct types if needed
                Schema::table('dorm_student', function (Blueprint $table) {
                    // Check if columns need to be modified
                    if (!$this->isColumnForeignId('dorm_student', 'student_id')) {
                        $table->foreignId('student_id')->change();
                    }
                    
                    if (!$this->isColumnForeignId('dorm_student', 'dorm_id')) {
                        $table->foreignId('dorm_id')->change();
                    }
                    
                    // Add foreign key constraints
                    $table->foreign('student_id')->references('id')->on('student_records')->onDelete('cascade');
                    $table->foreign('dorm_id')->references('id')->on('dorms')->onDelete('cascade');
                });
            }
        }
    }
    
    public function down()
    {
        // Since we're fixing the column types, we may not need a specific down migration
        // But we'll keep it for consistency
        Schema::table('dorm_student', function (Blueprint $table) {
            if ($this->foreignKeyExists('dorm_student', 'dorm_student_student_id_foreign')) {
                $table->dropForeign(['student_id']);
            }
            
            if ($this->foreignKeyExists('dorm_student', 'dorm_student_dorm_id_foreign')) {
                $table->dropForeign(['dorm_id']);
            }
        });
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
    
    /**
     * Check if a column is of type foreignId (bigint unsigned)
     * 
     * @param string $table
     * @param string $column
     * @return bool
     */
    private function isColumnForeignId($table, $column)
    {
        $columnInfo = DB::select("
            SELECT DATA_TYPE, COLUMN_TYPE
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '{$table}'
            AND COLUMN_NAME = '{$column}'
        ");
        
        if (count($columnInfo) === 0) {
            return false;
        }
        
        // Check if the column is a bigint unsigned which is what foreignId uses
        return $columnInfo[0]->DATA_TYPE === 'bigint' && 
               strpos($columnInfo[0]->COLUMN_TYPE, 'unsigned') !== false;
    }
};
