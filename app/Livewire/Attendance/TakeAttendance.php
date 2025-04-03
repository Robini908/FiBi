<?php

namespace App\Livewire\Attendance;

use App\Models\AttendanceRecord;
use App\Models\AttendanceDetail;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TakeAttendance extends Component
{
    public $classId;
    public $sectionId;
    public $class;
    public $section;
    public $students = [];
    public $attendanceDate;
    public $sessionType = 'whole_day';
    public $remarks;
    public $expectedTime;
    public $studentStatus = [];
    public $studentRemarks = [];
    public $timeIn = [];
    public $existingRecord = null;
    public $search = '';
    public $statusOptions = [
        'present' => 'Present',
        'absent' => 'Absent',
        'late' => 'Late',
        'excused' => 'Excused',
        'sick' => 'Sick',
        'on_leave' => 'On Leave',
        'other' => 'Other',
    ];
    public $sessionTypes = [
        'morning' => 'Morning Session',
        'afternoon' => 'Afternoon Session',
        'whole_day' => 'Whole Day',
    ];
    public $showLateTime = [];
    public $saving = false;
    public $showSuccessMessage = false;
    public $errorMessage = '';
    public $classes = [];
    public $sections = [];

    protected $rules = [
        'classId' => 'required|exists:my_classes,id',
        'sectionId' => 'required|exists:sections,id',
        'attendanceDate' => 'required|date',
        'sessionType' => 'required|in:morning,afternoon,whole_day',
        'remarks' => 'nullable|string|max:500',
        'studentStatus.*' => 'required|in:present,absent,late,excused,sick,on_leave,other',
        'studentRemarks.*' => 'nullable|string|max:255',
    ];

    public function mount($classId = null, $sectionId = null)
    {
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        
        // Check if date and session are provided in the URL for editing existing records
        $this->attendanceDate = request()->query('date') ? request()->query('date') : Carbon::today()->format('Y-m-d');
        $this->sessionType = request()->query('session') ? request()->query('session') : 'whole_day';
        
        \Illuminate\Support\Facades\Log::info('TakeAttendance mounted', [
            'class_id' => $this->classId,
            'section_id' => $this->sectionId,
            'date' => $this->attendanceDate,
            'session' => $this->sessionType
        ]);
        
        // Load all classes for selection if class not provided
        if (!$this->classId) {
            $this->classes = MyClass::orderBy('name')->get();
            return;
        }
        
        $this->class = MyClass::find($classId);
        
        if (!$this->class) {
            return redirect()->route('attendance.take')
                ->with('error', 'Class not found');
        }
        
        // Load sections for the selected class
        $this->sections = Section::where('my_class_id', $this->classId)
            ->orderBy('name')
            ->get();
        
        if ($this->sectionId) {
            $this->section = Section::find($sectionId);
            
            if (!$this->section) {
                return redirect()->route('attendance.take')
                    ->with('error', 'Section not found');
            }
            
            $this->loadStudents();
            $this->checkExistingRecord();
        }
    }

    public function updatedClassId()
    {
        $this->sections = Section::where('my_class_id', $this->classId)
            ->orderBy('name')
            ->get();
        $this->sectionId = null;
        $this->students = [];
        $this->studentStatus = [];
        $this->studentRemarks = [];
        $this->timeIn = [];
        $this->showLateTime = [];
        $this->existingRecord = null;
    }

    public function updatedSectionId()
    {
        if ($this->sectionId) {
            $this->section = Section::find($this->sectionId);
            
            // Reset student data while preserving class/section information
            $this->remarks = null;
            $this->expectedTime = null;
            $this->existingRecord = null;
            $this->studentStatus = [];
            $this->studentRemarks = [];
            $this->timeIn = [];
            $this->showLateTime = [];
            
            // Load students
            $this->loadStudents();
            
            // Check for existing record
            $this->checkExistingRecord();
        }
    }

    public function updatedAttendanceDate()
    {
        // Reset student data while preserving class/section information
        $this->remarks = null;
        $this->existingRecord = null;
        $this->studentStatus = [];
        $this->studentRemarks = [];
        $this->timeIn = [];
        $this->showLateTime = [];
        
        // Reload students
        $this->loadStudents();
        
        // Check for existing record with new date
        $this->checkExistingRecord();
    }

    public function updatedSessionType()
    {
        // Reset student data while preserving class/section information
        $this->remarks = null;
        $this->existingRecord = null;
        $this->studentStatus = [];
        $this->studentRemarks = [];
        $this->timeIn = [];
        $this->showLateTime = [];
        
        // Reload students
        $this->loadStudents();
        
        // Check for existing record with new session type
        $this->checkExistingRecord();
    }

    public function updatedStudentStatus($value, $key)
    {
        $studentId = explode('.', $key)[0];
        $this->showLateTime[$studentId] = $value === 'late';
    }

    public function setAllStatus($status)
    {
        foreach ($this->students as $student) {
            $this->studentStatus[$student['id']] = $status;
            $this->showLateTime[$student['id']] = $status === 'late';
        }
    }

    public function save()
    {
        try {
            // Validate the form data
            $validatedData = $this->validate();
            
            $this->saving = true;
            
            // Get current session and term
            $academicYear = Setting::where('key', 'current_session')->first()->value ?? date('Y');
            
            // Make sure term is an integer
            $termSetting = Setting::where('key', 'current_term')->first();
            $term = 1; // Default to 1
            
            // Debug the term value
            \Illuminate\Support\Facades\Log::info('Raw term value from settings', [
                'original' => $termSetting ? $termSetting->value : null
            ]);
            
            if ($termSetting) {
                // Check if it's already numeric
                if (is_numeric($termSetting->value)) {
                    $term = (int)$termSetting->value;
                } 
                // Try to extract just the number from strings like "Term 1" or "1st Term"
                else if (preg_match('/(\d+)/', $termSetting->value, $matches)) {
                    $term = (int)$matches[1];
                }
                // Fallback for specific term strings
                else if (strtolower($termSetting->value) === 'first term' || strtolower($termSetting->value) === 'term one') {
                    $term = 1;
                }
                else if (strtolower($termSetting->value) === 'second term' || strtolower($termSetting->value) === 'term two') {
                    $term = 2;
                }
                else if (strtolower($termSetting->value) === 'third term' || strtolower($termSetting->value) === 'term three') {
                    $term = 3;
                }
            }
            
            // Log the final term value that will be used
            \Illuminate\Support\Facades\Log::info('Final term value after processing', [
                'term' => $term,
                'type' => gettype($term)
            ]);
            
            DB::beginTransaction();
            
            try {
                // Check if attendance record already exists
                if ($this->existingRecord) {
                    // Update existing record
                    $this->existingRecord->update([
                        'marked_by' => Auth::id(),
                        'remarks' => $this->remarks,
                    ]);
                    
                    $attendanceRecord = $this->existingRecord;
                    
                    // Delete existing details to avoid duplicates
                    $attendanceRecord->attendanceDetails()->delete();
                } else {
                    // Create new attendance record
                    $attendanceRecord = AttendanceRecord::create([
                        'attendance_date' => $this->attendanceDate,
                        'class_id' => $this->classId,
                        'section_id' => $this->sectionId,
                        'marked_by' => Auth::id(),
                        'remarks' => $this->remarks,
                        'academic_year' => $academicYear,
                        'term' => $term,
                        'session_type' => $this->sessionType,
                    ]);
                }
                
                // Store attendance details for each student
                $attendanceDetails = [];
                
                foreach ($this->studentStatus as $studentId => $status) {
                    $timeIn = null;
                    $timeOut = null;
                    $minutesLate = 0;
                    
                    // If student is late, record the time and minutes late
                    if ($status === 'late' && !empty($this->timeIn[$studentId])) {
                        $timeIn = $this->timeIn[$studentId];
                        
                        // Calculate minutes late if expected time is set
                        if (!empty($this->expectedTime)) {
                            $expectedTime = Carbon::parse($this->expectedTime);
                            $actualTime = Carbon::parse($timeIn);
                            $minutesLate = $expectedTime->diffInMinutes($actualTime);
                        }
                    }
                    
                    $attendanceDetails[] = [
                        'attendance_record_id' => $attendanceRecord->id,
                        'student_id' => $studentId,
                        'status' => $status,
                        'time_in' => $timeIn,
                        'time_out' => $timeOut,
                        'minutes_late' => $minutesLate,
                        'remarks' => $this->studentRemarks[$studentId] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                // Insert all attendance details at once
                AttendanceDetail::insert($attendanceDetails);
                
                DB::commit();
                
                $this->showSuccessMessage = true;
                $this->errorMessage = '';
                $this->existingRecord = $attendanceRecord;
                
                // Try both ways to dispatch the notification
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Attendance has been recorded successfully!',
                ]);
                
                // Also try direct notification as a fallback
                session()->flash('success', 'Attendance has been recorded successfully!');
                
                // Reset form if this is a new record (not an update)
                if (!$this->existingRecord) {
                    // Keep the class and section selected, but reset other fields
                    $currentClassId = $this->classId;
                    $currentSectionId = $this->sectionId;
                    $currentClass = $this->class;
                    $currentSection = $this->section;
                    
                    // Reset the form but keep the class/section
                    $this->reset(['studentStatus', 'studentRemarks', 'timeIn', 'showLateTime', 'remarks', 'expectedTime']);
                    
                    // Restore class/section
                    $this->classId = $currentClassId;
                    $this->sectionId = $currentSectionId;
                    $this->class = $currentClass;
                    $this->section = $currentSection;
                    
                    // Reload students with default values
                    $this->loadStudents();
                }
                
                // Set the existingRecord property to reflect the current state
                $this->checkExistingRecord();
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->errorMessage = 'Failed to record attendance: ' . $e->getMessage();
                
                \Illuminate\Support\Facades\Log::error('Failed to save attendance', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => $this->errorMessage,
                ]);
                
                // Also try direct notification as a fallback
                session()->flash('error', $this->errorMessage);
            }
            
            $this->saving = false;
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Catch validation errors specifically
            \Illuminate\Support\Facades\Log::error('Validation error', [
                'errors' => $e->validator->errors()->toArray()
            ]);
            
            // Display validation errors
            $errorMessage = 'Please fix the following errors: ';
            foreach ($e->validator->errors()->all() as $error) {
                $errorMessage .= $error . ' ';
            }
            
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => $errorMessage,
            ]);
            
            // Rethrow to let Livewire handle validation errors normally
            throw $e;
        } catch (\Exception $e) {
            // Catch other exceptions
            \Illuminate\Support\Facades\Log::error('Validation or pre-save error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ]);
            
            // Also try direct notification as a fallback
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $filteredStudents = $this->students;
        
        // Filter students if search is not empty
        if (!empty($this->search)) {
            $searchTerm = strtolower($this->search);
            $filteredStudents = array_filter($this->students, function($student) use ($searchTerm) {
                return str_contains(strtolower($student['name']), $searchTerm) || 
                       str_contains(strtolower($student['adm_no'] ?? ''), $searchTerm);
            });
        }
        
        return view('livewire.attendance.take-attendance', [
            'classes' => $this->classes,
            'sections' => $this->sections,
            'filteredStudents' => $filteredStudents
        ]);
    }

    public function loadStudents()
    {
        if (!$this->classId || !$this->sectionId) {
            return;
        }
        
        \Illuminate\Support\Facades\Log::info('Loading students', [
            'class_id' => $this->classId,
            'section_id' => $this->sectionId
        ]);
        
        $studentsQuery = StudentRecord::where('my_class_id', $this->classId)
            ->where('section_id', $this->sectionId)
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name');
            
        $this->students = $studentsQuery->get()->map(function ($student) {
            // Make sure the name is accessible in the array
            return array_merge($student->toArray(), [
                'name' => $student->name,
            ]);
        })->toArray();
            
        // Initialize all students as present by default only if studentStatus is empty
        // This prevents overriding status when we're just reloading students list
        foreach ($this->students as $student) {
            if (!isset($this->studentStatus[$student['id']])) {
                $this->studentStatus[$student['id']] = 'present';
                $this->studentRemarks[$student['id']] = '';
                $this->timeIn[$student['id']] = '';
                $this->showLateTime[$student['id']] = false;
            }
        }
        
        \Illuminate\Support\Facades\Log::info('Students loaded', [
            'count' => count($this->students)
        ]);
    }

    public function checkExistingRecord()
    {
        if (!$this->classId || !$this->sectionId) {
            return;
        }
        
        \Illuminate\Support\Facades\Log::info('Checking existing record', [
            'class_id' => $this->classId,
            'section_id' => $this->sectionId
        ]);
        
        $existingRecord = AttendanceRecord::where('class_id', $this->classId)
            ->where('section_id', $this->sectionId)
            ->where('attendance_date', $this->attendanceDate)
            ->first();
        
        if ($existingRecord) {
            \Illuminate\Support\Facades\Log::info('Existing record found', [
                'record_id' => $existingRecord->id
            ]);
            
            $this->existingRecord = $existingRecord;
        } else {
            \Illuminate\Support\Facades\Log::info('No existing record found');
            
            $this->existingRecord = null;
        }
    }
} 