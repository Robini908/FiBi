<?php

declare(strict_types=1);

namespace App\Livewire\Exams;

use App\Models\GradingSystem;
use App\Models\GradeRange;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EditGradingSystem extends Component
{
    public $gradingSystem;
    
    public string $name = '';
    public string $description = '';
    public bool $is_active = true;
    
    public array $gradeRanges = [];
    public bool $saving = false;
    
    protected $listeners = ['addGradeRange', 'removeGradeRange'];
    
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('grading_systems')->ignore($this->gradingSystem->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
            'gradeRanges' => ['required', 'array', 'min:1'],
            'gradeRanges.*.grade' => ['required', 'string', 'max:10'],
            'gradeRanges.*.min_mark' => ['required', 'numeric', 'min:0', 'max:100'],
            'gradeRanges.*.max_mark' => ['required', 'numeric', 'min:0', 'max:100', 'gte:gradeRanges.*.min_mark'],
            'gradeRanges.*.description' => ['nullable', 'string', 'max:255'],
        ];
    }
    
    public function mount(string|int $gradingSystem): void
    {
        // Handle the case where $gradingSystem might be the ID passed as a route parameter
        if (!$gradingSystem instanceof GradingSystem) {
            $this->gradingSystem = GradingSystem::findOrFail($gradingSystem);
        }
        
        $this->name = $this->gradingSystem->name;
        $this->description = $this->gradingSystem->description ?? '';
        $this->is_active = $this->gradingSystem->is_active;
        
        // Load existing grade ranges
        $this->loadGradeRanges();
    }
    
    private function loadGradeRanges(): void
    {
        $this->gradeRanges = $this->gradingSystem->gradeRanges()
            ->orderBy('min_mark')
            ->get()
            ->map(function ($range) {
                return [
                    'id' => $range->id,
                    'grade' => $range->grade,
                    'min_mark' => $range->min_mark,
                    'max_mark' => $range->max_mark,
                    'description' => $range->description ?? '',
                ];
            })
            ->toArray();
            
        // If no grade ranges exist, add an empty one
        if (empty($this->gradeRanges)) {
            $this->addGradeRange();
        }
    }
    
    public function addGradeRange(): void
    {
        $this->gradeRanges[] = [
            'id' => null,
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
    
    public function save(): void
    {
        $this->saving = true;
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            // Update grading system
            $this->gradingSystem->name = $this->name;
            $this->gradingSystem->description = $this->description;
            $this->gradingSystem->is_active = $this->is_active;
            $this->gradingSystem->save();
            
            // Get existing grade range IDs
            $existingIds = $this->gradingSystem->gradeRanges->pluck('id')->toArray();
            $updatedIds = [];
            
            // Update or create grade ranges
            foreach ($this->gradeRanges as $range) {
                if (isset($range['id'])) {
                    // Update existing
                    $gradeRange = GradeRange::find($range['id']);
                    if ($gradeRange) {
                        $gradeRange->update([
                            'grade' => $range['grade'],
                            'min_mark' => $range['min_mark'],
                            'max_mark' => $range['max_mark'],
                            'description' => $range['description'],
                        ]);
                        $updatedIds[] = $range['id'];
                    }
                } else {
                    // Create new
                    $gradeRange = new GradeRange([
                        'grade' => $range['grade'],
                        'min_mark' => $range['min_mark'],
                        'max_mark' => $range['max_mark'],
                        'description' => $range['description'],
                    ]);
                    $this->gradingSystem->gradeRanges()->save($gradeRange);
                    $updatedIds[] = $gradeRange->id;
                }
            }
            
            // Delete removed grade ranges
            $toDelete = array_diff($existingIds, $updatedIds);
            if (!empty($toDelete)) {
                GradeRange::whereIn('id', $toDelete)->delete();
            }
            
            DB::commit();
            
            // Emit event for Alpine.js to handle
            $this->dispatch('gradingSystemUpdated', 'Grading system updated successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('errorOccurred', 'Failed to update grading system: ' . $e->getMessage());
        } finally {
            $this->saving = false;
        }
    }
    
    public function render()
    {
        return view('livewire.exams.edit-grading-system');
    }
} 