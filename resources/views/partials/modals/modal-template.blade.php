<?php
/**
 * Wire Elements Modal Template
 * 
 * Instructions:
 * 1. Create a new Livewire component that extends ModalComponent
 * 2. Move your modal content to the component's view
 * 3. Replace your existing modal with a button that dispatches the openModal event
 * 
 * Example component class (App\Livewire\ExamDetailsModal.php):
 * 
 * <?php
 * namespace App\Livewire;
 * 
 * use LivewireUI\Modal\ModalComponent;
 * use App\Models\Exam;
 * 
 * class ExamDetailsModal extends ModalComponent
 * {
 *     public Exam $exam;
 *     
 *     public function mount(Exam $exam)
 *     {
 *         $this->exam = $exam;
 *     }
 *     
 *     public function render()
 *     {
 *         return view('livewire.exam-details-modal');
 *     }
 * }
 */
?>

<!-- ORIGINAL MODAL (before conversion): -->
<!--
<div class="fixed inset-0 overflow-y-auto z-50 {{ $showExamDetailsModal ? 'block' : 'hidden' }}">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <!-- Modal content here -->
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <!-- Modal buttons here -->
            </div>
        </div>
    </div>
</div>
-->

<!-- REPLACEMENT BUTTON (after conversion): -->
<!--
<button 
    wire:click="$dispatch('openModal', { component: 'exam-details-modal', arguments: { exam: {{ $exam->id }} } })"
    class="text-blue-600 hover:text-blue-900"
>
    View Details
</button>
-->

<!-- MODAL COMPONENT VIEW (create in resources/views/livewire/exam-details-modal.blade.php): -->
<!--
<div>
    <!-- Modal Header -->
    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    {{ $exam->name ?? 'Exam Details' }}
                </h3>
                
                <!-- Modal Content -->
                <div class="mt-4">
                    <!-- Your modal content here -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Footer -->
    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="button" 
            wire:click="$dispatch('closeModal')" 
            class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Close
        </button>
    </div>
</div>
-->

<!-- Common Role-Based Access Control Pattern -->
<!--
// In your modal component class:
public function mount($resourceId = null)
{
    if ($resourceId) {
        $this->resource = SomeModel::findOrFail($resourceId);
        
        // Role-based access control
        if (auth()->check()) {
            $userRole = auth()->user()->role;
            $allowedRoles = ['admin', 'teacher', 'superadmin'];
            
            if (!in_array($userRole, $allowedRoles)) {
                // For resources that require authorization
                if (!auth()->user()->can('view', $this->resource)) {
                    $this->forceClose()->closeModal();
                    toast('You do not have permission to view this resource.', 'error');
                    return;
                }
                
                // Hide sensitive information
                $this->showSensitiveInfo = false;
            }
        }
    }
}
--> 