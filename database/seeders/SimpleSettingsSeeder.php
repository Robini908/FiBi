<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SimpleSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        $now = Carbon::now();
        
        $settings = [
            // School Settings Group
            [
                'key' => 'school_name',
                'display_name' => 'School Name',
                'value' => 'Kenya Model Secondary School',
                'type' => 'text',
                'group' => 'school',
                'description' => 'Full name of the school',
                'is_public' => true,
                'sort_order' => 1,
                'options' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'school_type',
                'display_name' => 'School Type',
                'value' => 'Mixed',
                'type' => 'select',
                'group' => 'school',
                'description' => 'Type of school (Mixed, Boys, or Girls)',
                'is_public' => true,
                'sort_order' => 2,
                'options' => json_encode(['Mixed', 'Boys', 'Girls']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'school_category',
                'display_name' => 'School Category',
                'value' => 'Boarding and Day',
                'type' => 'select',
                'group' => 'school',
                'description' => 'Category of school (Boarding, Day, or Both)',
                'is_public' => true,
                'sort_order' => 3,
                'options' => json_encode(['Boarding', 'Day', 'Boarding and Day']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            
            // Academic Settings Group
            [
                'key' => 'current_academic_year',
                'display_name' => 'Current Academic Year',
                'value' => '2024 to 2025',
                'type' => 'academic_year',
                'group' => 'academic',
                'description' => 'The current academic year in format YYYY-YYYY',
                'is_public' => true,
                'sort_order' => 1,
                'options' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'next_academic_year',
                'display_name' => 'Next Academic Year',
                'value' => '2025 to 2026',
                'type' => 'academic_year',
                'group' => 'academic',
                'description' => 'The next academic year for planning',
                'is_public' => true,
                'sort_order' => 2,
                'options' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'current_term',
                'display_name' => 'Current Term',
                'value' => 'Term 1',
                'type' => 'select',
                'group' => 'academic',
                'description' => 'The current academic term',
                'is_public' => true,
                'sort_order' => 3,
                'options' => json_encode(['Term 1', 'Term 2', 'Term 3']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            
            // System Settings Group
            [
                'key' => 'system_name',
                'display_name' => 'System Name',
                'value' => 'MBUKU ERP',
                'type' => 'text',
                'group' => 'system',
                'description' => 'Name of the ERP system',
                'is_public' => true,
                'sort_order' => 1,
                'options' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'enable_sms_notifications',
                'display_name' => 'Enable SMS Notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Enable or disable SMS notifications',
                'is_public' => false,
                'sort_order' => 2,
                'options' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            
            // Finance Settings Group
            [
                'key' => 'currency',
                'display_name' => 'Currency',
                'value' => 'KES',
                'type' => 'text',
                'group' => 'finance',
                'description' => 'Currency code (e.g., KES for Kenyan Shilling)',
                'is_public' => true,
                'sort_order' => 1,
                'options' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'currency_symbol',
                'display_name' => 'Currency Symbol',
                'value' => 'KSh',
                'type' => 'text',
                'group' => 'finance',
                'description' => 'Symbol for the currency',
                'is_public' => true,
                'sort_order' => 2,
                'options' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('settings')->insert($settings);
    }
} 