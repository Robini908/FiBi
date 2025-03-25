<?php

/**
 * Script to create a super_admin user with predefined credentials
 * 
 * Usage:
 * php create-super_admin.php
 */

// Define the default values
$name = 'Super Admin';
$email = 'super_admin@example.com';
$username = 'super_admin';
$password = 'password123'; // Default password, change this in production

// Build and execute the artisan command
$command = sprintf(
    'php artisan make:super_admin "%s" "%s" "%s" "%s"',
    escapeshellarg($name),
    escapeshellarg($email),
    escapeshellarg($username),
    escapeshellarg($password)
);

// Print the command (without the password)
echo "Executing: php artisan make:super_admin \"{$name}\" \"{$email}\" \"{$username}\" ********\n";

// Execute the command
$output = [];
$return_var = 0;
exec($command, $output, $return_var);

// Output the result
foreach ($output as $line) {
    echo $line . PHP_EOL;
}

if ($return_var !== 0) {
    echo "Command failed with exit code {$return_var}" . PHP_EOL;
} else {
    echo "Super_admin user created successfully!" . PHP_EOL;
    echo "Username: {$username}" . PHP_EOL;
    echo "Password: {$password}" . PHP_EOL;
    echo "IMPORTANT: Please change this password immediately after login." . PHP_EOL;
} 