<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\GradingSystem;
use App\Models\MyClass;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ExamSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); // Create a Faker instance

        // Get all available grading systems, classes, and sections
        $gradingSystems = GradingSystem::all();
        $classes = MyClass::all();
        $sections = Section::all();

        // Skip if any of these are empty
        if ($gradingSystems->isEmpty() || $classes->isEmpty()) {
            \Log::warning('ExamSeeder: Cannot seed exams without grading systems and classes');
            return;
        }

        // Define a list of Kenyan exam types
        $examTypes = [
            'Term Exams', 'Prediction Exams', 'District Exams', 
            'National Exams', 'Cluster Exams', 'KCSE Mock Exams', 
            'KCPE Mock Exams', 'Mid-Year Exams', 'End-Year Exams',
            'Continuous Assessment Tests (CATs)', 'Classroom Assessments',
            'Joint Examinations'
        ];

        // Define locations, districts, wards, and sublocations
        $locations = [
            'Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Eldoret', 
            'Thika', 'Nyeri', 'Meru', 'Machakos', 'Embu', 
            'Kakamega', 'Kitale', 'Garissa', 'Malindi', 'Homa Bay', 
            'Kajiado', 'Bomet', 'Bungoma', 'Lamu', 'Siaya', 
            'Narok', 'Uasin Gishu'
        ];

        $districts = [
            'Central Nairobi', 'Coast Region', 'Western Kenya', 
            'Rift Valley', 'Central Kenya', 'Eastern Region'
        ];

        $wards = [
            'Kilimani', 'Westlands', 'Ngara', 'Madaraka', 
            'Mombasa Central', 'Kisumu East', 'Nakuru West',
            'Eldoret North', 'Kakamega South', 'Thika Town'
        ];

        $sublocations = [
            'Lavington', 'Karen', 'Kilimani', 'Nyali',
            'Milimani', 'Shimanzi', 'Kisumu Central', 
            'Eldoret Town', 'Nakuru East', 'Thika East'
        ];

        // Define different formats for exam names
        $examNameFormats = [
            "{examType} - {location} ({district}) - {year}",
            "{examType} - {year} - {location}",
            "{examType} ({district}) - {location} - {ward} - {year}",
            "{location} - {examType} - {sublocation} - {year}",
            "{district} - {examType} - {location} - {ward}",
            "{examType} - {location} - {year} - {ward} - {sublocation}",
            "{year} {examType} - {location} - {district}",
            "{examType} {year} ({district}) - {sublocation}",
            "{examType} - {ward} - {location} - {year}",
            "{location} - {examType} ({district}) - {sublocation}",
        ];

        // Generate 30 exam records (reduced for faster seeding)
        for ($i = 0; $i < 30; $i++) {
            try {
                // Start a transaction
                DB::beginTransaction();
                
                // Randomly select grading system
                $gradingSystem = $gradingSystems->random();
                $class = $classes->random();
                
                // Get sections for this class
                $classSections = $sections->where('my_class_id', $class->id);
                
                // Skip if no sections for this class
                if ($classSections->isEmpty()) {
                    DB::rollBack();
                    continue;
                }
                
                // Generate logical name for exam
                $examType = $examTypes[array_rand($examTypes)]; // Random exam type
                $location = $locations[array_rand($locations)]; // Random location
                $district = $districts[array_rand($districts)]; // Random district
                $ward = $wards[array_rand($wards)]; // Random ward
                $sublocation = $sublocations[array_rand($sublocations)]; // Random sublocation
                $year = $faker->year(); // Random year
                $term = $faker->numberBetween(1, 4); // Random term between 1 and 4
                
                // Randomly select an exam name format
                $examNameFormat = $examNameFormats[array_rand($examNameFormats)];
                
                // Create the exam name using the selected format
                $examName = str_replace(
                    ['{examType}', '{location}', '{district}', '{ward}', '{sublocation}', '{year}'],
                    [$examType, $location, $district, $ward, $sublocation, $year],
                    $examNameFormat
                );
                
                // Create the exam record using the new approach (without class_id and section_id)
                $exam = Exam::create([
                    'name' => $examName, // Generated exam name
                    'term' => $term, // Random term between 1 and 4
                    'year' => (string)$year, // Ensure the year is a string
                    'grading_system_id' => $gradingSystem->id, // Link to a grading system
                ]);
                
                // Add entries to the pivot table for each section
                $pivotData = [];
                
                // Randomly decide how many sections to select (1 to all)
                $sectionsToAdd = $faker->numberBetween(1, $classSections->count());
                $selectedSections = $classSections->random($sectionsToAdd);
                
                // Generate reasonable exam date and times
                $examDate = $faker->dateTimeBetween('-1 year', '+6 months')->format('Y-m-d');
                $startHour = $faker->numberBetween(8, 14);
                $startTime = sprintf('%02d:%02d', $startHour, $faker->randomElement([0, 15, 30, 45]));
                $durationMinutes = $faker->randomElement([60, 90, 120, 180]);
                $endHour = $startHour + floor($durationMinutes / 60);
                $endMinutes = ($startHour % 60) + ($durationMinutes % 60);
                if ($endMinutes >= 60) {
                    $endHour++;
                    $endMinutes -= 60;
                }
                $endTime = sprintf('%02d:%02d', $endHour, $endMinutes);
                
                // Generate some instructions
                $instructions = $faker->randomElement([
                    'Answer all questions. Show your work for full credit.',
                    'Calculators are permitted. No sharing of materials.',
                    'This is a closed-book examination. All materials must be cleared from your desk.',
                    'Read all questions carefully before answering. Time management is essential.',
                    'No electronic devices allowed. Ensure you have all necessary stationery.'
                ]);
                
                // Add each section to the pivot table
                foreach ($selectedSections as $section) {
                    DB::table('exam_class_section')->insert([
                        'exam_id' => $exam->id,
                        'class_id' => $class->id,
                        'section_id' => $section->id,
                        'exam_date' => $examDate,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'instructions' => $instructions,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                
                // Commit the transaction
                DB::commit();
                
            } catch (\Exception $e) {
                // If an error occurs, rollback the transaction
                DB::rollBack();
                \Log::error('Error seeding exam: ' . $e->getMessage());
            }
        }
        
        \Log::info('ExamSeeder completed successfully');
    }
}
