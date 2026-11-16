<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\AnnouncementRecipient;
use App\Models\AttendanceDiscrepancy;
use App\Models\AttendanceDiscrepancyRequest;
use App\Models\AttendanceException;
use App\Models\AttendanceRecord;
use App\Models\EvaluationForm;
use App\Models\EvaluationSubmission;
use App\Models\Event;
use App\Models\EventAssignment;
use App\Models\EventRegistration;
use App\Models\ExcuseRequest;
use App\Models\PersonnelAvailability;
use App\Models\PersonnelDuty;
use App\Models\RfidCardAssignment;
use App\Models\User;
use App\Notifications\DySignNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * In-app notifications for successful, material workflow changes.
 * The observer runs after database commits. Recipient lookup is always
 * role-, department-, assignment-, or student-scoped, never a global blast.
 * This service is deliberately NOT called for reads, filters, or bulk status refreshes.
 */
final class NotificationRouter
{
    private static function users(string $role, ?int $departmentId = null)
    {
        return User::query()->where('status', 'Active')
            ->whereHas('role', fn ($q) => $q->where('role_name', $role))
            ->when($departmentId !== null, fn ($q) => $q->where('department_id', $departmentId))
            ->get();
    }

    private static function student(int $id)
    {
        return User::query()->where('status', 'Active')->where('student_id', $id)
            ->whereHas('role', fn ($q) => $q->where('role_name', 'Student Account'))->get();
    }

    private static function assigned(Event $event)
    {
        return User::query()->where('status', 'Active')
            ->whereHas('role', fn ($q) => $q->where('role_name', 'Attendance Personnel'))
            ->whereHas('personnel.assignments', fn ($q) => $q->where('event_id', $event->event_id))
            ->get();
    }

    private static function eventUsers(?Event $event)
    {
        if (!$event) return collect();
        return self::users('Department Staff', (int) $event->department_id);
    }

    private static function deliver($recipients, string $title, string $message, string $url, string $category): void
    {
        if (!str_starts_with($url, '/') || str_starts_with($url, '//')) $url = '/dashboard';
        foreach (collect($recipients)->filter()->unique('user_id') as $user) {
            try {
                if ($user->status === 'Active') {
                    $user->notify(new DySignNotification($title, $message, self::destination($user, $url), $category));
                }
            } catch (\Throwable $e) {
                Log::warning('DySign notification could not be saved', [
                    'recipient_id' => $user->user_id, 'category' => $category,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /** Always redirect each recipient into pages their role can actually open. */
    private static function destination(User $user, string $url): string
    {
        $role = $user->role?->role_name;
        if ($url === '/my-profile' || $url === '/dashboard') return $url;
        if ($role === 'Administrator') return '/dashboard';
        if ($role === 'Student Account') {
            if (str_contains($url, 'discrepanc')) return '/student/attendance-discrepancies';
            if (str_contains($url, 'excuse')) return '/student/excuse-requests';
            if (str_contains($url, 'evaluation')) return '/student/evaluations';
            if (str_contains($url, 'announcement')) return '/student/announcements';
            if (str_contains($url, 'attendance') || str_contains($url, 'duties')) return '/student/attendance-history';
            return route('student.registrations.index', [], false);
        }
        if ($role === 'Attendance Personnel') {
            if (str_contains($url, 'discrepanc')) return '/my-attendance-discrepancies';
            if (str_contains($url, 'availability')) return '/my-availability';
            if (str_contains($url, 'duties')) return '/my-duties';
            if (str_contains($url, 'exception')) return '/my-attendance-exceptions';
            return '/my-events';
        }
        if ($role === 'Department Staff') {
            if (str_contains($url, 'discrepanc')) return '/staff/attendance-discrepancies';
            if (str_contains($url, 'excuse')) return '/staff/excuse-requests';
            if (str_contains($url, 'exception')) return '/staff/attendance-exceptions';
            if (str_contains($url, 'availability')) return '/personnel-availability';
            if (str_contains($url, 'duties')) return '/personnel-duty-monitoring';
            if (str_contains($url, 'evaluation')) return '/staff/evaluation-analysis';
            if (str_contains($url, 'events/') && preg_match('~\A/events/\d+\z~', $url)) return $url;
            return '/events';
        }
        return '/dashboard';
    }

    private static function actor(): \Illuminate\Support\Collection
    {
        $user = auth()->user();
        return $user && $user->status === 'Active' ? collect([$user]) : collect();
    }

    private static function stakeholders(?Event $event, bool $includeStudents = false)
    {
        if (!$event) return collect();
        $users = self::eventUsers($event)->merge(self::assigned($event));
        if ($includeStudents) {
            User::query()->where('status', 'Active')
                ->whereHas('role', fn ($q) => $q->where('role_name', 'Student Account'))
                ->whereHas('student.eventEligibilities', fn ($q) => $q->where('event_id', $event->event_id))
                ->chunkById(250, function ($chunk) use (&$users) { $users = $users->merge($chunk); }, 'user_id');
        }
        return $users;
    }

    /** Called by Eloquent observer only after the transaction is committed. */
    public static function recorded(Model $model, string $action): void
    {
        try { self::routeChange($model, $action); }
        catch (\Throwable $e) {
            Log::error('DySign notification routing error', [
                'model' => get_class($model), 'id' => $model->getKey(),
                'action' => $action, 'error' => $e->getMessage(),
            ]);
        }
    }

    private static function routeChange(Model $m, string $a): void
    {
        if ($m instanceof Event) {
            if ($a === 'created') {
                self::deliver(self::users('Administrator')->merge(self::eventUsers($m))->merge(self::actor()),
                    'Event created', "{$m->event_name} was created for {$m->event_date?->format('M j, Y')}.", '/events/'.$m->event_id, 'Events');
            } elseif ($a === 'updated' && ($m->wasChanged(['event_name','event_date','start_time','end_time','location']) || ($m->wasChanged('status') && $m->status === 'Cancelled'))) {
                self::deliver(self::users('Administrator')->merge(self::stakeholders($m, true))->merge(self::actor()),
                    $m->status === 'Cancelled' ? 'Event cancelled' : 'Event details updated',
                    "Event: {$m->event_name}. Review the latest official details.", '/dashboard', 'Events');
            }
            return;
        }
        if ($m instanceof EventAssignment && $a === 'created') {
            self::assignment($m->event_id, [$m->personnel_id], 'assigned', false); return;
        }
        if ($m instanceof EventRegistration) {
            if ($a === 'created' || ($a === 'updated' && $m->wasChanged('status'))) {
                $event = $m->event; if (!$event) return;
                $label = (string) ($m->status ?: 'Registered');
                self::deliver(self::student((int)$m->student_id)->merge(self::eventUsers($event))->merge(self::actor()),
                    'Event registration '.$label, "Registration for {$event->event_name}: {$label}.", route('student.registrations.index', [], false), 'Events');
            }
            return;
        }
        if ($m instanceof AttendanceRecord) {
            if ($a === 'created' || ($a === 'updated' && $m->wasChanged(['time_in','time_out','status']))) {
                $event = $m->event; if (!$event) return;
                // RFID scanners already receive immediate UI feedback. Alert only
                // the student to avoid hundreds of scanner notifications per event.
                self::deliver(self::student((int)$m->student_id),
                    'Attendance updated', "{$event->event_name}: {$m->status}. Check your recorded Time In/Out.",
                    '/student/attendance-history', 'Attendance');
            }
            return;
        }
        if ($m instanceof ExcuseRequest) {
            $event = $m->event; if (!$event) return;
            if ($a === 'created') {
                self::deliver(self::eventUsers($event)->merge(self::student((int)$m->student_id))->merge(self::actor()),
                    'Excuse request submitted', "An excuse was submitted for {$event->event_name}.", '/staff/excuse-requests', 'Requests');
            } elseif ($a === 'updated' && $m->wasChanged('status')) {
                self::deliver(self::student((int)$m->student_id)->merge(self::eventUsers($event))->merge(self::actor()),
                    'Excuse request '.$m->status, "Your excuse review for {$event->event_name} is {$m->status}.",
                    '/student/excuse-requests', 'Requests');
            }
            return;
        }
        if ($m instanceof AttendanceDiscrepancy) {
            $event = $m->event; if (!$event) return;
            if ($a === 'created') {
                self::deliver(self::student((int)$m->student_id)->merge(self::eventUsers($event))->merge(self::assigned($event)),
                    'Attendance discrepancy submitted', "New discrepancy for {$event->event_name} awaits verification.",
                    '/my-attendance-discrepancies', 'Requests');
            } elseif ($a === 'updated' && $m->wasChanged(['verification_status','status'])) {
                $reviewed = $m->wasChanged('status') && $m->status !== 'Pending';
                self::deliver(self::student((int)$m->student_id)->merge(self::eventUsers($event))->merge(self::assigned($event)),
                    $reviewed ? 'Discrepancy decision: '.$m->status : 'Discrepancy verification updated',
                    "An attendance concern for {$event->event_name} was updated.",
                    '/student/attendance-discrepancies', 'Requests');
            }
            return;
        }
        if ($m instanceof AttendanceDiscrepancyRequest) {
            if ($a === 'created' || ($a === 'updated' && $m->wasChanged('status'))) {
                $event = $m->event; if (!$event) return;
                self::deliver(self::student((int)$m->student_id)->merge(self::assigned($event)),
                    'Attendance request '.$m->status, "Attendance request for {$event->event_name}: {$m->status}.",
                    '/dashboard', 'Requests');
            }
            return;
        }
        if ($m instanceof AttendanceException) {
            $event = $m->event; if (!$event) return;
            if ($a === 'created') {
                self::deliver(self::eventUsers($event)->merge(self::assigned($event))->merge(self::actor()),
                    'Attendance exception reported', "An attendance exception for {$event->event_name} needs review.",
                    '/staff/attendance-exceptions', 'Requests');
            } elseif ($a === 'updated' && $m->wasChanged(['status','checked_out_at'])) {
                self::deliver(self::student((int)$m->student_id)->merge(self::eventUsers($event))->merge(self::assigned($event)),
                    'Attendance exception updated', "An attendance exception for {$event->event_name} is {$m->status}.",
                    '/staff/attendance-exceptions', 'Attendance');
            }
            return;
        }
        if ($m instanceof PersonnelDuty) {
            if (($a === 'created' && ($m->check_in_at || $m->check_out_at)) ||
                ($a === 'updated' && $m->wasChanged(['check_in_at','check_out_at']))) {
                $event = $m->event; if (!$event) return;
                $person = $m->personnel?->user;
                self::deliver(collect([$person])->merge(self::eventUsers($event))->merge(self::actor()),
                    $m->check_out_at ? 'Duty checked out' : 'Duty checked in',
                    "Duty attendance for {$event->event_name} was recorded.", '/my-duties', 'Attendance');
            }
            return;
        }
        if ($m instanceof PersonnelAvailability) {
            if (in_array($a, ['created','updated','deleted'], true)) {
                $person = $m->personnel?->user;
                if (!$person) return;
                $departmentStaff = $person->department_id !== null
                    ? self::users('Department Staff', (int)$person->department_id)
                    : collect();
                self::deliver(collect([$person])->merge($departmentStaff),
                    'Personnel availability '.($a === 'deleted' ? 'removed' : 'updated'),
                    "Availability schedule for {$person->name} was {$a}.", '/my-availability', 'Schedules');
            }
            return;
        }
        if ($m instanceof EvaluationForm) {
            if ($a === 'updated' && $m->wasChanged('status')) {
                $event = $m->event; if (!$event) return;
                $users = self::eventUsers($event)->merge(self::actor());
                if ($m->status === 'Published') $users = $users->merge(self::eligibleStudents($event));
                self::deliver($users, 'Event evaluation '.$m->status,
                    "Evaluation for {$event->event_name} is {$m->status}.", '/student/evaluations', 'Events');
            }
            return;
        }
        if ($m instanceof EvaluationSubmission && $a === 'created') {
            $event = $m->form?->event; if (!$event) return;
            self::deliver(self::student((int)$m->student_id)->merge(self::eventUsers($event)),
                'Evaluation submitted', "Feedback for {$event->event_name} was submitted successfully.",
                '/student/evaluations', 'Events'); return;
        }
        if ($m instanceof Announcement && $a === 'created') {
            $event = $m->event; if (!$event) return;
            self::deliver(self::eventUsers($event)->merge(self::actor()), 'Announcement created',
                "Announcement for {$event->event_name} was created.", '/dashboard', 'Events'); return;
        }
        if ($m instanceof AnnouncementRecipient && $a === 'created') {
            $announcement = $m->announcement; if (!$announcement) return;
            $users = $m->user_id ? User::query()->whereKey($m->user_id)->get() : collect();
            if ($m->student_id) $users = $users->merge(self::student((int)$m->student_id));
            self::deliver($users, 'New announcement', (string)$announcement->subject,
                '/student/announcements', 'Events'); return;
        }
        if ($m instanceof RfidCardAssignment) {
            if ($a === 'created' || ($a === 'updated' && $m->wasChanged('status'))) {
                self::deliver(self::student((int)$m->student_id)->merge(self::users('Administrator'))->merge(self::actor()),
                    'RFID card '.$m->status, 'RFID card assignment status was updated.',
                    '/dashboard', 'Accounts');
            }
            return;
        }
        if ($m instanceof User) {
            if ($a === 'created' || ($a === 'updated' && $m->wasChanged(['status','role_id','department_id']))) {
                $recipients = self::users('Administrator')->merge(collect([$m]));
                self::deliver($recipients, $a === 'created' ? 'Account created' : 'Account access updated',
                    "Account status or access for {$m->name} has been updated.", '/my-profile', 'Accounts');
            }
        }
    }

    private static function eligibleStudents(Event $event)
    {
        return User::query()->where('status', 'Active')
            ->whereHas('role', fn ($q) => $q->where('role_name','Student Account'))
            ->whereHas('student.eventEligibilities', fn ($q) => $q->where('event_id', $event->event_id))->get();
    }

    /** Invoked only with actual eligibility diffs after the bulk database transaction. */
    public static function eligibility(Event $event, array $changes): void
    {
        if (!$changes) return;
        foreach (array_chunk($changes, 200, true) as $batch) {
            $users = User::query()->where('status', 'Active')->whereIn('student_id', array_keys($batch))
                ->whereHas('role', fn ($q) => $q->where('role_name','Student Account'))->get();
            foreach ($users as $u) {
                $type = (string)($batch[$u->student_id] ?? 'Optional');
                self::deliver([$u], $type === 'Required' ? 'Required event attendance' : 'Event eligibility updated',
                    "{$event->event_name}: your participation is {$type}. Check the official event details.",
                    route('student.registrations.index', [], false), 'Events');
            }
        }
        self::deliver(self::eventUsers($event)->merge(self::actor()),
            'Event eligibility updated', count($changes)." student eligibility record(s) changed for {$event->event_name}.",
            '/events/'.$event->event_id, 'Events');
    }

    /** One administrator summary per successful student-import batch, not one per row. */
    public static function studentImport(int $created, int $updated, int $failed): void
    {
        if ($created + $updated === 0) return;
        self::deliver(self::users('Administrator'), 'Student import completed',
            "Student import result: {$created} created, {$updated} updated, {$failed} failed.",
            '/students', 'Accounts');
    }

    /** Explicit hook needed for bulk DB::table insert/delete of personnel assignments. */
    public static function assignment(int $eventId, iterable $personnelIds, string $action, bool $notifyStaff = true): void
    {
        $event = Event::find($eventId); if (!$event) return;
        $people = \App\Models\Personnel::query()->with('user')->whereIn('personnel_id', collect($personnelIds)->all())->get();
        foreach ($people as $person) {
            self::deliver(collect([$person->user]), 'Event assignment '.$action,
                "You were {$action} for {$event->event_name}. Review your event schedule.",
                '/my-events', 'Schedules');
        }
        if ($notifyStaff) self::rosterSummary($eventId);
    }

    /** Only one summary per roster save, regardless of how many personnel changed. */
    public static function rosterSummary(int $eventId): void
    {
        $event = Event::find($eventId); if (!$event) return;
        self::deliver(self::eventUsers($event)->merge(self::actor()), 'Personnel assignment updated',
            "Attendance personnel assignments for {$event->event_name} were updated.",
            '/events/'.$event->event_id, 'Schedules');
    }
}
