<?php
namespace Database\Seeders;

use App\Models\Dorm;
use App\User; 
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DormsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        // Using 'teacher' role name consistent with Qs.php
        $teachers = User::where('user_type', 'teacher')->pluck('id')->toArray();

        // Check if there are teachers
        if (empty($teachers)) {
            $this->command->error('No teachers found in the users table.');
            return;
        }

        $this->command->info('Creating dormitories...');
        
        $dormData = [];
        for ($i = 0; $i < 20; $i++) {
            $numberOfStudents = rand(30, 100); // Set a maximum limit for students in the dorm

            // Assign a random teacher as the dorm master
            $dormMasterId = $faker->randomElement($teachers);

            $dormData[] = [
                'name' => $faker->company . ' Dormitory',
                'capacity' => $numberOfStudents, // Set capacity to the number of students
                'user_id' => $dormMasterId, // Assign the teacher as the user_id
                'dorm_master_id' => $dormMasterId, // Add dorm_master_id for the teacher
                'session' => $faker->year($max = 'now'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Insert dorms in batches
        Dorm::insert($dormData);
        
        $this->command->info('Dormitories created successfully. Students will be assigned in a separate seeder.');
    }
}
