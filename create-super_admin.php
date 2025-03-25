<?php

/**
 * Script to create a super_admin user directly using the User model
 * 
 * Usage:
 * php create-super_admin.php
 */

// Bootstrap Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

// Define the super_admin details
$name = 'MbukuErp Admin Manager';
$email = 'admin_manager@mbukuerp.com';
$username = 'mbuku_admin';
$password = 'MbukuAdmin@123'; // Strong default password

// Check if User with this email or username already exists
if (User::where('email', $email)->orWhere('username', $username)->exists()) {
    echo "A user with the email '{$email}' or username '{$username}' already exists.\n";
    exit(1);
}

// Check if the super_admin role exists in Spatie Roles
$superAdminRole = Role::where('name', 'super_admin')->first();
if (!$superAdminRole) {
    echo "The 'super_admin' role does not exist in the database. Please run the RolesAndPermissionsSeeder first.\n";
    exit(1);
}

try {
    // Create the super_admin user
    $user = new User();
    $user->name = $name;
    $user->email = $email;
    $user->username = $username;
    $user->password = Hash::make($password);
    $user->user_type = 'super_admin'; // Using consistent role name as in Qs.php
    $user->code = strtoupper(Str::random(10));
    $user->remember_token = Str::random(10);
    $user->save();

    // Assign the super_admin role using Spatie
    $user->assignRole('super_admin');

    // Output success message
    echo "========================================================\n";
    echo "Super admin user created successfully!\n";
    echo "========================================================\n";
    echo "Name: {$name}\n";
    echo "Email: {$email}\n";
    echo "Username: {$username}\n";
    echo "Password: {$password}\n";
    echo "User Type: super_admin\n";
    echo "Role Assigned: super_admin\n";
    echo "========================================================\n";
    echo "IMPORTANT: Please change this password immediately after login.\n";
    
    exit(0);
} catch (\Exception $e) {
    echo "An error occurred while creating the super_admin user: {$e->getMessage()}\n";
    exit(1);
} 