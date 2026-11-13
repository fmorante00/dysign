<x-admin-layout>
    <style>
        /* Matches the campus-photo hero and gold/ring details used by Event Management. */
        .dysign-exception-hero {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            background-image:
                linear-gradient(100deg, rgba(16, 16, 100, .97) 0%, rgba(16, 16, 100, .91) 52%, rgba(16, 16, 100, .73) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        .dysign-exception-ring {
            position: absolute;
            display: block;
            border: 2px solid rgba(255, 255, 255, .23);
            border-radius: 50%;
            pointer-events: none;
        }
        .dysign-exception-ring.ring-one { width: 410px; height: 410px; top: -145px; right: -55px; }
        .dysign-exception-ring.ring-two { width: 300px; height: 300px; top: -90px; right: 0; }
        .dysign-exception-ring.ring-three { width: 190px; height: 190px; top: -35px; right: 55px; }
        .dysign-exception-field {
            display: block;
            width: 100%;
            min-height: 45px;
            padding: 10px 13px;
            border: 1px solid #dfe4ed;
            border-radius: 10px;
            background: #fff;
            color: #171c50;
            font-size: 13px;
        }
        .dysign-exception-field:focus {
            outline: none;
            border-color: #101064;
            box-shadow: 0 0 0 3px rgba(16, 16, 100, .10);
        }
        @media (max-width: 640px) {
            .dysign-exception-ring { transform: translateX(160px); }
        }
    </style>

    <div class="w-full min-w-0 space-y-7 pb-8">
        {{-- Page header: same visual treatment as DySign Event Management. --}}
        <section class="dysign-exception-hero rounded-[28px] text-white shadow-sm">
            <div class="pointer-events-none absolute inset-0 z-0" aria-hidden="true">
                <span class="dysign-exception-ring ring-one"></span>
                <span class="dysign-exception-ring ring-two"></span>
                <span class="dysign-exception-ring ring-three"></span>
            </div>
            <div class="absolute inset-x-0 bottom-0 z-20 h-1 bg-[#D4A017]"></div>
            <div class="relative z-10 flex min-h-[210px] flex-col justify-center gap-5 px-7 py-9 sm:px-9 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="min-w-0 max-w-3xl">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="h-[2px] w-8 bg-[#D4A017]"></span>
                        <span class="text-[11px] font-bold uppercase tracking-[.27em] text-[#F0C65B]">Attendance Management / Department Staff</span>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Attendance Exceptions</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">
                        Review personnel-reported scanner issues and record verified manual attendance with a genuine Time In and Time Out.
                    </p>
                </div>
                <div class="inline-flex shrink-0 items-center gap-2 self-start rounded-xl border border-white/25 bg-white/10 px-4 py-3 text-xs font-semibold text-white backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 12 2 2 4-4"/><path d="M12 3a9 9 0 1 0 9 9"/><path d="M12 7v5"/></svg>
                    Department review only
                </div>
            </div>
        </section>

        @if (session('success'))
            <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <strong>Review could not be saved:</strong>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Summary: familiar card proportions, gold section label, restrained typography. --}}
        <section aria-labelledby="exception-summary-heading">
            <div class="mb-4">
                <p class="text-[11px] font-bold uppercase tracking-[.23em] text-[#BD8D18]">Overview</p>
                <h2 id="exception-summary-heading" class="mt-2 text-xl font-bold text-[#101064]">Exception Review Summary</h2>
            </div>
            <div class="grid grid-cols-1 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm sm:grid-cols-3">
                <div class="border-b border-gray-100 px-6 py-6 sm:border-b-0 sm:border-r">
                    <p class="text-[11px] font-semibold uppercase tracking-[.1em] text-slate-400">Pending Reviews</p>
                    <p class="mt-3 text-3xl font-bold text-[#101064]">{{ $pendingCount }}</p>
                    <p class="mt-1 text-xs text-slate-500">Awaiting staff decision</p>
                </div>
                <div class="border-b border-gray-100 px-6 py-6 sm:border-b-0 sm:border-r">
                    <p class="text-[11px] font-semibold uppercase tracking-[.1em] text-slate-400">Matching Requests</p>
                    <p class="mt-3 text-3xl font-bold text-[#101064]">{{ $exceptions->total() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Based on current filters</p>
                </div>
                <div class="px-6 py-6">
                    <p class="text-[11px] font-semibold uppercase tracking-[.1em] text-slate-400">Review Scope</p>
                    <p class="mt-3 text-lg font-bold text-[#101064]">Department events</p>
                    <p class="mt-1 text-xs text-slate-500">Requests outside your department are not shown</p>
                </div>
            </div>
        </section>

        <section aria-labelledby="exception-list-heading" class="space-y-4">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[.23em] text-[#BD8D18]">Verification Queue</p>
                    <h2 id="exception-list-heading" class="mt-2 text-xl font-bold text-[#101064]">Manual Attendance Reviews</h2>
                </div>
                <span class="text-xs font-medium text-slate-500">Approved records require verified Time In + Time Out</span>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                <form method="GET" action="{{ route('staff.attendance-exceptions.index') }}" class="grid items-end gap-4 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
                    <div>
                        <label for="exception-event-filter" class="mb-2 block text-[11px] font-bold uppercase tracking-[.09em] text-slate-500">Event</label>
                        <select id="exception-event-filter" name="event_id" class="dysign-exception-field">
                            <option value="">All Department Events</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->event_id }}" @selected((string) request('event_id') === (string) $event->event_id)>
                                    {{ $event->event_name }} ({{ $event->event_date?->format('M d, Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="exception-status-filter" class="mb-2 block text-[11px] font-bold uppercase tracking-[.09em] text-slate-500">Status</label>
                        <select id="exception-status-filter" name="status" class="dysign-exception-field">
                            <option value="">All Statuses</option>
                            @foreach (['Pending', 'Approved', 'Rejected'] as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" class="inline-flex min-h-[45px] items-center justify-center rounded-xl bg-[#101064] px-6 text-sm font-bold text-white transition hover:bg-[#1B1B85] focus:outline-none focus:ring-4 focus:ring-indigo-100">Apply Filters</button>
                        <a href="{{ route('staff.attendance-exceptions.index') }}" class="py-3 text-sm font-semibold text-slate-500 transition hover:text-[#101064]">Reset</a>
                    </div>
                </form>
            </div>

            @forelse ($exceptions as $exception)
                <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="h-1 w-full bg-[#D4A017]"></div>
                    <div class="space-y-5 p-5 sm:p-7">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-[11px] font-bold uppercase tracking-[.16em] text-[#BD8D18]">Exception #{{ $exception->exception_id }} &middot; {{ $exception->issue_type }}</p>
                                <h3 class="mt-2 text-xl font-bold text-[#101064]">
                                    {{ $exception->student?->last_name }}, {{ $exception->student?->first_name }}
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">Student #{{ $exception->student?->student_number }} &middot; {{ $exception->event?->event_name }}</p>
                            </div>
                            @php
                                $statusClass = match ($exception->status) {
                                    'Approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Rejected' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-amber-50 text-amber-800 border-amber-200',
                                };
                            @endphp
                            <span class="rounded-full border px-3 py-1.5 text-xs font-bold {{ $statusClass }}">{{ $exception->status }}</span>
                        </div>

                        <div class="grid gap-3 rounded-xl bg-[#F7F8FC] p-4 text-sm sm:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Personnel-Observed Time In</p>
                                <p class="mt-1 font-semibold text-[#101064]">{{ $exception->observed_at?->format('M d, Y · g:i A') }} <span class="text-xs font-normal text-slate-500">PH time</span></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Reported By</p>
                                <p class="mt-1 font-semibold text-[#101064]">{{ $exception->reporter?->name ?? 'Unknown Personnel' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Date Submitted</p>
                                <p class="mt-1 font-semibold text-[#101064]">{{ $exception->created_at?->format('M d, Y · g:i A') }}</p>
                            </div>
                        </div>

                        <div class="rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700">
                            <p class="font-bold text-[#101064]">Personnel Explanation</p>
                            <p class="mt-2 whitespace-pre-wrap leading-6">{{ $exception->reason }}</p>
                        </div>

                        @if ($exception->status === 'Pending')
                            <div class="grid gap-4 border-t border-slate-100 pt-5 xl:grid-cols-2">
                                <form method="POST" action="{{ route('staff.attendance-exceptions.review', $exception->exception_id) }}" class="space-y-4 rounded-xl border border-emerald-200 bg-emerald-50/50 p-5" data-confirm data-confirm-title="Approve verified attendance?" data-confirm-message="Only approve if both the Time In and Time Out evidence have been verified." data-confirm-confirm-text="Approve Attendance" data-confirm-variant="warning">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="decision" value="Approved">
                                    <div>
                                        <h4 class="font-bold text-emerald-900">Approve Verified Attendance</h4>
                                        <p class="mt-2 text-xs leading-5 text-emerald-900/80">Verify genuine Time In and Time Out evidence before approving. The final status is calculated automatically, not selected manually.</p>
                                    </div>
                                    <div class="rounded-lg border border-emerald-100 bg-white px-4 py-3 text-xs text-slate-700">
                                        <strong>Reported Time In:</strong> {{ $exception->observed_at?->format('M d, Y g:i A') }} (PH time)
                                        <p class="mt-1 text-slate-500">Time Out must be from event end through one hour afterward, and must have already occurred.</p>
                                    </div>
                                    <div>
                                        <label for="exception-time-out-{{ $exception->exception_id }}" class="mb-2 block text-xs font-semibold text-emerald-900">Verified Time Out (PH time) *</label>
                                        <input id="exception-time-out-{{ $exception->exception_id }}" type="datetime-local" name="verified_time_out" required value="{{ old('verified_time_out') }}" class="dysign-exception-field">
                                    </div>
                                    <div>
                                        <label for="exception-approve-note-{{ $exception->exception_id }}" class="mb-2 block text-xs font-semibold text-emerald-900">Evidence / Review Notes *</label>
                                        <textarea id="exception-approve-note-{{ $exception->exception_id }}" name="review_notes" rows="3" minlength="10" maxlength="2000" required class="dysign-exception-field" placeholder="State who verified the two times and the supporting evidence."></textarea>
                                    </div>
                                    <button type="submit" class="inline-flex min-h-[43px] items-center justify-center rounded-xl bg-emerald-700 px-5 text-sm font-bold text-white transition hover:bg-emerald-800">Approve Attendance</button>
                                </form>

                                <form method="POST" action="{{ route('staff.attendance-exceptions.review', $exception->exception_id) }}" class="space-y-4 rounded-xl border border-red-200 bg-red-50/50 p-5" data-confirm data-confirm-title="Reject attendance exception?" data-confirm-message="The report will be rejected. No manual attendance record will be created." data-confirm-confirm-text="Reject Report" data-confirm-variant="danger">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="decision" value="Rejected">
                                    <div>
                                        <h4 class="font-bold text-red-900">Reject Report</h4>
                                        <p class="mt-2 text-xs leading-5 text-red-900/80">Rejection records the reason and does not create an attendance record.</p>
                                    </div>
                                    <div>
                                        <label for="exception-reject-note-{{ $exception->exception_id }}" class="mb-2 block text-xs font-semibold text-red-900">Reason for Rejection *</label>
                                        <textarea id="exception-reject-note-{{ $exception->exception_id }}" name="review_notes" rows="3" maxlength="2000" required class="dysign-exception-field" placeholder="Explain why the reported attendance could not be verified."></textarea>
                                    </div>
                                    <button type="submit" class="inline-flex min-h-[43px] items-center justify-center rounded-xl bg-red-700 px-5 text-sm font-bold text-white transition hover:bg-red-800">Reject Report</button>
                                </form>
                            </div>
                        @else
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                <p><strong class="text-[#101064]">Staff decision:</strong> {{ $exception->review_notes ?: 'No note provided.' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $exception->reviewer?->name ?? 'Staff reviewer' }} &middot; {{ $exception->reviewed_at?->format('M d, Y g:i A') }}</p>
                                @if ($exception->attendance_id)
                                    <p class="mt-3 font-semibold text-emerald-700">Verified manual attendance: Record #{{ $exception->attendance_id }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="mx-auto max-w-xl px-6 py-12 text-center sm:py-16">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F2F0FF] text-[#101064]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4M16 2v4M3 10h18"/><rect x="3" y="4" width="18" height="18" rx="2"/><path d="m9 16 2 2 4-4"/></svg>
                        </div>
                        @if (request()->filled('event_id') || request()->filled('status'))
                            <h3 class="mt-5 text-lg font-bold text-[#101064]">No matching review requests</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Try resetting your filters. Only requests for your department's events appear here.</p>
                            <a href="{{ route('staff.attendance-exceptions.index') }}" class="mt-5 inline-flex min-h-[43px] items-center rounded-xl bg-[#101064] px-5 text-sm font-semibold text-white hover:bg-[#1B1B85]">Reset Filters</a>
                        @else
                            <h3 class="mt-5 text-lg font-bold text-[#101064]">No attendance exception requests yet</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                This page is for Department Staff to <strong>review reports submitted by Attendance Personnel</strong>.
                                Approval forms will appear here once a report is submitted for an event in your department.
                            </p>
                            <div class="mt-6 rounded-xl border border-[#E8EAF1] bg-[#F8F9FD] p-4 text-left">
                                <p class="text-xs font-bold uppercase tracking-[.09em] text-[#BD8D18]">How to test the workflow</p>
                                <ol class="mt-3 list-decimal space-y-2 pl-5 text-xs leading-5 text-slate-600">
                                    <li>Log in as <strong>Attendance Personnel</strong> assigned to a test event.</li>
                                    <li>Open <strong>Attendance Exceptions</strong> and submit a report for an eligible test student with no attendance record.</li>
                                    <li>Return to this Department Staff page and refresh to review the Pending report.</li>
                                </ol>
                            </div>
                        @endif
                    </div>
                </div>
            @endforelse
            @if ($exceptions->hasPages())
                <div class="rounded-xl border border-gray-200 bg-white px-5 py-3 shadow-sm">
                    {{ $exceptions->links() }}
                </div>
            @endif
        </section>
    </div>
</x-admin-layout>
