<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // User role is already restricted by the existing Administrator-only route.
        $filters = $request->validate([
            'search' => 'nullable|string|max:200',
            'module' => 'nullable|string|max:100',
            'action' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to' => 'nullable|date_format:Y-m-d|after_or_equal:date_from',
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

        $logs = $query
            ->orderByDesc('created_at')
            ->orderByDesc('activity_log_id')
            ->paginate(20)
            ->withQueryString();

        $modules = ActivityLog::query()->whereNotNull('module')->distinct()->orderBy('module')->pluck('module');
        $actions = ActivityLog::query()->whereNotNull('action')->distinct()->orderBy('action')->pluck('action');
        $statuses = ActivityLog::query()->whereNotNull('status')->distinct()->orderBy('status')->pluck('status');

        $statistics = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'success' => ActivityLog::where('status', 'Success')->count(),
            'failed' => ActivityLog::where('status', 'Failed')->count(),
        ];

        return view('logs.index', compact('logs', 'modules', 'actions', 'statuses', 'statistics'));
    }
}
