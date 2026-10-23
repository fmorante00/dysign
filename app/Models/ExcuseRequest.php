<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcuseRequest extends Model
{
    protected $primaryKey =
        'excuse_request_id';


    protected $fillable = [

        'event_id',

        'student_id',

        'request_timing',

        'reason',

        'status',

        'reviewed_by',

        'review_remarks',

        'reviewed_at',

        'submitted_at',

    ];


    protected $casts = [

        'reviewed_at' =>
            'datetime',

        'submitted_at' =>
            'datetime',

    ];


    /*
    |--------------------------------------------------------------------------
    | EVENT
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
    | STUDENT
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id',
            'student_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REVIEWER
    |--------------------------------------------------------------------------
    */

    public function reviewer()
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by',
            'user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUPPORTING DOCUMENTS
    |--------------------------------------------------------------------------
    */

    public function documents()
    {
        return $this->hasMany(
            ExcuseRequestDocument::class,
            'excuse_request_id',
            'excuse_request_id'
        );
    }
}