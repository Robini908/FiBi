<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for students
        $studentPermissions = [
            'view_own_profile',
            'view_own_grades',
            'view_own_timetable',
            'view_own_attendance',
            'submit_assignments',
            'view_course_materials',
            'join_online_classes',
        ];

        // Create permissions for teachers
        $teacherPermissions = [
            'view_student_profiles',
            'manage_grades',
            'create_assignments',
            'view_class_attendance',
            'mark_attendance',
            'create_course_materials',
            'view_timetable',
            'conduct_online_classes',
        ];

        // Create permissions for parents
        $parentPermissions = [
            'view_children_profiles',
            'view_children_grades',
            'view_children_attendance',
            'view_fee_statements',
            'communicate_with_teachers',
        ];

        // Create permissions for librarians
        $librarianPermissions = [
            'manage_books',
            'manage_book_categories',
            'manage_book_issues',
            'manage_book_returns',
            'view_student_profiles',
            'generate_library_reports',
            'manage_library_settings',
            'send_reminders',
            'view_own_profile',
        ];

        // Create permissions for accountants
        $accountantPermissions = [
            'manage_fees',
            'manage_fee_categories',
            'manage_fee_payments',
            'generate_financial_reports',
            'manage_expenses',
            'manage_salary',
            'view_student_profiles',
            'view_fee_statements',
            'view_own_profile',
        ];

        // Create permissions for administrators
        $adminPermissions = [
            'manage_users',
            'manage_teachers',
            'manage_students',
            'manage_parents',
            'manage_classes',
            'manage_sections',
            'manage_subjects',
            'manage_timetable',
            'manage_exams',
            'manage_attendance',
            'manage_fees',
            'view_reports',
            'manage_settings',
        ];

        // Create permissions for super administrators
        $superAdminPermissions = [
            'manage_admins',
            'manage_roles',
            'manage_permissions',
            'manage_system_settings',
            'access_logs',
            'backup_data',
            'restore_data',
        ];

        // Create all permissions
        $allPermissions = array_merge(
            $studentPermissions,
            $teacherPermissions,
            $parentPermissions,
            $librarianPermissions,
            $accountantPermissions,
            $adminPermissions,
            $superAdminPermissions
        );

        // Create permissions if they don't exist
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        // Using consistent role names as defined in Qs.php helper
        $roleStudent = Role::firstOrCreate(['name' => 'student']);
        $roleStudent->syncPermissions($studentPermissions);

        $roleTeacher = Role::firstOrCreate(['name' => 'teacher']);
        $roleTeacher->syncPermissions(array_merge($studentPermissions, $teacherPermissions));

        $roleParent = Role::firstOrCreate(['name' => 'parent']);
        $roleParent->syncPermissions($parentPermissions);

        $roleLibrarian = Role::firstOrCreate(['name' => 'librarian']);
        $roleLibrarian->syncPermissions($librarianPermissions);

        $roleAccountant = Role::firstOrCreate(['name' => 'accountant']);
        $roleAccountant->syncPermissions($accountantPermissions);

        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions(array_merge(
            $studentPermissions,
            $teacherPermissions,
            $parentPermissions,
            $librarianPermissions,
            $accountantPermissions,
            $adminPermissions
        ));

        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $roleSuperAdmin->syncPermissions(Permission::all());

        // Assign roles to existing users based on user_type
        $this->command->info('Starting role assignment to users...');
        
        $users = User::all();
        $assignedCount = 0;
        
        foreach ($users as $user) {
            // Skip users who already have roles assigned
            if ($user->hasAnyRole(Role::all())) {
                continue;
            }
            
            // Ensure user_type is consistent with role names in Qs.php
            switch ($user->user_type) {
                case 'student':
                    $user->assignRole('student');
                    $assignedCount++;
                    break;
                case 'teacher':
                    $user->assignRole('teacher');
                    $assignedCount++;
                    break;
                case 'parent':
                    $user->assignRole('parent');
                    $assignedCount++;
                    break;
                case 'librarian':
                    $user->assignRole('librarian');
                    $assignedCount++;
                    break;
                case 'accountant':
                    $user->assignRole('accountant');
                    $assignedCount++;
                    break;
                case 'admin':
                    $user->assignRole('admin');
                    $assignedCount++;
                    break;
                case 'super_admin':
                    $user->assignRole('super_admin');
                    $assignedCount++;
                    break;
                // Handle any legacy user types
                case 'super-admin':
                    $user->assignRole('super_admin');
                    $assignedCount++;
                    break;
            }
        }
        
        $this->command->info("Roles assigned to {$assignedCount} users successfully.");
    }
}
