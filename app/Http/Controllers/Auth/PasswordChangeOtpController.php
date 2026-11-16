<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

/**
 * Email verification for an authenticated user's password change.
 *
 * Uses the existing Laravel session store; no new database table is needed.
 * The existing password.update action must be separately protected before
 * this feature can be considered enabled.
 */
class PasswordChangeOtpController extends Controller
{
    public const CHALLENGE_SESSION_KEY = 'dysign.password_change.challenge';
    public const VERIFIED_SESSION_KEY = 'dysign.password_change.verified';
    public const OTP_LIFETIME_SECONDS = 300;
    private const MAX_ATTEMPTS = 5;

    /**
     * Confirm the current password, then email a short-lived verification code.
     */
    public function send(Request $request): RedirectResponse
    {
        $request->validateWithBag('passwordOtpRequest', [
            'current_password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $email = trim((string) $user->email);

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors([
                'current_password' => 'Your account has no valid email address. Contact an administrator.',
            ], 'passwordOtpRequest');
        }

        // Starting a fresh challenge revokes a prior verification in this session.
        $request->session()->forget([
            self::CHALLENGE_SESSION_KEY,
            self::VERIFIED_SESSION_KEY,
        ]);

        $code = (string) random_int(100000, 999999);
        $challenge = [
            'user_id' => (string) $user->getAuthIdentifier(),
            'email' => $email,
            'password_fingerprint' => hash('sha256', (string) $user->getAuthPassword()),
            // A keyed digest prevents easy offline guessing if session data leaks.
            'code_digest' => hash_hmac('sha256', $code, (string) config('app.key')),
            'expires_at' => time() + self::OTP_LIFETIME_SECONDS,
            'attempts' => 0,
        ];

        try {
            Mail::raw(
                "Your DySign password-change verification code is: {$code}\n\n" .
                "This code expires in 5 minutes. Do not share it with anyone.\n" .
                "If you did not request a password change, you can ignore this email.",
                static function (Message $message) use ($email): void {
                    $message->to($email)->subject('DySign Password Change Verification');
                }
            );
        } catch (\Throwable $exception) {
            // The code is never persisted if delivery fails.
            return back()->withErrors([
                'current_password' => 'Unable to send the verification code. Please try again later.',
            ], 'passwordOtpRequest');
        }

        $request->session()->put(self::CHALLENGE_SESSION_KEY, $challenge);

        return back()->with('status', 'password-otp-sent');
    }

    /**
     * Check the one-time code and grant a short, session-bound authorization.
     */
    public function verify(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('passwordOtpVerify', [
            'otp' => ['required', 'digits:6'],
        ]);

        $challenge = $request->session()->get(self::CHALLENGE_SESSION_KEY);
        $user = $request->user();

        if (! is_array($challenge)
            || ($challenge['user_id'] ?? '') !== (string) $user->getAuthIdentifier()
            || ($challenge['email'] ?? '') !== (string) $user->email
            || ($challenge['password_fingerprint'] ?? '') !== hash('sha256', (string) $user->getAuthPassword())
            || ($challenge['expires_at'] ?? 0) < time()
            || ($challenge['attempts'] ?? self::MAX_ATTEMPTS) >= self::MAX_ATTEMPTS
        ) {
            $request->session()->forget(self::CHALLENGE_SESSION_KEY);

            return back()->withErrors([
                'otp' => 'The verification request has expired or is invalid. Request a new code.',
            ], 'passwordOtpVerify');
        }

        $attempts = (int) $challenge['attempts'] + 1;
        $expected = (string) ($challenge['code_digest'] ?? '');
        $actual = hash_hmac('sha256', (string) $validated['otp'], (string) config('app.key'));

        if ($expected === '' || ! hash_equals($expected, $actual)) {
            if ($attempts >= self::MAX_ATTEMPTS) {
                $request->session()->forget(self::CHALLENGE_SESSION_KEY);
            } else {
                $challenge['attempts'] = $attempts;
                $request->session()->put(self::CHALLENGE_SESSION_KEY, $challenge);
            }

            return back()->withErrors([
                'otp' => $attempts >= self::MAX_ATTEMPTS
                    ? 'Too many incorrect codes. Request a new code.'
                    : 'The verification code is incorrect.',
            ], 'passwordOtpVerify');
        }

        $request->session()->forget(self::CHALLENGE_SESSION_KEY);
        $request->session()->put(self::VERIFIED_SESSION_KEY, [
            'user_id' => (string) $user->getAuthIdentifier(),
            'email' => (string) $user->email,
            'password_fingerprint' => hash('sha256', (string) $user->getAuthPassword()),
            'expires_at' => time() + self::OTP_LIFETIME_SECONDS,
        ]);

        return back()->with('status', 'password-otp-verified');
    }
}
