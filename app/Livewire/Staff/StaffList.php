<?php

namespace App\Livewire\Staff;

use App\User;
use App\Models\StaffRecord;
use App\Helpers\Qs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class StaffList extends Component
{
    use WithPagination, WithFileUploads;
    
    // List properties
    public $perPage = 10;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedRole = '';
    public $selectedStatus = null;
    public $showFilters = false;
    
    // Modal properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showViewModal = false;
    public $showDeleteModal = false;
    
    // Staff form properties
    public $staffId;
    public $name;
    public $email;
    public $phone;
    public $gender;
    public $address;
    public $role;
    public $empDate;
    public $qualification;
    public $experience;
    public $departments;
    public $isActive = true;
    public $password;
    public $photo;
    
    // For viewing staff
    public $viewingStaff;
    
    protected function queryString()
    {
        return [
            'search' => ['except' => ''],
            'selectedRole' => ['except' => ''],
            'selectedStatus' => ['except' => null],
            'sortField' => ['except' => 'name'],
            'sortDirection' => ['except' => 'asc'],
        ];
    }
    
    public function mount()
    {
        $this->resetStaffFormFields();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->selectedRole = '';
        $this->selectedStatus = null;
        $this->resetPage();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function openCreateModal()
    {
        $this->resetValidation();
        $this->resetStaffFormFields();
        $this->showCreateModal = true;
    }
    
    public function editStaff($staffId)
    {
        $this->resetValidation();
        $this->resetStaffFormFields();
        
        $this->staffId = $staffId;
        $staff = User::with('roles')->findOrFail($staffId);
        
        $this->name = $staff->name;
        $this->email = $staff->email;
        $this->phone = $staff->phone;
        $this->gender = $staff->gender;
        $this->address = $staff->address;
        $this->role = $staff->roles->first() ? $staff->roles->first()->name : null;
        $this->empDate = $staff->emp_date;
        $this->qualification = $staff->qualification;
        $this->experience = $staff->experience;
        $this->departments = $staff->departments;
        $this->isActive = $staff->is_active;
        
        $this->showEditModal = true;
    }
    
    public function viewStaff($staffId)
    {
        try {
            $this->viewingStaff = User::with('roles')->findOrFail($staffId);
            $this->showViewModal = true;
        } catch (\Exception $e) {
            session()->flash('message', 'Staff member not found.');
            session()->flash('type', 'error');
        }
    }
    
    public function confirmDelete($staffId)
    {
        $this->staffId = $staffId;
        $this->showDeleteModal = true;
    }
    
    public function resetStaffFormFields()
    {
        $this->reset([
            'staffId', 'name', 'email', 'phone', 'gender', 'address', 
            'role', 'empDate', 'qualification', 'experience', 'departments', 
            'isActive', 'password', 'photo'
        ]);
        
        // Set defaults
        $this->isActive = true;
    }
    
    public function createStaff()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:Male,Female',
            'address' => 'nullable|string|max:500',
            'role' => 'required|exists:roles,name',
            'empDate' => 'nullable|date',
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|string',
            'departments' => 'nullable|string|max:255',
            'isActive' => 'boolean',
            'password' => 'required|min:6',
            'photo' => 'nullable|image|max:1024',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create user
            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->gender = $this->gender;
            $user->address = $this->address;
            $user->password = Hash::make($this->password);
            
            // Handle photo upload
            if ($this->photo) {
                $photoPath = $this->photo->store('profile-photos', 'public');
                $user->profile_photo_path = $photoPath;
            }
            
            $user->save();
            
            // Assign role using Spatie
            $user->assignRole($this->role);
            
            // Add staff details
            $user->emp_date = $this->empDate;
            $user->qualification = $this->qualification;
            $user->experience = $this->experience;
            $user->departments = $this->departments;
            $user->is_active = $this->isActive;
            $user->save();
            
            DB::commit();
            
            session()->flash('message', 'Staff member created successfully!');
            session()->flash('type', 'success');
            
            $this->showCreateModal = false;
            $this->resetStaffFormFields();
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('message', 'Error creating staff member: ' . $e->getMessage());
            session()->flash('type', 'error');
        }
    }
    
    public function updateStaff()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->staffId,
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:Male,Female',
            'address' => 'nullable|string|max:500',
            'role' => 'required|exists:roles,name',
            'empDate' => 'nullable|date',
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|string',
            'departments' => 'nullable|string|max:255',
            'isActive' => 'boolean',
            'password' => 'nullable|min:6',
            'photo' => 'nullable|image|max:1024',
        ]);
        
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($this->staffId);
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->gender = $this->gender;
            $user->address = $this->address;
            
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            
            // Handle photo upload
            if ($this->photo) {
                $photoPath = $this->photo->store('profile-photos', 'public');
                $user->profile_photo_path = $photoPath;
            }
            
            // Update role using Spatie
            $user->syncRoles([$this->role]);
            
            // Update staff details
            $user->emp_date = $this->empDate;
            $user->qualification = $this->qualification;
            $user->experience = $this->experience;
            $user->departments = $this->departments;
            $user->is_active = $this->isActive;
            $user->save();
            
            DB::commit();
            
            session()->flash('message', 'Staff member updated successfully!');
            session()->flash('type', 'success');
            
            $this->showEditModal = false;
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('message', 'Error updating staff member: ' . $e->getMessage());
            session()->flash('type', 'error');
        }
    }
    
    public function deleteStaff()
    {
        try {
            $user = User::findOrFail($this->staffId);
            $user->delete();
            
            session()->flash('message', 'Staff member deleted successfully!');
            session()->flash('type', 'success');
            
            $this->showDeleteModal = false;
            
        } catch (\Exception $e) {
            session()->flash('message', 'Error deleting staff member: ' . $e->getMessage());
            session()->flash('type', 'error');
        }
    }
    
    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewingStaff = null;
    }
    
    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetValidation();
        $this->resetStaffFormFields();
    }
    
    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetValidation();
        $this->resetStaffFormFields();
    }
    
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->staffId = null;
    }
    
    public function render()
    {
        // Get all roles that are for staff members
        $roles = Role::whereIn('name', Qs::getStaffRoles())->get();
        
        $query = User::query()->with('roles');
        
        // Add staff user types filter - we only want staff members, not students or parents
        $query->whereHas('roles', function($q) {
            $q->whereIn('name', Qs::getStaffRoles());
        });
        
        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }
        
        // Role filter
        if ($this->selectedRole) {
            $query->whereHas('roles', function($q) {
                $q->where('name', $this->selectedRole);
            });
        }
        
        // Status filter
        if (!is_null($this->selectedStatus)) {
            $query->where('is_active', $this->selectedStatus === 'active');
        }
        
        // Sort
        $query->orderBy($this->sortField, $this->sortDirection);
        
        $staff = $query->paginate($this->perPage);
        
        return view('livewire.staff.staff-list', [
            'staff' => $staff,
            'roles' => $roles,
        ]);
    }
} 