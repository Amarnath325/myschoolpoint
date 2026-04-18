<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{
    protected $fillable = [
        // Basic School Information
        'business_name',        // School Name
        'school_code',          // School Code/ID
        'established_year',     // Established Year
        'school_type',          // day / boarding / day_boarding
        'management_type',      // private / government / aided
        
        // Location Details
        'country',              // Country (default: India)
        'state',                // State
        'city',                 // City/District
        'pincode',              // Pin Code
        'full_address',         // Full Address
        'latitude',             // Latitude (optional)
        'longitude',            // Longitude (optional)
        
        // Contact Details
        'contact_number',       // School Contact Number
        'email',                // Email Address
        'website',              // Website (optional)
        
        // Affiliation Details
        'affiliation_board',    // CBSE / ICSE / State Board / IB / Cambridge
        'affiliation_number',   // Affiliation Number
        'affiliation_status',   // active / pending / expired
        
        // Academic Structure
        'classes_available',    // JSON array of classes
        'streams_available',    // JSON array of streams
        'medium_of_instruction', // JSON array of mediums
        
        // Infrastructure Details
        'has_labs',             // boolean - Labs Available
        'has_library',          // boolean - Library Available
        'has_sports',           // boolean - Sports Facilities
        'has_hostel',           // boolean - Hostel Facility
        'has_transport',        // boolean - Transport Facility
        
        // Subscription Plan
        'subscription_plan',    // free / basic / premium / trial
        'subscription_start_date',
        'subscription_end_date',
        
        // About School
        'about_school',         // Description about school
        
        // Status & Settings
        'status',               // active / inactive / suspended
        'settings',             // JSON settings
        
        // Legacy Fields (for backward compatibility)
        'registration_number',
        'tax_number',
        'phone',
        'mobile',
        'address',
        'logo',
    ];
    
    protected $casts = [
        // Academic Structure (JSON fields)
        'classes_available' => 'array',
        'streams_available' => 'array',
        'medium_of_instruction' => 'array',
        
        // Infrastructure (boolean)
        'has_labs' => 'boolean',
        'has_library' => 'boolean',
        'has_sports' => 'boolean',
        'has_hostel' => 'boolean',
        'has_transport' => 'boolean',
        
        // Dates
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
        'established_year' => 'integer',
        
        // Settings & Status
        'settings' => 'array',
        'status' => 'string',
        
        // Legacy
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
    
    // Accessors
    public function getFormattedAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->attributes['full_address'] ?? null,
            $this->city,
            $this->state,
            $this->pincode,
            $this->country
        ]));
    }
    
    public function getClassesListAttribute(): array
    {
        return $this->classes_available ?? [];
    }
    
    public function getStreamsListAttribute(): array
    {
        return $this->streams_available ?? [];
    }
    
    public function getMediumsListAttribute(): array
    {
        return $this->medium_of_instruction ?? ['english'];
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
    
    public function scopeByPlan($query, $plan)
    {
        return $query->where('subscription_plan', $plan);
    }
    
    public function scopeByBoard($query, $board)
    {
        return $query->where('affiliation_board', $board);
    }
    
    public function scopeByType($query, $type)
    {
        return $query->where('school_type', $type);
    }
    
    public function scopeByManagement($query, $type)
    {
        return $query->where('management_type', $type);
    }
    
    // Helper Methods
    public function isSubscriptionActive(): bool
    {
        return $this->status === 'active' && 
               $this->subscription_end_date && 
               $this->subscription_end_date > now();
    }
    
    public function hasClass(string $class): bool
    {
        return in_array($class, $this->classes_available ?? []);
    }
    
    public function hasStream(string $stream): bool
    {
        return in_array($stream, $this->streams_available ?? []);
    }
    
    public function hasMedium(string $medium): bool
    {
        return in_array(strtolower($medium), $this->medium_of_instruction ?? []);
    }
    
    public function getSubscriptionPlanName(): string
    {
        $plans = [
            'free' => 'Free Plan',
            'basic' => 'Basic Plan',
            'premium' => 'Premium Plan',
            'trial' => 'Trial Plan',
        ];
        
        return $plans[$this->subscription_plan] ?? 'Unknown';
    }
    
    public function getSchoolTypeName(): string
    {
        $types = [
            'day' => 'Day School',
            'boarding' => 'Boarding School',
            'day_boarding' => 'Day & Boarding School',
        ];
        
        return $types[$this->school_type] ?? 'Unknown';
    }
    
    public function getManagementTypeName(): string
    {
        $types = [
            'private' => 'Private',
            'government' => 'Government',
            'aided' => 'Aided',
        ];
        
        return $types[$this->management_type] ?? 'Unknown';
    }
    
    public function getAffiliationBoardName(): string
    {
        $boards = [
            'CBSE' => 'Central Board of Secondary Education',
            'ICSE' => 'Council for Indian School Certificate',
            'State Board' => 'State Board',
            'IB' => 'International Baccalaureate',
            'Cambridge' => 'Cambridge International',
        ];
        
        return $boards[$this->affiliation_board] ?? $this->affiliation_board;
    }
    
    public function getAffiliationStatusBadge(): string
    {
        $badges = [
            'active' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'expired' => 'bg-red-100 text-red-800',
        ];
        
        return $badges[$this->affiliation_status] ?? 'bg-gray-100 text-gray-800';
    }
    
    public function getStatusBadge(): string
    {
        $badges = [
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'suspended' => 'bg-red-100 text-red-800',
        ];
        
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
