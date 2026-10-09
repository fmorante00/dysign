<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $primaryKey =
        'activity_log_id';


    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'status',
        'ip_address',
        'user_agent',
    ];


    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }


    public static function record(
        string $action,
        string $module,
        ?string $description = null,
        string $status = 'Success'
    ): self {

        return self::create([

            'user_id' =>
                auth()->id(),

            'action' =>
                $action,

            'module' =>
                $module,

            'description' =>
                $description,

            'status' =>
                $status,

            'ip_address' =>
                request()->ip(),

            'user_agent' =>
                request()->userAgent(),

        ]);
    }
}