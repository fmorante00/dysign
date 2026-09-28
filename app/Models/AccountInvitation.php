<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountInvitation extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'used_at',
    ];


    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}