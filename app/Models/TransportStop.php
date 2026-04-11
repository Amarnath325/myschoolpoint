<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransportStop extends Model
{
    protected $fillable = ['route_id', 'stop_name', 'stop_order', 'pickup_time', 'drop_time', 'latitude', 'longitude'];
    protected $casts = ['pickup_time' => 'datetime', 'drop_time' => 'datetime', 'latitude' => 'decimal:8', 'longitude' => 'decimal:8'];
    
    public function route(): BelongsTo { return $this->belongsTo(TransportRoute::class); }
    public function studentAssignments(): HasMany { return $this->hasMany(StudentTransport::class); }
}