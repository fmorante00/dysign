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
        'status',
    ];

}