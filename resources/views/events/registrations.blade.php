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
        .module-seven-hero {
            background: linear-gradient(100deg, rgba(16,16,100,.97), rgba(16,16,100,.88) 57%, rgba(16,16,100,.75)),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
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

    <div class="min-w-0 space-y-7">
        <section class="registration-hero relative overflow-hidden rounded-[26px] px-6 py-7 text-white shadow-sm sm:px-8 lg:px-9">
            <div class="absolute inset-x-0 bottom-0 h-[3px] bg-[#D4A017]"></div>
            <a href="{{ route('events.registrations.index') }}" class="relative text-xs font-bold text-[#E7C75B] hover:text-white">← Back to Registration Directory</a>
            <p class="relative mt-4 text-[11px] font-bold uppercase tracking-[.25em] text-[#E7C75B]">PARTICIPANT REGISTRATION · {{ $managedEvent->status }}</p>
            <h1 class="relative mt-2 break-words text-2xl font-bold tracking-tight sm:text-3xl">{{ $managedEvent->event_name }}</h1>
            <p class="relative mt-2 text-sm text-white/75">{{ $managedEvent->event_date?->format('M d, Y') }} · {{ $managedEvent->location ?: 'Venue TBA' }}</p>
        </section>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registered participants</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ $registeredCount }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Eligible students</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ $eligibleCount }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registration status</p>
                <p class="mt-3 font-bold {{ $canEdit ? 'text-emerald-700' : 'text-slate-500' }}">{{ $canEdit ? 'Open' : 'Closed' }}</p>
            </div>
        </div>

        @if ($canEdit)
            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                <h2 class="text-lg font-bold text-[#101064]">Register Eligible Student</h2>
                <p class="mt-1 text-sm text-slate-500">Enter the student's exact school-issued student number. Eligibility is checked automatically.</p>
                <form action="{{ route('events.registrations.store', $managedEvent->event_id) }}" method="POST" class="mt-4 flex flex-col gap-3 sm:flex-row" data-confirm data-confirm-title="Register this participant?" data-confirm-message="Check the student number before adding this participant to the event." data-confirm-confirm-text="Add Participant" data-confirm-variant="default">
                    @csrf
                    <label class="flex-1">
                        <span class="sr-only">Student Number</span>
                        <input type="text" name="student_number" value="{{ old('student_number') }}" maxlength="100" required placeholder="Student Number" class="w-full rounded-xl border-gray-300 text-sm focus:border-[#101064] focus:ring-[#101064]">
                    </label>
                    <button type="submit" class="rounded-xl bg-[#D4A017] px-6 py-3 text-sm font-bold text-[#101064] transition hover:bg-[#E7C75B]">Add Participant</button>
                </form>
            </section>
        @else
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900">Registration changes are closed because this event is no longer upcoming.</div>
        @endif

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="participant-directory-title">
            <div class="flex flex-wrap items-end justify-between gap-3 px-4 pt-5 sm:px-5">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[.22em] text-amber-600">Student registration records</p>
                    <h2 id="participant-directory-title" class="mt-1 text-lg font-bold text-[#101064]">Participant Directory</h2>
                    <p class="mt-1 text-xs text-slate-500">Registration status is not an attendance status. RFID check-in, Present, Late, Absent and Excused are handled separately.</p>
                </div>
                <span class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-[#101064]">{{ $participants->total() }} matching records</span>
            </div>
        <form method="GET" action="{{ route('events.registrations.show', $managedEvent->event_id) }}" class="registration-ui-filters registration-ui-detail-filters">
                <label class="block lg:col-span-4">
                    <span class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">Search student</span>
                    <input type="search" name="search" value="{{ $search }}" maxlength="100" placeholder="Name or student number" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#101064] focus:ring-[#101064]">
                </label>
                <label class="block lg:col-span-2">
                    <span class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">Registration status</span>
                    <select name="registration_status" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        @foreach (['Registered' => 'Registered', 'Cancelled' => 'Cancelled', 'all' => 'All statuses'] as $key => $label)
                            <option value="{{ $key }}" @selected($registrationStatus === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block lg:col-span-2">
                    <span class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">Program</span>
                    <select name="program" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        <option value="">All programs</option>
                        @foreach ($programs as $value)
                            <option value="{{ $value }}" @selected((string) $program === (string) $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block lg:col-span-2">
                    <span class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">Year level</span>
                    <select name="year_level" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        <option value="">All years</option>
                        @foreach ($yearLevels as $value)
                            <option value="{{ $value }}" @selected((string) $yearLevel === (string) $value)>Year {{ $value }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block lg:col-span-2">
                    <span class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">Rows per page</span>
                    <select name="per_page" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        @foreach ([10,25,50] as $amount)
                            <option value="{{ $amount }}" @selected($perPage === $amount)>{{ $amount }} rows</option>
                        @endforeach
                    </select>
                </label>
                <label class="block lg:col-span-2">
                    <span class="mb-1.5 block text-[11px] font-bold uppercase tracking-wide text-slate-500">Sort</span>
                    <select name="sort" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        <option value="latest" @selected($sort === 'latest')>Newest first</option>
                        <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
                    </select>
                </label>
                <div class="flex gap-2 lg:col-span-10 lg:justify-end">
                    <button type="submit" class="rounded-xl bg-[#101064] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#1b1b80]">Apply Filters</button>
                    <a href="{{ route('events.registrations.show', $managedEvent->event_id) }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-center text-xs font-bold text-slate-600 hover:bg-slate-50">Reset</a>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="whitespace-nowrap px-4 py-3 sm:px-5">Student number</th>
                            <th scope="col" class="px-4 py-3 sm:px-5">Student name</th>
                            <th scope="col" class="px-4 py-3 sm:px-5">Program / Year</th>
                            <th scope="col" class="px-4 py-3 sm:px-5">Registered at</th>
                            <th scope="col" class="px-4 py-3 sm:px-5">Source</th>
                            <th scope="col" class="px-4 py-3 sm:px-5">Registration</th>
                            @if ($canEdit)
                                <th scope="col" class="px-4 py-3 sm:px-5">Manage</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($participants as $registration)
                            <tr class="align-middle text-slate-700 hover:bg-slate-50/60">
                                <td class="whitespace-nowrap px-4 py-3 font-semibold text-[#101064] sm:px-5">{{ $registration->student?->student_number ?? '—' }}</td>
                                <td class="px-4 py-3 sm:px-5">{{ $registration->student?->last_name }}, {{ $registration->student?->first_name }}</td>
                                <td class="px-4 py-3 sm:px-5">
                                    <span class="font-semibold">{{ $registration->student?->program_code ?? '—' }}</span>
                                    <span class="block whitespace-nowrap text-xs text-slate-500">Year {{ $registration->student?->year_level ?? '—' }} · {{ $registration->student?->college ?? '—' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-xs sm:px-5">{{ $registration->registered_at?->format('M d, Y g:i A') ?? '—' }}</td>
                                <td class="px-4 py-3 text-xs sm:px-5">{{ $registration->registeredBy?->name ?? $registration->source }}</td>
                                <td class="px-4 py-3 sm:px-5">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $registration->status === 'Registered' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $registration->status }}</span>
                                </td>
                                @if ($canEdit)
                                    <td class="px-4 py-3 sm:px-5">
                                        @if ($registration->status === 'Registered')
                                            <form method="POST" action="{{ route('events.registrations.cancel', [$managedEvent->event_id, $registration->registration_id]) }}" data-confirm data-confirm-title="Unregister participant?" data-confirm-message="Remove this optional student's registration? This cannot be undone without registering again." data-confirm-confirm-text="Unregister">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="whitespace-nowrap rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-50">Unregister</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">—</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="{{ $canEdit ? 7 : 6 }}" class="px-5 py-12 text-center text-sm text-slate-500">No registrations match your filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="space-y-3 border-t border-slate-100 px-4 py-4 sm:px-5">
                <p class="text-xs text-slate-500">Showing {{ $participants->firstItem() ?? 0 }}–{{ $participants->lastItem() ?? 0 }} of {{ number_format($participants->total()) }} registrations</p>
                @if ($participants->hasPages())
                    {{ $participants->links() }}
                @endif
            </div>
        </section>
    </div>
</x-admin-layout>
