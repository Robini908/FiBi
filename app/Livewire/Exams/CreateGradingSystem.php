<?php

declare(strict_types=1);

namespace App\Livewire\Exams;

use App\Models\GradingSystem;
use App\Models\GradeRange;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreateGradingSystem extends Component
{
    public string $name = '';
    public string $description = '';
    public bool $is_active = true;
    
    public array $gradeRanges = [];
    
    protected $listeners = ['addGradeRange', 'removeGradeRange'];
    
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:grading_systems,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
            'gradeRanges' => ['required', 'array', 'min:1'],
            'gradeRanges.*.grade' => ['required', 'string', 'max:10'],
            'gradeRanges.*.min_mark' => ['required', 'numeric', 'min:0', 'max:100'],
            'gradeRanges.*.max_mark' => ['required', 'numeric', 'min:0', 'max:100', 'gte:gradeRanges.*.min_mark'],
            'gradeRanges.*.description' => ['nullable', 'string', 'max:255'],
        ];
    }
    
    public function mount(): void
    {
        $this->addGradeRange();
    }
    
    public function addGradeRange(): void
    {
        $this->gradeRanges[] = [
            'grade' => '',
            'min_mark' => 0,
            'max_mark' => 0,
            'description' => '',
        ];
    }
    
    public function removeGradeRange(int $index): void
    {
        if (count($this->gradeRanges) > 1) {
            unset($this->gradeRanges[$index]);
            $this->gradeRanges = array_values($this->gradeRanges);
        }
    }
    
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }
    
    public function save()
    {
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            // Create grading system
            $gradingSystem = new GradingSystem([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            $gradingSystem->save();
            
            // Create grade ranges
            foreach ($this->gradeRanges as $range) {
                $gradeRange = new GradeRange([
                    'grade' => $range['grade'],
                    'min_mark' => $range['min_mark'],
                    'max_mark' => $range['max_mark'],
                    'description' => $range['description'],
                ]);
                $gradingSystem->gradeRanges()->save($gradeRange);
            }
            
            DB::commit();
            
            session()->flash('success', 'Grading system created successfully.');
            return redirect()->route('exams.grading-systems.show', $gradingSystem->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create grading system: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.exams.create-grading-system');
    }
} 