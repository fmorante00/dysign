<?php

namespace App\Http\Controllers;

use App\Models\EvaluationForm;
use App\Models\EvaluationSubmission;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\EventEligibleStudent;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Module #18: read-only analytics of existing evaluation responses.
 * Department Staff can only read evaluations for events in their department.
 */
class EventEvaluationAnalysisController extends Controller
{
    public function index(Request $request): View|StreamedResponse
    {
        $departmentId = $request->user()?->department_id;
        abort_unless($departmentId, 403, 'Your account is not assigned to a department.');

        $search = trim(substr((string) $request->query('search', ''), 0, 120));
        $status = (string) $request->query('status', 'all');
        if (!in_array($status, ['all', 'Draft', 'Scheduled', 'Published', 'Closed'], true)) {
            $status = 'all';
        }
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 25, 50], true)) {
            $perPage = 10;
        }

        // All totals and exports are constrained to the authenticated staff department.
        $base = EvaluationForm::query()->whereHas('event', fn ($query) =>
            $query->where('department_id', $departmentId));

        $totalForms = (clone $base)->count();
        $publishedForms = (clone $base)->where('status', 'Published')->count();
        $scheduledForms = (clone $base)->where('status', 'Scheduled')->count();
        $totalResponses = EvaluationSubmission::query()
            ->whereHas('form.event', fn ($query) => $query->where('department_id', $departmentId))
            ->count();

        $query = (clone $base)
            ->with('event')
            ->withCount('submissions')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhereHas('event', fn ($eventQuery) => $eventQuery
                            ->where('event_name', 'like', '%'.$search.'%'));
                });
            })
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->orderByDesc('created_at');

        if ($request->query('export') === 'csv') {
            // Export the *filtered* department directory without a page limit.
            $rows = (clone $query)->get();
            return response()->streamDownload(function () use ($rows) {
                $stream = fopen('php://output', 'w');
                fwrite($stream, "\xEF\xBB\xBF"); // Excel-friendly UTF-8 BOM
                fputcsv($stream, ['Form ID', 'Questionnaire', 'Event', 'Event Date', 'Status', 'Responses']);
                foreach ($rows as $form) {
                    fputcsv($stream, [
                        $form->evaluation_form_id,
                        $this->csvSafe($form->title),
                        $this->csvSafe($form->event?->event_name ?? ''),
                        $form->event?->event_date?->format('Y-m-d') ?? '',
                        $form->status,
                        $form->submissions_count,
                    ]);
                }
                fclose($stream);
            }, 'dysign-evaluation-directory-'.now()->format('Ymd-His').'.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Cache-Control' => 'no-store, private',
            ]);
        }

        $forms = $query->paginate($perPage)->withQueryString();

        return view('staff.evaluation-analysis.index', compact(
            'forms', 'search', 'status', 'perPage', 'totalForms',
            'publishedForms', 'scheduledForms', 'totalResponses'
        ));
    }

    public function show(Request $request, int $evaluationForm): View|StreamedResponse
    {
        $departmentId = $request->user()?->department_id;
        abort_unless($departmentId, 403, 'Your account is not assigned to a department.');

        // The ownership check is enforced at the database query, not just in the sidebar.
        $form = EvaluationForm::query()
            ->whereHas('event', fn ($query) => $query->where('department_id', $departmentId))
            ->with([
                'event',
                'questions.options',
                'submissions.answers.selectedOptions',
            ])
            ->findOrFail($evaluationForm);

        $eligibleCount = EventEligibleStudent::query()
            ->where('event_id', $form->event_id)
            ->distinct('student_id')
            ->count('student_id');

        $submissionCount = $form->submissions->count();
        $responseRate = $eligibleCount > 0
            ? round($submissionCount / $eligibleCount * 100, 1)
            : null;

        $allAnswers = $form->submissions->flatMap(fn ($submission) => $submission->answers);
        $results = [];
        $allRatingValues = collect();

        foreach ($form->questions as $question) {
            $answers = $allAnswers->where('evaluation_question_id', $question->evaluation_question_id);
            $result = [
                'question' => $question,
                'answered' => 0,
                'average' => null,
                'distribution' => [],
                'comments' => [],
                'is_multiple' => $question->question_type === 'Multiple Choice',
            ];

            switch ($question->question_type) {
                case 'Rating':
                    $values = $answers->pluck('answer_number')
                        ->filter(fn ($value) => $value !== null)->map(fn ($value) => (int) $value);
                    $result['answered'] = $values->count();
                    $result['average'] = $values->isNotEmpty() ? round($values->avg(), 2) : null;
                    $allRatingValues = $allRatingValues->concat($values);
                    $min = max(1, (int) ($question->rating_min ?? 1));
                    $max = min(10, max($min, (int) ($question->rating_max ?? 5)));
                    foreach (range($min, $max) as $rating) {
                        $count = $values->filter(fn ($value) => $value === $rating)->count();
                        $result['distribution'][] = $this->optionResult((string) $rating, $count, $values->count());
                    }
                    break;

                case 'Yes No':
                    $values = $answers->pluck('answer_boolean')
                        ->filter(fn ($value) => $value !== null);
                    $result['answered'] = $values->count();
                    $yes = $values->filter(fn ($value) => (bool) $value)->count();
                    $result['distribution'] = [
                        $this->optionResult('Yes', $yes, $values->count()),
                        $this->optionResult('No', $values->count() - $yes, $values->count()),
                    ];
                    break;

                case 'Single Choice':
                case 'Multiple Choice':
                    $selected = $answers->map(fn ($answer) => $answer->selectedOptions
                        ->pluck('evaluation_question_option_id')->unique()->values());
                    $answered = $selected->filter(fn ($selection) => $selection->isNotEmpty());
                    $result['answered'] = $answered->count();
                    $allSelectedIds = $answered->flatMap(fn ($selection) => $selection);
                    foreach ($question->options as $option) {
                        $count = $allSelectedIds->filter(fn ($id) => (int) $id === (int) $option->evaluation_question_option_id)->count();
                        $result['distribution'][] = $this->optionResult(
                            $option->option_text, $count, $answered->count()
                        );
                    }
                    break;

                case 'Short Answer':
                case 'Paragraph':
                    $comments = $answers->pluck('answer_text')
                        ->filter(fn ($value) => is_string($value) && trim($value) !== '')
                        ->map(fn ($value) => trim($value));
                    $result['answered'] = $comments->count();
                    // Comments are shown without student identities and capped for readability.
                    $result['comments'] = $comments->take(40)->values()->all();
                    break;
            }

            $results[] = $result;
        }

        $overallRating = $allRatingValues->isNotEmpty()
            ? round($allRatingValues->avg(), 2)
            : null;

        if ($request->query('export') === 'csv') {
            return $this->downloadAnalysisCsv(
                $form, $eligibleCount, $submissionCount, $responseRate, $overallRating, $results
            );
        }

        if ($request->boolean('print')) {
            return view('staff.evaluation-analysis.print', compact(
                'form', 'eligibleCount', 'submissionCount', 'responseRate', 'results', 'overallRating'
            ));
        }

        return view('staff.evaluation-analysis.show', compact(
            'form', 'eligibleCount', 'submissionCount', 'responseRate', 'results', 'overallRating'
        ));
    }

    /**
     * Export a single, rectangular question-by-question CSV table.
     * A CSV cannot store fonts, logos, column widths, or merged cells;
     * use the existing branded Print / Save PDF report for visual presentation.
     * No student identifiers or email addresses are included.
     */
    private function downloadAnalysisCsv(
        EvaluationForm $form,
        int $eligibleCount,
        int $submissionCount,
        ?float $responseRate,
        ?float $overallRating,
        array $results
    ): StreamedResponse {
        return response()->streamDownload(function () use ($results) {
            $stream = fopen('php://output', 'w');
            // BOM preserves Filipino names and other UTF-8 text in Excel/WPS.
            fwrite($stream, "\xEF\xBB\xBF");

            // Exactly one header, and every data row has exactly eight columns.
            $headers = [
                'Question #', 'Question', 'Question Type', 'Answer / Option',
                'Count', 'Percent (%)', 'Answered', 'Average Rating',
            ];
            fputcsv($stream, $headers);

            $writeRow = function (array $values) use ($stream): void {
                fputcsv($stream, array_map(fn ($value) => $this->csvSafe($value), $values));
            };

            foreach ($results as $index => $result) {
                $question = $result['question'];
                $common = [
                    $index + 1,
                    $question->question_text,
                    $question->question_type,
                ];
                $answered = (int) $result['answered'];
                $average = $result['average'] ?? '';

                if (count($result['distribution']) > 0) {
                    foreach ($result['distribution'] as $choice) {
                        $writeRow([
                            ...$common,
                            $choice['label'],
                            (int) $choice['count'],
                            $choice['percent'],
                            $answered,
                            $average,
                        ]);
                    }
                    continue;
                }

                if (count($result['comments']) > 0) {
                    // Text answers are anonymous, with the same 40-comment cap as the page.
                    foreach ($result['comments'] as $comment) {
                        $writeRow([
                            ...$common,
                            preg_replace('/\s+/u', ' ', trim((string) $comment)) ?? trim((string) $comment),
                            '', '', $answered, '',
                        ]);
                    }
                    continue;
                }

                $writeRow([...$common, 'No responses', 0, '', 0, $average]);
            }
            fclose($stream);
        }, 'dysign-question-results-'.$form->evaluation_form_id.'-'
            .Str::slug((string) $form->title).'-'.now()->format('Ymd').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, private',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /** Prevent spreadsheet programs from executing imported cell content as formulas. */
    private function csvSafe(mixed $value): string|int|float
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }
        $value = (string) ($value ?? '');
        return preg_match('/^[\s]*[=+\-@]/u', $value) ? "'".$value : $value;
    }

    private function optionResult(string $label, int $count, int $answered): array
    {
        return [
            'label' => $label,
            'count' => $count,
            'percent' => $answered > 0 ? round($count / $answered * 100, 1) : 0,
        ];
    }
}
