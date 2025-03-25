# Creating a Super_Admin User

This guide provides instructions for creating a super_admin user in the MbukuErp system.

## Option 1: Using the Interactive Command

Run the following Artisan command, which will prompt you for the necessary information:

```bash
php artisan make:super_admin
```

Follow the prompts to enter:
- Full name
- Email address
- Username
- Password

## Option 2: Using Command with Parameters

You can also create a super_admin by providing all parameters directly:

```bash
php artisan make:super_admin "Admin Name" "admin@example.com" "admin_username" "secure_password"
```

## Option 3: Using the Helper Script

For convenience, we've included a simple script that creates a super_admin with default values:

```bash
php create-super_admin.php
```

This will create a super_admin with the following default credentials:
- Name: Super Admin
- Email: super_admin@example.com
- Username: super_admin
- Password: password123

**IMPORTANT**: If using this method, be sure to change the password immediately after logging in.

## Customizing the Default Values

If you wish to change the default values in the helper script, edit the `create-super_admin.php` file and modify the following lines:

```php
$name = 'Super Admin';
$email = 'super_admin@example.com';
$username = 'super_admin';
$password = 'password123';
```

## Super_Admin Privileges

According to the system's user types definition, a super_admin (level 1) has the highest level of access in the system and can:

- Access all system features
- Manage other users, including admins
- Configure system-wide settings
- Access all data and reports

## Security Note

For production environments, always:
1. Use strong, unique passwords
2. Change default passwords immediately
3. Use secure email addresses
4. Limit the number of super_admin accounts 