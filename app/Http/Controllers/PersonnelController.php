<?php

namespace App\Http\Controllers;

use App\Mail\AccountInvitationMail;
use App\Models\AccountInvitation;
use App\Models\Personnel;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PersonnelController extends Controller
{
    public function index()
    {
        $personnel = Personnel::with([
            'user.role',
            'user.invitation',
        ])
        ->latest()
        ->get();

        return view('personnel.index', compact('personnel'));
    }

    public function create()
    {
        $roles = Role::where('status', 'Active')
            ->orderBy('role_name')
            ->get();

        return view('personnel.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'department' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role_id' => [
                'required',
                Rule::exists('roles', 'role_id')
                    ->where(fn ($query) => $query->where('status', 'Active')),
            ],
        ]);

        $firstName = trim($validated['first_name']);
        $lastName = trim($validated['last_name']);

        $username = $this->generateUniqueUsername(
            $firstName,
            $lastName
        );

        $result = DB::transaction(function () use (
            $validated,
            $firstName,
            $lastName,
            $username
        ) {
            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'username' => $username,
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make(Str::random(64)),
                'role_id' => $validated['role_id'],
                'status' => 'Active',
                'must_change_password' => true,
            ]);

            $personnel = Personnel::create([
                'user_id' => $user->user_id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'department' => trim($validated['department']),
            ]);

            $invitation = AccountInvitation::create([
                'user_id' => $user->user_id,
                'token' => Str::random(64),
                'expires_at' => now()->addDay(),
                'used_at' => null,
            ]);

            return [
                'user' => $user,
                'personnel' => $personnel,
                'invitation' => $invitation,
            ];
        });

        $user = $result['user'];
        $invitation = $result['invitation'];

        try {
            Mail::to($user->email)
                ->send(new AccountInvitationMail($invitation));

            return redirect()
                ->route('personnel.index')
                ->with(
                    'success',
                    'Personnel account created successfully. An account setup invitation was sent to ' .
                    $user->email .
                    '. Username: ' .
                    $user->username
                );
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('personnel.index')
                ->with(
                    'error',
                    'The personnel account was created, but the invitation email could not be sent. Please use Resend Invitation after checking the mail configuration.'
                );
        }
    }

    public function show(string $id)
    {
        $personnel = Personnel::with([
            'user.role',
            'user.invitation',
        ])
        ->findOrFail($id);

        return view('personnel.show', compact('personnel'));
    }

    public function edit(string $id)
    {
        $personnel = Personnel::with('user')->findOrFail($id);

        $roles = Role::where('status', 'Active')
            ->orderBy('role_name')
            ->get();

        return view('personnel.edit', compact('personnel', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $personnel = Personnel::with('user')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'department' => ['required', 'string', 'max:150'],
            'position' => ['required', 'string', 'max:150'],
            'role_id' => [
                'required',
                Rule::exists('roles', 'role_id')
                    ->where(fn ($query) => $query->where('status', 'Active')),
            ],
        ]);

        DB::transaction(function () use ($validated, $personnel) {
            $firstName = trim($validated['first_name']);
            $lastName = trim($validated['last_name']);

            $personnel->update([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'department' => trim($validated['department']),
                'position' => trim($validated['position']),
            ]);

            $personnel->user->update([
                'name' => $firstName . ' ' . $lastName,
                'role_id' => $validated['role_id'],
            ]);
        });

        return redirect()
            ->route('personnel.index')
            ->with('success', 'Personnel updated successfully.');
    }

    public function resendInvitation(string $id)
    {
        $personnel = Personnel::with('user.invitation')->findOrFail($id);
        $user = $personnel->user;

        if (!$user->must_change_password) {
            return redirect()
                ->route('personnel.index')
                ->with('error', 'This account has already completed setup.');
        }

        $invitation = DB::transaction(function () use ($user) {
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
                    'used_at' => null,
                ]);
            }

            return $invitation;
        });

        try {
            Mail::to($user->email)
                ->send(new AccountInvitationMail($invitation));

            return redirect()
                ->route('personnel.index')
                ->with(
                    'success',
                    'A new account setup invitation was sent to ' . $user->email . '.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('personnel.index')
                ->with(
                    'error',
                    'The invitation was renewed, but the email could not be sent. Please check the mail configuration and try again.'
                );
        }
    }

    public function activate(string $id)
    {
        $personnel = Personnel::with('user')->findOrFail($id);

        $personnel->user->update([
            'status' => 'Active',
        ]);

        return redirect()
            ->route('personnel.index')
            ->with('success', 'Personnel account activated successfully.');
    }

    public function destroy(string $id)
    {
        $personnel = Personnel::with('user')->findOrFail($id);

        $personnel->user->update([
            'status' => 'Inactive',
        ]);

        return redirect()
            ->route('personnel.index')
            ->with('success', 'Personnel account deactivated successfully.');
    }

    private function generateUniqueUsername(
        string $firstName,
        string $lastName
    ): string {
        $cleanFirstName = Str::lower(
            preg_replace(
                '/[^a-zA-Z0-9]/',
                '',
                Str::ascii(trim($firstName))
            )
        );

        $cleanLastName = Str::lower(
            preg_replace(
                '/[^a-zA-Z0-9]/',
                '',
                Str::ascii(trim($lastName))
            )
        );

        $base = $cleanFirstName . '.' . $cleanLastName;

        if ($base === '.') {
            $base = 'user';
        }

        $username = $base;
        $number = 2;

        while (User::where('username', $username)->exists()) {
            $username = $base . $number;
            $number++;
        }

        return $username;
    }
}
