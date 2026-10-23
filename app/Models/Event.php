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

        /*
        |--------------------------------------------------------------------------
        | Preserve Cancelled Status
        |--------------------------------------------------------------------------
        */

        if ($this->status === 'Cancelled') {
            return 'Cancelled';
        }


        /*
        |--------------------------------------------------------------------------
        | Current Date and Time
        |--------------------------------------------------------------------------
        */

        $now = $now
            ? $now->copy()->setTimezone(self::EVENT_TIMEZONE)
            : Carbon::now(self::EVENT_TIMEZONE);


        /*
        |--------------------------------------------------------------------------
        | Validate Schedule Information
        |--------------------------------------------------------------------------
        */

        if (
            !$this->event_date
            || !$this->start_time
            || !$this->end_time
        ) {
            return $this->status ?? 'Upcoming';
        }


        /*
        |--------------------------------------------------------------------------
        | Normalize Event Date
        |--------------------------------------------------------------------------
        |
        | event_date is cast as a Carbon date object.
        | We explicitly format it to Y-m-d so that the automatic
        | 00:00:00 portion is never included when combining it
        | with start_time or end_time.
        |
        */

        $eventDate = Carbon::parse(
            $this->event_date
        )->format('Y-m-d');


        /*
        |--------------------------------------------------------------------------
        | Normalize Start and End Time
        |--------------------------------------------------------------------------
        |
        | We also normalize the time fields to H:i:s.
        | This prevents Carbon from receiving something like:
        |
        | 2026-10-05 00:00:00 07:30:00
        |
        */

        $startTime = Carbon::parse(
            $this->start_time
        )->format('H:i:s');

        $endTime = Carbon::parse(
            $this->end_time
        )->format('H:i:s');


        /*
        |--------------------------------------------------------------------------
        | Build Full Event DateTime
        |--------------------------------------------------------------------------
        */

        $eventStart = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $eventDate . ' ' . $startTime,
            self::EVENT_TIMEZONE
        );

        $eventEnd = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $eventDate . ' ' . $endTime,
            self::EVENT_TIMEZONE
        );


        /*
        |--------------------------------------------------------------------------
        | Determine Automatic Status
        |--------------------------------------------------------------------------
        */

        if ($now->lt($eventStart)) {
            return 'Upcoming';
        }

        if ($now->lte($eventEnd)) {
            return 'Ongoing';
        }

        return 'Completed';
    }


    /*
    |--------------------------------------------------------------------------
    | Synchronize Single Event Status
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Synchronize All Event Statuses
    |--------------------------------------------------------------------------
    */

    public static function syncScheduledStatuses(): void
    {
        $now = Carbon::now(
            self::EVENT_TIMEZONE
        );

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

    public function evaluationForms()
    {
        return $this->hasMany(
            EvaluationForm::class,
            'event_id',
            'event_id'
        );
    }
}