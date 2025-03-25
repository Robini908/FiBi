<?php

namespace App;

use App\Models\{
    Dorm,
    State,
    MyClass,
    ChatMessage,
    BloodGroup,
    StaffRecord,
    StudentRecord,
    ParentDetail,
    StudentTransition,
    StudentPromotionDemotion,
    Section,
    Tenant
};
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToTenant, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'phone2',
        'dob',
        'gender',
        'photo',
        'address',
        'bg_id',
        'password',
        'state_id',
        'code',
        'user_type', // Kept for backward compatibility
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
    ];

    // Define relationships
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function dorms()
    {
        return $this->belongsToMany(Dorm::class, 'dorm_teacher', 'user_id', 'dorm_id')
            ->withPivot('session') // Include the session from the pivot table
            ->withTimestamps();    // Include timestamps if needed
    }

    public function parentDetails()
    {
        return $this->hasOne(ParentDetail::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(MyClass::class, 'class_teacher', 'user_id', 'my_class_id')
            ->withPivot('session') // Include the session column from the pivot table
            ->withTimestamps();    // Include timestamps if needed
    }

    public function studentRecord()
    {
        return $this->hasOne(StudentRecord::class);
    }

    public function receivedMessages()
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }


    public function blood_group()
    {
        return $this->belongsTo(BloodGroup::class, 'bg_id');
    }

    public function staff()
    {
        return $this->hasMany(StaffRecord::class);
    }

    // Relationship to MyClass as the master (teacher)
    public function classes_as_master()
    {
        return $this->hasMany(MyClass::class, 'master_id')->where('user_type', 'teacher');
    }

    /**
     * Define the relationship with Section.
     * Each user (teacher) can be associated with multiple sections.
     */
    public function sections()
    {
        return $this->hasMany(Section::class, 'teacher_id'); // Assuming 'teacher_id' is the foreign key in the sections table
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'sender_id')
            ->orWhere('receiver_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->withDefault(); // Ensure it returns a default model if no message exists
    }

    /**
     * Get the entity's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    /**
     * Find a user by their username or email
     *
     * @param string $username
     * @return \App\User|null
     */
    public static function findForAuth($username)
    {
        return static::where('email', $username)
                    ->orWhere('username', $username)
                    ->first();
    }

    /**
     * Determine if the user has enabled two-factor authentication.
     *
     * @return bool
     */
    public function twoFactorAuthEnabled()
    {
        return ! is_null($this->two_factor_secret) &&
               ! is_null($this->two_factor_confirmed_at);
    }
}
