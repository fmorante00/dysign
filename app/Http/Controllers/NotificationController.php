<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mark One Notification as Read
    |--------------------------------------------------------------------------
    */

    public function markAsRead(
        Request $request,
        string $notification
    ): RedirectResponse {

        // Only find notifications belonging to the logged-in user.
        $record = $request->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $record->markAsRead();

        return back()->with(
            'success',
            'Notification marked as read.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Mark All Notifications as Read
    |--------------------------------------------------------------------------
    */

    public function markAllAsRead(
        Request $request
    ): RedirectResponse {

        $request->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }
}
