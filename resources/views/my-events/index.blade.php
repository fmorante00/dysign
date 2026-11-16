<x-admin-layout>

@php

    $timezone = 'Asia/Manila';

    $now =
        \Carbon\Carbon::now(
            $timezone
        );


    /*
    |--------------------------------------------------------------------------
    | Safe Event Collection
    |--------------------------------------------------------------------------
    */

    $events =
        $events
        ?? collect();


    /*
    |--------------------------------------------------------------------------
    | Safe Event Date/Time Builder
    |--------------------------------------------------------------------------
    |
    | event_date is cast as a date in the Event model, so Laravel may return:
    |
    | 2026-10-05 00:00:00
    |
    | We must first normalize the date to Y-m-d before combining it with
    | start_time or end_time. This prevents Carbon's:
    |
    | "Double time specification"
    |
    */

    $buildEventDateTime =
        function ($event, $time) use ($timezone) {

            if (
                !$event->event_date
                || !$time
            ) {
                return null;
            }


            $eventDate =
                \Carbon\Carbon::parse(
                    $event->event_date
                )->format('Y-m-d');


            $eventTime =
                \Carbon\Carbon::parse(
                    $time
                )->format('H:i:s');


            return \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $eventDate
                . ' '
                . $eventTime,
                $timezone
            );

        };


    /*
    |--------------------------------------------------------------------------
    | Schedule-Based Event Status
    |--------------------------------------------------------------------------
    */

    $computedStatuses = [];

    $ongoingCount = 0;

    $upcomingCount = 0;

    $completedCount = 0;


    foreach ($events as $item) {

        /*
        |--------------------------------------------------------------------------
        | Cancelled Events
        |--------------------------------------------------------------------------
        */

        if (
            $item->status
            === 'Cancelled'
        ) {

            $computedStatuses[
                $item->event_id
            ] = 'Cancelled';

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Build Schedule
        |--------------------------------------------------------------------------
        */

        $startsAt =
            $buildEventDateTime(
                $item,
                $item->start_time
            );


        $endsAt =
            $buildEventDateTime(
                $item,
                $item->end_time
            );


        /*
        |--------------------------------------------------------------------------
        | Missing Schedule Information
        |--------------------------------------------------------------------------
        */

        if (
            !$startsAt
            || !$endsAt
        ) {

            $computedStatuses[
                $item->event_id
            ] =
                $item->status
                ?? 'Upcoming';

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Determine Current Status
        |--------------------------------------------------------------------------
        */

        if (
            $now->lt(
                $startsAt
            )
        ) {

            $status =
                'Upcoming';

            $upcomingCount++;

        } elseif (
            $now->lte(
                $endsAt
            )
        ) {

            $status =
                'Ongoing';

            $ongoingCount++;

        } else {

            $status =
                'Completed';

            $completedCount++;

        }


        $computedStatuses[
            $item->event_id
        ] = $status;

    }

@endphp


<style>

    .assigned-events-hero {

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


/* Assigned Events: accessible, responsive filters and pagination. */
#ae-assigned-schedule [hidden] { display: none !important; }
.ae-filter-panel { border: 1px solid #dfe5f2; border-radius: 20px; background: #fff; padding: 20px; box-shadow: 0 6px 24px rgba(17,24,75,.035); margin-bottom: 18px; }
.ae-filter-heading { display:flex; justify-content:space-between; align-items:flex-start; gap: 16px; flex-wrap:wrap; margin-bottom: 16px; }
.ae-filter-title { color:#101064; font-size:15px; font-weight:800; }
.ae-filter-caption { color:#72809a; font-size:12px; margin-top:4px; }
.ae-filter-grid { display:grid; grid-template-columns:repeat(12,minmax(0,1fr)); column-gap:12px; row-gap:15px; }
.ae-field { display:flex; flex-direction:column; min-width:0; gap:6px; }
.ae-field label { font-size:11px; font-weight:700; color:#49587a; }
.ae-field input, .ae-field select { box-sizing:border-box; width:100%; min-height:42px; border:1px solid #ced8e9; border-radius:11px; background:#fff; padding: 9px 11px; color:#101064; font-size:13px; outline:none; }
.ae-field input:focus, .ae-field select:focus { border-color:#d4a017; box-shadow:0 0 0 3px rgba(212,160,23,.14); }
.ae-span-4 { grid-column:span 4; } .ae-span-3 { grid-column:span 3; } .ae-span-2 { grid-column:span 2; }
.ae-filter-actions { display:flex; align-items:end; gap:10px; }
.ae-reset { display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:8px 15px; border:1px solid #ced8e9; border-radius:11px; font-size:12px; font-weight:750; color:#101064; background:#fff; cursor:pointer; }
.ae-reset:hover { background:#f2f5fb; }
.ae-count { color:#596782; font-size:12px; }
.ae-results-top { display:flex; align-items:center; justify-content:space-between; gap:14px; margin: 10px 0 12px; flex-wrap:wrap; }
.ae-no-results { padding:36px 20px; text-align:center; color:#71809a; font-size:13px; border-top:1px solid #e9edf5; }
.ae-no-results strong { display:block; margin-bottom:6px; color:#101064; font-size:15px; }
.ae-pagination { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; padding:15px 18px; background:#f8f9fc; border-top:1px solid #e4e9f2; }
.ae-pagination-info { font-size:12px; color:#5b6984; }
.ae-page-controls { display:flex; align-items:center; gap:5px; flex-wrap:wrap; }
.ae-page-button { padding:7px 10px; min-width:33px; min-height:34px; background:#fff; color:#101064; border:1px solid #dbe2ef; border-radius:9px; font-size:12px; font-weight:700; cursor:pointer; }
.ae-page-button:hover:not(:disabled) { border-color:#d4a017; }
.ae-page-button:disabled { cursor:default; opacity:.40; }
.ae-page-button.is-active { border-color:#101064; background:#101064; color:#fff; }
.ae-page-dots { padding:0 4px; font-size:11px; color:#7a86a0; }
@media (max-width: 950px) { .ae-field.ae-span-4,.ae-field.ae-span-3,.ae-field.ae-span-2 { grid-column:span 6; } }
@media (max-width: 600px) { .ae-filter-panel { padding:16px; } .ae-field.ae-span-4,.ae-field.ae-span-3,.ae-field.ae-span-2 { grid-column:span 12; } .ae-pagination { align-items:stretch; } .ae-page-controls { width:100%; } }

</style>


<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            assigned-events-hero
            relative
            overflow-hidden
            rounded-[28px]
            px-7
            py-8
            text-white
            sm:px-8
            lg:px-10
            lg:py-10
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
                relative
                z-10
                flex
                flex-col
                gap-8
                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >

            <div class="max-w-3xl">


                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.35em]
                        text-[#E7C75B]
                    "
                >
                    Attendance Operations
                </p>


                <h1
                    class="
                        mt-3
                        text-3xl
                        font-bold
                        tracking-tight
                        md:text-4xl
                    "
                >
                    My Assigned Events
                </h1>


                <p
                    class="
                        mt-3
                        max-w-2xl
                        text-sm
                        leading-6
                        text-white/75
                    "
                >
                    Review your assigned events, monitor attendance activity,
                    and open the RFID scanner during active event schedules.
                </p>


                <div
                    class="
                        mt-6
                        flex
                        flex-wrap
                        gap-x-8
                        gap-y-3
                        text-sm
                        text-white/70
                    "
                >

                    <div
                        class="
                            flex
                            items-center
                            gap-2
                        "
                    >

                        <svg
                            class="
                                h-4
                                w-4
                                text-[#E7C75B]
                            "
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"
                            />

                        </svg>


                        {{ $now->format('F d, Y') }}

                    </div>


                    <div
                        class="
                            flex
                            items-center
                            gap-2
                        "
                    >

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-green-400
                            "
                        ></span>

                        Attendance Personnel Workspace

                    </div>

                </div>

            </div>



            <div
                class="
                    hidden
                    shrink-0
                    items-center
                    gap-4
                    rounded-2xl
                    border
                    border-white/15
                    bg-white/10
                    px-6
                    py-5
                    backdrop-blur-sm
                    lg:flex
                "
            >

                <div
                    class="
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-xl
                        bg-white/10
                        text-[#E7C75B]
                    "
                >

                    <svg
                        class="
                            h-6
                            w-6
                        "
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M13 10V3L4 14h7v7l9-11h-7z"
                        />

                    </svg>

                </div>


                <div>

                    <p
                        class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.22em]
                            text-[#E7C75B]
                        "
                    >
                        RFID Service
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            font-semibold
                            text-white
                        "
                    >
                        Automatic Event Window
                    </p>

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SUMMARY --}}
    {{-- ====================================================== --}}

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
                Assignment Overview
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Event Status
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Event availability is calculated from the scheduled start and end time.
            </p>

        </div>


        <div
            class="
                grid
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                md:grid-cols-3
            "
        >


            {{-- ONGOING --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-b-0
                    md:border-r
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.16em]
                        text-gray-400
                    "
                >
                    Ongoing
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-bold
                        text-green-600
                    "
                >
                    {{ number_format($ongoingCount) }}
                </p>


                <p
                    class="
                        mt-1
                        text-xs
                        text-gray-400
                    "
                >
                    Scanner available now
                </p>

            </div>



            {{-- UPCOMING --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-b-0
                    md:border-r
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.16em]
                        text-gray-400
                    "
                >
                    Upcoming
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-bold
                        text-[#D4A017]
                    "
                >
                    {{ number_format($upcomingCount) }}
                </p>


                <p
                    class="
                        mt-1
                        text-xs
                        text-gray-400
                    "
                >
                    Scheduled assignments
                </p>

            </div>



            {{-- COMPLETED --}}

            <div
                class="
                    px-6
                    py-6
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.16em]
                        text-gray-400
                    "
                >
                    Completed
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-bold
                        text-gray-600
                    "
                >
                    {{ number_format($completedCount) }}
                </p>


                <p
                    class="
                        mt-1
                        text-xs
                        text-gray-400
                    "
                >
                    Attendance sessions closed
                </p>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ASSIGNED EVENTS --}}
    {{-- ====================================================== --}}

    <section>


        <div
            class="
                mb-4
                flex
                flex-col
                gap-3
                sm:flex-row
                sm:items-end
                sm:justify-between
            "
        >

            <div>

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.28em]
                        text-[#D4A017]
                    "
                >
                    Assigned Schedule
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Assigned Events
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Open an event to review its attendance session.
                </p>

            </div>



            <div
                class="
                    inline-flex
                    w-fit
                    items-center
                    gap-2
                    rounded-full
                    bg-[#F1F2FA]
                    px-3
                    py-1.5
                    text-xs
                    font-semibold
                    text-[#101064]
                "
            >

                <span
                    class="
                        h-1.5
                        w-1.5
                        rounded-full
                        bg-[#D4A017]
                    "
                ></span>


                {{ number_format($events->count()) }}

                {{
                    $events->count() === 1
                        ? 'Event'
                        : 'Events'
                }}

            </div>

        </div>




        {{-- Search, schedule filters and pagination apply to the already-authorized assigned events only. --}}
        <div id="ae-assigned-schedule">
            <div class="ae-filter-panel">
                <div class="ae-filter-heading">
                    <div>
                        <div class="ae-filter-title">Find an assigned event</div>
                        <div class="ae-filter-caption">Search by event or venue, refine the schedule, then open the event to view attendance.</div>
                    </div>
                    <button type="button" class="ae-reset" id="ae-reset">Reset Filters</button>
                </div>
                <div class="ae-filter-grid">
                    <div class="ae-field ae-span-4">
                        <label for="ae-search">Search events</label>
                        <input type="search" id="ae-search" placeholder="Event name, venue, department" autocomplete="off" />
                    </div>
                    <div class="ae-field ae-span-3">
                        <label for="ae-status">Event status</label>
                        <select id="ae-status">
                            <option value="all">All statuses</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Upcoming">Upcoming</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="ae-field ae-span-3">
                        <label for="ae-sort">Sort by</label>
                        <select id="ae-sort">
                            <option value="priority">Default priority</option>
                            <option value="date-asc">Schedule: earliest first</option>
                            <option value="date-desc">Schedule: latest first</option>
                            <option value="name-asc">Event name: A to Z</option>
                            <option value="name-desc">Event name: Z to A</option>
                        </select>
                    </div>
                    <div class="ae-field ae-span-2">
                        <label for="ae-size">Per page</label>
                        <select id="ae-size">
                            <option value="5">5 events</option>
                            <option value="10">10 events</option>
                            <option value="20">20 events</option>
                        </select>
                    </div>
                    <div class="ae-field ae-span-4">
                        <label for="ae-from">Event date from</label>
                        <input type="date" id="ae-from" />
                    </div>
                    <div class="ae-field ae-span-4">
                        <label for="ae-to">Event date to</label>
                        <input type="date" id="ae-to" />
                    </div>
                    <div class="ae-field ae-span-4">
                        <label>Matching results</label>
                        <div style="min-height:42px;display:flex;align-items:center;padding:8px 12px;border:1px solid #e1e7f1;border-radius:11px;background:#f8f9fc;">
                            <span id="ae-results" class="ae-count" role="status" aria-live="polite">{{ $events->count() }} assigned events</span>
                        </div>
                    </div>
                </div>
            </div>

        <div
            class="
                overflow-hidden
                rounded-2xl
                border
                border-gray-200
                bg-white
            "
            id="ae-event-list"
        >

            @forelse($events as $event)


                @php

                    /*
                    |--------------------------------------------------------------------------
                    | Current Display Status
                    |--------------------------------------------------------------------------
                    */

                    $status =
                        $computedStatuses[
                            $event->event_id
                        ]
                        ?? $event->status;


                    /*
                    |--------------------------------------------------------------------------
                    | Safe Schedule Parsing
                    |--------------------------------------------------------------------------
                    */

                    $startsAt =
                        $buildEventDateTime(
                            $event,
                            $event->start_time
                        );


                    $endsAt =
                        $buildEventDateTime(
                            $event,
                            $event->end_time
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Status Style
                    |--------------------------------------------------------------------------
                    */

                    $statusStyle =
                        match ($status) {

                            'Ongoing' => [
                                'class' =>
                                    'bg-green-50 text-green-700',

                                'dot' =>
                                    'bg-green-500',
                            ],


                            'Upcoming' => [
                                'class' =>
                                    'bg-[#FFF8E1] text-[#A87900]',

                                'dot' =>
                                    'bg-[#D4A017]',
                            ],


                            'Completed' => [
                                'class' =>
                                    'bg-gray-100 text-gray-600',

                                'dot' =>
                                    'bg-gray-500',
                            ],


                            default => [
                                'class' =>
                                    'bg-red-50 text-red-600',

                                'dot' =>
                                    'bg-red-500',
                            ],

                        };

                @endphp



                <div
                    class="
                        ae-event-row
                        border-b
                        border-gray-100
                        p-6
                        transition
                        last:border-b-0
                        hover:bg-gray-50/60
                        lg:px-7
                    "
                    data-search="{{ $event->event_name }} {{ $event->location }} {{ $event->department?->department_name }}"
                    data-status="{{ $status }}"
                    data-date="{{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') : '' }}"
                    data-datetime="{{ $startsAt?->format('Y-m-d H:i:s') ?? '' }}"
                    data-name="{{ $event->event_name }}"
                >

                    <div
                        class="
                            flex
                            flex-col
                            gap-6
                            lg:flex-row
                            lg:items-center
                            lg:justify-between
                        "
                    >


                        {{-- EVENT DETAILS --}}

                        <div class="min-w-0">


                            <div
                                class="
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-3
                                "
                            >

                                <h3
                                    class="
                                        break-words
                                        text-xl
                                        font-bold
                                        text-[#101064]
                                    "
                                >
                                    {{ $event->event_name }}
                                </h3>


                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-full
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        {{ $statusStyle['class'] }}
                                    "
                                >

                                    <span
                                        class="
                                            h-1.5
                                            w-1.5
                                            rounded-full
                                            {{ $statusStyle['dot'] }}
                                        "
                                    ></span>


                                    {{ $status }}

                                </span>

                            </div>



                            <p
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-500
                                "
                            >
                                {{
                                    $event
                                        ->department
                                        ->department_name
                                    ?? 'University-wide Event'
                                }}
                            </p>



                            <div
                                class="
                                    mt-4
                                    flex
                                    flex-wrap
                                    gap-x-5
                                    gap-y-2
                                    text-sm
                                    text-gray-500
                                "
                            >


                                {{-- DATE --}}

                                <span>

                                    {{
                                        $startsAt
                                            ? $startsAt->format(
                                                'F d, Y'
                                            )
                                            : 'Date unavailable'
                                    }}

                                </span>



                                {{-- TIME --}}

                                <span>

                                    {{
                                        $startsAt
                                            ? $startsAt->format(
                                                'g:i A'
                                            )
                                            : '—'
                                    }}

                                    –

                                    {{
                                        $endsAt
                                            ? $endsAt->format(
                                                'g:i A'
                                            )
                                            : '—'
                                    }}

                                </span>



                                {{-- LOCATION --}}

                                <span>

                                    {{
                                        $event->location
                                        ?? 'Location unavailable'
                                    }}

                                </span>

                            </div>

                        </div>



                        {{-- ACTION AREA --}}

                        <div
                            class="
                                flex
                                shrink-0
                                flex-wrap
                                items-center
                                gap-4
                                lg:justify-end
                            "
                        >


                            <div
                                class="
                                    hidden
                                    min-w-[90px]
                                    text-right
                                    md:block
                                "
                            >

                                <p
                                    class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                    "
                                >
                                    Attendance
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-2xl
                                        font-bold
                                        text-[#101064]
                                    "
                                >
                                    {{
                                        number_format(
                                            $event->attendance_count
                                            ?? 0
                                        )
                                    }}
                                </p>

                            </div>



                            <a
                                href="{{
                                    route(
                                        'my-events.show',
                                        $event->event_id
                                    )
                                }}"
                                class="
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-xl
                                    bg-[#101064]
                                    px-5
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-white
                                    transition
                                    hover:bg-[#D4A017]
                                    hover:text-[#101064]
                                "
                            >

                                {{
                                    $status === 'Ongoing'
                                        ? 'Open Attendance'
                                        : 'View Event'
                                }}


                                <svg
                                    class="
                                        h-4
                                        w-4
                                    "
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >

                                    <path
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 5l7 7-7 7"
                                    />

                                </svg>

                            </a>

                        </div>

                    </div>

                </div>


            @empty


                <div
                    class="
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
                            rounded-xl
                            bg-[#F1F2FA]
                            text-[#101064]
                        "
                    >

                        <svg
                            class="
                                h-6
                                w-6
                            "
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"
                            />

                        </svg>

                    </div>


                    <p
                        class="
                            mt-4
                            font-semibold
                            text-[#101064]
                        "
                    >
                        No assigned events found
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            text-gray-400
                        "
                    >
                        Events assigned to your personnel account will appear here.
                    </p>

                </div>


            @endforelse



            <div id="ae-no-results" class="ae-no-results" hidden>
                <strong>No events match your filters.</strong>
                Try another search, status, or date range, or choose Reset Filters.
            </div>

            <nav class="ae-pagination" aria-label="Assigned event pagination" id="ae-pagination">
                <span class="ae-pagination-info" id="ae-page-info">Showing assigned events</span>
                <div class="ae-page-controls" id="ae-page-controls"></div>
            </nav>

        </div>
        </div> {{-- /ae-assigned-schedule --}}

    </section>

</div>


<script>
(function () {
    'use strict';
    function initializeAssignedEvents() {
        const section = document.getElementById('ae-assigned-schedule');
        if (!section || section.dataset.initialized === '1') return;
        section.dataset.initialized = '1';
        const list = document.getElementById('ae-event-list');
        const rows = Array.from(section.querySelectorAll('.ae-event-row'));
        const search = document.getElementById('ae-search');
        const status = document.getElementById('ae-status');
        const sort = document.getElementById('ae-sort');
        const size = document.getElementById('ae-size');
        const from = document.getElementById('ae-from');
        const to = document.getElementById('ae-to');
        const count = document.getElementById('ae-results');
        const empty = document.getElementById('ae-no-results');
        const info = document.getElementById('ae-page-info');
        const controls = document.getElementById('ae-page-controls');
        const reset = document.getElementById('ae-reset');
        let page = 1;
        rows.forEach((row, i) => { row.dataset.originalOrder = String(i); });

        function pageButton(text, target, disabled, active, label) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'ae-page-button' + (active ? ' is-active' : '');
            button.textContent = text;
            button.disabled = disabled;
            button.setAttribute('aria-label', label || text);
            if (active) button.setAttribute('aria-current', 'page');
            button.addEventListener('click', () => { page = target; render(); });
            controls.appendChild(button);
        }
        function ellipsis() {
            const span = document.createElement('span');
            span.className = 'ae-page-dots';
            span.textContent = '…';
            controls.appendChild(span);
        }
        function render() {
            const phrase = search.value.trim().toLocaleLowerCase();
            const selected = status.value;
            const start = from.value;
            const end = to.value;
            const perPage = Number(size.value) || 5;
            const matching = rows.filter((row) => {
                const eventDate = row.dataset.date || '';
                return (!phrase || (row.dataset.search || '').toLocaleLowerCase().includes(phrase))
                    && (selected === 'all' || row.dataset.status === selected)
                    && (!start || (eventDate && eventDate >= start))
                    && (!end || (eventDate && eventDate <= end));
            });
            const ordered = matching.sort((a, b) => {
                if (sort.value === 'date-asc') return (a.dataset.datetime || a.dataset.date || '').localeCompare(b.dataset.datetime || b.dataset.date || '') || Number(a.dataset.originalOrder) - Number(b.dataset.originalOrder);
                if (sort.value === 'date-desc') return (b.dataset.datetime || b.dataset.date || '').localeCompare(a.dataset.datetime || a.dataset.date || '') || Number(a.dataset.originalOrder) - Number(b.dataset.originalOrder);
                if (sort.value === 'name-asc') return (a.dataset.name || '').localeCompare(b.dataset.name || '');
                if (sort.value === 'name-desc') return (b.dataset.name || '').localeCompare(a.dataset.name || '');
                return Number(a.dataset.originalOrder) - Number(b.dataset.originalOrder);
            });
            const totalPages = Math.max(1, Math.ceil(ordered.length / perPage));
            page = Math.min(Math.max(1, page), totalPages);
            const begin = (page - 1) * perPage;
            const visible = ordered.slice(begin, begin + perPage);
            const visibleSet = new Set(visible);
            rows.forEach((row) => { row.hidden = !visibleSet.has(row); });
            // Reorder only already-authorized event rows, keeping the pagination footer in place.
            const footer = document.getElementById('ae-no-results');
            ordered.forEach((row) => list.insertBefore(row, footer));
            empty.hidden = ordered.length !== 0 || rows.length === 0;
            count.textContent = `${ordered.length} of ${rows.length} assigned event${rows.length === 1 ? '' : 's'}`;
            info.textContent = ordered.length ? `Showing ${begin + 1}–${Math.min(begin + perPage, ordered.length)} of ${ordered.length} events` : 'Showing 0 events';
            controls.replaceChildren();
            if (ordered.length > perPage) {
                pageButton('Previous', page - 1, page === 1, false, 'Previous page');
                let prev = 0;
                for (let p = 1; p <= totalPages; p++) {
                    if (!(p === 1 || p === totalPages || Math.abs(page - p) <= 1)) continue;
                    if (prev && p - prev > 1) ellipsis();
                    pageButton(String(p), p, false, p === page, `Page ${p}`);
                    prev = p;
                }
                pageButton('Next', page + 1, page === totalPages, false, 'Next page');
            }
        }
        [search, status, sort, size, from, to].forEach((element) => {
            element.addEventListener(element === search ? 'input' : 'change', () => { page = 1; render(); });
        });
        reset.addEventListener('click', () => {
            search.value = ''; status.value = 'all'; sort.value = 'priority'; size.value = '5'; from.value = ''; to.value = '';
            page = 1; render(); search.focus();
        });
        render();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeAssignedEvents);
    else initializeAssignedEvents();
})();
</script>

</x-admin-layout>