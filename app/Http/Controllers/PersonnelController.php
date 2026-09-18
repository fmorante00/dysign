<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personnel = Personnel::all();

    return view('personnel.index', compact('personnel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $roles = \App\Models\Role::where('status', 'Active')->get();

    return view('personnel.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'first_name' => ['required'],
        'last_name' => ['required'],
        'department' => ['required'],
        'position' => ['required'],

        'username' => ['required', 'unique:users'],
        'email' => ['required', 'email', 'unique:users'],
        'password' => ['required'],

        'role_id' => ['required'],
    ]);


    $user = User::create([
        'name' => $request->first_name . ' ' . $request->last_name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $request->role_id,
        'status' => 'Active',
    ]);


    Personnel::create([
        'user_id' => $user->user_id,
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'department' => $request->department,
        'position' => $request->position,
    ]);


    return redirect()
        ->route('personnel.index')
        ->with('success', 'Personnel account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $personnel = Personnel::findOrFail($id);

    $roles = \App\Models\Role::where('status', 'Active')->get();

    return view('personnel.edit', compact('personnel', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $personnel = Personnel::findOrFail($id);


    $request->validate([
        'first_name' => ['required'],
        'last_name' => ['required'],
        'department' => ['required'],
        'position' => ['required'],
        'role_id' => ['required'],
    ]);



    $personnel->update([

        'first_name' => $request->first_name,

        'last_name' => $request->last_name,

        'department' => $request->department,

        'position' => $request->position,

    ]);




    $personnel->user->update([

        'name' => $request->first_name . ' ' . $request->last_name,

        'role_id' => $request->role_id,

    ]);




    return redirect()

        ->route('personnel.index')

        ->with('success', 'Personnel updated successfully.');
    }

    public function activate(string $id)
{
    $personnel = Personnel::findOrFail($id);


    $personnel->user->update([
        'status' => 'Active',
    ]);


    return redirect()
        ->route('personnel.index')
        ->with('success', 'Personnel account activated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $personnel = Personnel::findOrFail($id);


    $personnel->user->update([
        'status' => 'Inactive',
    ]);


    return redirect()
        ->route('personnel.index')
        ->with('success', 'Personnel account deactivated successfully.');   
    }
}
