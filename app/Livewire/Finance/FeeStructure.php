<?php

namespace App\Livewire\Finance;

use App\Models\FeeStructure as FeeStructureModel;
use App\Models\MyClass;
use App\Helpers\Qs;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;
use Illuminate\Support\Facades\Auth;
use App\Traits\WithSorting;

class FeeStructure extends Component
{
    use WithPagination, WireToast, WithSorting;
    
    // Component properties
    public $showModal = false;
    public $showDeleteModal = false;
    public $isEditing = false;
    public $feeStructureId = null;
    public $search = '';
    public $classFilter = '';
    public $yearFilter = '';
    public $termFilter = '';
    public $categoryFilter = '';
    public $perPage = 10;
    public $hasManagePermission = false;
    
    // View variables
    public $classrooms = [];
    public $classes = [];
    public $years = [];
    public $categories = [];
    public $totalMandatoryFees = null;
    public $classTotals = [];
    public $viewingFeeStructure = null;
    public $deletingFeeStructure = null;
    public $deleteReason = '';
    
    // User role properties
    public $isAdmin = false;
    public $isAccountant = false;
    public $isTeacher = false;
    public $isStudent = false;
    
    // Form fields
    public $my_class_id;
    public $name;
    public $category;
    public $amount = 0;
    public $description;
    public $academic_year;
    public $term;
    public $is_mandatory = true;
    public $is_active = true;
    
    // Fee categories
    public $feeCategories = [
        'Tuition',
        'Development',
        'Transport',
        'Boarding',
        'Examination',
        'Library',
        'Laboratory',
        'Sports',
        'Computer',
        'Other'
    ];
    
    // Validation rules
    protected $rules = [
        'my_class_id' => 'required|exists:my_classes,id',
        'name' => 'required|string|max:100',
        'category' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'academic_year' => 'required|integer|min:2000|max:2100',
        'term' => 'required|integer|min:1|max:3',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    // Listeners
    protected $listeners = [
        'deleteFeeStructure', 
        'refreshComponent' => '$refresh'
    ];
    
    public function mount()
    {
        // Set user roles
        $this->setUserRoles();
        
        // Set permission
        $this->hasManagePermission = $this->isAdmin || $this->isAccountant;
        
        // Set default sorting
        $this->sortField = 'name';
        
        // Load classes for dropdowns
        $this->loadClasses();
        
        // Load years for dropdowns (current year and previous 5 years)
        $this->years = $this->getYearsForDropdown();
        
        // Set categories from fee categories array
        $this->categories = collect($this->feeCategories)->toArray();
        
        // Set default academic year to current year
        $this->academic_year = date('Y');
        
        // Determine current term based on month (customize as needed)
        $month = date('n');
        if ($month >= 1 && $month <= 4) {
            $this->term = 1;
        } elseif ($month >= 5 && $month <= 8) {
            $this->term = 2;
        } else {
            $this->term = 3;
        }
    }
    
    /**
     * Set user roles based on authentication
     */
    public function setUserRoles()
    {
        if(Auth::check()) {
            $this->isAdmin = Qs::userIsAdmin();
            $this->isAccountant = Qs::isAccountant();
            $this->isTeacher = Qs::userIsTeacher();
            $this->isStudent = Qs::userIsStudent();
            
            // Update management permission
            $this->hasManagePermission = $this->isAdmin || $this->isAccountant;
        }
    }
    
    /**
     * Reset the form fields
     */
    public function resetForm()
    {
        $this->reset([
            'my_class_id', 'name', 'category', 'amount', 'description', 
            'is_mandatory', 'is_active', 'isEditing', 'feeStructureId'
        ]);
        
        // Reset validation rules
        $this->resetValidation();
        
        // Set defaults
        $this->academic_year = date('Y');
        $month = date('n');
        if ($month >= 1 && $month <= 4) {
            $this->term = 1;
        } elseif ($month >= 5 && $month <= 8) {
            $this->term = 2;
        } else {
            $this->term = 3;
        }
        $this->is_mandatory = true;
        $this->is_active = true;
    }
    
    /**
     * Open the create/edit modal
     */
    public function openModal()
    {
        if (!$this->hasManagePermission) {
            toast()->warning('You do not have permission to perform this action.')->push();
            return;
        }
        
        $this->resetForm();
        $this->showModal = true;
        $this->dispatch('open-form-modal');
    }
    
    /**
     * Close the modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('close-form-modal');
    }
    
    /**
     * Show fee structure details in a modal
     */
    public function viewFeeStructure($id)
    {
        try {
            $this->viewingFeeStructure = FeeStructureModel::with(['classroom', 'creator', 'updator'])->findOrFail($id);
            $this->dispatch('open-view-modal');
        } catch (\Exception $e) {
            toast()->danger('Error loading fee structure: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Close view modal
     */
    public function closeViewModal()
    {
        $this->viewingFeeStructure = null;
        $this->dispatch('close-view-modal');
    }

    /**
     * Confirm delete fee structure
     */
    public function confirmDeleteFeeStructure($id)
    {
        if (!$this->hasManagePermission) {
            toast()->warning('You do not have permission to perform this action.')->push();
            return;
        }
        
        try {
            $this->feeStructureId = $id;
            $this->deletingFeeStructure = FeeStructureModel::findOrFail($id);
            $this->deleteReason = '';
            $this->dispatch('open-delete-modal');
        } catch (\Exception $e) {
            toast()->danger('Error loading fee structure: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Close delete confirmation modal
     */
    public function closeDeleteModal()
    {
        $this->feeStructureId = null;
        $this->deletingFeeStructure = null;
        $this->deleteReason = '';
        $this->dispatch('close-delete-modal');
    }
    
    /**
     * Create or update a fee structure
     */
    public function saveFeeStructure()
    {
        if (!$this->hasManagePermission) {
            toast()->warning('You do not have permission to perform this action.')->push();
            return;
        }
        
        if ($this->isEditing) {
            $this->updateFeeStructure();
            return;
        }
        
        $this->validate();
        
        try {
            // Check if a fee structure with the same attributes already exists
            $exists = FeeStructureModel::where('my_class_id', $this->my_class_id)
                ->where('name', $this->name)
                ->where('academic_year', $this->academic_year)
                ->where('term', $this->term)
                ->exists();
                
            if ($exists) {
                toast()->warning("A fee structure with these details already exists.")->push();
                return;
            }
            
            // Create the fee structure
            $feeStructure = FeeStructureModel::create([
                'my_class_id' => $this->my_class_id,
                'name' => $this->name,
                'category' => $this->category,
                'amount' => $this->amount,
                'description' => $this->description,
                'academic_year' => $this->academic_year,
                'term' => $this->term,
                'is_mandatory' => $this->is_mandatory,
                'is_active' => $this->is_active,
                'created_by' => auth()->user()->name,
            ]);
            
            $this->closeModal();
            toast()->success("Fee '{$feeStructure->name}' created successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error creating fee structure: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Load a fee structure for editing
     */
    public function editFeeStructure($id)
    {
        if (!$this->hasManagePermission) {
            toast()->warning('You do not have permission to perform this action.')->push();
            return;
        }
        
        $this->resetForm();
        $this->isEditing = true;
        $this->feeStructureId = $id;
        
        try {
            $feeStructure = FeeStructureModel::findOrFail($id);
            
            $this->my_class_id = $feeStructure->my_class_id;
            $this->name = $feeStructure->name;
            $this->category = $feeStructure->category;
            $this->amount = $feeStructure->amount;
            $this->description = $feeStructure->description;
            $this->academic_year = $feeStructure->academic_year;
            $this->term = $feeStructure->term;
            $this->is_mandatory = $feeStructure->is_mandatory;
            $this->is_active = $feeStructure->is_active;
            
            $this->showModal = true;
        } catch (\Exception $e) {
            toast()->danger('Error loading fee structure: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Update an existing fee structure
     */
    public function updateFeeStructure()
    {
        if (!$this->hasManagePermission) {
            toast()->warning('You do not have permission to perform this action.')->push();
            return;
        }
        
        $this->validate();
        
        try {
            $feeStructure = FeeStructureModel::findOrFail($this->feeStructureId);
            
            // Check if a fee structure with the same attributes already exists (except this one)
            $exists = FeeStructureModel::where('my_class_id', $this->my_class_id)
                ->where('name', $this->name)
                ->where('academic_year', $this->academic_year)
                ->where('term', $this->term)
                ->where('id', '<>', $this->feeStructureId)
                ->exists();
                
            if ($exists) {
                toast()->warning("A fee structure with these details already exists.")->push();
                return;
            }
            
            $feeStructure->update([
                'my_class_id' => $this->my_class_id,
                'name' => $this->name,
                'category' => $this->category,
                'amount' => $this->amount,
                'description' => $this->description,
                'academic_year' => $this->academic_year,
                'term' => $this->term,
                'is_mandatory' => $this->is_mandatory,
                'is_active' => $this->is_active,
                'updated_by' => auth()->user()->name,
            ]);
            
            $this->closeModal();
            toast()->success("Fee '{$feeStructure->name}' updated successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error updating fee structure: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Toggle fee structure active status
     */
    public function toggleStatus($id)
    {
        if (!$this->hasManagePermission) {
            toast()->warning('You do not have permission to perform this action.')->push();
            return;
        }
        
        try {
            $feeStructure = FeeStructureModel::findOrFail($id);
            $status = !$feeStructure->is_active;
            $feeStructure->update([
                'is_active' => $status,
                'updated_by' => auth()->user()->name
            ]);
            
            $statusText = $status ? 'activated' : 'deactivated';
            toast()->success("Fee '{$feeStructure->name}' has been {$statusText}")->push();
        } catch (\Exception $e) {
            toast()->danger('Error toggling status: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Toggle fee structure mandatory status
     */
    public function toggleMandatory($id)
    {
        if (!$this->hasManagePermission) {
            toast()->warning('You do not have permission to perform this action.')->push();
            return;
        }
        
        try {
            $feeStructure = FeeStructureModel::findOrFail($id);
            $status = !$feeStructure->is_mandatory;
            $feeStructure->update([
                'is_mandatory' => $status,
                'updated_by' => auth()->user()->name
            ]);
            
            $statusText = $status ? 'mandatory' : 'optional';
            toast()->success("Fee '{$feeStructure->name}' is now {$statusText}")->push();
        } catch (\Exception $e) {
            toast()->danger('Error toggling mandatory status: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Delete a fee structure
     */
    public function deleteFeeStructure()
    {
        if (!$this->hasManagePermission) {
            toast()->warning('You do not have permission to perform this action.')->push();
            return;
        }
        
        // Validate delete reason
        $this->validate([
            'deleteReason' => 'required|min:3',
        ], [
            'deleteReason.required' => 'Please provide a reason for deletion.',
            'deleteReason.min' => 'The reason must be at least 3 characters.',
        ]);
        
        try {
            if (!$this->feeStructureId || !$this->deletingFeeStructure) {
                toast()->warning('No fee structure selected for deletion.')->push();
                return;
            }
            
            $feeStructure = FeeStructureModel::findOrFail($this->feeStructureId);
            $name = $feeStructure->name;
            
            // Record deletion reason in logs or a separate table if needed
            // You can add code here to log the deletion reason
            
            $feeStructure->delete();
            
            $this->closeDeleteModal();
            toast()->success("Fee '{$name}' has been deleted successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error deleting fee structure: ' . $e->getMessage())->push();
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
        $this->reset(['search', 'classFilter', 'yearFilter', 'termFilter', 'categoryFilter']);
        $this->resetPage();
    }
    
    /**
     * Copy a fee structure to the next term or academic year
     */
    public function copyFeeStructure($id, $target = 'next_term')
    {
        if (!$this->hasManagePermission) {
            toast()->warning('You do not have permission to perform this action.')->push();
            return;
        }
        
        try {
            $original = FeeStructureModel::findOrFail($id);
            
            // Determine target term and academic year
            $newTerm = $original->term;
            $newYear = $original->academic_year;
            
            if ($target === 'next_term') {
                if ($original->term < 3) {
                    $newTerm = $original->term + 1;
                } else {
                    $newTerm = 1;
                    $newYear = $original->academic_year + 1;
                }
            } elseif ($target === 'next_year') {
                $newYear = $original->academic_year + 1;
            }
            
            // Check if fee structure already exists for the target term and year
            $exists = FeeStructureModel::where('my_class_id', $original->my_class_id)
                ->where('name', $original->name)
                ->where('academic_year', $newYear)
                ->where('term', $newTerm)
                ->exists();
                
            if ($exists) {
                toast()->warning("A similar fee structure already exists for the target term/year")->push();
                return;
            }
            
            // Create the new fee structure
            $copy = $original->replicate();
            $copy->term = $newTerm;
            $copy->academic_year = $newYear;
            $copy->created_by = auth()->user()->name;
            $copy->updated_by = null;
            $copy->created_at = now();
            $copy->updated_at = null;
            $copy->save();
            
            $termText = "Term {$newTerm}, {$newYear}";
            toast()->success("Fee '{$original->name}' copied to {$termText}")->push();
        } catch (\Exception $e) {
            toast()->danger('Error copying fee structure: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Export fee structures to Excel
     */
    public function exportFeeStructures()
    {
        // To be implemented
        toast()->info('Export functionality will be implemented soon.')->push();
    }
    
    /**
     * Load classes for dropdowns
     */
    private function loadClasses()
    {
        try {
            $this->classes = MyClass::orderBy('name')->get();
            $this->classrooms = $this->classes;
        } catch (\Exception $e) {
            $this->classes = [];
            $this->classrooms = [];
            toast()->danger('Error loading classes: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Get years for dropdown
     */
    private function getYearsForDropdown()
    {
        $currentYear = (int)date('Y');
        $years = [];
        
        for ($i = $currentYear - 5; $i <= $currentYear + 1; $i++) {
            $years[] = $i;
        }
        
        return $years;
    }
    
    public function render()
    {
        // Build query with filters
        $query = FeeStructureModel::query()
            ->with('classroom')
            ->when($this->search, function ($q) {
                return $q->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                        ->orWhere('category', 'like', "%{$this->search}%");
                });
            })
            ->when($this->classFilter, function ($q) {
                return $q->where('my_class_id', $this->classFilter);
            })
            ->when($this->yearFilter, function ($q) {
                return $q->where('academic_year', $this->yearFilter);
            })
            ->when($this->termFilter, function ($q) {
                return $q->where('term', $this->termFilter);
            })
            ->when($this->categoryFilter, function ($q) {
                return $q->where('category', $this->categoryFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);
        
        // Calculate totals when class, year, and term are selected
        if ($this->classFilter && $this->yearFilter && $this->termFilter) {
            $this->calculateTotalMandatoryFees();
        } else {
            $this->totalMandatoryFees = null;
        }
        
        // Calculate class totals when year and term are selected
        if ($this->yearFilter && $this->termFilter) {
            $this->calculateClassTotals();
        } else {
            $this->classTotals = [];
        }
        
        return view('livewire.finance.fee-structure', [
            'feeStructures' => $query->paginate($this->perPage),
        ]);
    }
    
    /**
     * Calculate total mandatory fees for selected class, year, and term
     */
    protected function calculateTotalMandatoryFees()
    {
        try {
            $this->totalMandatoryFees = FeeStructureModel::where('my_class_id', $this->classFilter)
                ->where('academic_year', $this->yearFilter)
                ->where('term', $this->termFilter)
                ->where('is_mandatory', true)
                ->where('is_active', true)
                ->sum('amount');
        } catch (\Exception $e) {
            $this->totalMandatoryFees = 0;
        }
    }
    
    /**
     * Calculate fee totals for all classes for the selected year and term
     */
    protected function calculateClassTotals()
    {
        try {
            $this->classTotals = [];
            foreach ($this->classes as $class) {
                $total = FeeStructureModel::where('my_class_id', $class->id)
                    ->where('academic_year', $this->yearFilter)
                    ->where('term', $this->termFilter)
                    ->where('is_mandatory', true)
                    ->where('is_active', true)
                    ->sum('amount');
                
                if ($total > 0) {
                    $this->classTotals[$class->id] = $total;
                }
            }
        } catch (\Exception $e) {
            $this->classTotals = [];
        }
    }
}
