<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function index()
    {
        return view('students.index');
    }

    public function import()
{
    return view('students.import');
}


    public function create()
    {
        return view('students.create');
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        return view('students.show');
    }


    public function edit(string $id)
    {
        return view('students.edit');
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }

}