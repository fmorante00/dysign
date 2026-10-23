<?php

namespace App\Http\Controllers;

use App\Mail\AccountInvitationMail;
use App\Models\AccountInvitation;
use App\Models\Department;
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
        // Only predefined staff roles belong to this module.
        $roles = Role::where('status', 'Active')
            ->whereIn('role_name', ['Department Staff', 'Attendance Personnel'])
            ->orderBy('role_name')
            ->get();

        $departments = Department::orderBy('department_name')->get();

        return view('personnel.create', compact('roles', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'department_id' => ['required', 'integer', Rule::exists('departments', 'department_id')],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role_id' => [
                'required',
                Rule::exists('roles', 'role_id')
                    ->where(fn ($query) => $query
                        ->where('status', 'Active')
                        ->whereIn('role_name', ['Department Staff', 'Attendance Personnel'])),
            ],
        ]);

        $department = Department::findOrFail($validated['department_id']);
        $firstName = trim($validated['first_name']);
        $lastName = trim($validated['last_name']);

        $username = $this->generateUniqueUsername(
            $firstName,
            $lastName
        );

        $result = DB::transaction(function () use (
            $validated,
            $department,
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
                'department_id' => $department->department_id,
                'status' => 'Active',
                'must_change_password' => true,
            ]);

            $personnel = Personnel::create([
                'user_id' => $user->user_id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'department' => $department->department_name,
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
                    'Staff account created successfully. An account setup invitation was sent to ' .
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
                    'The staff account was created, but the invitation email could not be sent. Please use Resend Invitation after checking the mail configuration.'
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
        $personnel = Personnel::with('user.role')->findOrFail($id);
        $staffRoles = ['Department Staff', 'Attendance Personnel'];
        $isStaffAccount = in_array($personnel->user->role?->role_name, $staffRoles, true);

        // Only staff roles may be selected for staff accounts. Preserve the
        // current role on older non-staff records without allowing escalation.
        $roles = $isStaffAccount
            ? Role::where('status', 'Active')->whereIn('role_name', $staffRoles)->orderBy('role_name')->get()
            : Role::whereKey($personnel->user->role_id)->get();

        $departments = Department::orderBy('department_name')->get();
        $selectedDepartmentId = $personnel->user->department_id
            ?? Department::where('department_name', $personnel->department)->value('department_id');

        return view('personnel.edit', compact('personnel', 'roles', 'departments', 'selectedDepartmentId'));
    }

    public function update(Request $request, string $id)
    {
        $personnel = Personnel::with('user.role')->findOrFail($id);
        $staffRoles = ['Department Staff', 'Attendance Personnel'];
        $isStaffAccount = in_array($personnel->user->role?->role_name, $staffRoles, true);

        $allowedRoleIds = $isStaffAccount
            ? Role::where('status', 'Active')->whereIn('role_name', $staffRoles)->pluck('role_id')->all()
            : [$personnel->user->role_id];

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'department_id' => ['required', 'integer', Rule::exists('departments', 'department_id')],
            'position' => ['nullable', 'string', 'max:150'],
            'role_id' => ['required', 'integer', Rule::in($allowedRoleIds)],
        ]);

        $department = Department::findOrFail($validated['department_id']);

        DB::transaction(function () use ($validated, $personnel, $department) {
            $firstName = trim($validated['first_name']);
            $lastName = trim($validated['last_name']);
            $position = isset($validated['position']) ? trim($validated['position']) : null;

            $personnel->update([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'department' => $department->department_name,
                'position' => $position !== '' ? $position : null,
            ]);

            $personnel->user->update([
                'name' => $firstName . ' ' . $lastName,
                'role_id' => $validated['role_id'],
                'department_id' => $department->department_id,
            ]);
        });

        return redirect()
            ->route('personnel.index')
            ->with('success', 'Staff account updated successfully.');
    }

    public function resendInvitation(string $id)
    {
        $personnel = Personnel::with('user.invitation')->findOrFail($id);
        $user = $personnel->user;

        
if ($user->status !== 'Active') {
    return redirect()
        ->route('personnel.index')
        ->with(
            'error',
            'Cannot resend an invitation to a deactivated account. Please reactivate the account first.'
        );
}


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
