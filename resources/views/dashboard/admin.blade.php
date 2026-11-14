<x-admin-layout>
@php
    // All dashboard metrics continue to use the existing DashboardController data.
    $totalUsersValue = (int) ($totalUsers ?? 0);
    $activeUsersValue = (int) ($activeUsers ?? 0);
    $totalPersonnelValue = (int) ($totalPersonnel ?? 0);
    $totalRolesValue = (int) ($totalRoles ?? 0);
    $inactiveUsersValue = max(0, $totalUsersValue - $activeUsersValue);
    $activeRate = $totalUsersValue > 0 ? round(($activeUsersValue / $totalUsersValue) * 100) : 0;

    // Keep the original calendar event contract and its detail dialog intact.
    if (!isset($calendarEvents)) {
        try {
            $calendarEvents = \App\Models\Event::with('department')
                ->orderBy('event_date')
                ->orderBy('start_time')
                ->get();
        } catch (\Throwable $e) {
            $calendarEvents = collect();
        }
    }

    $calendarEventData = collect($calendarEvents)->map(function ($event) {
        return [
            'id' => $event->event_id ?? $event->id ?? null,
            'name' => $event->event_name ?? 'Untitled Event',
            'date' => !empty($event->event_date)
                ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') : null,
            'start_time' => !empty($event->start_time)
                ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : null,
            'end_time' => !empty($event->end_time)
                ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : null,
            'location' => $event->location ?? 'To be announced',
            'status' => $event->status ?? 'Scheduled',
            'department' => $event->department->department_name ?? 'University-wide Event',
        ];
    })->filter(fn ($event) => !empty($event['date']))->values();

    $eventsThisMonth = $calendarEventData->filter(
        fn ($event) => str_starts_with($event['date'], now()->format('Y-m'))
    )->count();

    $upcomingCalendarEvents = $calendarEventData->filter(function ($event) {
        return $event['date'] >= now()->toDateString()
            && !in_array(strtolower((string) $event['status']), ['canceled', 'cancelled'], true);
    })->sortBy('date')->take(5)->values();

    // Count every event record (including cancelled and events outside this month),
    // just like Event Directory. The five agenda rows are only a preview.
    $allEventRecords = collect($calendarEvents);
    $totalEventRecords = $allEventRecords->count();
    $allEventStatusCounts = $allEventRecords->countBy(
        fn ($event) => strtolower(trim((string) data_get($event, 'status', '')))
    );
    $cancelledEventRecords = (int) $allEventStatusCounts->get('cancelled', 0)
        + (int) $allEventStatusCounts->get('canceled', 0);

    $dashboardUser = auth()->user();
    $dashboardName = trim((string) ($dashboardUser?->name ?? 'Administrator'));
    $dashboardInitials = collect(preg_split('/\s+/', $dashboardName))->filter()
        ->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('') ?: 'AD';
@endphp

<style>
    /* Shared DySign campus language: navy photo hero, gold accents, quiet cards. */
    .dy-admin-hero {
        background-image: linear-gradient(102deg,rgba(12,13,79,.97) 0%,rgba(16,16,100,.9) 54%,rgba(16,16,100,.72) 100%),url('{{ asset('images/school.jpg') }}');
        background-size:cover;
        background-position:center;
    }
    .dy-admin-action {transition:border-color .2s ease,box-shadow .2s ease,transform .2s ease;}
    .dy-admin-action:hover {border-color:rgba(16,16,100,.25);box-shadow:0 12px 26px -19px rgba(16,16,100,.27);transform:translateY(-2px);}
    .admin-calendar-cell {min-height:92px;transition:background-color .15s ease,box-shadow .15s ease;}
    .admin-calendar-cell:hover {background:#f8f9ff;box-shadow:inset 0 0 0 1px rgba(16,16,100,.12);}
    .admin-scrollbar::-webkit-scrollbar {height:6px;width:6px;}
    .admin-scrollbar::-webkit-scrollbar-track {background:#f1f5f9;}
    .admin-scrollbar::-webkit-scrollbar-thumb {background:#cbd5e1;border-radius:9999px;}
    .dy-admin-progress {background:linear-gradient(90deg,#101064,#383a9c);}
    @media (max-width:639px) {.admin-calendar-cell {min-height:76px;}}
</style>

<div class="min-w-0 space-y-7 pb-8">

    {{-- A welcoming institutional identity banner, consistent with Student Dashboard. --}}
    <section class="dy-admin-hero relative isolate overflow-hidden rounded-[28px] bg-[#101064] px-6 py-8 text-white shadow-sm sm:px-9 sm:py-10" aria-labelledby="admin-welcome-heading">
        <div class="pointer-events-none absolute -right-16 -top-24 h-80 w-80 rounded-full border border-white/10"></div>
        <div class="pointer-events-none absolute -right-4 -top-12 h-60 w-60 rounded-full border border-white/10"></div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-28 w-80 bg-gradient-to-l from-[#D4A017]/20 to-transparent"></div>
        <div class="relative flex flex-col items-start justify-between gap-7 lg:flex-row lg:items-center">
            <div class="min-w-0 max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-[#E7C75B]">DySign · Administration Portal</p>
                <h1 id="admin-welcome-heading" class="mt-3 break-words text-3xl font-bold tracking-tight sm:text-4xl">Welcome, {{ $dashboardName }}!</h1>
                <p class="mt-3 max-w-xl text-sm leading-6 text-white/75">Your institutional operations, event coordination, and account management in one secure workspace.</p>
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-medium text-white/90">
                        <svg class="h-4 w-4 text-[#E7C75B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18"/></svg>
                        {{ now()->format('l, F d, Y') }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-medium text-white/90"><span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span> Administrator workspace</span>
                </div>
            </div>
            <div class="flex w-full shrink-0 flex-wrap items-center gap-4 lg:w-auto">
                <div class="hidden items-center gap-3 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-sm sm:flex">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/20 bg-white/15 text-sm font-bold">{{ $dashboardInitials }}</div>
                    <div class="min-w-0"><p class="max-w-[160px] truncate text-sm font-semibold">{{ $dashboardName }}</p><p class="mt-0.5 text-xs text-white/60">System Administrator</p></div>
                </div>
                <a href="{{ route('events.index') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#D4A017] px-5 py-3 text-sm font-bold text-[#101064] transition hover:bg-[#E7C75B] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                    Manage Events
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Four clear metrics, matching the Student Dashboard card language. --}}
    <section aria-label="Institutional records overview" class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <div><p class="text-sm font-medium text-slate-500">Total Accounts</p><p class="mt-3 text-3xl font-bold tracking-tight text-[#101064]">{{ number_format($totalUsersValue) }}</p></div>
                <div class="rounded-xl bg-indigo-50 p-3 text-[#101064]"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7" aria-hidden="true"><path d="M16 20H5a2 2 0 0 1-2-2v-2a5 5 0 0 1 10 0v2M8 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8-2a3 3 0 1 0 0-6m2 17h3v-2a5 5 0 0 0-5-5"/></svg></div>
            </div><p class="mt-4 text-xs leading-5 text-slate-500">Registered system user accounts</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <div><p class="text-sm font-medium text-slate-500">Active Accounts</p><p class="mt-3 text-3xl font-bold tracking-tight text-[#101064]">{{ number_format($activeUsersValue) }}</p></div>
                <div class="rounded-xl bg-emerald-50 p-3 text-emerald-700"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="3"/><path d="m8 12 3 3 5-6"/></svg></div>
            </div><p class="mt-4 text-xs leading-5 text-slate-500">{{ $activeRate }}% of all registered accounts</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <div><p class="text-sm font-medium text-slate-500">Personnel Records</p><p class="mt-3 text-3xl font-bold tracking-tight text-[#101064]">{{ number_format($totalPersonnelValue) }}</p></div>
                <div class="rounded-xl bg-amber-50 p-3 text-[#A9780B]"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg></div>
            </div><p class="mt-4 text-xs leading-5 text-slate-500">Staff and personnel registry entries</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start justify-between gap-3">
                <div><p class="text-sm font-medium text-slate-500">System Roles</p><p class="mt-3 text-3xl font-bold tracking-tight text-[#101064]">{{ number_format($totalRolesValue) }}</p></div>
                <div class="rounded-xl bg-slate-100 p-3 text-slate-600"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7" aria-hidden="true"><path d="M12 3 3 8l9 5 9-5-9-5Zm-6 7v5c3.5 3 8.5 3 12 0v-5M4 19h16"/></svg></div>
            </div><p class="mt-4 text-xs leading-5 text-slate-500">Configured access roles in DySign</p>
        </div>
    </section>

    {{-- Real event calendar + upcoming agenda, with the original JS and event detail dialog. --}}
    <div class="grid min-w-0 gap-6 xl:grid-cols-[minmax(0,1.9fr)_minmax(280px,0.85fr)]">
        <section class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="admin-calendar-heading">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div><p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#A9780B]">Institutional Schedule</p><h2 id="admin-calendar-heading" class="mt-1.5 text-lg font-bold text-[#101064]">Events Calendar</h2><p class="mt-1 text-xs leading-5 text-slate-500">Select a date to view event details.</p></div>
                <div class="flex shrink-0 items-center gap-2">
                    <button type="button" onclick="goToToday()" class="inline-flex h-10 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-[#101064] hover:bg-slate-50">Today</button>
                    <button type="button" onclick="changeCalendarMonth(-1)" aria-label="Previous month" class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-[#101064] hover:bg-slate-50"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg></button>
                    <button type="button" onclick="changeCalendarMonth(1)" aria-label="Next month" class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-[#101064] hover:bg-slate-50"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg></button>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-5 py-3 sm:px-6">
                <h3 id="calendarMonthTitle" aria-live="polite" class="text-sm font-bold text-[#101064]"></h3>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-medium text-slate-500"><span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#101064]"></span>Today</span><span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#D4A017]"></span>Event scheduled</span></div>
            </div>
            <div class="admin-scrollbar max-w-full overflow-x-auto">
                <div class="min-w-[650px]">
                    <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $weekday)
                            <div class="border-r border-slate-100 px-1 py-3 last:border-r-0">{{ $weekday }}</div>
                        @endforeach
                    </div>
                    <div id="adminCalendarGrid" class="grid grid-cols-7" aria-label="Calendar dates"></div>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 bg-white px-5 py-4 text-xs text-slate-500 sm:px-6">
                <span>Events recorded for <span id="adminMonthlyEventLabel">{{ now()->format('F Y') }}</span></span><span class="rounded-full bg-[#101064]/5 px-3 py-1 font-semibold text-[#101064]"><span id="adminMonthlyEventCount">{{ $eventsThisMonth }}</span> events</span>
            </div>
        </section>

        <section class="flex min-w-0 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="admin-agenda-heading">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-6 py-5">
                <div><p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#A9780B]">Today &amp; Ahead</p><h2 id="admin-agenda-heading" class="mt-1.5 text-lg font-bold text-[#101064]">Event Agenda</h2></div>
                <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-[#101064]">{{ $upcomingCalendarEvents->count() }} of {{ $totalEventRecords }} shown</span>
            </div>

            {{-- Let the agenda rows expand naturally to match the adjacent calendar's height. --}}
            <div class="flex flex-1 flex-col">
                @forelse($upcomingCalendarEvents as $event)
                    <div class="flex min-h-[90px] flex-1 items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                        <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50/70 text-[#101064]">
                            <span class="text-[10px] font-bold uppercase tracking-wider">{{ \Carbon\Carbon::parse($event['date'])->format('M') }}</span><span class="text-xl font-bold leading-6">{{ \Carbon\Carbon::parse($event['date'])->format('d') }}</span>
                        </div>
                        <div class="min-w-0 flex-1"><p class="break-words text-sm font-semibold leading-5 text-slate-800">{{ $event['name'] }}</p><p class="mt-1 break-words text-xs text-slate-500">{{ $event['department'] }}</p><p class="mt-2 flex flex-wrap items-center gap-1.5 text-[11px] text-slate-500"><span class="h-1.5 w-1.5 rounded-full bg-[#D4A017]"></span>{{ $event['start_time'] ?: 'Time to be announced' }} <span class="text-slate-300">·</span> {{ $event['status'] }}</p></div>
                    </div>
                @empty
                    <div class="flex flex-1 flex-col items-center justify-center px-7 py-16 text-center"><div class="rounded-2xl bg-slate-100 p-4 text-slate-500"><svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18M8 15h8"/></svg></div><h3 class="mt-4 text-sm font-semibold text-slate-800">No upcoming events</h3><p class="mt-2 text-xs leading-5 text-slate-500">Newly scheduled events will appear here.</p></div>
                @endforelse
            </div>

            {{-- Meaningful bottom section rather than an empty stretched card. --}}
            <div class="border-t border-slate-100 bg-slate-50/60 px-5 py-4 sm:px-6" aria-label="Agenda status overview">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h3 class="text-[11px] font-bold uppercase tracking-[0.16em] text-[#101064]">All Events at a Glance</h3>
                    <span class="text-[11px] font-semibold tabular-nums text-[#101064]">{{ number_format($totalEventRecords) }} total records</span>
                </div>
                <p class="mt-1 text-[11px] text-slate-500">Counts include all event dates, not just the agenda preview.</p>
                <dl class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-4">
                    <div class="rounded-xl border border-indigo-100 bg-white px-3 py-3">
                        <dt class="text-[11px] font-medium text-slate-500">Upcoming</dt>
                        <dd class="mt-1 text-xl font-bold tabular-nums text-[#101064]">{{ $allEventStatusCounts->get('upcoming', 0) }}</dd>
                    </div>
                    <div class="rounded-xl border border-amber-100 bg-white px-3 py-3">
                        <dt class="text-[11px] font-medium text-slate-500">Ongoing</dt>
                        <dd class="mt-1 text-xl font-bold tabular-nums text-amber-700">{{ $allEventStatusCounts->get('ongoing', 0) }}</dd>
                    </div>
                    <div class="rounded-xl border border-emerald-100 bg-white px-3 py-3">
                        <dt class="text-[11px] font-medium text-slate-500">Completed</dt>
                        <dd class="mt-1 text-xl font-bold tabular-nums text-emerald-700">{{ $allEventStatusCounts->get('completed', 0) }}</dd>
                    </div>
                    <div class="rounded-xl border border-rose-100 bg-white px-3 py-3">
                        <dt class="text-[11px] font-medium text-slate-500">Cancelled</dt>
                        <dd class="mt-1 text-xl font-bold tabular-nums text-rose-700">{{ $cancelledEventRecords }}</dd>
                    </div>
                </dl>
            </div>
            <div class="border-t border-slate-100 bg-white px-6 py-4"><a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#101064] hover:underline">View all {{ number_format($totalEventRecords) }} event records <span aria-hidden="true">→</span></a></div>
        </section>
    </div>

    {{-- Primary work areas, grouped like a purposeful campus administration console. --}}
    <section aria-labelledby="admin-management-heading">
        <div class="mb-4 flex flex-wrap items-end justify-between gap-3"><div><p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#A9780B]">Quick Access</p><h2 id="admin-management-heading" class="mt-1.5 text-xl font-bold text-[#101064]">Administrative Workspaces</h2><p class="mt-1 text-sm text-slate-500">Open the tools you use to manage day-to-day operations.</p></div></div>
        <div class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-3">
            @php
                $dashboardActions = [
                    ['name' => 'Event Management', 'description' => 'Plan schedules and oversee institutional events.', 'route' => 'events.index', 'icon' => 'calendar'],
                    ['name' => 'Student Records', 'description' => 'Maintain student profiles and registration details.', 'route' => 'students.index', 'icon' => 'students'],
                    ['name' => 'Personnel Management', 'description' => 'Manage staff records and account assignments.', 'route' => 'personnel.index', 'icon' => 'personnel'],
                    ['name' => 'Reports & Analytics', 'description' => 'Review attendance and participation records.', 'route' => 'reports.attendance', 'icon' => 'chart'],
                    ['name' => 'Audit Trail', 'description' => 'Inspect logged actions and system activity.', 'route' => 'logs.index', 'icon' => 'audit'],
                    ['name' => 'System Backup', 'description' => 'Access database backup and recovery options.', 'route' => 'backup.index', 'icon' => 'backup'],
                ];
            @endphp
            @foreach($dashboardActions as $action)
                <a href="{{ route($action['route']) }}" class="dy-admin-action group flex min-w-0 items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#101064]">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-[#101064]">
                        @if($action['icon'] === 'calendar')<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18"/></svg>
                        @elseif($action['icon'] === 'students')<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M17 6a3 3 0 0 1 0 6m1 4a4 4 0 0 1 3 4v1"/></svg>
                        @elseif($action['icon'] === 'personnel')<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h4"/></svg>
                        @elseif($action['icon'] === 'chart')<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true"><path d="M4 20V8m5 12V4m5 16v-7m5 7V9M3 20h18"/></svg>
                        @elseif($action['icon'] === 'audit')<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true"><path d="M8 3h8l4 4v14H4V3h4Zm8 0v5h4M8 13h8m-8 4h6"/></svg>
                        @else<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true"><path d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v4h16v-4M5 7V4h14v3"/></svg>@endif
                    </div>
                    <div class="min-w-0 flex-1"><h3 class="text-sm font-bold text-[#101064]">{{ $action['name'] }}</h3><p class="mt-1.5 text-xs leading-5 text-slate-500">{{ $action['description'] }}</p></div>
                    <svg class="mt-1 h-4 w-4 shrink-0 text-slate-300 transition group-hover:translate-x-1 group-hover:text-[#101064]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Personnel and account health: both use live controller values. --}}
    <div class="grid min-w-0 gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(290px,0.9fr)]">
        <section class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="admin-personnel-heading">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-5"><div><p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#A9780B]">Staff Registry</p><h2 id="admin-personnel-heading" class="mt-1.5 text-lg font-bold text-[#101064]">Recent Personnel Accounts</h2></div><a href="{{ route('personnel.index') }}" class="text-xs font-bold text-[#101064] hover:underline">View all</a></div>
            @forelse(($recentPersonnel ?? collect()) as $person)
                <div class="flex flex-wrap items-center gap-3 border-b border-slate-100 px-6 py-4 last:border-b-0 sm:flex-nowrap">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-xs font-bold text-[#101064]">{{ strtoupper(substr((string) ($person->first_name ?? 'P'), 0, 1) . substr((string) ($person->last_name ?? ''), 0, 1)) }}</div>
                    <div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-800">{{ trim(($person->first_name ?? '') . ' ' . ($person->last_name ?? '')) ?: 'Personnel Record' }}</p><p class="mt-0.5 truncate text-xs text-slate-500">{{ $person->department ?? 'No department assigned' }} · {{ $person->position ?? 'Personnel' }}</p></div>
                    <span class="shrink-0 text-xs text-slate-400">{{ $person->created_at ? $person->created_at->format('M d, Y') : '—' }}</span>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center px-6 py-16 text-center"><div class="rounded-xl bg-slate-100 p-4 text-slate-500"><svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg></div><p class="mt-4 text-sm font-semibold text-slate-800">No personnel records yet</p><p class="mt-1 text-xs text-slate-500">Recently created personnel records will appear here.</p></div>
            @endforelse
        </section>
        <section class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="admin-access-heading">
            <div class="border-b border-slate-100 px-6 py-5"><p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#A9780B]">Account Monitoring</p><h2 id="admin-access-heading" class="mt-1.5 text-lg font-bold text-[#101064]">Account Availability</h2></div>
            <div class="p-6">
                <div class="flex flex-wrap items-end justify-between gap-2"><div><p class="text-sm text-slate-500">Active user rate</p><p class="mt-2 text-3xl font-bold tracking-tight text-[#101064]">{{ $activeRate }}%</p></div><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ number_format($activeUsersValue) }} active</span></div>
                <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-valuenow="{{ $activeRate }}" aria-valuemin="0" aria-valuemax="100" aria-label="Percentage of active user accounts"><div class="dy-admin-progress h-full rounded-full" style="width: {{ min(100, max(0, $activeRate)) }}%"></div></div>
                <dl class="mt-6 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 px-4 py-4 text-sm"><div><dt class="text-xs text-slate-500">Active Accounts</dt><dd class="mt-1 font-bold text-[#101064]">{{ number_format($activeUsersValue) }}</dd></div><div><dt class="text-xs text-slate-500">Inactive Accounts</dt><dd class="mt-1 font-bold text-slate-700">{{ number_format($inactiveUsersValue) }}</dd></div></dl>
                <div class="mt-6 border-t border-slate-100 pt-5"><h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">Accounts by Role</h3>
                    <div class="mt-4 space-y-3">
                        @forelse(($roleDistribution ?? collect()) as $role)
                            @php $roleCount = (int) ($role->users_count ?? 0); @endphp
                            <div class="flex items-center justify-between gap-3 text-xs"><span class="min-w-0 truncate text-slate-600">{{ $role->role_name ?? 'Role' }}</span><span class="shrink-0 font-semibold tabular-nums text-[#101064]">{{ number_format($roleCount) }}</span></div>
                        @empty
                            <p class="text-xs text-slate-500">No role information available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


{{-- ====================================================== --}}
{{-- EVENT DATE MODAL --}}
{{-- ====================================================== --}}

<div
    id="eventDayModal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modalEventDate"
    class="
        fixed
        inset-0
        z-[100]
        hidden
        items-center
        justify-center
        bg-black/50
        px-4
        py-6
        backdrop-blur-[2px]
    "
    onclick="closeEventDayModal(event)"
>

    <div
        class="
            max-h-[90vh]
            w-full
            max-w-xl
            overflow-hidden
            rounded-2xl
            bg-white
            shadow-2xl
        "
        onclick="event.stopPropagation()"
    >


        {{-- MODAL HEADER --}}

        <div
            class="
                relative
                bg-[#101064]
                px-6
                py-5
                text-white
            "
        >

            <div
                class="
                    absolute
                    bottom-0
                    left-0
                    h-1
                    w-full
                    bg-[#D4A017]
                "
            ></div>


            <div
                class="
                    flex
                    min-w-0
                    items-start
                    justify-between
                    gap-5
                "
            >

                <div class="min-w-0">

                    <p
                        class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.25em]
                            text-[#E7C75B]
                        "
                    >
                        Events Schedule
                    </p>


                    <h3
                        id="modalEventDate"
                        class="
                            mt-2
                            break-words
                            text-xl
                            font-bold
                        "
                    >
                    </h3>

                </div>



                <button
                    type="button"
                    onclick="closeEventDayModal()"
                    class="
                        flex
                        h-9
                        w-9
                        shrink-0
                        items-center
                        justify-center
                        rounded-full
                        text-white/70
                        transition
                        hover:bg-white/10
                        hover:text-white
                    "
                >

                    <svg
                        class="
                            h-5
                            w-5
                        "
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>

        </div>



        {{-- MODAL EVENT LIST --}}

        <div
            id="modalEventList"
            class="
                admin-scrollbar
                max-h-[60vh]
                overflow-y-auto
            "
        ></div>



        {{-- MODAL FOOTER --}}

        <div
            class="
                flex
                justify-end
                border-t
                border-gray-100
                bg-gray-50
                px-6
                py-4
            "
        >

            <button
                type="button"
                onclick="closeEventDayModal()"
                class="
                    rounded-lg
                    bg-[#101064]
                    px-5
                    py-2.5
                    text-sm
                    font-semibold
                    text-white
                    transition
                    hover:bg-[#D4A017]
                    hover:text-[#101064]
                "
            >
                Close
            </button>

        </div>

    </div>

</div>



{{-- ====================================================== --}}
{{-- CALENDAR JAVASCRIPT --}}
{{-- ====================================================== --}}

<script>

    /*
    |--------------------------------------------------------------------------
    | EVENTS FROM LARAVEL
    |--------------------------------------------------------------------------
    */

    const adminCalendarEvents = @json($calendarEventData);



    /*
    |--------------------------------------------------------------------------
    | CURRENT CALENDAR MONTH
    |--------------------------------------------------------------------------
    */

    let calendarCurrentDate = new Date();

    calendarCurrentDate.setDate(1);



    /*
    |--------------------------------------------------------------------------
    | RENDER CALENDAR
    |--------------------------------------------------------------------------
    */

    function renderAdminCalendar()
    {

        const calendarGrid =
            document.getElementById(
                'adminCalendarGrid'
            );


        const calendarTitle =
            document.getElementById(
                'calendarMonthTitle'
            );


        if (
            !calendarGrid
            ||
            !calendarTitle
        ) {

            return;

        }



        const year =
            calendarCurrentDate.getFullYear();


        const month =
            calendarCurrentDate.getMonth();



        const monthName =
            calendarCurrentDate.toLocaleString(
                'en-US',
                {
                    month: 'long'
                }
            );



        calendarTitle.textContent =
            `${monthName} ${year}`;

        const monthlyCountEl = document.getElementById('adminMonthlyEventCount');
        const monthlyLabelEl = document.getElementById('adminMonthlyEventLabel');
        const monthlyPrefix = `${year}-${String(month + 1).padStart(2, '0')}`;
        if (monthlyCountEl) {
            monthlyCountEl.textContent = adminCalendarEvents.filter(event => event.date && event.date.startsWith(monthlyPrefix)).length;
        }
        if (monthlyLabelEl) {
            monthlyLabelEl.textContent = `${monthName} ${year}`;
        }



        const firstDay =
            new Date(
                year,
                month,
                1
            ).getDay();



        const daysInMonth =
            new Date(
                year,
                month + 1,
                0
            ).getDate();



        const daysInPreviousMonth =
            new Date(
                year,
                month,
                0
            ).getDate();



        const today =
            new Date();



        calendarGrid.innerHTML = '';



        /*
        |--------------------------------------------------------------------------
        | 42 CELLS = 6 WEEKS
        |--------------------------------------------------------------------------
        */

        for (
            let index = 0;
            index < 42;
            index++
        ) {

            let dateNumber;

            let cellMonth =
                month;

            let cellYear =
                year;

            let isCurrentMonth =
                true;



            /*
            |--------------------------------------------------------------------------
            | PREVIOUS MONTH DAYS
            |--------------------------------------------------------------------------
            */

            if (
                index < firstDay
            ) {

                dateNumber =
                    daysInPreviousMonth
                    - firstDay
                    + index
                    + 1;


                cellMonth =
                    month - 1;


                isCurrentMonth =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | NEXT MONTH DAYS
            |--------------------------------------------------------------------------
            */

            else if (
                index >=
                firstDay
                + daysInMonth
            ) {

                dateNumber =
                    index
                    - firstDay
                    - daysInMonth
                    + 1;


                cellMonth =
                    month + 1;


                isCurrentMonth =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | CURRENT MONTH
            |--------------------------------------------------------------------------
            */

            else {

                dateNumber =
                    index
                    - firstDay
                    + 1;

            }



            /*
            |--------------------------------------------------------------------------
            | ACTUAL DATE
            |--------------------------------------------------------------------------
            */

            const actualDate =
                new Date(
                    cellYear,
                    cellMonth,
                    dateNumber
                );



            cellYear =
                actualDate.getFullYear();


            cellMonth =
                actualDate.getMonth();



            const dateKey =
                formatCalendarDate(
                    actualDate
                );



            /*
            |--------------------------------------------------------------------------
            | GET EVENTS FOR DATE
            |--------------------------------------------------------------------------
            */

            const eventsForDay =
                adminCalendarEvents.filter(
                    calendarEvent =>
                        calendarEvent.date
                        === dateKey
                );



            /*
            |--------------------------------------------------------------------------
            | TODAY CHECK
            |--------------------------------------------------------------------------
            */

            const isToday =

                actualDate.getFullYear()
                    === today.getFullYear()

                &&

                actualDate.getMonth()
                    === today.getMonth()

                &&

                actualDate.getDate()
                    === today.getDate();



            /*
            |--------------------------------------------------------------------------
            | CELL
            |--------------------------------------------------------------------------
            */

            const cell =
                document.createElement(
                    'button'
                );


            cell.type =
                'button';

            cell.setAttribute('aria-label', actualDate.toLocaleDateString('en-US', {
                weekday: 'long', month: 'long', day: 'numeric', year: 'numeric'
            }) + ' — ' + eventsForDay.length + ' events');


            cell.className = `
                admin-calendar-cell
                relative
                min-w-0
                overflow-hidden
                border-b
                border-r
                border-gray-100
                bg-white
                p-3
                text-left
                focus:outline-none
                focus:ring-2
                focus:ring-inset
                focus:ring-[#101064]/20
            `;



            if (
                !isCurrentMonth
            ) {

                cell.classList.add(
                    'bg-gray-50/60'
                );

            }



            /*
            |--------------------------------------------------------------------------
            | CLICK DATE
            |--------------------------------------------------------------------------
            */

            cell.addEventListener(
                'click',
                function () {

                    openEventDayModal(
                        actualDate,
                        eventsForDay
                    );

                }
            );



            /*
            |--------------------------------------------------------------------------
            | DATE NUMBER
            |--------------------------------------------------------------------------
            */

            const dateLabel =
                document.createElement(
                    'span'
                );


            dateLabel.className = `
                flex
                h-7
                w-7
                items-center
                justify-center
                rounded-full
                text-xs
                font-semibold
            `;



            if (
                isToday
            ) {

                dateLabel.classList.add(
                    'bg-[#101064]',
                    'text-white'
                );

            }

            else if (
                !isCurrentMonth
            ) {

                dateLabel.classList.add(
                    'text-gray-300'
                );

            }

            else {

                dateLabel.classList.add(
                    'text-gray-600'
                );

            }



            dateLabel.textContent =
                dateNumber;



            cell.appendChild(
                dateLabel
            );



            /*
            |--------------------------------------------------------------------------
            | EVENT LABELS
            |--------------------------------------------------------------------------
            */

            if (
                eventsForDay.length > 0
            ) {

                /*
                | GOLD DOT
                */

                const indicator =
                    document.createElement(
                        'span'
                    );


                indicator.className = `
                    absolute
                    right-3
                    top-3
                    h-2
                    w-2
                    rounded-full
                    bg-[#D4A017]
                `;


                cell.appendChild(
                    indicator
                );



                /*
                | EVENTS CONTAINER
                */

                const eventContainer =
                    document.createElement(
                        'div'
                    );


                eventContainer.className =
                    'mt-2 min-w-0 space-y-1';



                /*
                | SHOW FIRST TWO EVENTS
                */

                eventsForDay
                    .slice(0, 2)
                    .forEach(
                        calendarEvent => {

                            const eventLabel =
                                document.createElement(
                                    'div'
                                );


                            eventLabel.className = `
                                min-w-0
                                truncate
                                border-l-2
                                border-[#D4A017]
                                bg-[#F7F7FB]
                                px-2
                                py-1.5
                                text-[10px]
                                font-semibold
                                text-[#101064]
                            `;


                            eventLabel.textContent =
                                calendarEvent.name;


                            eventContainer.appendChild(
                                eventLabel
                            );

                        }
                    );



                /*
                | + MORE
                */

                if (
                    eventsForDay.length > 2
                ) {

                    const moreLabel =
                        document.createElement(
                            'p'
                        );


                    moreLabel.className =
                        'truncate px-1 text-[10px] font-semibold text-gray-400';


                    moreLabel.textContent =
                        `+${eventsForDay.length - 2} more`;


                    eventContainer.appendChild(
                        moreLabel
                    );

                }



                cell.appendChild(
                    eventContainer
                );

            }



            calendarGrid.appendChild(
                cell
            );

        }

    }



    /*
    |--------------------------------------------------------------------------
    | FORMAT DATE TO YYYY-MM-DD
    |--------------------------------------------------------------------------
    */

    function formatCalendarDate(date)
    {

        const year =
            date.getFullYear();


        const month =
            String(
                date.getMonth() + 1
            ).padStart(
                2,
                '0'
            );


        const day =
            String(
                date.getDate()
            ).padStart(
                2,
                '0'
            );


        return `${year}-${month}-${day}`;

    }



    /*
    |--------------------------------------------------------------------------
    | PREVIOUS / NEXT MONTH
    |--------------------------------------------------------------------------
    */

    function changeCalendarMonth(direction)
    {

        calendarCurrentDate.setMonth(
            calendarCurrentDate.getMonth()
            + direction
        );


        renderAdminCalendar();

    }



    /*
    |--------------------------------------------------------------------------
    | TODAY
    |--------------------------------------------------------------------------
    */

    function goToToday()
    {

        calendarCurrentDate =
            new Date();


        calendarCurrentDate.setDate(
            1
        );


        renderAdminCalendar();

    }



    /*
    |--------------------------------------------------------------------------
    | OPEN DATE MODAL
    |--------------------------------------------------------------------------
    */

    function openEventDayModal(
        selectedDate,
        events
    )
    {

        const modal =
            document.getElementById(
                'eventDayModal'
            );


        const dateTitle =
            document.getElementById(
                'modalEventDate'
            );


        const eventList =
            document.getElementById(
                'modalEventList'
            );



        /*
        |--------------------------------------------------------------------------
        | DATE TITLE
        |--------------------------------------------------------------------------
        */

        dateTitle.textContent =
            selectedDate.toLocaleDateString(
                'en-US',
                {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                }
            );



        /*
        |--------------------------------------------------------------------------
        | CLEAR OLD EVENTS
        |--------------------------------------------------------------------------
        */

        eventList.innerHTML =
            '';



        /*
        |--------------------------------------------------------------------------
        | NO EVENTS
        |--------------------------------------------------------------------------
        */

        if (
            events.length === 0
        ) {

            const emptyContainer =
                document.createElement(
                    'div'
                );


            emptyContainer.className =
                'px-6 py-12 text-center';



            const accent =
                document.createElement(
                    'div'
                );


            accent.className =
                'mx-auto h-1 w-10 bg-[#D4A017]';



            const emptyTitle =
                document.createElement(
                    'h4'
                );


            emptyTitle.className =
                'mt-5 font-bold text-[#101064]';


            emptyTitle.textContent =
                'No scheduled events';



            const emptyDescription =
                document.createElement(
                    'p'
                );


            emptyDescription.className =
                'mt-2 text-sm text-gray-400';


            emptyDescription.textContent =
                'There are no institutional events scheduled for this date.';



            emptyContainer.appendChild(
                accent
            );


            emptyContainer.appendChild(
                emptyTitle
            );


            emptyContainer.appendChild(
                emptyDescription
            );


            eventList.appendChild(
                emptyContainer
            );

        }


        /*
        |--------------------------------------------------------------------------
        | EVENT LIST
        |--------------------------------------------------------------------------
        */

        else {

            events.forEach(
                calendarEvent => {

                    /*
                    | MAIN ITEM
                    */

                    const eventItem =
                        document.createElement(
                            'div'
                        );


                    eventItem.className = `
                        min-w-0
                        border-b
                        border-gray-100
                        px-6
                        py-5
                        transition
                        last:border-b-0
                        hover:bg-gray-50
                    `;



                    /*
                    | WRAPPER
                    */

                    const wrapper =
                        document.createElement(
                            'div'
                        );


                    wrapper.className =
                        'flex min-w-0 items-start gap-4';



                    /*
                    | STATUS DOT
                    */

                    const statusDot =
                        document.createElement(
                            'span'
                        );


                    statusDot.className =
                        'mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full';



                    if (
                        calendarEvent.status
                        === 'Ongoing'
                    ) {

                        statusDot.classList.add(
                            'bg-green-500'
                        );

                    }

                    else if (
                        ['Cancelled', 'Canceled'].includes(calendarEvent.status)
                    ) {

                        statusDot.classList.add(
                            'bg-red-500'
                        );

                    }

                    else {

                        statusDot.classList.add(
                            'bg-[#D4A017]'
                        );

                    }



                    /*
                    | CONTENT
                    */

                    const content =
                        document.createElement(
                            'div'
                        );


                    content.className =
                        'min-w-0 flex-1';



                    /*
                    | TOP
                    */

                    const topRow =
                        document.createElement(
                            'div'
                        );


                    topRow.className = `
                        flex
                        min-w-0
                        flex-col
                        gap-2
                        sm:flex-row
                        sm:items-start
                        sm:justify-between
                    `;



                    const headingContainer =
                        document.createElement(
                            'div'
                        );


                    headingContainer.className =
                        'min-w-0';



                    const eventName =
                        document.createElement(
                            'h4'
                        );


                    eventName.className =
                        'break-words font-bold text-[#101064]';


                    eventName.textContent =
                        calendarEvent.name;



                    const department =
                        document.createElement(
                            'p'
                        );


                    department.className =
                        'mt-1 break-words text-xs text-gray-400';


                    department.textContent =
                        calendarEvent.department;



                    headingContainer.appendChild(
                        eventName
                    );


                    headingContainer.appendChild(
                        department
                    );



                    /*
                    | STATUS
                    */

                    const status =
                        document.createElement(
                            'span'
                        );


                    status.className =
                        'shrink-0 text-xs font-semibold';



                    if (
                        calendarEvent.status
                        === 'Ongoing'
                    ) {

                        status.classList.add(
                            'text-green-600'
                        );

                    }

                    else if (
                        ['Cancelled', 'Canceled'].includes(calendarEvent.status)
                    ) {

                        status.classList.add(
                            'text-red-500'
                        );

                    }

                    else {

                        status.classList.add(
                            'text-[#101064]'
                        );

                    }



                    status.textContent =
                        calendarEvent.status
                        ?? 'Scheduled';



                    topRow.appendChild(
                        headingContainer
                    );


                    topRow.appendChild(
                        status
                    );



                    /*
                    |--------------------------------------------------------------------------
                    | EVENT DETAILS
                    |--------------------------------------------------------------------------
                    */

                    const details =
                        document.createElement(
                            'div'
                        );


                    details.className = `
                        mt-4
                        grid
                        min-w-0
                        grid-cols-1
                        gap-4
                        sm:grid-cols-2
                    `;



                    /*
                    | TIME
                    */

                    const timeContainer =
                        document.createElement(
                            'div'
                        );


                    timeContainer.className =
                        'min-w-0';



                    const timeLabel =
                        document.createElement(
                            'p'
                        );


                    timeLabel.className = `
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    `;


                    timeLabel.textContent =
                        'Time';



                    const timeValue =
                        document.createElement(
                            'p'
                        );


                    timeValue.className =
                        'mt-1 break-words text-sm font-semibold text-gray-700';



                    if (
                        calendarEvent.start_time
                    ) {

                        timeValue.textContent =
                            calendarEvent.start_time
                            +
                            (
                                calendarEvent.end_time
                                ? ` – ${calendarEvent.end_time}`
                                : ''
                            );

                    }

                    else {

                        timeValue.textContent =
                            'Time not specified';

                    }



                    timeContainer.appendChild(
                        timeLabel
                    );


                    timeContainer.appendChild(
                        timeValue
                    );



                    /*
                    | VENUE
                    */

                    const venueContainer =
                        document.createElement(
                            'div'
                        );


                    venueContainer.className =
                        'min-w-0';



                    const venueLabel =
                        document.createElement(
                            'p'
                        );


                    venueLabel.className = `
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    `;


                    venueLabel.textContent =
                        'Venue';



                    const venueValue =
                        document.createElement(
                            'p'
                        );


                    venueValue.className =
                        'mt-1 break-words text-sm font-semibold text-gray-700';


                    venueValue.textContent =
                        calendarEvent.location
                        ?? 'To be announced';



                    venueContainer.appendChild(
                        venueLabel
                    );


                    venueContainer.appendChild(
                        venueValue
                    );



                    details.appendChild(
                        timeContainer
                    );


                    details.appendChild(
                        venueContainer
                    );



                    /*
                    | ASSEMBLE
                    */

                    content.appendChild(
                        topRow
                    );


                    content.appendChild(
                        details
                    );


                    wrapper.appendChild(
                        statusDot
                    );


                    wrapper.appendChild(
                        content
                    );


                    eventItem.appendChild(
                        wrapper
                    );


                    eventList.appendChild(
                        eventItem
                    );

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | SHOW MODAL
        |--------------------------------------------------------------------------
        */

        modal.classList.remove(
            'hidden'
        );


        modal.classList.add(
            'flex'
        );


        document.body.classList.add(
            'overflow-hidden'
        );

    }



    /*
    |--------------------------------------------------------------------------
    | CLOSE MODAL
    |--------------------------------------------------------------------------
    */

    function closeEventDayModal(event = null)
    {

        if (
            event
            &&
            event.target
            !== event.currentTarget
        ) {

            return;

        }



        const modal =
            document.getElementById(
                'eventDayModal'
            );



        if (
            !modal
        ) {

            return;

        }



        modal.classList.add(
            'hidden'
        );


        modal.classList.remove(
            'flex'
        );


        document.body.classList.remove(
            'overflow-hidden'
        );

    }



    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            renderAdminCalendar();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | ESCAPE KEY CLOSES MODAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape'
            ) {

                closeEventDayModal();

            }

        }
    );

</script>


</x-admin-layout>