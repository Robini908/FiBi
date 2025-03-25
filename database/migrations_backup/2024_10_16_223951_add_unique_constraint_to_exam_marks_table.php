<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddUniqueConstraintToExamMarksTable extends Migration
{
    public function up()
    {
        Schema::table('exam_marks', function (Blueprint $table) {
            // Add unique constraint to student_id, exam_id, and subject_id
            $table->unique(['student_id', 'exam_id', 'subject_id']);
        });
    }

    public function down()
    {
        // First check if index exists before trying to drop it
        if ($this->indexExists('exam_marks', 'exam_marks_student_id_exam_id_subject_id_unique')) {
            // Use DB::statement for more direct control over the SQL
            try {
                DB::statement('ALTER TABLE exam_marks DROP INDEX exam_marks_student_id_exam_id_subject_id_unique');
            } catch (\Exception $e) {
                // Log the error but don't fail the migration
                \Log::error("Error dropping unique index: " . $e->getMessage());
            }
        }
    }

    /**
     * Check if an index exists on a table
     *
     * @param string $table The table name
     * @param string $index The index name
     * @return bool
     */
    private function indexExists($table, $index)
    {
        try {
            $indexes = DB::select(
                "SHOW INDEXES FROM {$table} WHERE Key_name = '{$index}'"
            );
            return count($indexes) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}
