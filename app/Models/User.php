<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $fillable = [
        'school_id', 'user_type', 'username', 'email', 'mobile', 'password',
        'first_name', 'last_name', 'profile_pic', 'date_of_birth', 'gender',
        'address', 'city', 'state', 'country', 'pincode', 'last_login',
        'is_active', 'email_verified_at', 'settings'
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array',
        'date_of_birth' => 'date',
    ];
    
    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }
    
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }
    
    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    public function getRoleAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->user_type));
    }
    
    // Scopes
    public function scopeSuperAdmin($query)
    {
        return $query->where('user_type', 'super_admin');
    }
    
    public function scopeSchoolAdmin($query)
    {
        return $query->where('user_type', 'school_admin');
    }
    
    public function scopeTeacher($query)
    {
        return $query->where('user_type', 'teacher');
    }
    
    public function scopeStudent($query)
    {
        return $query->where('user_type', 'student');
    }
    
    // Helper Methods
    public function isSuperAdmin(): bool
    {
        return $this->user_type === 'super_admin';
    }
    
    public function isSchoolAdmin(): bool
    {
        return $this->user_type === 'school_admin';
    }
    
    public function isTeacher(): bool
    {
        return $this->user_type === 'teacher';
    }
    
    public function isStudent(): bool
    {
        return $this->user_type === 'student';
    }
    
    public function isParent(): bool
    {
        return $this->user_type === 'parent';
    }
}
