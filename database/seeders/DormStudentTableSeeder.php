<?php

namespace Database\Seeders;

/**
 * DormStudentTableSeeder
 * 
 * This seeder populates the dorm_student pivot table which manages the many-to-many 
 * relationship between students and dormitories. It was created to support the updated
 * database structure where students can be assigned to dormitories for different academic years.
 */

use Illuminate\Database\Seeder;
use App\Models\StudentRecord;
use App\Models\Dorm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DormStudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if the dorm_student table exists
        if (!Schema::hasTable('dorm_student')) {
            $this->command->error('The dorm_student table does not exist. Please run the migrations first.');
            return;
        }
        
        // Get all student records and dorms
        $students = StudentRecord::all();
        $dorms = Dorm::all();
        
        if ($students->isEmpty() || $dorms->isEmpty()) {
            $this->command->error('No students or dormitories found to assign.');
            return;
        }
        
        $currentYear = Carbon::now()->year;
        $dormStudentRecords = [];
        
        $this->command->info('Assigning students to dormitories...');
        
        // Assign each student to a random dorm
        foreach ($students as $student) {
            // If the student already has a dorm_id, create a relationship in the pivot table
            if ($student->dorm_id) {
                $dormStudentRecords[] = [
                    'student_id' => $student->id,
                    'dorm_id' => $student->dorm_id,
                    'year' => $currentYear,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                // Assign random dorm if the student doesn't have one
                $randomDorm = $dorms->random();
                
                // Update the student record with the dorm_id for backwards compatibility
                $student->update(['dorm_id' => $randomDorm->id]);
                
                $dormStudentRecords[] = [
                    'student_id' => $student->id,
                    'dorm_id' => $randomDorm->id,
                    'year' => $currentYear,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Insert in batches to avoid memory issues
            if (count($dormStudentRecords) >= 100) {
                DB::table('dorm_student')->insert($dormStudentRecords);
                $dormStudentRecords = [];
            }
        }
        
        // Insert any remaining records
        if (!empty($dormStudentRecords)) {
            DB::table('dorm_student')->insert($dormStudentRecords);
        }
        
        $this->command->info('Dormitory student assignments completed successfully.');
    }
} 