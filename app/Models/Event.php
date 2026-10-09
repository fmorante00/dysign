<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_name',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'created_by',
        'department_id',
        'status',
    ];


    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'user_id'
        );
    }


    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'department_id'
        );
    }


    public function assignments()
    {
        return $this->hasMany(
            EventAssignment::class,
            'event_id',
            'event_id'
        );
    }


    public function assignedPersonnel()
    {
        return $this->belongsToMany(
            Personnel::class,
            'event_assignments',
            'event_id',
            'personnel_id',
            'event_id',
            'personnel_id'
        )
        ->withPivot([
            'assignment_id',
            'assigned_by',
        ])
        ->withTimestamps();
    }


    public function attendanceRecords()
    {
        return $this->hasMany(
            AttendanceRecord::class,
            'event_id',
            'event_id'
        );
    }
}