<x-admin-layout>
@php
    $personnelUser = auth()->user();
    $personnelName = trim((string) ($personnelUser?->name ?? 'Attendance Personnel')) ?: 'Attendance Personnel';
    $personnelInitials = collect(preg_split('/\s+/', $personnelName))->filter()->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('') ?: 'AP';
    $totalAssignedEvents = (int) ($totalAssignedEvents ?? 0);
    $upcomingEventsCount = (int) ($upcomingEventsCount ?? 0);
    $todayScanned = (int) ($todayScanned ?? 0);
    $totalScanned = (int) ($totalScanned ?? 0);
    $currentEventScanned = (int) ($currentEventScanned ?? 0);
    $recentAttendance = collect($recentAttendance ?? []);

    // Controller already restricts this collection to this logged-in personnel's assignments.
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
                'url' => route('my-events.show', $event->event_id),
            ];
        })->values();

    $todayDate = now('Asia/Manila')->format('Y-m-d');
    $activeAgenda = $calendarData->filter(function ($item) use ($todayDate) {
        $status = strtolower($item['status']);
        return $status === 'ongoing' || ($status === 'upcoming' && $item['date'] >= $todayDate);
    })->sortBy(fn ($item) => (strtolower($item['status']) === 'ongoing' ? '0' : '1') . $item['date'] . $item['time'])->values();
    $fallbackAgenda = $calendarData->reject(fn ($item) => $activeAgenda->contains('id', $item['id']))
        ->sortByDesc('date')->values();
    $agenda = $activeAgenda->concat($fallbackAgenda)->take(5)->values();
    $calendarMonthCount = $calendarData->filter(fn ($item) => str_starts_with($item['date'], now('Asia/Manila')->format('Y-m')))->count();
    $totalEvents = $totalAssignedEvents;
    $ongoingEventsCount = $calendarData->where('status', 'Ongoing')->count();
    $completedEventsCount = $calendarData->where('status', 'Completed')->count();
    $cancelledEventsCount = $calendarData->where('status', 'Cancelled')->count();
@endphp
<style>
    .dy-personnel-hero {
        background-image: linear-gradient(102deg, rgba(12,13,79,.97) 0%, rgba(16,16,100,.90) 54%, rgba(16,16,100,.72) 100%), url('{{ asset('images/school.jpg') }}');
        background-size: cover; background-position: center;
    }
    .dy-personnel-calendar-cell { min-height: 92px; transition: background-color .15s ease; }
    .dy-personnel-calendar-cell:hover { background: #f8f9ff; }
    .dy-personnel-action { transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease; }
    .dy-personnel-action:hover { border-color: #c7c9e8; box-shadow: 0 10px 26px -20px rgba(16,16,100,.32); transform: translateY(-2px); }
    .dy-personnel-scroll::-webkit-scrollbar { height: 6px; }
    .dy-personnel-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
    /* Keep calendar event dialogs inside the viewport even on days with many events.
       Explicit CSS avoids relying on dynamically generated utility classes. */
    #dyPersonnelDayModal { box-sizing: border-box; overflow-y: auto; overscroll-behavior: contain; padding: 16px; }
    #dyPersonnelDayModal .dy-personnel-dialog-panel {
        box-sizing: border-box;
        display: grid;
        grid-template-rows: auto minmax(0, 1fr) auto;
        width: min(100%, 660px);
        max-height: min(760px, calc(100vh - 32px));
        max-height: min(760px, calc(100dvh - 32px));
        margin: auto;
        overflow: hidden;
    }
    #dyPersonnelModalEvents {
        min-height: 0;
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #ffffff;
    }
    #dyPersonnelModalEvents::-webkit-scrollbar { width: 8px; }
    #dyPersonnelModalEvents::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    @media(max-width:639px) {
        .dy-personnel-calendar-cell { min-height: 76px; }
        #dyPersonnelDayModal { padding: 10px; }
        #dyPersonnelDayModal .dy-personnel-dialog-panel {
            max-height: calc(100vh - 20px);
            max-height: calc(100dvh - 20px);
        }
    }
</style>
<div class="min-w-0 space-y-7 pb-8">
    <section class="dy-personnel-hero relative isolate overflow-hidden rounded-[28px] bg-[#101064] px-6 py-8 text-white shadow-sm sm:px-9 sm:py-10" aria-labelledby="personnel-welcome-heading">
        <div class="pointer-events-none absolute -right-16 -top-24 h-80 w-80 rounded-full border border-white/10"></div>
        <div class="pointer-events-none absolute -right-4 -top-12 h-60 w-60 rounded-full border border-white/10"></div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-28 w-80 bg-gradient-to-l from-[#D4A017]/20 to-transparent"></div>
        <div class="relative flex flex-col items-start justify-between gap-7 lg:flex-row lg:items-center">
            <div class="min-w-0 max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[.25em] text-[#E7C75B]">DySign · Attendance Personnel Portal</p>
                <h1 id="personnel-welcome-heading" class="mt-3 break-words text-3xl font-bold tracking-tight sm:text-4xl">Welcome, {{ $personnelName }}!</h1>
                <p class="mt-3 max-w-xl text-sm leading-6 text-white/75">Your assigned events, attendance activity, and upcoming schedules at a glance.</p>
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-medium text-white/90">
                        <svg class="h-4 w-4 text-[#E7C75B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18"/></svg>
                        {{ now('Asia/Manila')->format('l, F d, Y') }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-medium text-white/90"><span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span> Assigned Events Only</span>
                </div>
            </div>
            <div class="flex w-full shrink-0 flex-wrap items-center gap-3 lg:w-auto">
                <div class="hidden items-center gap-3 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-sm sm:flex">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/20 bg-white/15 text-sm font-bold">{{ $personnelInitials }}</div>
                    <div class="min-w-0"><p class="max-w-[180px] truncate text-sm font-semibold">{{ $personnelName }}</p><p class="mt-0.5 text-xs text-white/65">Attendance Personnel</p></div>
                </div>
                <a href="{{ route('my-events.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-[#D4A017] px-5 py-3 text-sm font-bold text-[#101064] transition hover:bg-[#E7C75B]">My Assigned Events <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-4" aria-label="Personnel attendance overview">
        @foreach([
            ['label'=>'Assigned Events', 'value'=>$totalAssignedEvents, 'desc'=>'Events assigned to you', 'color'=>'indigo'],
            ['label'=>'Upcoming Events', 'value'=>$upcomingEventsCount, 'desc'=>'Scheduled assigned events', 'color'=>'amber'],
            ['label'=>'Total Attendance', 'value'=>$totalScanned, 'desc'=>'Records for assigned events', 'color'=>'emerald'],
            ["label"=>"Today's Attendance", 'value'=>$todayScanned, 'desc'=>'Records checked in today', 'color'=>'slate'],
        ] as $metric)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div><p class="text-sm font-medium text-slate-500">{{ $metric['label'] }}</p><p class="mt-3 text-3xl font-bold tracking-tight text-[#101064]">{{ number_format($metric['value']) }}</p></div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ match($metric['color']) { 'amber'=>'bg-amber-50 text-amber-700','emerald'=>'bg-emerald-50 text-emerald-700','slate'=>'bg-slate-100 text-slate-600',default=>'bg-indigo-50 text-[#101064]' } }}">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4m8-4v4M4 10h16M8 15h3"/></svg>
                    </div>
                </div>
                <p class="mt-4 text-xs leading-5 text-slate-500">{{ $metric['desc'] }}</p>
            </div>
        @endforeach
    </section>
    {{-- Main section mirrors Administrator: interactive calendar + full-height agenda. --}}
    <div class="grid min-w-0 gap-6 xl:grid-cols-[minmax(0,1.9fr)_minmax(280px,0.85fr)]">
        <section class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="personnel-calendar-heading">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div><p class="text-[11px] font-semibold uppercase tracking-[.22em] text-[#A9780B]">Assigned Schedule</p><h2 id="personnel-calendar-heading" class="mt-1.5 text-lg font-bold text-[#101064]">Events Calendar</h2><p class="mt-1 text-xs text-slate-500">Select a date or event name to review details.</p></div>
                <div class="flex shrink-0 items-center gap-2">
                    <button id="dyPersonnelToday" type="button" class="inline-flex h-10 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-[#101064] hover:bg-slate-50">Today</button>
                    <button id="dyPersonnelPrev" type="button" aria-label="Previous month" class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-[#101064] hover:bg-slate-50">‹</button>
                    <button id="dyPersonnelNext" type="button" aria-label="Next month" class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-[#101064] hover:bg-slate-50">›</button>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-5 py-3 sm:px-6">
                <h3 id="dyPersonnelMonth" class="text-sm font-bold text-[#101064]" aria-live="polite">{{ now()->format('F Y') }}</h3>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-medium text-slate-500"><span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#101064]"></span>Today</span><span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#D4A017]"></span>Event scheduled</span></div>
            </div>
            <div class="dy-personnel-scroll max-w-full overflow-x-auto">
                <div class="min-w-[650px]">
                    <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $weekday)
                            <div class="border-r border-slate-100 px-1 py-3 last:border-r-0">{{ $weekday }}</div>
                        @endforeach
                    </div>
                    <div id="dyPersonnelCalendarGrid" class="grid grid-cols-7" aria-label="Department calendar dates"></div>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 px-5 py-4 text-xs text-slate-500 sm:px-6"><span>Assigned events in <span id="dyPersonnelMonthLabel">{{ now()->format('F Y') }}</span></span><span class="rounded-full bg-indigo-50 px-3 py-1 font-semibold text-[#101064]"><span id="dyPersonnelMonthCount">{{ $calendarMonthCount }}</span> events</span></div>
        </section>

        <section class="flex min-w-0 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="personnel-agenda-heading">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-5 sm:px-6"><div><p class="text-[11px] font-semibold uppercase tracking-[.22em] text-[#A9780B]">Active, Upcoming &amp; Recent</p><h2 id="personnel-agenda-heading" class="mt-1.5 text-lg font-bold text-[#101064]">Event Agenda</h2></div><span class="shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-[#101064]">{{ $agenda->count() }} of {{ $totalEvents }} shown</span></div>
            <div class="flex flex-1 flex-col">
                @forelse($agenda as $event)
                    <a href="{{ $event['url'] }}" class="flex min-h-[90px] flex-1 items-center gap-3 border-b border-slate-100 px-5 py-4 transition hover:bg-slate-50 sm:px-6">
                        <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50/70 text-[#101064]"><span class="text-[10px] font-bold uppercase tracking-wider">{{ \Carbon\Carbon::parse($event['date'])->format('M') }}</span><span class="text-xl font-bold leading-6">{{ \Carbon\Carbon::parse($event['date'])->format('d') }}</span></div>
                        <div class="min-w-0 flex-1"><p class="break-words text-sm font-semibold leading-5 text-slate-800">{{ $event['name'] }}</p><p class="mt-1 truncate text-xs text-slate-500">{{ $event['location'] }}</p><p class="mt-2 flex flex-wrap items-center gap-1.5 text-[11px] text-slate-500"><span class="h-1.5 w-1.5 rounded-full {{ strtolower($event['status']) === 'ongoing' ? 'bg-emerald-500' : 'bg-[#D4A017]' }}"></span>{{ $event['time'] }} <span class="text-slate-300">·</span> {{ $event['status'] }}</p></div>
                    </a>
                @empty
                    <div class="flex flex-1 flex-col items-center justify-center px-7 py-14 text-center"><div class="rounded-2xl bg-indigo-50 p-4 text-[#101064]"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18"/></svg></div><h3 class="mt-4 text-sm font-bold text-[#101064]">No assigned events yet</h3><p class="mt-2 text-xs leading-5 text-slate-500">Your assigned events will appear here when staff schedule them.</p></div>
                @endforelse
            </div>
            <div class="border-t border-slate-100 bg-slate-50/60 px-5 py-4 sm:px-6">
                <div class="flex items-center justify-between gap-2"><h3 class="text-[11px] font-bold uppercase tracking-[.16em] text-[#101064]">Assignments at a Glance</h3><span class="text-[11px] font-semibold text-[#101064]">{{ $totalEvents }} total</span></div>
                <div class="mt-3 grid grid-cols-2 gap-2">
                    <div class="rounded-xl border border-amber-100 bg-white px-3 py-2"><p class="text-[11px] text-slate-500">Upcoming</p><p class="mt-1 text-xl font-bold text-amber-700">{{ $upcomingEventsCount }}</p></div>
                    <div class="rounded-xl border border-emerald-100 bg-white px-3 py-2"><p class="text-[11px] text-slate-500">Ongoing</p><p class="mt-1 text-xl font-bold text-emerald-700">{{ $ongoingEventsCount }}</p></div>
                    <div class="rounded-xl border border-indigo-100 bg-white px-3 py-2"><p class="text-[11px] text-slate-500">Completed</p><p class="mt-1 text-xl font-bold text-[#101064]">{{ $completedEventsCount }}</p></div>
                    <div class="rounded-xl border border-rose-100 bg-white px-3 py-2"><p class="text-[11px] text-slate-500">Cancelled</p><p class="mt-1 text-xl font-bold text-rose-700">{{ $cancelledEventsCount }}</p></div>
                </div>
            </div>
            <div class="border-t border-slate-100 px-6 py-4"><a href="{{ route('my-events.index') }}" class="text-xs font-bold text-[#101064] hover:underline">View all {{ $totalEvents }} assigned events →</a></div>
        </section>
    </div>


    <section class="space-y-4" aria-labelledby="personnel-current-heading">
        <div class="flex flex-wrap items-end justify-between gap-3"><div><p class="text-[11px] font-semibold uppercase tracking-[.22em] text-[#A9780B]">Assigned Session</p><h2 id="personnel-current-heading" class="mt-1.5 text-xl font-bold text-[#101064]">Current / Next Assigned Event</h2></div></div>
        @if ($currentEvent)
            <div class="flex flex-wrap items-center justify-between gap-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2"><span class="text-[11px] font-bold uppercase tracking-[.16em] text-[#A9780B]">{{ $currentEvent->status === 'Ongoing' ? 'Ongoing now' : 'Next assigned event' }}</span><span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-[#101064]">{{ $currentEvent->status }}</span></div>
                    <h3 class="mt-2 break-words text-lg font-bold text-[#101064]">{{ $currentEvent->event_name }}</h3>
                    <p class="mt-2 text-sm text-slate-500">{{ \Carbon\Carbon::parse($currentEvent->event_date)->format('M d, Y') }} · {{ \Carbon\Carbon::parse($currentEvent->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($currentEvent->end_time)->format('g:i A') }} · {{ $currentEvent->location }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $currentEvent->department?->department_name ?? 'School-wide event' }} · {{ $currentEventScanned }} attendance records</p>
                </div>
                <div class="flex flex-wrap gap-2"><a href="{{ route('my-events.show', $currentEvent->event_id) }}" class="inline-flex min-h-10 items-center rounded-xl border border-slate-200 px-4 text-xs font-bold text-[#101064] hover:bg-slate-50">View Event</a><a href="{{ route('attendance.index', $currentEvent->event_id) }}" class="inline-flex min-h-10 items-center rounded-xl bg-[#101064] px-4 text-xs font-bold text-white hover:bg-[#1B1B85]">Open Attendance</a></div>
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white px-6 py-9 text-center shadow-sm"><h3 class="font-bold text-[#101064]">No ongoing or upcoming assigned event</h3><p class="mt-2 text-sm text-slate-500">You can still review past assignments in the calendar.</p><a href="{{ route('my-events.index') }}" class="mt-4 inline-flex text-xs font-bold text-[#101064] hover:underline">View My Assigned Events</a></div>
        @endif
    </section>

    <section class="space-y-4" aria-labelledby="personnel-recent-heading">
        <div class="flex flex-wrap items-end justify-between gap-3"><div><p class="text-[11px] font-semibold uppercase tracking-[.22em] text-[#A9780B]">Attendance Activity</p><h2 id="personnel-recent-heading" class="mt-1.5 text-xl font-bold text-[#101064]">Recent Attendance Records</h2><p class="mt-1 text-sm text-slate-500">Actual student profile photos and attendance statuses from assigned events.</p></div><a href="{{ route('personnel.attendance-exceptions.index') }}" class="text-xs font-bold text-[#101064] hover:underline">Attendance Exceptions</a></div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @forelse ($recentAttendance as $record)
                @php
                    $student = $record->student;
                    $status = (string) ($record->status ?: 'Unknown');
                    $statusClass = match ($status) {
                        'Present' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                        'Late' => 'border-amber-200 bg-amber-50 text-amber-800',
                        'Checked In' => 'border-blue-200 bg-blue-50 text-blue-700',
                        'Incomplete' => 'border-orange-200 bg-orange-50 text-orange-700',
                        'Absent', 'No Show', 'No-Show' => 'border-red-200 bg-red-50 text-red-700',
                        'Excused' => 'border-slate-200 bg-slate-100 text-slate-700',
                        default => 'border-slate-200 bg-slate-50 text-slate-600',
                    };
                    $initials = strtoupper(substr((string) ($student?->first_name ?? 'S'), 0, 1));
                @endphp
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-5 py-4 last:border-b-0 hover:bg-slate-50/60 sm:px-6">
                    <div class="flex min-w-0 items-center gap-4">
                        <div class="relative flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full border border-indigo-100 bg-indigo-50 text-sm font-bold text-[#101064]">
                            @if($student?->photo_path)
                                <img src="{{ asset($student->photo_path) }}" alt="{{ $student->first_name }} {{ $student->last_name }} photo" class="absolute inset-0 h-full w-full object-cover" onerror="this.style.display='none'">
                            @endif
                            <span>{{ $initials ?: 'S' }}</span>
                        </div>
                        <div class="min-w-0"><p class="truncate text-sm font-bold text-[#101064]">{{ $student?->first_name ?? 'Unknown' }} {{ $student?->last_name ?? 'Student' }}</p><p class="mt-1 truncate text-xs text-slate-500">{{ $student?->student_number ?? 'No student number' }} · {{ $record->event?->event_name ?? 'Deleted Event' }}</p></div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 sm:gap-5"><div class="text-xs text-slate-500 sm:text-right"><p class="font-bold text-slate-700">{{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('g:i A') : 'No Time In' }}</p><p class="mt-1">{{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('M d, Y') : '' }}</p></div><span class="inline-flex rounded-full border px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ $status }}</span></div>
                </div>
            @empty
                <div class="px-6 py-12 text-center"><p class="font-bold text-[#101064]">No attendance records yet</p><p class="mt-2 text-sm text-slate-500">Attendance from your assigned events will appear here.</p></div>
            @endforelse
        </div>
    </section>
</div>
<div id="dyPersonnelDayModal" role="dialog" aria-modal="true" aria-labelledby="dyPersonnelModalTitle" aria-describedby="dyPersonnelModalCount" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/60" aria-hidden="true">
    <div class="dy-personnel-dialog-panel rounded-2xl bg-white shadow-2xl" role="document">
        <div class="relative bg-[#101064] px-5 py-5 text-white sm:px-6">
            <div class="absolute inset-x-0 bottom-0 h-1 bg-[#D4A017]"></div>
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold uppercase tracking-[.22em] text-[#E7C75B]">Assigned Event Schedule</p>
                    <h2 id="dyPersonnelModalTitle" class="mt-2 break-words text-xl font-bold">Events</h2>
                    <p id="dyPersonnelModalCount" class="mt-1.5 text-xs text-indigo-100">Events scheduled for this day</p>
                </div>
                <button id="dyPersonnelModalCloseTop" type="button" aria-label="Close event details" class="shrink-0 rounded-lg border border-white/25 px-3 py-2 text-sm hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#D4A017]">✕</button>
            </div>
        </div>
        <div id="dyPersonnelModalEvents" class="space-y-3 px-4 py-4 sm:px-6 sm:py-5" tabindex="0" aria-label="Events for the selected day; scroll to view more"></div>
        <div class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-5 py-3.5 sm:px-6">
            <p class="text-[11px] text-slate-500">Scroll the list to view more events.</p>
            <button id="dyPersonnelModalClose" type="button" class="shrink-0 rounded-lg bg-[#101064] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#0b0b4d]">Close</button>
        </div>
    </div>
</div>

<script>
(() => {
    'use strict';
    const events = @json($calendarData);
    const grid = document.getElementById('dyPersonnelCalendarGrid');
    const monthLabel = document.getElementById('dyPersonnelMonth');
    const monthFooter = document.getElementById('dyPersonnelMonthLabel');
    const monthCount = document.getElementById('dyPersonnelMonthCount');
    const modal = document.getElementById('dyPersonnelDayModal');
    const modalEvents = document.getElementById('dyPersonnelModalEvents');
    const modalTitle = document.getElementById('dyPersonnelModalTitle');
    const modalCount = document.getElementById('dyPersonnelModalCount');
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
            modalEvents.appendChild(text('p', 'No assigned events on this date.', 'py-10 text-center text-sm text-slate-500'));
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
        document.getElementById('dyPersonnelModalCloseTop').focus();
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
            cell.className = `dy-personnel-calendar-cell min-w-0 cursor-pointer border-b border-r border-slate-100 px-1.5 py-2 ${active ? 'bg-white' : 'bg-slate-50/60'}`;
            cell.addEventListener('click', () => openDay(key));
            const dayButton = document.createElement('button');
            dayButton.type='button';
            dayButton.className=`mb-1 flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold ${today ? 'bg-[#101064] text-white' : active ? 'text-slate-700 hover:bg-indigo-50' : 'text-slate-400'}`;
            dayButton.textContent=String(date.getDate());
            dayButton.setAttribute('aria-label', `${dateFormatter.format(date)}, ${dayEvents.length} assigned events`);
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
    document.getElementById('dyPersonnelToday').addEventListener('click', () => {current=new Date();current.setDate(1);render();});
    document.getElementById('dyPersonnelPrev').addEventListener('click', () => {current.setMonth(current.getMonth()-1);render();});
    document.getElementById('dyPersonnelNext').addEventListener('click', () => {current.setMonth(current.getMonth()+1);render();});
    document.getElementById('dyPersonnelModalClose').addEventListener('click', closeDay);
    document.getElementById('dyPersonnelModalCloseTop').addEventListener('click', closeDay);
    modal.addEventListener('click', event => {if(event.target===modal) closeDay();});
    document.addEventListener('keydown', event => {if(event.key==='Escape' && !modal.classList.contains('hidden')) closeDay();});
    render();
})();
</script>
</x-admin-layout>
