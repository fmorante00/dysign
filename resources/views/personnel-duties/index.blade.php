<x-admin-layout>

<style>
    .duty-hero {
        background:
            linear-gradient(
                100deg,
                rgba(16,16,100,.97) 0%,
                rgba(16,16,100,.92) 52%,
                rgba(16,16,100,.74) 100%
            ),
            url('{{ asset('images/school.jpg') }}');

        background-size: cover;
        background-position: center;
    }
</style>
<style>
    /* My Duties V3 additions only. Keep DySign's original navy/gold cards and layout. */
    #dy-personnel-duties .dy-v3-filter-panel { background:#fff; border:1px solid #e5e7eb; border-radius:16px; box-shadow:0 1px 3px rgba(16,16,100,.045); }
    #dy-personnel-duties .dy-v3-filter-label { display:block; margin-bottom:6px; color:#63738d; font-size:11px; font-weight:700; }
    #dy-personnel-duties .dy-v3-input { width:100%; min-width:0; border:1px solid #cdd6e6; border-radius:10px; background:#fff; padding:10px 12px; color:#101064; font-size:13px; min-height:42px; }
    #dy-personnel-duties .dy-v3-input:focus { outline:2px solid #e6bf52; outline-offset:1px; border-color:#b18a24; }
    #dy-personnel-duties .dy-v3-advanced { border:1px solid #e5eaf2 !important; background:#f8faff !important; }
    #dy-personnel-duties .dy-v3-count { color:#64748b; font-weight:600; }
    #dy-personnel-duties .dy-v3-pagebtn { display:inline-flex; justify-content:center; align-items:center; min-height:36px; padding:7px 12px; border-radius:9px; border:1px solid #d7dce7; background:#fff; color:#101064; font-size:12px; font-weight:750; }
    #dy-personnel-duties .dy-v3-pagebtn[aria-current="page"] { background:#101064; color:#fff; border-color:#101064; }
    #dy-personnel-duties .dy-v3-pagebtn:disabled { opacity:.45; cursor:default; }
    #dy-personnel-duties [data-duty-item][hidden] { display:none !important; }
</style>


<div class="w-full min-w-0 space-y-7" id="dy-personnel-duties">

    {{-- HERO --}}
    <section
        class="
            duty-hero
            relative
            overflow-hidden
            rounded-[28px]
            px-8
            py-9
            text-white
            lg:px-10
            lg:py-10
        "
    >
        <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>

        <div class="relative z-10 max-w-3xl">
            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.30em]
                    text-[#E7C75B]
                "
            >
                Personnel Duty Management
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">
                My Assigned Duties
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">
                Record your check-in and check-out for assigned events
                and monitor your duty completion status.
            </p>
        </div>
    </section>


    {{-- ALERTS --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4">
            <p class="text-sm font-bold text-green-700">
                Success
            </p>

            <p class="mt-1 text-sm text-green-600">
                {{ session('success') }}
            </p>
        </div>
    @endif


    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">
            <p class="text-sm font-bold text-red-700">
                Action could not be completed
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- SUMMARY --}}
    <section
        class="
            grid
            grid-cols-2
            overflow-hidden
            rounded-2xl
            border
            border-gray-200
            bg-white
            md:grid-cols-3
            xl:grid-cols-6
        "
    >
        @php
            $summaryCards = [
                [
                    'label' => 'Total Duties',
                    'value' => $summary['total'],
                    'color' => 'text-[#101064]',
                ],
                [
                    'label' => 'Assigned',
                    'value' => $summary['assigned'],
                    'color' => 'text-[#101064]',
                ],
                [
                    'label' => 'On Duty',
                    'value' => $summary['on_duty'],
                    'color' => 'text-blue-600',
                ],
                [
                    'label' => 'Completed',
                    'value' => $summary['completed'],
                    'color' => 'text-green-600',
                ],
                [
                    'label' => 'Missed',
                    'value' => $summary['missed'],
                    'color' => 'text-red-600',
                ],
                [
                    'label' => 'Incomplete',
                    'value' => $summary['incomplete'],
                    'color' => 'text-amber-600',
                ],
            ];
        @endphp

        @foreach($summaryCards as $card)
            <div class="border-b border-r border-gray-100 px-5 py-5">
                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    {{ $card['label'] }}
                </p>

                <p class="mt-2 text-2xl font-bold {{ $card['color'] }}">
                    {{ $card['value'] }}
                </p>
            </div>
        @endforeach
    </section>


    {{-- INFO --}}
    <section
        class="
            flex
            items-start
            gap-4
            rounded-2xl
            border
            border-blue-100
            bg-blue-50/60
            px-5
            py-4
        "
    >
        <div
            class="
                flex
                h-9
                w-9
                shrink-0
                items-center
                justify-center
                rounded-full
                bg-[#101064]
                text-white
            "
        >
            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
        </div>

        <div>
            <p class="text-sm font-bold text-[#101064]">
                Duty Check-In Rule
            </p>

            <p class="mt-1 text-xs leading-5 text-gray-600">
                Check-in and check-out actions become available only
                while the assigned event is ongoing.
            </p>
        </div>
    </section>


    {{-- DUTY LIST HEADER --}}
    <section>
        <div class="mb-4">
            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.28em]
                    text-[#D4A017]
                "
            >
                Event Duties
            </p>

            <h2 class="mt-2 text-xl font-bold text-[#101064]">
                Duty Records
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Review your assigned events and duty completion history.
            </p>
        </div>



        {{-- Compact filters: original DySign duty cards below are unchanged. --}}
        @if($duties->isNotEmpty())
        <div class="dy-v3-filter-panel mb-5 p-5 md:p-6">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#bd8b15]">Find a duty</p>
                    <h3 class="mt-1 text-base font-bold text-[#101064]">Search and filter records</h3>
                </div>
                <span class="rounded-full bg-[#f4f5fa] px-3 py-1.5 text-xs font-semibold text-[#101064]">{{ $summary['total'] }} assigned duties</span>
            </div>
            <div class="grid gap-3 md:grid-cols-4">
                <div class="md:col-span-2">
                    <label class="dy-v3-filter-label" for="dy-duty-search">Search event or location</label>
                    <input id="dy-duty-search" class="dy-v3-input" type="search" placeholder="Search your assigned events..." autocomplete="off">
                </div>
                <div>
                    <label class="dy-v3-filter-label" for="dy-duty-status">Duty status</label>
                    <select id="dy-duty-status" class="dy-v3-input">
                        <option value="">All duty statuses</option>
                        <option>Assigned</option><option>On Duty</option><option>Completed</option><option>Missed</option><option>Incomplete</option>
                    </select>
                </div>
                <div>
                    <label class="dy-v3-filter-label" for="dy-duty-sort">Sort records</label>
                    <select id="dy-duty-sort" class="dy-v3-input">
                        <option value="priority">On Duty first</option>
                        <option value="newest">Newest event first</option>
                        <option value="oldest">Oldest event first</option>
                        <option value="name">Event name A–Z</option>
                    </select>
                </div>
            </div>
            <details class="dy-v3-advanced mt-4 rounded-xl px-4 py-3">
                <summary class="cursor-pointer text-xs font-bold text-[#101064]">Advanced filters — event status and date range</summary>
                <div class="mt-4 grid gap-3 md:grid-cols-3">
                    <div>
                        <label class="dy-v3-filter-label" for="dy-duty-event-status">Event status</label>
                        <select id="dy-duty-event-status" class="dy-v3-input">
                            <option value="">All event statuses</option>
                            @foreach($duties->pluck('event_status')->filter()->unique()->sort()->values() as $eventStatus)
                                <option value="{{ $eventStatus }}">{{ $eventStatus }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="dy-v3-filter-label" for="dy-duty-from">From date</label><input type="date" id="dy-duty-from" class="dy-v3-input"></div>
                    <div><label class="dy-v3-filter-label" for="dy-duty-to">To date</label><input type="date" id="dy-duty-to" class="dy-v3-input"></div>
                </div>
            </details>
            <div class="mt-4 flex flex-wrap items-end justify-between gap-3">
                <div class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="dy-v3-filter-label" for="dy-duty-page-size">Show per page</label>
                        <select id="dy-duty-page-size" class="dy-v3-input" style="width:auto;min-width:130px"><option value="5">5 duties</option><option value="10">10 duties</option><option value="20">20 duties</option></select>
                    </div>
                    <button type="button" id="dy-duty-reset" class="rounded-lg border border-[#d1d8e5] bg-white px-4 py-2.5 text-xs font-bold text-[#101064] hover:bg-[#f6f8fc]" style="min-height:42px">Reset</button>
                </div>
                <p id="dy-duty-count" class="dy-v3-count text-xs" aria-live="polite" role="status"></p>
            </div>
        </div>
        @endif

        <div class="space-y-5" id="dy-duty-list">

            @forelse($duties as $duty)

                @php
                    $statusClasses = match($duty->duty_status) {
                        'Completed' => 'bg-green-50 text-green-700 border-green-100',
                        'On Duty' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'Missed' => 'bg-red-50 text-red-700 border-red-100',
                        'Incomplete' => 'bg-amber-50 text-amber-700 border-amber-100',
                        default => 'bg-gray-100 text-gray-600 border-gray-200',
                    };

                    $hours =
                        $duty->completed_minutes !== null
                            ? intdiv((int) $duty->completed_minutes, 60)
                            : 0;

                    $minutes =
                        $duty->completed_minutes !== null
                            ? ((int) $duty->completed_minutes % 60)
                            : 0;
                @endphp


                <article
                    class="
                        overflow-hidden
                        rounded-2xl
                        border
                        border-gray-200
                        bg-white
                        shadow-sm
                    "
                    data-duty-item
                    data-search="{{ mb_strtolower(($duty->event_name ?? '').' '.($duty->location ?? '').' '.($duty->event_status ?? '')) }}"
                    data-duty-status="{{ $duty->duty_status ?? 'Assigned' }}"
                    data-event-status="{{ $duty->event_status }}"
                    data-date="{{ \Carbon\Carbon::parse($duty->event_date)->format('Y-m-d') }}"
                    data-event-name="{{ mb_strtolower($duty->event_name ?? '') }}"
                >

                    {{-- CARD HEADER --}}
                    <div
                        class="
                            flex
                            flex-col
                            gap-4
                            border-b
                            border-gray-100
                            px-6
                            py-5
                            md:flex-row
                            md:items-center
                            md:justify-between
                        "
                    >

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        border
                                        px-3
                                        py-1
                                        text-xs
                                        font-bold
                                        {{ $statusClasses }}
                                    "
                                >
                                    {{ $duty->duty_status }}
                                </span>

                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        bg-gray-100
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-gray-600
                                    "
                                >
                                    {{ $duty->event_status }}
                                </span>
                            </div>

                            <h3
                                class="
                                    mt-3
                                    break-words
                                    text-xl
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                {{ $duty->event_name }}
                            </h3>
                        </div>


                        <div class="shrink-0">

                            @if(
                                $duty->event_status === 'Ongoing'
                                && !$duty->check_in_at
                            )
                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'personnel-duties.check-in',
                                            $duty->assignment_id
                                        )
                                    }}"
                                 data-confirm data-confirm-title="Check in for this duty?" data-confirm-message="Your duty check-in time will be recorded." data-confirm-confirm-text="Check In" data-confirm-variant="default">
                                    @csrf

                                    <button
                                        type="submit"
                                        class="
                                            inline-flex
                                            min-w-[150px]
                                            items-center
                                            justify-center
                                            rounded-lg
                                            bg-[#101064]
                                            px-5
                                            py-3
                                            text-sm
                                            font-bold
                                            text-white
                                            transition
                                            hover:bg-[#D4A017]
                                            hover:text-[#101064]
                                        "
                                    >
                                        Check In
                                    </button>
                                </form>

                            @elseif(
                                $duty->event_status === 'Ongoing'
                                && $duty->check_in_at
                                && !$duty->check_out_at
                            )
                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'personnel-duties.check-out',
                                            $duty->assignment_id
                                        )
                                    }}"
                                 data-confirm data-confirm-title="Check out from this duty?" data-confirm-message="Your duty check-out time will be recorded." data-confirm-confirm-text="Check Out" data-confirm-variant="warning">
                                    @csrf

                                    <button
                                        type="submit"
                                        class="
                                            inline-flex
                                            min-w-[150px]
                                            items-center
                                            justify-center
                                            rounded-lg
                                            bg-green-600
                                            px-5
                                            py-3
                                            text-sm
                                            font-bold
                                            text-white
                                            transition
                                            hover:bg-green-700
                                        "
                                    >
                                        Check Out
                                    </button>
                                </form>

                            @elseif($duty->duty_status === 'Completed')
                                <span
                                    class="
                                        inline-flex
                                        min-w-[150px]
                                        justify-center
                                        rounded-lg
                                        bg-green-50
                                        px-4
                                        py-3
                                        text-xs
                                        font-bold
                                        text-green-700
                                    "
                                >
                                    Duty Completed
                                </span>

                            @elseif($duty->duty_status === 'Missed')
                                <span
                                    class="
                                        inline-flex
                                        min-w-[150px]
                                        justify-center
                                        rounded-lg
                                        bg-red-50
                                        px-4
                                        py-3
                                        text-xs
                                        font-bold
                                        text-red-700
                                    "
                                >
                                    Duty Missed
                                </span>

                            @elseif($duty->duty_status === 'Incomplete')
                                <span
                                    class="
                                        inline-flex
                                        min-w-[150px]
                                        justify-center
                                        rounded-lg
                                        bg-amber-50
                                        px-4
                                        py-3
                                        text-xs
                                        font-bold
                                        text-amber-700
                                    "
                                >
                                    Incomplete Duty
                                </span>

                            @else
                                <span
                                    class="
                                        inline-flex
                                        min-w-[150px]
                                        justify-center
                                        rounded-lg
                                        bg-gray-100
                                        px-4
                                        py-3
                                        text-xs
                                        font-semibold
                                        text-gray-500
                                    "
                                >
                                    Waiting for Event
                                </span>
                            @endif

                        </div>
                    </div>


                    {{-- EVENT INFORMATION --}}
                    <div
                        class="
                            grid
                            grid-cols-1
                            gap-0
                            sm:grid-cols-2
                            xl:grid-cols-4
                        "
                    >

                        <div class="border-b border-gray-100 px-6 py-5 sm:border-r">
                            <p
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Event Date
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                {{
                                    \Carbon\Carbon::parse(
                                        $duty->event_date
                                    )->format('F d, Y')
                                }}
                            </p>
                        </div>


                        <div class="border-b border-gray-100 px-6 py-5 xl:border-r">
                            <p
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Event Time
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                {{
                                    \Carbon\Carbon::parse(
                                        $duty->start_time
                                    )->format('g:i A')
                                }}
                                –
                                {{
                                    \Carbon\Carbon::parse(
                                        $duty->end_time
                                    )->format('g:i A')
                                }}
                            </p>
                        </div>


                        <div class="border-b border-gray-100 px-6 py-5 sm:border-r">
                            <p
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Location
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                {{ $duty->location ?? 'Not specified' }}
                            </p>
                        </div>


                        <div class="border-b border-gray-100 px-6 py-5">
                            <p
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Completed Duty Time
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                @if($duty->completed_minutes !== null)

                                    @if($hours > 0)
                                        {{ $hours }} hr
                                    @endif

                                    {{ $minutes }} min

                                @else
                                    —
                                @endif
                            </p>
                        </div>

                    </div>


                    {{-- CHECK IN / CHECK OUT --}}
                    <div
                        class="
                            grid
                            grid-cols-1
                            bg-gray-50/60
                            md:grid-cols-2
                        "
                    >

                        <div class="border-b border-gray-100 px-6 py-5 md:border-b-0 md:border-r">
                            <p
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Check-In Record
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                @if($duty->check_in_at)
                                    {{
                                        \Carbon\Carbon::parse(
                                            $duty->check_in_at
                                        )->format('F d, Y · g:i A')
                                    }}
                                @else
                                    Not recorded
                                @endif
                            </p>
                        </div>


                        <div class="px-6 py-5">
                            <p
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Check-Out Record
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                @if($duty->check_out_at)
                                    {{
                                        \Carbon\Carbon::parse(
                                            $duty->check_out_at
                                        )->format('F d, Y · g:i A')
                                    }}
                                @else
                                    Not recorded
                                @endif
                            </p>
                        </div>

                    </div>

                </article>

            @empty

                <div
                    class="
                        rounded-2xl
                        border
                        border-dashed
                        border-gray-300
                        bg-white
                        px-6
                        py-16
                        text-center
                    "
                >
                    <div
                        class="
                            mx-auto
                            flex
                            h-12
                            w-12
                            items-center
                            justify-center
                            rounded-full
                            bg-gray-100
                            text-[#101064]
                        "
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 7V3m8 4V3M5 11h14M5 7h14v14H5z"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-4 font-bold text-[#101064]">
                        No Assigned Duties
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Your assigned event duties will appear here.
                    </p>
                </div>

            @endforelse

        </div>
        @if($duties->isNotEmpty())
            <div id="dy-duty-empty" class="dy-v3-filter-panel mt-4 hidden px-6 py-10 text-center">
                <p class="text-base font-bold text-[#101064]">No matching duties</p>
                <p class="mt-2 text-sm text-gray-500">Try another search or reset the filters.</p>
                <button type="button" id="dy-duty-empty-reset" class="mt-4 rounded-lg bg-[#101064] px-5 py-2.5 text-xs font-bold text-white">Reset filters</button>
            </div>
            <div id="dy-duty-pagination-wrap" class="mt-5 flex flex-wrap items-center justify-between gap-3">
                <p id="dy-duty-page-label" class="text-xs font-medium text-gray-500"></p>
                <nav id="dy-duty-pages" class="flex flex-wrap gap-2" aria-label="Duty pages"></nav>
            </div>
        @endif
    </section>

</div>

<script>
(function () {
    const root = document.getElementById('dy-personnel-duties');
    if (!root || !root.querySelector('#dy-duty-search')) return;
    const get = id => root.querySelector('#' + id);
    const list = get('dy-duty-list');
    const cards = Array.from(list.querySelectorAll('[data-duty-item]'));
    const search = get('dy-duty-search');
    const status = get('dy-duty-status');
    const eventStatus = get('dy-duty-event-status');
    const from = get('dy-duty-from');
    const to = get('dy-duty-to');
    const sort = get('dy-duty-sort');
    const perPage = get('dy-duty-page-size');
    const nav = get('dy-duty-pages');
    const empty = get('dy-duty-empty');
    const countLabel = get('dy-duty-count');
    const pageLabel = get('dy-duty-page-label');
    const pageWrap = get('dy-duty-pagination-wrap');
    let page = 1;
    const ranks = {'On Duty':0,'Assigned':1,'Incomplete':2,'Missed':3,'Completed':4};
    function render() {
        const term = search.value.trim().toLocaleLowerCase();
        const matching = cards.filter(card => {
            const d = card.dataset;
            return (!term || d.search.includes(term)) &&
                (!status.value || d.dutyStatus === status.value) &&
                (!eventStatus.value || d.eventStatus === eventStatus.value) &&
                (!from.value || d.date >= from.value) &&
                (!to.value || d.date <= to.value);
        });
        matching.sort((a,b) => {
            const x=a.dataset, y=b.dataset;
            if (sort.value === 'name') return x.eventName.localeCompare(y.eventName) || y.date.localeCompare(x.date);
            if (sort.value === 'oldest') return x.date.localeCompare(y.date) || x.eventName.localeCompare(y.eventName);
            if (sort.value === 'newest') return y.date.localeCompare(x.date) || x.eventName.localeCompare(y.eventName);
            return (ranks[x.dutyStatus] ?? 5)-(ranks[y.dutyStatus] ?? 5) || y.date.localeCompare(x.date);
        });
        const size = Number(perPage.value) || 5;
        const totalPages = Math.max(1, Math.ceil(matching.length / size));
        page = Math.max(1,Math.min(page,totalPages));
        const start=(page-1)*size;
        const current=new Set(matching.slice(start,start+size));
        cards.forEach(card => { card.hidden = !current.has(card); });
        matching.forEach(card => list.appendChild(card));
        countLabel.textContent = matching.length + ' matching of ' + cards.length + ' duties';
        pageLabel.textContent = matching.length ? 'Showing ' + (start+1) + '–' + Math.min(start+size,matching.length) + ' of ' + matching.length : 'No records to show';
        empty.classList.toggle('hidden',matching.length !== 0);
        pageWrap.classList.toggle('hidden',matching.length === 0);
        nav.replaceChildren();
        function button(label, target, disabled, active) {
            const el=document.createElement('button');
            el.type='button';el.className='dy-v3-pagebtn';el.textContent=label;el.disabled=disabled;
            if (active) el.setAttribute('aria-current','page');
            el.addEventListener('click',() => { page=target;render(); });
            nav.appendChild(el);
        }
        if (matching.length > size) {
            button('Previous',page-1,page===1,false);
            const pStart=Math.max(1,Math.min(page-2,totalPages-4));
            for(let p=pStart;p<=Math.min(totalPages,pStart+4);p++) button(String(p),p,false,p===page);
            button('Next',page+1,page===totalPages,false);
        }
    }
    function reset(){search.value='';status.value='';eventStatus.value='';from.value='';to.value='';sort.value='priority';perPage.value='5';page=1;render();}
    [search,status,eventStatus,from,to,sort,perPage].forEach(el => el.addEventListener(el===search?'input':'change',()=>{page=1;render();}));
    get('dy-duty-reset').addEventListener('click',reset);
    get('dy-duty-empty-reset').addEventListener('click',reset);
    render();
})();
</script>
</x-admin-layout>