<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Base data needed for the system
        $this->call(BloodGroupsTableSeeder::class);
        $this->call(SkillsTableSeeder::class);
        $this->call(ClassTypesTableSeeder::class);
        $this->call(MyClassesTableSeeder::class);
        
        // Add Spatie Roles and Permissions before creating users
        $this->call(RolesAndPermissionsSeeder::class);
        
        // User-related data
        $this->call(UsersTableSeeder::class);
        
        // Settings
        $this->call(SettingsTableSeeder::class);
        $this->call(SectionsTableSeeder::class);

        // Student-related data
        $this->call(ParentDetailsTableSeeder::class);
        $this->call(StudentRecordSeeder::class);

        // Academic data
        $this->call(SubjectCategorySeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(GradingSystemSeeder::class);
        
        // Dormitory data
        $this->call(DormsTableSeeder::class);
        
        // Create pivot table records for dorm-student relationships
        $this->call(DormStudentTableSeeder::class);
    }
}
