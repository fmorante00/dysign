<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Student;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with([
            'event',
            'creator'
        ])
        ->latest()
        ->get();

        $events = Event::whereNotIn('status', [
            'Completed',
            'Cancelled'
        ])
        ->orderBy('event_date')
        ->get();

        $colleges = Student::where('status', 'Active')
            ->whereNotNull('college')
            ->distinct()
            ->orderBy('college')
            ->pluck('college');

        $yearLevels = Student::where('status', 'Active')
            ->distinct()
            ->orderBy('year_level')
            ->pluck('year_level');

        $totalAnnouncements =
            Announcement::count();

        $totalRecipients =
            Announcement::sum('recipient_count');

        return view(
            'announcements.index',
            compact(
                'announcements',
                'events',
                'colleges',
                'yearLevels',
                'totalAnnouncements',
                'totalRecipients'
            )
        );
    }
}