<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * High-impact actions that are already emitted by ActivityLogMiddleware.
     * These are review priorities, not security incident verdicts.
     */
    private const SENSITIVE_ACTIONS = [
        'Updated role or permissions',
        'Deactivated personnel account',
        'Activated personnel account',
        'Created personnel account',
        'Updated personnel account',
        'Resent account invitation',
        'Reported RFID card as lost',
        'Replaced RFID card',
        'Approved imported RFID card',
        'Cancelled or deleted event',
        'Updated event personnel assignment',
        'Imported student records',
        'Created system database backup',
    ];

    public function index(Request $request)
    {
        // The /logs route already enforces auth + Administrator role.
        $filters = $request->validate([
            'search' => 'nullable|string|max:200',
            'module' => 'nullable|string|max:100',
            'action' => 'nullable|string|max:100',
            'status' => 'nullable|in:Success,Failed',
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to' => 'nullable|date_format:Y-m-d|after_or_equal:date_from',
            'priority' => 'nullable|in:all,failed,sensitive,routine',
            'sort' => 'nullable|in:priority,latest',
            'per_page' => 'nullable|in:10,25,50',
        ]);

        $query = ActivityLog::query()->with('user');

        if (filled($filters['search'] ?? null)) {
            $term = trim($filters['search']);
            $query->where(function ($q) use ($term) {
                $q->where('action', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('module', 'like', "%{$term}%")
                    ->orWhere('subject_type', 'like', "%{$term}%")
                    ->orWhere('subject_id', 'like', "%{$term}%")
                    ->orWhereHas('user', function ($users) use ($term) {
                        $users->where('name', 'like', "%{$term}%")
                            ->orWhere('username', 'like', "%{$term}%");
                    });
            });
        }

        foreach (['module', 'action', 'status'] as $field) {
            if (filled($filters[$field] ?? null)) {
                $query->where($field, $filters[$field]);
            }
        }

        if (filled($filters['date_from'] ?? null)) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (filled($filters['date_to'] ?? null)) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $priority = $filters['priority'] ?? 'all';
        if ($priority === 'failed') {
            $query->where('status', 'Failed');
        } elseif ($priority === 'sensitive') {
            $query->where('status', 'Success')->whereIn('action', self::SENSITIVE_ACTIONS);
        } elseif ($priority === 'routine') {
            $query->where('status', 'Success')->whereNotIn('action', self::SENSITIVE_ACTIONS);
        }

        // The review queue respects the exact same active filters as the table.
        $reviewScope = function ($q) {
            $q->where('status', 'Failed')
                ->orWhere(function ($sensitive) {
                    $sensitive->where('status', 'Success')
                        ->whereIn('action', self::SENSITIVE_ACTIONS);
                });
        };
        $reviewCount = (clone $query)->where($reviewScope)->count();
        // Show both categories in the spotlight, even when many failures exist.
        $failedReviewLogs = (clone $query)->where('status', 'Failed')
            ->orderByDesc('created_at')->orderByDesc('activity_log_id')->limit(2)->get();
        $sensitiveReviewLogs = (clone $query)->where('status', 'Success')
            ->whereIn('action', self::SENSITIVE_ACTIONS)
            ->orderByDesc('created_at')->orderByDesc('activity_log_id')->limit(2)->get();
        $reviewLogs = $failedReviewLogs->concat($sensitiveReviewLogs);

        $sort = $filters['sort'] ?? 'priority';
        if ($sort === 'latest') {
            $query->orderByDesc('created_at')->orderByDesc('activity_log_id');
        } else {
            $this->orderByPriority($query);
        }

        $perPage = (int) ($filters['per_page'] ?? 10);
        $logs = $query->paginate($perPage)->withQueryString();

        $modules = ActivityLog::query()->whereNotNull('module')->distinct()->orderBy('module')->pluck('module');
        $actions = ActivityLog::query()->whereNotNull('action')->distinct()->orderBy('action')->pluck('action');

        // The overview numbers are all-time values; applying filters does not change them.
        $statistics = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'success' => ActivityLog::where('status', 'Success')->count(),
            'failed' => ActivityLog::where('status', 'Failed')->count(),
            'sensitive' => ActivityLog::where('status', 'Success')->whereIn('action', self::SENSITIVE_ACTIONS)->count(),
        ];

        $sensitiveActions = self::SENSITIVE_ACTIONS;

        return view('logs.index', compact(
            'logs', 'modules', 'actions', 'statistics', 'sensitiveActions',
            'reviewLogs', 'reviewCount', 'priority', 'sort', 'perPage'
        ));
    }

    private function orderByPriority($query): void
    {
        $placeholders = implode(', ', array_fill(0, count(self::SENSITIVE_ACTIONS), '?'));

        // Failed first, successful high-impact changes second, routine actions last.
        // Bindings are fixed code-defined action names, never arbitrary user SQL.
        $query->orderByRaw(
            "CASE WHEN `status` = ? THEN 0 WHEN `status` = ? AND `action` IN ({$placeholders}) THEN 1 ELSE 2 END ASC",
            array_merge(['Failed', 'Success'], self::SENSITIVE_ACTIONS)
        )->orderByDesc('created_at')->orderByDesc('activity_log_id');
    }
}
