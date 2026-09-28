<?php

namespace App\Http\Controllers;

use App\Models\AccountInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountSetupController extends Controller
{
    public function show($token)
    {
        $invitation = AccountInvitation::where('token', $token)
            ->whereNull('used_at')
            ->firstOrFail();


        if (now()->greaterThan($invitation->expires_at)) {
            abort(403, 'This invitation link has expired.');
        }


        return view('auth.account-setup', compact('invitation'));
    }



    public function store(Request $request, $token)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ]);


        $invitation = AccountInvitation::where('token', $token)
            ->whereNull('used_at')
            ->firstOrFail();


        if (now()->greaterThan($invitation->expires_at)) {
            abort(403, 'This invitation link has expired.');
        }


        $user = $invitation->user;


        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
            'status' => 'Active',
        ]);


        $invitation->update([
            'used_at' => now(),
        ]);


        return redirect()
            ->route('login')
            ->with('success', 'Your account setup is complete. You may now login.');
    }
}