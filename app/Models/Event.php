<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $primaryKey = 'event_id';

    private const EVENT_TIMEZONE = 'Asia/Manila';

    protected $fillable = [
        'event_name',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'created_by',
        'department_id',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Automatic Event Status
    |--------------------------------------------------------------------------
    |
    | Upcoming  = current time is before event start
    | Ongoing   = current time is between start and end
    | Completed = current time is after event end
    | Cancelled = manual override and will never be changed automatically
    |
    */

    public function calculateScheduledStatus(
        ?Carbon $now = null
    ): string {

        if ($this->status === 'Cancelled') {
            return 'Cancelled';
        }

        $now = $now
            ? $now->copy()->setTimezone(self::EVENT_TIMEZONE)
            : Carbon::now(self::EVENT_TIMEZONE);

        $eventDate = $this->event_date
            ? Carbon::parse(
                $this->event_date
            )->format('Y-m-d')
            : null;

        if (
            !$eventDate
            || !$this->start_time
            || !$this->end_time
        ) {
            return $this->status ?? 'Upcoming';
        }

        $eventStart = Carbon::parse(
            $eventDate . ' ' . $this->start_time,
            self::EVENT_TIMEZONE
        );

        $eventEnd = Carbon::parse(
            $eventDate . ' ' . $this->end_time,
            self::EVENT_TIMEZONE
        );


        if ($now->lt($eventStart)) {
            return 'Upcoming';
        }

        if ($now->lte($eventEnd)) {
            return 'Ongoing';
        }

        return 'Completed';
    }


    public function syncScheduledStatus(
        ?Carbon $now = null
    ): bool {

        if ($this->status === 'Cancelled') {
            return false;
        }

        $newStatus =
            $this->calculateScheduledStatus($now);

        if ($this->status === $newStatus) {
            return false;
        }

        $this->status = $newStatus;
        $this->save();

        return true;
    }


    public static function syncScheduledStatuses(): void
    {
        $now = Carbon::now(self::EVENT_TIMEZONE);

        static::query()
            ->where(
                'status',
                '!=',
                'Cancelled'
            )
            ->get()
            ->each(
                function (Event $event) use ($now) {

                    $event->syncScheduledStatus(
                        $now
                    );

                }
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'user_id'
        );
    }


    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'department_id'
        );
    }


    public function assignments()
    {
        return $this->hasMany(
            EventAssignment::class,
            'event_id',
            'event_id'
        );
    }


    public function assignedPersonnel()
    {
        return $this->belongsToMany(
            Personnel::class,
            'event_assignments',
            'event_id',
            'personnel_id',
            'event_id',
            'personnel_id'
        )
            ->withPivot([
                'assignment_id',
                'assigned_by',
            ])
            ->withTimestamps();
    }


    public function attendanceRecords()
    {
        return $this->hasMany(
            AttendanceRecord::class,
            'event_id',
            'event_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Event Eligibility
    |--------------------------------------------------------------------------
    */

    public function eligibilityRules()
    {
        return $this->hasMany(
            EventEligibilityRule::class,
            'event_id',
            'event_id'
        );
    }


    public function eligibleStudents()
    {
        return $this->hasMany(
            EventEligibleStudent::class,
            'event_id',
            'event_id'
        );
    }
}