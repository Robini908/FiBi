<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key', 
        'display_name', 
        'value', 
        'type', 
        'group', 
        'options', 
        'description', 
        'is_public',
        'sort_order'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'sort_order' => 'integer',
        'options' => 'array'
    ];

    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            // Convert the value based on the type
            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function set(string $key, $value): bool
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return false;
        }
        
        $setting->value = $value;
        $result = $setting->save();
        
        if ($result) {
            Cache::forget("settings.{$key}");
        }
        
        return $result;
    }

    /**
     * Get all settings by group
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup(string $group)
    {
        return self::where('group', $group)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get all settings
     *
     * @param bool $grouped
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public static function getAllSettings(bool $grouped = false)
    {
        $settings = self::orderBy('group')
            ->orderBy('sort_order')
            ->get();
            
        if (!$grouped) {
            return $settings;
        }
        
        return $settings->groupBy('group');
    }

    /**
     * Cast stored value to its appropriate type
     *
     * @param string $value
     * @param string $type
     * @return mixed
     */
    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'array':
            case 'json':
                return json_decode($value, true) ?: [];
            default:
                return $value;
        }
    }
}
