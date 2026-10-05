<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $primaryKey = 'attendance_id';


    protected $fillable = [
        'event_id',
        'student_id',
        'rfid_identifier',
        'time_in',
        'status',
        'scanned_by',
    ];


    protected $casts = [
        'time_in' => 'datetime',
    ];


    public function event()
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'event_id'
        );
    }


    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id',
            'student_id'
        );
    }


    public function scanner()
    {
        return $this->belongsTo(
            User::class,
            'scanned_by',
            'user_id'
        );
    }
}