<x-admin-layout>
    <style>
        .dy-event-hero {
            background-image: radial-gradient(circle at 98% 5%, transparent 0 78px, rgba(255,255,255,.10) 79px 81px, transparent 82px 124px),
                linear-gradient(103deg, rgba(16,16,100,.98), rgba(16,16,100,.91) 53%, rgba(16,16,100,.70)),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover; background-position: center;
        }
        .dy-event-action { background-color: #E5AE19 !important; color: #101064 !important; border: 1px solid #f2c44d; }
        .dy-event-primary { background-color: #101064 !important; color: #ffffff !important; }
        .dy-event-card { transition: border-color .18s ease, transform .18s ease, box-shadow .18s ease; }
        .dy-event-card:hover, .dy-event-card:focus-visible { border-color: #d4a017; transform: translateY(-2px); box-shadow: 0 10px 22px rgba(16,16,100,.08); }
        .dy-event-field { min-height: 44px; border: 1px solid #cbd5e1; border-radius: 12px; background-color: white; color: #101064; padding: 10px 13px; width: 100%; }
        .dy-event-field:focus { outline: 2px solid #d4a017; outline-offset: 1px; }
        .dy-event-status { display: inline-flex; align-items: center; border-radius: 9999px; padding: 5px 10px; font-size: 11px; font-weight: 700; white-space: nowrap; }
        .dy-event-table th { color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: .07em; font-weight: 700; text-align: left; padding: 14px 16px; }
        .dy-event-table td { padding: 15px 16px; border-top: 1px solid #edf2f7; font-size: 13px; color: #334155; vertical-align: middle; }
        .dy-event-table tbody tr:hover { background-color: #f8fafc; }
        @media(max-width: 720px) { .dy-event-table { min-width: 780px; } }
    </style>

    <div class="w-full min-w-0 space-y-6 pb-10">
        <section class="dy-event-hero relative overflow-hidden rounded-[28px] border-b-4 border-[#D4A017] px-6 py-8 text-white shadow-sm sm:px-9 sm:py-10">
            <div class="flex flex-wrap items-center justify-between gap-6">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[.24em] text-[#F2C94C]">DySign · Department Staff</p>
                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Event Attendance Records</h1>
                    <p class="mt-3 text-sm leading-6 text-indigo-100">Select a department event to review students' recorded Time In, Time Out, and attendance status. Export a CSV or formatted PDF report.</p>
                    <div class="mt-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white">
                        <span class="h-2 w-2 rounded-full bg-[#E5AE19]"></span> Department events only · Read-only attendance
                    </div>
                </div>
                @if($selectedEvent)
                    <a href="{{ route('staff.participation-classifications.index') }}" class="dy-event-action inline-flex min-h-11 items-center justify-center rounded-xl px-5 py-3 text-sm font-extrabold shadow-sm">← Choose Another Event</a>
                @endif
            </div>
        </section>

        @if(!$selectedEvent)
            <section class="grid gap-4 sm:grid-cols-3" aria-label="Event attendance guide">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase tracking-[.12em] text-[#A9780B]">Step 01</p><p class="mt-2 text-base font-extrabold text-[#101064]">Choose an Event</p><p class="mt-2 text-xs leading-5 text-slate-500">Open one of your department's events below.</p></div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase tracking-[.12em] text-[#A9780B]">Step 02</p><p class="mt-2 text-base font-extrabold text-[#101064]">Review Attendance</p><p class="mt-2 text-xs leading-5 text-slate-500">View student names, recorded check-in/check-out times, and statuses.</p></div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase tracking-[.12em] text-[#A9780B]">Step 03</p><p class="mt-2 text-base font-extrabold text-[#101064]">Export Report</p><p class="mt-2 text-xs leading-5 text-slate-500">Download matching records in CSV or PDF format.</p></div>
            </section>
        @else
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="selected-event-heading">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[.18em] text-[#A9780B]">Selected Department Event</p>
                        <h2 id="selected-event-heading" class="mt-2 text-xl font-extrabold text-[#101064] sm:text-2xl">{{ $selectedEvent->event_name }}</h2>
                        <p class="mt-2 text-sm text-slate-600">{{ $selectedEvent->event_date?->format('M d, Y') ?? 'Date unavailable' }} · {{ $selectedEvent->location ?: 'Location unavailable' }}</p>
                        <p class="mt-1 text-xs text-slate-500">Scheduled: {{ $selectedEvent->start_time ? \Carbon\Carbon::parse($selectedEvent->start_time)->format('g:i A') : 'N/A' }} – {{ $selectedEvent->end_time ? \Carbon\Carbon::parse($selectedEvent->end_time)->format('g:i A') : 'N/A' }} · {{ $selectedEvent->status }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('staff.participation-classifications.csv', array_merge(request()->except(['page','event_page']), ['event_id' => $selectedEvent->event_id])) }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#101064] bg-white px-4 py-2.5 text-sm font-extrabold text-[#101064]">↓ Download CSV</a>
                        <a href="{{ route('staff.participation-classifications.pdf', array_merge(request()->except(['page','event_page']), ['event_id' => $selectedEvent->event_id])) }}" class="dy-event-action inline-flex min-h-11 items-center justify-center rounded-xl px-4 py-2.5 text-sm font-extrabold shadow-sm">↓ Download PDF</a>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-500">Downloads contain all records matching the selected filters, not only the current page. Missing Time In/Out stays unrecorded.</p>
            </section>
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Event attendance summary">
                @foreach([
                    ['title' => 'Recorded Students', 'value' => (int)$statistics->recorded, 'subtitle' => 'All attendance statuses'],
                    ['title' => 'Attended', 'value' => (int)$statistics->present + (int)$statistics->late, 'subtitle' => 'Present + Late'],
                    ['title' => 'Present', 'value' => (int)$statistics->present, 'subtitle' => 'Marked Present'],
                    ['title' => 'Late', 'value' => (int)$statistics->late, 'subtitle' => 'Marked Late'],
                ] as $metric)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-500">{{ $metric['title'] }}</p>
                        <p class="mt-3 text-3xl font-extrabold text-[#101064]">{{ number_format($metric['value']) }}</p>
                        <p class="mt-2 text-xs text-slate-500">{{ $metric['subtitle'] }}</p>
                    </div>
                @endforeach
            </section>
        @endif

        @if(!$selectedEvent)
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="department-events-heading">
                <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                    <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#A9780B]">Event Directory</p>
                    <h2 id="department-events-heading" class="mt-1 text-lg font-extrabold text-[#101064]">Choose a Department Event</h2>
                    <p class="mt-1 text-xs text-slate-500">{{ number_format($events->total()) }} matching events. Click an event to open its attendance records.</p>
                </div>
                <form method="GET" action="{{ route('staff.participation-classifications.index') }}" class="grid gap-3 border-b border-slate-100 bg-slate-50/60 px-5 py-5 md:grid-cols-[2fr_1fr_auto_auto] md:items-end sm:px-6">
                    <label class="block"><span class="mb-2 block text-xs font-bold text-slate-700">Search Events</span><input class="dy-event-field" type="search" name="event_search" maxlength="100" value="{{ $filters['event_search'] }}" placeholder="Event name or venue"></label>
                    <label class="block"><span class="mb-2 block text-xs font-bold text-slate-700">Event Status</span><select name="event_status" class="dy-event-field"><option value="">All statuses</option>@foreach(['Upcoming','Ongoing','Completed','Cancelled'] as $eventStatus)<option value="{{ $eventStatus }}" @selected($filters['event_status'] === $eventStatus)>{{ $eventStatus }}</option>@endforeach</select></label>
                    <button class="dy-event-primary min-h-11 rounded-xl px-5 py-3 text-sm font-bold" type="submit">Apply Filters</button>
                    <a class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-[#101064]" href="{{ route('staff.participation-classifications.index') }}">Reset</a>
                </form>
                <div class="grid gap-4 p-4 sm:grid-cols-2 sm:p-6 xl:grid-cols-3">
                    @forelse($events as $event)
                        <a href="{{ route('staff.participation-classifications.index', ['event_id' => $event->event_id]) }}" class="dy-event-card flex min-w-0 flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm focus:outline-none" aria-label="View attendance for {{ $event->event_name }}">
                            <div>
                                <div class="flex items-start justify-between gap-2"><span class="text-[11px] font-bold uppercase tracking-[.12em] text-[#A9780B]">{{ $event->event_date?->format('M d, Y') ?? 'No date' }}</span><span class="dy-event-status bg-indigo-50 text-[#101064]">{{ $event->status }}</span></div>
                                <h3 class="mt-3 break-words text-base font-extrabold text-[#101064]">{{ $event->event_name }}</h3>
                                <p class="mt-2 text-xs text-slate-500">{{ $event->location ?: 'Location unavailable' }}</p>
                            </div>
                            <div class="mt-5 flex items-center justify-between gap-2 border-t border-slate-100 pt-4">
                                <span class="text-xs text-slate-600"><strong class="text-[#101064]">{{ $event->attended_count }}</strong> attended · {{ $event->recorded_count }} recorded</span>
                                <span class="text-xs font-extrabold text-[#A9780B]">View Attendance →</span>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full rounded-xl border border-dashed border-slate-200 px-6 py-12 text-center text-sm text-slate-500">No department events match these filters.</div>
                    @endforelse
                </div>
                @if($events->hasPages())<div class="border-t border-slate-100 px-5 py-4 sm:px-6">{{ $events->links() }}</div>@endif
            </section>
        @else
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="event-records-heading">
                <header class="border-b border-slate-100 bg-[#101064] px-5 py-5 text-white sm:px-6">
                    <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#F2C94C]">Attendance & Student Monitoring</p>
                    <h2 id="event-records-heading" class="mt-1 text-lg font-extrabold">Recorded Student Attendance</h2>
                    <p class="mt-1 text-xs text-indigo-100">Default shows attendees marked Present or Late. Change the status filter to inspect other recorded statuses.</p>
                </header>
                <form method="GET" action="{{ route('staff.participation-classifications.index') }}" class="grid gap-3 border-b border-slate-100 bg-slate-50/70 p-5 sm:p-6 lg:grid-cols-3 xl:grid-cols-5">
                    <input type="hidden" name="event_id" value="{{ $selectedEvent->event_id }}">
                    <label><span class="mb-2 block text-xs font-bold text-slate-700">Search Students</span><input type="search" name="search" value="{{ $filters['search'] }}" maxlength="100" placeholder="Name, student number..." class="dy-event-field"></label>
                    <label><span class="mb-2 block text-xs font-bold text-slate-700">Attendance Status</span><select name="status" class="dy-event-field">
                        @foreach(['attended' => 'Attended (Present + Late)', 'all' => 'All recorded statuses', 'Present' => 'Present', 'Late' => 'Late', 'Checked In' => 'Checked In', 'Incomplete' => 'Incomplete', 'Absent' => 'Absent', 'Excused' => 'Excused'] as $statusValue => $label)
                            <option value="{{ $statusValue }}" @selected($filters['status'] === $statusValue)>{{ $label }}</option>
                        @endforeach
                    </select></label>
                    <label><span class="mb-2 block text-xs font-bold text-slate-700">Program</span><select name="program" class="dy-event-field"><option value="">All programs</option>@foreach($programs as $program)<option value="{{ $program }}" @selected($filters['program'] === $program)>{{ $program }}</option>@endforeach</select></label>
                    <label><span class="mb-2 block text-xs font-bold text-slate-700">Sort By</span><select name="sort" class="dy-event-field">
                        @foreach(['name_asc' => 'Name A to Z','name_desc' => 'Name Z to A','time_in_asc' => 'Earliest Time In','time_in_desc' => 'Latest Time In','status' => 'Attendance Status'] as $sortValue => $sortLabel)
                            <option value="{{ $sortValue }}" @selected($filters['sort'] === $sortValue)>{{ $sortLabel }}</option>
                        @endforeach
                    </select></label>
                    <label><span class="mb-2 block text-xs font-bold text-slate-700">Rows Per Page</span><select name="per_page" class="dy-event-field">@foreach([5,10,20] as $perPage)<option value="{{ $perPage }}" @selected($filters['per_page'] === $perPage)>{{ $perPage }} students</option>@endforeach</select></label>
                    <div class="flex flex-wrap gap-2 lg:col-span-3 xl:col-span-5">
                        <button class="dy-event-primary min-h-11 rounded-xl px-5 py-3 text-sm font-bold" type="submit">Apply Filters</button>
                        <a class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-[#101064]" href="{{ route('staff.participation-classifications.index', ['event_id' => $selectedEvent->event_id]) }}">Reset</a>
                        <span class="ml-auto self-center text-xs text-slate-500">{{ number_format($records->total()) }} matching · Showing {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }}</span>
                    </div>
                </form>
                <div class="overflow-x-auto">
                    <table class="dy-event-table w-full min-w-[780px] border-collapse">
                        <thead class="bg-slate-50"><tr><th>Student</th><th>Program / Year</th><th>Time In</th><th>Time Out</th><th>Attendance Status</th></tr></thead>
                        <tbody>
                            @forelse($records as $record)
                                @php
                                    $statusTone = match ($record->status) {
                                        'Present' => 'bg-emerald-50 text-emerald-800',
                                        'Late' => 'bg-amber-50 text-amber-800',
                                        'Checked In' => 'bg-indigo-50 text-[#101064]',
                                        'Incomplete' => 'bg-rose-50 text-rose-700',
                                        'Excused' => 'bg-violet-50 text-violet-800',
                                        'Absent' => 'bg-slate-100 text-slate-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <tr><td><p class="font-bold text-[#101064]">{{ $record->last_name }}, {{ $record->first_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $record->student_number }}</p></td>
                                    <td>{{ $record->program_code ?: 'N/A' }}<p class="mt-1 text-xs text-slate-500">Year {{ $record->year_level ?: 'N/A' }}</p></td>
                                    <td>{{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('M d, Y') : 'Not recorded' }}@if($record->time_in)<p class="mt-1 font-bold text-[#101064]">{{ \Carbon\Carbon::parse($record->time_in)->format('g:i A') }}</p>@endif</td>
                                    <td>{{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('M d, Y') : 'Not recorded' }}@if($record->time_out)<p class="mt-1 font-bold text-[#101064]">{{ \Carbon\Carbon::parse($record->time_out)->format('g:i A') }}</p>@endif</td>
                                    <td><span class="dy-event-status {{ $statusTone }}">{{ $record->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-12 text-center text-slate-500">No recorded student attendance matches the selected event and filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($records->hasPages())<div class="border-t border-slate-100 px-5 py-4 sm:px-6">{{ $records->links() }}</div>@endif
            </section>
            <p class="text-xs leading-5 text-slate-500">Only saved attendance records are shown. Present and Late count as attended; Checked In, Incomplete, Absent, and Excused remain distinguishable. This page and its exports do not modify attendance or synthesize missing scan timestamps.</p>
        @endif
    </div>
</x-admin-layout>
