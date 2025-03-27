<?php

namespace App\Console\Commands;

use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\StudentTransition;
use App\User;
use Illuminate\Console\Command;

class CreateTestTransition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-test-transition';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test student transition record to verify the model works';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating a test student transition...');
        
        // Get a student record
        $student = StudentRecord::first();
        
        if (!$student) {
            $this->error('No student records found in the database');
            return 1;
        }
        
        $this->info("Found student: {$student->first_name} {$student->last_name} (ID: {$student->id})");
        
        // Get the first user for created_by
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found in the database');
            return 1;
        }
        
        // Get classes
        $fromClass = MyClass::find($student->my_class_id);
        if (!$fromClass) {
            $fromClass = MyClass::first();
            
            if (!$fromClass) {
                $this->error('No classes found in the database');
                return 1;
            }
        }
        
        // Get sections
        $fromSection = Section::find($student->section_id);
        if (!$fromSection) {
            $fromSection = Section::first();
            
            if (!$fromSection) {
                $this->error('No sections found in the database');
                return 1;
            }
        }
        
        // For to_class, try to find a class with higher ID, or just use the same class
        $toClass = MyClass::where('id', '>', $fromClass->id)->first();
        if (!$toClass) {
            $toClass = $fromClass;
        }
        
        // Create transition data
        $transitionData = [
            'student_id' => $student->id,
            'from_academic_year' => now()->year,
            'to_academic_year' => now()->year + 1,
            'from_class_id' => $fromClass->id,
            'from_section_id' => $fromSection->id,
            'to_class_id' => $toClass->id,
            'to_section_id' => $fromSection->id,
            'transition_type' => 'promotion',
            'is_active' => true,
            'reason' => 'Test transition created by command',
            'created_by' => $user->id,
            'effective_date' => now(),
            'academic_period' => 'Term 1',
        ];
        
        $this->info('Creating student transition with the following data:');
        $this->table(
            ['Field', 'Value'],
            collect($transitionData)->map(function ($value, $key) {
                return [$key, is_object($value) ? get_class($value) : $value];
            })->toArray()
        );
        
        try {
            // Create the transition
            $transition = StudentTransition::create($transitionData);
            
            if ($transition) {
                $this->info("Transition created successfully with ID: {$transition->id}");
                
                // Verify the transition was saved correctly
                $savedTransition = StudentTransition::find($transition->id);
                if ($savedTransition) {
                    $this->info("Transition saved correctly in the database");
                    $this->info("Current active transitions for student {$student->id}: " . 
                        StudentTransition::where('student_id', $student->id)
                            ->where('is_active', true)
                            ->count()
                    );
                    
                    return 0;
                } else {
                    $this->error("Failed to retrieve the created transition");
                    return 1;
                }
            } else {
                $this->error("Failed to create transition");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("Error creating transition: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
