<?php

namespace App\Livewire\Finance;

use App\Models\FeeAllocation;
use App\Models\FinanceAccount;
use App\Models\AccountVotehead;
use App\Models\Term;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\DB;
use Usernotnull\Toast\Concerns\WireToast;

class FeeAllocations extends Component
{
    use WithPagination, WithSorting, WireToast;
    
    // Search and Filter Properties
    public $search = '';
    public $financeAccountFilter = '';
    public $voteheadFilter = '';
    public $statusFilter = '';
    public $yearFilter = '';
    public $termFilter = '';

    // Form Properties
    public $showAllocationModal = false;
    public $showDeleteModal = false;
    public $editAllocationId = null;
    public $deleteAllocationId = null;
    public $finance_account_id;
    public $votehead_id;
    public $academic_year;
    public $term;

    public $form = [
        'finance_account_id' => '',
        'votehead_id' => '',
        'amount' => '',
        'description' => '',
        'academic_year' => '',
        'term' => '',
        'is_approved' => false,
    ];
    
    // Component properties
    public $isEditing = false;
    public $allocationId = null;
    public $accountFilter = '';
    public $voteheadName = '';
    
    // Computed properties
    public $availableVoteheads = [];
    public $accountBalance = 0;
    public $accountName = '';

    // Statistics properties
    public $pendingCount = 0;
    public $approvedCount = 0;
    public $totalAmount = 0;
    
    // Listeners
    protected $listeners = ['deleteAllocation', 'approveAllocation', 'refreshFeeAllocations' => '$refresh'];
    
    /**
     * Initialize properties with default values
     */
    public function initializeProperties()
    {
        $this->showAllocationModal = false;
        $this->showDeleteModal = false;
        $this->editAllocationId = null;
        $this->deleteAllocationId = null;
        $this->finance_account_id = null;
        $this->votehead_id = null;
        $this->academic_year = date('Y');
        $this->term = null;
        $this->isEditing = false;
        $this->allocationId = null;
        $this->accountFilter = '';
        $this->voteheadName = '';
        $this->availableVoteheads = [];
        $this->accountBalance = 0;
        $this->accountName = '';
    }
    
    public function mount()
    {
        $this->initializeProperties();
        
        // Set default year and term based on current month
        $currentMonth = date('n');
        if ($currentMonth >= 1 && $currentMonth <= 4) {
            $this->form['academic_year'] = date('Y');
            $this->form['term'] = 1;
        } elseif ($currentMonth >= 5 && $currentMonth <= 8) {
            $this->form['academic_year'] = date('Y');
            $this->form['term'] = 2;
        } else {
            $this->form['academic_year'] = date('Y');
            $this->form['term'] = 3;
        }
        
        // Apply the same defaults to filters
        $this->yearFilter = $this->form['academic_year'];
        $this->termFilter = $this->form['term'];

        // Load initial statistics
        $this->loadStatistics();
    }

    /**
     * Load statistics for the dashboard cards
     */
    private function loadStatistics()
    {
        $query = FeeAllocation::query();

        // Apply the same filters as the main query
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('financeAccount', function ($subq) {
                    $subq->where('name', 'like', "%{$this->search}%");
                })->orWhereHas('votehead', function ($subq) {
                    $subq->where('name', 'like', "%{$this->search}%");
                })->orWhere('description', 'like', "%{$this->search}%");
            });
        }
        
        if ($this->financeAccountFilter) {
            $query->where('finance_account_id', $this->financeAccountFilter);
        }
        
        if ($this->voteheadFilter) {
            $query->where('votehead_id', $this->voteheadFilter);
        }
        
        if ($this->yearFilter) {
            $query->where('academic_year', $this->yearFilter);
        }
        
        if ($this->termFilter) {
            $query->where('term', $this->termFilter);
        }

        // Get statistics
        $pendingCountQuery = clone $query;
        $this->pendingCount = $pendingCountQuery->where('is_approved', false)->count();
        
        $approvedCountQuery = clone $query;
        $this->approvedCount = $approvedCountQuery->where('is_approved', true)->count();
        
        $totalAmountQuery = clone $query;
        $this->totalAmount = $totalAmountQuery->sum('amount');
    }
    
    /**
     * Reset the form fields
     */
    public function resetForm()
    {
        $this->form = [
            'finance_account_id' => '',
            'votehead_id' => '',
            'amount' => '',
            'description' => '',
            'academic_year' => '',
            'term' => '',
            'is_approved' => false,
        ];
        $this->editAllocationId = null;
        $this->resetValidation();
    }
    
    /**
     * Update available voteheads when account is selected
     */
    public function updatedFormFinanceAccountId()
    {
        if (!$this->form['finance_account_id']) {
            $this->availableVoteheads = [];
            $this->accountBalance = 0;
            $this->accountName = '';
            return;
        }
        
        $account = FinanceAccount::find($this->form['finance_account_id']);
        
        if ($account) {
            $this->accountName = $account->name;
            $this->accountBalance = $account->current_balance;
            
            // Get all voteheads available for this account
            $this->availableVoteheads = AccountVotehead::where('is_active', true)
                ->where(function($query) use ($account) {
                    $query->where('finance_account_id', $account->id)
                          ->orWhereNull('finance_account_id');
                })
                ->orderBy('name')
                ->get();
        } else {
            $this->availableVoteheads = [];
            $this->accountBalance = 0;
            $this->accountName = '';
        }
        
        // Reset votehead selection
        $this->form['votehead_id'] = '';
    }
    
    /**
     * Update votehead details when a votehead is selected
     */
    public function updatedFormVoteheadId()
    {
        if (!$this->form['votehead_id']) {
            $this->voteheadName = '';
            return;
        }
        
        $votehead = AccountVotehead::find($this->form['votehead_id']);
        if ($votehead) {
            $this->voteheadName = $votehead->name;
        }
    }
    
    /**
     * Open the create/edit modal
     */
    public function openAllocationModal()
    {
        // Close the delete modal if it's open
        $this->showDeleteModal = false;
        
        $this->resetForm();
        $this->isEditing = true;
        $this->showAllocationModal = true;
        
        // Pre-fill with current term data
        if (date('n') >= 1 && date('n') <= 4) {
            $this->form['academic_year'] = date('Y');
            $this->form['term'] = 1;
        } elseif (date('n') >= 5 && date('n') <= 8) {
            $this->form['academic_year'] = date('Y');
            $this->form['term'] = 2;
        } else {
            $this->form['academic_year'] = date('Y');
            $this->form['term'] = 3;
        }
    }
    
    /**
     * Close the modal
     */
    public function closeAllocationModal()
    {
        $this->showAllocationModal = false;
        $this->editAllocationId = null;
        $this->resetForm();
        $this->resetValidation();
        $this->availableVoteheads = [];
        $this->accountBalance = 0;
        $this->accountName = '';
    }
    
    /**
     * Validate the form data
     */
    public function validate($rules = null, $messages = [], $attributes = [])
    {
        if ($rules === null) {
            $rules = [
                'form.finance_account_id' => 'required|exists:finance_accounts,id',
                'form.votehead_id' => 'required|exists:account_voteheads,id',
                'form.amount' => 'required|numeric|min:0',
                'form.description' => 'nullable|string|max:500',
                'form.academic_year' => 'required|numeric|min:2020|max:2050',
                'form.term' => 'required|numeric|in:1,2,3',
            ];
        }
        
        return parent::validate($rules, $messages, $attributes);
    }
    
    /**
     * Create a new allocation
     */
    public function createAllocation()
    {
        $this->validate();
        
        DB::beginTransaction();
        try {
            // Create the allocation
            FeeAllocation::create([
                'finance_account_id' => $this->form['finance_account_id'],
                'votehead_id' => $this->form['votehead_id'],
                'amount' => $this->form['amount'],
                'description' => $this->form['description'],
                'academic_year' => $this->form['academic_year'],
                'term' => $this->form['term'],
                'is_approved' => false,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();
            $this->closeAllocationModal();
            $this->loadStatistics(); // Refresh statistics
            $this->dispatch('toast', 'Fee allocation created successfully!', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', 'Error creating fee allocation: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Load an allocation for editing
     */
    public function editAllocation($allocationId)
    {
        // Close the delete modal if it's open
        $this->showDeleteModal = false;
        
        $this->resetValidation();
        $this->editAllocationId = $allocationId;
        
        $allocation = FeeAllocation::findOrFail($allocationId);
        
        $this->form = [
            'finance_account_id' => $allocation->finance_account_id,
            'votehead_id' => $allocation->votehead_id,
            'amount' => $allocation->amount,
            'description' => $allocation->description,
            'academic_year' => $allocation->academic_year,
            'term' => $allocation->term,
            'is_approved' => $allocation->is_approved,
        ];
        
        // Load available voteheads for this account
        $this->updatedFormFinanceAccountId();
        
        // Set as editing mode
        $this->isEditing = true;
        $this->showAllocationModal = true;
    }
    
    /**
     * Update an existing allocation
     */
    public function updateAllocation()
    {
        $this->validate();
        
        DB::beginTransaction();
        try {
            $allocation = FeeAllocation::findOrFail($this->editAllocationId);
            
            // Only allow updates if allocation is pending
            if ($allocation->is_approved) {
                $this->dispatch('toast', 'Cannot update an approved allocation', 'error');
                return;
            }
            
            $allocation->update([
                'finance_account_id' => $this->form['finance_account_id'],
                'votehead_id' => $this->form['votehead_id'],
                'amount' => $this->form['amount'],
                'description' => $this->form['description'],
                'academic_year' => $this->form['academic_year'],
                'term' => $this->form['term'],
                'updated_by' => auth()->id(),
            ]);
            
            DB::commit();
            $this->closeAllocationModal();
            $this->loadStatistics(); // Refresh statistics
            $this->dispatch('toast', 'Fee allocation updated successfully!', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', 'Error updating fee allocation: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Delete an allocation
     */
    public function confirmDeleteAllocation($allocationId)
    {
        // Close the allocation modal if it's open
        $this->showAllocationModal = false;
        
        // Set the ID and show the delete modal
        $this->deleteAllocationId = $allocationId;
        $this->showDeleteModal = true;
    }
    
    /**
     * Close delete modal
     */
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteAllocationId = null;
    }
    
    /**
     * Delete an allocation
     */
    public function deleteAllocation()
    {
        DB::beginTransaction();
        try {
            $allocation = FeeAllocation::findOrFail($this->deleteAllocationId);
            
            // Only allow deletion if allocation is pending
            if ($allocation->is_approved) {
                $this->dispatch('toast', 'Cannot delete an approved allocation', 'error');
                return;
            }
            
            $allocation->delete();
            
            DB::commit();
            $this->closeDeleteModal();
            $this->loadStatistics(); // Refresh statistics
            $this->dispatch('toast', 'Fee allocation deleted successfully!', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', 'Error deleting fee allocation: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Approve an allocation
     */
    public function approveAllocation($allocationId)
    {
        DB::beginTransaction();
        try {
            $allocation = FeeAllocation::findOrFail($allocationId);
            
            // Only allow approval if allocation is pending
            if ($allocation->is_approved) {
                $this->dispatch('toast', 'This allocation is already approved', 'error');
                return;
            }
            
            // Update the allocation status
            $allocation->update([
                'is_approved' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
            
            // Update the votehead's amount allocation
            $votehead = AccountVotehead::findOrFail($allocation->votehead_id);
            $votehead->update([
                'allocated_amount' => $votehead->allocated_amount + $allocation->amount
            ]);
            
            DB::commit();
            $this->loadStatistics(); // Refresh statistics
            $this->dispatch('toast', 'Fee allocation approved successfully!', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', 'Error approving fee allocation: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Export allocations
     */
    public function exportAllocations()
    {
        // You can implement export functionality here
        $this->dispatch('toast', 'Export feature will be implemented soon!', 'info');
    }
    
    /**
     * Apply filters
     */
    public function applyFilters()
    {
        $this->resetPage();
        $this->loadStatistics();
    }
    
    /**
     * Reset filters
     */
    public function resetFilters()
    {
        $this->search = '';
        $this->financeAccountFilter = '';
        $this->voteheadFilter = '';
        $this->statusFilter = '';
        $this->yearFilter = '';
        $this->termFilter = '';
        $this->resetPage();
        $this->loadStatistics();
    }
    
    /**
     * Reset period filters
     */
    public function resetPeriodFilters()
    {
        $this->yearFilter = '';
        $this->termFilter = '';
        $this->resetPage();
        $this->loadStatistics();
    }
    
    /**
     * Update properties when filters change
     */
    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedFinanceAccountFilter()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedVoteheadFilter()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedYearFilter()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedTermFilter()
    {
        $this->resetPage();
        $this->loadStatistics();
    }
    
    /**
     * View allocation details
     */
    public function viewAllocation($allocationId)
    {
        // Close the delete modal if it's open
        $this->showDeleteModal = false;
        
        $allocation = FeeAllocation::findOrFail($allocationId);
        
        // Set the form data for view only
        $this->form = [
            'finance_account_id' => $allocation->finance_account_id,
            'votehead_id' => $allocation->votehead_id,
            'amount' => $allocation->amount,
            'description' => $allocation->description,
            'academic_year' => $allocation->academic_year,
            'term' => $allocation->term,
            'is_approved' => $allocation->is_approved,
        ];
        
        // Load available voteheads for context
        $this->updatedFormFinanceAccountId();
        
        // Set as viewing mode (not editing)
        $this->isEditing = false;
        $this->editAllocationId = $allocationId;
        $this->showAllocationModal = true;
    }
    
    /**
     * Render the component
     */
    public function render()
    {
        $financeAccounts = FinanceAccount::where('is_active', true)->get();
        $voteheads = AccountVotehead::where('is_active', true)->get();
        
        // Get all academic years
        $currentYear = (int)date('Y');
        $academicYears = range($currentYear - 1, $currentYear + 1);
        
        // Build the query
        $allocationsQuery = FeeAllocation::query()
            ->with(['financeAccount', 'votehead']);
        
        if ($this->search) {
            $allocationsQuery->where(function($q) {
                $q->whereHas('financeAccount', function ($subq) {
                    $subq->where('name', 'like', "%{$this->search}%");
                })->orWhereHas('votehead', function ($subq) {
                    $subq->where('name', 'like', "%{$this->search}%");
                })->orWhere('description', 'like', "%{$this->search}%");
            });
        }
        
        if ($this->financeAccountFilter) {
            $allocationsQuery->where('finance_account_id', $this->financeAccountFilter);
        }
        
        if ($this->voteheadFilter) {
            $allocationsQuery->where('votehead_id', $this->voteheadFilter);
        }
        
        if ($this->statusFilter !== '') {
            $allocationsQuery->where('is_approved', $this->statusFilter == '1');
        }
        
        if ($this->yearFilter) {
            $allocationsQuery->where('academic_year', $this->yearFilter);
        }
        
        if ($this->termFilter) {
            $allocationsQuery->where('term', $this->termFilter);
        }
        
        if ($this->sortField) {
            $allocationsQuery->orderBy($this->sortField, $this->sortDirection);
        } else {
            $allocationsQuery->orderBy('created_at', 'desc');
        }
        
        $allocations = $allocationsQuery->paginate(10);
        
        return view('livewire.finance.fee-allocations', [
            'allocations' => $allocations,
            'financeAccounts' => $financeAccounts,
            'voteheads' => $voteheads,
            'academicYears' => $academicYears,
            'terms' => [1, 2, 3],
        ]);
    }
} 