<?php

namespace App\Livewire;

use App\Models\Exam;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;

class ExamDetailsModal extends ModalComponent
{
    public $exam;
    public $examId;
    
    /**
     * Mount the component with the exam ID
     * 
     * @param int $examId
     * @return void
     */
    public function mount($examId = null)
    {
        $this->examId = $examId;
        
        if ($examId) {
            $this->exam = Exam::with('gradingSystem')->findOrFail($examId);
            
            // Role-based access control
            $this->authorizeAccess();
        }
    }
    
    /**
     * Authorize access to the exam details based on user role
     * 
     * @return void
     */
    protected function authorizeAccess()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Example of role-based access control
            // If the user is not an admin, teacher, or super admin
            $allowedRoles = ['admin', 'teacher', 'superadmin'];
            $userRole = $user->role ?? 'guest';
            
            if (!in_array($userRole, $allowedRoles)) {
                // We could use Laravel's authorization system too
                // if (!$user->can('view', $this->exam)) {
                //     $this->forceClose()->closeModal();
                //     toast('You do not have permission to view this exam.', 'error');
                //     return;
                // }
                
                // For simplicity, we'll just check if the exam is published
                if (!$this->exam->is_published) {
                    $this->forceClose()->closeModal();
                    toast('This exam is not published yet.', 'error');
                    return;
                }
            }
        }
    }
    
    /**
     * Set modal max width
     *
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        // Available width classes: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
        return '2xl';
    }
    
    /**
     * Render the component
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.exam-details-modal');
    }
} 