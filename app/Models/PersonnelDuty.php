<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonnelDuty extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table =
        'personnel_duties';


    /*
    |--------------------------------------------------------------------------
    | Primary Key
    |--------------------------------------------------------------------------
    */

    protected $primaryKey =
        'duty_id';


    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'assignment_id',
        'event_id',
        'personnel_id',
        'check_in_at',
        'check_out_at',
        'duty_status',
        'completed_minutes',
    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'check_in_at' =>
            'datetime',

        'check_out_at' =>
            'datetime',

        'completed_minutes' =>
            'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Event
    |--------------------------------------------------------------------------
    */

    public function event()
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'event_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Personnel
    |--------------------------------------------------------------------------
    */

    public function personnel()
    {
        return $this->belongsTo(
            Personnel::class,
            'personnel_id',
            'personnel_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Event Assignment
    |--------------------------------------------------------------------------
    */

    public function assignment()
    {
        return $this->belongsTo(
            EventAssignment::class,
            'assignment_id',
            'assignment_id'
        );
    }
}