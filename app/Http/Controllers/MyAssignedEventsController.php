<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyAssignedEventsController extends Controller
{

    public function index()
    {
        return view('my-events.index');
    }

    public function show($event)
{
    return view('my-events.show');
}

}