<x-admin-layout>
@php
    $staff = auth()->user();
    $staffName = trim((string) ($staff?->name ?? 'Department Staff')) ?: 'Department Staff';
    $staffInitials = collect(preg_split('/\s+/', $staffName))->filter()->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('') ?: 'DS';
    $departmentLabel = (string) ($departmentName ?? 'No Department Assigned');
    $totalEvents = (int) ($totalEvents ?? 0);
    $upcomingEventsCount = (int) ($upcomingEventsCount ?? 0);
    $ongoingEventsCount = (int) ($ongoingEventsCount ?? 0);
    $completedEventsCount = (int) ($completedEventsCount ?? 0);
    $cancelledEventsCount = (int) ($cancelledEventsCount ?? 0);
    $recentEvents = collect($recentEvents ?? []);

    // This collection is prepared by DashboardController with the authenticated
    // user's department_id. Never query the school-wide event registry here.
    $calendarEvents = collect($calendarEvents ?? []);
    $calendarData = $calendarEvents->filter(fn ($event) => !empty($event->event_date))
        ->map(function ($event) {
            return [
                'id' => $event->event_id,
                'name' => (string) ($event->event_name ?? 'Untitled event'),
                'date' => \Carbon\Carbon::parse($event->event_date)->format('Y-m-d'),
                'time' => $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : 'Time TBA',
                'end_time' => $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : null,
                'location' => (string) ($event->location ?: 'Venue to be announced'),
                'status' => (string) ($event->status ?? 'Unknown'),
                'url' => route('events.show', $event->event_id),
            ];
        })->values();

    $todayDate = now()->toDateString();
    // Ongoing first, then scheduled future events; fill remaining agenda slots
    // with the most recently dated department records, without duplicates.
    $activeAgenda = $calendarData->filter(function ($item) use ($todayDate) {
        $status = strtolower($item['status']);
        return $status === 'ongoing' || ($status === 'upcoming' && $item['date'] >= $todayDate);
    })->sortBy(fn ($item) => (strtolower($item['status']) === 'ongoing' ? '0' : '1') . $item['date'] . $item['time'])->values();

    $fallbackAgenda = $calendarData->reject(fn ($item) => $activeAgenda->contains('id', $item['id']))
        ->sortByDesc('date')->values();
    $agenda = $activeAgenda->concat($fallbackAgenda)->take(5)->values();
    $calendarMonthCount = $calendarData->filter(fn ($item) => str_starts_with($item['date'], now()->format('Y-m')))->count();
    $completedRate = $totalEvents > 0 ? min(100, round(($completedEventsCount / $totalEvents) * 100)) : 0;

    $tools = [
        ['name' => 'Event Management', 'description' => 'Create and coordinate department events', 'route' => 'events.index', 'icon' => 'calendar'],
        ['name' => 'Participant Eligibility', 'description' => 'Review required and optional attendees', 'route' => 'events.eligibility.events', 'icon' => 'users'],
        ['name' => 'Personnel Assignment', 'description' => 'Manage event staff responsibilities', 'route' => 'events.personnel-assignment.events', 'icon' => 'person'],
        ['name' => 'Participant Registration', 'description' => 'Monitor event registration records', 'route' => 'events.registrations.index', 'icon' => 'clipboard'],
        ['name' => 'Attendance Requests', 'description' => 'Review submitted attendance exceptions', 'route' => 'staff.attendance-exceptions.index', 'icon' => 'review'],
        ['name' => 'Department Reports', 'description' => 'Attendance and participation insights', 'route' => 'staff.reports.index', 'icon' => 'chart'],
    ];
@endphp

<style>
    .dy-department-hero {
        background-image: linear-gradient(102deg, rgba(12,13,79,.97) 0%, rgba(16,16,100,.90) 54%, rgba(16,16,100,.72) 100%), url('{{ asset('images/school.jpg') }}');
        background-size: cover; background-position: center;
    }
    .dy-dept-calendar-cell { min-height: 92px; transition: background-color .15s ease; }
    .dy-dept-calendar-cell:hover { background: #f8f9ff; }
    .dy-dept-action { transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease; }
    .dy-dept-action:hover { border-color: #c7c9e8; box-shadow: 0 10px 26px -20px rgba(16,16,100,.32); transform: translateY(-2px); }
    .dy-dept-scroll::-webkit-scrollbar { height: 6px; }
    .dy-dept-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
    /* Keep calendar event dialogs inside the viewport even on days with many events.
       Explicit CSS avoids relying on dynamically generated utility classes. */
    #dyDeptDayModal { box-sizing: border-box; overflow-y: auto; overscroll-behavior: contain; padding: 16px; }
    #dyDeptDayModal .dy-dept-dialog-panel {
        box-sizing: border-box;
        display: grid;
        grid-template-rows: auto minmax(0, 1fr) auto;
        width: min(100%, 660px);
        max-height: min(760px, calc(100vh - 32px));
        max-height: min(760px, calc(100dvh - 32px));
        margin: auto;
        overflow: hidden;
    }
    #dyDeptModalEvents {
        min-height: 0;
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #ffffff;
    }
    #dyDeptModalEvents::-webkit-scrollbar { width: 8px; }
    #dyDeptModalEvents::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    @media(max-width:639px) {
        .dy-dept-calendar-cell { min-height: 76px; }
        #dyDeptDayModal { padding: 10px; }
        #dyDeptDayModal .dy-dept-dialog-panel {
            max-height: calc(100vh - 20px);
            max-height: calc(100dvh - 20px);
        }
    }
</style>

<div class="min-w-0 space-y-7 pb-8">
    {{-- Identical visual language to the Administrator / Student Dashboard. --}}
    <section class="dy-department-hero relative isolate overflow-hidden rounded-[28px] bg-[#101064] px-6 py-8 text-white shadow-sm sm:px-9 sm:py-10" aria-labelledby="dept-welcome-title">
        <div class="pointer-events-none absolute -right-16 -top-24 h-80 w-80 rounded-full border border-white/10"></div>
        <div class="pointer-events-none absolute -right-4 -top-12 h-60 w-60 rounded-full border border-white/10"></div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-28 w-80 bg-gradient-to-l from-[#D4A017]/20 to-transparent"></div>
        <div class="relative flex flex-col items-start justify-between gap-7 lg:flex-row lg:items-center">
            <div class="min-w-0 max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[.25em] text-[#E7C75B]">DySign · Department Staff Portal</p>
                <h1 id="dept-welcome-title" class="mt-3 break-words text-3xl font-bold tracking-tight sm:text-4xl">Welcome, {{ $staffName }}!</h1>
                <p class="mt-3 max-w-xl text-sm leading-6 text-white/75">Your department events, schedules, personnel coordination, and student participation at a glance.</p>
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-medium text-white/90">
                        <svg class="h-4 w-4 text-[#E7C75B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18"/></svg>
                        {{ now()->format('l, F d, Y') }}
                    </span>
                    <span class="inline-flex max-w-full items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-medium text-white/90"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-300"></span><span class="truncate">{{ $departmentLabel }}</span></span>
                </div>
            </div>
            <div class="flex w-full shrink-0 flex-wrap items-center gap-3 lg:w-auto">
                <div class="hidden items-center gap-3 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-sm sm:flex">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/20 bg-white/15 text-sm font-bold">{{ $staffInitials }}</div>
                    <div class="min-w-0"><p class="max-w-[180px] truncate text-sm font-semibold">{{ $staffName }}</p><p class="mt-0.5 max-w-[180px] truncate text-xs text-white/65">Department Staff</p></div>
                </div>
                <a href="{{ route('events.index') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#D4A017] px-5 py-3 text-sm font-bold text-[#101064] transition hover:bg-[#E7C75B]">Manage Events <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    {{-- KPI cards: same style, but only data from the signed-in department. --}}
    <section class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-4" aria-label="Department event overview">
        @foreach([
            ['label' => 'Total Events', 'value' => $totalEvents, 'desc' => 'Registered department events', 'color' => 'indigo'],
            ['label' => 'Upcoming', 'value' => $upcomingEventsCount, 'desc' => 'Scheduled activities', 'color' => 'amber'],
            ['label' => 'Ongoing', 'value' => $ongoingEventsCount, 'desc' => 'Currently active sessions', 'color' => 'emerald'],
            ['label' => 'Completed', 'value' => $completedEventsCount, 'desc' => 'Finished event records', 'color' => 'slate'],
        ] as $metric)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div><p class="text-sm font-medium text-slate-500">{{ $metric['label'] }}</p><p class="mt-3 text-3xl font-bold tracking-tight text-[#101064]">{{ number_format($metric['value']) }}</p></div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ match($metric['color']) { 'amber' => 'bg-amber-50 text-amber-700', 'emerald' => 'bg-emerald-50 text-emerald-700', 'slate' => 'bg-slate-100 text-slate-600', default => 'bg-indigo-50 text-[#101064]' } }}">
                        @if($metric['color'] === 'amber')
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        @elseif($metric['color'] === 'emerald')
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M13 2 5 13h6l-1 9 9-12h-6l1-8Z" stroke-linejoin="round"/></svg>
                        @elseif($metric['color'] === 'slate')
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg>
                        @else
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18"/></svg>
                        @endif
                    </div>
                </div>
                <p class="mt-4 text-xs leading-5 text-slate-500">{{ $metric['desc'] }}</p>
            </div>
        @endforeach
    </section>

    {{-- Main section mirrors Administrator: interactive calendar + full-height agenda. --}}
    <div class="grid min-w-0 gap-6 xl:grid-cols-[minmax(0,1.9fr)_minmax(280px,0.85fr)]">
        <section class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="dept-calendar-heading">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div><p class="text-[11px] font-semibold uppercase tracking-[.22em] text-[#A9780B]">Department Schedule</p><h2 id="dept-calendar-heading" class="mt-1.5 text-lg font-bold text-[#101064]">Events Calendar</h2><p class="mt-1 text-xs text-slate-500">Select a date or event name to review details.</p></div>
                <div class="flex shrink-0 items-center gap-2">
                    <button id="dyDeptToday" type="button" class="inline-flex h-10 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-[#101064] hover:bg-slate-50">Today</button>
                    <button id="dyDeptPrev" type="button" aria-label="Previous month" class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-[#101064] hover:bg-slate-50">‹</button>
                    <button id="dyDeptNext" type="button" aria-label="Next month" class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-[#101064] hover:bg-slate-50">›</button>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-5 py-3 sm:px-6">
                <h3 id="dyDeptMonth" class="text-sm font-bold text-[#101064]" aria-live="polite">{{ now()->format('F Y') }}</h3>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-medium text-slate-500"><span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#101064]"></span>Today</span><span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#D4A017]"></span>Event scheduled</span></div>
            </div>
            <div class="dy-dept-scroll max-w-full overflow-x-auto">
                <div class="min-w-[650px]">
                    <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $weekday)
                            <div class="border-r border-slate-100 px-1 py-3 last:border-r-0">{{ $weekday }}</div>
                        @endforeach
                    </div>
                    <div id="dyDeptCalendarGrid" class="grid grid-cols-7" aria-label="Department calendar dates"></div>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 px-5 py-4 text-xs text-slate-500 sm:px-6"><span>Department events in <span id="dyDeptMonthLabel">{{ now()->format('F Y') }}</span></span><span class="rounded-full bg-indigo-50 px-3 py-1 font-semibold text-[#101064]"><span id="dyDeptMonthCount">{{ $calendarMonthCount }}</span> events</span></div>
        </section>

        <section class="flex min-w-0 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="dept-agenda-heading">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-5 sm:px-6"><div><p class="text-[11px] font-semibold uppercase tracking-[.22em] text-[#A9780B]">Active, Upcoming &amp; Recent</p><h2 id="dept-agenda-heading" class="mt-1.5 text-lg font-bold text-[#101064]">Event Agenda</h2></div><span class="shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-[#101064]">{{ $agenda->count() }} of {{ $totalEvents }} shown</span></div>
            <div class="flex flex-1 flex-col">
                @forelse($agenda as $event)
                    <a href="{{ $event['url'] }}" class="flex min-h-[90px] flex-1 items-center gap-3 border-b border-slate-100 px-5 py-4 transition hover:bg-slate-50 sm:px-6">
                        <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50/70 text-[#101064]"><span class="text-[10px] font-bold uppercase tracking-wider">{{ \Carbon\Carbon::parse($event['date'])->format('M') }}</span><span class="text-xl font-bold leading-6">{{ \Carbon\Carbon::parse($event['date'])->format('d') }}</span></div>
                        <div class="min-w-0 flex-1"><p class="break-words text-sm font-semibold leading-5 text-slate-800">{{ $event['name'] }}</p><p class="mt-1 truncate text-xs text-slate-500">{{ $event['location'] }}</p><p class="mt-2 flex flex-wrap items-center gap-1.5 text-[11px] text-slate-500"><span class="h-1.5 w-1.5 rounded-full {{ strtolower($event['status']) === 'ongoing' ? 'bg-emerald-500' : 'bg-[#D4A017]' }}"></span>{{ $event['time'] }} <span class="text-slate-300">·</span> {{ $event['status'] }}</p></div>
                    </a>
                @empty
                    <div class="flex flex-1 flex-col items-center justify-center px-7 py-14 text-center"><div class="rounded-2xl bg-indigo-50 p-4 text-[#101064]"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18"/></svg></div><h3 class="mt-4 text-sm font-bold text-[#101064]">No department events yet</h3><p class="mt-2 text-xs leading-5 text-slate-500">Your department's events will appear here after they are scheduled.</p></div>
                @endforelse
            </div>
            <div class="border-t border-slate-100 bg-slate-50/60 px-5 py-4 sm:px-6">
                <div class="flex items-center justify-between gap-2"><h3 class="text-[11px] font-bold uppercase tracking-[.16em] text-[#101064]">Department at a Glance</h3><span class="text-[11px] font-semibold text-[#101064]">{{ $totalEvents }} total</span></div>
                <div class="mt-3 grid grid-cols-2 gap-2">
                    <div class="rounded-xl border border-amber-100 bg-white px-3 py-2"><p class="text-[11px] text-slate-500">Upcoming</p><p class="mt-1 text-xl font-bold text-amber-700">{{ $upcomingEventsCount }}</p></div>
                    <div class="rounded-xl border border-emerald-100 bg-white px-3 py-2"><p class="text-[11px] text-slate-500">Ongoing</p><p class="mt-1 text-xl font-bold text-emerald-700">{{ $ongoingEventsCount }}</p></div>
                    <div class="rounded-xl border border-indigo-100 bg-white px-3 py-2"><p class="text-[11px] text-slate-500">Completed</p><p class="mt-1 text-xl font-bold text-[#101064]">{{ $completedEventsCount }}</p></div>
                    <div class="rounded-xl border border-rose-100 bg-white px-3 py-2"><p class="text-[11px] text-slate-500">Cancelled</p><p class="mt-1 text-xl font-bold text-rose-700">{{ $cancelledEventsCount }}</p></div>
                </div>
            </div>
            <div class="border-t border-slate-100 px-6 py-4"><a href="{{ route('events.index') }}" class="text-xs font-bold text-[#101064] hover:underline">View all {{ $totalEvents }} department events →</a></div>
        </section>
    </div>

    <section aria-labelledby="dept-tools-heading">
        <div class="mb-4"><p class="text-[11px] font-semibold uppercase tracking-[.22em] text-[#A9780B]">Quick Access</p><h2 id="dept-tools-heading" class="mt-1.5 text-xl font-bold text-[#101064]">Department Workspaces</h2><p class="mt-1 text-sm text-slate-500">Tools for coordinating events and supporting student participation.</p></div>
        <div class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-3">
            @foreach($tools as $tool)
                <a href="{{ route($tool['route']) }}" class="dy-dept-action group flex min-w-0 items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-[#101064]">
                        @if($tool['icon'] === 'calendar')<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18"/></svg>
                        @elseif($tool['icon'] === 'chart')<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 20V8m5 12V4m5 16v-7m5 7V9M3 20h18"/></svg>
                        @elseif($tool['icon'] === 'users')<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M17 6a3 3 0 0 1 0 6"/></svg>
                        @elseif($tool['icon'] === 'person')<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg>
                        @else<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M7 3h10v18H7zM10 8h4m-4 4h4m-4 4h4"/></svg>@endif
                    </div>
                    <div class="min-w-0 flex-1"><h3 class="text-sm font-bold text-[#101064]">{{ $tool['name'] }}</h3><p class="mt-1.5 text-xs leading-5 text-slate-500">{{ $tool['description'] }}</p></div>
                    <span class="mt-1 shrink-0 text-slate-300 transition group-hover:translate-x-1 group-hover:text-[#101064]" aria-hidden="true">→</span>
                </a>
            @endforeach
        </div>
    </section>

    <section aria-labelledby="dept-recent-heading">
        <div class="mb-4 flex flex-wrap items-end justify-between gap-3"><div><p class="text-[11px] font-semibold uppercase tracking-[.22em] text-[#A9780B]">Department Activity</p><h2 id="dept-recent-heading" class="mt-1.5 text-xl font-bold text-[#101064]">Recent Event Records</h2><p class="mt-1 text-sm text-slate-500">Latest updates to your department's event schedule.</p></div><a href="{{ route('events.index') }}" class="text-xs font-bold text-[#101064] hover:underline">View Event Directory →</a></div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @forelse($recentEvents as $event)
                <a href="{{ route('events.show', $event->event_id) }}" class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 transition last:border-b-0 hover:bg-slate-50 sm:px-6">
                    <div class="min-w-0"><p class="break-words text-sm font-semibold text-[#101064]">{{ $event->event_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : 'Date TBA' }} · {{ $event->location ?: 'Venue to be announced' }}</p></div>
                    <div class="flex shrink-0 items-center gap-3"><span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-[#101064]">{{ $event->status ?: 'Unknown' }}</span><span class="text-xs font-bold text-[#101064]">View →</span></div>
                </a>
            @empty
                <div class="px-6 py-10 text-center text-sm text-slate-500">No department event records available.</div>
            @endforelse
        </div>
    </section>
</div>

{{-- Dates and event badges open this read-only detail modal; the event page is an explicit link inside it. --}}
<div id="dyDeptDayModal" role="dialog" aria-modal="true" aria-labelledby="dyDeptModalTitle" aria-describedby="dyDeptModalCount" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/60" aria-hidden="true">
    <div class="dy-dept-dialog-panel rounded-2xl bg-white shadow-2xl" role="document">
        <div class="relative bg-[#101064] px-5 py-5 text-white sm:px-6">
            <div class="absolute inset-x-0 bottom-0 h-1 bg-[#D4A017]"></div>
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold uppercase tracking-[.22em] text-[#E7C75B]">Department Event Schedule</p>
                    <h2 id="dyDeptModalTitle" class="mt-2 break-words text-xl font-bold">Events</h2>
                    <p id="dyDeptModalCount" class="mt-1.5 text-xs text-indigo-100">Events scheduled for this day</p>
                </div>
                <button id="dyDeptModalCloseTop" type="button" aria-label="Close event details" class="shrink-0 rounded-lg border border-white/25 px-3 py-2 text-sm hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#D4A017]">✕</button>
            </div>
        </div>
        <div id="dyDeptModalEvents" class="space-y-3 px-4 py-4 sm:px-6 sm:py-5" tabindex="0" aria-label="Events for the selected day; scroll to view more"></div>
        <div class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-5 py-3.5 sm:px-6">
            <p class="text-[11px] text-slate-500">Scroll the list to view more events.</p>
            <button id="dyDeptModalClose" type="button" class="shrink-0 rounded-lg bg-[#101064] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#0b0b4d]">Close</button>
        </div>
    </div>
</div>

<script>
(() => {
    'use strict';
    const events = @json($calendarData);
    const grid = document.getElementById('dyDeptCalendarGrid');
    const monthLabel = document.getElementById('dyDeptMonth');
    const monthFooter = document.getElementById('dyDeptMonthLabel');
    const monthCount = document.getElementById('dyDeptMonthCount');
    const modal = document.getElementById('dyDeptDayModal');
    const modalEvents = document.getElementById('dyDeptModalEvents');
    const modalTitle = document.getElementById('dyDeptModalTitle');
    const modalCount = document.getElementById('dyDeptModalCount');
    if (!grid || !monthLabel || !modal || !modalEvents) return;

    // The dashboard lives inside a scrollable <main>. Portaling the dialog to
    // <body> prevents clipping and stacking issues on smaller viewports.
    document.body.appendChild(modal);

    let current = new Date(); current.setDate(1);
    let lastFocus = null;
    const pad = value => String(value).padStart(2, '0');
    const dateKey = date => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
    const todayKey = dateKey(new Date());
    const monthFormatter = new Intl.DateTimeFormat('en-US', {month:'long',year:'numeric'});
    const dateFormatter = new Intl.DateTimeFormat('en-US', {month:'long',day:'numeric',year:'numeric'});
    const grouped = new Map();
    events.forEach(event => {
        if (!event.date) return;
        if (!grouped.has(event.date)) grouped.set(event.date, []);
        grouped.get(event.date).push(event);
    });

    function text(tag, value, classes) {
        const element = document.createElement(tag);
        if (classes) element.className = classes;
        element.textContent = value;
        return element;
    }
    // The date number opens a compact day list. An event badge shows that
    // event's full details, preserving the existing authorized event URL.
    function openDay(key, selectedEventId = null) {
        if (modal.classList.contains('hidden')) lastFocus = document.activeElement;
        const date = new Date(`${key}T12:00:00`);
        modalTitle.textContent = dateFormatter.format(date);
        modalEvents.replaceChildren();

        const allDayEvents = grouped.get(key) || [];
        const isSingleEvent = selectedEventId !== null;
        const dayEvents = isSingleEvent
            ? allDayEvents.filter(event => String(event.id) === String(selectedEventId))
            : allDayEvents;
        modalCount.textContent = isSingleEvent
            ? `Event details · ${allDayEvents.length} event${allDayEvents.length === 1 ? '' : 's'} on this date`
            : `${allDayEvents.length} event${allDayEvents.length === 1 ? '' : 's'} scheduled on this date`;

        if (isSingleEvent && allDayEvents.length > 1) {
            const allButton = text('button', `← Back to all ${allDayEvents.length} events`, 'w-full rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-left text-xs font-bold text-[#101064] hover:bg-indigo-100');
            allButton.type = 'button';
            allButton.addEventListener('click', () => openDay(key));
            modalEvents.appendChild(allButton);
        }

        if (!dayEvents.length) {
            modalEvents.appendChild(text('p', 'No department events on this date.', 'py-10 text-center text-sm text-slate-500'));
        } else {
            dayEvents.forEach(event => {
                const state = String(event.status || '').toLowerCase();
                const pillClasses = state === 'ongoing'
                    ? ['bg-emerald-50', 'text-emerald-700']
                    : state === 'cancelled' || state === 'canceled'
                        ? ['bg-rose-50', 'text-rose-700']
                        : state === 'completed'
                            ? ['bg-slate-100', 'text-slate-700']
                            : ['bg-amber-50', 'text-amber-700'];
                const card = document.createElement('article');
                card.className = isSingleEvent
                    ? 'rounded-xl border border-slate-200 bg-white p-4 sm:p-5'
                    : 'rounded-xl border border-slate-200 bg-white p-3.5 transition hover:border-indigo-200 hover:bg-indigo-50/30 sm:p-4';

                const top = document.createElement('div');
                top.className = 'flex min-w-0 flex-wrap items-start justify-between gap-2';
                top.appendChild(text('h3', event.name, 'min-w-0 flex-1 break-words text-sm font-bold text-[#101064] sm:text-base'));
                const status = text('span', event.status || 'Scheduled', 'shrink-0 rounded-full px-2.5 py-1 text-[10px] font-bold');
                status.classList.add(...pillClasses);
                top.appendChild(status);
                card.appendChild(top);

                if (isSingleEvent) {
                    const details = document.createElement('dl');
                    details.className = 'mt-4 grid gap-4 border-t border-slate-100 pt-4 sm:grid-cols-2';
                    for (const [label, value] of [
                        ['Time', `${event.time || 'Time TBA'}${event.end_time ? ' – ' + event.end_time : ''}`],
                        ['Venue', event.location || 'Venue to be announced']
                    ]) {
                        const item = document.createElement('div');
                        item.className = 'min-w-0';
                        item.appendChild(text('dt', label, 'text-[10px] font-bold uppercase tracking-wider text-slate-400'));
                        item.appendChild(text('dd', value, 'mt-1 break-words text-sm font-semibold text-slate-700'));
                        details.appendChild(item);
                    }
                    card.appendChild(details);
                } else {
                    const timeValue = `${event.time || 'Time TBA'}${event.end_time ? ' – ' + event.end_time : ''}`;
                    card.appendChild(text('p', `${timeValue}  ·  ${event.location || 'Venue TBA'}`, 'mt-2 break-words text-xs text-slate-500'));
                }

                const actions = document.createElement('div');
                actions.className = isSingleEvent
                    ? 'mt-4 flex justify-end border-t border-slate-100 pt-4'
                    : 'mt-2.5 flex flex-wrap items-center justify-end gap-2';
                if (!isSingleEvent) {
                    const preview = text('button', 'Quick View', 'rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-[#101064] hover:bg-slate-50');
                    preview.type = 'button';
                    preview.addEventListener('click', () => openDay(key, event.id));
                    actions.appendChild(preview);
                }
                const viewLink = document.createElement('a');
                viewLink.href = event.url;
                viewLink.className = 'inline-flex items-center rounded-lg bg-[#101064] px-3 py-2 text-xs font-bold text-white hover:bg-[#0b0b4d] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#D4A017]';
                viewLink.textContent = 'Event Page →';
                actions.appendChild(viewLink);
                card.appendChild(actions);
                modalEvents.appendChild(card);
            });
        }
        modalEvents.scrollTop = 0;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.getElementById('dyDeptModalCloseTop').focus();
    }
    function closeDay() {
        modal.classList.add('hidden'); modal.classList.remove('flex');
        modal.setAttribute('aria-hidden','true');
        if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
    }
    function render() {
        grid.replaceChildren();
        const year = current.getFullYear(), month = current.getMonth();
        const firstDay = new Date(year,month,1).getDay();
        const length = new Date(year,month+1,0).getDate();
        const label = monthFormatter.format(current);
        monthLabel.textContent = label;
        if(monthFooter) monthFooter.textContent = label;
        const prefix = `${year}-${pad(month+1)}`;
        if(monthCount) monthCount.textContent = events.filter(event => event.date && event.date.startsWith(prefix)).length;
        // Match the Admin calendar: fixed six-week grid keeps both cards aligned.
        for(let i=0;i<42;i++) {
            const date = new Date(year,month,i-firstDay+1);
            const key = dateKey(date);
            const active = date.getMonth()===month;
            const today = key===todayKey;
            const dayEvents = grouped.get(key) || [];
            const cell = document.createElement('div');
            cell.className = `dy-dept-calendar-cell min-w-0 cursor-pointer border-b border-r border-slate-100 px-1.5 py-2 ${active ? 'bg-white' : 'bg-slate-50/60'}`;
            cell.addEventListener('click', () => openDay(key));
            const dayButton = document.createElement('button');
            dayButton.type='button';
            dayButton.className=`mb-1 flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold ${today ? 'bg-[#101064] text-white' : active ? 'text-slate-700 hover:bg-indigo-50' : 'text-slate-400'}`;
            dayButton.textContent=String(date.getDate());
            dayButton.setAttribute('aria-label', `${dateFormatter.format(date)}, ${dayEvents.length} department events`);
            dayButton.addEventListener('click', event => { event.stopPropagation(); openDay(key); });
            cell.appendChild(dayButton);
            dayEvents.slice(0,2).forEach(event => {
                const eventButton = document.createElement('button');
                eventButton.type = 'button';
                eventButton.title = `View details: ${event.name}`;
                eventButton.setAttribute('aria-label', `View details for ${event.name} on ${dateFormatter.format(date)}`);
                eventButton.className = 'mb-1 block w-full truncate rounded bg-indigo-50 px-1.5 py-1 text-left text-[10px] font-medium text-[#101064] hover:bg-amber-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#D4A017]';
                eventButton.textContent = event.name;
                eventButton.addEventListener('click', clickEvent => { clickEvent.stopPropagation(); openDay(key, event.id); });
                cell.appendChild(eventButton);
            });
            if(dayEvents.length>2) {
                const extra=document.createElement('button');
                extra.type='button';
                extra.className='text-[10px] font-semibold text-[#A9780B] hover:underline';
                extra.textContent=`+${dayEvents.length-2} more`;
                extra.addEventListener('click', event => { event.stopPropagation(); openDay(key); });
                cell.appendChild(extra);
            } else if(dayEvents.length>0 && active) {
                const dot=document.createElement('span'); dot.className='mt-1 block h-1.5 w-1.5 rounded-full bg-[#D4A017]'; cell.appendChild(dot);
            }
            grid.appendChild(cell);
        }
    }
    document.getElementById('dyDeptToday').addEventListener('click', () => {current=new Date();current.setDate(1);render();});
    document.getElementById('dyDeptPrev').addEventListener('click', () => {current.setMonth(current.getMonth()-1);render();});
    document.getElementById('dyDeptNext').addEventListener('click', () => {current.setMonth(current.getMonth()+1);render();});
    document.getElementById('dyDeptModalClose').addEventListener('click', closeDay);
    document.getElementById('dyDeptModalCloseTop').addEventListener('click', closeDay);
    modal.addEventListener('click', event => {if(event.target===modal) closeDay();});
    document.addEventListener('keydown', event => {if(event.key==='Escape' && !modal.classList.contains('hidden')) closeDay();});
    render();
})();
</script>
</x-admin-layout>
