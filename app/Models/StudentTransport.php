<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTransport extends Model
{
    protected $fillable = ['school_id', 'student_id', 'route_id', 'stop_id', 'academic_year_id', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function route(): BelongsTo { return $this->belongsTo(TransportRoute::class); }
    public function stop(): BelongsTo { return $this->belongsTo(TransportStop::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
}