<?php

namespace App\Livewire;

use Mpdf\Mpdf;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Services\SuspensionService;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ManageSuspensions extends Component
{
    use LivewireAlert;
    
    // Updated property definition
    public $suspendedStudents = [];
    public $isReinstating = false;
    public $showForm = false;

    public $selectedStudentId;
    public $student;
    public $studentName;
    public $newSuspensionEndDate;
    public $isExtendingSuspension = false;

    public $suspensionPeriod;

    // New properties for filter controls
    public $search = '';
    public $classFilter = '';
    public $typeFilter = '';
    public $durationFilter = '';
    
    // New properties for statistics
    public $totalSuspended = 0;
    public $temporarySuspensions = 0;
    public $indefiniteSuspensions = 0;
    public $expiringThisWeek = 0;
    public $totalStudents = 0;
    
    // New properties for creating a new suspension
    public $isCreatingSuspension = false;
    public $studentId;
    public $selectedStudent;
    public $suspensionType = 'temporary';
    public $startDate;
    public $endDate;
    public $reason;
    public $notifyParent = true;

    public function mount()
    {
        $this->fetchSuspendedStudents();
        $this->calculateStatistics();
    }

    public function printSuspension($id)
    {
        $student = StudentRecord::find($id);
        if (!$student) {
            $this->alert('error', 'Student not found.');
            return;
        }
        $html = view('pdf.suspension', ['student' => $student])->render();
        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, 'suspension_details.pdf');
    }

    public function downloadStudentSuspension($studentId)
    {
        // Find the student in the database directly
        $student = StudentRecord::find($studentId);

        if ($student && $student->is_suspended) {
            $suspensionService = new SuspensionService();
            $pdfPath = $suspensionService->generateSuspensionPdf(
                $student,
                $student->suspension_reason,
                $student->suspension_type,
                $student->suspension_end_date
            );

            return response()->download($pdfPath)->deleteFileAfterSend();
        } else {
            $this->alert('error', 'Student not found or not suspended.');
        }
    }


    public function printStudentSuspension($studentId)
    {
        // Find the student in the database directly
        $student = StudentRecord::find($studentId);

        if ($student && $student->is_suspended) {
            $this->dispatch('printStudentSuspension', $student);
        } else {
            $this->alert('error', 'Student not found or not suspended.');
        }
    }



    public function printSuspensions()
    {
        // Emit an event to handle client-side printing
        $this->dispatch('printSuspensions');
    }



    public function humanReadableCountdown($endDate)
    {
        $now = Carbon::now();
        $endDate = Carbon::parse($endDate);

        // Calculate the difference
        $diff = $now->diff($endDate);

        // Format the difference in a human-readable way
        $weeks = $diff->days / 7; // Get weeks from days
        $days = $diff->days % 7; // Remaining days after full weeks

        return sprintf(
            "%d weeks, %d days, %d hours, %d minutes, %d seconds",
            floor($weeks),
            $days,
            $diff->h,
            $diff->i,
            $diff->s
        );
    }

    public function humanReadableElapsedTime($startDate)
    {
        $now = Carbon::now();
        $startDate = Carbon::parse($startDate);

        // Calculate the difference
        $diff = $now->diff($startDate);

        // Format the difference in a human-readable way
        return sprintf(
            "%d days, %d hours, %d minutes, %d seconds ago",
            $diff->d,
            $diff->h,
            $diff->i,
            $diff->s
        );
    }

    public function fetchSuspendedStudents()
    {
        // Start with a query for suspended students
        $query = StudentRecord::where('is_suspended', true);
        
        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('adm_no', 'like', '%' . $this->search . '%');
            });
        }
        
        // Apply class filter
        if (!empty($this->classFilter)) {
            $query->where('my_class_id', $this->classFilter);
        }
        
        // Apply suspension type filter
        if (!empty($this->typeFilter)) {
            $query->where('suspension_type', $this->typeFilter);
        }
        
        // Apply duration filter
        if (!empty($this->durationFilter)) {
            $now = Carbon::now();
            
            if ($this->durationFilter === 'short') {
                // Less than 1 week
                $query->where('suspension_type', 'temporary')
                      ->whereNotNull('suspension_end_date')
                      ->where('suspension_end_date', '<', $now->copy()->addWeek());
            } else if ($this->durationFilter === 'medium') {
                // 1-4 weeks
                $query->where('suspension_type', 'temporary')
                      ->whereNotNull('suspension_end_date')
                      ->where('suspension_end_date', '>=', $now->copy()->addWeek())
                      ->where('suspension_end_date', '<', $now->copy()->addWeeks(4));
            } else if ($this->durationFilter === 'long') {
                // More than 4 weeks
                $query->where(function($q) use ($now) {
                    $q->where('suspension_type', 'temporary')
                      ->whereNotNull('suspension_end_date')
                      ->where('suspension_end_date', '>=', $now->copy()->addWeeks(4))
                      ->orWhere('suspension_type', 'indefinite');
                });
            }
        }
        
        // Get the results and assign to the property as a Collection
        $this->suspendedStudents = $query->get();
    }

    public function reinstate($id)
    {
        $this->selectedStudentId = $id;
        $this->isReinstating = true;
        // $this->studentName = $this->student->name;
    }

    public function confirmReinstatement()
    {
        $student = StudentRecord::find($this->selectedStudentId);
        if ($student) {
            $student->is_suspended = false; // Reinstate the student
            $student->suspension_reason = null; // Clear the reason for suspension
            $student->suspended_by = null; // Clear who suspended the student
            $student->suspension_date = null; // Clear the suspension date
            $student->suspension_type = null; // Clear the type of suspension
            $student->suspension_end_date = null; // Clear the end date
            $student->save();

            $this->alert('success', 'Student reinstated successfully.');
            $this->fetchSuspendedStudents(); // Fetch updated list of suspended students
            $this->calculateStatistics(); // Recalculate statistics
        } else {
            $this->alert('error', 'Failed to reinstate student. Please try again.');
        }

        $this->resetFields();
    }


    public function checkSuspensions()
    {
        $now = Carbon::now();

        $studentsToReinstate = StudentRecord::where('is_suspended', true)
            ->whereNotNull('suspension_end_date')
            ->where('suspension_end_date', '<=', $now)
            ->get();

        foreach ($studentsToReinstate as $student) {
            $student->update([
                'is_suspended' => false,
                'suspension_reason' => null,
                'suspended_by' => null,
                'suspension_date' => null,
                'suspension_type' => null,
                'suspension_end_date' => null,
            ]);
        }

        if ($studentsToReinstate->count() > 0) {
        $this->fetchSuspendedStudents(); // Refresh the list of suspended students
            $this->calculateStatistics(); // Recalculate statistics
        }
    }

    public function confirmExtension()
    {
        // Validate the new suspension end date input
        $this->validate([
            'newSuspensionEndDate' => 'required|date|after_or_equal:' . now()->toDateString(),
        ]);

        // Check if the student is set
        if (!$this->student) {
            $this->alert('error', 'Student not found.');
            return;
        }

        // Get the current end date
        $currentEndDate = $this->student->suspension_end_date
            ? Carbon::parse($this->student->suspension_end_date)
            : now();

        // Parse the new suspension end date from the input
        $newEndDate = Carbon::parse($this->newSuspensionEndDate);

        // Calculate total suspension duration
        if ($this->student->is_suspended) {
            // If the student is already suspended, we extend the end date if the new one is later
            if ($newEndDate->isAfter($currentEndDate)) {
                $this->student->suspension_end_date = $newEndDate; // Update to new end date
            }
        } else {
            $this->alert('error', 'Student is not currently suspended.');
            return;
        }

        // Save the updated student record
        $this->student->save();

        // Set a success message in the session
        $this->alert('success', 'Suspension extended successfully.');
        $this->fetchSuspendedStudents(); // Fetch updated list of suspended students
        $this->calculateStatistics(); // Recalculate statistics

        // Reset fields after processing
        $this->resetFields();
    }

    public function extendSuspension($id)
    {
        $this->selectedStudentId = $id;
        $this->student = StudentRecord::find($id); // Load the student record
        $this->isExtendingSuspension = true;
    }

    private function resetFields()
    {
        $this->isReinstating = false;
        $this->isExtendingSuspension = false;
        $this->isCreatingSuspension = false;
        $this->suspensionPeriod = null;
        $this->selectedStudentId = null;
        $this->student = null;
        $this->newSuspensionEndDate = null;
    }

    public function render()
    {
        // Get all classes for the class filter dropdown
        $classes = MyClass::orderBy('name')->get();
        
        // Refresh suspended students list every render call
        $this->fetchSuspendedStudents();
        $this->calculateStatistics();
        
        // Get active students for new suspension form
        $activeStudents = $this->getActiveStudents();
        
        return view('livewire.manage-suspensions', [
            'suspendedStudents' => $this->suspendedStudents,
            'student' => $this->student, // Pass the student to the view
            'classes' => $classes, // Pass classes for the filter dropdown
            'totalSuspended' => $this->totalSuspended,
            'temporarySuspensions' => $this->temporarySuspensions,
            'indefiniteSuspensions' => $this->indefiniteSuspensions,
            'expiringThisWeek' => $this->expiringThisWeek,
            'totalStudents' => $this->totalStudents,
            'students' => $activeStudents, // Pass active students for the dropdown
        ]);
    }

    // Method to reset all filters
    public function resetFilters()
    {
        $this->search = '';
        $this->classFilter = '';
        $this->typeFilter = '';
        $this->durationFilter = '';
        $this->dispatchBrowserEvent('search-changed');
    }
    
    // Helper method to get class name from ID
    public function getClassName($classId)
    {
        $class = MyClass::find($classId);
        return $class ? $class->name : 'Unknown';
    }
    
    // Method to create a new suspension
    public function createSuspension()
    {
        // Validate the input
        $this->validate([
            'studentId' => 'required|exists:student_records,id',
            'suspensionType' => 'required|in:temporary,indefinite',
            'startDate' => 'required|date|after_or_equal:' . now()->toDateString(),
            'reason' => 'required|string|min:10',
        ]);
        
        // Additional validation for temporary suspensions
        if ($this->suspensionType === 'temporary') {
            $this->validate([
                'endDate' => 'required|date|after:startDate',
            ]);
        }
        
        // Find the student
        $student = StudentRecord::find($this->studentId);
        
        if (!$student) {
            $this->alert('error', 'Student not found.');
            return;
        }
        
        // Check if the student is already suspended
        if ($student->is_suspended) {
            $this->alert('error', 'This student is already suspended.');
            return;
        }
        
        // Update student record
        $student->is_suspended = true;
        $student->suspension_reason = $this->reason;
        $student->suspension_date = Carbon::parse($this->startDate);
        $student->suspension_type = $this->suspensionType;
        
        if ($this->suspensionType === 'temporary' && $this->endDate) {
            $student->suspension_end_date = Carbon::parse($this->endDate);
        } else {
            $student->suspension_end_date = null;
        }
        
        // Store who suspended the student (current authenticated user)
        $student->suspended_by = auth()->id();
        
        // Save the changes
        $student->save();
        
        // Generate and send PDF if notify parent is checked
        if ($this->notifyParent) {
            // Placeholder for email notification to parent
            // For now, we'll just generate the PDF
            $suspensionService = new SuspensionService();
            $suspensionService->generateSuspensionPdf(
                $student,
                $this->reason,
                $this->suspensionType,
                $student->suspension_end_date
            );
            
            // You would add code here to send the email with the PDF attachment
        }
        
        // Reset the form
        $this->resetSuspensionForm();
        
        // Refresh the list of suspended students
        $this->fetchSuspendedStudents();
        $this->calculateStatistics();
        
        // Show success message
        $this->alert('success', 'Student suspended successfully.');
    }
    
    // Method to reset the suspension form
    private function resetSuspensionForm()
    {
        $this->isCreatingSuspension = false;
        $this->studentId = null;
        $this->selectedStudent = null;
        $this->suspensionType = 'temporary';
        $this->startDate = null;
        $this->endDate = null;
        $this->reason = null;
        $this->notifyParent = true;
    }
    
    // Method to calculate statistics
    private function calculateStatistics()
    {
        $this->totalSuspended = StudentRecord::where('is_suspended', true)->count();
        $this->temporarySuspensions = StudentRecord::where('is_suspended', true)
            ->where('suspension_type', 'temporary')
            ->count();
        $this->indefiniteSuspensions = StudentRecord::where('is_suspended', true)
            ->where('suspension_type', 'indefinite')
            ->count();
        $this->expiringThisWeek = StudentRecord::where('is_suspended', true)
            ->where('suspension_type', 'temporary')
            ->whereNotNull('suspension_end_date')
            ->where('suspension_end_date', '<=', now()->addWeek())
            ->count();
        $this->totalStudents = StudentRecord::count();
    }
    
    // Method to handle student selection for create suspension form
    public function updatedStudentId($value)
    {
        if ($value) {
            $this->selectedStudent = StudentRecord::find($value);
        } else {
            $this->selectedStudent = null;
        }
    }

    // Livewire v3 event listener
    #[On('refreshSuspensions')]
    public function refreshSuspensions()
    {
        $this->fetchSuspendedStudents();
    }

    // Method to get active students (not suspended)
    public function getActiveStudents()
    {
        return StudentRecord::where('is_suspended', false)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }
}
