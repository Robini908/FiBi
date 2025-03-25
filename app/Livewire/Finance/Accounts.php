<?php

namespace App\Livewire\Finance;

use App\Models\FinanceAccount;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Accounts extends Component
{
    use WithPagination, WireToast;
    
    // User role properties
    public $isAdmin = false;
    public $isAccountant = false;
    public $isParent = false;
    public $isStudent = false;
    
    // Component properties
    public $showModal = false;
    public $isEditing = false;
    public $accountId = null;
    public $search = '';
    
    // Form fields
    public $name;
    public $code;
    public $description;
    public $initial_balance = 0;
    public $is_active = true;
    
    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:100',
        'code' => 'required|string|max:20|unique:finance_accounts,code',
        'description' => 'nullable|string',
        'initial_balance' => 'nullable|numeric|min:0',
        'is_active' => 'boolean',
    ];
    
    // Listeners
    protected $listeners = ['deleteAccount', 'showAccount'];
    
    public function mount()
    {
        // Set user role flags
        $this->isAdmin = Qs::isAdministrator() || Qs::isAdmin();
        $this->isAccountant = Qs::isAccountant();
        $this->isParent = Qs::isParent();
        $this->isStudent = Qs::isStudent();
        
        // Check if the user has permission to access this component
        if (!$this->isAdmin && !$this->isAccountant) {
            $this->redirectRoute('dashboard');
            toast()->warning('You do not have permission to access finance accounts')->push();
        }
    }
    
    /**
     * Reset the form fields
     */
    public function resetForm()
    {
        $this->reset([
            'name', 'code', 'description', 'initial_balance', 
            'is_active', 'isEditing', 'accountId'
        ]);
        
        $this->resetValidation();
    }
    
    /**
     * Open the create/edit modal
     */
    public function openModal()
    {
        // Check permission
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()->warning('You do not have permission to create finance accounts')->push();
            return;
        }
        
        $this->resetForm();
        // Generate a new code for new accounts
        $this->generateCode();
        $this->showModal = true;
    }
    
    /**
     * Generate a unique account code
     */
    private function generateCode()
    {
        if (!$this->isEditing) {
            // Set a prefix for finance accounts
            $prefix = 'FA';
            
            // Get current year and month
            $year = date('y');
            $month = date('m');
            
            // Get the last account with this prefix pattern
            $lastAccount = FinanceAccount::where('code', 'like', "{$prefix}{$year}{$month}%")
                ->orderBy('id', 'desc')
                ->first();
                
            if ($lastAccount) {
                // Extract the sequential number and increment it
                $lastNumber = (int) substr($lastAccount->code, strlen($prefix . $year . $month));
                $nextNumber = $lastNumber + 1;
            } else {
                // If no existing accounts with this pattern, start with 1
                $nextNumber = 1;
            }
            
            // Format the new code (e.g. FA2304001 for first account created in April 2023)
            $this->code = $prefix . $year . $month . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }
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
     * Create a new finance account
     */
    public function createAccount()
    {
        // Check permission
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()->warning('You do not have permission to create finance accounts')->push();
            return;
        }
        
        if ($this->isEditing) {
            $this->updateAccount();
            return;
        }
        
        $this->validate();
        
        try {
            $account = FinanceAccount::create([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'initial_balance' => $this->initial_balance,
                'current_balance' => $this->initial_balance,
                'is_active' => $this->is_active,
                'created_by' => auth()->user()->name,
            ]);
            
            $this->closeModal();
            toast()->success("Account '{$account->name}' created successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error creating account: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Load an account for editing
     */
    public function editAccount($id)
    {
        // Check permission
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()->warning('You do not have permission to edit finance accounts')->push();
            return;
        }
        
        $this->resetForm();
        $this->isEditing = true;
        $this->accountId = $id;
        
        try {
            $account = FinanceAccount::findOrFail($id);
            
            $this->name = $account->name;
            $this->code = $account->code;
            $this->description = $account->description;
            $this->initial_balance = $account->initial_balance;
            $this->is_active = $account->is_active;
            
            // Update validation rules for unique code
            $this->rules['code'] = "required|string|max:20|unique:finance_accounts,code,{$id}";
            
            $this->showModal = true;
        } catch (\Exception $e) {
            toast()->danger('Error loading account: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Update an existing finance account
     */
    public function updateAccount()
    {
        // Check permission
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()->warning('You do not have permission to update finance accounts')->push();
            return;
        }
        
        $this->validate();
        
        try {
            $account = FinanceAccount::findOrFail($this->accountId);
            
            // Store the old balance to calculate the difference
            $oldInitialBalance = $account->initial_balance;
            
            $account->update([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'initial_balance' => $this->initial_balance,
                'is_active' => $this->is_active,
                'updated_by' => auth()->user()->name,
            ]);
            
            // Update current balance if initial balance changed
            if ($oldInitialBalance != $this->initial_balance) {
                $difference = $this->initial_balance - $oldInitialBalance;
                $account->current_balance += $difference;
                $account->save();
            }
            
            $this->closeModal();
            toast()->success("Account '{$account->name}' updated successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error updating account: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Toggle account active status
     */
    public function toggleStatus($id)
    {
        // Check permission
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()->warning('You do not have permission to change account status')->push();
            return;
        }
        
        try {
            $account = FinanceAccount::findOrFail($id);
            $account->is_active = !$account->is_active;
            $account->updated_by = auth()->user()->name;
            $account->save();
            
            $status = $account->is_active ? 'activated' : 'deactivated';
            toast()->success("Account '{$account->name}' {$status} successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error toggling account status: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Show account details
     */
    public function showAccount($id)
    {
        // Redirect to account details page
        return redirect()->route('finance.accounts.show', ['id' => $id]);
    }
    
    /**
     * Delete an account
     */
    public function deleteAccount($id)
    {
        // Check permission
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()->warning('You do not have permission to delete finance accounts')->push();
            return;
        }
        
        try {
            $account = FinanceAccount::findOrFail($id);
            
            // Check if the account has voteheads
            if ($account->voteheads()->count() > 0) {
                toast()->warning("Cannot delete account '{$account->name}' because it has voteheads. Deactivate it instead.")->push();
                return;
            }
            
            $name = $account->name;
            $account->delete();
            
            toast()->success("Account '{$name}' deleted successfully")->push();
        } catch (\Exception $e) {
            toast()->danger('Error deleting account: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Calculate summary metrics for accounts
     */
    private function calculateAccountSummary()
    {
        $totalAccounts = FinanceAccount::count();
        $activeAccounts = FinanceAccount::where('is_active', true)->count();
        $totalBalance = FinanceAccount::sum('current_balance');
        
        return [
            'totalAccounts' => $totalAccounts,
            'activeAccounts' => $activeAccounts,
            'totalBalance' => $totalBalance
        ];
    }
    
    /**
     * Render the component
     */
    public function render()
    {
        // Check access permission again (in case of direct Livewire access)
        if (!$this->isAdmin && !$this->isAccountant) {
            return view('livewire.finance.access-denied');
        }
        
        // Build the query
        $accounts = FinanceAccount::where(function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('code', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate(10);
        
        // Calculate summary metrics
        $summary = $this->calculateAccountSummary();
            
        return view('livewire.finance.accounts', [
            'accounts' => $accounts,
            'summary' => $summary,
            'isAdmin' => $this->isAdmin,
            'isAccountant' => $this->isAccountant
        ]);
    }
}
