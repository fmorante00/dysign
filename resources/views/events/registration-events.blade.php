<x-admin-layout>
    <style id="dysign-registration-visual-v2">
/* DySign Registration UI v2 — scoped to registration pages only.
   Native CSS layout is intentional: works even when utility classes are absent from the compiled Tailwind bundle. */
.registration-hero {
    position: relative !important;
    isolation: isolate;
    overflow: hidden !important;
    border-radius: 24px !important;
    border-bottom: 3px solid #d4a017;
    min-height: 176px;
    padding: 30px 34px !important;
}
.registration-hero::before,
.registration-hero::after {
    content: "";
    position: absolute;
    display: block;
    border: 2px solid rgba(255,255,255,.25);
    border-radius: 50%;
    background: transparent;
    pointer-events: none;
    z-index: 0;
}
.registration-hero::before {
    width: 480px;
    height: 480px;
    right: -120px;
    top: -156px;
}
.registration-hero::after {
    width: 344px;
    height: 344px;
    right: -27px;
    top: -90px;
    border-color: rgba(255,255,255,.17);
}
.registration-hero > * { position: relative; z-index: 1; }
.registration-hero > [class*="bottom-0"][class*="absolute"] { position: absolute; }

/* Both directory filter forms are explicitly gridded regardless of Tailwind build. */
.registration-ui-filters {
    display: grid !important;
    grid-template-columns: repeat(12, minmax(0, 1fr));
    gap: 14px 16px !important;
    align-items: end;
    padding: 18px 20px !important;
    background: #fbfcff;
    border-bottom: 1px solid #e4e8f0;
    box-sizing: border-box;
}
.registration-ui-filters > label {
    display: block !important;
    margin: 0;
    min-width: 0;
}
.registration-ui-filters > label > span {
    display: block !important;
    margin: 0 0 7px !important;
    font-size: 10px !important;
    font-weight: 800 !important;
    letter-spacing: .065em;
    line-height: 1.5;
    text-transform: uppercase;
    color: #596c91;
}
.registration-ui-filters input,
.registration-ui-filters select {
    display: block !important;
    width: 100% !important;
    min-width: 0;
    height: 43px !important;
    margin: 0 !important;
    padding: 0 13px !important;
    box-sizing: border-box !important;
    border: 1px solid #d9e0ee !important;
    border-radius: 10px !important;
    background-color: #fff !important;
    color: #17255b !important;
    font-size: 12px !important;
    font-family: inherit;
    line-height: 1.25 !important;
    box-shadow: none !important;
}
.registration-ui-filters input::placeholder { color: #92a0b8; opacity: 1; }
.registration-ui-filters input:focus,
.registration-ui-filters select:focus {
    outline: 2px solid rgba(212,160,23,.28) !important;
    outline-offset: 1px;
    border-color: #d4a017 !important;
}
.registration-ui-filters > div {
    display: flex !important;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 8px !important;
    min-width: 0;
}
.registration-ui-filters > div > button,
.registration-ui-filters > div > a {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    width: auto;
    min-height: 43px;
    padding: 0 17px !important;
    box-sizing: border-box;
    border-radius: 10px !important;
    font-family: inherit;
    font-size: 12px !important;
    font-weight: 800 !important;
    text-decoration: none !important;
    cursor: pointer;
    white-space: nowrap;
}
.registration-ui-filters > div > button {
    color: white !important;
    background-color: #101064 !important;
    border: 1px solid #101064 !important;
}
.registration-ui-filters > div > a {
    color: #101064 !important;
    background: white !important;
    border: 1px solid #d9e0ee !important;
}
.registration-ui-filters > div > button:hover { background: #232383 !important; }
.registration-ui-filters > div > a:hover { background: #f0f3fb !important; }

/* Event directory: one compact row on wide screens. */
.registration-ui-events > label:nth-of-type(1) { grid-column: span 5; }
.registration-ui-events > label:nth-of-type(2) { grid-column: span 3; }
.registration-ui-events > label:nth-of-type(3) { grid-column: span 2; }
.registration-ui-events > div { grid-column: span 2; }

/* Participant directory: useful, balanced two-row filter panel. */
.registration-ui-details > label:nth-of-type(1) { grid-column: span 4; }
.registration-ui-details > label:nth-of-type(2) { grid-column: span 3; }
.registration-ui-details > label:nth-of-type(3) { grid-column: span 3; }
.registration-ui-details > label:nth-of-type(4) { grid-column: span 2; }
.registration-ui-details > label:nth-of-type(5) { grid-column: span 2; }
.registration-ui-details > label:nth-of-type(6) { grid-column: span 2; }
.registration-ui-details > div { grid-column: span 8; }

@media (max-width: 1250px) {
    .registration-ui-events > label:nth-of-type(1) { grid-column: span 6; }
    .registration-ui-events > label:nth-of-type(2) { grid-column: span 6; }
    .registration-ui-events > label:nth-of-type(3) { grid-column: span 5; }
    .registration-ui-events > div { grid-column: span 7; }
    .registration-ui-details > label:nth-of-type(1),
    .registration-ui-details > label:nth-of-type(2),
    .registration-ui-details > label:nth-of-type(3),
    .registration-ui-details > label:nth-of-type(4) { grid-column: span 6; }
    .registration-ui-details > label:nth-of-type(5),
    .registration-ui-details > label:nth-of-type(6) { grid-column: span 3; }
    .registration-ui-details > div { grid-column: span 6; }
}
@media (max-width: 700px) {
    .registration-hero { min-height: 145px; padding: 24px 22px !important; }
    .registration-hero::before { width: 275px; height: 275px; top: -95px; right: -126px; }
    .registration-hero::after { width: 195px; height: 195px; top: -53px; right: -74px; }
    .registration-ui-filters { grid-template-columns: minmax(0,1fr); padding: 16px !important; gap: 12px !important; }
    .registration-ui-filters > label,
    .registration-ui-filters > div { grid-column: 1 / -1 !important; }
    .registration-ui-filters > div { justify-content: stretch; }
    .registration-ui-filters > div > button { flex: 1; }
}

    </style>
    <style>
        .registration-hero {
            position: relative;
            isolation: isolate;
            background: linear-gradient(105deg, rgba(16,16,100,.98), rgba(16,16,100,.91) 54%, rgba(16,16,100,.78)), url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        .registration-hero::before,
        .registration-hero::after {
            content: '';
            position: absolute;
            display: block;
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .registration-hero::before {
            width: 360px;
            height: 360px;
            right: -40px;
            top: -205px;
        }
        .registration-hero::after {
            width: 245px;
            height: 245px;
            right: 17px;
            top: -136px;
            border-color: rgba(255,255,255,.12);
        }
        .registration-hero > * { position: relative; z-index: 1; }
    </style>

    <div class="min-w-0 space-y-6">
        <section class="registration-hero relative overflow-hidden rounded-[26px] px-6 py-7 text-white shadow-sm sm:px-8 lg:px-9">
            <div class="absolute inset-x-0 bottom-0 h-[3px] bg-[#D4A017]"></div>
            <p class="text-[11px] font-bold uppercase tracking-[.26em] text-[#E7C75B]">EVENT PARTICIPATION · DEPARTMENT STAFF</p>
            <div class="relative mt-2 flex flex-wrap items-end justify-between gap-5">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Participant Registration</h1>
                    <p class="mt-2 max-w-2xl text-sm text-white/75">Review optional registrations for events owned by your department. Past records remain available for review.</p>
                </div>
                <div class="rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-semibold backdrop-blur-sm">
                    {{ number_format($totalEvents) }} department events
                </div>
            </div>
        </section>

        <section class="grid grid-cols-2 gap-3 lg:grid-cols-4" aria-label="Registration overview">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">All events</p>
                <p class="mt-2 text-3xl font-extrabold text-[#101064]">{{ number_format($totalEvents) }}</p>
                <p class="mt-1 text-xs text-slate-500">Own department only</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Active registrations</p>
                <p class="mt-2 text-3xl font-extrabold text-[#101064]">{{ number_format($totalRegistrations) }}</p>
                <p class="mt-1 text-xs text-slate-500">Optional students who registered</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Upcoming</p>
                <p class="mt-2 text-3xl font-extrabold text-amber-600">{{ number_format($upcomingCount) }}</p>
                <p class="mt-1 text-xs text-slate-500">Registration may be open</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Completed</p>
                <p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ number_format($completedCount) }}</p>
                <p class="mt-1 text-xs text-slate-500">Read-only history</p>
            </div>
        </section>

        <section aria-labelledby="registration-directory-title" class="space-y-3">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[.22em] text-amber-600">Registration directory</p>
                    <h2 id="registration-directory-title" class="mt-1 text-lg font-bold text-[#101064]">Department Event Registry</h2>
                    <p class="mt-1 text-xs text-slate-500">Search events, filter by status, and review their registered participants.</p>
                </div>
                <span class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-[#101064]">{{ number_format($events->total()) }} matching events</span>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <form method="GET" action="{{ route('events.registrations.index') }}" class="grid grid-cols-1 gap-3 border-b border-slate-100 p-4 md:grid-cols-12 md:items-end sm:p-5 registration-ui-filters registration-ui-events">
                    <label class="block md:col-span-5">
                        <span class="mb-2 block text-[11px] font-bold uppercase tracking-wider text-slate-500">Search event / venue</span>
                        <input type="search" name="search" value="{{ $search }}" maxlength="120" placeholder="Find event by name or venue" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#101064] focus:ring-[#101064]">
                    </label>
                    <label class="block md:col-span-3">
                        <span class="mb-2 block text-[11px] font-bold uppercase tracking-wider text-slate-500">Event status</span>
                        <select name="status" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#101064] focus:ring-[#101064]">
                            @foreach (['all' => 'All statuses', 'Upcoming' => 'Upcoming', 'Ongoing' => 'Ongoing', 'Completed' => 'Completed', 'Cancelled' => 'Cancelled'] as $value => $label)
                                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="block md:col-span-2">
                        <span class="mb-2 block text-[11px] font-bold uppercase tracking-wider text-slate-500">Rows per page</span>
                        <select name="per_page" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#101064] focus:ring-[#101064]">
                            @foreach ([10,25,50] as $amount)
                                <option value="{{ $amount }}" @selected($perPage === $amount)>{{ $amount }} events</option>
                            @endforeach
                        </select>
                    </label>
                    <div class="flex items-center gap-2 md:col-span-2">
                        <button type="submit" class="flex-1 rounded-xl bg-[#101064] px-3 py-2.5 text-xs font-bold text-white hover:bg-[#1b1b80]">Apply</button>
                        <a href="{{ route('events.registrations.index') }}" class="rounded-xl border border-slate-200 px-3 py-2.5 text-center text-xs font-semibold text-slate-600 hover:bg-slate-50">Reset</a>
                    </div>
                </form>

                <div class="divide-y divide-slate-100">
                    @forelse ($events as $event)
                        @php
                            $statusColors = [
                                'Upcoming' => 'border-amber-100 bg-amber-50 text-amber-700',
                                'Ongoing' => 'border-emerald-100 bg-emerald-50 text-emerald-700',
                                'Completed' => 'border-slate-200 bg-slate-100 text-slate-600',
                                'Cancelled' => 'border-red-100 bg-red-50 text-red-700',
                            ];
                            $registrationCount = (int) ($counts[$event->event_id] ?? 0);
                        @endphp
                        <article class="flex flex-col gap-4 px-4 py-4 hover:bg-slate-50/60 sm:px-5 lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex min-w-0 items-start gap-4">
                                <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50 text-[#101064]">
                                    <span class="text-[10px] font-bold uppercase">{{ $event->event_date?->format('M') }}</span>
                                    <span class="text-xl font-extrabold leading-6">{{ $event->event_date?->format('d') }}</span>
                                </div>
                                <div class="min-w-0 space-y-1.5">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $statusColors[$event->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $event->status }}</span>
                                        <span class="rounded-full border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-600">{{ $registrationCount }} registered</span>
                                        <span class="rounded-full border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-600">{{ (int) $event->eligible_students_count }} eligible</span>
                                    </div>
                                    <h3 class="truncate text-sm font-bold text-[#101064] sm:text-base">{{ $event->event_name }}</h3>
                                    <p class="text-xs text-slate-500">{{ $event->event_date?->format('M d, Y') }} · {{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : 'TBA' }} · {{ $event->location ?: 'Venue TBA' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('events.registrations.show', $event->event_id) }}" class="inline-flex shrink-0 items-center justify-center rounded-xl {{ $event->status === 'Upcoming' ? 'bg-[#101064] text-white hover:bg-[#1b1b80]' : 'border border-slate-200 bg-white text-[#101064] hover:bg-slate-50' }} px-4 py-2.5 text-xs font-bold">
                                {{ $event->status === 'Upcoming' ? 'Manage Registrations' : 'View Participants' }} <span class="ml-2" aria-hidden="true">→</span>
                            </a>
                        </article>
                    @empty
                        <div class="px-6 py-14 text-center">
                            <p class="text-sm font-bold text-[#101064]">No matching events</p>
                            <p class="mt-2 text-xs text-slate-500">Try a different search or status filter.</p>
                        </div>
                    @endforelse
                </div>
                <div class="flex flex-col gap-3 border-t border-slate-100 px-4 py-4 sm:px-5">
                    <p class="text-xs text-slate-500">Showing {{ $events->firstItem() ?? 0 }}–{{ $events->lastItem() ?? 0 }} of {{ number_format($events->total()) }} events</p>
                    @if ($events->hasPages())
                        {{ $events->links() }}
                    @endif
                </div>
            </div>
            <p class="text-xs text-slate-500">Registration is for <strong>optional</strong> participation. Required eligible students are already included in the expected attendance roster. Attendance status is managed separately through RFID/Attendance.</p>
        </section>
    </div>
</x-admin-layout>
