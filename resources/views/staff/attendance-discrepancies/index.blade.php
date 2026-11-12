<x-admin-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="rounded-3xl border-b-4 border-[#D4A017] bg-[#101064] px-7 py-9 text-white shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[.28em] text-[#F2C94C]">Module #22 · Department Staff</p>
            <h1 class="mt-3 text-3xl font-extrabold">Attendance Discrepancy Reviews</h1>
            <p class="mt-2 text-sm text-indigo-100">Review student-reported attendance issues for your department's events. Approved corrections keep before/after audit snapshots.</p>
        </section>
        @if(session('success'))<div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pending Reviews</p><div class="mt-2 text-3xl font-extrabold text-[#101064]">{{ $pendingCount }}</div></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Visible on This Page</p><div class="mt-2 text-3xl font-extrabold text-[#101064]">{{ $discrepancies->count() }}</div></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Scope</p><p class="mt-2 font-bold text-[#101064]">Your Department's Events</p></div>
        </section>
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('staff.attendance-discrepancies.index') }}" class="grid gap-4 md:grid-cols-3">
                <div><label class="mb-2 block text-sm font-semibold text-slate-600">Event</label><select name="event_id" class="w-full rounded-xl border border-slate-300 p-3 text-sm"><option value="">All Department Events</option>@foreach($events as $event)<option value="{{ $event->event_id }}" @selected(request('event_id') == $event->event_id)>{{ $event->event_name }}</option>@endforeach</select></div>
                <div><label class="mb-2 block text-sm font-semibold text-slate-600">Status</label><select name="status" class="w-full rounded-xl border border-slate-300 p-3 text-sm"><option value="">All Statuses</option>@foreach(['Pending', 'Approved', 'Rejected'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select></div>
                <div class="flex items-end gap-3"><button class="rounded-xl bg-[#101064] px-6 py-3 text-sm font-bold text-white">Filter</button><a href="{{ route('staff.attendance-discrepancies.index') }}" class="py-3 text-sm font-semibold text-slate-500">Reset</a></div>
            </form>
        </section>
        <section class="space-y-4">
            @forelse($discrepancies as $item)
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div><p class="text-xs font-bold uppercase tracking-wide text-[#D4A017]">Request #{{ $item->discrepancy_id }} · {{ $item->request_type }}</p>
                            <h2 class="mt-1 text-lg font-extrabold text-[#101064]">{{ $item->student?->last_name }}, {{ $item->student?->first_name }} <span class="text-sm font-normal text-slate-500">{{ $item->student?->student_number }}</span></h2>
                            <p class="mt-1 text-sm text-slate-600">{{ $item->event?->event_name }} · {{ $item->event?->event_date?->format('M d, Y') }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $item->status === 'Approved' ? 'bg-green-100 text-green-800' : ($item->status === 'Rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">{{ $item->status }}</span>
                    </div>
                    <div class="mt-4 grid gap-4 lg:grid-cols-2">
                        <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-700">
                            <p class="font-bold">Student Request</p><p class="mt-1 whitespace-pre-wrap">{{ $item->reason }}</p>
                            <p class="mt-3 text-xs"><strong>Recorded when submitted:</strong> {{ $item->before_status ?? 'No attendance' }} · {{ $item->before_time_in?->format('M d, Y g:i A') ?? 'No time' }}</p>
                            <p class="mt-1 text-xs"><strong>Requested:</strong> {{ $item->requested_status ?? 'Keep status' }} · {{ $item->requested_time_in?->format('M d, Y g:i A') ?? 'Keep time' }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-700">
                            <p class="font-bold">Attendance Personnel Verification: {{ $item->verification_status }}</p>
                            <p class="mt-2 whitespace-pre-wrap">{{ $item->verification_notes ?: 'No verification submitted. Staff must check independent evidence.' }}</p>
                            @if($item->verified_at)<p class="mt-2 text-xs text-slate-500">{{ $item->verifier?->name }} · {{ $item->verified_at->format('M d, Y g:i A') }}</p>@endif
                        </div>
                    </div>
                    @if ($item->status === 'Pending')
                        <div class="mt-5 grid gap-4 border-t border-slate-100 pt-5 lg:grid-cols-2">
                            <form method="POST" action="{{ route('staff.attendance-discrepancies.review', $item->discrepancy_id) }}" class="space-y-3 rounded-xl border border-green-200 bg-green-50 p-4">
                                @csrf @method('PATCH')<input type="hidden" name="decision" value="Approved">
                                <p class="font-bold text-green-900">Approve & Correct Attendance</p><p class="text-xs text-green-900">Check the attendance log and evidence first. Approval can create or modify a record.</p>
                                <label class="block text-xs font-semibold text-green-900">Approval Reason / Evidence (required)</label><textarea name="review_notes" rows="3" required minlength="10" maxlength="2000" class="w-full rounded-lg border border-green-200 p-3 text-sm" placeholder="Explain the evidence supporting the correction."></textarea>
                                <button class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-bold text-white" onclick="return confirm('Approve and apply the requested attendance correction?')">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('staff.attendance-discrepancies.review', $item->discrepancy_id) }}" class="space-y-3 rounded-xl border border-red-200 bg-red-50 p-4">
                                @csrf @method('PATCH')<input type="hidden" name="decision" value="Rejected">
                                <p class="font-bold text-red-900">Reject Request</p><p class="text-xs text-red-900">No attendance record will be changed.</p>
                                <label class="block text-xs font-semibold text-red-900">Reason for Rejection (required)</label><textarea name="review_notes" rows="3" required minlength="10" maxlength="2000" class="w-full rounded-lg border border-red-200 p-3 text-sm" placeholder="Explain why the request was rejected."></textarea>
                                <button class="rounded-lg bg-red-700 px-5 py-2.5 text-sm font-bold text-white" onclick="return confirm('Reject this discrepancy request?')">Reject</button>
                            </form>
                        </div>
                    @else
                        <div class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-700">
                            <p><strong>Staff Decision:</strong> {{ $item->review_notes }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $item->reviewer?->name }} · {{ $item->reviewed_at?->format('M d, Y g:i A') }}</p>
                            @if ($item->status === 'Approved')<p class="mt-2 text-green-800">Attendance #{{ $item->attendance_id }} · After correction: {{ $item->after_status }} · {{ $item->after_time_in?->format('M d, Y g:i A') }}</p>@endif
                        </div>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">No student discrepancy requests match your filters.</div>
            @endforelse
            <div>{{ $discrepancies->links() }}</div>
        </section>
    </div>
</x-admin-layout>
