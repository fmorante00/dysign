<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventEligibleStudent extends Model
{
    protected $primaryKey =
        'eligible_student_id';

    protected $fillable = [
        'event_id',
        'student_id',
        'participation_type',
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
}