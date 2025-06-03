<?php

namespace App\Http\Livewire\Exams;

use App\Models\GradingSystem;
use Livewire\Component;
use Livewire\WithPagination;

class GradingSystemsList extends Component
{
    use WithPagination;
    
    public $perPage = 12;
    public $search = '';
    public $navigating = false;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 12],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $gradingSystems = GradingSystem::query()
            ->when($this->search, function($query) {
                return $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->with('gradeRanges')
            ->orderBy('effective_date', 'desc')
            ->paginate($this->perPage);
            
        return view('livewire.exams.grading-systems-list', [
            'gradingSystems' => $gradingSystems
        ]);
    }
    
    public function showGradingSystemForm()
    {
        $this->navigating = true;
        // The actual navigation is handled by Alpine.js
    }
    
    public function editGradingSystem($gradingSystemId)
    {
        $this->navigating = true;
        // The actual navigation is handled by Alpine.js
    }
    
    public function viewGradingSystem($gradingSystemId)
    {
        $this->navigating = true;
        // The actual navigation is handled by Alpine.js
    }
} 