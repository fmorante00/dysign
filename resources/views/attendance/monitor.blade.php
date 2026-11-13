<x-admin-layout>
<style>
    .dy-attendance-monitor { --dy-navy: #101064; --dy-gold: #D4A017; }
    .dy-attendance-monitor .dy-hero {
        background: linear-gradient(100deg, rgba(16,16,100,.97), rgba(16,16,100,.89) 52%, rgba(16,16,100,.73)),
            url('{{ asset('images/school.jpg') }}') center / cover;
        box-shadow: 0 18px 35px -28px rgba(16,16,100,.7);
    }
    .dy-attendance-monitor .dy-orbit { position: absolute; border: 1px solid rgba(255,255,255,.2); border-radius: 50%; pointer-events: none; }
    .dy-attendance-monitor .dy-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 9px 28px -25px rgba(16,16,100,.55); }
    .dy-attendance-monitor .dy-kicker { font-size: 10px; font-weight: 800; letter-spacing: .26em; text-transform: uppercase; color: #ae8005; }
    .dy-attendance-monitor .dy-stat { padding: 20px; min-width: 0; }
    .dy-attendance-monitor .dy-stat p:first-child { color: #76839b; text-transform: uppercase; font-size: 10px; font-weight: 800; letter-spacing: .1em; }
    .dy-attendance-monitor .dy-stat strong { display: block; margin-top: 12px; font-size: 30px; line-height: 1; font-weight: 800; }
    .dy-attendance-monitor .dy-table th { padding: 15px 18px; background: #f8fafc; color: #78859b; letter-spacing: .09em; font-size: 10px; font-weight: 800; text-transform: uppercase; white-space: nowrap; }
    .dy-attendance-monitor .dy-table td { padding: 14px 18px; border-top: 1px solid #f1f5f9; font-size: 12px; color: #475569; vertical-align: middle; }
    .dy-attendance-monitor .dy-table tr:hover td { background: #fafbff; }
    .dy-attendance-monitor .dy-feed-pulse { width: 8px; height: 8px; border-radius: 999px; display: inline-block; background: #22c55e; box-shadow: 0 0 0 4px rgba(34,197,94,.12); }
    .dy-attendance-monitor .dy-feed-pulse[data-state="error"] { background: #ef4444; box-shadow: 0 0 0 4px rgba(239,68,68,.12); }
    .dy-attendance-monitor .dy-feed-pulse[data-state="sync"] { background: #d4a017; box-shadow: 0 0 0 4px rgba(212,160,23,.13); }

    /* Student-portal inspired event directory, limited to this monitoring view. */
    .dy-attendance-monitor .dy-registry-panel { overflow: hidden; border: 1px solid #e2e7f0; border-radius: 22px; background: #fff; box-shadow: 0 5px 18px rgba(18,34,64,.035); }
    .dy-attendance-monitor .dy-registry-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; padding: 24px 26px; border-bottom: 1px solid #edf0f5; }
    .dy-attendance-monitor .dy-registry-search { width: min(100%,310px); border: 1px solid #dfe5ef; border-radius: 11px; background: #f8fafd; padding: 11px 14px; font-size: 12px; color: #101064; outline: none; }
    .dy-attendance-monitor .dy-registry-search:focus { border-color: #D4A017; box-shadow: 0 0 0 3px rgba(212,160,23,.1); }
    .dy-attendance-monitor .dy-tabs { display: flex; gap: 6px; flex-wrap: wrap; padding: 13px 22px; background: #fcfdfe; border-bottom: 1px solid #eef1f6; }
    .dy-attendance-monitor .dy-tab { border-radius: 9px; border: 1px solid transparent; padding: 9px 13px; font-size: 11px; font-weight: 750; color: #63708a; transition: background .15s,color .15s; }
    .dy-attendance-monitor .dy-tab:hover { background: #f1f3f9; color: #101064; }
    .dy-attendance-monitor .dy-tab[aria-pressed="true"] { color: white; background: #101064; box-shadow: 0 3px 9px rgba(16,16,100,.15); }
    .dy-attendance-monitor .dy-event-list { padding: 18px 22px 24px; display: grid; gap: 13px; }
    .dy-attendance-monitor .dy-event-row { border: 1px solid #e5eaf2; border-radius: 18px; background: #fff; overflow: hidden; transition: border-color .15s, box-shadow .15s; }
    .dy-attendance-monitor .dy-event-row:hover { border-color: #cdd5e4; box-shadow: 0 7px 22px rgba(17,29,56,.055); }
    .dy-attendance-monitor .dy-event-main { display: grid; grid-template-columns: 65px minmax(0,1fr) 170px; gap: 19px; align-items: center; padding: 19px 21px; }
    .dy-attendance-monitor .dy-date-tile { border: 1px solid #e2e7f1; border-radius: 14px; background: #f3f4fb; padding: 11px 6px; text-align: center; color: #101064; }
    .dy-attendance-monitor .dy-date-month { font-size: 10px; font-weight: 850; letter-spacing: .1em; text-transform: uppercase; }
    .dy-attendance-monitor .dy-date-day { margin-top: 3px; font-size: 25px; line-height: 1.12; font-weight: 850; }
    .dy-attendance-monitor .dy-date-year { margin-top: 3px; font-size: 10px; color: #65748d; }
    .dy-attendance-monitor .dy-badge { display: inline-flex; align-items: center; gap: 5px; border-radius: 7px; padding: 5px 8px; font-size: 10px; font-weight: 750; }
    .dy-attendance-monitor .dy-badge.upcoming { background: #eff2ff; color: #303080; }
    .dy-attendance-monitor .dy-badge.ongoing { background: #eaf8f1; color: #087a55; }
    .dy-attendance-monitor .dy-badge.completed { background: #f1f3f6; color: #5f6c81; }
    .dy-attendance-monitor .dy-badge.gold { background: #fff7e4; color: #8b670d; }
    .dy-attendance-monitor .dy-event-meta { display: flex; flex-wrap: wrap; gap: 8px 18px; margin-top: 12px; color: #596882; font-size: 11px; }
    .dy-attendance-monitor .dy-event-meta span { display: inline-flex; align-items: center; gap: 5px; }
    .dy-attendance-monitor .dy-event-meta b { font-weight: 700; color: #a9821a; }
    .dy-attendance-monitor .dy-action-btn { display: flex; align-items: center; justify-content: center; min-height: 42px; border-radius: 10px; padding: 10px 13px; background: #101064; color: #fff; font-size: 11px; text-align: center; font-weight: 800; transition: background .15s; }
    .dy-attendance-monitor .dy-action-btn:hover { background: #282880; }
    .dy-attendance-monitor .dy-event-foot { padding: 11px 21px; border-top: 1px solid #f0f2f6; background: #fbfcfe; color: #77859c; font-size: 10px; line-height: 1.55; }
    .dy-attendance-monitor .dy-pager { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; padding: 14px 23px; border-top: 1px solid #edf0f5; font-size: 11px; color: #65748d; }
    .dy-attendance-monitor .dy-pager button { border: 1px solid #dfe5ef; border-radius: 9px; background: white; color: #101064; padding: 8px 12px; font-size: 11px; font-weight: 750; }
    .dy-attendance-monitor .dy-pager button:disabled { color: #9aa5b6; background: #f8fafc; cursor: not-allowed; }
    .dy-attendance-monitor .dy-activity-list { padding: 14px; display: grid; gap: 10px; }
    .dy-attendance-monitor .dy-activity-item { display: grid; grid-template-columns: minmax(0,1fr) auto; gap: 14px; align-items: center; padding: 16px 17px; border: 1px solid #e8edf4; border-radius: 14px; background: #fff; }
    .dy-attendance-monitor .dy-activity-foot { display: flex; flex-wrap: wrap; gap: 9px 17px; padding-top: 10px; margin-top: 11px; border-top: 1px solid #f0f2f6; color: #748198; font-size: 11px; }
    @media(max-width:1000px) { .dy-attendance-monitor .dy-event-main { grid-template-columns: 65px minmax(0,1fr); } .dy-attendance-monitor .dy-event-action { grid-column: 2; } }
    @media(max-width:640px) { .dy-attendance-monitor .dy-registry-head { padding: 20px 17px; } .dy-attendance-monitor .dy-tabs { padding: 12px; } .dy-attendance-monitor .dy-event-list { padding: 12px; } .dy-attendance-monitor .dy-event-main { padding: 15px; grid-template-columns: 57px minmax(0,1fr); gap: 12px; } .dy-attendance-monitor .dy-date-day { font-size: 22px; } .dy-attendance-monitor .dy-event-action { grid-column: 1/-1; } .dy-attendance-monitor .dy-activity-item { grid-template-columns: 1fr; } }
    .dy-attendance-monitor .dy-new-row { background: #fffbeb !important; }
    @media (prefers-reduced-motion: no-preference) {
        @keyframes dy-fade-in { from { opacity: .3; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        .dy-attendance-monitor .dy-new-row { animation: dy-fade-in .4s ease-out; }
    }
</style>

@php
    $hasAnyActive = $activeEvents->isNotEmpty();
    $eventEntries = $events->map(fn ($event) => ['event' => $event, 'status' => $event->calculateScheduledStatus()]);
    $scheduledCounts = $eventEntries->countBy('status');
    $latestName = $latestAttendance ? trim(collect([
        $latestAttendance->first_name, $latestAttendance->middle_name, $latestAttendance->last_name
    ])->filter()->implode(' ')) : '';
@endphp

<div class="dy-attendance-monitor min-w-0 space-y-7 pb-9">
    {{-- Institutional hero, aligned with Student Dashboard and refreshed Admin pages --}}
    <section class="dy-hero relative isolate overflow-hidden rounded-[28px] px-6 py-8 text-white sm:px-8 lg:px-10 lg:py-10">
        <span aria-hidden="true" class="dy-orbit -right-16 -top-32 h-80 w-80"></span>
        <span aria-hidden="true" class="dy-orbit -right-5 -top-20 h-60 w-60"></span>
        <span aria-hidden="true" class="pointer-events-none absolute bottom-0 right-0 h-32 w-80 bg-gradient-to-l from-[#D4A017]/10 to-transparent"></span>
        <span aria-hidden="true" class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></span>
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-5">
            <div class="min-w-0 max-w-3xl">
                <p class="text-[10px] font-extrabold uppercase tracking-[.3em] text-[#f2ca51]">Institutional Attendance Oversight</p>
                <h1 class="mt-3 break-words text-2xl font-extrabold tracking-tight sm:text-3xl lg:text-4xl">Attendance Monitoring</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">View live check-ins, check-outs, and verified attendance records across university events. Administrator access is view-only.</p>
                <div class="mt-5 flex flex-wrap gap-4 text-xs font-semibold text-white/80">
                    <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>{{ $activeEvents->count() }} ongoing {{ \Illuminate\Support\Str::plural('event', $activeEvents->count()) }}</span>
                    <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-[#f2ca51]"></span>{{ $events->count() }} monitorable {{ \Illuminate\Support\Str::plural('event', $events->count()) }}</span>
                    <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-white/60"></span>Read-only administrator view</span>
                </div>
            </div>
            @if($selectedEvent)
                <a href="{{ route('events.show', $selectedEvent->event_id) }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-[#D4A017] px-5 py-3 text-xs font-bold text-[#101064] shadow-sm transition hover:bg-white">
                    View Event Details <span aria-hidden="true">↗</span>
                </a>
            @endif
        </div>
    </section>

    @if(!$selectedEvent)
        {{-- The event card directory deliberately mirrors the Student "My Eligible Events" layout. --}}
        <section class="dy-registry-panel" id="monitor-directory" aria-labelledby="monitor-event-heading">
            <div class="dy-registry-head">
                <div>
                    <p class="dy-kicker">University Events</p>
                    <h2 id="monitor-event-heading" class="mt-2 text-xl font-extrabold text-[#101064]">Attendance Sessions</h2>
                    <p class="mt-1 text-xs leading-6 text-slate-500">Select an event to see its attendance records and live RFID activity.</p>
                </div>
                <label class="w-full sm:w-auto" for="monitor-event-search">
                    <span class="sr-only">Search attendance events</span>
                    <input id="monitor-event-search" class="dy-registry-search" type="search" placeholder="Search events or venues..." autocomplete="off">
                </label>
            </div>
            <div class="dy-tabs" role="group" aria-label="Filter events by status">
                <button type="button" class="dy-tab" data-monitor-tab="all" aria-pressed="true">All Events ({{ $eventEntries->count() }})</button>
                <button type="button" class="dy-tab" data-monitor-tab="Ongoing" aria-pressed="false">Ongoing ({{ $scheduledCounts->get('Ongoing', 0) }})</button>
                <button type="button" class="dy-tab" data-monitor-tab="Upcoming" aria-pressed="false">Upcoming ({{ $scheduledCounts->get('Upcoming', 0) }})</button>
                <button type="button" class="dy-tab" data-monitor-tab="Completed" aria-pressed="false">Completed ({{ $scheduledCounts->get('Completed', 0) }})</button>
            </div>
            <div class="dy-event-list" id="monitor-event-list">
                @forelse($eventEntries as $entry)
                    @php
                        $event = $entry['event'];
                        $status = $entry['status'];
                        $date = \Carbon\Carbon::parse($event->event_date);
                    @endphp
                    <article class="dy-event-row" data-monitor-event data-status="{{ $status }}" data-search="{{ mb_strtolower($event->event_name . ' ' . $event->location . ' ' . $event->event_id) }}">
                        <div class="dy-event-main">
                            <div class="dy-date-tile" aria-label="{{ $date->format('F j, Y') }}">
                                <div class="dy-date-month">{{ $date->format('M') }}</div>
                                <div class="dy-date-day">{{ $date->format('d') }}</div>
                                <div class="dy-date-year">{{ $date->format('Y') }}</div>
                            </div>
                            <div class="min-w-0">
                                <div class="mb-2 flex flex-wrap gap-2">
                                    <span class="dy-badge {{ strtolower($status) }}">{{ $status }}</span>
                                    <span class="dy-badge gold">Event #{{ $event->event_id }}</span>
                                    @if($status === 'Ongoing')<span class="dy-badge ongoing"><span class="dy-feed-pulse"></span> Live session</span>@endif
                                </div>
                                <h3 class="break-words text-[15px] font-extrabold text-[#101064]">{{ $event->event_name }}</h3>
                                @if($event->description)
                                    <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 130) }}</p>
                                @endif
                                <div class="dy-event-meta">
                                    <span><b aria-hidden="true">▦</b> {{ $date->format('M j, Y') }}</span>
                                    <span><b aria-hidden="true">◷</b> {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</span>
                                    <span><b aria-hidden="true">⌖</b> {{ $event->location ?: 'Venue not set' }}</span>
                                </div>
                            </div>
                            <div class="dy-event-action">
                                <a href="{{ route('attendance.monitor', ['event_id' => $event->event_id]) }}" class="dy-action-btn">{{ $status === 'Ongoing' ? 'Monitor Live' : 'View Attendance' }} <span class="ml-2" aria-hidden="true">→</span></a>
                                <p class="mt-2 text-center text-[10px] text-slate-400">{{ $status === 'Ongoing' ? 'Auto-updates every 5 seconds' : 'View-only attendance records' }}</p>
                            </div>
                        </div>
                        <div class="dy-event-foot">{{ $status === 'Ongoing' ? 'Attendance may be happening now. New RFID scans will appear automatically when you open this session.' : ($status === 'Upcoming' ? 'Scheduled event. Attendance activity will be available when authorized scans begin.' : 'Past event. Review recorded check-ins and check-outs.') }}</div>
                    </article>
                @empty
                    <p class="py-12 text-center text-sm text-slate-500">No events are available for attendance monitoring.</p>
                @endforelse
            </div>
            <div id="monitor-no-matches" class="hidden px-5 py-12 text-center text-sm text-slate-500" role="status">No events match your search or selected status.</div>
            <div class="dy-pager" id="monitor-directory-pager">
                <span id="monitor-directory-count" class="mr-auto">Showing attendance sessions</span>
                <button type="button" id="monitor-directory-prev">Previous</button>
                <span id="monitor-directory-pages" aria-live="polite">Page 1</span>
                <button type="button" id="monitor-directory-next">Next</button>
            </div>
        </section>
    @else
        {{-- Event detail and connectivity indicator --}}
        <section id="monitor-live" data-feed-url="{{ route('attendance.monitor.feed', $selectedEvent->event_id) }}" class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div><p class="dy-kicker">Selected Attendance Session</p><h2 class="mt-1 text-xl font-extrabold text-[#101064]">Event Attendance</h2></div>
                <a href="{{ route('attendance.monitor') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-[#101064] hover:border-[#D4A017]">← All Attendance Sessions</a>
            </div>
            <div class="dy-registry-panel">
                <div class="dy-event-main">
                    <div class="dy-date-tile">
                        <div class="dy-date-month">{{ \Carbon\Carbon::parse($selectedEvent->event_date)->format('M') }}</div>
                        <div class="dy-date-day">{{ \Carbon\Carbon::parse($selectedEvent->event_date)->format('d') }}</div>
                        <div class="dy-date-year">{{ \Carbon\Carbon::parse($selectedEvent->event_date)->format('Y') }}</div>
                    </div>
                    <div class="min-w-0">
                        <div class="mb-2 flex flex-wrap items-center gap-2">
                            <span id="monitor-event-status" data-status="{{ $eventStatus }}" class="dy-badge {{ strtolower($eventStatus) }}">{{ $eventStatus }}</span>
                            <span class="dy-badge gold">Event #{{ $selectedEvent->event_id }}</span>
                        </div>
                        <h3 class="break-words text-lg font-extrabold text-[#101064]">{{ $selectedEvent->event_name }}</h3>
                        <div class="dy-event-meta">
                            <span><b aria-hidden="true">▦</b> {{ \Carbon\Carbon::parse($selectedEvent->event_date)->format('M j, Y') }}</span>
                            <span><b aria-hidden="true">◷</b> {{ \Carbon\Carbon::parse($selectedEvent->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($selectedEvent->end_time)->format('g:i A') }}</span>
                            <span><b aria-hidden="true">⌖</b> {{ $selectedEvent->location ?: 'Venue not set' }}</span>
                        </div>
                    </div>
                    <div class="dy-event-action flex flex-col items-start gap-2 lg:items-end">
                        <span class="inline-flex items-center gap-2 rounded-lg border border-emerald-100 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700"><span id="monitor-pulse" class="dy-feed-pulse" data-state="sync"></span><span id="monitor-connection-label">Connecting…</span></span>
                        <a href="{{ route('events.show', $selectedEvent->event_id) }}" class="text-[11px] font-bold text-[#101064] hover:underline">View Event Details ↗</a>
                    </div>
                </div>
                <div class="dy-event-foot">Read-only Administrator view · Attendance data refreshes automatically every 5 seconds while this tab is open.</div>
            </div>

            {{-- Full-event counts (not just latest 50 records) --}}
            <section aria-labelledby="monitor-overview-heading">
                <div class="mb-4">
                    <p class="dy-kicker">Attendance Overview</p>
                    <h2 id="monitor-overview-heading" class="mt-2 text-xl font-extrabold text-[#101064]">Live Attendance Summary</h2>
                    <p class="mt-1 text-sm text-slate-500">Full-event totals directly from recorded attendance data.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="dy-card dy-stat"><p>Total Recorded</p><strong id="attendance_count" class="text-[#101064]">{{ $attendanceCount }}</strong><div class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Students with attendance records</div></article>
                    <article class="dy-card dy-stat"><p>Checked In</p><strong id="checked_in_count" class="text-sky-700">{{ $checkedInCount }}</strong><div class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Awaiting check-out</div></article>
                    <article class="dy-card dy-stat"><p>Checked Out</p><strong id="checked_out_count" class="text-emerald-600">{{ $checkedOutCount }}</strong><div class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Completed RFID exit scans</div></article>
                    <article class="dy-card dy-stat"><p>Late Records</p><strong id="late_count" class="text-amber-600">{{ $lateCount }}</strong><div class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500"><span id="present_count">{{ $presentCount }}</span> marked Present</div></article>
                </div>
            </section>

            {{-- Most recent student activity, with checkout-aware ordering --}}
            <section class="grid min-w-0 gap-4 xl:grid-cols-[1.45fr_1fr]" aria-label="Latest attendance and live status">
                <div class="dy-card overflow-hidden">
                    <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                        <div><p class="dy-kicker">Most Recent Scan</p><h3 class="mt-1 text-lg font-extrabold text-[#101064]">Latest RFID Activity</h3></div>
                        <span class="rounded-full bg-indigo-50 px-3 py-1.5 text-[11px] font-bold text-[#101064]">Live feed</span>
                    </div>
                    <div id="latest_attendance_container" class="flex min-h-[240px] items-center px-5 py-7 sm:px-7" aria-live="polite">
                        @if($latestAttendance)
                            <div class="flex w-full flex-wrap items-center gap-5">
                                <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-indigo-50 text-3xl font-extrabold text-[#101064]">
                                    @if($latestAttendance->photo_path)
                                        <img class="h-full w-full object-cover" src="{{ asset('student_photos/' . basename($latestAttendance->photo_path)) }}" alt="Student photo">
                                    @else
                                        {{ strtoupper(substr($latestAttendance->first_name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="dy-kicker">{{ $latestAttendance->time_out ? 'Latest Check-out' : 'Latest Check-in' }}</p>
                                    <h4 class="mt-1 break-words text-xl font-extrabold text-[#101064]">{{ $latestName }}</h4>
                                    <p class="mt-2 text-xs text-slate-500">{{ $latestAttendance->student_number }} · {{ $latestAttendance->program_code }} · Year {{ $latestAttendance->year_level }}</p>
                                    <p class="mt-3 text-xs font-semibold text-slate-700">{{ $latestAttendance->status }} · {{ $latestAttendance->time_out ? \Carbon\Carbon::parse($latestAttendance->time_out)->format('h:i A') : \Carbon\Carbon::parse($latestAttendance->time_in)->format('h:i A') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="mx-auto py-7 text-center"><p class="text-base font-extrabold text-[#101064]">No attendance scans yet</p><p class="mt-2 text-sm text-slate-500">Student details will appear here after a check-in or check-out scan.</p></div>
                        @endif
                    </div>
                </div>
                <div class="dy-card flex flex-col p-5 sm:p-6">
                    <p class="dy-kicker">Monitoring Connection</p>
                    <h3 class="mt-2 text-lg font-extrabold text-[#101064]">Real-Time Activity Overview</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">This screen refreshes every 5 seconds while the tab is active. New check-in and check-out scans appear automatically.</p>
                    <div class="mt-5 space-y-3 rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <div class="flex justify-between gap-3 text-xs"><span class="text-slate-500">Feed status</span><strong id="monitor-feed-state" class="text-[#101064]">Connecting</strong></div>
                        <div class="flex justify-between gap-3 text-xs"><span class="text-slate-500">Last successful sync</span><strong id="monitor-last-sync" class="text-[#101064]">—</strong></div>
                        <div class="flex justify-between gap-3 text-xs"><span class="text-slate-500">Latest activities displayed</span><strong id="monitor-records-count" class="text-[#101064]">{{ $attendanceRecords->count() }} / 50 max</strong></div>
                    </div>
                    <div class="mt-auto pt-5 text-[11px] leading-5 text-slate-500">Monitor only. RFID check-in and check-out are performed by authorized Attendance Personnel, subject to their scanner time windows.</div>
                    <button type="button" id="monitor-refresh" class="mt-4 inline-flex items-center justify-center gap-2 rounded-xl border border-[#101064]/20 bg-white px-4 py-2.5 text-xs font-bold text-[#101064] transition hover:bg-indigo-50">Refresh Now <span aria-hidden="true">↻</span></button>
                </div>
            </section>

            {{-- Latest 50 activity records, with client-side filtering --}}
            <section aria-labelledby="monitor-feed-heading">
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div><p class="dy-kicker">Attendance Registry</p><h2 id="monitor-feed-heading" class="mt-2 text-xl font-extrabold text-[#101064]">Recent RFID Activity</h2><p class="mt-1 text-sm text-slate-500">Recent check-ins and check-outs, displayed as student activity cards.</p></div>
                    <a href="{{ route('participation.records') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-[#101064] transition hover:border-[#D4A017]">Full Participation Records ↗</a>
                </div>
                <div class="dy-card overflow-hidden">
                    <div class="flex flex-wrap gap-3 border-b border-slate-100 p-4 sm:p-5">
                        <div class="min-w-[200px] flex-1"><label for="monitor-search" class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Search recent records</label><input id="monitor-search" type="search" placeholder="Name or student number..." autocomplete="off" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]/20"></div>
                        <div class="min-w-[160px]"><label for="monitor-filter" class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Attendance Status</label><select id="monitor-filter" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]/20"><option value="all">All statuses</option><option value="Checked In">Checked In</option><option value="Present">Present</option><option value="Late">Late</option><option value="Absent">Absent</option><option value="Excused">Excused</option></select></div>
                    </div>
                    <div id="attendance_table_body" class="dy-activity-list" aria-live="polite">
                        @forelse($attendanceRecords->take(8) as $record)
                            @php $recordName = trim(collect([$record->first_name, $record->middle_name, $record->last_name])->filter()->implode(' ')); @endphp
                            <article class="dy-activity-item">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-indigo-50 font-extrabold text-[#101064]">
                                        @if($record->photo_path)<img src="{{ asset('student_photos/' . basename($record->photo_path)) }}" alt="" class="h-full w-full object-cover">@else {{ strtoupper(substr($record->first_name, 0, 1)) }} @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="break-words text-sm font-extrabold text-[#101064]">{{ $recordName }}</p>
                                        <p class="mt-1 text-[11px] text-slate-500">{{ $record->student_number }} · {{ $record->program_code }} · Year {{ $record->year_level }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                    <span class="dy-badge {{ $record->time_out ? 'completed' : 'upcoming' }}">{{ $record->time_out ? 'Check-out' : 'Check-in' }}</span>
                                    <span class="dy-badge {{ $record->status === 'Late' ? 'gold' : ($record->status === 'Present' ? 'ongoing' : 'upcoming') }}">{{ $record->status }}</span>
                                </div>
                                <div class="dy-activity-foot col-span-full">
                                    <span>Time In: {{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('g:i A') : '—' }}</span>
                                    <span>Time Out: {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('g:i A') : '—' }}</span>
                                    <span>Scanned by: {{ $record->scanned_by_name ?: 'Unknown' }}</span>
                                </div>
                            </article>
                        @empty
                            <div class="py-14 text-center text-sm text-slate-500">No attendance records have been scanned for this event.</div>
                        @endforelse
                    </div>
                    <div class="dy-pager" id="monitor-activity-pager">
                        <span id="monitor-activity-page" class="mr-auto">Page 1</span>
                        <button type="button" id="monitor-activity-prev">Previous</button>
                        <button type="button" id="monitor-activity-next">Next</button>
                    </div>
                    <div class="flex flex-wrap justify-between gap-2 border-t border-slate-100 bg-slate-50/60 px-5 py-4 text-xs text-slate-500"><span id="monitor-showing">Showing latest {{ $attendanceRecords->count() }} records</span><span>Latest 50 only · full-event totals shown above · refresh every 5 seconds</span></div>
                </div>
            </section>
        </section>
    @endif
</div>

@if(!$selectedEvent)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const directory = document.getElementById('monitor-directory');
    if (!directory) return;
    const search = document.getElementById('monitor-event-search');
    const items = Array.from(directory.querySelectorAll('[data-monitor-event]'));
    const tabs = Array.from(directory.querySelectorAll('[data-monitor-tab]'));
    const previous = document.getElementById('monitor-directory-prev');
    const next = document.getElementById('monitor-directory-next');
    const counter = document.getElementById('monitor-directory-count');
    const pages = document.getElementById('monitor-directory-pages');
    const noMatches = document.getElementById('monitor-no-matches');
    let status = 'all';
    let page = 1;
    const perPage = 8;
    function draw() {
        const term = search.value.toLocaleLowerCase().trim();
        const matches = items.filter(function (item) {
            return (status === 'all' || item.dataset.status === status) && (item.dataset.search || '').includes(term);
        });
        const totalPages = Math.max(1, Math.ceil(matches.length / perPage));
        page = Math.min(page, totalPages);
        const begin = (page - 1) * perPage;
        const visible = matches.slice(begin, begin + perPage);
        items.forEach(function (item) { item.hidden = !visible.includes(item); });
        noMatches.classList.toggle('hidden', matches.length !== 0 || items.length === 0);
        counter.textContent = matches.length ? 'Showing ' + (begin + 1) + '–' + (begin + visible.length) + ' of ' + matches.length + ' events' : '0 matching events';
        pages.textContent = 'Page ' + page + ' of ' + totalPages;
        previous.disabled = page === 1;
        next.disabled = page >= totalPages;
    }
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            status = tab.dataset.monitorTab;
            page = 1;
            tabs.forEach(function (other) { other.setAttribute('aria-pressed', String(tab === other)); });
            draw();
        });
    });
    search.addEventListener('input', function () { page = 1; draw(); });
    previous.addEventListener('click', function () { if (page > 1) { page--; draw(); } });
    next.addEventListener('click', function () { page++; draw(); });
    draw();
});
</script>
@endif

@if($selectedEvent)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const monitor = document.getElementById('monitor-live');
    if (!monitor) return;

    const feedUrl = monitor.dataset.feedUrl;
    const tableBody = document.getElementById('attendance_table_body');
    const searchInput = document.getElementById('monitor-search');
    const filterInput = document.getElementById('monitor-filter');
    const refreshButton = document.getElementById('monitor-refresh');
    let records = [];
    let activityPage = 1;
    const activityPerPage = 8;
    let lastActivityKey = null;
    let initialized = false;
    let isFetching = false;
    let pollingTimer = null;

    function safe(value) {
        return String(value ?? '').replaceAll('&', '&amp;').replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#39;');
    }

    function safePhoto(url) {
        if (!url) return '';
        try {
            const parsed = new URL(url, window.location.origin);
            return ['http:', 'https:'].includes(parsed.protocol)
                ? safe(parsed.href) : '';
        } catch (_) { return ''; }
    }

    function pill(status) {
        const palette = {
            'Present': 'bg-emerald-50 text-emerald-700',
            'Late': 'bg-amber-50 text-amber-700',
            'Checked In': 'bg-blue-50 text-blue-700',
            'Excused': 'bg-violet-50 text-violet-700',
            'Absent': 'bg-rose-50 text-rose-700'
        };
        return '<span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold ' +
            (palette[status] || 'bg-slate-100 text-slate-600') + '">' + safe(status || 'Unknown') + '</span>';
    }

    function avatar(record, large) {
        const size = large ? 'h-20 w-20 rounded-2xl text-3xl' : 'h-9 w-9 rounded-xl text-sm';
        const photo = safePhoto(record.photo_url);
        return '<div class="flex shrink-0 items-center justify-center overflow-hidden bg-indigo-50 font-extrabold text-[#101064] ' + size + '">' +
            (photo ? '<img src="' + photo + '" alt="" class="h-full w-full object-cover">' : safe(record.initial || '?')) + '</div>';
    }

    function renderLatest(record) {
        const target = document.getElementById('latest_attendance_container');
        if (!record) {
            target.innerHTML = '<div class="mx-auto py-7 text-center"><p class="text-base font-extrabold text-[#101064]">No attendance scans yet</p><p class="mt-2 text-sm text-slate-500">Student details will appear here after a check-in or check-out scan.</p></div>';
            return;
        }
        target.innerHTML = '<div class="flex w-full flex-wrap items-center gap-5">' + avatar(record, true) +
            '<div class="min-w-0 flex-1"><p class="dy-kicker">Latest ' + safe(record.activity_type) + '</p>' +
            '<h4 class="mt-1 break-words text-xl font-extrabold text-[#101064]">' + safe(record.name) + '</h4>' +
            '<p class="mt-2 text-xs text-slate-500">' + safe(record.student_number) + ' · ' + safe(record.program_code) + ' · Year ' + safe(record.year_level) + '</p>' +
            '<div class="mt-3 flex flex-wrap items-center gap-3">' + pill(record.status) +
            '<span class="text-xs font-semibold text-slate-600">' + safe(record.activity_time) + '</span></div></div></div>';
    }

    function renderRows() {
        const search = searchInput.value.toLocaleLowerCase().trim();
        const status = filterInput.value;
        const filtered = records.filter(function (r) {
            return (String(r.name || '') + ' ' + String(r.student_number || '')).toLocaleLowerCase().includes(search)
                && (status === 'all' || r.status === status);
        });
        const pageCount = Math.max(1, Math.ceil(filtered.length / activityPerPage));
        activityPage = Math.min(activityPage, pageCount);
        const start = (activityPage - 1) * activityPerPage;
        const visible = filtered.slice(start, start + activityPerPage);
        if (!visible.length) {
            tableBody.innerHTML = '<div class="py-14 text-center text-sm text-slate-500">' +
                (records.length ? 'No recent records match your search.' : 'No attendance scans yet for this event.') + '</div>';
        } else {
            tableBody.innerHTML = visible.map(function (r) {
                const badge = r.time_out && r.time_out !== '—' ? 'completed' : 'upcoming';
                const statusBadge = r.status === 'Late' ? 'gold' : (r.status === 'Present' ? 'ongoing' : 'upcoming');
                return '<article class="dy-activity-item"><div class="flex min-w-0 items-center gap-3">' + avatar(r, false) +
                    '<div class="min-w-0"><p class="break-words text-sm font-extrabold text-[#101064]">' + safe(r.name) + '</p>' +
                    '<p class="mt-1 text-[11px] text-slate-500">' + safe(r.student_number) + ' · ' + safe(r.program_code) + ' · Year ' + safe(r.year_level) + '</p></div></div>' +
                    '<div class="flex flex-wrap items-center gap-2 sm:justify-end"><span class="dy-badge ' + badge + '">' + safe(r.activity_type) +
                    '</span><span class="dy-badge ' + statusBadge + '">' + safe(r.status || 'Unknown') + '</span></div>' +
                    '<div class="dy-activity-foot col-span-full"><span>Time In: ' + safe(r.time_in) + '</span>' +
                    '<span>Time Out: ' + safe(r.time_out) + '</span><span>Scanned by: ' + safe(r.scanned_by) + '</span></div></article>';
            }).join('');
        }
        document.getElementById('monitor-showing').textContent = filtered.length ?
            'Showing ' + (start + 1) + '–' + (start + visible.length) + ' of ' + filtered.length + ' recent records' : 'No matching records';
        document.getElementById('monitor-activity-page').textContent = 'Page ' + activityPage + ' of ' + pageCount;
        document.getElementById('monitor-activity-prev').disabled = activityPage <= 1;
        document.getElementById('monitor-activity-next').disabled = activityPage >= pageCount;
    }

    function setConnection(state, label) {
        document.getElementById('monitor-pulse').dataset.state = state;
        document.getElementById('monitor-connection-label').textContent = label;
        document.getElementById('monitor-feed-state').textContent = label;
    }

    function setEventStatus(status) {
        const badge = document.getElementById('monitor-event-status');
        badge.textContent = status || 'Unknown';
        badge.dataset.status = status || 'Unknown';
        badge.className = 'dy-badge ' + (status === 'Ongoing' ? 'ongoing' : status === 'Upcoming' ? 'upcoming' : 'completed');
    }

    async function refreshAttendance() {
        if (isFetching) return;
        isFetching = true;
        refreshButton.disabled = true;
        try {
            const response = await fetch(feedUrl, {headers: {'Accept': 'application/json'}, cache: 'no-store'});
            if (!response.ok) throw new Error('The attendance feed returned HTTP ' + response.status);
            const data = await response.json();
            records = Array.isArray(data.records) ? data.records : [];
            document.getElementById('attendance_count').textContent = Number(data.attendance_count || 0).toLocaleString();
            document.getElementById('checked_in_count').textContent = Number(data.checked_in_count || 0).toLocaleString();
            document.getElementById('checked_out_count').textContent = Number(data.checked_out_count || 0).toLocaleString();
            document.getElementById('present_count').textContent = Number(data.present_count || 0).toLocaleString();
            document.getElementById('late_count').textContent = Number(data.late_count || 0).toLocaleString();
            document.getElementById('monitor-records-count').textContent = records.length + ' / 50 max';
            document.getElementById('monitor-last-sync').textContent = data.server_time || 'Just now';
            setEventStatus(data.event_status);
            setConnection('live', 'Connected · Updating every 5s');

            const latest = data.latest || null;
            const nextKey = latest ? String(latest.attendance_id) + '|' + String(latest.activity_at || '') : null;
            // Only highlight actual changes after the initial feed connection.
            const newActivity = initialized && nextKey && lastActivityKey && nextKey !== lastActivityKey;
            if (newActivity) {
                document.getElementById('latest_attendance_container').classList.add('dy-new-row');
            } else {
                document.getElementById('latest_attendance_container').classList.remove('dy-new-row');
            }
            lastActivityKey = nextKey;
            initialized = true;
            renderLatest(latest);
            renderRows();
        } catch (error) {
            setConnection('error', 'Connection lost · Retrying');
            console.warn('DySign attendance monitoring feed:', error);
        } finally {
            isFetching = false;
            refreshButton.disabled = false;
        }
    }

    function scheduleRefresh() {
        if (pollingTimer) clearTimeout(pollingTimer);
        pollingTimer = setTimeout(async function tick() {
            if (!document.hidden) await refreshAttendance();
            scheduleRefresh();
        }, 5000);
    }

    searchInput.addEventListener('input', function () { activityPage = 1; renderRows(); });
    filterInput.addEventListener('change', function () { activityPage = 1; renderRows(); });
    document.getElementById('monitor-activity-prev').addEventListener('click', function () { if (activityPage > 1) { activityPage--; renderRows(); } });
    document.getElementById('monitor-activity-next').addEventListener('click', function () { activityPage++; renderRows(); });
    refreshButton.addEventListener('click', refreshAttendance);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) { refreshAttendance(); scheduleRefresh(); }
    });
    refreshAttendance();
    scheduleRefresh();
});
</script>
@endif
</x-admin-layout>
