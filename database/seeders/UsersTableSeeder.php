<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Qs;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default users
        $this->command->info('Creating default users...');
        $defaultUsers = $this->createNewUsers();

        // Create 100 users for each user type except teacher
        $this->command->info('Creating users of various types...');
        $this->createManyUsers(100);

        // Create 200 teachers
        $this->command->info('Creating teacher users...');
        $this->createTeachers(200);

        // Assign roles to all users
        $this->command->info('Assigning Spatie roles to users...');
        $this->assignRolesToUsers();
    }

    /**
     * Create the default admin users.
     * 
     * @return array Created users
     */
    protected function createNewUsers()
    {
        $password = Hash::make('cj'); // Default user password
        $users = [];

        // Using consistent role names from Qs.php helper
        $defaultUsers = [
            [
                'name' => 'CJ Inspired',
                'email' => 'cj@cj.com',
                'username' => 'cj',
                'password' => $password,
                'user_type' => 'super_admin', // Consistent with Qs.php
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Admin KORA',
                'email' => 'admin@admin.com',
                'username' => 'admin',
                'password' => $password,
                'user_type' => 'admin',
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Parent Kaba',
                'email' => 'parent@parent.com',
                'username' => 'parent',
                'password' => $password,
                'user_type' => 'parent',
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Accountant Jeff',
                'email' => 'accountant@accountant.com',
                'username' => 'accountant',
                'password' => $password,
                'user_type' => 'accountant',
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Teacher Wise',
                'email' => 'teacher@teacher.com',
                'username' => 'teacher',
                'password' => $password,
                'user_type' => 'teacher',
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Librarian Bookman',
                'email' => 'librarian@librarian.com',
                'username' => 'librarian',
                'password' => $password,
                'user_type' => 'librarian',
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
        ];

        foreach ($defaultUsers as $user) {
            // Check if email or username already exists
            if (!DB::table('users')->where('email', $user['email'])->orWhere('username', $user['username'])->exists()) {
                $userId = DB::table('users')->insertGetId($user);
                $users[] = $userId;
            }
        }

        return $users;
    }

    /**
     * Create many users of different types
     * 
     * @param int $count Number of users per type to create
     * @return void
     */
    protected function createManyUsers(int $count)
    {
        $data = [];
        // Define the user types consistently with Qs.php helper
        $userTypes = ['admin', 'parent', 'accountant', 'librarian', 'student'];

        // Initialize Faker
        $faker = Faker::create();

        foreach ($userTypes as $userType) {
            for ($i = 1; $i <= $count; $i++) {
                $email = strtolower($userType) . $i . '@example.com';
                $username = strtolower($userType) . $i;

                // Check for existing email or username before adding to data array
                if (!DB::table('users')->where('email', $email)->orWhere('username', $username)->exists()) {
                    $data[] = [
                        'name' => ucfirst($userType) . ' ' . $faker->lastName, // Random last name
                        'email' => $email,
                        'user_type' => $userType, // Consistent with Qs.php
                        'username' => $username,
                        'password' => Hash::make('password'), // Default password
                        'code' => strtoupper(Str::random(10)),
                        'remember_token' => Str::random(10),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert users in chunks to optimize performance
        $chunks = array_chunk($data, 1000); // Insert in batches of 1000
        foreach ($chunks as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }

    /**
     * Create teacher users
     * 
     * @param int $count Number of teachers to create
     * @return void
     */
    protected function createTeachers(int $count)
    {
        $data = [];
        // Initialize Faker
        $faker = Faker::create();

        for ($i = 1; $i <= $count; $i++) {
            $email = 'teacher' . $i . '@example.com';
            $username = 'teacher' . $i;

            // Check for existing email or username before adding to data array
            if (!DB::table('users')->where('email', $email)->orWhere('username', $username)->exists()) {
                $data[] = [
                    'name' => 'Teacher ' . $faker->name, // Random teacher name
                    'email' => $email,
                    'user_type' => 'teacher', // Consistent with Qs.php
                    'username' => $username,
                    'password' => Hash::make('password'), // Default password
                    'code' => strtoupper(Str::random(10)),
                    'remember_token' => Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert teachers in chunks to optimize performance
        $chunks = array_chunk($data, 1000); // Insert in batches of 1000
        foreach ($chunks as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }

    /**
     * Assign Spatie roles to all users based on their user_type
     * 
     * @return void
     */
    protected function assignRolesToUsers()
    {
        // Make sure all roles exist
        $availableRoles = Role::pluck('name')->toArray();
        
        if (empty($availableRoles)) {
            $this->command->error("No roles found in the database. Run the RolesAndPermissionsSeeder first.");
            return;
        }

        // Get all users
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            // Skip users that already have roles
            if ($user->hasAnyRole(Role::all())) {
                continue;
            }

            // Map user_type to role name (they should be the same in this case)
            $roleName = $user->user_type;
            
            // Handle any legacy role naming - map to the correct role name as defined in Qs.php
            if ($roleName === 'super-admin') {
                $roleName = 'super_admin';
            }
            
            // Check if the role exists and assign it
            if (in_array($roleName, $availableRoles)) {
                $user->assignRole($roleName);
                $count++;
            } else {
                $this->command->warn("Role '{$roleName}' not found for user ID {$user->id}");
            }
        }

        $this->command->info("Assigned roles to {$count} users.");
    }
}
