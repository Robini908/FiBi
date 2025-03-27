<?php

namespace App\Models;

use App\User;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class StudentRecord extends Model
{
    use HasFactory;
    use Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'parent_id_no',
        'my_class_id',
        'user_id',
        'section_id',
        'adm_no',
        'dorm_id',
        'year_admitted',
        'kcpe',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'gender',
        'phone',
        'dob',
        'nationality', // Added
        'state', // Added
        'town', // Added
        'nal_id',
        'state_id',
        'lga_id',
        'bg_id',
        'photo',
        'status',
        'student_password',
        'is_suspended',
        'suspension_reason',
        'suspended_by',
        'notification_content',
        'suspension_date',
        'suspension_type',
        'suspension_end_date',
        'disapproval_reason',
        'upi_number', // Added
    ];

    protected $appends = ['is_enrolled'];

    protected $casts = [
        'suspension_date' => 'datetime',
        'suspension_end_date' => 'datetime',
        'is_enrolled' => 'boolean'
    ];

    public function getAuthPassword()
    {
        return $this->student_password;
    }

    public function bloodgroup()
    {
        return $this->belongsTo(BloodGroup::class, 'student_id');
    }

    public function transitions(): HasMany
    {
        return $this->hasMany(StudentTransition::class, 'student_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    protected static function boot()
    {
        parent::boot();

        // Listen for the 'deleting' event
        static::deleting(function ($student) {
            // Load the user relationship if not already loaded
            if (!$student->relationLoaded('user')) {
                $student->load('user');
            }

            // Delete the associated user if it exists
            if ($student->user) {
                $student->user->delete();
            }
        });
    }


    

    /**
     * Get the current class and section for the student.
     * 
     * @param int $academicYear The academic year to check (e.g., 2023)
     * @param string|null $academicPeriod Optional period like semester/term
     * @return array|null Array with class and section objects or null if not found
     */
    public function getCurrentClassAndSection(int $academicYear, ?string $academicPeriod = null)
    {
        $query = $this->transitions()
            ->where('to_academic_year', $academicYear)
            ->where('is_active', true);
            
        if ($academicPeriod) {
            $query->where('academic_period', $academicPeriod);
        }
        
        $activeTransition = $query->latest('effective_date')->first();
        
        if (!$activeTransition) {
            // If no active transition is found for the specified year,
            // fall back to the base class and section if it matches the academic year
            if ($this->my_class_id && $this->section_id) {
                return [
                    'class' => $this->my_class,
                    'section' => $this->section,
                ];
            }
            return null;
        }
        
        return [
            'class' => $activeTransition->toClass,
            'section' => $activeTransition->toSection,
            'transition' => $activeTransition,
        ];
    }


    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'student_subject', 'student_id', 'subject_id')
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_id');
    }


    public function examMarks(): HasMany
    {
        return $this->hasMany(ExamMarks::class, 'student_id');
    }

    public function parent_detail()
    {
        return $this->belongsTo(ParentDetail::class, 'parent_id_no', 'parent_id_no');
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }



    /**
     * Get the current class for the student in a specific academic year.
     * 
     * @param int $academicYear The academic year to check
     * @param string|null $academicPeriod Optional period like semester/term
     * @return MyClass|null The class object or null if not found
     */
    public function getCurrentClass(int $academicYear, ?string $academicPeriod = null)
    {
        $classAndSection = $this->getCurrentClassAndSection($academicYear, $academicPeriod);
        return $classAndSection ? $classAndSection['class'] : null;
    }

    /**
     * Get the section that this student belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function dorm()
    {
        return $this->belongsTo(Dorm::class);
    }

    public function dorms()
    {
        return $this->belongsToMany(Dorm::class, 'dorm_student', 'student_id', 'dorm_id')
            ->withPivot('year') // Include the year in the pivot table
            ->withTimestamps();
    }

    public function marks(): HasMany
    {
        return $this->hasMany(ExamMarks::class, 'student_id');
    }

    public function studentResults(): HasMany
    {
        return $this->hasMany(StudentResult::class, 'student_id', 'id');
    }

    /**
     * Get the enrollment status of the student.
     * This is a dynamic attribute that will be appended to the model.
     *
     * @return bool
     */
    public function getIsEnrolledAttribute()
    {
        // If no subject is selected in the context, return true
        if (!request()->has('subject_id')) {
            return true;
        }

        $subjectId = request()->get('subject_id');
        $isSelectionEnabled = SubjectSelectionSetting::where('class_id', $this->my_class_id)
            ->where('is_subject_selection_enabled', true)
            ->exists();

        // If subject selection is not enabled for this class, student is enrolled in all subjects
        if (!$isSelectionEnabled) {
            return true;
        }

        // If subject selection is enabled, check if student has selected this subject
        return $this->subjects()->where('subjects.id', $subjectId)->exists();
    }

    /**
     * Get all transitions for this student.
     * 
     * @return \Illuminate\Database\Eloquent\Collection Collection of transitions
     */
    public function getAllTransitions()
    {
        return $this->transitions()->orderBy('effective_date', 'desc')->get();
    }
    
    /**
     * Create a new transition for this student.
     * 
     * @param array $attributes The transition attributes
     * @param bool $applyImmediately Whether to apply the transition immediately
     * @return StudentTransition|null The created transition or null if failed
     */
    public function createTransition(array $attributes, bool $applyImmediately = true)
    {
        $attributes['student_id'] = $this->id;
        
        // If from_class_id and from_section_id are not provided, use current values
        if (!isset($attributes['from_class_id'])) {
            $attributes['from_class_id'] = $this->my_class_id;
        }
        
        if (!isset($attributes['from_section_id'])) {
            $attributes['from_section_id'] = $this->section_id;
        }
        
        if ($applyImmediately) {
            return StudentTransition::createAndApply($attributes);
        }
        
        return StudentTransition::create($attributes);
    }
}
