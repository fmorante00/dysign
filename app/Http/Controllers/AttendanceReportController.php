<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AttendanceReportController extends Controller
{
    /** Attendance lifecycle values from the DySign attendance_records schema. */
    private const STATUSES = [
        'Checked In', 'Present', 'Late', 'Incomplete', 'Absent', 'Excused',
    ];

    /** Only these columns may be requested by the CSV export form. */
    private const CSV_COLUMNS = [
        'student_number' => 'Student Number',
        'student_name'   => 'Student Name',
        'college'        => 'College',
        'program'        => 'Program',
        'year_level'     => 'Year Level',
        'event_name'     => 'Event',
        'event_date'     => 'Event Date',
        'event_status'   => 'Event Status',
        'status'         => 'Attendance Status',
        'time_in'        => 'Time In',
        'time_out'       => 'Time Out',
        'location'       => 'Venue',
        'rfid_identifier'=> 'RFID',
        'scanned_by'     => 'Recorded By',
    ];

    private const DEFAULT_COLUMNS = [
        'student_number', 'student_name', 'college', 'program', 'year_level',
        'event_name', 'event_date', 'status', 'time_in', 'time_out',
    ];

    public function index(Request $request)
    {
        $filters = $this->filters($request);
        $query = $this->attendanceQuery($filters);

        // Summary counts are calculated over ALL matching rows, not just the current page.
        $totals = (clone $query)->selectRaw("COUNT(*) AS total")
            ->selectRaw('COUNT(DISTINCT ar.event_id) AS events')
            ->selectRaw("SUM(CASE WHEN ar.status = 'Present' THEN 1 ELSE 0 END) AS present_count")
            ->selectRaw("SUM(CASE WHEN ar.status = 'Late' THEN 1 ELSE 0 END) AS late_count")
            ->selectRaw("SUM(CASE WHEN ar.status = 'Checked In' THEN 1 ELSE 0 END) AS checked_in_count")
            ->selectRaw("SUM(CASE WHEN ar.status = 'Incomplete' THEN 1 ELSE 0 END) AS incomplete_count")
            ->selectRaw("SUM(CASE WHEN ar.status = 'Absent' THEN 1 ELSE 0 END) AS absent_count")
            ->selectRaw("SUM(CASE WHEN ar.status = 'Excused' THEN 1 ELSE 0 END) AS excused_count")
            ->selectRaw('SUM(CASE WHEN ar.time_out IS NOT NULL THEN 1 ELSE 0 END) AS checked_out_count')
            ->first();

        $summary = [
            'total' => (int) ($totals->total ?? 0),
            'events' => (int) ($totals->events ?? 0),
            'present' => (int) ($totals->present_count ?? 0),
            'late' => (int) ($totals->late_count ?? 0),
            'checked_in' => (int) ($totals->checked_in_count ?? 0),
            'checked_out' => (int) ($totals->checked_out_count ?? 0),
            'incomplete' => (int) ($totals->incomplete_count ?? 0),
            'absent' => (int) ($totals->absent_count ?? 0),
            'excused' => (int) ($totals->excused_count ?? 0),
        ];
        $finalized = $summary['present'] + $summary['late'];
        $summary['on_time_rate'] = $finalized > 0
            ? round(100 * $summary['present'] / $finalized, 1)
            : null;

        $records = $this->selectColumns($query)
            ->orderByDesc('e.event_date')
            ->orderByDesc('ar.time_in')
            ->orderByDesc('ar.attendance_id')
            ->paginate((int) $filters['per_page'])
            ->withQueryString();

        $events = Event::query()
            ->orderByDesc('event_date')->orderByDesc('start_time')
            ->get(['event_id', 'event_name', 'event_date', 'status']);

        // Choices must not depend on the current result page or filter selection.
        $colleges = DB::table('students')->whereNotNull('college')
            ->where('college', '<>', '')->distinct()->orderBy('college')->pluck('college');
        $programs = DB::table('students')->whereNotNull('program_code')
            ->where('program_code', '<>', '')->distinct()
            ->orderBy('program_code')->get(['program_code', 'program_name']);
        $years = DB::table('students')->whereNotNull('year_level')
            ->distinct()->orderBy('year_level')->pluck('year_level');

        return view('reports.attendance', [
            'filters' => $filters,
            'records' => $records,
            'summary' => $summary,
            'events' => $events,
            'statuses' => self::STATUSES,
            'colleges' => $colleges,
            'programs' => $programs,
            'years' => $years,
            'csvColumns' => self::CSV_COLUMNS,
            'selectedColumns' => $this->selectedColumns($request),
        ]);
    }

    /** Export all matching rows, independent of the on-screen pagination. */
    public function export(Request $request)
    {
        $filters = $this->filters($request);
        $columns = $this->selectedColumns($request);
        $query = $this->selectColumns($this->attendanceQuery($filters))
            ->orderByDesc('e.event_date')
            ->orderByDesc('ar.time_in')
            ->orderByDesc('ar.attendance_id');

        $filename = 'dysign-attendance-' . now('Asia/Manila')->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query, $columns) {
            $out = fopen('php://output', 'wb');
            // UTF-8 BOM makes Philippine student names display correctly in Excel.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, array_map(fn ($field) => self::CSV_COLUMNS[$field], $columns), ',', '"', '');

            foreach ($query->cursor() as $record) {
                $values = $this->recordValues($record);
                fputcsv($out, array_map(function ($field) use ($values) {
                    $value = (string) ($values[$field] ?? '');
                    // Prevent CSV / spreadsheet formula execution in user-controlled text.
                    if (preg_match('/^[\s\x00-\x1f]*[=+@-]/u', $value)) {
                        $value = "'" . $value;
                    }
                    return $value;
                }, $columns), ',', '"', '');
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, private',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function filters(Request $request): array
    {
        $data = $request->validate([
            'event_id' => ['nullable', 'regex:/^(all|[1-9][0-9]*)$/'],
            'status' => ['nullable', Rule::in(array_merge(['all'], self::STATUSES))],
            'college' => ['nullable', 'string', 'max:150'],
            'program' => ['nullable', 'string', 'max:50'],
            'year_level' => ['nullable', 'string', 'max:20'],
            'search' => ['nullable', 'string', 'max:120'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
            'per_page' => ['nullable', Rule::in(['10', '25', '50'])],
        ]);

        return [
            'event_id' => $data['event_id'] ?? 'all',
            'status' => $data['status'] ?? 'all',
            'college' => trim($data['college'] ?? ''),
            'program' => trim($data['program'] ?? ''),
            'year_level' => $data['year_level'] ?? '',
            'search' => trim($data['search'] ?? ''),
            'date_from' => $data['date_from'] ?? '',
            'date_to' => $data['date_to'] ?? '',
            'per_page' => (string) ($data['per_page'] ?? '25'),
        ];
    }

    private function attendanceQuery(array $filters): Builder
    {
        $query = DB::table('attendance_records as ar')
            ->join('students as s', 's.student_id', '=', 'ar.student_id')
            ->join('events as e', 'e.event_id', '=', 'ar.event_id')
            ->leftJoin('users as u', 'u.user_id', '=', 'ar.scanned_by');

        if ($filters['event_id'] !== 'all') {
            $query->where('ar.event_id', (int) $filters['event_id']);
        }
        if ($filters['status'] !== 'all') {
            $query->where('ar.status', $filters['status']);
        }
        if ($filters['college'] !== '') {
            $query->where('s.college', $filters['college']);
        }
        if ($filters['program'] !== '') {
            $query->where('s.program_code', $filters['program']);
        }
        if ($filters['year_level'] !== '') {
            $query->where('s.year_level', $filters['year_level']);
        }
        if ($filters['date_from'] !== '') {
            $query->whereDate('e.event_date', '>=', $filters['date_from']);
        }
        if ($filters['date_to'] !== '') {
            $query->whereDate('e.event_date', '<=', $filters['date_to']);
        }
        if ($filters['search'] !== '') {
            $like = '%' . addcslashes($filters['search'], '%_\\') . '%';
            $query->where(function (Builder $q) use ($like) {
                $q->where('s.student_number', 'like', $like)
                    ->orWhere('s.first_name', 'like', $like)
                    ->orWhere('s.middle_name', 'like', $like)
                    ->orWhere('s.last_name', 'like', $like)
                    ->orWhere('e.event_name', 'like', $like)
                    ->orWhereRaw("CONCAT_WS(' ', s.first_name, NULLIF(s.middle_name, ''), s.last_name) LIKE ?", [$like]);
            });
        }

        return $query;
    }

    private function selectColumns(Builder $query): Builder
    {
        return $query->select([
            'ar.attendance_id', 'ar.event_id', 'ar.student_id', 'ar.rfid_identifier',
            'ar.time_in', 'ar.time_out', 'ar.status',
            's.student_number', 's.first_name', 's.middle_name', 's.last_name',
            's.college', 's.program_code', 's.program_name', 's.year_level',
            'e.event_name', 'e.event_date', 'e.status as event_status', 'e.location',
            'u.name as scanned_by_name',
        ]);
    }

    private function selectedColumns(Request $request): array
    {
        $data = $request->validate([
            'columns' => ['sometimes', 'array', 'min:1', 'max:14'],
            'columns.*' => ['string', Rule::in(array_keys(self::CSV_COLUMNS))],
        ]);

        $columns = $data['columns'] ?? self::DEFAULT_COLUMNS;
        return array_values(array_unique($columns));
    }

    private function recordValues(object $r): array
    {
        return [
            'student_number' => $r->student_number,
            'student_name' => trim(implode(' ', array_filter([
                $r->first_name, $r->middle_name, $r->last_name,
            ], fn ($part) => $part !== null && trim((string) $part) !== ''))),
            'college' => $r->college,
            'program' => trim(($r->program_code ?? '') . ' ' . ($r->program_name ?? '')),
            'year_level' => $r->year_level,
            'event_name' => $r->event_name,
            'event_date' => $r->event_date,
            'event_status' => $r->event_status,
            'status' => $r->status,
            'time_in' => $r->time_in,
            'time_out' => $r->time_out,
            'location' => $r->location,
            'rfid_identifier' => $r->rfid_identifier,
            'scanned_by' => $r->scanned_by_name,
        ];
    }
}
