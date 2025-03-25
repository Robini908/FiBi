<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffRecord extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'code',
        'emp_date',
        'qualification',
        'experience',
        'departments',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the staff record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the qualifications for the staff member.
     */
    public function qualifications(): HasMany
    {
        return $this->hasMany(StaffQualification::class, 'staff_id');
    }
    
    /**
     * Get the attendance records for the staff member.
     */
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(StaffAttendanceRecord::class, 'staff_id');
    }
    
    /**
     * Get the active status as a string
     * 
     * @return string
     */
    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }
    
    /**
     * Format the employment date for display
     * 
     * @return string|null
     */
    public function getFormattedEmpDateAttribute(): ?string
    {
        return $this->emp_date ? date('M d, Y', strtotime($this->emp_date)) : null;
    }
}
