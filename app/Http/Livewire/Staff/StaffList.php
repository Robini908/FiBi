<?php

namespace App\Http\Livewire\Staff;

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
use UserNotNull\Toast\Concerns\WireToast;

class StaffList extends Component
{
    use WithPagination, WithFileUploads, WireToast;
    
    // List properties
    public $perPage = 10;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $roleFilter = '';
    public $statusFilter = '';
    public $showFilters = false;
    
    // Create/Edit staff form properties
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteConfirmation = false;
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
    
    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];
    
    protected $listeners = [
        'staffUpdated' => '$refresh',
        'staffCreated' => '$refresh',
        'refreshStaffList' => '$refresh',
        'deleteConfirmed' => 'deleteStaff',
    ];
    
    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|email|max:255',
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
    ];
    
    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
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
        $this->reset(['search', 'roleFilter', 'statusFilter']);
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingRoleFilter()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    public function openCreateModal()
    {
        $this->resetValidation();
        $this->resetStaffFormFields();
        $this->showCreateModal = true;
    }
    
    public function openEditModal($staffId)
    {
        $this->resetValidation();
        $this->resetStaffFormFields();
        
        $this->staffId = $staffId;
        $staff = StaffRecord::with('user')->findOrFail($staffId);
        $user = $staff->user;
        
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->gender = $user->gender;
        $this->address = $user->address;
        $this->role = $user->roles->first()->name ?? null;
        $this->empDate = $staff->emp_date;
        $this->qualification = $staff->qualification;
        $this->experience = $staff->experience;
        $this->departments = $staff->departments;
        $this->isActive = $staff->is_active;
        
        $this->showEditModal = true;
    }
    
    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteConfirmation = false;
    }
    
    public function confirmDelete($staffId)
    {
        $this->staffId = $staffId;
        $this->showDeleteConfirmation = true;
    }
    
    public function deleteStaff()
    {
        try {
            $staff = StaffRecord::findOrFail($this->staffId);
            $user = $staff->user;
            
            // Delete staff record first, then user
            $staff->delete();
            $user->delete();
            
            $this->toast()->success('Staff member deleted successfully');
            $this->showDeleteConfirmation = false;
        } catch (\Exception $e) {
            $this->toast()->danger('Failed to delete staff member: ' . $e->getMessage());
        }
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
        $this->validate(array_merge($this->rules, [
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
        ]));
        
        try {
            DB::beginTransaction();
            
            // Create user
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'gender' => $this->gender,
                'address' => $this->address,
                'password' => Hash::make($this->password),
                'code' => strtoupper(Str::random(10)),
                'user_type' => $this->role, // For backward compatibility
            ];
            
            $user = User::create($userData);
            
            // Assign role
            $user->assignRole($this->role);
            
            // Create staff record
            $staffCode = Qs::getAppCode().'/STAFF/'.date('Y/m', strtotime($this->empDate)).'/'.mt_rand(1000, 9999);
            
            $staffData = [
                'user_id' => $user->id,
                'code' => $staffCode,
                'emp_date' => $this->empDate,
                'qualification' => $this->qualification,
                'experience' => $this->experience,
                'departments' => $this->departments,
                'is_active' => $this->isActive,
            ];
            
            StaffRecord::create($staffData);
            
            // Handle photo upload if provided
            if ($this->photo) {
                $photoPath = $this->photo->store('uploads/staff', 'public');
                $user->photo = asset('storage/' . $photoPath);
                $user->save();
            }
            
            DB::commit();
            
            $this->toast()->success('Staff member created successfully');
            $this->closeModals();
            $this->dispatch('staffCreated');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->toast()->danger('Failed to create staff member: ' . $e->getMessage());
        }
    }
    
    public function updateStaff()
    {
        $this->validate(array_merge($this->rules, [
            'email' => 'required|email|max:255|unique:users,email,' . StaffRecord::find($this->staffId)->user_id,
        ]));
        
        try {
            DB::beginTransaction();
            
            $staff = StaffRecord::findOrFail($this->staffId);
            $user = $staff->user;
            
            // Update user data
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->gender = $this->gender;
            $user->address = $this->address;
            $user->user_type = $this->role; // For backward compatibility
            
            if (!empty($this->password)) {
                $user->password = Hash::make($this->password);
            }
            
            // Handle photo upload if provided
            if ($this->photo) {
                $photoPath = $this->photo->store('uploads/staff', 'public');
                $user->photo = asset('storage/' . $photoPath);
            }
            
            $user->save();
            
            // Update role
            $user->syncRoles($this->role);
            
            // Update staff record
            $staff->emp_date = $this->empDate;
            $staff->qualification = $this->qualification;
            $staff->experience = $this->experience;
            $staff->departments = $this->departments;
            $staff->is_active = $this->isActive;
            $staff->save();
            
            DB::commit();
            
            $this->toast()->success('Staff member updated successfully');
            $this->closeModals();
            $this->dispatch('staffUpdated');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->toast()->danger('Failed to update staff member: ' . $e->getMessage());
        }
    }
    
    public function viewStaffDetails($staffId)
    {
        return redirect()->route('staff.show', $staffId);
    }
    
    public function render()
    {
        $query = User::query()
            ->select('users.*')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->whereIn('roles.name', Qs::getStaffRoles())
            ->leftJoin('staff_records', 'users.id', '=', 'staff_records.user_id')
            ->where(function($q) {
                $q->where('users.name', 'like', '%' . $this->search . '%')
                    ->orWhere('users.email', 'like', '%' . $this->search . '%')
                    ->orWhere('users.phone', 'like', '%' . $this->search . '%')
                    ->orWhere('staff_records.code', 'like', '%' . $this->search . '%');
            });
        
        if ($this->roleFilter) {
            $query->where('roles.name', $this->roleFilter);
        }
        
        if ($this->statusFilter) {
            if ($this->statusFilter === 'active') {
                $query->where('staff_records.is_active', true);
            } elseif ($this->statusFilter === 'inactive') {
                $query->where('staff_records.is_active', false);
            }
        }
        
        if ($this->sortField === 'name') {
            $query->orderBy('users.name', $this->sortDirection);
        } elseif ($this->sortField === 'email') {
            $query->orderBy('users.email', $this->sortDirection);
        } elseif ($this->sortField === 'role') {
            $query->orderBy('roles.name', $this->sortDirection);
        } elseif ($this->sortField === 'code') {
            $query->orderBy('staff_records.code', $this->sortDirection);
        } elseif ($this->sortField === 'status') {
            $query->orderBy('staff_records.is_active', $this->sortDirection);
        }
        
        $staffUsers = $query->distinct()->paginate($this->perPage);
        
        // Get all roles for filtering
        $roles = Role::whereIn('name', Qs::getStaffRoles())->orderBy('name')->get();
        
        return view('livewire.staff.staff-list', [
            'staffUsers' => $staffUsers,
            'roles' => $roles,
        ]);
    }
} 