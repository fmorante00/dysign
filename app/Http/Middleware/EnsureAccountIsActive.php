<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status !== 'Active') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your account has been deactivated.'
                ], 403);
            }

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account has been deactivated. Please contact the administrator.'
                ]);
        }

        return $next($request);
    }
}
