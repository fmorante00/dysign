<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    protected $table = 'personnel';
    
    protected $primaryKey = 'personnel_id';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'department',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}