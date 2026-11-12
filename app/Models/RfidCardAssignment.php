<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidCardAssignment extends Model
{
    protected $primaryKey = 'rfid_card_assignment_id';

    protected $fillable = [
        'student_id',
        'rfid_identifier',
        'status',
        'assigned_at',
        'ended_at',
        'reason',
        'assigned_by',
        'ended_by',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'user_id');
    }

    public function endedBy()
    {
        return $this->belongsTo(User::class, 'ended_by', 'user_id');
    }
}
