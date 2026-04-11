<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransportRoute extends Model
{
    protected $fillable = ['school_id', 'route_name', 'vehicle_number', 'driver_name', 'driver_phone', 'capacity', 'amount'];
    protected $casts = ['amount' => 'decimal:2', 'capacity' => 'integer'];
    
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function stops(): HasMany { return $this->hasMany(TransportStop::class); }
    public function studentAssignments(): HasMany { return $this->hasMany(StudentTransport::class); }
}