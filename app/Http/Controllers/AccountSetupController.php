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
        // A deactivated staff member must not reactivate via an unused invitation.
        // Student Account provisioning has a separate pending-account lifecycle.
        if ($user->role?->role_name !== 'Student Account' && $user->status !== 'Active') {
            abort(403, 'This staff account has been deactivated. Please contact the administrator.');
        }

        // Student invitations must point to an actual linked student record.
        if ($user->role?->role_name === 'Student Account' && !$user->student) {
            abort(403, 'No student record is linked to this account.');
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
            // Read the invitation first to determine which user row to lock.
            $candidate = AccountInvitation::where('token', $token)->firstOrFail();

            // Serialize setup requests for the same user, even if they have
            // different valid invitation tokens.
            $user = User::query()
                ->whereKey($candidate->user_id)
                ->lockForUpdate()
                ->firstOrFail();

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

            // Re-check after locking the user record to avoid setup/deactivation races.
            if ($user->role?->role_name !== 'Student Account' && $user->status !== 'Active') {
                abort(403, 'This staff account has been deactivated. Please contact the administrator.');
            }

            if ($user->role?->role_name === 'Student Account' && !$user->student) {
                abort(403, 'No student record is linked to this account.');
            }

            $user->update([
                'password' => Hash::make($validated['password']),
                'must_change_password' => false,
                'status' => 'Active',
            ]);

            // Invalidate all outstanding account-setup links for this user.
            AccountInvitation::where('user_id', $user->getKey())
                ->whereNull('used_at')
                ->update(['used_at' => now()]);
        }, 3);

        return redirect()
            ->route('login')
            ->with('success', 'Your account setup is complete. You may now log in.');
    }
}
