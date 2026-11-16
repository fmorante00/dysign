<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    private const CATEGORIES = ['All','Events','Attendance','Requests','Schedules','Accounts','System'];

    public function index(Request $request): View
    {
        $user = $request->user();
        $category = (string) $request->query('category', 'All');
        if (!in_array($category, self::CATEGORIES, true)) $category = 'All';
        $status = (string) $request->query('status', 'all');
        if (!in_array($status, ['all','unread','read'], true)) $status = 'all';

        $query = $user->notifications();
        if ($category !== 'All') $query->where('data->category', $category);
        if ($status === 'unread') $query->whereNull('read_at');
        if ($status === 'read') $query->whereNotNull('read_at');

        return view('notifications.index', [
            'notifications' => $query->orderByDesc('created_at')->paginate(10)->withQueryString(),
            'category' => $category,
            'categories' => self::CATEGORIES,
            'status' => $status,
            'totalCount' => $user->notifications()->count(),
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }

    public function count(Request $request): JsonResponse
    {
        return response()->json(['unread' => $request->user()->unreadNotifications()->count()]);
    }

    /** Open a notification securely, marking it read in the same user session. */
    public function open(Request $request, string $notification): RedirectResponse
    {
        $record = $request->user()->notifications()->where('id', $notification)->firstOrFail();
        if ($record->read_at === null) $record->markAsRead();
        $path = data_get($record->data, 'url');
        if (!is_string($path) || !preg_match('~^/(?!/)[^\\x00-\\x1F\\x7F\\\\]*$~u', $path)) {
            $path = '/dashboard';
        }
        if ($path === '/student/registrations') {
            $path = route('student.registrations.index', [], false);
        }
        return redirect()->to($path);
    }

    public function markAsRead(Request $request, string $notification): RedirectResponse
    {
        $record = $request->user()->notifications()->where('id', $notification)->firstOrFail();
        if ($record->read_at === null) $record->markAsRead();
        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        return back()->with('success', 'All notifications marked as read.');
    }
}
