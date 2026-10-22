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

    /**
     * Student attendance records.
     */
    public function attendanceRecords()
    {
        return $this->hasMany(
            AttendanceRecord::class,
            'student_id',
            'student_id'
        );
    }

    /**
     * Student's associated login account.
     */
    public function user()
    {
        return $this->hasOne(
            User::class,
            'student_id',
            'student_id'
        );
    }
}
