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
    @php
        $pageRecords = $discrepancies->getCollection();
        $pendingOnPage = $pageRecords->where('status', 'Pending')->count();
        $verifiedOnPage = $pageRecords->filter(fn ($entry) => $entry->status === 'Pending' && $entry->verification_status === 'Confirmed')->count();
        $studentPlaceholder = 'Student';
    @endphp
    <style>
        .dy-disc-staff-hero {
            background-image:
                radial-gradient(circle at 96% 12%, transparent 0 90px, rgba(255,255,255,.16) 91px 93px, transparent 94px 142px, rgba(255,255,255,.12) 143px 145px, transparent 146px),
                linear-gradient(100deg,rgba(16,16,100,.97),rgba(16,16,100,.90) 52%,rgba(16,16,100,.71)),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        .dy-disc-staff-details summary { list-style: none; }
        .dy-disc-staff-details summary::-webkit-details-marker { display:none; }
        .dy-disc-staff-details[open] .dy-disc-expand-icon { transform: rotate(180deg); }
    </style>

    <div class="w-full min-w-0 space-y-6 pb-9">
        <section class="dy-disc-staff-hero relative isolate overflow-hidden rounded-[28px] border-b-4 border-[#D4A017] px-7 py-9 text-white shadow-sm sm:px-9" aria-labelledby="staff-disc-heading">
            <p class="text-xs font-bold uppercase tracking-[.24em] text-[#F2C94C]">DySign &middot; Department Staff</p>
            <h1 id="staff-disc-heading" class="mt-3 text-3xl font-extrabold tracking-tight">Attendance Discrepancy Reviews</h1>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-indigo-100">Review student-reported attendance issues in your department. Personnel verification supports your final decision; approved corrections preserve before and after records.</p>
            <div class="mt-5 inline-flex max-w-full items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white/90">
                <span class="h-2 w-2 rounded-full bg-[#F2C94C]"></span>
                Department Events Only
            </div>
        </section>

        @if(session('success'))
            <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <p class="font-bold">Please review the following:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-4" aria-label="Discrepancy review overview">
            @foreach([
                ['label' => 'Pending Reviews', 'value' => $pendingCount, 'desc' => 'Awaiting a staff decision', 'tone' => 'amber'],
                ['label' => 'Visible Requests', 'value' => $discrepancies->count(), 'desc' => 'Shown on this page', 'tone' => 'indigo'],
                ['label' => 'Verified & Pending', 'value' => $verifiedOnPage, 'desc' => 'Ready for evidence review on this page', 'tone' => 'emerald'],
                ['label' => 'Department Events', 'value' => $events->count(), 'desc' => 'Events in your department', 'tone' => 'slate'],
            ] as $metric)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500">{{ $metric['label'] }}</p>
                            <p class="mt-3 text-3xl font-extrabold text-[#101064]">{{ number_format($metric['value']) }}</p>
                        </div>
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ match($metric['tone']) {'amber' => 'bg-amber-50 text-amber-700', 'emerald' => 'bg-emerald-50 text-emerald-700', 'slate' => 'bg-slate-100 text-slate-700', default => 'bg-indigo-50 text-[#101064]'} }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="3"/><path d="m8 12 3 3 5-6"/></svg>
                        </span>
                    </div>
                    <p class="mt-4 text-xs text-slate-500">{{ $metric['desc'] }}</p>
                </div>
            @endforeach
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-label="Filter review records">
            <div class="mb-5">
                <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#A9780B]">Department Requests</p>
                <h2 class="mt-1 text-lg font-extrabold text-[#101064]">Filter Discrepancy Reviews</h2>
                <p class="mt-1 text-xs text-slate-500">Filter by event and decision without changing request records.</p>
            </div>
            <form method="GET" action="{{ route('staff.attendance-discrepancies.index') }}" class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] lg:items-end">
                <div>
                    <label for="staff-disc-event" class="mb-2 block text-xs font-bold text-slate-700">Event</label>
                    <select id="staff-disc-event" name="event_id" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        <option value="">All Department Events</option>
                        @foreach($events as $event)
                            <option value="{{ $event->event_id }}" @selected((string)request('event_id') === (string)$event->event_id)>{{ $event->event_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="staff-disc-status" class="mb-2 block text-xs font-bold text-slate-700">Staff Decision</label>
                    <select id="staff-disc-status" name="status" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        <option value="">All Decisions</option>
                        @foreach(['Pending','Approved','Rejected'] as $decision)
                            <option value="{{ $decision }}" @selected(request('status') === $decision)>{{ $decision }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="min-h-11 rounded-xl bg-[#101064] px-5 py-3 text-sm font-bold text-white hover:bg-[#222280]">Apply</button>
                    <a href="{{ route('staff.attendance-discrepancies.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-[#101064] hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="staff-disc-records">
            <header class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#A9780B]">Verification &amp; Decision History</p>
                    <h2 id="staff-disc-records" class="mt-1 text-lg font-extrabold text-[#101064]">Student Discrepancy Requests</h2>
                    <p class="mt-1 text-xs text-slate-500">Pending reviews appear first. Resolved cases remain available for audit.</p>
                </div>
                <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600">{{ $discrepancies->total() }} matching</span>
            </header>

            <div class="space-y-4 p-4 sm:p-6">
                @forelse($discrepancies as $item)
                    @php
                        $isPending = $item->status === 'Pending';
                        $confirmed = $item->verification_status === 'Confirmed';
                        $hasVerifiedTimes = $item->verified_time_in && $item->verified_time_out;
                        $canSubmitApproval = $isPending && $confirmed && ($item->request_type === 'Missing Attendance' || $hasVerifiedTimes);
                        $willBeIncomplete = $canSubmitApproval && $item->request_type === 'Missing Attendance' && !$hasVerifiedTimes;
                        $studentName = trim(($item->student?->first_name ?? '') . ' ' . ($item->student?->last_name ?? '')) ?: 'Unknown Student';
                        $studentInitial = strtoupper(substr($item->student?->first_name ?? 'S', 0, 1));
                        $badgeClass = match($item->status) { 'Approved' => 'bg-emerald-50 text-emerald-800 ring-emerald-200', 'Rejected' => 'bg-rose-50 text-rose-800 ring-rose-200', default => 'bg-amber-50 text-amber-800 ring-amber-200' };
                    @endphp
                    <article class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white {{ $isPending ? 'shadow-sm' : 'bg-slate-50/40' }}">
                        <div class="flex flex-wrap items-start justify-between gap-4 p-5 sm:p-6">
                            <div class="flex min-w-0 items-start gap-3 sm:gap-4">
                                <div class="relative flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full border border-indigo-100 bg-indigo-50 font-bold text-[#101064]">
                                    <span>{{ $studentInitial }}</span>
                                    @if($item->student?->photo_path)
                                        <img src="{{ asset($item->student->photo_path) }}" alt="{{ $studentName }} profile photo" class="absolute inset-0 h-full w-full object-cover" loading="lazy" onerror="this.style.display='none'">
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold uppercase tracking-[.15em] text-[#A9780B]">Request #{{ $item->discrepancy_id }} &middot; {{ $item->request_type }}</p>
                                    <h3 class="mt-1 break-words text-base font-extrabold text-[#101064]">{{ $studentName }}</h3>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->student?->student_number ?? 'No student number' }} &middot; {{ $item->event?->event_name ?? 'Unavailable Event' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">Event {{ $item->event?->event_date?->format('M d, Y') ?? 'date unavailable' }} &middot; Submitted {{ $item->created_at?->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                            <div class="flex shrink-0 flex-wrap items-center gap-2">
                                <span class="rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-bold text-[#101064]">Personnel: {{ $item->verification_status }}</span>
                                <span class="rounded-full px-3 py-1.5 text-xs font-bold ring-1 {{ $badgeClass }}">{{ $item->status }}</span>
                            </div>
                        </div>

                        @if($isPending)
                            <div class="grid gap-4 border-t border-slate-100 px-5 py-5 lg:grid-cols-2 sm:px-6">
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Student Request</p>
                                    <p class="mt-2 whitespace-pre-wrap text-sm leading-6 text-slate-700">{{ $item->reason }}</p>
@include('components.attendance-discrepancy-evidence', ['item' => $item, 'routeName' => 'staff.attendance-discrepancies.evidence'])
                                    <p class="mt-3 text-xs text-slate-600">Original: <strong>{{ $item->before_status ?? 'No record' }}</strong> &middot; In {{ $item->before_time_in?->format('M d, Y g:i A') ?? 'None' }} &middot; Out {{ $item->before_time_out?->format('M d, Y g:i A') ?? 'None' }}</p>
                                    <p class="mt-2 text-xs text-slate-600">Requested: <strong>{{ $item->requested_status ?? 'No status change' }}</strong> &middot; {{ $item->requested_time_in?->format('M d, Y g:i A') ?? 'No time change' }}</p>
                                </div>
                                <div class="rounded-xl border border-indigo-100 bg-indigo-50/40 p-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-[#101064]">Attendance Personnel Verification</p>
                                    <p class="mt-2 text-sm font-bold text-[#101064]">{{ $item->verification_status }}</p>
                                    <p class="mt-2 whitespace-pre-wrap text-sm leading-6 text-slate-700">{{ $item->verification_notes ?: 'Awaiting independent evidence verification.' }}</p>
                                    <p class="mt-2 text-xs text-slate-600">Verified In: {{ $item->verified_time_in?->format('M d, Y g:i A') ?? 'Not provided' }}</p>
                                    <p class="mt-1 text-xs text-slate-600">Verified Out: {{ $item->verified_time_out?->format('M d, Y g:i A') ?? 'Not provided' }}</p>
                                    @if($item->verified_at)
                                        <p class="mt-3 text-xs text-slate-500">{{ $item->verifier?->name ?? 'Personnel' }} &middot; {{ $item->verified_at->format('M d, Y g:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                            @if(!$canSubmitApproval)
                                <div class="mx-5 mb-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs leading-5 text-amber-900 sm:mx-6">Approval requires assigned Attendance Personnel confirmation. Time corrections and Present/Late statuses also need independently captured Time In and Time Out. You may reject with a documented reason.</div>
                            @endif
                            @if($willBeIncomplete)
                                <div class="mx-5 mb-4 rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-xs font-semibold leading-5 text-amber-900 sm:mx-6">
                                    No system-captured event Time In/Out exists. Approving this verified Missing Attendance claim creates an Incomplete attendance record with no invented timestamps. It does NOT grant Present or Late.
                                </div>
                            @endif
                            <div class="grid gap-4 border-t border-slate-100 px-5 py-5 lg:grid-cols-2 sm:px-6">
                                <form method="POST" action="{{ route('staff.attendance-discrepancies.review', $item->discrepancy_id) }}" class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-4" data-confirm data-confirm-title="Approve attendance correction?" data-confirm-message="{{ $willBeIncomplete ? 'No historical scan times exist. Approval will record Incomplete attendance without Time In/Out, NOT Present or Late. Proceed only if participation is independently confirmed.' : 'This may create or modify attendance using independently captured times. Confirm the verification evidence before approving.' }}" data-confirm-confirm-text="Approve Correction" data-confirm-variant="warning">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="decision" value="Approved">
                                    <h4 class="text-sm font-bold text-emerald-900">Approve &amp; Correct Attendance</h4>
                                    <p class="mt-1 text-xs leading-5 text-emerald-800">Approval changes attendance only when all server-side checks pass.</p>
                                    <label for="approve-note-{{ $item->discrepancy_id }}" class="mt-3 block text-xs font-bold text-emerald-900">Evidence / Approval Reason</label>
                                    <textarea id="approve-note-{{ $item->discrepancy_id }}" name="review_notes" rows="3" required minlength="10" maxlength="2000" placeholder="Describe the records supporting this correction." class="mt-2 w-full rounded-xl border border-emerald-200 bg-white p-3 text-sm"></textarea>
                                    <button type="submit" @disabled(!$canSubmitApproval) class="mt-3 rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-800 disabled:cursor-not-allowed disabled:opacity-40">Approve Correction</button>
                                </form>
                                <form method="POST" action="{{ route('staff.attendance-discrepancies.review', $item->discrepancy_id) }}" class="rounded-xl border border-rose-200 bg-rose-50/60 p-4" data-confirm data-confirm-title="Reject discrepancy request?" data-confirm-message="Reject this student request without modifying the official attendance record?" data-confirm-confirm-text="Reject Request" data-confirm-variant="danger">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="decision" value="Rejected">
                                    <h4 class="text-sm font-bold text-rose-900">Reject Request</h4>
                                    <p class="mt-1 text-xs leading-5 text-rose-800">Attendance stays unchanged when you reject.</p>
                                    <label for="reject-note-{{ $item->discrepancy_id }}" class="mt-3 block text-xs font-bold text-rose-900">Rejection Reason</label>
                                    <textarea id="reject-note-{{ $item->discrepancy_id }}" name="review_notes" rows="3" required minlength="10" maxlength="2000" placeholder="Explain the decision clearly to the student." class="mt-2 w-full rounded-xl border border-rose-200 bg-white p-3 text-sm"></textarea>
                                    <button type="submit" class="mt-3 rounded-xl bg-rose-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-rose-800" style="display:inline-block;margin-top:12px;padding:10px 20px;background-color:#b91c1c;color:#ffffff;border-radius:12px;font-weight:700;">Reject Request</button>
                                </form>
                            </div>
                        @else
                            <details class="dy-disc-staff-details border-t border-slate-100" >
                                <summary class="flex cursor-pointer items-center justify-between gap-2 px-5 py-4 text-sm font-semibold text-[#101064] hover:bg-slate-50 sm:px-6">
                                    <span>View decision, verification and attendance audit</span>
                                    <svg class="dy-disc-expand-icon h-4 w-4 shrink-0 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                                </summary>
                                <div class="grid gap-4 border-t border-slate-100 px-5 py-5 lg:grid-cols-2 sm:px-6">
                                    <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-700">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Student Request</p>
                                        <p class="mt-2 whitespace-pre-wrap">{{ $item->reason }}</p>
@include('components.attendance-discrepancy-evidence', ['item' => $item, 'routeName' => 'staff.attendance-discrepancies.evidence'])
                                        <p class="mt-2 text-xs">Requested: {{ $item->requested_status ?? 'No status change' }} &middot; {{ $item->requested_time_in?->format('M d, Y g:i A') ?? 'No time change' }}</p>
                                    </div>
                                    <div class="rounded-xl bg-indigo-50/40 p-4 text-sm text-slate-700">
                                        <p class="text-xs font-bold uppercase tracking-wider text-[#101064]">Verification &amp; Staff Decision</p>
                                        <p class="mt-2">Personnel: {{ $item->verification_status }} &middot; {{ $item->verifier?->name ?? 'Not verified' }}</p>
                                        <p class="mt-1 whitespace-pre-wrap">{{ $item->verification_notes ?: 'No verification notes provided.' }}</p>
                                        <p class="mt-2">Verified In: {{ $item->verified_time_in?->format('M d, Y g:i A') ?? 'None' }}</p>
                                        <p>Verified Out: {{ $item->verified_time_out?->format('M d, Y g:i A') ?? 'None' }}</p>
                                        <p class="mt-3 font-semibold">Staff: {{ $item->reviewer?->name ?? 'No reviewer recorded' }}</p>
                                        <p class="mt-1 whitespace-pre-wrap">{{ $item->review_notes ?: 'No decision notes.' }}</p>
                                        <p class="mt-2 text-xs text-slate-500">{{ $item->reviewed_at?->format('M d, Y g:i A') }}</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-700 lg:col-span-2">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Attendance Audit Snapshot</p>
                                        <div class="mt-3 grid gap-4 md:grid-cols-2">
                                            <div><p class="font-bold text-[#101064]">Before</p><p class="mt-1">Status: {{ $item->before_status ?? 'No record' }}</p><p>Time In: {{ $item->before_time_in?->format('M d, Y g:i A') ?? 'None' }}</p><p>Time Out: {{ $item->before_time_out?->format('M d, Y g:i A') ?? 'None' }}</p></div>
                                            <div><p class="font-bold text-[#101064]">After</p><p class="mt-1">Status: {{ $item->after_status ?? 'No changes' }}</p><p>Time In: {{ $item->after_time_in?->format('M d, Y g:i A') ?? 'None' }}</p><p>Time Out: {{ $item->after_time_out?->format('M d, Y g:i A') ?? 'None' }}</p></div>
                                        </div>
                                        @if($item->status === 'Approved' && $item->attendance_id)
                                            <p class="mt-3 text-xs font-semibold text-emerald-800">Linked attendance record #{{ $item->attendance_id }}</p>
                                        @endif
                                    </div>
                                </div>
                            </details>
                        @endif
                    </article>
                @empty
                    <div class="flex flex-col items-center justify-center px-5 py-14 text-center">
                        <div class="mb-4 h-1 w-12 bg-[#D4A017]"></div>
                        <h3 class="font-bold text-[#101064]">No discrepancy requests found</h3>
                        <p class="mt-2 text-sm text-slate-500">No student discrepancy requests match the selected event and status filters.</p>
                    </div>
                @endforelse
            </div>
            @if($discrepancies->hasPages())
                <div class="border-t border-slate-100 px-5 py-4 sm:px-6">{{ $discrepancies->links() }}</div>
            @endif
        </section>
    </div>
    @endif
</x-admin-layout>
