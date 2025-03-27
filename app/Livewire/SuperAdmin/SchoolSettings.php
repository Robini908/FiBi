<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

class SchoolSettings extends Component
{
    public $activeGroup = 'school';
    public $editingSettings = false;
    public $formValues = [];
    public $successMessage = '';
    
    // Store the settings data privately
    private $settingsData = null;

    public function mount()
    {
        // Clear any potential caching issues
        $this->settingsData = null;
        Cache::forget('settings_data');
        
        $this->loadFormValues();
    }

    #[Title('School Settings')]
    public function render()
    {
        return view('livewire.super-admin.school-settings');
    }

    #[Computed]
    public function settings()
    {
        if ($this->settingsData === null) {
            $this->settingsData = Setting::getAllSettings(true);
            
            // For debugging
            if ($this->settingsData->isEmpty()) {
                $this->successMessage = 'Warning: No settings found in the database. Please run seeder.';
            }
        }
        return $this->settingsData;
    }
    
    #[Computed]
    public function groups()
    {
        $groups = array_keys($this->settings()->toArray());
        
        // If no groups are found, set a message
        if (empty($groups)) {
            $this->successMessage = 'Warning: No setting groups found. Please check the database.';
        }
        
        return $groups;
    }
    
    public function loadFormValues()
    {
        $this->formValues = [];
        
        // Ensure active group is valid
        if (!in_array($this->activeGroup, $this->groups()) && !empty($this->groups())) {
            $this->activeGroup = $this->groups()[0];
        }
        
        if (isset($this->settings()[$this->activeGroup])) {
            foreach ($this->settings()[$this->activeGroup] as $setting) {
                $this->formValues[$setting->key] = $setting->value;
            }
        }
    }
    
    public function setActiveGroup($group)
    {
        $this->activeGroup = $group;
        $this->loadFormValues();
    }
    
    public function startEditing()
    {
        $this->editingSettings = true;
    }
    
    public function cancelEditing()
    {
        $this->editingSettings = false;
        $this->loadFormValues();
    }
    
    public function updateSettings()
    {
        $this->validate([
            'formValues.*' => 'required',
        ]);
        
        $updated = false;
        
        foreach ($this->formValues as $key => $value) {
            if (Setting::set($key, $value)) {
                $updated = true;
            }
        }
        
        if ($updated) {
            // Clear all settings cache
            Cache::flush();
            
            // Reset the settings data to force a refresh
            $this->settingsData = null;
            
            // Reload form values
            $this->loadFormValues();
            
            $this->successMessage = 'Settings updated successfully.';
            $this->editingSettings = false;
        }
    }
}
