<x-admin-layout>

@php

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD DATA
    |--------------------------------------------------------------------------
    */

    $totalUsersValue = (int) ($totalUsers ?? 0);
    $activeUsersValue = (int) ($activeUsers ?? 0);
    $totalPersonnelValue = (int) ($totalPersonnel ?? 0);
    $totalRolesValue = (int) ($totalRoles ?? 0);

    $inactiveUsersValue = max(
        $totalUsersValue - $activeUsersValue,
        0
    );

    $activeRate = $totalUsersValue > 0
        ? round(($activeUsersValue / $totalUsersValue) * 100)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | CALENDAR EVENTS
    |--------------------------------------------------------------------------
    |
    | If your controller already sends $calendarEvents,
    | this will use it.
    |
    | Otherwise, it will automatically get events
    | from App\Models\Event.
    |
    */

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


    /*
    |--------------------------------------------------------------------------
    | FORMAT EVENTS FOR JAVASCRIPT
    |--------------------------------------------------------------------------
    */

    $calendarEventData = collect($calendarEvents)
        ->map(function ($event) {

            return [

                'id' => $event->event_id ?? $event->id ?? null,

                'name' => $event->event_name ?? 'Untitled Event',

                'date' => !empty($event->event_date)
                    ? \Carbon\Carbon::parse(
                        $event->event_date
                    )->format('Y-m-d')
                    : null,

                'start_time' => !empty($event->start_time)
                    ? \Carbon\Carbon::parse(
                        $event->start_time
                    )->format('g:i A')
                    : null,

                'end_time' => !empty($event->end_time)
                    ? \Carbon\Carbon::parse(
                        $event->end_time
                    )->format('g:i A')
                    : null,

                'location' => $event->location
                    ?? 'To be announced',

                'status' => $event->status
                    ?? 'Scheduled',

                'department' =>
                    $event->department->department_name
                    ?? 'University-wide Event',

            ];

        })
        ->filter(function ($event) {

            return !empty($event['date']);

        })
        ->values();

@endphp



<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .admin-hero {

        background:
            linear-gradient(
                100deg,
                rgba(16, 16, 100, .97) 0%,
                rgba(16, 16, 100, .92) 52%,
                rgba(16, 16, 100, .74) 100%
            ),
            url('{{ asset('images/school.jpg') }}');

        background-size: cover;
        background-position: center;

    }


    /*
    |--------------------------------------------------------------------------
    | SECTIONS
    |--------------------------------------------------------------------------
    */

    .admin-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }


    /*
    |--------------------------------------------------------------------------
    | MANAGEMENT LINKS
    |--------------------------------------------------------------------------
    */

    .admin-management-item {

        transition:
            background-color .2s ease,
            border-color .2s ease;

    }

    .admin-management-item:hover {

        background: #fafafa;

    }


    /*
    |--------------------------------------------------------------------------
    | CALENDAR
    |--------------------------------------------------------------------------
    */

    .admin-calendar-cell {

        min-height: 118px;

        transition:
            background-color .15s ease,
            box-shadow .15s ease;

    }

    .admin-calendar-cell:hover {

        background: #fafafd;

        box-shadow:
            inset 0 0 0 1px
            rgba(16, 16, 100, .08);

    }


    /*
    |--------------------------------------------------------------------------
    | SCROLLBAR
    |--------------------------------------------------------------------------
    */

    .admin-scrollbar::-webkit-scrollbar {

        width: 6px;
        height: 6px;

    }

    .admin-scrollbar::-webkit-scrollbar-track {

        background: #f3f4f6;

    }

    .admin-scrollbar::-webkit-scrollbar-thumb {

        background: #d1d5db;
        border-radius: 9999px;

    }

    .admin-scrollbar::-webkit-scrollbar-thumb:hover {

        background: #9ca3af;

    }

</style>



<div
    class="
        min-w-0
        space-y-8
    "
>



    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            admin-hero
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

        {{-- GOLD ACCENT --}}

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
                min-w-0
                flex-col
                gap-8
                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >


            {{-- HERO CONTENT --}}

            <div
                class="
                    min-w-0
                    max-w-3xl
                "
            >

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.35em]
                        text-[#E7C75B]
                    "
                >
                    System Administration
                </p>


                <h1
                    class="
                        mt-3
                        break-words
                        text-3xl
                        font-bold
                        tracking-tight
                        md:text-4xl
                    "
                >
                    Good day,
                    {{ auth()->user()?->name ?? 'Administrator' }}
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
                    Oversee DySign system operations, manage institutional
                    records, administer user access, and monitor scheduled
                    university events from one central workspace.
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


                    {{-- DATE --}}

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
                                shrink-0
                                text-[#E7C75B]
                            "
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0
                                012 2v12a2 2 0 01-2 2H5a2 2 0
                                01-2-2V7a2 2 0 012-2z"
                            />
                        </svg>

                        <span>
                            {{ now()->format('l, F d, Y') }}
                        </span>

                    </div>



                    {{-- STATUS --}}

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

                        System Administration Portal

                    </div>

                </div>

            </div>



            {{-- HERO BUTTON --}}

            <div class="shrink-0">

                <a
                    href="{{ route('events.index') }}"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        gap-3
                        rounded-xl
                        bg-[#D4A017]
                        px-6
                        py-3
                        text-sm
                        font-bold
                        text-[#101064]
                        transition
                        hover:bg-white
                    "
                >

                    Event Management

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

    </section>



    {{-- ====================================================== --}}
    {{-- SYSTEM OVERVIEW --}}
    {{-- ====================================================== --}}

    <section
        class="
            min-w-0
        "
    >

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
                Overview
            </p>


            <div
                class="
                    mt-2
                    flex
                    min-w-0
                    flex-col
                    gap-2
                    sm:flex-row
                    sm:items-end
                    sm:justify-between
                "
            >

                <h2
                    class="
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Institutional System Summary
                </h2>


                <p
                    class="
                        text-sm
                        text-gray-400
                    "
                >
                    Current DySign administrative records
                </p>

            </div>

        </div>



        <div
            class="
                grid
                min-w-0
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                sm:grid-cols-2
                xl:grid-cols-4
            "
        >


            {{-- SYSTEM USERS --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    sm:border-r
                    xl:border-b-0
                "
            >

                <div
                    class="
                        flex
                        min-w-0
                        items-start
                        justify-between
                        gap-4
                    "
                >

                    <div class="min-w-0">

                        <p
                            class="
                                truncate
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            System Users
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-[#101064]
                            "
                        >
                            {{ $totalUsersValue }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Registered accounts
                        </p>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-[#F0F1F8]
                            text-[#101064]
                        "
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 00-4-4H6a4
                                4 0 00-4 4v2m7-10a4 4 0 100-8
                                4 4 0 000 8zm8 10v-2a4 4 0
                                00-3-3.87"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- ACTIVE ACCOUNTS --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    xl:border-b-0
                    xl:border-r
                "
            >

                <div
                    class="
                        flex
                        min-w-0
                        items-start
                        justify-between
                        gap-4
                    "
                >

                    <div class="min-w-0">

                        <p
                            class="
                                truncate
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Active Accounts
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-green-600
                            "
                        >
                            {{ $activeUsersValue }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            {{ $activeRate }}% currently active
                        </p>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-green-50
                            text-green-600
                        "
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- PERSONNEL --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    sm:border-b-0
                    sm:border-r
                "
            >

                <div
                    class="
                        flex
                        min-w-0
                        items-start
                        justify-between
                        gap-4
                    "
                >

                    <div class="min-w-0">

                        <p
                            class="
                                truncate
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Personnel
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-[#D4A017]
                            "
                        >
                            {{ $totalPersonnelValue }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Authorized personnel
                        </p>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-[#FFF9E7]
                            text-[#B68A0D]
                        "
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 14c3.866 0 7 1.79 7 4v2H5v-2c0-2.21
                                3.134-4 7-4zm0-2a4 4 0 100-8 4
                                4 0 000 8z"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- ROLES --}}

            <div
                class="
                    min-w-0
                    px-6
                    py-6
                "
            >

                <div
                    class="
                        flex
                        min-w-0
                        items-start
                        justify-between
                        gap-4
                    "
                >

                    <div class="min-w-0">

                        <p
                            class="
                                truncate
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            System Roles
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-[#101064]
                            "
                        >
                            {{ $totalRolesValue }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Access classifications
                        </p>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-[#F0F1F8]
                            text-[#101064]
                        "
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 15l3.5-2V8L12 6 8.5
                                8v5L12 15zm0 0v6M5 7l7-4 7 4"
                            />
                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- EVENTS CALENDAR --}}
    {{-- ====================================================== --}}

    <section
        class="
            min-w-0
        "
    >

        {{-- TITLE + CONTROLS --}}

        <div
            class="
                mb-4
                flex
                min-w-0
                flex-col
                gap-4
                md:flex-row
                md:items-end
                md:justify-between
            "
        >

            <div class="min-w-0">

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.28em]
                        text-[#D4A017]
                    "
                >
                    DYCI Schedule
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Events Calendar
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Select a date to view scheduled institutional events.
                </p>

            </div>



            {{-- CONTROLS --}}

            <div
                class="
                    flex
                    shrink-0
                    items-center
                    gap-2
                "
            >

                <button
                    type="button"
                    onclick="goToToday()"
                    class="
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        px-4
                        py-2.5
                        text-xs
                        font-semibold
                        text-[#101064]
                        transition
                        hover:border-[#101064]
                        hover:bg-gray-50
                    "
                >
                    Today
                </button>



                <button
                    type="button"
                    onclick="changeCalendarMonth(-1)"
                    class="
                        flex
                        h-10
                        w-10
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        text-[#101064]
                        transition
                        hover:border-[#101064]
                        hover:bg-gray-50
                    "
                    aria-label="Previous month"
                >

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
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                </button>



                <button
                    type="button"
                    onclick="changeCalendarMonth(1)"
                    class="
                        flex
                        h-10
                        w-10
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        text-[#101064]
                        transition
                        hover:border-[#101064]
                        hover:bg-gray-50
                    "
                    aria-label="Next month"
                >

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

                </button>

            </div>

        </div>



        {{-- CALENDAR CONTAINER --}}

        <div
            class="
                admin-section
                min-w-0
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >


            {{-- MONTH HEADER --}}

            <div
                class="
                    flex
                    min-w-0
                    flex-col
                    gap-4
                    border-b
                    border-gray-100
                    px-6
                    py-5
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                "
            >

                <div class="min-w-0">

                    <p
                        class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.22em]
                            text-gray-400
                        "
                    >
                        Academic Events
                    </p>


                    <h3
                        id="calendarMonthTitle"
                        class="
                            mt-1
                            truncate
                            text-lg
                            font-bold
                            text-[#101064]
                        "
                    >
                    </h3>

                </div>



                <div
                    class="
                        flex
                        flex-wrap
                        items-center
                        gap-x-5
                        gap-y-2
                        text-xs
                        text-gray-400
                    "
                >

                    <span
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
                                bg-[#101064]
                            "
                        ></span>

                        Today

                    </span>


                    <span
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
                                bg-[#D4A017]
                            "
                        ></span>

                        Has Event

                    </span>

                </div>

            </div>



            {{-- RESPONSIVE CALENDAR SCROLLER --}}

            <div
                class="
                    admin-scrollbar
                    w-full
                    max-w-full
                    overflow-x-auto
                "
            >

                <div
                    class="
                        min-w-[760px]
                    "
                >


                    {{-- WEEKDAY HEADERS --}}

                    <div
                        class="
                            grid
                            grid-cols-7
                            border-b
                            border-gray-200
                            bg-gray-50
                        "
                    >

                        <div
                            class="
                                border-r
                                border-gray-100
                                px-3
                                py-3
                                text-center
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Sunday
                        </div>


                        <div
                            class="
                                border-r
                                border-gray-100
                                px-3
                                py-3
                                text-center
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Monday
                        </div>


                        <div
                            class="
                                border-r
                                border-gray-100
                                px-3
                                py-3
                                text-center
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Tuesday
                        </div>


                        <div
                            class="
                                border-r
                                border-gray-100
                                px-3
                                py-3
                                text-center
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Wednesday
                        </div>


                        <div
                            class="
                                border-r
                                border-gray-100
                                px-3
                                py-3
                                text-center
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Thursday
                        </div>


                        <div
                            class="
                                border-r
                                border-gray-100
                                px-3
                                py-3
                                text-center
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Friday
                        </div>


                        <div
                            class="
                                px-3
                                py-3
                                text-center
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Saturday
                        </div>

                    </div>



                    {{-- CALENDAR DAYS --}}

                    <div
                        id="adminCalendarGrid"
                        class="
                            grid
                            grid-cols-7
                        "
                    ></div>

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ADMINISTRATIVE MANAGEMENT --}}
    {{-- ====================================================== --}}

    <section
        class="
            min-w-0
        "
    >

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
                Administration
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Administrative Management
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Access the primary administrative functions of DySign.
            </p>

        </div>



        <div
            class="
                admin-section
                min-w-0
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >

            <div
                class="
                    grid
                    min-w-0
                    grid-cols-1
                    md:grid-cols-2
                    xl:grid-cols-3
                "
            >


                {{-- EVENT MANAGEMENT --}}

                <a
                    href="{{ route('events.index') }}"
                    class="
                        admin-management-item
                        group
                        min-w-0
                        overflow-hidden
                        border-b
                        border-gray-100
                        p-6
                        md:border-r
                    "
                >

                    <div
                        class="
                            flex
                            min-w-0
                            items-start
                            gap-4
                        "
                    >

                        <div
                            class="
                                flex
                                h-12
                                w-12
                                shrink-0
                                items-center
                                justify-center
                                border
                                border-gray-200
                                bg-gray-50
                                text-[#101064]
                                transition
                                group-hover:border-[#D4A017]
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
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 7V3m8 4V3M5 11h14M5 5h14a2
                                    2 0 012 2v12a2 2 0 01-2 2H5a2
                                    2 0 01-2-2V7a2 2 0 012-2z"
                                />
                            </svg>

                        </div>


                        <div class="min-w-0">

                            <div
                                class="
                                    flex
                                    min-w-0
                                    items-center
                                    gap-2
                                "
                            >

                                <h3
                                    class="
                                        min-w-0
                                        truncate
                                        font-bold
                                        text-[#101064]
                                    "
                                >
                                    Event Management
                                </h3>


                                <span
                                    class="
                                        shrink-0
                                        text-gray-300
                                        transition
                                        group-hover:translate-x-1
                                        group-hover:text-[#D4A017]
                                    "
                                >
                                    →
                                </span>

                            </div>


                            <p
                                class="
                                    mt-2
                                    break-words
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                Create, schedule, review, and administer
                                institutional events.
                            </p>

                        </div>

                    </div>

                </a>



                {{-- STUDENTS --}}

                <a
                    href="{{ route('students.index') }}"
                    class="
                        admin-management-item
                        group
                        min-w-0
                        overflow-hidden
                        border-b
                        border-gray-100
                        p-6
                        xl:border-r
                    "
                >

                    <div
                        class="
                            flex
                            min-w-0
                            items-start
                            gap-4
                        "
                    >

                        <div
                            class="
                                flex
                                h-12
                                w-12
                                shrink-0
                                items-center
                                justify-center
                                border
                                border-gray-200
                                bg-gray-50
                                text-[#101064]
                                transition
                                group-hover:border-[#D4A017]
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
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 14L3 9l9-5 9 5-9
                                    5zm-6-2v5c3 2 9 2 12 0v-5"
                                />
                            </svg>

                        </div>


                        <div class="min-w-0">

                            <div
                                class="
                                    flex
                                    min-w-0
                                    items-center
                                    gap-2
                                "
                            >

                                <h3
                                    class="
                                        truncate
                                        font-bold
                                        text-[#101064]
                                    "
                                >
                                    Student Records
                                </h3>


                                <span
                                    class="
                                        shrink-0
                                        text-gray-300
                                        transition
                                        group-hover:translate-x-1
                                        group-hover:text-[#D4A017]
                                    "
                                >
                                    →
                                </span>

                            </div>


                            <p
                                class="
                                    mt-2
                                    break-words
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                Maintain student identity and participation
                                records.
                            </p>

                        </div>

                    </div>

                </a>



                {{-- PERSONNEL --}}

                <a
                    href="{{ route('personnel.index') }}"
                    class="
                        admin-management-item
                        group
                        min-w-0
                        overflow-hidden
                        border-b
                        border-gray-100
                        p-6
                        md:border-r
                        xl:border-r-0
                    "
                >

                    <div
                        class="
                            flex
                            min-w-0
                            items-start
                            gap-4
                        "
                    >

                        <div
                            class="
                                flex
                                h-12
                                w-12
                                shrink-0
                                items-center
                                justify-center
                                border
                                border-gray-200
                                bg-gray-50
                                text-[#101064]
                                transition
                                group-hover:border-[#D4A017]
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
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 12a4 4 0 100-8 4 4 0
                                    000 8zm-7 9a7 7 0 0114 0"
                                />
                            </svg>

                        </div>


                        <div class="min-w-0">

                            <div
                                class="
                                    flex
                                    min-w-0
                                    items-center
                                    gap-2
                                "
                            >

                                <h3
                                    class="
                                        truncate
                                        font-bold
                                        text-[#101064]
                                    "
                                >
                                    Personnel Management
                                </h3>


                                <span
                                    class="
                                        shrink-0
                                        text-gray-300
                                        transition
                                        group-hover:translate-x-1
                                        group-hover:text-[#D4A017]
                                    "
                                >
                                    →
                                </span>

                            </div>


                            <p
                                class="
                                    mt-2
                                    break-words
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                Manage authorized personnel accounts,
                                departments, and access.
                            </p>

                        </div>

                    </div>

                </a>



                {{-- REPORTS --}}

                <a
                    href="{{ route('reports.attendance') }}"
                    class="
                        admin-management-item
                        group
                        min-w-0
                        overflow-hidden
                        border-b
                        border-gray-100
                        p-6
                        xl:border-r
                        xl:border-b-0
                    "
                >

                    <div
                        class="
                            flex
                            min-w-0
                            items-start
                            gap-4
                        "
                    >

                        <div
                            class="
                                flex
                                h-12
                                w-12
                                shrink-0
                                items-center
                                justify-center
                                border
                                border-gray-200
                                bg-gray-50
                                text-[#101064]
                                transition
                                group-hover:border-[#D4A017]
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
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 19V9m6 10V5m6
                                    14v-7m4 7H2"
                                />
                            </svg>

                        </div>


                        <div class="min-w-0">

                            <div
                                class="
                                    flex
                                    min-w-0
                                    items-center
                                    gap-2
                                "
                            >

                                <h3
                                    class="
                                        truncate
                                        font-bold
                                        text-[#101064]
                                    "
                                >
                                    Reports & Analytics
                                </h3>


                                <span
                                    class="
                                        shrink-0
                                        text-gray-300
                                        transition
                                        group-hover:translate-x-1
                                        group-hover:text-[#D4A017]
                                    "
                                >
                                    →
                                </span>

                            </div>


                            <p
                                class="
                                    mt-2
                                    break-words
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                Review attendance information and generate
                                administrative reports.
                            </p>

                        </div>

                    </div>

                </a>



                {{-- AUDIT LOGS --}}

                <a
                    href="{{ route('logs.index') }}"
                    class="
                        admin-management-item
                        group
                        min-w-0
                        overflow-hidden
                        border-b
                        border-gray-100
                        p-6
                        md:border-b-0
                        md:border-r
                    "
                >

                    <div
                        class="
                            flex
                            min-w-0
                            items-start
                            gap-4
                        "
                    >

                        <div
                            class="
                                flex
                                h-12
                                w-12
                                shrink-0
                                items-center
                                justify-center
                                border
                                border-gray-200
                                bg-gray-50
                                text-[#101064]
                                transition
                                group-hover:border-[#D4A017]
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
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6M9
                                    8h6M5 3h14v18H5z"
                                />
                            </svg>

                        </div>


                        <div class="min-w-0">

                            <div
                                class="
                                    flex
                                    min-w-0
                                    items-center
                                    gap-2
                                "
                            >

                                <h3
                                    class="
                                        truncate
                                        font-bold
                                        text-[#101064]
                                    "
                                >
                                    Audit Trail
                                </h3>


                                <span
                                    class="
                                        shrink-0
                                        text-gray-300
                                        transition
                                        group-hover:translate-x-1
                                        group-hover:text-[#D4A017]
                                    "
                                >
                                    →
                                </span>

                            </div>


                            <p
                                class="
                                    mt-2
                                    break-words
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                Review administrative actions and recorded
                                system activities.
                            </p>

                        </div>

                    </div>

                </a>



                {{-- BACKUP --}}

                <a
                    href="{{ route('backup.index') }}"
                    class="
                        admin-management-item
                        group
                        min-w-0
                        overflow-hidden
                        p-6
                    "
                >

                    <div
                        class="
                            flex
                            min-w-0
                            items-start
                            gap-4
                        "
                    >

                        <div
                            class="
                                flex
                                h-12
                                w-12
                                shrink-0
                                items-center
                                justify-center
                                border
                                border-gray-200
                                bg-gray-50
                                text-[#101064]
                                transition
                                group-hover:border-[#D4A017]
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
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 7h16v13H4V7zm3-4h10v4H7V3zm5
                                    8v6m-3-3h6"
                                />
                            </svg>

                        </div>


                        <div class="min-w-0">

                            <div
                                class="
                                    flex
                                    min-w-0
                                    items-center
                                    gap-2
                                "
                            >

                                <h3
                                    class="
                                        truncate
                                        font-bold
                                        text-[#101064]
                                    "
                                >
                                    System Backup
                                </h3>


                                <span
                                    class="
                                        shrink-0
                                        text-gray-300
                                        transition
                                        group-hover:translate-x-1
                                        group-hover:text-[#D4A017]
                                    "
                                >
                                    →
                                </span>

                            </div>


                            <p
                                class="
                                    mt-2
                                    break-words
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                Maintain backup copies of essential DySign
                                institutional records.
                            </p>

                        </div>

                    </div>

                </a>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- RECENT PERSONNEL + ACCOUNT STATUS --}}
    {{-- ====================================================== --}}

    <section
        class="
            grid
            min-w-0
            grid-cols-1
            gap-8
            xl:grid-cols-3
        "
    >


        {{-- ================================================== --}}
        {{-- RECENT PERSONNEL --}}
        {{-- ================================================== --}}

        <div
            class="
                admin-section
                min-w-0
                overflow-hidden
                border
                border-gray-200
                bg-white
                xl:col-span-2
            "
        >

            <div
                class="
                    flex
                    min-w-0
                    flex-col
                    gap-3
                    border-b
                    border-gray-100
                    px-7
                    py-6
                    sm:flex-row
                    sm:items-end
                    sm:justify-between
                "
            >

                <div class="min-w-0">

                    <p
                        class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-[0.25em]
                            text-[#D4A017]
                        "
                    >
                        Personnel Registry
                    </p>


                    <h2
                        class="
                            mt-2
                            truncate
                            text-xl
                            font-bold
                            text-[#101064]
                        "
                    >
                        Recent Personnel Accounts
                    </h2>

                </div>


                <a
                    href="{{ route('personnel.index') }}"
                    class="
                        shrink-0
                        text-xs
                        font-semibold
                        text-[#101064]
                        transition
                        hover:text-[#D4A017]
                    "
                >
                    View All
                </a>

            </div>



            @if(
                isset($recentPersonnel)
                &&
                $recentPersonnel->count()
            )

                <div
                    class="
                        divide-y
                        divide-gray-100
                    "
                >

                    @foreach($recentPersonnel as $person)

                        <div
                            class="
                                flex
                                min-w-0
                                flex-col
                                gap-4
                                px-7
                                py-5
                                transition
                                hover:bg-gray-50
                                md:flex-row
                                md:items-center
                                md:justify-between
                            "
                        >


                            {{-- PERSON DETAILS --}}

                            <div
                                class="
                                    flex
                                    min-w-0
                                    items-center
                                    gap-4
                                "
                            >

                                <div
                                    class="
                                        flex
                                        h-11
                                        w-11
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-full
                                        bg-[#F0F1F8]
                                        text-sm
                                        font-bold
                                        text-[#101064]
                                    "
                                >

                                    {{ strtoupper(
                                        substr(
                                            $person->first_name ?? 'P',
                                            0,
                                            1
                                        )
                                    ) }}

                                    {{ strtoupper(
                                        substr(
                                            $person->last_name ?? '',
                                            0,
                                            1
                                        )
                                    ) }}

                                </div>


                                <div class="min-w-0">

                                    <p
                                        class="
                                            truncate
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        {{ $person->first_name ?? '' }}
                                        {{ $person->last_name ?? '' }}
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            truncate
                                            text-sm
                                            text-gray-500
                                        "
                                    >
                                        {{ $person->department ?? 'No Department' }}
                                    </p>

                                </div>

                            </div>



                            {{-- PERSON META --}}

                            <div
                                class="
                                    grid
                                    shrink-0
                                    grid-cols-2
                                    gap-x-8
                                    gap-y-3
                                    md:text-right
                                "
                            >

                                <div class="min-w-0">

                                    <p
                                        class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-gray-400
                                        "
                                    >
                                        Position
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            max-w-[180px]
                                            truncate
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        {{ $person->position ?? 'Personnel' }}
                                    </p>

                                </div>



                                <div class="min-w-0">

                                    <p
                                        class="
                                            text-[10px]
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-gray-400
                                        "
                                    >
                                        Added
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            whitespace-nowrap
                                            text-sm
                                            text-gray-500
                                        "
                                    >
                                        {{ $person->created_at
                                            ? $person->created_at->format('M d, Y')
                                            : '—'
                                        }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div
                    class="
                        px-7
                        py-14
                        text-center
                    "
                >

                    <div
                        class="
                            mx-auto
                            h-1
                            w-12
                            bg-[#D4A017]
                        "
                    ></div>


                    <h3
                        class="
                            mt-5
                            font-semibold
                            text-[#101064]
                        "
                    >
                        No personnel accounts available
                    </h3>


                    <p
                        class="
                            mt-2
                            text-sm
                            text-gray-400
                        "
                    >
                        Newly registered personnel will appear here.
                    </p>

                </div>

            @endif

        </div>



        {{-- ================================================== --}}
        {{-- ACCOUNT STATUS --}}
        {{-- ================================================== --}}

        <div
            class="
                admin-section
                min-w-0
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-6
                "
            >

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.25em]
                        text-[#D4A017]
                    "
                >
                    Access Status
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Account Availability
                </h2>

            </div>



            <div class="p-6">

                <div
                    class="
                        flex
                        min-w-0
                        items-end
                        justify-between
                        gap-4
                    "
                >

                    <div class="min-w-0">

                        <p
                            class="
                                text-5xl
                                font-bold
                                tracking-tight
                                text-[#101064]
                            "
                        >
                            {{ $activeRate }}%
                        </p>


                        <p
                            class="
                                mt-2
                                text-sm
                                text-gray-500
                            "
                        >
                            Active system accounts
                        </p>

                    </div>


                    <span
                        class="
                            mb-2
                            flex
                            shrink-0
                            items-center
                            gap-2
                            text-xs
                            font-semibold
                            text-green-600
                        "
                    >

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-green-500
                            "
                        ></span>

                        Operational

                    </span>

                </div>



                {{-- PROGRESS --}}

                <div
                    class="
                        mt-6
                        h-2
                        w-full
                        overflow-hidden
                        bg-gray-100
                    "
                >

                    <div
                        class="
                            h-full
                            bg-[#101064]
                        "
                        style="width: {{ min($activeRate, 100) }}%"
                    ></div>

                </div>



                {{-- STATUS DETAILS --}}

                <div
                    class="
                        mt-7
                        divide-y
                        divide-gray-100
                        border-t
                        border-gray-100
                    "
                >

                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-4
                            py-4
                        "
                    >

                        <span
                            class="
                                text-sm
                                text-gray-500
                            "
                        >
                            Total Accounts
                        </span>

                        <span
                            class="
                                shrink-0
                                font-bold
                                text-[#101064]
                            "
                        >
                            {{ $totalUsersValue }}
                        </span>

                    </div>



                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-4
                            py-4
                        "
                    >

                        <span
                            class="
                                text-sm
                                text-gray-500
                            "
                        >
                            Active
                        </span>

                        <span
                            class="
                                shrink-0
                                font-bold
                                text-green-600
                            "
                        >
                            {{ $activeUsersValue }}
                        </span>

                    </div>



                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-4
                            py-4
                        "
                    >

                        <span
                            class="
                                text-sm
                                text-gray-500
                            "
                        >
                            Inactive
                        </span>

                        <span
                            class="
                                shrink-0
                                font-bold
                                text-gray-500
                            "
                        >
                            {{ $inactiveUsersValue }}
                        </span>

                    </div>



                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-4
                            py-4
                        "
                    >

                        <span
                            class="
                                text-sm
                                text-gray-500
                            "
                        >
                            Access Roles
                        </span>

                        <span
                            class="
                                shrink-0
                                font-bold
                                text-[#D4A017]
                            "
                        >
                            {{ $totalRolesValue }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>



{{-- ====================================================== --}}
{{-- EVENT DATE MODAL --}}
{{-- ====================================================== --}}

<div
    id="eventDayModal"
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
                        calendarEvent.status
                        === 'Cancelled'
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
                        calendarEvent.status
                        === 'Cancelled'
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