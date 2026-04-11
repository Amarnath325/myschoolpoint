<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    protected $fillable = [
        'school_id', 'name', 'start_date', 'end_date', 'is_current', 'status'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class);
    }
    
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
    
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
