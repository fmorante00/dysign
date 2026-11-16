<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceDiscrepancy extends Model
{
    protected $primaryKey = 'discrepancy_id';

    protected $fillable = [
        'event_id', 'student_id', 'request_type', 'requested_status',
        'requested_time_in', 'reason', 'status', 'verification_status',
        'verified_by', 'verified_at', 'verification_notes', 'verified_time_in', 'verified_time_out', 'reviewed_by',
        'reviewed_at', 'review_notes', 'attendance_id', 'before_status',
        'before_time_in', 'before_time_out', 'after_status', 'after_time_in', 'after_time_out',
    ];

    protected $casts = [
        'requested_time_in' => 'datetime', 'verified_at' => 'datetime', 'verified_time_in' => 'datetime', 'verified_time_out' => 'datetime',
        'reviewed_at' => 'datetime', 'before_time_in' => 'datetime', 'before_time_out' => 'datetime',
        'after_time_in' => 'datetime', 'after_time_out' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by', 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }

    public function attendance()
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_id', 'attendance_id');
    }

    public function documents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            AttendanceDiscrepancyDocument::class,
            'discrepancy_id',
            'discrepancy_id'
        );
    }
}
