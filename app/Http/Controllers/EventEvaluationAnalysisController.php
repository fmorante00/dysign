<?php

namespace App\Http\Controllers;

use App\Models\EvaluationForm;
use App\Models\EventEligibleStudent;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Module #18: read-only analytics of existing evaluation responses.
 * Department Staff can only read evaluations for events in their department.
 */
class EventEvaluationAnalysisController extends Controller
{
    public function index(Request $request): View
    {
        $departmentId = $request->user()?->department_id;
        abort_unless($departmentId, 403, 'Your account is not assigned to a department.');

        $search = trim((string) $request->query('search', ''));

        $forms = EvaluationForm::query()
            ->with('event')
            ->withCount('submissions')
            ->whereHas('event', fn ($query) => $query->where('department_id', $departmentId))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhereHas('event', fn ($eventQuery) => $eventQuery
                            ->where('event_name', 'like', '%' . $search . '%'));
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('staff.evaluation-analysis.index', compact('forms', 'search'));
    }

    public function show(Request $request, int $evaluationForm): View
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

        return view('staff.evaluation-analysis.show', compact(
            'form', 'eligibleCount', 'submissionCount', 'responseRate', 'results', 'overallRating'
        ));
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
