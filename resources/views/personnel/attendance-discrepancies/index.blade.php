<x-admin-layout>
    <style>
        .dy-discrepancy-personnel-hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background-image:
                radial-gradient(circle at 94% 15%, transparent 0 92px, rgba(255,255,255,.19) 93px 95px, transparent 96px 146px, rgba(255,255,255,.14) 147px 149px, transparent 150px),
                linear-gradient(100deg, rgba(16,16,100,.97) 0%, rgba(16,16,100,.91) 52%, rgba(16,16,100,.73) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
    </style>

    <div class="w-full min-w-0 space-y-6 pb-8">
        <section class="dy-discrepancy-personnel-hero rounded-[28px] border-b-4 border-[#D4A017] px-7 py-9 text-white shadow-sm sm:px-9">
            <p class="text-xs font-bold uppercase tracking-[.28em] text-[#F2C94C]">Attendance Personnel</p>
            <h1 class="mt-3 text-3xl font-extrabold">Discrepancy Verification</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-indigo-100">Verify student-reported attendance concerns for your assigned events. You record evidence; Department Staff makes the final decision.</p>
            <div class="mt-5 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white/90">
                {{ now('Asia/Manila')->format('l, F d, Y') }} &middot; Assigned Events Only
            </div>
        </section>

        @if(session('success'))
            <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <p class="font-bold">Please check your verification:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-4" aria-label="Discrepancy verification overview">
            @foreach([
                ['label' => 'Total Requests', 'value' => $counts['all'], 'hint' => 'For your assigned events', 'tone' => 'text-[#101064]', 'surface' => 'bg-indigo-50'],
                ['label' => 'Awaiting Verification', 'value' => $counts['awaiting'], 'hint' => 'Action needed from you', 'tone' => 'text-amber-700', 'surface' => 'bg-amber-50'],
                ['label' => 'Verified', 'value' => $counts['verified'], 'hint' => 'Confirmed or not confirmed', 'tone' => 'text-emerald-700', 'surface' => 'bg-emerald-50'],
                ['label' => 'Resolved by Staff', 'value' => $counts['resolved'], 'hint' => 'Approved or rejected', 'tone' => 'text-slate-700', 'surface' => 'bg-slate-100'],
            ] as $metric)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0"><p class="text-sm font-medium text-slate-500">{{ $metric['label'] }}</p><p class="mt-3 text-3xl font-extrabold {{ $metric['tone'] }}">{{ number_format($metric['value']) }}</p></div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $metric['surface'] }} {{ $metric['tone'] }}">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="3"/><path d="m8 12 3 3 5-6"/></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-slate-500">{{ $metric['hint'] }}</p>
                </div>
            @endforeach
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-4">
                <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#BD8D18]">Assigned Event Requests</p>
                <h2 class="mt-1 text-lg font-extrabold text-[#101064]">Filter Verification Records</h2>
                <p class="mt-1 text-xs text-slate-500">Filter results without affecting your pending verifications or history.</p>
            </div>
            <form method="GET" action="{{ route('personnel.attendance-discrepancies.index') }}" class="grid items-end gap-4 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_auto]">
                <div>
                    <label for="personnel-discrepancy-status" class="mb-2 block text-sm font-semibold text-slate-700">Staff Decision</label>
                    <select id="personnel-discrepancy-status" name="status" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                        <option value="">All Decisions</option>
                        @foreach(['Pending', 'Approved', 'Rejected'] as $statusOption)
                            <option value="{{ $statusOption }}" @selected(($filters['status'] ?? '') === $statusOption)>{{ $statusOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="personnel-discrepancy-verification" class="mb-2 block text-sm font-semibold text-slate-700">Personnel Verification</label>
                    <select id="personnel-discrepancy-verification" name="verification_status" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                        <option value="">All Verification Results</option>
                        @foreach(['Unverified', 'Confirmed', 'Not Confirmed'] as $verifyOption)
                            <option value="{{ $verifyOption }}" @selected(($filters['verification_status'] ?? '') === $verifyOption)>{{ $verifyOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="rounded-xl bg-[#101064] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#222281]">Apply</button>
                    <a href="{{ route('personnel.attendance-discrepancies.index') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="personnel-discrepancy-list-title">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#BD8D18]">Verification &amp; Review History</p>
                    <h2 id="personnel-discrepancy-list-title" class="mt-1 text-lg font-extrabold text-[#101064]">Student Discrepancy Requests</h2>
                    <p class="mt-1 text-xs text-slate-500">Requests awaiting your verification appear first. Completed records stay visible.</p>
                </div>
                <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600">{{ $discrepancies->total() }} matching</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($discrepancies as $item)
                    @php
                        $isActionable = $item->status === 'Pending' && $item->verification_status === 'Unverified';
                        $decisionColor = match($item->status) {
                            'Approved' => 'bg-emerald-100 text-emerald-800',
                            'Rejected' => 'bg-red-100 text-red-800',
                            default => 'bg-amber-100 text-amber-800',
                        };
                        $verificationColor = match($item->verification_status) {
                            'Confirmed' => 'bg-emerald-50 text-emerald-800',
                            'Not Confirmed' => 'bg-red-50 text-red-800',
                            default => 'bg-slate-100 text-slate-600',
                        };
                        $initials = strtoupper(substr((string) ($item->student?->first_name ?? 'S'), 0, 1));
                    @endphp
                    <article class="px-5 py-5 sm:px-6" aria-label="Discrepancy request {{ $item->discrepancy_id }}">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="flex min-w-0 items-start gap-3 sm:gap-4">
                                <div class="relative flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-indigo-100 bg-indigo-50 text-sm font-bold text-[#101064]">
                                    <span>{{ $initials }}</span>
                                    @if($item->student?->photo_path)
                                        <img src="{{ asset($item->student->photo_path) }}" alt="Student profile photo" class="absolute inset-0 h-full w-full object-cover" loading="lazy" onerror="this.remove()">
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold uppercase tracking-[.12em] text-[#A9780B]">Request #{{ $item->discrepancy_id }} &middot; {{ $item->request_type }}</p>
                                    <h3 class="mt-1 break-words text-base font-extrabold text-[#101064]">{{ $item->student?->last_name ?? 'Unknown' }}, {{ $item->student?->first_name ?? 'Student' }}</h3>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->student?->student_number ?? 'No student number' }} @if($item->student?->program_code) &middot; {{ $item->student->program_code }} @endif</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-800">{{ $item->event?->event_name ?? 'Event unavailable' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->event?->event_date?->format('M d, Y') ?? 'Date unavailable' }} &middot; Submitted {{ $item->created_at?->format('M d, Y g:i A') ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ $verificationColor }}">{{ $item->verification_status }}</span>
                                <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ $decisionColor }}">{{ $item->status }}</span>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 rounded-xl bg-slate-50 p-4 text-xs leading-5 text-slate-700 sm:grid-cols-2">
                            <div><p class="font-bold text-[#101064]">Student-Requested Correction</p><p class="mt-1">Status: {{ $item->requested_status ?? 'No status change' }}</p><p>Time In: {{ $item->requested_time_in?->format('M d, Y g:i A') ?? 'No time change' }}</p></div>
                            <div><p class="font-bold text-[#101064]">Attendance When Reported</p><p class="mt-1">Status: {{ $item->before_status ?? 'No attendance record' }}</p><p>Time In: {{ $item->before_time_in?->format('M d, Y g:i A') ?? 'None' }}</p><p>Time Out: {{ $item->before_time_out?->format('M d, Y g:i A') ?? 'None' }}</p></div>
                        </div>

                        <details class="mt-3 rounded-xl border border-slate-200 bg-white px-4 py-3">
                            <summary class="cursor-pointer text-xs font-bold text-[#101064]">View student explanation and request details</summary>
                            <p class="mt-3 whitespace-pre-wrap text-sm leading-6 text-slate-700">{{ $item->reason }}</p>
@include('components.attendance-discrepancy-evidence', ['item' => $item, 'routeName' => 'personnel.attendance-discrepancies.evidence'])
                        </details>

                        @if($isActionable)
                            <form method="POST" action="{{ route('personnel.attendance-discrepancies.verify', $item->discrepancy_id) }}"
                                  class="mt-4 grid gap-4 rounded-xl border border-indigo-100 bg-indigo-50/40 p-4 md:grid-cols-2"
                                  data-dy-verify-form
                                  data-confirm
                                  data-confirm-title="Save discrepancy verification?"
                                  data-confirm-message="Record your verification decision and independently checked evidence. Department Staff will make the final decision."
                                  data-confirm-confirm-text="Save Verification"
                                  data-confirm-variant="warning">
                                @csrf
                                @method('PATCH')
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-extrabold text-[#101064]">Personnel Verification</h4>
                                    <p class="mt-1 text-xs text-slate-600">Verifying does not modify official attendance records.</p>
                                </div>
                                <div>
                                    <label for="verify-status-{{ $item->discrepancy_id }}" class="mb-2 block text-sm font-semibold text-slate-700">Verification Result *</label>
                                    <select id="verify-status-{{ $item->discrepancy_id }}" name="verification_status" required data-dy-verify-select class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm">
                                        <option value="">Choose result</option>
                                        <option value="Confirmed">Confirmed</option>
                                        <option value="Not Confirmed">Not Confirmed</option>
                                    </select>
                                </div>
                                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs leading-5 text-amber-900">
                                    <strong>Automatic timestamps:</strong> There are no manual Time In / Time Out fields. The server uses previously captured attendance or live Manual Exception timestamps, if available. A photo or event schedule cannot supply past scan times.
                                </div>
                                <div class="md:col-span-2 rounded-xl border border-indigo-100 bg-white px-4 py-3 text-xs leading-5 text-slate-700">
                                    @if($item->request_type === 'Missing Attendance')
                                        <strong>Missing Attendance:</strong> Confirm only when you have independently verified the student's participation. Without captured check-in/out timestamps, staff approval records <strong>Incomplete</strong>, never Present or Late. Explain the evidence in your notes.
                                    @elseif($item->request_type === 'Incorrect Time')
                                        <strong>Incorrect Time:</strong> Confirmation needs an independently captured timestamp pair, such as a live Manual Exception record. If none exists, use Not Confirmed and explain why.
                                    @else
                                        <strong>Incorrect Status:</strong> The system automatically checks the recorded Time In/Out and determines the correct Present/Late status. Confirm only if the supporting evidence agrees.
                                    @endif
                                </div>
                                <div class="md:col-span-2">
                                    <label for="verify-notes-{{ $item->discrepancy_id }}" class="mb-2 block text-sm font-semibold text-slate-700">Verification Evidence / Notes *</label>
                                    <textarea id="verify-notes-{{ $item->discrepancy_id }}" name="verification_notes" required minlength="10" maxlength="2000" rows="3" class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm" placeholder="Describe how you verified the RFID logs, check-in and check-out evidence, or witnessed attendance."></textarea>
                                </div>
                                <div class="md:col-span-2 flex justify-end">
                                    <button type="submit" class="rounded-xl bg-[#101064] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#222281]">Save Verification</button>
                                </div>
                            </form>
                        @else
                            <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-4">
                                <p class="text-xs font-bold uppercase tracking-[.12em] text-[#101064]">Personnel Verification Record</p>
                                @if($item->verification_status !== 'Unverified')
                                    <p class="mt-2 text-sm font-semibold text-slate-800">{{ $item->verification_status }} by {{ $item->verifier?->name ?? 'Former personnel' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->verified_at?->format('M d, Y g:i A') ?? 'Date unavailable' }}</p>
                                    <p class="mt-2 whitespace-pre-wrap text-sm text-slate-700">{{ $item->verification_notes }}</p>
                                    @if($item->verified_time_in || $item->verified_time_out)
                                        <p class="mt-2 text-xs font-medium text-slate-700">Verified Time In: {{ $item->verified_time_in?->format('M d, Y g:i A') ?? 'None' }} &middot; Verified Time Out: {{ $item->verified_time_out?->format('M d, Y g:i A') ?? 'None' }}</p>
                                    @endif
                                @else
                                    <p class="mt-2 text-sm text-slate-500">No verification recorded before staff review.</p>
                                @endif
                                @if($item->status !== 'Pending')
                                    <details class="mt-3 border-t border-slate-200 pt-3">
                                        <summary class="cursor-pointer text-xs font-bold text-[#101064]">View Department Staff decision</summary>
                                        <p class="mt-2 text-xs text-slate-600">{{ $item->status }} by {{ $item->reviewer?->name ?? 'Department Staff' }} &middot; {{ $item->reviewed_at?->format('M d, Y g:i A') ?? 'Date unavailable' }}</p>
                                        <p class="mt-2 whitespace-pre-wrap text-sm text-slate-700">{{ $item->review_notes ?: 'No review notes available.' }}</p>
                                        @if($item->status === 'Approved')
                                            <p class="mt-2 text-xs text-emerald-700">After correction: {{ $item->after_status ?? 'Unknown' }} &middot; Time In: {{ $item->after_time_in?->format('M d, Y g:i A') ?? 'None' }} &middot; Time Out: {{ $item->after_time_out?->format('M d, Y g:i A') ?? 'None' }}</p>
                                        @endif
                                    </details>
                                @endif
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto h-1 w-12 bg-[#D4A017]"></div>
                        <h3 class="mt-5 text-base font-bold text-[#101064]">No discrepancy requests found</h3>
                        <p class="mt-2 text-sm text-slate-500">There are no requests matching your filters for events assigned to you.</p>
                    </div>
                @endforelse
            </div>

            @if($discrepancies->hasPages())
                <div class="border-t border-slate-100 px-5 py-4 sm:px-6">{{ $discrepancies->links() }}</div>
            @endif
        </section>
    </div>

</x-admin-layout>