<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timetable extends Model
{
    protected $fillable = ['school_id', 'academic_year_id', 'class_id', 'subject_id', 
                           'teacher_id', 'day_of_week', 'start_time', 'end_time', 'room_number', 'is_break'];
    protected $casts = ['is_break' => 'boolean'];
    
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function class(): BelongsTo { return $this->belongsTo(Classes::class); }
    public function subject(): BelongsTo { return $this->belongsTo(Subject::class); }
    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); }
}