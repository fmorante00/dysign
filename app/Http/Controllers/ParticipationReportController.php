<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ParticipationReportController extends Controller
{
    /** Participation classifications follow the existing DySign evaluation thresholds. */
    private const CLASSIFICATIONS = [
        'Highly Participative', 'Participative', 'Moderately Participative',
        'Low Participation', 'No Data',
    ];

    /** Explicit allow-list: never use user-submitted column names as query fields. */
    private const CSV_COLUMNS = [
        'student_number' => 'Student Number',
        'student_name' => 'Student Name',
        'college' => 'College',
        'program_code' => 'Program Code',
        'program_name' => 'Program Name',
        'year_level' => 'Year Level',
        'events_attended' => 'Events Attended',
        'total_applicable_events' => 'Recorded Events in Scope',
        'participation_rate' => 'Participation Rate (%)',
        'classification' => 'Classification',
        'last_participation' => 'Last Attendance',
    ];

    private const DEFAULT_COLUMNS = [
        'student_number', 'student_name', 'college', 'program_code',
        'year_level', 'events_attended', 'total_applicable_events',
        'participation_rate', 'classification',
    ];

    public function index(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $report = $this->buildReport($filters);
        $matching = $report['results'];
        $perPage = (int) $filters['per_page'];
        $page = max(1, LengthAwarePaginator::resolveCurrentPage());

        // Only the table is paginated; CSV exports the entire filtered collection.
        $results = new LengthAwarePaginator(
            $matching->forPage($page, $perPage)->values(),
            $matching->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $events = DB::table('events')
            ->orderByDesc('event_date')->orderByDesc('start_time')
            ->get(['event_id', 'event_name', 'event_date', 'status']);

        // Filter controls always represent the full active-student registry.
        $colleges = DB::table('students')->where('status', 'Active')
            ->whereNotNull('college')->where('college', '<>', '')
            ->distinct()->orderBy('college')->pluck('college');
        $programs = DB::table('students')->where('status', 'Active')
            ->whereNotNull('program_code')->where('program_code', '<>', '')
            ->distinct()->orderBy('program_code')
            ->get(['program_code', 'program_name']);
        $years = DB::table('students')->where('status', 'Active')
            ->whereNotNull('year_level')->distinct()->orderBy('year_level')
            ->pluck('year_level');

        return view('reports.participation', [
            'filters' => $filters,
            'results' => $results,
            'summary' => $report['summary'],
            'totalRecordedEvents' => $report['totalRecordedEvents'],
            'events' => $events,
            'colleges' => $colleges,
            'programs' => $programs,
            'years' => $years,
            'classifications' => self::CLASSIFICATIONS,
            'csvColumns' => self::CSV_COLUMNS,
            'selectedColumns' => $this->selectedColumns($request),
        ]);
    }

    /** CSV contains ALL records matching the same filters as the on-screen report. */
    public function export(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $columns = $this->selectedColumns($request);
        $report = $this->buildReport($filters);
        $rows = $report['results'];
        $filename = 'dysign-participation-' . now('Asia/Manila')->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows, $columns) {
            $output = fopen('php://output', 'wb');
            // Excel recognizes UTF-8 names with this BOM.
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, array_map(fn ($field) => self::CSV_COLUMNS[$field], $columns), ',', '"', '');

            foreach ($rows as $student) {
                $values = $this->csvValues($student);
                $cells = array_map(function ($field) use ($values) {
                    $text = (string) ($values[$field] ?? '');
                    // Prevent spreadsheet formula injection in names and other user-supplied fields.
                    if (preg_match('/^[\s\x00-\x1f]*[=+@-]/u', $text)) {
                        $text = "'" . $text;
                    }
                    return $text;
                }, $columns);
                fputcsv($output, $cells, ',', '"', '');
            }
            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function validatedFilters(Request $request): array
    {
        $data = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'event_id' => ['nullable', 'integer', 'min:1'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date_format:Y-m-d'],
            'college' => ['nullable', 'string', 'max:150'],
            'program' => ['nullable', 'string', 'max:100'],
            'year_level' => ['nullable', 'string', 'max:20'],
            'classification' => ['nullable', Rule::in(self::CLASSIFICATIONS)],
            'sort' => ['nullable', Rule::in(['name', 'highest_rate', 'lowest_rate', 'most_events'])],
            'per_page' => ['nullable', Rule::in(['10', '25', '50'])],
            'columns' => ['sometimes', 'array', 'max:11'],
            'columns.*' => ['string', Rule::in(array_keys(self::CSV_COLUMNS))],
        ]);

        if (!empty($data['date_from']) && !empty($data['date_to'])
            && $data['date_to'] < $data['date_from']) {
            throw ValidationException::withMessages([
                'date_to' => 'End date must be on or after the start date.',
            ]);
        }

        return [
            'search' => trim($data['search'] ?? ''),
            'event_id' => $data['event_id'] ?? null,
            'date_from' => $data['date_from'] ?? '',
            'date_to' => $data['date_to'] ?? '',
            'college' => $data['college'] ?? '',
            'program' => $data['program'] ?? '',
            'year_level' => $data['year_level'] ?? '',
            'classification' => $data['classification'] ?? '',
            'sort' => $data['sort'] ?? 'name',
            'per_page' => $data['per_page'] ?? '10',
        ];
    }

    private function selectedColumns(Request $request): array
    {
        $requested = $request->input('columns', []);
        if (!is_array($requested)) {
            return self::DEFAULT_COLUMNS;
        }
        // Preserve the form's published ordering and exclude duplicates.
        $selected = array_values(array_unique(array_filter($requested,
            fn ($field) => is_string($field) && array_key_exists($field, self::CSV_COLUMNS)
        )));
        return $selected !== [] ? $selected : self::DEFAULT_COLUMNS;
    }

    /** One scope query is reused for the denominator and each student's numerator. */
    private function scopedAttendance(array $filters)
    {
        return DB::table('attendance_records as ar')
            ->join('events as e', 'e.event_id', '=', 'ar.event_id')
            ->when($filters['event_id'] !== null, fn ($q) =>
                $q->where('ar.event_id', $filters['event_id']))
            ->when($filters['date_from'] !== '', fn ($q) =>
                $q->whereDate('e.event_date', '>=', $filters['date_from']))
            ->when($filters['date_to'] !== '', fn ($q) =>
                $q->whereDate('e.event_date', '<=', $filters['date_to']));
    }

    private function buildReport(array $filters): array
    {
        // Keep DySign's existing formula: distinct events with attendance records.
        // Do not treat 'Required' eligibility or scheduled events as attended events.
        $totalRecordedEvents = (clone $this->scopedAttendance($filters))
            ->distinct()->count('ar.event_id');

        $scopedRows = $this->scopedAttendance($filters)
            ->select('ar.student_id', 'ar.event_id', 'ar.time_in');

        $students = DB::table('students as s')
            ->leftJoinSub($scopedRows, 'scans', 'scans.student_id', '=', 's.student_id')
            ->where('s.status', 'Active')
            ->select([
                's.student_id', 's.student_number', 's.first_name',
                's.middle_name', 's.last_name', 's.college',
                's.program_code', 's.program_name', 's.year_level', 's.photo_path',
                DB::raw('COUNT(DISTINCT scans.event_id) as events_attended'),
                DB::raw('MAX(scans.time_in) as last_participation'),
            ])
            ->groupBy([
                's.student_id', 's.student_number', 's.first_name',
                's.middle_name', 's.last_name', 's.college',
                's.program_code', 's.program_name', 's.year_level', 's.photo_path',
            ])
            ->orderBy('s.last_name')->orderBy('s.first_name')
            ->get();

        $evaluations = $students->map(function ($student) use ($totalRecordedEvents) {
            $student->student_name = trim(implode(' ', array_filter([
                $student->first_name, $student->middle_name, $student->last_name,
            ], fn ($part) => $part !== null && trim($part) !== '')));
            $student->events_attended = (int) $student->events_attended;
            $student->total_applicable_events = (int) $totalRecordedEvents;
            $student->participation_rate = $totalRecordedEvents > 0
                ? round(($student->events_attended / $totalRecordedEvents) * 100, 2)
                : 0;
            $student->classification = match (true) {
                $totalRecordedEvents === 0 => 'No Data',
                $student->participation_rate >= 90 => 'Highly Participative',
                $student->participation_rate >= 75 => 'Participative',
                $student->participation_rate >= 60 => 'Moderately Participative',
                default => 'Low Participation',
            };
            return $student;
        });

        $matches = $evaluations->filter(function ($student) use ($filters) {
            if ($filters['college'] !== '' && $filters['college'] !== $student->college) return false;
            if ($filters['program'] !== '' && $filters['program'] !== $student->program_code) return false;
            if ($filters['year_level'] !== '' && (string) $filters['year_level'] !== (string) $student->year_level) return false;
            if ($filters['classification'] !== '' && $filters['classification'] !== $student->classification) return false;
            if ($filters['search'] !== '') {
                $haystack = implode(' ', [
                    $student->student_number, $student->student_name,
                    $student->college, $student->program_code, $student->program_name,
                ]);
                if (stripos($haystack, $filters['search']) === false) return false;
            }
            return true;
        });

        $matches = match ($filters['sort']) {
            'highest_rate' => $matches->sortByDesc('participation_rate'),
            'lowest_rate' => $matches->sortBy('participation_rate'),
            'most_events' => $matches->sortByDesc('events_attended'),
            default => $matches,
        };
        $matches = $matches->values();

        $summary = [
            'total' => $matches->count(),
            'highly' => $matches->where('classification', 'Highly Participative')->count(),
            'participative' => $matches->where('classification', 'Participative')->count(),
            'moderately' => $matches->where('classification', 'Moderately Participative')->count(),
            'low' => $matches->where('classification', 'Low Participation')->count(),
            'no_data' => $matches->where('classification', 'No Data')->count(),
        ];

        return ['results' => $matches, 'summary' => $summary, 'totalRecordedEvents' => $totalRecordedEvents];
    }

    private function csvValues(object $student): array
    {
        return [
            'student_number' => $student->student_number,
            'student_name' => $student->student_name,
            'college' => $student->college,
            'program_code' => $student->program_code,
            'program_name' => $student->program_name,
            'year_level' => $student->year_level,
            'events_attended' => $student->events_attended,
            'total_applicable_events' => $student->total_applicable_events,
            'participation_rate' => number_format($student->participation_rate, 2, '.', ''),
            'classification' => $student->classification,
            'last_participation' => $student->last_participation,
        ];
    }
}
