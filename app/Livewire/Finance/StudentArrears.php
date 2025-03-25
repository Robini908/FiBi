<?php

namespace App\Livewire\Finance;

use App\Models\StudentArrear;
use App\Models\StudentRecord;
use App\Models\MyClass;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Auth;

class StudentArrears extends Component
{
    use WithPagination, WireToast;
    
    // User role properties
    public $isAdmin = false;
    public $isAccountant = false;
    public $isParent = false;
    public $isStudent = false;
    public $parentChildren = [];
    
    // Component properties
    public $showModal = false;
    public $isEditing = false;
    public $arrearId = null;
    public $search = '';
    public $classFilter = '';
    public $yearFilter = '';
    public $termFilter = '';
    public $statusFilter = '';
    public $selectedStudent = null;
    
    // Form fields
    public $student_id;
    public $class_id;
    public $amount;
    public $previous_year;
    public $previous_term;
    public $description;
    
    // Student details for confirmation
    public $student_name;
    public $student_admission_number;
    public $arrears_total;
    
    // For bulk import
    public $showBulkImportModal = false;
    public $selectedClass = null;
    public $selectedYear = null;
    public $selectedTerm = null;
    
    // Validation rules
    protected $rules = [
        'student_id' => 'required|exists:student_records,id',
        'amount' => 'required|numeric|min:0',
        'previous_year' => 'required|integer|min:2000|max:2100',
        'previous_term' => 'required|integer|min:1|max:3',
        'description' => 'required|string|max:500',
    ];
    
    // Listeners
    protected $listeners = ['deleteArrear', 'markArrearAsCleared'];
    
    public function mount()
    {
        // Set user role flags
        $this->isAdmin = Qs::isAdministrator() || Qs::isAdmin();
        $this->isAccountant = Qs::isAccountant();
        $this->isParent = Qs::isParent();
        $this->isStudent = Qs::isStudent();
        
        // For parent users, get their children's IDs
        if ($this->isParent) {
            $userId = Auth::id();
            $this->parentChildren = StudentRecord::where('my_parent_id', $userId)
                ->pluck('id')
                ->toArray();
        }
        
        // For student users, preselect themselves
        if ($this->isStudent) {
            $this->student_id = StudentRecord::where('user_id', Auth::id())->value('id');
            $this->updatedStudentId();
        }
        
        // Set default previous year to last year
        $this->previous_year = date('Y') - 1;
        
        // Set default previous term to term 3
        $this->previous_term = 3;
    }
    
    /**
     * Reset the form fields
     */
    public function resetForm()
    {
        $this->reset([
            'student_id', 'class_id', 'amount', 'description', 
            'isEditing', 'arrearId', 'student_name', 
            'student_admission_number', 'arrears_total'
        ]);
        
        // Set defaults
        $this->previous_year = date('Y') - 1;
        $this->previous_term = 3;
        
        // Reset validation rules
        $this->resetValidation();
    }
    
    /**
     * Update student details when student is selected
     */
    public function updatedStudentId()
    {
        if ($this->student_id) {
            $student = StudentRecord::find($this->student_id);
            
            if ($student) {
                $this->student_name = $student->name;
                $this->student_admission_number = $student->admission_number;
                $this->class_id = $student->my_class_id;
                $this->arrears_total = StudentArrear::totalArrears($this->student_id);
            }
        } else {
            $this->student_name = '';
            $this->student_admission_number = '';
            $this->class_id = null;
            $this->arrears_total = 0;
        }
    }
    
    /**
     * Open the create/edit modal
     */
    public function openModal()
    {
        // Only administrators and accountants can create/edit arrears
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()->warning('You do not have permission to create or edit arrears')->push();
            return;
        }
        
        $this->resetForm();
        $this->showModal = true;
    }
    
    /**
     * Close the modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    /**
     * Open the bulk import modal
     */
    public function openBulkImportModal()
    {
        $this->showBulkImportModal = true;
    }
    
    /**
     * Close the bulk import modal
     */
    public function closeBulkImportModal()
    {
        $this->showBulkImportModal = false;
        $this->reset(['selectedClass', 'selectedYear', 'selectedTerm']);
    }
    
    /**
     * Create a new arrear
     */
    public function saveArrear()
    {
        if ($this->isEditing) {
            $this->updateArrear();
            return;
        }
        
        $this->validate();
        
        try {
            // Create the arrear
            $arrear = StudentArrear::create([
                'student_id' => $this->student_id,
                'class_id' => $this->class_id,
                'amount' => $this->amount,
                'previous_year' => $this->previous_year,
                'previous_term' => $this->previous_term,
                'description' => $this->description,
                'is_cleared' => false,
                'created_by' => auth()->user()->name,
            ]);
            
            $this->closeModal();
            toast()->success('Student arrear added successfully')->push();
        } catch (\Exception $e) {
            toast()->danger('Error adding arrear: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Load an arrear for editing
     */
    public function editArrear($id)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->arrearId = $id;
        
        try {
            $arrear = StudentArrear::findOrFail($id);
            
            // Check if arrear can be edited (only uncleared arrears)
            if ($arrear->is_cleared) {
                toast()->warning('Cleared arrears cannot be edited')->push();
                return;
            }
            
            $this->student_id = $arrear->student_id;
            $this->class_id = $arrear->class_id;
            $this->amount = $arrear->amount;
            $this->previous_year = $arrear->previous_year;
            $this->previous_term = $arrear->previous_term;
            $this->description = $arrear->description;
            
            // Load student details
            $this->updatedStudentId();
            
            $this->showModal = true;
        } catch (\Exception $e) {
            toast()->danger('Error loading arrear: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Update an existing arrear
     */
    public function updateArrear()
    {
        $this->validate();
        
        try {
            $arrear = StudentArrear::findOrFail($this->arrearId);
            
            // Check if arrear can be edited
            if ($arrear->is_cleared) {
                toast()->warning('Cleared arrears cannot be edited')->push();
                return;
            }
            
            $arrear->update([
                'student_id' => $this->student_id,
                'class_id' => $this->class_id,
                'amount' => $this->amount,
                'previous_year' => $this->previous_year,
                'previous_term' => $this->previous_term,
                'description' => $this->description,
                'updated_by' => auth()->user()->name,
            ]);
            
            $this->closeModal();
            toast()->success('Student arrear updated successfully')->push();
        } catch (\Exception $e) {
            toast()->danger('Error updating arrear: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Delete an arrear
     */
    public function deleteArrear($id)
    {
        // Only administrators and accountants can delete arrears
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()->warning('You do not have permission to delete arrears')->push();
            return;
        }
        
        try {
            $arrear = StudentArrear::findOrFail($id);
            
            // Check if arrear can be deleted (only uncleared arrears)
            if ($arrear->is_cleared) {
                toast()->warning('Cleared arrears cannot be deleted')->push();
                return;
            }
            
            $arrear->delete();
            
            toast()->success('Student arrear deleted successfully')->push();
        } catch (\Exception $e) {
            toast()->danger('Error deleting arrear: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Mark an arrear as cleared
     */
    public function markArrearAsCleared($id)
    {
        // Only administrators and accountants can mark arrears as cleared
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()->warning('You do not have permission to clear arrears')->push();
            return;
        }
        
        try {
            $arrear = StudentArrear::findOrFail($id);
            
            if ($arrear->is_cleared) {
                toast()->info('This arrear is already cleared')->push();
                return;
            }
            
            $arrear->markAsCleared(auth()->user()->name);
            
            toast()->success('Arrear marked as cleared successfully')->push();
        } catch (\Exception $e) {
            toast()->danger('Error clearing arrear: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Generate student arrears from previous term/year unpaid fees
     */
    public function generateArrears()
    {
        // Only administrators and accountants can generate arrears
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()->warning('You do not have permission to generate arrears')->push();
            return;
        }
        
        $this->validate([
            'selectedClass' => 'required|exists:my_classes,id',
            'selectedYear' => 'required|integer|min:2000|max:2100',
            'selectedTerm' => 'required|integer|min:1|max:3',
        ]);
        
        try {
            // Get students from the selected class
            $students = StudentRecord::where('my_class_id', $this->selectedClass)
                ->where('is_active', true)
                ->get();
                
            $arrearsCount = 0;
            
            // This would typically connect to your fee payment system
            // to generate arrears based on unpaid fees
            // For demonstration, we'll just use a placeholder approach
            foreach ($students as $student) {
                // Calculate fee balance for the student (this is a placeholder)
                // In a real system, you would calculate this from StudentFeePayment model
                $feeBalance = 0; // This should be calculated from your actual fee records
                
                if ($feeBalance > 0) {
                    StudentArrear::create([
                        'student_id' => $student->id,
                        'class_id' => $this->selectedClass,
                        'amount' => $feeBalance,
                        'previous_year' => $this->selectedYear,
                        'previous_term' => $this->selectedTerm,
                        'description' => 'Unpaid fees from ' . $this->selectedYear . ' Term ' . $this->selectedTerm,
                        'is_cleared' => false,
                        'created_by' => auth()->user()->name,
                    ]);
                    
                    $arrearsCount++;
                }
            }
            
            $this->closeBulkImportModal();
            
            if ($arrearsCount > 0) {
                toast()->success('Generated ' . $arrearsCount . ' student arrears successfully')->push();
            } else {
                toast()->info('No arrears were generated. All students have paid their fees.')->push();
            }
        } catch (\Exception $e) {
            toast()->danger('Error generating arrears: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Apply filters
     */
    public function applyFilters()
    {
        $this->resetPage();
    }
    
    /**
     * Reset filters
     */
    public function resetFilters()
    {
        $this->reset(['classFilter', 'yearFilter', 'termFilter', 'statusFilter', 'search']);
        $this->resetPage();
    }
    
    /**
     * Render the component
     */
    public function render()
    {
        // Get all classes for filter dropdown
        $classes = MyClass::orderBy('name')->get();
        
        // Get unique academic years from the arrears
        $years = StudentArrear::select('previous_year')
            ->distinct()
            ->orderBy('previous_year', 'desc')
            ->pluck('previous_year')
            ->toArray();
            
        if (empty($years)) {
            $years = [date('Y') - 1];
        }
        
        // Build the query
        $query = StudentArrear::with(['student.myClass', 'myClass']);
        
        // Filter by user role
        if ($this->isStudent) {
            // Students can only see their own arrears
            $studentId = StudentRecord::where('user_id', Auth::id())->value('id');
            $query->where('student_id', $studentId);
        } elseif ($this->isParent) {
            // Parents can only see their children's arrears
            $query->whereIn('student_id', $this->parentChildren);
        }
        
        // Apply filters
        $query->when($this->classFilter, function ($q) {
                $q->where('class_id', $this->classFilter);
            })
            ->when($this->yearFilter, function ($q) {
                $q->where('previous_year', $this->yearFilter);
            })
            ->when($this->termFilter, function ($q) {
                $q->where('previous_term', $this->termFilter);
            })
            ->when($this->statusFilter !== '', function ($q) {
                $q->where('is_cleared', $this->statusFilter === 'cleared');
            })
            ->when($this->search, function ($q) {
                $q->where(function ($qry) {
                    $qry->where('description', 'like', "%{$this->search}%")
                        ->orWhere('amount', 'like', "%{$this->search}%")
                        ->orWhereHas('student', function ($q) {
                            $q->where('name', 'like', "%{$this->search}%")
                                ->orWhere('admission_number', 'like', "%{$this->search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc');
            
        // Get students for form dropdown
        $students = [];
        
        if ($this->isAdmin || $this->isAccountant) {
            // Admins/accountants see all active students
            $students = StudentRecord::where('is_active', true)
                ->orderBy('name')
                ->get();
        } elseif ($this->isParent) {
            // Parents only see their children
            $students = StudentRecord::whereIn('id', $this->parentChildren)
                ->orderBy('name')
                ->get();
        }
        
        // Calculate total arrears for different categories
        if ($this->isAdmin || $this->isAccountant) {
            $totalArrearsAll = StudentArrear::where('is_cleared', false)->sum('amount');
            $totalArrearsClass = $this->classFilter ? 
                StudentArrear::where('class_id', $this->classFilter)
                    ->where('is_cleared', false)
                    ->sum('amount') 
                : 0;
        } elseif ($this->isParent) {
            $totalArrearsAll = StudentArrear::whereIn('student_id', $this->parentChildren)
                ->where('is_cleared', false)
                ->sum('amount');
            $totalArrearsClass = $this->classFilter ? 
                StudentArrear::whereIn('student_id', $this->parentChildren)
                    ->where('class_id', $this->classFilter)
                    ->where('is_cleared', false)
                    ->sum('amount') 
                : 0;
        } elseif ($this->isStudent) {
            $studentId = StudentRecord::where('user_id', Auth::id())->value('id');
            $totalArrearsAll = StudentArrear::where('student_id', $studentId)
                ->where('is_cleared', false)
                ->sum('amount');
            $totalArrearsClass = $totalArrearsAll; // For students, both totals are the same
        } else {
            $totalArrearsAll = 0;
            $totalArrearsClass = 0;
        }
        
        return view('livewire.finance.student-arrears', [
            'arrears' => $query->paginate(10),
            'classes' => $classes,
            'years' => $years,
            'students' => $students,
            'totalArrearsAll' => $totalArrearsAll,
            'totalArrearsClass' => $totalArrearsClass,
            'isAdmin' => $this->isAdmin,
            'isAccountant' => $this->isAccountant,
            'isParent' => $this->isParent,
            'isStudent' => $this->isStudent,
        ]);
    }
}
