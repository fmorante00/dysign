<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityPoint extends Model
{
    protected $table = 'activity_points';

    protected $primaryKey = 'activity_point_id';

    protected $fillable = [
        'student_id',
        'event_id',
        'attendance_id',
        'points_awarded',
        'awarded_at',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'event_id' => 'integer',
        'attendance_id' => 'integer',
        'points_awarded' => 'integer',
        'awarded_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id',
            'student_id'
        );
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'event_id'
        );
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(
            AttendanceRecord::class,
            'attendance_id',
            'attendance_id'
        );
    }
}
