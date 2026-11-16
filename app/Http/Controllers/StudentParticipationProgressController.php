<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Support\StudentParticipationPdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class StudentParticipationProgressController extends Controller
{
    private const STATUSES = ['Checked In', 'Present', 'Late', 'Incomplete', 'Absent', 'Excused'];

    public function index(Request $request): View
    {
        $student = $request->user()?->student;
        abort_unless($student, 403, 'No student record is linked to this account.');

        $summary = $this->summary((int) $student->student_id);
        $filters = $this->filters($request);

        $attendanceRecords = $this->attendanceQuery((int) $student->student_id, $filters)
            ->paginate((int) ($filters['per_page'] ?? 5))
            ->withQueryString();

        return view('student.participation-progress.index', [
            'student' => $student,
            'attendanceRecords' => $attendanceRecords,
            'summary' => $summary,
            'filters' => $filters,
            'statuses' => self::STATUSES,
        ]);
    }

    /** Download a full, read-only PDF for the logged-in student only. */
    public function downloadPdf(Request $request): Response
    {
        $student = $request->user()?->student;
        abort_unless($student, 403, 'No student record is linked to this account.');

        $studentId = (int) $student->student_id;
        $summary = $this->summary($studentId);
        $records = $this->attendanceQuery($studentId, ['sort' => 'newest'])->get();
        $pdf = StudentParticipationPdf::render($student, $summary, $records);

        $studentNumber = preg_replace('/[^A-Za-z0-9_-]/', '-', (string) ($student->student_number ?: 'student'));
        $filename = 'DySign-Participation-Report-' . $studentNumber . '-' . now('Asia/Manila')->format('Ymd') . '.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($pdf),
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function filters(Request $request): array
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(array_merge(['all'], self::STATUSES))],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest'])],
            'per_page' => ['nullable', Rule::in(['5', '10', '20'])],
        ]);
        $validated['search'] = trim((string) ($validated['search'] ?? ''));
        $validated['status'] = $validated['status'] ?? 'all';
        $validated['sort'] = $validated['sort'] ?? 'newest';
        $validated['per_page'] = (int) ($validated['per_page'] ?? 5);

        return $validated;
    }

    private function attendanceQuery(int $studentId, array $filters): Builder
    {
        $query = AttendanceRecord::query()
            ->select('attendance_records.*')
            ->join('events', 'events.event_id', '=', 'attendance_records.event_id')
            ->with('event')
            ->where('attendance_records.student_id', $studentId);

        if (!empty($filters['search'])) {
            $term = addcslashes($filters['search'], '%_\\');
            $query->where(function (Builder $q) use ($term) {
                $q->where('events.event_name', 'like', '%' . $term . '%')
                    ->orWhere('events.location', 'like', '%' . $term . '%');
            });
        }
        if (($filters['status'] ?? 'all') !== 'all') {
            $query->where('attendance_records.status', $filters['status']);
        }
        if (!empty($filters['from'])) {
            $query->whereDate('events.event_date', '>=', $filters['from']);
        }
        if (!empty($filters['to'])) {
            $query->whereDate('events.event_date', '<=', $filters['to']);
        }

        $direction = ($filters['sort'] ?? 'newest') === 'oldest' ? 'asc' : 'desc';
        return $query->orderBy('events.event_date', $direction)
            ->orderBy('attendance_records.attendance_id', $direction);
    }

    private function summary(int $studentId): array
    {
        // This denominator preserves the existing DySign participation calculation:
        // completed events for which the student has an eligibility record.
        $eligibleIds = DB::table('event_eligible_students')
            ->join('events', 'events.event_id', '=', 'event_eligible_students.event_id')
            ->where('event_eligible_students.student_id', $studentId)
            ->where('events.status', 'Completed')
            ->distinct()
            ->pluck('events.event_id');

        $eligibleTotal = $eligibleIds->count();
        $attended = $eligibleTotal ? AttendanceRecord::query()
            ->where('student_id', $studentId)
            ->whereIn('event_id', $eligibleIds->all())
            ->whereIn('status', ['Present', 'Late'])
            ->distinct()
            ->count('event_id') : 0;

        $statusCounts = array_fill_keys(self::STATUSES, 0);
        $rows = AttendanceRecord::query()
            ->where('student_id', $studentId)
            ->select('status')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('status')
            ->get();
        foreach ($rows as $row) {
            if (array_key_exists($row->status, $statusCounts)) {
                $statusCounts[$row->status] = (int) $row->total;
            }
        }

        return [
            'eligible' => $eligibleTotal,
            'attended' => $attended,
            'other' => max(0, $eligibleTotal - $attended),
            'percentage' => $eligibleTotal ? round(($attended / $eligibleTotal) * 100, 1) : 0.0,
            'recorded' => array_sum($statusCounts),
            'statuses' => $statusCounts,
        ];
    }
}
