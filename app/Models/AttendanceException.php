<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceException extends Model
{
    protected $primaryKey = 'exception_id';

    protected $fillable = [
        'event_id',
        'student_id',
        'reported_by',
        'issue_type',
        'observed_at',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'attendance_id',
        'checked_out_at',
        'checked_out_by',
    ];

    protected $casts = [
        'observed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by', 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }

    public function checkedOutBy()
    {
        return $this->belongsTo(User::class, 'checked_out_by', 'user_id');
    }

    public function attendance()
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_id', 'attendance_id');
    }
}