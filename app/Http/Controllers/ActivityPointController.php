<?php

namespace App\Http\Controllers;

use App\Models\ActivityPoint;
use App\Models\ActivityPointRule;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ActivityPointController extends Controller
{
    /**
     * Ensure the event belongs to the staff's department.
     *
     * Routes must also be protected by the existing
     * Department Staff role middleware.
     */
    private function getDepartmentEvent(
        Request $request,
        int $eventId
    ): Event {
        $user = $request->user();

        abort_unless(
            $user && $user->department_id !== null,
            403,
            'Unauthorized access.'
        );

        return Event::query()
            ->where('event_id', $eventId)
            ->where('department_id', $user->department_id)
            ->firstOrFail();
    }

    /**
     * Display activity point rules and awarded points.
     */
    public function show(
        Request $request,
        int $eventId
    ): View {
        $event = $this->getDepartmentEvent(
            $request,
            $eventId
        );

        $rule = ActivityPointRule::query()
            ->where('event_id', $eventId)
            ->first();

        $activityPoints = ActivityPoint::query()
            ->with(['student', 'attendance'])
            ->where('event_id', $eventId)
            ->orderByDesc('awarded_at')
            ->paginate(15);

        $totalPoints = ActivityPoint::query()
            ->where('event_id', $eventId)
            ->sum('points_awarded');

        $awardedStudents = ActivityPoint::query()
            ->where('event_id', $eventId)
            ->where('points_awarded', '>', 0)
            ->count();

        return view('staff.activity-points.show', [
            'event' => $event,
            'rule' => $rule,
            'activityPoints' => $activityPoints,
            'totalPoints' => $totalPoints,
            'awardedStudents' => $awardedStudents,
        ]);
    }

    /**
     * Create or update activity point rules.
     *
     * Only Upcoming events can have their
     * point configurations modified.
     */
    public function updateRule(
        Request $request,
        int $eventId
    ): RedirectResponse {
        $event = $this->getDepartmentEvent(
            $request,
            $eventId
        );

        $validated = $request->validate([
            'present_points' => [
                'required',
                'integer',
                'min:0',
                'max:4294967295',
            ],
            'late_points' => [
                'required',
                'integer',
                'min:0',
                'max:4294967295',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $event,
            $validated
        ) {
            $lockedEvent = Event::query()
                ->where('event_id', $event->event_id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $lockedEvent->status === 'Upcoming',
                422,
                'Point rules can only be modified for upcoming events.'
            );

            $rule = ActivityPointRule::query()
                ->firstOrNew([
                    'event_id' => $lockedEvent->event_id,
                ]);

            if (!$rule->exists) {
                $rule->created_by =
                    $request->user()->user_id;
            }

            $rule->fill($validated);
            $rule->save();
        });

        return back()->with(
            'success',
            'Activity point rules saved successfully.'
        );
    }


    /**
     * Display events available for Activity Point Management.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        abort_unless(
            $user && $user->department_id !== null,
            403,
            'Unauthorized access.'
        );

        $search = trim((string) $request->query('search', ''));

        $status = (string) $request->query('status', '');

        if (!in_array($status, [
            'Upcoming',
            'Ongoing',
            'Completed',
            'Cancelled',
        ], true)) {
            $status = '';
        }

        $events = Event::query()
            ->where('department_id', $user->department_id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(
                    'event_name',
                    'like',
                    '%' . $search . '%'
                );
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('event_date')
            ->orderByDesc('event_id')
            ->paginate(12)
            ->withQueryString();

        $configuredEventIds = ActivityPointRule::query()
            ->whereIn('event_id', $events->pluck('event_id'))
            ->pluck('event_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return view('staff.activity-points.index', [
            'events' => $events,
            'search' => $search,
            'status' => $status,
            'configuredEventIds' => $configuredEventIds,
        ]);
    }

}
