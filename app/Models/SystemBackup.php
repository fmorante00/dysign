<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemBackup extends Model
{
    protected $primaryKey = 'backup_id';


    protected $fillable = [
        'created_by',
        'filename',
        'file_path',
        'size_bytes',
        'status',
        'error_message',
    ];


    protected $casts = [
        'size_bytes' => 'integer',
    ];


    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'user_id'
        );
    }
}