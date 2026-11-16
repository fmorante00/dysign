<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * DySign role-scoped, read-only analytics and CSV reporting.
 * No attendance or eligibility data are ever mutated here.
 */
class AnalyticsController extends Controller
{
    private const TABS = ['overview', 'attendance', 'events', 'participation', 'trends'];
    private const STATUSES = ['Present', 'Late', 'Checked In', 'Incomplete', 'Absent', 'Excused'];

    public function index(Request $request): View
    {
        $user = $this->authorizedUser($request);
        $filters = $this->filters($request, $user);
        $eventsScope = $this->events($user, $filters);
        $attendance = $this->attendance($user, $filters);
        $completedScope = $this->events($user, $filters)->where('e.status', 'Completed');

        $eventCount = (clone $eventsScope)->count();
        $completedCount = (clone $completedScope)->count();
        $attendanceTotal = (clone $attendance)->count();
        $statusRaw = (clone $attendance)->select('ar.status')
            ->selectRaw('COUNT(*) AS total')->groupBy('ar.status')->pluck('total', 'ar.status')->all();
        $statusCounts = [];
        foreach (self::STATUSES as $status) {
            $statusCounts[$status] = (int) ($statusRaw[$status] ?? 0);
        }
        $present = $statusCounts['Present'];
        $late = $statusCounts['Late'];
        $recordedAttendances = $present + $late;
        $onTimeRate = $attendanceTotal ? round($present * 100 / $attendanceTotal, 1) : 0.0;

        $eligibleQuery = DB::table('event_eligible_students as es')
            ->whereIn('es.event_id', (clone $completedScope)->select('e.event_id'));
        if ($this->role($user) === 'Student Account') {
            $eligibleQuery->where('es.student_id', (int) $user->student_id);
        }
        $eligibleCount = (clone $eligibleQuery)->count();
        $qualifiedAttended = (clone $attendance)
            ->whereIn('ar.event_id', (clone $completedScope)->select('e.event_id'))
            ->whereIn('ar.status', ['Present', 'Late'])
            ->whereExists(function ($q) {
                $q->selectRaw('1')->from('event_eligible_students as es')
                    ->whereColumn('es.event_id', 'ar.event_id')
                    ->whereColumn('es.student_id', 'ar.student_id');
            })->count();
        $participationRate = $eligibleCount
            ? round(min(100, $qualifiedAttended * 100 / $eligibleCount), 1) : 0.0;

        // Only records from scoped events contribute to all charts and tables.
        $attendancePerEvent = (clone $attendance)->select('ar.event_id')
            ->selectRaw('COUNT(*) AS records')
            ->selectRaw("SUM(CASE WHEN ar.status IN ('Present','Late') THEN 1 ELSE 0 END) AS attended")
            ->groupBy('ar.event_id');
        $eligiblePerEvent = (clone $eligibleQuery)->select('es.event_id')
            ->selectRaw('COUNT(*) AS eligible')->groupBy('es.event_id');
        $eventRowsQuery = (clone $eventsScope)
            ->leftJoinSub($attendancePerEvent, 'at', 'at.event_id', '=', 'e.event_id')
            ->leftJoinSub($eligiblePerEvent, 'el', 'el.event_id', '=', 'e.event_id')
            ->select('e.event_id', 'e.event_name', 'e.event_date', 'e.status', 'e.location')
            ->selectRaw('COALESCE(at.records, 0) AS recorded_count')
            ->selectRaw('COALESCE(at.attended, 0) AS attended_count')
            ->selectRaw('COALESCE(el.eligible, 0) AS eligible_count')
            ->orderByDesc('e.event_date')->orderByDesc('e.event_id');

        $featuredEvents = (clone $eventRowsQuery)->limit(8)->get();
        $eventRows = (clone $eventRowsQuery)->paginate(8, ['*'], 'page')->withQueryString();
        $attendanceRows = (clone $attendance)
            ->join('events as ev', 'ev.event_id', '=', 'ar.event_id')
            ->join('students as s', 's.student_id', '=', 'ar.student_id')
            ->select('ar.attendance_id', 'ar.status', 'ar.time_in', 'ar.time_out',
                'ev.event_name', 'ev.event_date', 's.student_number', 's.first_name', 's.last_name')
            ->orderByDesc('ev.event_date')->orderByDesc('ar.attendance_id')
            ->paginate(8, ['*'], 'page')->withQueryString();

        $monthRows = (clone $attendance)
            ->join('events as ev', 'ev.event_id', '=', 'ar.event_id')
            ->selectRaw('SUBSTRING(ev.event_date, 1, 7) AS period')
            ->selectRaw('COUNT(*) AS records')
            ->selectRaw("SUM(CASE WHEN ar.status IN ('Present','Late') THEN 1 ELSE 0 END) AS attended")
            ->groupBy('period')->orderBy('period')->get()->keyBy('period');
        $months = [];
        foreach (range(5, 0) as $index) {
            $month = Carbon::now('Asia/Manila')->subMonths($index);
            $key = $month->format('Y-m');
            $months[] = [
                'label' => $month->format('M Y'),
                'records' => (int) ($monthRows[$key]->records ?? 0),
                'attended' => (int) ($monthRows[$key]->attended ?? 0),
            ];
        }
        $maxTrend = max(1, ...array_column($months, 'records'));
        $linePoints = [];
        foreach ($months as $i => $month) {
            $x = 30 + $i * 100;
            $y = 150 - (int) round($month['records'] * 120 / $maxTrend);
            $linePoints[] = $x . ',' . $y;
        }

        $selectEvents = $this->events($user, [
            'tab' => 'overview', 'from' => '', 'to' => '', 'event_id' => null,
        ])->orderByDesc('e.event_date')->limit(200)
            ->get(['e.event_id', 'e.event_name', 'e.event_date']);

        $scopeLabel = match ($this->role($user)) {
            'Administrator' => 'School-wide analytics',
            'Department Staff' => 'Your department only',
            'Attendance Personnel' => 'Assigned events only',
            default => 'Your participation only',
        };
        $audienceLabel = match ($this->role($user)) {
            'Administrator' => 'Administration',
            'Department Staff' => 'Department Staff',
            'Attendance Personnel' => 'Attendance Personnel',
            default => 'Student',
        };

        $reportView = match ($this->role($user)) {
            'Administrator' => 'analytics.admin',
            'Department Staff' => 'analytics.staff',
            'Attendance Personnel' => 'analytics.personnel',
            default => 'analytics.student',
        };

        return view($reportView, compact(
            'filters', 'scopeLabel', 'audienceLabel', 'eventCount', 'completedCount',
            'attendanceTotal', 'statusCounts', 'recordedAttendances', 'onTimeRate',
            'eligibleCount', 'qualifiedAttended', 'participationRate', 'featuredEvents',
            'eventRows', 'attendanceRows', 'months', 'maxTrend', 'linePoints', 'selectEvents'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $user = $this->authorizedUser($request);
        $filters = $this->filters($request, $user);
        $tab = $filters['tab'];
        if (in_array($this->role($user), ['Administrator', 'Department Staff', 'Attendance Personnel'], true)) {
            // Same flat, Excel-friendly CSV layout; queries remain scoped by role.
            return $this->exportAdministratorCsv($user, $filters);
        }
        if ($this->role($user) === 'Student Account') {
            return $this->exportStudentCsv($user, $filters);
        }
        $filename = 'DySign-Analytics-' . ucfirst($tab) . '-'
            . now('Asia/Manila')->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($user, $filters, $tab) {
            $out = fopen('php://output', 'w');
            if (!$out) return;
            fwrite($out, "\xEF\xBB\xBF");
            $row = function (array $cells) use ($out): void {
                fputcsv($out, array_map(fn ($v) => $this->csvSafe($v), $cells));
            };
            $row(['DYSIGN - ANALYTICS REPORT']);
            $row(['Scope', match ($this->role($user)) {
                'Administrator' => 'School-wide',
                'Department Staff' => 'Department ' . $user->department_id,
                'Attendance Personnel' => 'Assigned events',
                default => 'Own student attendance',
            }]);
            $row(['Report Category', ucfirst($tab)]);
            $row(['Generated (Asia/Manila)', now('Asia/Manila')->format('Y-m-d H:i:s')]);
            $row(['From', $filters['from'] ?: 'All dates']);
            $row(['To', $filters['to'] ?: 'All dates']);
            $row(['Event Filter', $filters['event_id'] ?: 'All in scope']);
            $row([]);
            if ($tab === 'attendance') {
                $row(['Student Number', 'Student', 'Event', 'Event Date', 'Time In', 'Time Out', 'Status']);
                $query = $this->attendance($user, $filters)
                    ->join('events as ev', 'ev.event_id', '=', 'ar.event_id')
                    ->join('students as s', 's.student_id', '=', 'ar.student_id')
                    ->select('s.student_number', 's.first_name', 's.last_name', 'ev.event_name',
                        'ev.event_date', 'ar.time_in', 'ar.time_out', 'ar.status')
                    ->orderBy('ev.event_date')->orderBy('ar.attendance_id');
                foreach ($query->cursor() as $r) {
                    $row([$r->student_number, trim($r->last_name . ', ' . $r->first_name),
                        $r->event_name, $r->event_date, $r->time_in ?: 'Not recorded',
                        $r->time_out ?: 'Not recorded', $r->status]);
                }
            } elseif ($tab === 'events' || $tab === 'participation') {
                $row(['Event', 'Event Date', 'Event Status', 'Eligible Students', 'Recorded Attendance', 'Present/Late']);
                $events = $this->events($user, $filters)->select(
                    'e.event_id', 'e.event_name', 'e.event_date', 'e.status'
                )->orderBy('e.event_date')->cursor();
                foreach ($events as $e) {
                    $att = $this->attendance($user, $filters)
                        ->where('ar.event_id', $e->event_id)->selectRaw('COUNT(*) AS total')
                        ->selectRaw("SUM(CASE WHEN ar.status IN ('Present','Late') THEN 1 ELSE 0 END) AS attended")
                        ->first();
                    $elig = DB::table('event_eligible_students')
                        ->where('event_id', $e->event_id);
                    if ($this->role($user) === 'Student Account') {
                        $elig->where('student_id', $user->student_id);
                    }
                    $row([$e->event_name, $e->event_date, $e->status,
                        $elig->count(), (int) $att->total, (int) $att->attended]);
                }
            } elseif ($tab === 'trends') {
                $row(['Month', 'Recorded Attendance', 'Present or Late']);
                $monthRows = $this->attendance($user, $filters)
                    ->join('events as ev', 'ev.event_id', '=', 'ar.event_id')
                    ->selectRaw('SUBSTRING(ev.event_date, 1, 7) AS period')
                    ->selectRaw('COUNT(*) AS records')
                    ->selectRaw("SUM(CASE WHEN ar.status IN ('Present','Late') THEN 1 ELSE 0 END) AS attended")
                    ->groupBy('period')->orderBy('period')->get();
                foreach ($monthRows as $m) $row([$m->period, $m->records, $m->attended]);
            } else {
                $total = $this->attendance($user, $filters)->count();
                $events = $this->events($user, $filters)->count();
                $row(['Events in scope', $events]);
                $row(['Recorded attendance', $total]);
                $row([]);
                $row(['Attendance Status', 'Count']);
                foreach (self::STATUSES as $status) {
                    $row([$status, $this->attendance($user, $filters)->where('ar.status', $status)->count()]);
                }
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Flat, rectangular CSV exports. No mixed metadata/header layouts.
     * CSV cannot store formatting, font styles or column widths.
     */
    private function exportAdministratorCsv(User $user, array $filters): StreamedResponse
    {
        $tab = $filters['tab'];
        $filename = 'DySign-' . match ($this->role($user)) {
            'Department Staff' => 'Department',
            'Attendance Personnel' => 'Personnel',
            default => 'Administrator',
        } . '-' . ucfirst($tab) . '-'
            . now('Asia/Manila')->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($user, $filters, $tab): void {
            $out = fopen('php://output', 'w');
            if (!$out) return;
            fwrite($out, "\xEF\xBB\xBF");
            $row = function (array $cells) use ($out): void {
                fputcsv($out, array_map(fn ($cell) => $this->csvSafe($cell), $cells));
            };

            $events = $this->events($user, $filters);
            $attendance = $this->attendance($user, $filters);

            if ($tab === 'overview') {
                $counts = (clone $attendance)->select('ar.status')
                    ->selectRaw('COUNT(*) AS total')->groupBy('ar.status')
                    ->pluck('total', 'ar.status');
                $attendanceCount = (clone $attendance)->count();
                $completedEvents = (clone $events)->where('e.status', 'Completed');
                $eligibleCount = DB::table('event_eligible_students as es')
                    ->whereIn('es.event_id', (clone $completedEvents)->select('e.event_id'))
                    ->count();
                $eligibleAttended = (clone $attendance)
                    ->whereIn('ar.event_id', (clone $completedEvents)->select('e.event_id'))
                    ->whereIn('ar.status', ['Present', 'Late'])
                    ->whereExists(function ($query) {
                        $query->selectRaw('1')->from('event_eligible_students as es')
                            ->whereColumn('es.event_id', 'ar.event_id')
                            ->whereColumn('es.student_id', 'ar.student_id');
                    })->count();
                $row(['Section', 'Indicator', 'Value', 'Unit']);
                $row(['Scope', 'Access', match ($this->role($user)) {
                    'Department Staff' => 'Department ' . $user->department_id,
                    'Attendance Personnel' => 'Assigned events',
                    default => 'School-wide',
                }, '']);
                $row(['Scope', 'Date from', $filters['from'] ?: 'All dates', '']);
                $row(['Scope', 'Date to', $filters['to'] ?: 'All dates', '']);
                $row(['Events', 'Events in scope', (clone $events)->count(), 'events']);
                $row(['Events', 'Completed events', (clone $completedEvents)->count(), 'events']);
                $row(['Attendance', 'Recorded attendance', $attendanceCount, 'records']);
                foreach (self::STATUSES as $status) {
                    $row(['Attendance', $status, (int) ($counts[$status] ?? 0), 'records']);
                }
                $row(['Participation', 'Eligible student-event entries', $eligibleCount, 'entries']);
                $row(['Participation', 'Attended eligible entries', $eligibleAttended, 'entries']);
                $row(['Participation', 'Participation rate', $eligibleCount ? round($eligibleAttended * 100 / $eligibleCount, 1) : 0, '%']);
            } elseif ($tab === 'attendance') {
                $row(['Student Number', 'Student Name', 'Event', 'Event Date', 'Time In', 'Time Out', 'Attendance Status']);
                $records = (clone $attendance)
                    ->join('events as ev', 'ev.event_id', '=', 'ar.event_id')
                    ->join('students as s', 's.student_id', '=', 'ar.student_id')
                    ->select('s.student_number', 's.first_name', 's.last_name', 'ev.event_name',
                        'ev.event_date', 'ar.time_in', 'ar.time_out', 'ar.status')
                    ->orderByDesc('ev.event_date')->orderBy('s.last_name');
                foreach ($records->cursor() as $record) {
                    $row([
                        $record->student_number,
                        trim($record->last_name . ', ' . $record->first_name),
                        $record->event_name,
                        $record->event_date,
                        $record->time_in ?: 'Not recorded',
                        $record->time_out ?: 'Not recorded',
                        $record->status,
                    ]);
                }
            } elseif (in_array($tab, ['events', 'participation'], true)) {
                $attPerEvent = (clone $attendance)->select('ar.event_id')
                    ->selectRaw('COUNT(*) AS records')
                    ->selectRaw("SUM(CASE WHEN ar.status IN ('Present','Late') THEN 1 ELSE 0 END) AS attended")
                    ->groupBy('ar.event_id');
                $eligPerEvent = DB::table('event_eligible_students as es')
                    ->whereIn('es.event_id', (clone $events)->select('e.event_id'))
                    ->select('es.event_id')->selectRaw('COUNT(*) AS eligible')->groupBy('es.event_id');
                $rows = (clone $events)
                    ->leftJoinSub($attPerEvent, 'rec', 'rec.event_id', '=', 'e.event_id')
                    ->leftJoinSub($eligPerEvent, 'elig', 'elig.event_id', '=', 'e.event_id')
                    ->select('e.event_name', 'e.event_date', 'e.status')
                    ->selectRaw('COALESCE(elig.eligible, 0) AS eligible_count')
                    ->selectRaw('COALESCE(rec.records, 0) AS recorded_count')
                    ->selectRaw('COALESCE(rec.attended, 0) AS attended_count')
                    ->orderByDesc('e.event_date');
                $row(['Event', 'Event Date', 'Event Status', 'Eligible Students',
                    'Recorded Attendance', 'Present/Late', 'Participation Rate (%)']);
                foreach ($rows->cursor() as $event) {
                    $row([$event->event_name, $event->event_date, $event->status,
                        $event->eligible_count, $event->recorded_count, $event->attended_count,
                        $event->eligible_count ? round(min(100, 100 * $event->attended_count / $event->eligible_count), 1) : 0]);
                }
            } else {
                $row(['Month', 'Recorded Attendance', 'Present / Late', 'Other Statuses']);
                $months = (clone $attendance)
                    ->join('events as ev', 'ev.event_id', '=', 'ar.event_id')
                    ->selectRaw('SUBSTRING(ev.event_date, 1, 7) AS period')
                    ->selectRaw('COUNT(*) AS records')
                    ->selectRaw("SUM(CASE WHEN ar.status IN ('Present','Late') THEN 1 ELSE 0 END) AS attended")
                    ->groupBy('period')->orderBy('period');
                foreach ($months->cursor() as $month) {
                    $row([$month->period, (int) $month->records,
                        (int) $month->attended, (int) $month->records - (int) $month->attended]);
                }
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Student exports: one rectangular table per category, only their own data.
     * The same scoped event/date validation is used for the page and export.
     */
    private function exportStudentCsv(User $user, array $filters): StreamedResponse
    {
        $tab = $filters['tab'];
        $file = 'DySign-Student-' . ucfirst($tab) . '-' . now('Asia/Manila')->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($user, $filters, $tab): void {
            $out = fopen('php://output', 'w');
            if (!$out) return;
            fwrite($out, "\xEF\xBB\xBF");
            $row = function (array $values) use ($out): void {
                fputcsv($out, array_map(fn ($value) => $this->csvSafe($value), $values));
            };
            $eventScope = $this->events($user, $filters);
            $records = $this->attendance($user, $filters);

            if ($tab === 'overview') {
                $completed = (clone $eventScope)->where('e.status', 'Completed');
                $eligible = DB::table('event_eligible_students as es')
                    ->where('es.student_id', (int) $user->student_id)
                    ->whereIn('es.event_id', (clone $completed)->select('e.event_id'));
                $eligibleCount = (clone $eligible)->count();
                $attended = (clone $records)
                    ->whereIn('ar.event_id', (clone $completed)->select('e.event_id'))
                    ->whereIn('ar.status', ['Present', 'Late'])
                    ->whereExists(function ($query) use ($user) {
                        $query->selectRaw('1')->from('event_eligible_students as es')
                            ->whereColumn('es.event_id', 'ar.event_id')
                            ->where('es.student_id', (int) $user->student_id);
                    })->count();
                $row(['Category', 'Metric', 'Value', 'Unit']);
                $row(['Events', 'Events in scope', (clone $eventScope)->count(), 'events']);
                $row(['Events', 'Completed events in scope', (clone $completed)->count(), 'events']);
                $row(['Participation', 'Eligible completed events', $eligibleCount, 'events']);
                $row(['Participation', 'Eligible completed events attended', $attended, 'events']);
                $row(['Participation', 'Participation rate', $eligibleCount ? round(100 * $attended / $eligibleCount, 1) : 0, '%']);
                $row(['Attendance', 'Recorded attendance', (clone $records)->count(), 'records']);
                foreach (self::STATUSES as $status) {
                    $row(['Attendance', $status, (clone $records)->where('ar.status', $status)->count(), 'records']);
                }
            } elseif ($tab === 'attendance') {
                $row(['Event', 'Event Date', 'Time In', 'Time Out', 'Attendance Status']);
                $data = (clone $records)
                    ->join('events as ev', 'ev.event_id', '=', 'ar.event_id')
                    ->select('ev.event_name', 'ev.event_date', 'ar.time_in', 'ar.time_out', 'ar.status')
                    ->orderByDesc('ev.event_date')->orderByDesc('ar.attendance_id');
                foreach ($data->cursor() as $record) {
                    $row([$record->event_name, $record->event_date,
                        $record->time_in ?: 'Not recorded',
                        $record->time_out ?: 'Not recorded', $record->status]);
                }
            } elseif ($tab === 'events' || $tab === 'participation') {
                if ($tab === 'events') {
                    $row(['Event', 'Event Date', 'Event Status', 'Eligible', 'Recorded Attendance Status', 'Time In', 'Time Out']);
                } else {
                    $row(['Event', 'Event Date', 'Event Completed', 'Eligible', 'Attendance Status', 'Counts Toward Participation']);
                }
                $events = (clone $eventScope)
                    ->select('e.event_id', 'e.event_name', 'e.event_date', 'e.status')
                    ->orderByDesc('e.event_date');
                foreach ($events->cursor() as $event) {
                    $eligible = DB::table('event_eligible_students')
                        ->where('event_id', $event->event_id)
                        ->where('student_id', (int) $user->student_id)->exists();
                    $record = (clone $records)->where('ar.event_id', $event->event_id)
                        ->first(['ar.status', 'ar.time_in', 'ar.time_out']);
                    $status = $record?->status ?: 'Not recorded';
                    if ($tab === 'events') {
                        $row([$event->event_name, $event->event_date, $event->status,
                            $eligible ? 'Yes' : 'No', $status,
                            $record?->time_in ?: 'Not recorded', $record?->time_out ?: 'Not recorded']);
                    } else {
                        $counted = $event->status === 'Completed' && $eligible
                            && in_array($status, ['Present', 'Late'], true);
                        $row([$event->event_name, $event->event_date,
                            $event->status === 'Completed' ? 'Yes' : 'No',
                            $eligible ? 'Yes' : 'No', $status,
                            $counted ? 'Yes' : 'No']);
                    }
                }
            } else {
                $row(['Month', 'Recorded Attendance', 'Present or Late', 'Other Statuses']);
                $monthly = (clone $records)
                    ->join('events as ev', 'ev.event_id', '=', 'ar.event_id')
                    ->selectRaw('SUBSTRING(ev.event_date, 1, 7) AS period')
                    ->selectRaw('COUNT(*) AS records')
                    ->selectRaw("SUM(CASE WHEN ar.status IN ('Present','Late') THEN 1 ELSE 0 END) AS attended")
                    ->groupBy('period')->get()->keyBy('period');
                foreach (range(5, 0) as $offset) {
                    $period = Carbon::now('Asia/Manila')->subMonths($offset)->format('Y-m');
                    $count = (int) ($monthly[$period]->records ?? 0);
                    $attended = (int) ($monthly[$period]->attended ?? 0);
                    $row([$period, $count, $attended, max(0, $count - $attended)]);
                }
            }
            fclose($out);
        }, $file, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function authorizedUser(Request $request): User
    {
        $user = $request->user();
        abort_unless($user && in_array($this->role($user), [
            'Administrator', 'Department Staff', 'Attendance Personnel', 'Student Account',
        ], true), 403);
        if ($this->role($user) === 'Department Staff') {
            abort_unless((int) $user->department_id > 0, 403, 'No department assigned.');
        }
        if ($this->role($user) === 'Attendance Personnel') {
            abort_unless($user->personnel, 403, 'No personnel profile linked.');
        }
        if ($this->role($user) === 'Student Account') {
            abort_unless((int) $user->student_id > 0, 403, 'No student profile linked.');
        }
        return $user;
    }

    private function filters(Request $request, User $user): array
    {
        $data = $request->validate([
            'tab' => ['nullable', Rule::in(self::TABS)],
            'event_id' => ['nullable', 'integer', 'min:1'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
        ]);
        $filters = [
            'tab' => $data['tab'] ?? 'overview',
            'event_id' => isset($data['event_id']) ? (int) $data['event_id'] : null,
            'from' => $data['from'] ?? '',
            'to' => $data['to'] ?? '',
        ];
        if ($filters['from'] && $filters['to'] && $filters['from'] > $filters['to']) {
            throw ValidationException::withMessages(['to' => 'End date must be on or after start date.']);
        }
        if ($filters['event_id'] && !$this->events($user, [
            'tab' => 'overview', 'event_id' => null, 'from' => '', 'to' => '',
        ])->where('e.event_id', $filters['event_id'])->exists()) {
            abort(404);
        }
        return $filters;
    }

    private function events(User $user, array $filters): Builder
    {
        $role = $this->role($user);
        $events = DB::table('events as e');
        if ($role === 'Department Staff') {
            $events->where('e.department_id', (int) $user->department_id);
        } elseif ($role === 'Attendance Personnel') {
            $personnelId = (int) $user->personnel->personnel_id;
            $events->whereExists(function ($q) use ($personnelId) {
                $q->selectRaw('1')->from('event_assignments as ea')
                    ->whereColumn('ea.event_id', 'e.event_id')
                    ->where('ea.personnel_id', $personnelId);
            });
        } elseif ($role === 'Student Account') {
            $studentId = (int) $user->student_id;
            $events->where(function ($q) use ($studentId) {
                $q->whereExists(function ($sub) use ($studentId) {
                    $sub->selectRaw('1')->from('event_eligible_students as es')
                        ->whereColumn('es.event_id', 'e.event_id')
                        ->where('es.student_id', $studentId);
                })->orWhereExists(function ($sub) use ($studentId) {
                    $sub->selectRaw('1')->from('attendance_records as sr')
                        ->whereColumn('sr.event_id', 'e.event_id')
                        ->where('sr.student_id', $studentId);
                });
            });
        }
        if ($filters['event_id'] ?? null) $events->where('e.event_id', $filters['event_id']);
        if ($filters['from'] ?? '') $events->whereDate('e.event_date', '>=', $filters['from']);
        if ($filters['to'] ?? '') $events->whereDate('e.event_date', '<=', $filters['to']);
        return $events;
    }

    private function attendance(User $user, array $filters): Builder
    {
        $query = DB::table('attendance_records as ar')
            ->whereIn('ar.event_id', $this->events($user, $filters)->select('e.event_id'));
        if ($this->role($user) === 'Student Account') {
            $query->where('ar.student_id', (int) $user->student_id);
        }
        return $query;
    }

    private function role(User $user): string
    {
        return (string) ($user->role?->role_name ?? '');
    }

    private function csvSafe(mixed $value): string
    {
        $s = (string) ($value ?? '');
        if (preg_match('/^[\s]*[=+\-@\t\r]/u', $s)) return "'" . $s;
        return $s;
    }
}
