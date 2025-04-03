<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingRepo
{
    /**
     * Get setting(s) by type
     *
     * @param string $type
     * @return \Illuminate\Support\Collection
     */
    public function getSetting($type)
    {
        // Check if Setting model and database table exist
        if (class_exists('App\\Models\\Setting')) {
            return Setting::where('key', $type)->get();
        }
        
        // Return default values for specific setting types if model doesn't exist
        if ($type === 'current_session') {
            return collect([
                (object)[
                    'id' => 1,
                    'key' => 'current_session',
                    'description' => '2023-2024'
                ]
            ]);
        }
        
        if ($type === 'session') {
            return collect([
                (object)[
                    'id' => 1,
                    'key' => 'session',
                    'description' => '2021-2022'
                ],
                (object)[
                    'id' => 2,
                    'key' => 'session',
                    'description' => '2022-2023'
                ],
                (object)[
                    'id' => 3,
                    'key' => 'session',
                    'description' => '2023-2024'
                ],
                (object)[
                    'id' => 4,
                    'key' => 'session',
                    'description' => '2024-2025'
                ]
            ]);
        }
        
        return collect([]);
    }
} 