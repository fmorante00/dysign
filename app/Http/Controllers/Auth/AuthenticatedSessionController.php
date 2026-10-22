<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login page.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate using existing login validation.
        $request->authenticate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | BLOCK INACTIVE ACCOUNTS
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'Active') {

            $this->terminateSession($request);

            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact the administrator.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | REQUIRE ACCOUNT SETUP
        |--------------------------------------------------------------------------
        */

        if ($user->must_change_password) {

            $this->terminateSession($request);

            return back()->withErrors([
                'email' => 'Please complete your account setup before logging in.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESSFUL LOGIN
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }

    /**
     * Securely terminate a user session.
     */
    private function terminateSession(Request $request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    /**
     * Handle logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->terminateSession($request);

        return redirect('/login');
    }
}
