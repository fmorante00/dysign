<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StudentAnnouncementController extends Controller
{
    public function index(): View
    {
        return view(
            'student.announcements.index'
        );
    }
}