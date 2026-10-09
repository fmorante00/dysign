<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementRecipient extends Model
{
    protected $primaryKey =
        'announcement_recipient_id';


    protected $fillable = [

        'announcement_id',
        'student_id',
        'email',
        'status',
        'sent_at',
        'error_message',

    ];


    protected $casts = [

        'sent_at' => 'datetime',

    ];


    public function announcement()
    {
        return $this->belongsTo(
            Announcement::class,
            'announcement_id',
            'announcement_id'
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