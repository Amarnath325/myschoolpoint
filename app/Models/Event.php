<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'school_id', 'title', 'description', 'event_type', 'start_datetime',
        'end_datetime', 'venue', 'is_holiday', 'target_audience', 'created_by'
    ];
    
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_holiday' => 'boolean',
        'target_audience' => 'array',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', now());
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('start_datetime', today());
    }
}