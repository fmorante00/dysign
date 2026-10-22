<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventEligibilityRule extends Model
{
    protected $primaryKey =
        'eligibility_rule_id';

    protected $fillable = [

        'event_id',

        'rule_type',

        'college',

        'program_code',

        'year_level',

        'student_id',

        'participation_type',

        'created_by',

    ];


    public function event(): BelongsTo
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'event_id'
        );
    }


    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id',
            'student_id'
        );
    }


    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'user_id'
        );
    }
}