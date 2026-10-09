<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementAttachment extends Model
{
    protected $primaryKey =
        'announcement_attachment_id';

    protected $fillable = [
        'announcement_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function announcement()
    {
        return $this->belongsTo(
            Announcement::class,
            'announcement_id',
            'announcement_id'
        );
    }
}