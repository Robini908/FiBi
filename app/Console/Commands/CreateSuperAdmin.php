<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:super_admin {name?} {email?} {username?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new super_admin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get inputs with prompts if not provided
        $name = $this->argument('name') ?: $this->ask('What is the super_admin\'s full name?');
        $email = $this->argument('email') ?: $this->ask('What is the super_admin\'s email?');
        $username = $this->argument('username') ?: $this->ask('What is the super_admin\'s username?');
        $password = $this->argument('password') ?: $this->secret('What is the super_admin\'s password?');

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('The email address is invalid.');
            return 1;
        }

        // Check if email or username already exists
        if (DB::table('users')->where('email', $email)->exists()) {
            $this->error("A user with the email '{$email}' already exists.");
            return 1;
        }

        if (DB::table('users')->where('username', $username)->exists()) {
            $this->error("A user with the username '{$username}' already exists.");
            return 1;
        }

        // Create the super_admin user
        $user = [
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => Hash::make($password),
            'user_type' => 'super_admin',  // Based on UserTypesTableSeeder
            'code' => strtoupper(Str::random(10)),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        try {
            DB::table('users')->insert($user);
            $this->info('Super_admin created successfully!');
            $this->table(
                ['Name', 'Email', 'Username', 'User Type'],
                [[$name, $email, $username, 'Super_Admin']]
            );
            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred while creating the super_admin: ' . $e->getMessage());
            return 1;
        }
    }
} 