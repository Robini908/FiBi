<?php

/*
|--------------------------------------------------------------------------
| Super Admin Creation Script
|--------------------------------------------------------------------------
|
| This script creates a super admin user with Spatie roles and permissions.
| Run this script with: php create-interactive-super_admin.php
|
*/

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\User;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

// Clear screen
system('clear');

echo "\n\n";
echo "┌───────────────────────────────────────────────────┐\n";
echo "│                                                   │\n";
echo "│       MBUKU ERP SUPER ADMIN CREATION UTILITY      │\n";
echo "│                                                   │\n";
echo "└───────────────────────────────────────────────────┘\n\n";

// Check if super_admin role exists - using consistent role name as in Qs.php
$superAdminRole = Role::where('name', 'super_admin')->first();
if (!$superAdminRole) {
    echo "\033[33mRole 'super_admin' does not exist yet. Creating it now...\033[0m\n";
    $superAdminRole = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    echo "\033[32mSuper Admin role created successfully!\033[0m\n\n";
} else {
    echo "\033[32mFound existing Super Admin role.\033[0m\n\n";
}

echo "This utility will help you create a new Super Administrator account.\n";
echo "Please provide the following information:\n\n";

// Get user input
echo "Full Name: ";
$name = trim(fgets(STDIN));

$email = null;
$validEmail = false;
while (!$validEmail) {
    echo "Email Address: ";
    $email = trim(fgets(STDIN));
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email is already in use
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            echo "\033[31mEmail is already in use. Please try another email.\033[0m\n";
        } else {
            $validEmail = true;
        }
    } else {
        echo "\033[31mInvalid email format. Please try again.\033[0m\n";
    }
}

$username = null;
$validUsername = false;
while (!$validUsername) {
    echo "Username: ";
    $username = trim(fgets(STDIN));
    
    if (strlen($username) >= 3) {
        // Check if username is already in use
        $existingUser = User::where('username', $username)->first();
        if ($existingUser) {
            echo "\033[31mUsername is already in use. Please try another username.\033[0m\n";
        } else {
            $validUsername = true;
        }
    } else {
        echo "\033[31mUsername must be at least 3 characters long. Please try again.\033[0m\n";
    }
}

echo "Phone Number: ";
$phone = trim(fgets(STDIN));

$password = null;
$validPassword = false;
while (!$validPassword) {
    echo "Password (min 8 characters): ";
    $password = trim(fgets(STDIN));
    
    if (strlen($password) >= 8) {
        echo "Confirm Password: ";
        $confirmPassword = trim(fgets(STDIN));
        
        if ($password === $confirmPassword) {
            $validPassword = true;
        } else {
            echo "\033[31mPasswords do not match. Please try again.\033[0m\n";
        }
    } else {
        echo "\033[31mPassword must be at least 8 characters long. Please try again.\033[0m\n";
    }
}

// Create the user
echo "\n\033[33mCreating super admin user...\033[0m\n";

try {
    $user = User::create([
        'name' => $name,
        'email' => $email,
        'username' => $username,
        'password' => Hash::make($password),
        'phone' => $phone,
        'code' => strtoupper(Str::random(10)),
        'user_type' => 'super_admin', // Consistent with role name in Qs.php
        'photo' => 'user.png',
    ]);

    // Assign the super_admin role - using consistent role name as in Qs.php
    $user->assignRole('super_admin');
    
    echo "\033[32mSuper Admin user created successfully!\033[0m\n\n";
    echo "Username: \033[1m{$username}\033[0m\n";
    echo "Email: \033[1m{$email}\033[0m\n";
    echo "Role Assigned: \033[1msuper_admin\033[0m\n";
    echo "You can now log in with these credentials.\n\n";
    
} catch (Exception $e) {
    echo "\033[31mError creating super admin user: {$e->getMessage()}\033[0m\n";
}

echo "┌───────────────────────────────────────────────────┐\n";
echo "│                                                   │\n";
echo "│     SUPER ADMIN CREATION COMPLETED                │\n";
echo "│                                                   │\n";
echo "└───────────────────────────────────────────────────┘\n\n"; 