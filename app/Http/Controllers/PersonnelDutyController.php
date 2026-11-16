<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Personnel;
use App\Models\PersonnelDuty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PersonnelDutyController extends Controller
{
    private const TIMEZONE = 'Asia/Manila';


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel - My Duties
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $personnel =
            $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Synchronize Event Statuses
        |--------------------------------------------------------------------------
        */

        Event::syncScheduledStatuses();


        /*
        |--------------------------------------------------------------------------
        | Create Missing Duty Records
        |--------------------------------------------------------------------------
        */

        $this->syncPersonnelDutyRecords(
            $personnel->personnel_id
        );


        /*
        |--------------------------------------------------------------------------
        | Synchronize Duty Statuses
        |--------------------------------------------------------------------------
        */

        $this->syncPersonnelDutyStatuses(
            $personnel->personnel_id
        );


        /*
        |--------------------------------------------------------------------------
        | Retrieve Assigned Duties
        |--------------------------------------------------------------------------
        */

        $duties =
            DB::table(
                'event_assignments as assignment'
            )
            ->join(
                'events',
                'events.event_id',
                '=',
                'assignment.event_id'
            )
            ->leftJoin(
                'personnel_duties as duty',
                'duty.assignment_id',
                '=',
                'assignment.assignment_id'
            )
            ->where(
                'assignment.personnel_id',
                $personnel->personnel_id
            )
            ->where(
                'events.status',
                '!=',
                'Cancelled'
            )
            ->select([
                'assignment.assignment_id',

                'events.event_id',
                'events.event_name',
                'events.event_date',
                'events.start_time',
                'events.end_time',
                'events.location',
                'events.status as event_status',

                'duty.duty_id',
                'duty.check_in_at',
                'duty.check_out_at',
                'duty.duty_status',
                'duty.completed_minutes',
            ])
            ->orderByDesc(
                'events.event_date'
            )
            ->orderByDesc(
                'events.start_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $duties->count(),

            'assigned' =>
                $duties
                    ->where(
                        'duty_status',
                        'Assigned'
                    )
                    ->count(),

            'on_duty' =>
                $duties
                    ->where(
                        'duty_status',
                        'On Duty'
                    )
                    ->count(),

            'completed' =>
                $duties
                    ->where(
                        'duty_status',
                        'Completed'
                    )
                    ->count(),

            'missed' =>
                $duties
                    ->where(
                        'duty_status',
                        'Missed'
                    )
                    ->count(),

            'incomplete' =>
                $duties
                    ->where(
                        'duty_status',
                        'Incomplete'
                    )
                    ->count(),

        ];


        return view(
            'personnel-duties.index',
            compact(
                'personnel',
                'duties',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel - Check In
    |--------------------------------------------------------------------------
    */

    public function checkIn(
        string $assignment
    ) {

        $personnel =
            $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Verify Assignment
        |--------------------------------------------------------------------------
        */

        $assignmentRecord =
            $this->ownedAssignment(
                $assignment,
                $personnel->personnel_id
            );


        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        $event =
            Event::where(
                'event_id',
                $assignmentRecord->event_id
            )
            ->firstOrFail();


        $event->syncScheduledStatus();

        $event->refresh();


        /*
        |--------------------------------------------------------------------------
        | Check-In Allowed Only During Ongoing Event
        |--------------------------------------------------------------------------
        */

        if (
            $event->status
            !== 'Ongoing'
        ) {

            throw ValidationException::withMessages([

                'duty' =>
                    'Duty check-in is only available while the event is ongoing.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Duty Record
        |--------------------------------------------------------------------------
        */

        $duty =
            PersonnelDuty::firstOrCreate(
                [
                    'assignment_id' =>
                        $assignmentRecord
                            ->assignment_id,
                ],
                [
                    'event_id' =>
                        $assignmentRecord
                            ->event_id,

                    'personnel_id' =>
                        $personnel
                            ->personnel_id,

                    'duty_status' =>
                        'Assigned',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Check-In
        |--------------------------------------------------------------------------
        */

        if (
            $duty->check_in_at
        ) {

            throw ValidationException::withMessages([

                'duty' =>
                    'You have already checked in for this duty.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Save Check-In
        |--------------------------------------------------------------------------
        */

        $duty->update([

            'check_in_at' =>
                Carbon::now(
                    self::TIMEZONE
                ),

            'duty_status' =>
                'On Duty',

        ]);


        return redirect()
            ->route(
                'personnel-duties.index'
            )
            ->with(
                'success',
                'Duty check-in recorded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel - Check Out
    |--------------------------------------------------------------------------
    */

    public function checkOut(
        string $assignment
    ) {

        $personnel =
            $this->attendancePersonnel();


        /*
        |--------------------------------------------------------------------------
        | Verify Assignment
        |--------------------------------------------------------------------------
        */

        $assignmentRecord =
            $this->ownedAssignment(
                $assignment,
                $personnel->personnel_id
            );


        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        $event =
            Event::where(
                'event_id',
                $assignmentRecord->event_id
            )
            ->firstOrFail();


        $event->syncScheduledStatus();

        $event->refresh();


        /*
        |--------------------------------------------------------------------------
        | Check-Out Allowed Only During Ongoing Event
        |--------------------------------------------------------------------------
        */

        if (
            $event->status
            !== 'Ongoing'
        ) {

            throw ValidationException::withMessages([

                'duty' =>
                    'Duty check-out is only available while the event is ongoing.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Duty
        |--------------------------------------------------------------------------
        */

        $duty =
            PersonnelDuty::where(
                'assignment_id',
                $assignmentRecord
                    ->assignment_id
            )
            ->where(
                'personnel_id',
                $personnel
                    ->personnel_id
            )
            ->first();


        if (
            !$duty
            ||
            !$duty->check_in_at
        ) {

            throw ValidationException::withMessages([

                'duty' =>
                    'You must check in before checking out.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Check-Out
        |--------------------------------------------------------------------------
        */

        if (
            $duty->check_out_at
        ) {

            throw ValidationException::withMessages([

                'duty' =>
                    'You have already checked out from this duty.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Record Check-Out
        |--------------------------------------------------------------------------
        */

        $checkOut =
            Carbon::now(
                self::TIMEZONE
            );


        $checkIn =
            Carbon::parse(
                $duty->check_in_at,
                self::TIMEZONE
            );


        $completedMinutes =
            max(
                0,
                $checkIn->diffInMinutes(
                    $checkOut
                )
            );


        $duty->update([

            'check_out_at' =>
                $checkOut,

            'completed_minutes' =>
                $completedMinutes,

            'duty_status' =>
                'Completed',

        ]);


        return redirect()
            ->route(
                'personnel-duties.index'
            )
            ->with(
                'success',
                'Duty check-out recorded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Department Staff - Duty Monitoring
    |--------------------------------------------------------------------------
    */

    /**
     * Department-scoped duty monitoring. The original synchronization rules remain
     * intact. CSV and PDF exports use the exact same filtered query as the page.
     */
    public function staffIndex(Request $request)
    {
        $user = $this->departmentStaffUser();

        // Preserve the existing automatic duty lifecycle.
        Event::syncScheduledStatuses();
        $this->syncDepartmentDutyRecords($user->department_id);
        $this->syncDepartmentDutyStatuses($user->department_id);

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'event' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'in:Assigned,On Duty,Completed,Missed,Incomplete'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date_format:Y-m-d', ...($request->filled('date_from') ? ['after_or_equal:date_from'] : [])],
            'sort' => ['nullable', 'in:newest,oldest,personnel,event,duration'],
            'per_page' => ['nullable', 'in:5,10,20'],
            'export' => ['nullable', 'in:csv,pdf'],
        ]);

        $filters = [
            'search' => trim((string) ($validated['search'] ?? '')),
            'event' => $validated['event'] ?? null,
            'status' => $validated['status'] ?? '',
            'date_from' => $validated['date_from'] ?? '',
            'date_to' => $validated['date_to'] ?? '',
            'sort' => $validated['sort'] ?? 'newest',
            'per_page' => (int) ($validated['per_page'] ?? 5),
        ];

        $events = Event::query()
            ->where('department_id', $user->department_id)
            ->where('status', '!=', 'Cancelled')
            ->orderByDesc('event_date')
            ->orderByDesc('start_time')
            ->get(['event_id', 'event_name', 'event_date']);

        $base = DB::table('personnel_duties as duty')
            ->join('event_assignments as assignment', 'assignment.assignment_id', '=', 'duty.assignment_id')
            ->join('events', 'events.event_id', '=', 'duty.event_id')
            ->join('personnel', 'personnel.personnel_id', '=', 'duty.personnel_id')
            ->where('events.department_id', $user->department_id)
            ->where('events.status', '!=', 'Cancelled');

        if ($filters['event']) {
            $base->where('events.event_id', $filters['event']);
        }
        if ($filters['status'] !== '') {
            $base->where('duty.duty_status', $filters['status']);
        }
        if ($filters['search'] !== '') {
            $needle = '%' . addcslashes($filters['search'], '%_\\') . '%';
            $base->where(function ($query) use ($needle) {
                $query->where('personnel.first_name', 'like', $needle)
                    ->orWhere('personnel.last_name', 'like', $needle)
                    ->orWhere('personnel.position', 'like', $needle)
                    ->orWhere('events.event_name', 'like', $needle)
                    ->orWhere('events.location', 'like', $needle);
            });
        }
        if ($filters['date_from'] !== '') {
            $base->whereDate('events.event_date', '>=', $filters['date_from']);
        }
        if ($filters['date_to'] !== '') {
            $base->whereDate('events.event_date', '<=', $filters['date_to']);
        }

        $counts = (clone $base)
            ->select('duty.duty_status', DB::raw('COUNT(*) as total'))
            ->groupBy('duty.duty_status')
            ->pluck('total', 'duty_status');
        $summary = ['total' => (int) $counts->sum()];
        foreach (['Assigned', 'On Duty', 'Completed', 'Missed', 'Incomplete'] as $status) {
            $summary[strtolower(str_replace(' ', '_', $status))] = (int) ($counts[$status] ?? 0);
        }
        $summary['attention'] = $summary['missed'] + $summary['incomplete'];
        $summary['active'] = $summary['assigned'] + $summary['on_duty'];

        $query = (clone $base)->select([
            'duty.duty_id', 'duty.assignment_id', 'duty.check_in_at',
            'duty.check_out_at', 'duty.duty_status', 'duty.completed_minutes',
            'events.event_id', 'events.event_name', 'events.event_date',
            'events.start_time', 'events.end_time', 'events.location',
            'events.status as event_status',
            'personnel.personnel_id', 'personnel.first_name',
            'personnel.last_name', 'personnel.position',
        ]);

        switch ($filters['sort']) {
            case 'oldest':
                $query->orderBy('events.event_date')->orderBy('events.start_time');
                break;
            case 'personnel':
                $query->orderBy('personnel.last_name')->orderBy('personnel.first_name');
                break;
            case 'event':
                $query->orderBy('events.event_name')->orderByDesc('events.event_date');
                break;
            case 'duration':
                $query->orderByDesc('duty.completed_minutes')->orderByDesc('events.event_date');
                break;
            default:
                $query->orderByDesc('events.event_date')->orderByDesc('events.start_time');
        }
        $query->orderByDesc('duty.duty_id');

        if ($request->filled('export')) {
            $records = $query->get();
            $stamp = now('Asia/Manila')->format('Ymd-His');

            if ($request->input('export') === 'csv') {
                return response()->streamDownload(function () use ($records): void {
                    $handle = fopen('php://output', 'wb');
                    fwrite($handle, "\xEF\xBB\xBF"); // UTF-8 BOM for spreadsheet applications.
                    fputcsv($handle, [
                        'Personnel', 'Position', 'Event', 'Event Date', 'Scheduled Start',
                        'Scheduled End', 'Location', 'Check In', 'Check Out',
                        'Duty Minutes', 'Duty Status',
                    ]);
                    $safe = static function ($value): string {
                        $text = (string) ($value ?? '');
                        // Prevent CSV formula execution in Excel/WPS/Sheets.
                        return preg_match('/^[\s]*[=+@\-]/u', $text) ? "'" . $text : $text;
                    };
                    foreach ($records as $duty) {
                        fputcsv($handle, [
                            $safe(trim($duty->first_name . ' ' . $duty->last_name)),
                            $safe($duty->position),
                            $safe($duty->event_name),
                            $duty->event_date,
                            $duty->start_time,
                            $duty->end_time,
                            $safe($duty->location),
                            $duty->check_in_at ?: '',
                            $duty->check_out_at ?: '',
                            $duty->completed_minutes ?? '',
                            $duty->duty_status,
                        ]);
                    }
                    fclose($handle);
                }, 'DySign-Duty-Monitoring-' . $stamp . '.csv', [
                    'Content-Type' => 'text/csv; charset=UTF-8',
                    'Cache-Control' => 'private, no-store',
                    'X-Content-Type-Options' => 'nosniff',
                ]);
            }

            $departmentName = (string) ($user->department?->department_name ?? 'Department ' . $user->department_id);
            $pdf = \App\Support\StaffDutyMonitoringPdf::render(
                $departmentName, $records->all(), $summary, $filters
            );
            return response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="DySign-Duty-Monitoring-' . $stamp . '.pdf"',
                'Content-Length' => (string) strlen($pdf),
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }

        $duties = $query->paginate($filters['per_page'])->withQueryString();
        return view('personnel-duties.staff', compact('events', 'duties', 'summary', 'filters'));
    }


    /*
    |--------------------------------------------------------------------------
    | Create Missing Duty Records - Personnel
    |--------------------------------------------------------------------------
    */

    private function syncPersonnelDutyRecords(
        int $personnelId
    ): void {

        $assignments =
            DB::table(
                'event_assignments as assignment'
            )
            ->join(
                'events',
                'events.event_id',
                '=',
                'assignment.event_id'
            )
            ->where(
                'assignment.personnel_id',
                $personnelId
            )
            ->where(
                'events.status',
                '!=',
                'Cancelled'
            )
            ->select([
                'assignment.assignment_id',
                'assignment.event_id',
                'assignment.personnel_id',
            ])
            ->get();


        foreach (
            $assignments
            as $assignment
        ) {

            PersonnelDuty::firstOrCreate(

                [
                    'assignment_id' =>
                        $assignment
                            ->assignment_id,
                ],

                [
                    'event_id' =>
                        $assignment
                            ->event_id,

                    'personnel_id' =>
                        $assignment
                            ->personnel_id,

                    'duty_status' =>
                        'Assigned',
                ]

            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Create Missing Duty Records - Department
    |--------------------------------------------------------------------------
    */

    private function syncDepartmentDutyRecords(
        int $departmentId
    ): void {

        $assignments =
            DB::table(
                'event_assignments as assignment'
            )
            ->join(
                'events',
                'events.event_id',
                '=',
                'assignment.event_id'
            )
            ->where(
                'events.department_id',
                $departmentId
            )
            ->where(
                'events.status',
                '!=',
                'Cancelled'
            )
            ->select([
                'assignment.assignment_id',
                'assignment.event_id',
                'assignment.personnel_id',
            ])
            ->get();


        foreach (
            $assignments
            as $assignment
        ) {

            PersonnelDuty::firstOrCreate(

                [
                    'assignment_id' =>
                        $assignment
                            ->assignment_id,
                ],

                [
                    'event_id' =>
                        $assignment
                            ->event_id,

                    'personnel_id' =>
                        $assignment
                            ->personnel_id,

                    'duty_status' =>
                        'Assigned',
                ]

            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Synchronize Personnel Duty Statuses
    |--------------------------------------------------------------------------
    */

    private function syncPersonnelDutyStatuses(
        int $personnelId
    ): void {

        $duties =
            PersonnelDuty::where(
                'personnel_id',
                $personnelId
            )
            ->get();


        foreach (
            $duties
            as $duty
        ) {

            $this->syncDutyStatus(
                $duty
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Synchronize Department Duty Statuses
    |--------------------------------------------------------------------------
    */

    private function syncDepartmentDutyStatuses(
        int $departmentId
    ): void {

        $duties =
            PersonnelDuty::whereHas(
                'event',
                function ($query) use (
                    $departmentId
                ) {

                    $query->where(
                        'department_id',
                        $departmentId
                    );

                }
            )
            ->get();


        foreach (
            $duties
            as $duty
        ) {

            $this->syncDutyStatus(
                $duty
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Automatic Duty Status
    |--------------------------------------------------------------------------
    */

    private function syncDutyStatus(
        PersonnelDuty $duty
    ): void {

        $event =
            Event::where(
                'event_id',
                $duty->event_id
            )
            ->first();


        if (
            !$event
        ) {

            return;
        }


        $event->syncScheduledStatus();

        $event->refresh();


        /*
        |--------------------------------------------------------------------------
        | Cancelled Events
        |--------------------------------------------------------------------------
        */

        if (
            $event->status
            === 'Cancelled'
        ) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Completed Duty
        |--------------------------------------------------------------------------
        */

        if (
            $duty->check_in_at
            &&
            $duty->check_out_at
        ) {

            $checkIn =
                Carbon::parse(
                    $duty->check_in_at,
                    self::TIMEZONE
                );


            $checkOut =
                Carbon::parse(
                    $duty->check_out_at,
                    self::TIMEZONE
                );


            $minutes =
                max(
                    0,
                    $checkIn->diffInMinutes(
                        $checkOut
                    )
                );


            $duty->update([

                'duty_status' =>
                    'Completed',

                'completed_minutes' =>
                    $minutes,

            ]);


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Event Already Completed
        |--------------------------------------------------------------------------
        */

        if (
            $event->status
            === 'Completed'
        ) {

            /*
            |--------------------------------------------------------------------------
            | Never Checked In
            |--------------------------------------------------------------------------
            */

            if (
                !$duty->check_in_at
            ) {

                $duty->update([

                    'duty_status' =>
                        'Missed',

                    'completed_minutes' =>
                        null,

                ]);


                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Checked In But Never Checked Out
            |--------------------------------------------------------------------------
            */

            $duty->update([

                'duty_status' =>
                    'Incomplete',

                'completed_minutes' =>
                    null,

            ]);


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Personnel Currently On Duty
        |--------------------------------------------------------------------------
        */

        if (
            $duty->check_in_at
            &&
            !$duty->check_out_at
        ) {

            $duty->update([

                'duty_status' =>
                    'On Duty',

            ]);


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Upcoming / Waiting
        |--------------------------------------------------------------------------
        */

        $duty->update([

            'duty_status' =>
                'Assigned',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Owned Assignment
    |--------------------------------------------------------------------------
    */

    private function ownedAssignment(
        string $assignmentId,
        int $personnelId
    ): object {

        $assignment =
            DB::table(
                'event_assignments'
            )
            ->where(
                'assignment_id',
                $assignmentId
            )
            ->where(
                'personnel_id',
                $personnelId
            )
            ->first();


        if (
            !$assignment
        ) {

            abort(403);
        }


        return $assignment;
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Personnel Authorization
    |--------------------------------------------------------------------------
    */

    private function attendancePersonnel(): Personnel
    {
        $user =
            auth()->user();


        if (
            !$user
            ||
            (
                $user->role->role_name
                ?? null
            )
            !== 'Attendance Personnel'
        ) {

            abort(403);
        }


        $personnel =
            $user->personnel;


        if (
            !$personnel
        ) {

            abort(
                403,
                'No personnel profile is linked to this account.'
            );
        }


        return $personnel;
    }


    /*
    |--------------------------------------------------------------------------
    | Department Staff Authorization
    |--------------------------------------------------------------------------
    */

    private function departmentStaffUser()
    {
        $user =
            auth()->user();


        if (
            !$user
            ||
            (
                $user->role->role_name
                ?? null
            )
            !== 'Department Staff'
        ) {

            abort(403);
        }


        if (
            !$user->department_id
        ) {

            abort(
                403,
                'Your account is not linked to a department.'
            );
        }


        return $user;
    }
}