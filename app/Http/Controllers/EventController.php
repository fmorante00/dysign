<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index()
    {
        return view('events.index');
    }



    public function create()
    {
        return view('events.create');
    }



    public function store(Request $request)
    {
        //
    }



    public function show(string $id)
    {
        return view('events.show');
    }



    public function edit(string $id)
    {
        return view('events.edit');
    }



    public function update(Request $request, string $id)
    {
        //
    }



    public function destroy(string $id)
    {
        //
    }

 
    public function assign($event)
    {
    return view('events.assign');
    }



}