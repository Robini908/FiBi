# Creating a Super_Admin User

This guide provides instructions for creating a super_admin user in the MbukuErp system.

## Option 1: Using the Interactive Script (Recommended)

For an interactive experience that prompts you for all details, run:

```bash
php create-interactive-super_admin.php
```

This will guide you through creating a super_admin user with your own custom details and validates your inputs as you go.

## Option 2: Using the Pre-configured Script

For quick setup with pre-configured values, run:

```bash
php create-super_admin.php
```

This will create a super_admin with the following credentials:
- Name: MbukuErp Admin Manager
- Email: admin_manager@mbukuerp.com
- Username: mbuku_admin
- Password: MbukuAdmin@123

**IMPORTANT**: Please change this password immediately after login.

### How These Scripts Work

Both scripts:
1. Bootstrap the Laravel application
2. Check if a user with the same email or username already exists
3. Verify that the 'super_admin' user type exists in the database
4. Create the user directly using the User model
5. Set the user_type to 'super_admin', which corresponds to level 1 (highest access)

## Option 3: Using Artisan Commands (Alternative)

If you prefer using Artisan commands, you can use:

```bash
php artisan make:super_admin
```

This will prompt you for:
- Full name
- Email address
- Username
- Password

Or provide all parameters at once:

```bash
php artisan make:super_admin "Admin Name" "admin@example.com" "admin_username" "secure_password"
```

## Customizing the Pre-configured Script

If you want to customize the default super_admin created by the helper script, edit the `create-super_admin.php` file and modify these variables:

```php
$name = 'MbukuErp Admin Manager';
$email = 'admin_manager@mbukuerp.com';
$username = 'mbuku_admin';
$password = 'MbukuAdmin@123';
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
5. Regularly review super_admin access 