<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Personnel;
use App\Models\AccountInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountInvitationMail;

class PersonnelController extends Controller
{
    public function index()
{
    $personnel = Personnel::with([
        'user.role',
        'user.invitation'
    ])
    ->latest()
    ->get();

    return view('personnel.index', compact('personnel'));
}


    public function create()
    {
        $roles = \App\Models\Role::where('status', 'Active')->get();

        return view('personnel.create', compact('roles'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'department' => ['required'],
            'position' => ['required'],

            'username' => ['required', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],

            'role_id' => ['required'],
        ]);


        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'username' => $request->username,
            'email' => $request->email,

            // temporary internal password
            'password' => Hash::make(Str::random(32)),

            'role_id' => $request->role_id,
            'status' => 'Active',
            'must_change_password' => true,
        ]);



        Personnel::create([
            'user_id' => $user->user_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'department' => $request->department,
            'position' => $request->position,
        ]);



        $invitation = AccountInvitation::create([
    'user_id' => $user->user_id,
    'token' => Str::random(64),
    'expires_at' => now()->addDay(),
]);


Mail::to($user->email)
    ->send(new AccountInvitationMail($invitation));



        return redirect()
            ->route('personnel.index')
            ->with('success', 'Personnel account created successfully. Invitation link generated.');
    }



    public function show(string $id)
    {
        $personnel = Personnel::findOrFail($id);

        return view('personnel.show', compact('personnel'));
    }



    public function edit(string $id)
    {
        $personnel = Personnel::findOrFail($id);

        $roles = \App\Models\Role::where('status', 'Active')->get();

        return view('personnel.edit', compact('personnel', 'roles'));
    }



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


    public function resendInvitation(string $id)
{
    $personnel = Personnel::with('user.invitation')
        ->findOrFail($id);


    $user = $personnel->user;


    // Only resend if setup is not completed

    if (!$user->must_change_password) {

        return redirect()
            ->route('personnel.index')
            ->with('error', 'This account is already active.');

    }



    // Create new invitation if none exists

    $invitation = $user->invitation;



    if ($invitation) {

        $invitation->update([
            'token' => Str::random(64),
            'expires_at' => now()->addDay(),
            'used_at' => null,
        ]);

    } else {

        $invitation = AccountInvitation::create([
            'user_id' => $user->user_id,
            'token' => Str::random(64),
            'expires_at' => now()->addDay(),
        ]);

    }



    Mail::to($user->email)
        ->send(new AccountInvitationMail($invitation));



    return redirect()
        ->route('personnel.index')
        ->with('success', 'Invitation email resent successfully.');
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