<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $primaryKey =
        'announcement_id';


    protected $fillable = [

        'event_id',
        'created_by',
        'subject',
        'message',
        'audience_type',
        'college',
        'year_level',
        'image_path',
        'recipient_count',
        'status',
        'sent_at',

    ];


    protected $casts = [

        'sent_at' => 'datetime',

    ];


    public function event()
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'event_id'
        );
    }


    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'user_id'
        );
    }


    public function recipients()
    {
        return $this->hasMany(
            AnnouncementRecipient::class,
            'announcement_id',
            'announcement_id'
        );
    }
}