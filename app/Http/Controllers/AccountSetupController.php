<?php

namespace App\Http\Controllers;

use App\Models\AccountInvitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountSetupController extends Controller
{
    /**
     * Display the account setup form for a valid invitation.
     */
    public function show(string $token): View
    {
        $invitation = AccountInvitation::with('user')
            ->where('token', $token)
            ->whereNull('used_at')
            ->firstOrFail();

        if (now()->greaterThanOrEqualTo($invitation->expires_at)) {
            abort(403, 'This invitation link has expired.');
        }

        $user = $invitation->user;

        if (!$user || ($user->status === 'Active' && !$user->must_change_password)) {
            abort(403, 'This account has already been set up or is unavailable.');
        }

        // Staff accounts are created Active while awaiting setup.
        // Deactivated staff cannot reactivate through an invitation.
        if ($user->role?->role_name !== 'Student Account' && $user->status !== 'Active') {
            abort(403, 'This staff account has been deactivated. Please contact the administrator.');
        }

        // Student invitations must be linked to an existing student.
        if ($user->role?->role_name === 'Student Account' && !$user->student) {
            abort(403, 'No student record is linked to this account.');
        }

        // Block deliberately deactivated Student Accounts.
        // First-time setup remains allowed when deactivated_at is null.
        if (
            $user->role?->role_name === 'Student Account' &&
            $user->deactivated_at !== null
        ) {
            abort(
                403,
                'This student account has been deactivated. Please contact the administrator.'
            );
        }

        return view('auth.account-setup', compact('invitation'));
    }

    /**
     * Activate the invited account and consume its setup invitation(s).
     */
    public function store(Request $request, string $token): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        DB::transaction(function () use ($token, $validated) {

            // Retrieve the invitation.
            $candidate = AccountInvitation::where('token', $token)
                ->firstOrFail();

            // Lock the user to prevent concurrent setup requests.
            $user = User::query()
                ->whereKey($candidate->user_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Lock and validate the invitation.
            $invitation = AccountInvitation::where('token', $token)
                ->whereNull('used_at')
                ->lockForUpdate()
                ->firstOrFail();

            if (now()->greaterThanOrEqualTo($invitation->expires_at)) {
                abort(403, 'This invitation link has expired.');
            }

            if ($user->status === 'Active' && !$user->must_change_password) {
                abort(403, 'This account has already been set up.');
            }

            // Prevent deactivated staff accounts from being reactivated.
            if (
                $user->role?->role_name !== 'Student Account' &&
                $user->status !== 'Active'
            ) {
                abort(
                    403,
                    'This staff account has been deactivated. Please contact the administrator.'
                );
            }

            // Ensure Student Accounts are linked to a student record.
            if (
                $user->role?->role_name === 'Student Account' &&
                !$user->student
            ) {
                abort(403, 'No student record is linked to this account.');
            }

            // Recheck student deactivation under the database lock.
            if (
                $user->role?->role_name === 'Student Account' &&
                $user->deactivated_at !== null
            ) {
                abort(
                    403,
                    'This student account has been deactivated. Please contact the administrator.'
                );
            }

            // Complete account setup.
            $user->update([
                'password' => Hash::make($validated['password']),
                'must_change_password' => false,
                'status' => 'Active',
            ]);

            // Invalidate all remaining setup invitations.
            AccountInvitation::where('user_id', $user->getKey())
                ->whereNull('used_at')
                ->update([
                    'used_at' => now(),
                ]);

        }, 3);

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Your account setup is complete. You may now log in.'
            );
    }
}