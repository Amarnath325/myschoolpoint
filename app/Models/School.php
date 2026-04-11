<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'business_name', 'registration_number', 'tax_number', 'email', 'phone',
        'mobile', 'address', 'city', 'state', 'country', 'pincode', 'logo',
        'website', 'established_year', 'affiliation_board', 'school_type',
        'gender_type', 'status', 'subscription_plan', 'subscription_start_date',
        'subscription_end_date', 'settings'
    ];
    
    protected $casts = [
        'settings' => 'array',
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
    ];
    
    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    public function academicYears(): HasMany
    {
        return $this->hasMany(AcademicYear::class);
    }
    
    public function currentAcademicYear()
    {
        return $this->hasOne(AcademicYear::class)->where('is_current', true);
    }
    
    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class);
    }
    
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
    
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
    
    public function businessDetail()
    {
        return $this->hasOne(SchoolBusinessDetail::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function scopeSubscriptionValid($query)
    {
        return $query->where('subscription_end_date', '>', now());
    }
    
    // Helper Methods
    public function isSubscriptionActive(): bool
    {
        return $this->status === 'active' && 
               $this->subscription_end_date && 
               $this->subscription_end_date > now();
    }
}
