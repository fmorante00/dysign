<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the password only after the authenticated user has confirmed
     * their current password and completed the email OTP challenge.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $verification = $request->session()->get(PasswordChangeOtpController::VERIFIED_SESSION_KEY);

        $isVerified = is_array($verification)
            && ($verification['user_id'] ?? null) === (string) $user->getAuthIdentifier()
            && ($verification['email'] ?? null) === (string) $user->email
            && ($verification['password_fingerprint'] ?? null) === hash('sha256', (string) $user->getAuthPassword())
            && (int) ($verification['expires_at'] ?? 0) > time();

        if (! $isVerified) {
            $request->session()->forget(PasswordChangeOtpController::VERIFIED_SESSION_KEY);

            return back()->withErrors([
                'otp' => 'Verify the email code before changing your password. Request a new code if your verification has expired.',
            ], 'updatePassword');
        }

        // Consume the authorization. It cannot be reused for another change.
        $request->session()->forget([
            PasswordChangeOtpController::VERIFIED_SESSION_KEY,
            PasswordChangeOtpController::CHALLENGE_SESSION_KEY,
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
