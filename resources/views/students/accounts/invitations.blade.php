<x-admin-layout>
    <div class="mx-auto max-w-6xl space-y-6 px-4 py-8 sm:px-6">
        <div class="rounded-2xl bg-[#101064] px-6 py-7 text-white">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#e7c75b]">Student Account Management</p>
            <h1 class="mt-2 text-2xl font-extrabold">Student Invitation Email Recovery</h1>
            <p class="mt-2 text-sm text-white/80">Restricted to Administrators. These one-time setup links are sensitive. New accounts receive an automatic email attempt during Bulk Provisioning. Use this administrator-only page only to retry undelivered invitations or access an emergency setup link. Never share links publicly.</p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a class="text-sm font-bold text-[#101064] underline" href="{{ route('students.accounts.index') }}">Back to Student Account Management</a>
            <p class="text-xs font-medium text-slate-600">Email first &bull; Retry when needed &bull; Backup links only in emergencies</p>
        </div>
        <div class="rounded-2xl border border-[#e7e0c4] bg-[#fffcf0] px-5 py-4">
            <p class="text-sm font-extrabold text-[#101064]">Automatic delivery is the normal workflow</p>
            <p class="mt-1 text-xs leading-5 text-slate-700">When an Administrator confirms Bulk Provisioning, DySign automatically attempts to send each new student's invitation by email. If an email fails or does not arrive, choose <strong>Send / Resend Email</strong> below. Only reveal or share an emergency link after verifying the student's identity. Email-provider acceptance does not guarantee inbox delivery.</p>
        </div>
        @if(session('success'))
            <p class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</p>
        @endif
        <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Student</th>
                        <th class="px-5 py-4">Email</th>
                        <th class="px-5 py-4">Emergency setup link</th>
                        <th class="px-5 py-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendingUsers as $user)
                        @php($invitation = $invitationLinks->get($user->user_id))
                        <tr>
                            <td class="px-5 py-4 font-semibold text-[#101064]">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                @if($invitation)
                                    <p class="text-xs font-bold text-emerald-700">Valid invitation available</p>
                                    <p class="mt-1 text-xs text-slate-500">Expires {{ $invitation->expires_at->format('M j, Y g:i A') }}</p>
                                    <details class="mt-2 rounded-lg border border-amber-200 bg-amber-50 p-2">
                                        <summary class="cursor-pointer text-xs font-bold text-amber-900">Reveal emergency link</summary>
                                        <input aria-label="Private emergency setup link for {{ $user->name }}"
                                            class="mt-2 w-80 max-w-full rounded-lg border border-slate-300 bg-white px-3 py-2 font-mono text-xs text-slate-700"
                                            type="text" readonly onclick="this.select()"
                                            value="{{ url('/account/setup/' . $invitation->token) }}">
                                        <p class="mt-1 text-xs text-amber-900">Sensitive one-time link. Verify identity, click the field, then press Ctrl+C. Never include it in a screenshot.</p>
                                    </details>
                                @else
                                    <span class="font-semibold text-amber-700">No valid link (expired or missing)</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 space-y-2">
                                <form method="POST" action="{{ route('students.accounts.email-invitation', $user->user_id) }}" onsubmit="return confirm('Send or resend the setup invitation to this student email?')">
                                    @csrf
                                    <button type="submit" class="rounded-xl bg-[#101064] px-4 py-2 text-xs font-bold text-white hover:bg-[#26268d]">Send / Resend Email</button>
                                </form>
                                @unless($invitation)
                                    <form method="POST" action="{{ route('students.accounts.reissue-invitation', $user->user_id) }}">
                                        @csrf
                                        <button type="submit" class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-bold text-[#101064] hover:bg-slate-50">Generate Backup Link</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">No pending student accounts currently need first-time setup.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="text-xs text-slate-500">Used links cannot be reused. New links are generated only when none is valid. Bulk Provisioning attempts email automatically. Use this page only for undelivered invitations or emergency recovery. Keep setup tokens confidential.</p>
    </div>
</x-admin-layout>
