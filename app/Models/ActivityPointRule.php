<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityPointRule extends Model
{
    protected $table = 'activity_point_rules';

    protected $primaryKey = 'activity_point_rule_id';

    protected $fillable = [
        'event_id',
        'present_points',
        'late_points',
        'created_by',
    ];

    protected $casts = [
        'event_id' => 'integer',
        'present_points' => 'integer',
        'late_points' => 'integer',
        'created_by' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'event_id'
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
