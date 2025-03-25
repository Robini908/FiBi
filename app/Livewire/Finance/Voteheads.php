<?php

namespace App\Livewire\Finance;

use App\Models\AccountVotehead;
use App\Models\FinanceAccount;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class Voteheads extends Component
{
    use WithPagination, WireToast;
    
    // Component properties
    public $showModal = false;
    public $isEditing = false;
    public $voteheadId = null;
    public $search = '';
    public $accountFilter = '';
    public $yearFilter = '';
    public $termFilter = '';
    
    // Form fields
    public $finance_account_id;
    public $name;
    public $code;
    public $description;
    public $allocated_amount = 0;
    public $academic_year;
    public $term;
    public $is_active = true;
    
    // Validation rules
    protected $rules = [
        'finance_account_id' => 'required|exists:finance_accounts,id',
        'name' => 'required|string|max:100',
        'code' => 'required|string|max:20|unique:account_voteheads,code',
        'description' => 'nullable|string',
        'allocated_amount' => 'required|numeric|min:0',
        'academic_year' => 'required|integer|min:2000|max:2100',
        'term' => 'required|integer|min:1|max:3',
        'is_active' => 'boolean',
    ];
    
    // Listeners
    protected $listeners = ['deleteVotehead', 'showVotehead'];
    
    public function mount()
    {
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
     * Reset the form fields
     */
    public function resetForm()
    {
        $this->reset([
            'finance_account_id', 'name', 'code', 'description', 'allocated_amount', 
            'is_active', 'isEditing', 'voteheadId'
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
        $this->is_active = true;
    }
    
    /**
     * Open the create/edit modal
     */
    public function openModal()
    {
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
     * Create or update a votehead
     */
    public function saveVotehead()
    {
        if ($this->isEditing) {
            $this->updateVotehead();
            return;
        }
        
        $this->validate();
        
        try {
            // Check if the account exists and is active
            $account = FinanceAccount::findOrFail($this->finance_account_id);
            if (!$account->is_active) {
                toast()->warning('Cannot add votehead to an inactive account')->push();
                return;
            }
            
            // Create the votehead
            $votehead = AccountVotehead::create([
                'finance_account_id' => $this->finance_account_id,
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'allocated_amount' => $this->allocated_amount,
                'spent_amount' => 0, // Initially no money spent
                'balance' => $this->allocated_amount, // Initial balance equals allocated amount
                'academic_year' => $this->academic_year,
                'term' => $this->term,
                'is_active' => $this->is_active,
                'created_by' => auth()->user()->name,
            ]);
            
            $this->closeModal();
            toast()->success("Votehead '{$votehead->name}' created successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error creating votehead: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Load a votehead for editing
     */
    public function editVotehead($id)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->voteheadId = $id;
        
        try {
            $votehead = AccountVotehead::findOrFail($id);
            
            $this->finance_account_id = $votehead->finance_account_id;
            $this->name = $votehead->name;
            $this->code = $votehead->code;
            $this->description = $votehead->description;
            $this->allocated_amount = $votehead->allocated_amount;
            $this->academic_year = $votehead->academic_year;
            $this->term = $votehead->term;
            $this->is_active = $votehead->is_active;
            
            // Update validation rules for unique code
            $this->rules['code'] = "required|string|max:20|unique:account_voteheads,code,{$id}";
            
            $this->showModal = true;
        } catch (\Exception $e) {
            toast()->danger('Error loading votehead: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Update an existing votehead
     */
    public function updateVotehead()
    {
        $this->validate();
        
        try {
            $votehead = AccountVotehead::findOrFail($this->voteheadId);
            
            // Store original allocated amount
            $originalAllocatedAmount = $votehead->allocated_amount;
            
            $votehead->update([
                'finance_account_id' => $this->finance_account_id,
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'allocated_amount' => $this->allocated_amount,
                'is_active' => $this->is_active,
                'academic_year' => $this->academic_year,
                'term' => $this->term,
                'updated_by' => auth()->user()->name,
            ]);
            
            // Adjust balance based on the change in allocated amount
            if ($originalAllocatedAmount != $this->allocated_amount) {
                $difference = $this->allocated_amount - $originalAllocatedAmount;
                $votehead->balance += $difference;
                $votehead->save();
            }
            
            $this->closeModal();
            toast()->success("Votehead '{$votehead->name}' updated successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error updating votehead: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Toggle votehead active status
     */
    public function toggleStatus($id)
    {
        try {
            $votehead = AccountVotehead::findOrFail($id);
            
            // Check if the votehead has transactions
            if (!$votehead->is_active && $votehead->spent_amount > 0) {
                toast()->warning("Cannot activate votehead with existing transactions")->push();
                return;
            }
            
            $votehead->is_active = !$votehead->is_active;
            $votehead->updated_by = auth()->user()->name;
            $votehead->save();
            
            $status = $votehead->is_active ? 'activated' : 'deactivated';
            toast()->success("Votehead '{$votehead->name}' {$status} successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error toggling votehead status: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Delete a votehead
     */
    public function deleteVotehead($id)
    {
        try {
            $votehead = AccountVotehead::findOrFail($id);
            
            // Check if the votehead has transactions
            if ($votehead->spent_amount > 0) {
                toast()->warning("Cannot delete votehead '{$votehead->name}' because it has transactions. Deactivate it instead.")->push();
                return;
            }
            
            // Check if the votehead has allocations
            if ($votehead->allocations()->count() > 0) {
                toast()->warning("Cannot delete votehead '{$votehead->name}' because it has allocations. Deactivate it instead.")->push();
                return;
            }
            
            $name = $votehead->name;
            $votehead->delete();
            
            toast()->success("Votehead '{$name}' deleted successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error deleting votehead: ' . $e->getMessage())->push();
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
        $this->reset(['accountFilter', 'yearFilter', 'termFilter', 'search']);
        $this->resetPage();
    }
    
    /**
     * Render the component
     */
    public function render()
    {
        // Get all active accounts for the dropdown
        $accounts = FinanceAccount::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        // Get academic years (unique years from voteheads)
        $years = AccountVotehead::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year')
            ->toArray();
            
        if (empty($years)) {
            $years = [date('Y')];
        }
        
        // Build the query
        $query = AccountVotehead::query()
            ->with('account'); // Eager load the account relation
            
        // Apply account filter
        if ($this->accountFilter) {
            $query->where('finance_account_id', $this->accountFilter);
        }
        
        // Apply year filter
        if ($this->yearFilter) {
            $query->where('academic_year', $this->yearFilter);
        }
        
        // Apply term filter
        if ($this->termFilter) {
            $query->where('term', $this->termFilter);
        }
        
        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }
        
        // Get paginated results
        $voteheads = $query->orderBy('name')->paginate(10);
            
        return view('livewire.finance.voteheads', [
            'voteheads' => $voteheads,
            'accounts' => $accounts,
            'years' => $years,
        ]);
    }
}
