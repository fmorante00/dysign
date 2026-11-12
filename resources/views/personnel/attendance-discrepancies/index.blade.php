<x-admin-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="rounded-3xl border-b-4 border-[#D4A017] bg-[#101064] px-7 py-9 text-white shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[.28em] text-[#F2C94C]">Module #22 · Attendance Personnel</p>
            <h1 class="mt-3 text-3xl font-extrabold">Discrepancy Verification</h1>
            <p class="mt-2 text-sm text-indigo-100">Check student claims only for events assigned to you. Verification does not edit attendance; Department Staff makes the decision.</p>
        </section>
        @if(session('success'))<div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="space-y-4">
            @forelse ($discrepancies as $item)
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div><p class="text-xs font-bold uppercase tracking-wide text-[#D4A017]">Request #{{ $item->discrepancy_id }} · {{ $item->request_type }}</p>
                            <h2 class="mt-1 text-lg font-extrabold text-[#101064]">{{ $item->student?->last_name }}, {{ $item->student?->first_name }} <span class="text-sm font-normal text-slate-500">{{ $item->student?->student_number }}</span></h2>
                            <p class="mt-1 text-sm text-slate-600">{{ $item->event?->event_name }} · {{ $item->event?->event_date?->format('M d, Y') }}</p>
                            <p class="mt-1 text-sm text-slate-700">Requested status: {{ $item->requested_status ?? 'No status change' }} · Requested time: {{ $item->requested_time_in?->format('M d, Y g:i A') ?? 'No time change' }}</p>
                        </div>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">{{ $item->verification_status }}</span>
                    </div>
                    <div class="mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-700"><strong>Student explanation:</strong><p class="mt-1 whitespace-pre-wrap">{{ $item->reason }}</p></div>
                    @if ($item->verification_status === 'Unverified')
                        <form method="POST" action="{{ route('personnel.attendance-discrepancies.verify', $item->discrepancy_id) }}" class="mt-5 grid gap-3 border-t border-slate-100 pt-5 md:grid-cols-3">
                            @csrf @method('PATCH')
                            <div><label class="mb-2 block text-sm font-semibold">Verification Result</label><select name="verification_status" required class="w-full rounded-xl border border-slate-300 p-3 text-sm"><option value="">Choose result</option><option value="Confirmed">Confirmed</option><option value="Not Confirmed">Not Confirmed</option></select></div>
                            <div class="md:col-span-2"><label class="mb-2 block text-sm font-semibold">Verification Evidence / Notes (required)</label><textarea name="verification_notes" rows="2" minlength="10" maxlength="2000" required class="w-full rounded-xl border border-slate-300 p-3 text-sm" placeholder="Explain how you checked the attendance log or observed the student."></textarea></div>
                            <div class="md:col-span-3 flex justify-end"><button class="rounded-xl bg-[#101064] px-6 py-3 text-sm font-bold text-white" onclick="return confirm('Save this verification?')">Save Verification</button></div>
                        </form>
                    @else
                        <div class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-600"><strong>Verified by:</strong> {{ $item->verifier?->name }} · {{ $item->verified_at?->format('M d, Y g:i A') }}<p class="mt-1 whitespace-pre-wrap">{{ $item->verification_notes }}</p></div>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">No pending discrepancies for events assigned to you.</div>
            @endforelse
            <div>{{ $discrepancies->links() }}</div>
        </section>
    </div>
</x-admin-layout>
