<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradingSystem;
use App\Models\ClassType;
use App\Models\Subject;
use Carbon\Carbon;
use Faker\Factory as Faker;

class GradingSystemSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); // Create a Faker instance

        // Retrieve all existing subjects and class types
        $subjects = Subject::all();
        $classTypes = ClassType::all();

        // Check if there are subjects to associate
        if ($subjects->isEmpty()) {
            $this->command->error("No subjects available to associate with grading systems.");
            return;
        }

        // Check if there are class types to associate
        if ($classTypes->isEmpty()) {
            $this->command->error("No class types available to associate with grading systems.");
            return;
        }

        $this->command->info("Creating grading systems...");

        $currentYear = Carbon::now()->year;
        $academicTerms = ['First Term', 'Second Term', 'Third Term'];

        // Generate grading systems for each class type
        foreach ($classTypes as $classType) {
            // Create one default grading system for each class type
            $defaultSystem = GradingSystem::create([
                'name' => $classType->name . ' Default Grading System',
                'description' => 'Default grading system for ' . $classType->name,
                'class_type_id' => $classType->id,
                'is_default' => true,
                'academic_term' => null, // Applies to all terms
                'academic_year' => $currentYear,
                'created_by' => 'System',
                'pass_mark' => 40,
                'effective_date' => Carbon::now(),
                'rules' => $this->generateGradingRules(),
            ]);

            // Create a few more grading systems per class type for different terms
            foreach ($academicTerms as $term) {
                GradingSystem::create([
                    'name' => $classType->name . ' ' . $term . ' Grading System',
                    'description' => 'Grading system for ' . $classType->name . ' during ' . $term,
                    'class_type_id' => $classType->id,
                    'is_default' => false,
                    'academic_term' => $term,
                    'academic_year' => $currentYear,
                    'created_by' => 'System',
                    'pass_mark' => $faker->numberBetween(35, 50),
                    'effective_date' => $faker->dateTimeBetween('-1 year', '+1 year'),
                    'rules' => $this->generateGradingRules(),
                ]);
            }
        }

        $this->command->info("Grading systems created successfully.");
    }

    // Function to generate random grading rules
    private function generateGradingRules()
    {
        // Create an array of grading rules
        $rules = [
            'Exam irregularity is punishable.',
            'Each student must complete all assessments.',
            'Grades will be finalized at the end of each term.',
            'Late submissions will incur penalties.',
            'No cheating is tolerated.',
            'Students must attend at least 80% of classes to qualify for final exams.',
            'Special considerations may be given for medical emergencies.',
            'All marks are rounded to the nearest integer.',
            'The highest achiever in each subject receives recognition.',
            'Continuous assessment tests contribute 30% to the final grade.',
        ];

        // Select 5 random rules
        $selectedRules = [];
        for ($i = 0; $i < 5; $i++) {
            $selectedRules[] = $rules[array_rand($rules)];
        }

        return implode(', ', $selectedRules);
    }
}
