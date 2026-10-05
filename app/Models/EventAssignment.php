<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAssignment extends Model
{
    protected $primaryKey = 'assignment_id';

    protected $fillable = [
        'event_id',
        'personnel_id',
        'assigned_by',
    ];


    public function event()
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'event_id'
        );
    }


    public function personnel()
    {
        return $this->belongsTo(
            Personnel::class,
            'personnel_id',
            'personnel_id'
        );
    }


    public function assignedBy()
    {
        return $this->belongsTo(
            User::class,
            'assigned_by',
            'user_id'
        );
    }
}