<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffQualification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'qualification_name',
        'institution',
        'qualification_type',
        'field_of_study',
        'year_obtained',
        'document_url',
        'is_verified',
        'verified_by',
        'verification_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year_obtained' => 'integer',
        'is_verified' => 'boolean',
        'verification_date' => 'datetime',
    ];

    /**
     * Get the staff record that owns the qualification.
     */
    public function staffRecord(): BelongsTo
    {
        return $this->belongsTo(StaffRecord::class, 'staff_id');
    }

    /**
     * Get the user who verified the qualification.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'verified_by');
    }
} 