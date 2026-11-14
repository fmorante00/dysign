<x-admin-layout>
    {{-- Keep both independently authorized discrepancy workflows. The
         incoming Attendance Personnel review controller supplies $requests,
         $counts and $status; the local Department Staff controller supplies
         $discrepancies, $pendingCount and $events. --}}
    @if (auth()->user()?->role?->role_name === 'Attendance Personnel')
    <div class="min-w-0 space-y-6 pb-10">
        <section class="rounded-[26px] border-b-4 border-[#D4A017] bg-[#101064] px-6 py-8 text-white shadow-sm sm:px-9">
            <p class="text-xs font-semibold uppercase tracking-[0.23em] text-[#E7C75B]">DySign · Attendance Personnel</p>
            <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Attendance Discrepancy Review</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">Review attendance concerns for events assigned to you. Record a decision and verified time without rewriting original RFID scan evidence.</p>
        </section>

        @if(session('success'))
            <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <p class="font-semibold">Unable to complete review:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Requests</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ number_format($counts['all']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pending Review</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ number_format($counts['pending']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Resolved</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format($counts['resolved']) }}</p>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold text-[#101064]">Assigned Event Requests</h2>
                    <p class="mt-1 text-xs text-slate-500">You may review requests only for your assigned events.</p>
                </div>
                <form method="GET" action="{{ route('attendance-discrepancies.staff.index') }}">
                    <label for="status" class="sr-only">Filter by status</label>
                    <select id="status" name="status" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        @foreach(['All', 'Pending', 'Approved', 'Rejected'] as $option)
                            <option value="{{ $option }}" @selected($status === $option)>{{ $option }} Requests</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="space-y-4 p-5 sm:p-6">
                @forelse($requests as $item)
                    @php
                        $badge = match($item->status) {
                            'Approved' => 'bg-emerald-50 text-emerald-800',
                            'Rejected' => 'bg-red-50 text-red-800',
                            default => 'bg-amber-50 text-amber-800',
                        };
                    @endphp
                    <article class="min-w-0 rounded-xl border border-slate-200 p-4 sm:p-5">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-base font-bold text-[#101064]">{{ $item->student?->first_name }} {{ $item->student?->last_name }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $item->student?->student_number }} · {{ $item->student?->program_code }} · Request #{{ $item->discrepancy_request_id }}</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">{{ $item->event?->event_name ?? 'Event unavailable' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $item->request_type }} · Submitted {{ $item->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $badge }}">{{ $item->status }}</span>
                        </div>

                        <div class="mt-4 grid gap-4 rounded-lg bg-slate-50 p-4 text-sm text-slate-700 md:grid-cols-2">
                            <div>
                                <p class="text-xs font-bold uppercase text-slate-500">Student's Explanation</p>
                                <p class="mt-2 whitespace-pre-line leading-6">{{ $item->description }}</p>
                                @if($item->expected_time_in)
                                    <p class="mt-2 text-xs">Student-reported time: {{ $item->expected_time_in->format('M d, Y h:i A') }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase text-slate-500">Original Attendance Snapshot</p>
                                @if($item->attendance_id)
                                    <p class="mt-2">Recorded status: <strong>{{ $item->original_attendance_status ?: '—' }}</strong></p>
                                    <p class="mt-1">Time-in: {{ $item->original_time_in?->format('M d, Y h:i A') ?: '—' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">Record ID: {{ $item->attendance_id }}</p>
                                @else
                                    <p class="mt-2">No original attendance record was linked at submission.</p>
                                @endif
                            </div>
                        </div>

                        @if($item->status === 'Pending')
                            <details class="mt-4 rounded-xl border border-indigo-100 bg-indigo-50/50 p-4">
                                <summary class="cursor-pointer text-sm font-semibold text-[#101064]">Review Request</summary>
                                <form method="POST" action="{{ route('attendance-discrepancies.staff.review', $item->discrepancy_request_id) }}" class="mt-4 space-y-4" data-confirm data-confirm-title="Save attendance discrepancy review?" data-confirm-message="Review the selected decision and evidence. Saving this review may update the discrepancy status." data-confirm-confirm-text="Save Review" data-confirm-variant="warning">
                                    @csrf
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label for="decision-{{ $item->discrepancy_request_id }}" class="mb-2 block text-sm font-semibold text-slate-700">Decision *</label>
                                            <select id="decision-{{ $item->discrepancy_request_id }}" name="decision" required class="w-full rounded-lg border-slate-300 text-sm">
                                                <option value="">Select decision</option>
                                                <option value="Approved">Approve — verified discrepancy</option>
                                                <option value="Rejected">Reject — not verified</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="verified-{{ $item->discrepancy_request_id }}" class="mb-2 block text-sm font-semibold text-slate-700">Verified Time-In (required for approval)</label>
                                            <input id="verified-{{ $item->discrepancy_request_id }}" type="datetime-local" name="verified_time_in" class="w-full rounded-lg border-slate-300 text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="notes-{{ $item->discrepancy_request_id }}" class="mb-2 block text-sm font-semibold text-slate-700">Resolution Notes *</label>
                                        <textarea id="notes-{{ $item->discrepancy_request_id }}" name="resolution_notes" required minlength="10" maxlength="2000" rows="3"
                                                  placeholder="Explain how you verified this request and what evidence was checked..."
                                                  class="w-full rounded-lg border-slate-300 text-sm"></textarea>
                                    </div>
                                    <button type="submit" class="rounded-lg bg-[#101064] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#202080]">Save Review Decision</button>
                                </form>
                            </details>
                        @else
                            <div class="mt-4 border-t border-slate-100 pt-4 text-sm">
                                <p class="font-semibold text-[#101064]">Resolution</p>
                                <p class="mt-1 whitespace-pre-line text-slate-700">{{ $item->resolution_notes }}</p>
                                @if($item->verified_time_in)
                                    <p class="mt-2 text-xs text-slate-500">Verified time: {{ $item->verified_time_in->format('M d, Y h:i A') }}</p>
                                @endif
                                <p class="mt-2 text-xs text-slate-500">Reviewed by {{ $item->reviewer?->name ?? 'Former personnel' }} on {{ $item->reviewed_at?->format('M d, Y h:i A') ?? '—' }}</p>
                            </div>
                        @endif
                    </article>
                @empty
                    <p class="py-12 text-center text-sm text-slate-500">No discrepancy requests found for your assigned events.</p>
                @endforelse
            </div>
            @if($requests->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">{{ $requests->links() }}</div>
            @endif
        </section>
        <p class="text-xs leading-5 text-slate-500">Approving a request records verification, not a new RFID tap. The original scanner attendance table is unchanged; report corrections require a separately authorized integration.</p>
    </div>
    @else
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
                            <form method="POST" action="{{ route('staff.attendance-discrepancies.review', $item->discrepancy_id) }}" class="space-y-3 rounded-xl border border-green-200 bg-green-50 p-4" data-confirm data-confirm-title="Approve attendance correction?" data-confirm-message="Approval can create or modify an attendance record. Check the evidence before proceeding." data-confirm-confirm-text="Approve Correction" data-confirm-variant="warning">
                                @csrf @method('PATCH')<input type="hidden" name="decision" value="Approved">
                                <p class="font-bold text-green-900">Approve & Correct Attendance</p><p class="text-xs text-green-900">Check the attendance log and evidence first. Approval can create or modify a record.</p>
                                <label class="block text-xs font-semibold text-green-900">Approval Reason / Evidence (required)</label><textarea name="review_notes" rows="3" required minlength="10" maxlength="2000" class="w-full rounded-lg border border-green-200 p-3 text-sm" placeholder="Explain the evidence supporting the correction."></textarea>
                                <button class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-bold text-white">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('staff.attendance-discrepancies.review', $item->discrepancy_id) }}" class="space-y-3 rounded-xl border border-red-200 bg-red-50 p-4" data-confirm data-confirm-title="Reject discrepancy request?" data-confirm-message="The request will be rejected and no attendance record will be changed." data-confirm-confirm-text="Reject Request" data-confirm-variant="danger">
                                @csrf @method('PATCH')<input type="hidden" name="decision" value="Rejected">
                                <p class="font-bold text-red-900">Reject Request</p><p class="text-xs text-red-900">No attendance record will be changed.</p>
                                <label class="block text-xs font-semibold text-red-900">Reason for Rejection (required)</label><textarea name="review_notes" rows="3" required minlength="10" maxlength="2000" class="w-full rounded-lg border border-red-200 p-3 text-sm" placeholder="Explain why the request was rejected."></textarea>
                                <button class="rounded-lg bg-red-700 px-5 py-2.5 text-sm font-bold text-white">Reject</button>
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
    @endif
</x-admin-layout>
