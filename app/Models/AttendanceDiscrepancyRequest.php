<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceDiscrepancyRequest extends Model
{
    protected $primaryKey = 'discrepancy_request_id';

    protected $fillable = [
        'student_id', 'event_id', 'attendance_id', 'requested_by',
        'request_type', 'description', 'expected_time_in', 'original_time_in',
        'original_attendance_status', 'status', 'pending_key',
        'reviewed_by', 'reviewed_at', 'resolution_notes', 'verified_time_in',
    ];

    protected $casts = [
        'expected_time_in' => 'datetime',
        'original_time_in' => 'datetime',
        'reviewed_at' => 'datetime',
        'verified_time_in' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_id', 'attendance_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }
}
