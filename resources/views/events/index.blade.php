<x-admin-layout>
    @php
        $currentUser = auth()->user();
        $isDepartmentStaff = $currentUser?->role?->role_name === 'Department Staff';

        // Counts reflect the event records returned by the existing controller.
        $totalEvents = $events->count();
        $upcomingCount = $events->where('status', 'Upcoming')->count();
        $ongoingCount = $events->where('status', 'Ongoing')->count();
        $completedCount = $events->where('status', 'Completed')->count();

        $search = request()->query('search', '');
        $statusFilter = request()->query('status', '');
        $dateFilter = request()->query('event_date', '');
        $departmentFilter = request()->query('department_id', '');

        $hasFilters = filled($search)
            || (filled($statusFilter) && $statusFilter !== 'all')
            || filled($dateFilter)
            || (! $isDepartmentStaff && filled($departmentFilter));
    @endphp

    <style>
        /* Only styles this page; keeps the standard DySign admin layout intact. */
        .dysign-events-hero {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            background-image:
                linear-gradient(100deg,
                    rgba(16, 16, 100, .96) 0%,
                    rgba(16, 16, 100, .89) 52%,
                    rgba(16, 16, 100, .70) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        .dysign-events-circle {
            position: absolute;
            display: block;
            width: 410px;
            height: 410px;
            top: -145px;
            right: -55px;
            border: 2px solid rgba(255, 255, 255, .25);
            border-radius: 50%;
            pointer-events: none;
        }
        .dysign-events-circle:nth-child(2) {
            width: 300px;
            height: 300px;
            top: -90px;
            right: 0;
            border-color: rgba(255, 255, 255, .22);
        }
        .dysign-events-circle:nth-child(3) {
            width: 190px;
            height: 190px;
            top: -35px;
            right: 55px;
            border-color: rgba(255, 255, 255, .19);
        }
        .dysign-events-input {
            width: 100%;
            min-height: 45px;
            padding: 10px 13px;
            border: 1px solid #e2e6ef;
            border-radius: 10px;
            background-color: #fff;
            color: #171c50;
            font-size: 13px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .dysign-events-input:focus {
            outline: none;
            border-color: #101064;
            box-shadow: 0 0 0 3px rgba(16,16,100,.09);
        }
        .dysign-events-input:disabled {
            background-color: #f6f7fa;
            color: #49536d;
            cursor: not-allowed;
        }
        .dysign-events-table { width: 100%; border-collapse: collapse; }
        .dysign-events-table th {
            padding: 15px 20px;
            font-size: 10px;
            font-weight: 700;
            line-height: 1.4;
            letter-spacing: .11em;
            text-transform: uppercase;
            text-align: left;
            color: #98a0b5;
            background: #fafbfe;
            border-bottom: 1px solid #eaedf3;
            white-space: nowrap;
        }
        .dysign-events-table td {
            padding: 19px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #eef0f5;
        }
        .dysign-events-table tbody tr:last-child td { border-bottom: 0; }
        .dysign-events-table tbody tr:hover { background: #fcfcff; }
        .dysign-events-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border-radius: 999px;
            padding: 6px 11px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }
        .dysign-events-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }
        @media (max-width: 640px) {
            .dysign-events-circle { transform: translateX(160px); }
            .dysign-events-table th, .dysign-events-table td { padding-left: 15px; padding-right: 15px; }
        }
    </style>

    <div class="w-full min-w-0 space-y-7 pb-8">
        {{-- Match the campus-photo hero used across Create, Edit and Event Details. --}}
        <section class="dysign-events-hero rounded-[28px] text-white">
            <div class="pointer-events-none absolute inset-0 z-0" aria-hidden="true">
                <span class="dysign-events-circle"></span>
                <span class="dysign-events-circle"></span>
                <span class="dysign-events-circle"></span>
            </div>
            <div class="absolute inset-x-0 bottom-0 z-20 h-1 bg-[#D4A017]"></div>

            <div class="relative z-10 flex min-h-[210px] flex-col justify-center gap-6 px-7 py-9 sm:px-9 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="min-w-0 max-w-3xl">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="h-[2px] w-8 bg-[#D4A017]"></span>
                        <span class="text-[11px] font-bold uppercase tracking-[.27em] text-[#F0C65B]">Event Administration</span>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Event Management</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-white/80">
                        Create, schedule, review, and manage institutional event records.
                    </p>
                </div>

                @if ($isDepartmentStaff)
                    <a href="{{ route('events.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 self-start rounded-xl bg-[#D4A017] px-6 py-3 text-sm font-bold text-[#101064] shadow-lg shadow-black/10 transition hover:bg-[#E8B735] focus:outline-none focus:ring-4 focus:ring-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                        Create Event
                    </a>
                @endif
            </div>
        </section>

        {{-- Familiar original DySign summary style, no heavy outlines. --}}
        <section aria-labelledby="event-summary-title">
            <div class="mb-4">
                <p class="text-[11px] font-bold uppercase tracking-[.23em] text-[#BD8D18]">Overview</p>
                <h2 id="event-summary-title" class="mt-2 text-xl font-bold text-[#101064]">Event Record Summary</h2>
            </div>

            <div class="grid grid-cols-2 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm lg:grid-cols-4">
                <div class="border-b border-r border-gray-100 px-6 py-6 lg:border-b-0">
                    <p class="text-[11px] font-semibold uppercase tracking-[.09em] text-slate-400">Total Events</p>
                    <p class="mt-3 text-3xl font-bold text-[#101064]">{{ $totalEvents }}</p>
                    <p class="mt-1 text-xs text-slate-400">Visible records</p>
                </div>
                <div class="border-b border-gray-100 px-6 py-6 lg:border-b-0 lg:border-r">
                    <p class="text-[11px] font-semibold uppercase tracking-[.09em] text-slate-400">Upcoming</p>
                    <p class="mt-3 text-3xl font-bold text-[#C69312]">{{ $upcomingCount }}</p>
                    <p class="mt-1 text-xs text-slate-400">Scheduled events</p>
                </div>
                <div class="border-r border-gray-100 px-6 py-6">
                    <p class="text-[11px] font-semibold uppercase tracking-[.09em] text-slate-400">Ongoing</p>
                    <p class="mt-3 text-3xl font-bold text-green-600">{{ $ongoingCount }}</p>
                    <p class="mt-1 text-xs text-slate-400">Currently active</p>
                </div>
                <div class="px-6 py-6">
                    <p class="text-[11px] font-semibold uppercase tracking-[.09em] text-slate-400">Completed</p>
                    <p class="mt-3 text-3xl font-bold text-slate-600">{{ $completedCount }}</p>
                    <p class="mt-1 text-xs text-slate-400">Finished events</p>
                </div>
            </div>
        </section>

        {{-- Directory: same section-heading style as the rest of DySign. --}}
        <section aria-labelledby="event-directory-title">
            <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[.23em] text-[#BD8D18]">Event Registry</p>
                    <h2 id="event-directory-title" class="mt-2 text-xl font-bold text-[#101064]">Event Directory</h2>
                    <p class="mt-1 text-sm text-slate-400">Search and review official event records.</p>
                </div>
                @if ($hasFilters)
                    <a href="{{ route('events.index') }}" class="text-sm font-semibold text-[#101064] underline decoration-[#D4A017] underline-offset-4">Clear Filters</a>
                @endif
            </div>

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <form method="GET" action="{{ route('events.index') }}" class="border-b border-gray-100 px-6 py-5">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <label for="search" class="mb-2 block text-[11px] font-bold uppercase tracking-[.07em] text-slate-500">Search</label>
                            <input id="search" type="search" name="search" value="{{ $search }}" placeholder="Event name or location..." class="dysign-events-input" autocomplete="off">
                        </div>
                        <div>
                            <label for="department_id" class="mb-2 block text-[11px] font-bold uppercase tracking-[.07em] text-slate-500">Department</label>
                            @if ($isDepartmentStaff)
                                <input id="department_id" class="dysign-events-input" value="{{ $currentUser->department?->department_name ?? 'Assigned department' }}" disabled>
                            @else
                                <select id="department_id" name="department_id" class="dysign-events-input">
                                    <option value="">All Departments</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->department_id }}" @selected((string) $departmentFilter === (string) $department->department_id)>{{ $department->department_name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div>
                            <label for="status" class="mb-2 block text-[11px] font-bold uppercase tracking-[.07em] text-slate-500">Status</label>
                            <select id="status" name="status" class="dysign-events-input">
                                <option value="">All Statuses</option>
                                @foreach (['Upcoming', 'Ongoing', 'Completed', 'Cancelled'] as $option)
                                    <option value="{{ $option }}" @selected($statusFilter === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="event_date" class="mb-2 block text-[11px] font-bold uppercase tracking-[.07em] text-slate-500">Event Date</label>
                            <input id="event_date" type="date" name="event_date" value="{{ $dateFilter }}" class="dysign-events-input">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#101064] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#25258A]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m16 16 5 5"/></svg>
                            Search Events
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="dysign-events-table min-w-[950px]">
                        <thead>
                            <tr>
                                <th scope="col" style="width:32%">Event</th>
                                <th scope="col" style="width:19%">Schedule</th>
                                <th scope="col" style="width:19%">Venue</th>
                                <th scope="col" style="width:10%">Personnel</th>
                                <th scope="col" style="width:10%">Status</th>
                                <th scope="col" style="width:10%; text-align:right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($events as $event)
                                @php
                                    $eventStatus = $event->status ?? 'Upcoming';
                                    $statusClasses = match ($eventStatus) {
                                        'Upcoming' => 'bg-amber-50 text-amber-700',
                                        'Ongoing' => 'bg-green-50 text-green-700',
                                        'Completed' => 'bg-slate-100 text-slate-600',
                                        'Cancelled' => 'bg-red-50 text-red-700',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                    $personnelCount = (int) ($event->assignments_count ?? $event->assignments?->count() ?? 0);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="flex min-w-0 items-start gap-3">
                                            <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#F1F2FA] text-[#101064]" aria-hidden="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4M17 3v4M3 10h18"/></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <a href="{{ route('events.show', $event->event_id) }}" class="block break-words text-sm font-bold leading-6 text-[#101064] hover:underline hover:decoration-[#D4A017] hover:underline-offset-4">
                                                    {{ $event->event_name }}
                                                </a>
                                                <p class="mt-1 break-words text-xs text-slate-400">{{ $event->department?->department_name ?? 'Unassigned Department' }} · Event #{{ $event->event_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-semibold text-[#101064]">{{ $event->event_date ? \Illuminate\Support\Carbon::parse($event->event_date)->format('M d, Y') : 'Date not set' }}</p>
                                        <p class="mt-1 whitespace-nowrap text-xs text-slate-400">
                                            @if ($event->start_time && $event->end_time)
                                                {{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }} – {{ \Illuminate\Support\Carbon::parse($event->end_time)->format('g:i A') }}
                                            @else
                                                Time not set
                                            @endif
                                        </p>
                                    </td>
                                    <td class="break-words text-sm text-slate-600">{{ $event->location ?: 'Not specified' }}</td>
                                    <td>
                                        <span class="whitespace-nowrap rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600">{{ $personnelCount }} assigned</span>
                                    </td>
                                    <td>
                                        <span class="dysign-events-status {{ $statusClasses }}">{{ $eventStatus }}</span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('events.show', $event->event_id) }}" class="whitespace-nowrap text-xs font-bold text-[#101064] transition hover:text-[#B18116]">
                                            View Event <span aria-hidden="true">→</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-14 text-center">
                                        <p class="text-base font-bold text-[#101064]">No events found</p>
                                        <p class="mt-2 text-sm text-slate-500">Try a different search or remove some filters.</p>
                                        @if ($hasFilters)
                                            <a href="{{ route('events.index') }}" class="mt-4 inline-block text-sm font-semibold text-[#101064] underline decoration-[#D4A017] underline-offset-4">Clear Filters</a>
                                        @elseif ($isDepartmentStaff)
                                            <a href="{{ route('events.create') }}" class="mt-5 inline-flex rounded-lg bg-[#101064] px-5 py-2.5 text-sm font-semibold text-white">Create Event</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-admin-layout>
