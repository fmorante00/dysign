<x-admin-layout>
    <div class="mx-auto max-w-6xl space-y-6 px-4 py-8 sm:px-6">
        <div class="rounded-2xl bg-[#101064] px-6 py-7 text-white">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#e7c75b]">Student Account Management</p>
            <h1 class="mt-2 text-2xl font-extrabold">Pending Invitation Links</h1>
            <p class="mt-2 text-sm text-white/80">Restricted to Administrators. These one-time setup links are sensitive. Send invitation emails to verified student addresses. Manual setup links are emergency backups only; never publish or include tokens in screenshots.</p>
        </div>
        <div>
            <a class="text-sm font-bold text-[#101064] underline" href="{{ route('students.accounts.index') }}">Back to Student Account Management</a>
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
                        <th class="px-5 py-4">Setup invitation</th>
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
                                    <input aria-label="Private setup link for {{ $user->name }}"
                                        class="w-80 max-w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 font-mono text-xs text-slate-700"
                                        type="text" readonly onclick="this.select()"
                                        value="{{ url('/account/setup/' . $invitation->token) }}">
                                    <p class="mt-1 text-xs text-slate-500">Expires {{ $invitation->expires_at->format('M j, Y g:i A') }}. Click field and press Ctrl+C.</p>
                                @else
                                    <span class="font-semibold text-amber-700">No valid link (expired or missing)</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 space-y-2">
                                <form method="POST" action="{{ route('students.accounts.email-invitation', $user->user_id) }}" onsubmit="return confirm('Send or resend the setup invitation to this student email?')">
                                    @csrf
                                    <button type="submit" class="rounded-xl bg-[#101064] px-4 py-2 text-xs font-bold text-white hover:bg-[#26268d]">Send Invitation Email</button>
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
        <p class="text-xs text-slate-500">Used links cannot be reused. New links are generated only when none is valid. Use Send Invitation Email first. Manual setup links are emergency backup only; keep tokens confidential.</p>
    </div>
</x-admin-layout>
