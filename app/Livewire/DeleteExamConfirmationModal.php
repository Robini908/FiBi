<?php

namespace App\Livewire;

use App\Models\Exam;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;

class DeleteExamConfirmationModal extends ModalComponent
{
    public $examId;
    public $examName;
    
    /**
     * Mount the component with the exam ID
     * 
     * @param int $examId
     * @return void
     */
    public function mount($examId)
    {
        $this->examId = $examId;
        
        // Get the exam name for confirmation message
        $exam = Exam::findOrFail($examId);
        $this->examName = $exam->name;
        
        // Authorization check - only admin and superadmin can delete exams
        $this->authorizeAccess($exam);
    }
    
    /**
     * Authorize access to delete exams based on user role
     * 
     * @param Exam $exam The exam to authorize access for
     * @return void
     */
    protected function authorizeAccess($exam)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Only admins and superadmins can delete exams
            $allowedRoles = ['admin', 'superadmin'];
            $userRole = $user->role ?? 'guest';
            
            if (!in_array($userRole, $allowedRoles)) {
                $this->forceClose()->closeModal();
                toast('You do not have permission to delete exams.', 'error');
                return;
            }
        } else {
            $this->forceClose()->closeModal();
            toast('You must be logged in to delete exams.', 'error');
            return;
        }
    }
    
    /**
     * Delete the exam
     * 
     * @return void
     */
    public function deleteExam()
    {
        try {
            $exam = Exam::findOrFail($this->examId);
            $exam->delete();
            
            $this->closeModalWithEvents([
                'examDeleted' => ['examId' => $this->examId],
            ]);
            
            toast('Exam deleted successfully.', 'success');
        } catch (\Exception $e) {
            toast('Failed to delete exam: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Set modal max width
     *
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        return 'lg';
    }
    
    /**
     * Render the component
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.delete-exam-confirmation-modal');
    }
} 