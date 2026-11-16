<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Support\StaffParticipationPdf;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Department-scoped participation monitoring and read-only exports.
 * Only completed eligible events with Present/Late attendance count as attended.
 * Activity points are deliberately excluded from this module.
 */
class StaffParticipationProgressController extends Controller
{
    public function index(Request $request): View
    {
        $departmentId = $this->departmentId($request);
        $filters = $this->filters($request);
        $filtered = $this->filteredQuery($departmentId, $filters);
        $totalStudents = (clone $filtered)->count();
        $averageProgress = $totalStudents
            ? round((float) (clone $filtered)->avg('p.progress_percentage'), 1)
            : 0.0;

        $students = $this->sortedQuery($filtered, $filters['sort'])
            ->paginate($filters['per_page'])
            ->withQueryString();

        $totalCompletedEvents = Event::query()
            ->where('department_id', $departmentId)
            ->where('status', 'Completed')
            ->count();

        $programs = DB::table('students as s')
            ->join('event_eligible_students as es', 'es.student_id', '=', 's.student_id')
            ->join('events as e', function ($join) use ($departmentId) {
                $join->on('e.event_id', '=', 'es.event_id')
                    ->where('e.department_id', '=', $departmentId)
                    ->where('e.status', '=', 'Completed');
            })
            ->where('s.status', 'Active')
            ->whereNotNull('s.program_code')
            ->where('s.program_code', '<>', '')
            ->distinct()
            ->orderBy('s.program_code')
            ->pluck('s.program_code');

        return view('staff.participation-progress.index', compact(
            'students', 'filters', 'totalStudents', 'totalCompletedEvents',
            'averageProgress', 'programs'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $departmentId = $this->departmentId($request);
        $filters = $this->filters($request);
        $departmentName = $this->departmentName($departmentId);
        $count = $this->filteredQuery($departmentId, $filters)->count();
        $filename = 'DySign-Department-Participation-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($departmentId, $filters, $departmentName, $count) {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            // Excel-friendly UTF-8 BOM, safe cells against spreadsheet formula injection.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['DYSIGN - DEPARTMENT PARTICIPATION PROGRESS REPORT']);
            fputcsv($out, ['Department', $this->safeCsv($departmentName)]);
            fputcsv($out, ['Generated (PH)', now('Asia/Manila')->format('Y-m-d H:i:s')]);
            fputcsv($out, ['Matching Students', $count]);
            fputcsv($out, ['Scope', 'Completed department events and eligible active students']);
            fputcsv($out, ['Attendance Rule', 'Present or Late in completed eligible events']);
            fputcsv($out, []);
            fputcsv($out, ['Student Number', 'Student Name', 'Program', 'Year Level', 'Eligible Events', 'Attended Events', 'Participation Rate (%)', 'Progress Level']);

            foreach ($this->sortedQuery($this->filteredQuery($departmentId, $filters), $filters['sort'])->cursor() as $student) {
                fputcsv($out, [
                    $this->safeCsv($student->student_number),
                    $this->safeCsv(trim($student->last_name . ', ' . $student->first_name)),
                    $this->safeCsv($student->program_code ?? ''),
                    $this->safeCsv($student->year_level ?? ''),
                    (int) $student->eligible_count,
                    (int) $student->attended_count,
                    number_format((float) $student->progress_percentage, 1, '.', ''),
                    $this->progressLevel((float) $student->progress_percentage),
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
        $departmentId = $this->departmentId($request);
        $filters = $this->filters($request);
        $records = $this->sortedQuery($this->filteredQuery($departmentId, $filters), $filters['sort'])->get();
        $departmentName = $this->departmentName($departmentId);
        $totalCompletedEvents = Event::query()
            ->where('department_id', $departmentId)
            ->where('status', 'Completed')
            ->count();

        $pdf = StaffParticipationPdf::render($departmentName, $records, $totalCompletedEvents, $filters);
        $filename = 'DySign-Department-Participation-' . now()->format('Ymd-His') . '.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($pdf),
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function departmentId(Request $request): int
    {
        abort_unless($request->user()?->role?->role_name === 'Department Staff', 403);
        $departmentId = (int) ($request->user()->department_id ?? 0);
        abort_unless($departmentId > 0, 403, 'No department is linked to this account.');
        return $departmentId;
    }

    private function departmentName(int $departmentId): string
    {
        return (string) (DB::table('departments')->where('department_id', $departmentId)->value('department_name') ?? 'Department');
    }

    private function filters(Request $request): array
    {
        $data = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'program' => ['nullable', 'string', 'max:50'],
            'year' => ['nullable', 'integer', 'between:1,8'],
            'progress' => ['nullable', 'in:high,moderate,low'],
            'sort' => ['nullable', 'in:name_asc,name_desc,progress_desc,progress_asc,attended_desc,eligible_desc'],
            'per_page' => ['nullable', 'integer', 'in:5,10,20'],
        ]);

        return [
            'search' => trim((string) ($data['search'] ?? '')),
            'program' => trim((string) ($data['program'] ?? '')),
            'year' => isset($data['year']) ? (int) $data['year'] : null,
            'progress' => (string) ($data['progress'] ?? ''),
            'sort' => (string) ($data['sort'] ?? 'name_asc'),
            'per_page' => (int) ($data['per_page'] ?? 5),
        ];
    }

    private function filteredQuery(int $departmentId, array $filters): Builder
    {
        $eligible = DB::table('event_eligible_students as es')
            ->join('events as e', 'e.event_id', '=', 'es.event_id')
            ->where('e.department_id', $departmentId)
            ->where('e.status', 'Completed')
            ->select('es.student_id')
            ->selectRaw('COUNT(DISTINCT es.event_id) as eligible_count')
            ->groupBy('es.student_id');

        $attended = DB::table('attendance_records as ar')
            ->join('events as e', 'e.event_id', '=', 'ar.event_id')
            ->join('event_eligible_students as es', function ($join) {
                $join->on('es.event_id', '=', 'ar.event_id')
                    ->on('es.student_id', '=', 'ar.student_id');
            })
            ->where('e.department_id', $departmentId)
            ->where('e.status', 'Completed')
            ->whereIn('ar.status', ['Present', 'Late'])
            ->select('ar.student_id')
            ->selectRaw('COUNT(DISTINCT ar.event_id) as attended_count')
            ->groupBy('ar.student_id');

        $base = DB::table('students as s')
            ->joinSub($eligible, 'eligible', 'eligible.student_id', '=', 's.student_id')
            ->leftJoinSub($attended, 'attended', 'attended.student_id', '=', 's.student_id')
            ->where('s.status', 'Active')
            ->select('s.student_id', 's.student_number', 's.first_name', 's.last_name', 's.program_code', 's.year_level', 'eligible.eligible_count')
            ->selectRaw('COALESCE(attended.attended_count, 0) AS attended_count')
            ->selectRaw('COALESCE(ROUND(100.0 * COALESCE(attended.attended_count, 0) / NULLIF(eligible.eligible_count, 0), 1), 0) AS progress_percentage');

        return DB::query()->fromSub($base, 'p')
            ->when($filters['search'] !== '', function (Builder $query) use ($filters) {
                $term = $filters['search'];
                $query->where(function (Builder $inner) use ($term) {
                    $inner->where('p.student_number', 'like', "%{$term}%")
                        ->orWhere('p.first_name', 'like', "%{$term}%")
                        ->orWhere('p.last_name', 'like', "%{$term}%")
                        ->orWhere('p.program_code', 'like', "%{$term}%");
                });
            })
            ->when($filters['program'] !== '', fn (Builder $query) => $query->where('p.program_code', $filters['program']))
            ->when($filters['year'] !== null, fn (Builder $query) => $query->where('p.year_level', $filters['year']))
            ->when($filters['progress'] === 'high', fn (Builder $query) => $query->where('p.progress_percentage', '>=', 80))
            ->when($filters['progress'] === 'moderate', fn (Builder $query) => $query->where('p.progress_percentage', '>=', 50)->where('p.progress_percentage', '<', 80))
            ->when($filters['progress'] === 'low', fn (Builder $query) => $query->where('p.progress_percentage', '<', 50));
    }

    private function sortedQuery(Builder $query, string $sort): Builder
    {
        match ($sort) {
            'name_desc' => $query->orderByDesc('p.last_name')->orderByDesc('p.first_name'),
            'progress_desc' => $query->orderByDesc('p.progress_percentage')->orderBy('p.last_name'),
            'progress_asc' => $query->orderBy('p.progress_percentage')->orderBy('p.last_name'),
            'attended_desc' => $query->orderByDesc('p.attended_count')->orderBy('p.last_name'),
            'eligible_desc' => $query->orderByDesc('p.eligible_count')->orderBy('p.last_name'),
            default => $query->orderBy('p.last_name')->orderBy('p.first_name'),
        };

        return $query->orderBy('p.student_id');
    }

    private function progressLevel(float $rate): string
    {
        return $rate >= 80 ? 'High' : ($rate >= 50 ? 'Moderate' : 'Low');
    }

    private function safeCsv(?string $value): string
    {
        $value = str_replace(["\r", "\n", "\t"], ' ', (string) $value);
        return preg_match('/^\s*[=+@\-]/u', $value) ? "'" . $value : $value;
    }
}
