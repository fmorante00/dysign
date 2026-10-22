<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'student_number',
        'first_name',
        'middle_name',
        'last_name',
        'college',
        'program_name',
        'program_code',
        'year_level',
        'student_status',
        'rfid_identifier',
        'email',
        'photo_path',
        'status',
    ];


    /*
    |--------------------------------------------------------------------------
    | Attendance Records
    |--------------------------------------------------------------------------
    */

    public function attendanceRecords()
    {
        return $this->hasMany(
            AttendanceRecord::class,
            'student_id',
            'student_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Student Account
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->hasOne(
            User::class,
            'student_id',
            'student_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Event Eligibility
    |--------------------------------------------------------------------------
    */

    public function eventEligibilities()
    {
        return $this->hasMany(
            EventEligibleStudent::class,
            'student_id',
            'student_id'
        );
    }
}