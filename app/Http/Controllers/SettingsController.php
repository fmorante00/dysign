<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $user->loadMissing(['role', 'department']);

        return view('settings.index', compact('user'));
    }
}
