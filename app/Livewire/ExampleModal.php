<?php

namespace App\Livewire;

use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;

class ExampleModal extends ModalComponent
{
    // Example parameters that could be passed to the modal
    public $userId;
    public $title = 'Example Modal';
    public $showAdvancedOptions = false;

    // Mount method to handle modal initialization
    public function mount($userId = null, $showAdvancedOptions = false)
    {
        $this->userId = $userId;
        $this->showAdvancedOptions = $showAdvancedOptions;
        
        // Example of role-based access control
        // Only allow admin, teacher, and superadmin to access advanced options
        if ($showAdvancedOptions) {
            $allowedRoles = ['admin', 'teacher', 'superadmin'];
            $userRole = Auth::user()->role ?? 'guest';
            
            if (!in_array($userRole, $allowedRoles)) {
                $this->showAdvancedOptions = false;
            }
        }
    }
    
    // Example method to demonstrate modal actions
    public function save()
    {
        // Simulate saving data
        // In a real app, you would validate and save to the database
        
        // Close the modal and dispatch an event to refresh a parent component
        $this->closeModalWithEvents([
            'exampleUpdated' => ['success' => true],
        ]);
    }
    
    // Example method to demonstrate closing a modal
    public function cancel()
    {
        $this->closeModal();
    }
    
    // Set modal properties - override parent class methods
    public static function modalMaxWidth(): string
    {
        return '2xl';
    }
    
    // Prevent closing on escape for important forms
    public static function closeModalOnEscape(): bool
    {
        return true; // Set to false to prevent escape key closing
    }
    
    // Prevent closing when clicking outside the modal
    public static function closeModalOnClickAway(): bool
    {
        return true; // Set to false to prevent click away closing
    }

    public function render()
    {
        return view('livewire.example-modal');
    }
}
