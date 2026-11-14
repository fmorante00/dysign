<x-admin-layout>
    <style>
        .dy-elig-hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background: linear-gradient(103deg, rgba(13,14,88,.97), rgba(16,16,100,.92) 53%, rgba(16,16,100,.69)),
                url('{{ asset('images/school.jpg') }}') center / cover;
        }
        .dy-elig-ring {
            position: absolute;
            pointer-events: none;
            border: 2px solid rgba(255,255,255,.19);
            border-radius: 9999px;
            width: 405px;
            height: 405px;
            top: -148px;
            right: -40px;
        }
        .dy-elig-ring:nth-child(2) { width: 300px; height: 300px; top: -94px; right: 12px; border-color: rgba(255,255,255,.17); }
        .dy-elig-ring:nth-child(3) { width: 195px; height: 195px; top: -40px; right: 64px; border-color: rgba(255,255,255,.14); }
        .dy-elig-control {
            width: 100%; min-height: 46px; padding: 10px 13px;
            border: 1px solid #e2e8f0; border-radius: 12px;
            background: #fff; color: #101064; font-size: 13px;
        }
        .dy-elig-control:focus { outline: none; border-color: #101064; box-shadow: 0 0 0 3px rgba(16,16,100,.08); }
        .dy-elig-event:hover { border-color: #c6ceed; box-shadow: 0 8px 26px rgba(16,16,100,.055); }
        @media(max-width: 640px) { .dy-elig-ring { transform: translateX(170px); } }
    </style>

    <div class="min-w-0 space-y-7 pb-9">
        <section class="dy-elig-hero rounded-[28px] text-white">
            <div class="absolute inset-0 z-0" aria-hidden="true">
                <span class="dy-elig-ring"></span><span class="dy-elig-ring"></span><span class="dy-elig-ring"></span>
            </div>
            <div class="absolute inset-x-0 bottom-0 z-20 h-1 bg-[#D4A017]"></div>
            <div class="relative z-10 flex min-h-[205px] flex-wrap items-center justify-between gap-6 px-7 py-9 sm:px-9 lg:px-10">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[.28em] text-[#F0C65B]">Event Management · Participant Eligibility</p>
                    <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">{{ $isAdministrator ? 'Eligibility Oversight' : 'Participant Eligibility' }}</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-white/80">
                        @if($isAdministrator)
                            Review eligibility rules and student participation for every institutional event, across all departments and statuses.
                        @else
                            Manage upcoming eligibility and review ongoing, completed, or cancelled events in your department.
                        @endif
                    </p>
                    <div class="mt-5 flex flex-wrap items-center gap-4 text-xs text-white/80">
                        <span>{{ $eventTotals['total'] }} event records</span>
                        <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-[#E7C75B]"></span>{{ $isAdministrator ? 'Administrator · View Only' : 'Department Staff · Management Access' }}</span>
                    </div>
                </div>
                @if($isAdministrator)
                    <span class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-xs font-bold text-white backdrop-blur-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        Read-only access
                    </span>
                @endif
            </div>
        </section>

        <section aria-labelledby="eligibility-overview-heading">
            <div class="mb-4">
                <p class="text-[11px] font-bold uppercase tracking-[.24em] text-[#B8890F]">Institutional Overview</p>
                <h2 id="eligibility-overview-heading" class="mt-1.5 text-lg font-bold text-[#101064]">Event Eligibility Summary</h2>
                <p class="mt-1 text-xs text-slate-500">All authorized event records, independent of the current filters.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                @foreach([
                    ['name' => 'Total Events', 'value' => $eventTotals['total'], 'color' => '#101064', 'detail' => 'Registered events'],
                    ['name' => 'Upcoming', 'value' => $eventTotals['Upcoming'], 'color' => '#B8890F', 'detail' => 'Scheduled events'],
                    ['name' => 'Ongoing', 'value' => $eventTotals['Ongoing'], 'color' => '#059669', 'detail' => 'In progress'],
                    ['name' => 'Completed', 'value' => $eventTotals['Completed'], 'color' => '#475569', 'detail' => 'Finished events'],
                    ['name' => 'Cancelled', 'value' => $eventTotals['Cancelled'], 'color' => '#DC2626', 'detail' => 'Cancelled events'],
                ] as $stat)
                    <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm">
                        <p class="text-[10px] font-bold uppercase tracking-[.12em] text-slate-500">{{ $stat['name'] }}</p>
                        <p class="mt-2 text-3xl font-bold" style="color: {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                        <p class="mt-2 border-t border-slate-100 pt-2 text-xs text-slate-500">{{ $stat['detail'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section aria-labelledby="eligibility-directory-title">
            <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[.24em] text-[#B8890F]">Event Registry</p>
                    <h2 id="eligibility-directory-title" class="mt-1.5 text-lg font-bold text-[#101064]">{{ $isAdministrator ? 'All Events · Eligibility Review' : 'Department Events · Eligibility & History' }}</h2>
                    <p class="mt-1 text-xs text-slate-500">Select an event to {{ $isAdministrator ? 'view its configured rules and eligible students' : 'manage upcoming eligibility or review saved participation history' }}.</p>
                </div>
                <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-[#101064]">{{ $events->total() }} matching events</span>
            </div>

            <form method="GET" action="{{ route('events.eligibility.events') }}" class="mb-4 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 {{ $isAdministrator ? 'xl:grid-cols-5' : 'xl:grid-cols-3' }}">
                <label class="block {{ $isAdministrator ? 'xl:col-span-2' : '' }}">
                    <span class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Search events</span>
                    <input class="dy-elig-control" name="search" value="{{ $search }}" placeholder="Event title or venue" maxlength="120" />
                </label>
                <label class="block">
                        <span class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Status</span>
                        <select class="dy-elig-control" name="status">
                            @foreach(['all' => 'All Statuses', 'Upcoming' => 'Upcoming', 'Ongoing' => 'Ongoing', 'Completed' => 'Completed', 'Cancelled' => 'Cancelled'] as $value => $label)
                                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                @if($isAdministrator)
                    <label class="block">
                        <span class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Department</span>
                        <select class="dy-elig-control" name="department_id">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->department_id }}" @selected($departmentId === (string) $department->department_id)>{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                    </label>
                @endif
                <div class="flex items-end gap-2">
                    <button class="min-h-[46px] flex-1 rounded-xl bg-[#101064] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#181877]" type="submit">Search Records</button>
                    <a class="inline-flex min-h-[46px] items-center rounded-xl border border-slate-200 px-3 text-xs font-semibold text-slate-600 hover:bg-slate-50" href="{{ route('events.eligibility.events') }}">Reset</a>
                </div>
            </form>

            <div class="space-y-3">
                @forelse($events as $event)
                    @php
                        $badgeClasses = match($event->status) {
                            'Upcoming' => 'bg-amber-50 text-amber-700',
                            'Ongoing' => 'bg-emerald-50 text-emerald-700',
                            'Completed' => 'bg-slate-100 text-slate-700',
                            'Cancelled' => 'bg-red-50 text-red-700',
                            default => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <article class="dy-elig-event overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition">
                        <div class="flex flex-col gap-5 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="inline-flex rounded-full px-3 py-1 font-semibold {{ $badgeClasses }}">{{ $event->status }}</span>
                                    <span class="text-slate-400">Event #{{ $event->event_id }}</span>
                                    <span class="text-slate-400">{{ $event->department?->department_name ?? 'University-wide' }}</span>
                                </div>
                                <h3 class="mt-2.5 break-words text-base font-bold text-[#101064]">{{ $event->event_name }}</h3>
                                <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-xs text-slate-500">
                                    <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                    <span>{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}–{{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</span>
                                    <span>{{ $event->location ?: 'No venue specified' }}</span>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 sm:flex-nowrap">
                                <div class="flex rounded-xl bg-slate-50 px-4 py-3 text-center">
                                    <div class="min-w-[65px]"><p class="text-[10px] uppercase tracking-wide text-slate-400">Rules</p><p class="mt-1 font-bold text-[#101064]">{{ $event->eligibility_rules_count }}</p></div>
                                    <div class="min-w-[72px] border-l border-slate-200"><p class="text-[10px] uppercase tracking-wide text-slate-400">Eligible</p><p class="mt-1 font-bold text-emerald-700">{{ $event->eligible_students_count }}</p></div>
                                </div>
                                <a href="{{ route('events.eligibility.index', $event->event_id) }}" class="inline-flex min-h-[46px] flex-1 items-center justify-center whitespace-nowrap rounded-xl bg-[#101064] px-5 py-3 text-xs font-bold text-white transition hover:bg-[#202075] sm:flex-none">
                                    {{ $isAdministrator || $event->status !== 'Upcoming' ? 'View Eligibility' : 'Manage Eligibility' }} <span class="ml-2" aria-hidden="true">→</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-slate-200 bg-white px-6 py-14 text-center">
                        <h3 class="font-bold text-[#101064]">No matching events found</h3>
                        <p class="mt-2 text-sm text-slate-500">{{ $isAdministrator ? 'Try removing filters to review all events.' : 'No department events match your search and status filters.' }}</p>
                        <a href="{{ route('events.eligibility.events') }}" class="mt-4 inline-block rounded-lg border border-slate-200 px-4 py-2 text-xs font-bold text-[#101064]">Clear Filters</a>
                    </div>
                @endforelse
            </div>

            @if($events->hasPages())
                <div class="mt-5 rounded-2xl border border-slate-200 bg-white px-5 py-4">
                    <div class="mb-3 text-xs text-slate-500">Showing {{ $events->firstItem() }}–{{ $events->lastItem() }} of {{ $events->total() }} events</div>
                    {{ $events->links() }}
                </div>
            @endif
        </section>
    </div>
</x-admin-layout>
