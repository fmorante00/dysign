<x-admin-layout>
    @php
        $currentStudent = auth()->user()?->student;
        $studentName = trim(collect([
            $currentStudent?->first_name,
            $currentStudent?->last_name
        ])->filter()->implode(' ')) ?: 'Student';
        $studentInitial = strtoupper(substr($currentStudent?->first_name ?: 'S', 0, 1));
    @endphp

    <style>
        .dy-student-discrepancy-hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background-image:
                radial-gradient(circle at 95% 14%, transparent 0 90px, rgba(255,255,255,.18) 91px 93px, transparent 94px 145px, rgba(255,255,255,.12) 146px 148px, transparent 149px),
                linear-gradient(102deg, rgba(16,16,100,.97) 0%, rgba(16,16,100,.91) 54%, rgba(16,16,100,.70) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        .dy-student-discrepancy-details summary { list-style: none; }
        .dy-student-discrepancy-details summary::-webkit-details-marker { display: none; }

        /* Student discrepancy history styles are intentionally scoped. */
        .dy-disc-filter { display:grid; gap:12px; grid-template-columns:repeat(12,minmax(0,1fr)); }
        .dy-disc-filter > div { grid-column:span 12; min-width:0; }
        .dy-disc-filter label { display:block; margin-bottom:6px; font-size:12px; font-weight:700; color:#334155; }
        .dy-disc-field { width:100%; min-height:44px; padding:10px 12px; border:1px solid #cbd5e1; border-radius:12px; background:white; color:#101064; font-size:13px; }
        .dy-disc-field:focus { outline:2px solid #D4A017; outline-offset:1px; }
        .dy-disc-primary { display:inline-flex; justify-content:center; align-items:center; min-height:44px; padding:10px 18px; border:0; border-radius:12px; background:#101064; color:white; font-size:13px; font-weight:800; }
        .dy-disc-secondary { display:inline-flex; justify-content:center; align-items:center; min-height:44px; padding:10px 18px; border:1px solid #cbd5e1; border-radius:12px; background:white; color:#101064; font-size:13px; font-weight:800; }
        .dy-disc-card { border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; background:white; box-shadow:0 3px 9px rgba(15,23,42,.04); }
        .dy-disc-card + .dy-disc-card { margin-top:14px; }
        .dy-disc-pill { display:inline-flex; align-items:center; border-radius:999px; padding:5px 10px; font-size:11px; font-weight:800; }
        .dy-disc-pill--ok { background:#dcfce7; color:#166534; }
        .dy-disc-pill--no { background:#ffe4e6; color:#9f1239; }
        .dy-disc-pill--wait { background:#fef3c7; color:#92400e; }
        .dy-disc-detail > summary { list-style:none; cursor:pointer; padding:15px 20px; border-top:1px solid #e2e8f0; color:#101064; font-size:13px; font-weight:800; }
        .dy-disc-detail > summary::-webkit-details-marker { display:none; }
        .dy-disc-detail > summary::after { content:'+'; float:right; color:#A9780B; font-size:20px; line-height:1; }
        .dy-disc-detail[open] > summary::after { content:'−'; }
        .dy-disc-details-grid { display:grid; gap:13px; grid-template-columns:repeat(3,minmax(0,1fr)); padding:16px 20px 20px; background:#f8fafc; }
        .dy-disc-details-box { min-width:0; border:1px solid #e2e8f0; border-radius:14px; background:white; padding:15px; overflow-wrap:anywhere; }
        .dy-disc-details-box h4 { margin:0 0 10px; font-size:13px; font-weight:800; color:#101064; }
        .dy-disc-details-box p { margin:6px 0 0; font-size:12px; line-height:1.65; color:#475569; }
        .dy-disc-progress { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); background:#f8fafc; border-top:1px solid #e2e8f0; }
        .dy-disc-progress > div { padding:12px 15px; min-width:0; }
        .dy-disc-progress > div+div { border-left:1px solid #e2e8f0; }
        @media (min-width:768px) {
            .dy-disc-filter .dy-disc-search { grid-column:span 6; }
            .dy-disc-filter .dy-disc-type { grid-column:span 3; }
            .dy-disc-filter .dy-disc-verification { grid-column:span 3; }
            .dy-disc-filter .dy-disc-status { grid-column:span 3; }
            .dy-disc-filter .dy-disc-sort { grid-column:span 3; }
            .dy-disc-filter .dy-disc-perpage { grid-column:span 3; }
            .dy-disc-filter .dy-disc-actions { grid-column:span 3; }
        }
        @media (max-width:767px) {
            .dy-disc-details-grid { grid-template-columns:1fr; padding:14px; }
            .dy-disc-progress { grid-template-columns:1fr; }
            .dy-disc-progress > div+div { border-left:0; border-top:1px solid #e2e8f0; }
        }
    </style>

    <div class="w-full min-w-0 space-y-6 pb-8">
        <section class="dy-student-discrepancy-hero rounded-[28px] border-b-4 border-[#D4A017] px-7 py-9 text-white shadow-sm sm:px-9">
            <div class="relative flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[.24em] text-[#F2C94C]">DySign &middot; Student Account</p>
                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight">My Attendance Discrepancies</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-indigo-100">Report missing or incorrect attendance after a completed event. Attendance Personnel checks the evidence; Department Staff makes the final decision.</p>
                    <span class="mt-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white/90">Completed events only &middot; Corrections require approval</span>
                </div>
                <div class="flex max-w-full shrink-0 items-center gap-3 rounded-2xl border border-white/20 bg-white/10 px-4 py-3">
                    <div class="relative flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-white/20 font-bold text-white">
                        {{ $studentInitial }}
                        @if ($currentStudent?->photo_path)
                            <img src="{{ asset($currentStudent->photo_path) }}" alt="Student profile photo" class="absolute inset-0 h-full w-full object-cover" onerror="this.style.display='none'">
                        @endif
                    </div>
                    <div class="min-w-0"><p class="max-w-[190px] truncate text-sm font-bold">{{ $studentName }}</p><p class="text-xs text-indigo-100">{{ $currentStudent?->student_number ?? 'Student Account' }}</p></div>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <p class="font-bold">Please check your request:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-4" aria-label="Discrepancy overview">
            @foreach ([
                ['label' => 'Total Requests', 'value' => $counts['all'], 'desc' => 'All requests on your account', 'color' => 'text-[#101064]', 'bg' => 'bg-indigo-50'],
                ['label' => 'Pending', 'value' => $counts['pending'], 'desc' => 'Waiting for a final decision', 'color' => 'text-amber-700', 'bg' => 'bg-amber-50'],
                ['label' => 'Personnel Verified', 'value' => $counts['verified'], 'desc' => 'Confirmed or Not Confirmed', 'color' => 'text-emerald-700', 'bg' => 'bg-emerald-50'],
                ['label' => 'Resolved', 'value' => $counts['resolved'], 'desc' => 'Approved or Rejected', 'color' => 'text-[#101064]', 'bg' => 'bg-slate-100'],
            ] as $metric)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0"><p class="text-sm font-medium text-slate-500">{{ $metric['label'] }}</p><p class="mt-3 text-3xl font-extrabold text-[#101064]">{{ number_format($metric['value']) }}</p></div>
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $metric['bg'] }} {{ $metric['color'] }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4m8-4v4M4 10h16m-12 5 2 2 5-5"/></svg>
                        </span>
                    </div>
                    <p class="mt-4 text-xs text-slate-500">{{ $metric['desc'] }}</p>
                </div>
            @endforeach
        </section>

        <section id="dyDiscSubmit" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="discrepancy-submit-heading">
            <div class="border-b border-slate-100 px-6 py-5 sm:px-7">
                <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#A9780B]">Attendance Concern</p>
                <h2 id="discrepancy-submit-heading" class="mt-1 text-lg font-extrabold text-[#101064]">Submit Attendance Discrepancy</h2>
                <p class="mt-1 text-xs leading-5 text-slate-500">Choose a completed event and describe the attendance you believe needs correction. Submission does not change your official record.</p>
            </div>
            <form method="POST" action="{{ route('student.attendance-discrepancies.store') }}" enctype="multipart/form-data" id="dyStudentDiscrepancyForm" class="grid gap-5 p-6 md:grid-cols-2 sm:p-7" data-confirm data-confirm-title="Submit attendance discrepancy?" data-confirm-message="Submit your attendance concern and supporting explanation for verification and Department Staff review. No attendance will change yet." data-confirm-confirm-text="Submit Request" data-confirm-variant="default">
                @csrf
                <div class="md:col-span-2">
                    <label for="event_id" class="mb-2 block text-sm font-semibold text-slate-700">Completed Event <span class="text-rose-600">*</span></label>
                    <select name="event_id" id="event_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        <option value="">Select a completed event</option>
                        @foreach ($events as $event)
                            @php($attendance = $attendanceByEvent->get($event->event_id))
                            <option value="{{ $event->event_id }}" data-has-attendance="{{ $attendance ? '1' : '0' }}" @selected(old('event_id') == $event->event_id)>{{ $event->event_name }} &middot; {{ $event->event_date?->format('M d, Y') }} &middot; {{ $attendance ? $attendance->status . ' recorded' : 'No attendance recorded' }}</option>
                        @endforeach
                    </select>
                    <p id="dyDiscrepancyEventHint" class="mt-1.5 text-xs text-slate-500">Your eligibility and existing attendance are checked again when you submit.</p>
                </div>
                <div>
                    <label for="request_type" class="mb-2 block text-sm font-semibold text-slate-700">Issue Type <span class="text-rose-600">*</span></label>
                    <select name="request_type" id="request_type" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        <option value="">Select an issue</option>
                        @foreach (['Missing Attendance', 'Incorrect Status', 'Incorrect Time'] as $type)
                            <option value="{{ $type }}" @selected(old('request_type') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="dyRequestedStatusBlock">
                    <label for="requested_status" class="mb-2 block text-sm font-semibold text-slate-700">Requested Attendance Status</label>
                    <select name="requested_status" id="requested_status" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        <option value="">Choose a status when applicable</option>
                        @foreach (['Present', 'Late'] as $status)
                            <option value="{{ $status }}" @selected(old('requested_status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-slate-500">Only needed for Incorrect Status. Personnel independently verifies the evidence.</p>
                </div>
                <div class="md:col-span-2">
                    <label for="reason" class="mb-2 block text-sm font-semibold text-slate-700">Explanation / Evidence <span class="text-rose-600">*</span></label>
                    <textarea name="reason" id="reason" rows="4" required minlength="10" maxlength="2000" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]" placeholder="Describe what happened, your attendance concern, and any evidence staff can check.">{{ old('reason') }}</textarea>
                    <p class="mt-1.5 text-xs text-slate-500">At least 10 characters. Evidence is reviewed by Attendance Personnel and Department Staff.</p>
                </div>
                <div class="md:col-span-2">
    <label for="discrepancy-evidence" class="mb-2 block text-sm font-semibold text-slate-700">
        Supporting Evidence <span class="text-xs font-normal text-slate-500">(Optional)</span>
    </label>

    <div class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-5">
        <div class="mb-3 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#101064]/10 text-[#101064]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16.5V6a2 2 0 012-2h12a2 2 0 012 2v10.5M4 16.5l4.5-4.5a2 2 0 012.8 0L15 15.7l1.5-1.5a2 2 0 012.8 0L20 15M4 16.5V18a2 2 0 002 2h12a2 2 0 002-2v-2.5M8 8h.01" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-[#101064]">Upload Event Photos</p>
                <p class="text-xs text-slate-500">Photos showing your participation in the event.</p>
            </div>
        </div>

        <input
            type="file"
            name="evidence[]"
            id="discrepancy-evidence"
            accept="image/jpeg,image/png,image/webp"
            multiple
            class="block w-full rounded-lg border border-slate-300 bg-white p-2 text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-[#101064] file:px-4 file:py-2 file:font-semibold file:text-white"
        >

        <p class="mt-2 text-xs text-slate-500">
            JPG, PNG or WEBP. Maximum 3 images, 5 MB each.
            Photos are supporting evidence and do not automatically confirm attendance.
        </p>
    </div>

    @error('evidence')
        <p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>
    @enderror

    @error('evidence.*')
        <p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>
    @enderror
</div>
<div class="md:col-span-2 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">
                    <p class="max-w-xl text-xs leading-5 text-slate-500">Only confirmed discrepancies can be approved. The official attendance record is unchanged until Department Staff approval.</p>
                    <button type="submit" @disabled($events->isEmpty()) class="rounded-xl bg-[#D4A017] px-6 py-3 text-sm font-extrabold text-[#101064] transition hover:bg-yellow-400 disabled:cursor-not-allowed disabled:opacity-40">Submit for Review</button>
                </div>
                @if ($events->isEmpty())
                    <p class="md:col-span-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">No eligible completed events are available yet. You can report discrepancies once an eligible event is completed.</p>
                @endif
            </form>
        </section>

        <section id="dyDiscHistory" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="discrepancy-history-heading">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-6">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-[#A9780B]">Submission and Decision History</p>
                    <h2 id="discrepancy-history-heading" class="mt-2 text-xl font-extrabold text-[#101064]">My Submitted Requests</h2>
                    <p class="mt-1 text-sm text-slate-500">Track your concern, personnel verification, and final Department Staff decision.</p>
                </div>
                <a href="#dyDiscSubmit" class="dy-disc-secondary">+ New Request</a>
            </div>
            <form method="GET" action="{{ route('student.attendance-discrepancies.index') }}#dyDiscHistory" class="border-b border-slate-100 bg-slate-50/70 p-5 sm:p-6">
                <div class="dy-disc-filter">
                    <div class="dy-disc-search"><label for="disc-search">Search event</label><input id="disc-search" class="dy-disc-field" name="search" maxlength="100" type="search" placeholder="Event name" value="{{ $filters['search'] ?? '' }}"></div>
                    <div class="dy-disc-type"><label for="disc-type">Request type</label><select id="disc-type" class="dy-disc-field" name="request_type"><option value="">All types</option>@foreach (['Missing Attendance', 'Incorrect Status', 'Incorrect Time'] as $type)<option value="{{ $type }}" @selected(($filters['request_type'] ?? '') === $type)>{{ $type }}</option>@endforeach</select></div>
                    <div class="dy-disc-verification"><label for="disc-verification">Personnel verification</label><select id="disc-verification" class="dy-disc-field" name="verification_status"><option value="">All results</option>@foreach (['Unverified', 'Confirmed', 'Not Confirmed'] as $verification)<option value="{{ $verification }}" @selected(($filters['verification_status'] ?? '') === $verification)>{{ $verification }}</option>@endforeach</select></div>
                    <div class="dy-disc-status"><label for="disc-status">Staff decision</label><select id="disc-status" class="dy-disc-field" name="status"><option value="">All decisions</option>@foreach (['Pending', 'Approved', 'Rejected'] as $decision)<option value="{{ $decision }}" @selected(($filters['status'] ?? '') === $decision)>{{ $decision }}</option>@endforeach</select></div>
                    <div class="dy-disc-sort"><label for="disc-sort">Sort</label><select id="disc-sort" class="dy-disc-field" name="sort"><option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Newest first</option><option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Oldest first</option></select></div>
                    <div class="dy-disc-perpage"><label for="disc-perpage">Per page</label><select id="disc-perpage" class="dy-disc-field" name="per_page">@foreach ([3, 5, 10] as $size)<option value="{{ $size }}" @selected((int)($filters['per_page'] ?? 3) === $size)>{{ $size }} requests</option>@endforeach</select></div>
                    <div class="dy-disc-actions" style="display:flex;align-items:end;gap:8px;flex-wrap:wrap"><button type="submit" class="dy-disc-primary">Apply</button><a href="{{ route('student.attendance-discrepancies.index') }}#dyDiscHistory" class="dy-disc-secondary">Reset</a></div>
                </div>
            </form>

            <div class="p-5 sm:p-6">
                <div class="mb-4 flex flex-wrap justify-between gap-2 text-xs text-slate-500">
                    <p><strong class="text-[#101064]">{{ $discrepancies->total() }}</strong> matching of {{ $counts['all'] }} total requests</p>
                    <p>Showing {{ $discrepancies->firstItem() ?? 0 }}–{{ $discrepancies->lastItem() ?? 0 }}</p>
                </div>
                @forelse ($discrepancies as $item)
                    <article class="dy-disc-card">
                        <div class="p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold uppercase tracking-widest text-[#A9780B]">Request #{{ $item->discrepancy_id }} &middot; {{ $item->request_type }}</p>
                                    <h3 class="mt-2 text-lg font-extrabold text-[#101064]">{{ $item->event?->event_name ?? 'Unavailable Event' }}</h3>
                                    <p class="mt-1 text-xs text-slate-500">Event: {{ $item->event?->event_date?->format('M d, Y') ?? 'Unknown' }} &middot; Submitted: {{ $item->created_at?->format('M d, Y g:i A') ?? 'Unknown' }}</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span class="dy-disc-pill {{ $item->verification_status === 'Confirmed' ? 'dy-disc-pill--ok' : ($item->verification_status === 'Not Confirmed' ? 'dy-disc-pill--no' : 'dy-disc-pill--wait') }}">Personnel: {{ $item->verification_status }}</span>
                                    <span class="dy-disc-pill {{ $item->status === 'Approved' ? 'dy-disc-pill--ok' : ($item->status === 'Rejected' ? 'dy-disc-pill--no' : 'dy-disc-pill--wait') }}">Staff: {{ $item->status }}</span>
                                </div>
                            </div>
                            <div class="mt-4 rounded-xl bg-slate-50 p-3 text-sm font-semibold {{ $item->status === 'Rejected' ? 'text-rose-700' : ($item->status === 'Approved' ? 'text-emerald-800' : 'text-amber-800') }}">
                                @if ($item->status === 'Approved')
                                    Approved &middot; Official attendance status: {{ $item->after_status ?? 'Updated' }}
                                    @if ($item->after_status === 'Incomplete')<p class="mt-1 text-xs font-normal text-amber-800">No actual check-in/check-out timestamps were recorded; times were not invented.</p>@endif
                                @elseif ($item->status === 'Rejected')
                                    Rejected &middot; No correction was applied
                                @else
                                    Pending &middot; Awaiting {{ $item->verification_status === 'Unverified' ? 'Personnel verification' : 'Department Staff decision' }}
                                @endif
                            </div>
                        </div>
                        <div class="dy-disc-progress">
                            <div><p class="text-[10px] uppercase text-slate-500">01 · Submission</p><p class="mt-1 text-xs font-bold text-[#101064]">Submitted</p></div>
                            <div><p class="text-[10px] uppercase text-slate-500">02 · Personnel</p><p class="mt-1 text-xs font-bold text-[#101064]">{{ $item->verification_status }}</p></div>
                            <div><p class="text-[10px] uppercase text-slate-500">03 · Staff</p><p class="mt-1 text-xs font-bold text-[#101064]">{{ $item->status }}</p></div>
                        </div>
                        <details class="dy-disc-detail">
                            <summary>View explanation, supporting evidence and decision details</summary>
                            <div class="dy-disc-details-grid">
                                <div class="dy-disc-details-box">
                                    <h4>Student Request</h4>
                                    <p><strong>Type:</strong> {{ $item->request_type }}</p>
                                    @if ($item->requested_status)<p><strong>Requested status:</strong> {{ $item->requested_status }}</p>@endif
                                    <p class="whitespace-pre-wrap"><strong>Explanation:</strong> {{ $item->reason }}</p>
                                    <h4 class="mt-4">Original Attendance</h4>
                                    <p><strong>Status:</strong> {{ $item->before_status ?? 'No record' }}</p>
                                    <p><strong>Time In:</strong> {{ $item->before_time_in?->format('M d, Y g:i A') ?? 'Not recorded' }}</p>
                                    <p><strong>Time Out:</strong> {{ $item->before_time_out?->format('M d, Y g:i A') ?? 'Not recorded' }}</p>
                                    @include('components.attendance-discrepancy-evidence', ['item' => $item, 'routeName' => 'student.attendance-discrepancies.evidence'])
                                </div>
                                <div class="dy-disc-details-box">
                                    <h4>Personnel Verification</h4>
                                    <span class="dy-disc-pill {{ $item->verification_status === 'Confirmed' ? 'dy-disc-pill--ok' : ($item->verification_status === 'Not Confirmed' ? 'dy-disc-pill--no' : 'dy-disc-pill--wait') }}">{{ $item->verification_status }}</span>
                                    <p><strong>Verified by:</strong> {{ $item->verifier?->name ?? 'Not verified' }}</p>
                                    <p><strong>Verified at:</strong> {{ $item->verified_at?->format('M d, Y g:i A') ?? 'Not recorded' }}</p>
                                    <p class="whitespace-pre-wrap"><strong>Notes:</strong> {{ $item->verification_notes ?: 'No notes yet' }}</p>
                                    <p><strong>Verified Time In:</strong> {{ $item->verified_time_in?->format('M d, Y g:i A') ?? 'Not captured' }}</p>
                                    <p><strong>Verified Time Out:</strong> {{ $item->verified_time_out?->format('M d, Y g:i A') ?? 'Not captured' }}</p>
                                </div>
                                <div class="dy-disc-details-box">
                                    <h4>Department Staff Decision</h4>
                                    <span class="dy-disc-pill {{ $item->status === 'Approved' ? 'dy-disc-pill--ok' : ($item->status === 'Rejected' ? 'dy-disc-pill--no' : 'dy-disc-pill--wait') }}">{{ $item->status }}</span>
                                    <p><strong>Reviewed by:</strong> {{ $item->reviewer?->name ?? 'Not reviewed' }}</p>
                                    <p><strong>Review date:</strong> {{ $item->reviewed_at?->format('M d, Y g:i A') ?? 'Not recorded' }}</p>
                                    <p class="whitespace-pre-wrap"><strong>Remarks:</strong> {{ $item->review_notes ?: 'No notes yet' }}</p>
                                    <h4 class="mt-4">Final Attendance Outcome</h4>
                                    @if ($item->status === 'Approved')
                                        <p><strong>Status:</strong> {{ $item->after_status ?? 'Updated' }}</p>
                                        <p><strong>Time In:</strong> {{ $item->after_time_in?->format('M d, Y g:i A') ?? 'Not captured' }}</p>
                                        <p><strong>Time Out:</strong> {{ $item->after_time_out?->format('M d, Y g:i A') ?? 'Not captured' }}</p>
                                    @else
                                        <p>No official attendance correction was applied.</p>
                                    @endif
                                </div>
                            </div>
                        </details>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center">
                        <h3 class="font-extrabold text-[#101064]">No requests found</h3>
                        <p class="mt-2 text-sm text-slate-500">Try other filters or submit a new request.</p>
                    </div>
                @endforelse
            </div>
            @if ($discrepancies->hasPages())
                <nav class="border-t border-slate-100 px-5 py-4 sm:px-6" aria-label="Discrepancy pages">
                    {{ $discrepancies->onEachSide(1)->links() }}
                </nav>
            @endif
        </section>
    </div>

    <script>
        (() => {
            'use strict';
            const form = document.getElementById('dyStudentDiscrepancyForm');
            if (!form) return;
            const eventSelect = form.querySelector('#event_id');
            const issue = form.querySelector('#request_type');
            const statusBlock = document.getElementById('dyRequestedStatusBlock');

            const statusInput = form.querySelector('#requested_status');

            const hint = document.getElementById('dyDiscrepancyEventHint');
            if (!eventSelect || !issue || !statusBlock || !statusInput) return;

            function updateFields() {
                const kind = issue.value;
                const needStatus = kind === 'Incorrect Status';

                statusBlock.classList.toggle('hidden', !needStatus);

                statusInput.required = needStatus;

                statusInput.disabled = !needStatus;

                const selected = eventSelect.options[eventSelect.selectedIndex];
                if (hint) {
                    if (!selected || !eventSelect.value) hint.textContent = 'Your eligibility and existing attendance are checked again when you submit.';
                    else if (selected.dataset.hasAttendance === '1') hint.textContent = 'An attendance record exists. Use Incorrect Status or Incorrect Time if details are wrong.';
                    else hint.textContent = 'No attendance record found. Select Missing Attendance to report it.';
                }
            }
            eventSelect.addEventListener('change', updateFields);
            issue.addEventListener('change', updateFields);
            updateFields();
        })();
    </script>
</x-admin-layout>