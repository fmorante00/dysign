<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Support\StaffEventAttendancePdf;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Read-only event-based attendance viewer for Department Staff.
 * The selected event and all exports are scoped to the authenticated department.
 * Recorded attendance is never generated or changed by this controller.
 */
class StudentParticipationClassificationController extends Controller
{
    public function index(Request $request): View
    {
        $departmentId = $this->departmentId($request);
        $filters = $this->filters($request);

        $events = Event::query()
            ->where('department_id', $departmentId)
            ->when($filters['event_search'] !== '', function ($query) use ($filters) {
                $term = $filters['event_search'];
                $query->where(function ($q) use ($term) {
                    $q->where('event_name', 'like', "%{$term}%")
                        ->orWhere('location', 'like', "%{$term}%");
                });
            })
            ->when($filters['event_status'] !== '', fn ($query) => $query->where('status', $filters['event_status']))
            ->withCount([
                'attendanceRecords as recorded_count',
                'attendanceRecords as attended_count' => fn ($query) => $query->whereIn('status', ['Present', 'Late']),
            ])
            ->orderByDesc('event_date')
            ->orderByDesc('event_id')
            ->paginate(8, ['*'], 'event_page')
            ->withQueryString();

        $selectedEvent = $filters['event_id']
            ? $this->eventInDepartment($departmentId, $filters['event_id'])
            : null;
        $records = null;
        $statistics = null;
        $programs = collect();

        if ($selectedEvent) {
            $statistics = $this->eventStatistics((int) $selectedEvent->event_id);
            $records = $this->sortedAttendanceQuery(
                $this->attendanceQuery((int) $selectedEvent->event_id, $filters),
                $filters['sort']
            )->paginate($filters['per_page'], ['*'], 'page')->withQueryString();

            $programs = DB::table('attendance_records as ar')
                ->join('students as s', 's.student_id', '=', 'ar.student_id')
                ->where('ar.event_id', $selectedEvent->event_id)
                ->whereNotNull('s.program_code')
                ->where('s.program_code', '<>', '')
                ->distinct()
                ->orderBy('s.program_code')
                ->pluck('s.program_code');
        }

        return view('staff.participation-classifications.index', compact(
            'events', 'selectedEvent', 'filters', 'records', 'statistics', 'programs'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        [$event, $filters, $departmentId] = $this->exportContext($request);
        $filename = 'DySign-Event-Attendance-' . $event->event_id . '-' . now('Asia/Manila')->format('Ymd-His') . '.csv';
        $department = $this->departmentName($departmentId);

        return response()->streamDownload(function () use ($event, $filters, $department) {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['DYSIGN - EVENT ATTENDANCE REPORT']);
            fputcsv($out, ['Department', $this->safeCsv($department)]);
            fputcsv($out, ['Event', $this->safeCsv($event->event_name)]);
            fputcsv($out, ['Event Date', $event->event_date?->format('Y-m-d')]);
            fputcsv($out, ['Location', $this->safeCsv($event->location)]);
            fputcsv($out, ['Generated (PH)', now('Asia/Manila')->format('Y-m-d H:i:s')]);
            fputcsv($out, ['Status Filter', $this->safeCsv($filters['status'])]);
            fputcsv($out, ['Student Search', $this->safeCsv($filters['search'])]);
            fputcsv($out, ['Program Filter', $this->safeCsv($filters['program'])]);
            fputcsv($out, ['Scope', 'Recorded attendance only; Present/Late treated as attended']);
            fputcsv($out, []);
            fputcsv($out, ['Student Number', 'Student Name', 'Program', 'Year Level', 'Time In (PH)', 'Time Out (PH)', 'Attendance Status']);

            foreach ($this->sortedAttendanceQuery(
                $this->attendanceQuery((int) $event->event_id, $filters), $filters['sort']
            )->cursor() as $row) {
                fputcsv($out, [
                    $this->safeCsv($row->student_number),
                    $this->safeCsv(trim($row->last_name . ', ' . $row->first_name)),
                    $this->safeCsv($row->program_code ?? ''),
                    $this->safeCsv($row->year_level ?? ''),
                    $row->time_in ? \Carbon\Carbon::parse($row->time_in)->format('Y-m-d H:i:s') : 'Not recorded',
                    $row->time_out ? \Carbon\Carbon::parse($row->time_out)->format('Y-m-d H:i:s') : 'Not recorded',
                    $this->safeCsv($row->status),
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function exportPdf(Request $request): Response
    {
        [$event, $filters, $departmentId] = $this->exportContext($request);
        $records = $this->sortedAttendanceQuery(
            $this->attendanceQuery((int) $event->event_id, $filters), $filters['sort']
        )->get();
        $statistics = $this->eventStatistics((int) $event->event_id);
        $pdf = StaffEventAttendancePdf::render(
            $this->departmentName($departmentId), $event, $records->all(), $statistics, $filters
        );
        $filename = 'DySign-Event-Attendance-' . $event->event_id . '-' . now('Asia/Manila')->format('Ymd-His') . '.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($pdf),
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function exportContext(Request $request): array
    {
        $departmentId = $this->departmentId($request);
        $filters = $this->filters($request);
        abort_unless($filters['event_id'], 422, 'Select an event before downloading.');
        $event = $this->eventInDepartment($departmentId, $filters['event_id']);
        return [$event, $filters, $departmentId];
    }

    private function departmentId(Request $request): int
    {
        abort_unless($request->user()?->role?->role_name === 'Department Staff', 403);
        $departmentId = (int) ($request->user()->department_id ?? 0);
        abort_unless($departmentId > 0, 403, 'No department is linked to this account.');
        return $departmentId;
    }

    private function eventInDepartment(int $departmentId, int $eventId): Event
    {
        return Event::query()->where('department_id', $departmentId)->findOrFail($eventId);
    }

    private function departmentName(int $departmentId): string
    {
        return (string) (DB::table('departments')->where('department_id', $departmentId)->value('department_name') ?? 'Department');
    }

    private function filters(Request $request): array
    {
        $data = $request->validate([
            'event_id' => ['nullable', 'integer', 'min:1'],
            'event_search' => ['nullable', 'string', 'max:100'],
            'event_status' => ['nullable', 'in:Upcoming,Ongoing,Completed,Cancelled'],
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:all,attended,Present,Late,Checked In,Incomplete,Absent,Excused'],
            'program' => ['nullable', 'string', 'max:50'],
            'sort' => ['nullable', 'in:name_asc,name_desc,time_in_asc,time_in_desc,status'],
            'per_page' => ['nullable', 'integer', 'in:5,10,20'],
        ]);

        return [
            'event_id' => (int) ($data['event_id'] ?? 0),
            'event_search' => trim((string) ($data['event_search'] ?? '')),
            'event_status' => (string) ($data['event_status'] ?? ''),
            'search' => trim((string) ($data['search'] ?? '')),
            'status' => (string) ($data['status'] ?? 'attended'),
            'program' => trim((string) ($data['program'] ?? '')),
            'sort' => (string) ($data['sort'] ?? 'name_asc'),
            'per_page' => (int) ($data['per_page'] ?? 5),
        ];
    }

    private function attendanceQuery(int $eventId, array $filters): Builder
    {
        return DB::table('attendance_records as ar')
            ->join('students as s', 's.student_id', '=', 'ar.student_id')
            ->where('ar.event_id', $eventId)
            ->when($filters['search'] !== '', function (Builder $query) use ($filters) {
                $term = $filters['search'];
                $query->where(function (Builder $q) use ($term) {
                    $q->where('s.student_number', 'like', "%{$term}%")
                        ->orWhere('s.first_name', 'like', "%{$term}%")
                        ->orWhere('s.last_name', 'like', "%{$term}%")
                        ->orWhere('s.program_code', 'like', "%{$term}%");
                });
            })
            ->when($filters['program'] !== '', fn (Builder $query) => $query->where('s.program_code', $filters['program']))
            ->when($filters['status'] === 'attended', fn (Builder $query) => $query->whereIn('ar.status', ['Present', 'Late']))
            ->when(!in_array($filters['status'], ['attended', 'all'], true),
                fn (Builder $query) => $query->where('ar.status', $filters['status']))
            ->select(
                'ar.attendance_id', 'ar.time_in', 'ar.time_out', 'ar.status',
                's.student_number', 's.first_name', 's.last_name', 's.program_code', 's.year_level'
            );
    }

    private function sortedAttendanceQuery(Builder $query, string $sort): Builder
    {
        return match ($sort) {
            'name_desc' => $query->orderByDesc('s.last_name')->orderByDesc('s.first_name')->orderBy('ar.attendance_id'),
            'time_in_asc' => $query->orderBy('ar.time_in')->orderBy('s.last_name')->orderBy('ar.attendance_id'),
            'time_in_desc' => $query->orderByDesc('ar.time_in')->orderBy('s.last_name')->orderBy('ar.attendance_id'),
            'status' => $query->orderBy('ar.status')->orderBy('s.last_name')->orderBy('ar.attendance_id'),
            default => $query->orderBy('s.last_name')->orderBy('s.first_name')->orderBy('ar.attendance_id'),
        };
    }

    private function eventStatistics(int $eventId): object
    {
        return DB::table('attendance_records')
            ->where('event_id', $eventId)
            ->selectRaw('COUNT(*) as recorded')
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END), 0) as present")
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END), 0) as late")
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'Checked In' THEN 1 ELSE 0 END), 0) as checked_in")
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'Incomplete' THEN 1 ELSE 0 END), 0) as incomplete")
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'Excused' THEN 1 ELSE 0 END), 0) as excused")
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END), 0) as absent")
            ->first();
    }

    private function safeCsv($value): string
    {
        $text = (string) ($value ?? '');
        $text = preg_replace('/^[\x00-\x20]+/u', '', $text) ?? $text;
        return preg_match('/^[=+@\-\t\r]/u', $text) ? "'" . $text : $text;
    }
}
