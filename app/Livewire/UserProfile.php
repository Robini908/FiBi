<?php

namespace App\Livewire;

use App\Helpers\Qs;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserProfile extends Component
{
    use WithFileUploads;

    // User data
    public $user;
    public $name;
    public $username;
    public $email;
    public $phone;
    public $mobile;
    public $address;
    public $photo;
    
    // Password data
    public $currentPassword;
    public $password;
    public $password_confirmation;
    
    // UI states
    public $isEditing = false;
    public $tempPhoto;
    
    // Success and error messages
    public $showSuccessMessage = false;
    public $showErrorMessage = false;
    public $errorMessage = '';
    
    // Protected properties
    protected $listeners = ['refreshUserProfile' => '$refresh'];

    // Rules for validation
    protected function rules()
    {
        $rules = [
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'tempPhoto' => 'nullable|image|max:2048'
        ];
        
        // Only validate username if it's not already set
        if (!$this->user->username) {
            $rules['username'] = 'required|string|unique:users,username|min:3|max:30';
        }
        
        return $rules;
    }
    
    // Password validation rules
    protected $passwordRules = [
        'currentPassword' => 'required|current_password',
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required'
    ];

    public function mount()
    {
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->mobile = $this->user->mobile;
        $this->address = $this->user->address;
        $this->photo = $this->user->photo 
            ? $this->user->photo 
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function startEditing()
    {
        $this->isEditing = true;
        $this->resetMessages();
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->loadUserData();
        $this->resetMessages();
        $this->reset('tempPhoto', 'currentPassword', 'password', 'password_confirmation');
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'tempPhoto') {
            $this->validateOnly($propertyName, [
                'tempPhoto' => 'image|max:1024|mimes:jpg,jpeg,png',
            ]);
        }
    }

    /**
     * Save photo after uploading via the photo manager
     */
    public function savePhoto()
    {
        $this->validate([
            'tempPhoto' => 'required|image|max:1024|mimes:jpg,jpeg,png',
        ]);
        
        try {
            $path = $this->tempPhoto->store('profile-photos', 'public');
            
            // Delete old photo if exists and not the default one
            if ($this->user->photo && !str_contains($this->user->photo, 'ui-avatars.com')) {
                // If the photo URL is a full URL to a storage file, try to get just the path
                $oldPath = str_replace(url('storage/'), '', $this->user->photo);
                if ($oldPath !== $this->user->photo) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            // Set the full URL as photo
            $fullUrl = url('storage/' . $path);
            $this->user->photo = $fullUrl;
            $this->user->save();
            
            $this->photo = $fullUrl;
            $this->tempPhoto = null;
            
            $this->showSuccessMessage = true;
        } catch (\Exception $e) {
            $this->showErrorMessage = true;
            $this->errorMessage = 'Failed to save photo: ' . $e->getMessage();
        }
    }

    /**
     * Cancel photo upload without saving
     */
    public function cancelPhotoUpload()
    {
        $this->reset('tempPhoto');
        $this->resetValidation('tempPhoto');
    }

    /**
     * Remove the current photo and set to default
     */
    public function removePhoto()
    {
        try {
            // Delete photo if exists and not the default one
            if ($this->user->photo && !str_contains($this->user->photo, 'ui-avatars.com')) {
                // If the photo URL is a full URL to a storage file, try to get just the path
                $oldPath = str_replace(url('storage/'), '', $this->user->photo);
                if ($oldPath !== $this->user->photo) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            $this->user->photo = null;
            $this->user->save();
            
            $this->photo = 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&color=7F9CF5&background=EBF4FF';
            
            $this->showSuccessMessage = true;
        } catch (\Exception $e) {
            $this->showErrorMessage = true;
            $this->errorMessage = 'Failed to remove photo: ' . $e->getMessage();
        }
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        
        try {
            $this->user->update([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'phone' => $this->phone,
                'mobile' => $this->mobile,
                'address' => $this->address,
            ]);
            
            $this->isEditing = false;
            $this->showSuccessMessage = true;
            $this->loadUserData();
        } catch (\Exception $e) {
            $this->showErrorMessage = true;
            $this->errorMessage = 'Failed to update profile: ' . $e->getMessage();
        }
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);
        
        try {
            $this->user->update([
                'password' => Hash::make($this->password),
            ]);
            
            $this->reset('currentPassword', 'password', 'password_confirmation');
            $this->showSuccessMessage = true;
        } catch (\Exception $e) {
            $this->showErrorMessage = true;
            $this->errorMessage = 'Failed to update password: ' . $e->getMessage();
        }
    }

    private function resetMessages()
    {
        $this->showSuccessMessage = false;
        $this->showErrorMessage = false;
        $this->errorMessage = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}
