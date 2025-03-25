<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\DemoRequest;
use Livewire\WithPagination;
use Carbon\Carbon;

class DemoRequests extends Component
{
    use WithPagination;
    
    public $search = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    public $isModalOpen = false;
    public $demoRequest = null;
    public $editingDemoRequest = [
        'id' => null,
        'name' => '',
        'email' => '',
        'phone' => '',
        'school' => '',
        'position' => '',
        'preferred_date' => '',
        'message' => '',
        'status' => '',
        'admin_notes' => '',
        'scheduled_at' => '',
    ];
    
    protected $listeners = ['refresh' => '$refresh'];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatus()
    {
        $this->resetPage();
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
    
    public function openModal($id = null)
    {
        if ($id) {
            $this->demoRequest = DemoRequest::find($id);
            $this->editingDemoRequest = [
                'id' => $this->demoRequest->id,
                'name' => $this->demoRequest->name,
                'email' => $this->demoRequest->email,
                'phone' => $this->demoRequest->phone,
                'school' => $this->demoRequest->school,
                'position' => $this->demoRequest->position,
                'preferred_date' => $this->demoRequest->preferred_date->format('Y-m-d'),
                'message' => $this->demoRequest->message,
                'status' => $this->demoRequest->status,
                'admin_notes' => $this->demoRequest->admin_notes ?? '',
                'scheduled_at' => $this->demoRequest->scheduled_at ? $this->demoRequest->scheduled_at->format('Y-m-d\TH:i') : '',
            ];
        } else {
            $this->demoRequest = null;
            $this->resetEditingDemoRequest();
        }
        
        $this->isModalOpen = true;
    }
    
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->demoRequest = null;
        $this->resetEditingDemoRequest();
    }
    
    public function resetEditingDemoRequest()
    {
        $this->editingDemoRequest = [
            'id' => null,
            'name' => '',
            'email' => '',
            'phone' => '',
            'school' => '',
            'position' => '',
            'preferred_date' => '',
            'message' => '',
            'status' => 'pending',
            'admin_notes' => '',
            'scheduled_at' => '',
        ];
    }
    
    public function updateDemoRequest()
    {
        $this->validate([
            'editingDemoRequest.name' => 'required|string|min:3|max:255',
            'editingDemoRequest.email' => 'required|email|max:255',
            'editingDemoRequest.phone' => 'required|string|min:10|max:20',
            'editingDemoRequest.school' => 'required|string|min:3|max:255',
            'editingDemoRequest.position' => 'required|string|min:3|max:255',
            'editingDemoRequest.preferred_date' => 'required|date',
            'editingDemoRequest.status' => 'required|in:pending,scheduled,completed,cancelled',
            'editingDemoRequest.admin_notes' => 'nullable|string|max:1000',
            'editingDemoRequest.scheduled_at' => 'nullable|date_format:Y-m-d\TH:i',
        ]);
        
        $demoRequest = DemoRequest::find($this->editingDemoRequest['id']);
        
        // Mark as completed if status changed
        $completed_at = null;
        if ($demoRequest->status !== 'completed' && $this->editingDemoRequest['status'] === 'completed') {
            $completed_at = now();
        } elseif ($demoRequest->status === 'completed' && $this->editingDemoRequest['status'] !== 'completed') {
            $completed_at = null;
        } else {
            $completed_at = $demoRequest->completed_at;
        }
        
        $demoRequest->update([
            'name' => $this->editingDemoRequest['name'],
            'email' => $this->editingDemoRequest['email'],
            'phone' => $this->editingDemoRequest['phone'],
            'school' => $this->editingDemoRequest['school'],
            'position' => $this->editingDemoRequest['position'],
            'preferred_date' => $this->editingDemoRequest['preferred_date'],
            'message' => $this->editingDemoRequest['message'],
            'status' => $this->editingDemoRequest['status'],
            'admin_notes' => $this->editingDemoRequest['admin_notes'],
            'scheduled_at' => $this->editingDemoRequest['scheduled_at'] ? Carbon::parse($this->editingDemoRequest['scheduled_at']) : null,
            'completed_at' => $completed_at,
        ]);
        
        toast()->success('Demo request updated successfully!')->push();
        
        $this->closeModal();
    }
    
    public function render()
    {
        $demoRequests = DemoRequest::query()
            ->when($this->search, function($query) {
                $query->where(function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('school', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.admin.demo-requests', [
            'demoRequests' => $demoRequests
        ]);
    }
} 