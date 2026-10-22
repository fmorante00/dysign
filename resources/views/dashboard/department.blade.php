<x-admin-layout>

@php

    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT DASHBOARD DATA
    |--------------------------------------------------------------------------
    |
    | The controller may pass these values directly. If it does not yet,
    | this view safely loads the authenticated department's event data.
    |
    */

    $dashboardUser = auth()->user();

    $departmentId =
        $dashboardUser?->department_id;


    $departmentName =
        $departmentName
        ?? null;


    $totalEvents =
        $totalEvents
        ?? null;


    $upcomingEventsCount =
        $upcomingEventsCount
        ?? null;


    $ongoingEventsCount =
        $ongoingEventsCount
        ?? null;


    $completedEventsCount =
        $completedEventsCount
        ?? null;


    $upcomingEvents =
        $upcomingEvents
        ?? null;


    $recentEvents =
        $recentEvents
        ?? null;


    /*
    |--------------------------------------------------------------------------
    | Resolve Department
    |--------------------------------------------------------------------------
    */

    if (
        !$departmentName
        && $departmentId
    ) {

        try {

            $departmentName =
                \App\Models\Department::where(
                    'department_id',
                    $departmentId
                )
                ->value(
                    'department_name'
                );

        } catch (\Throwable $e) {

            $departmentName = null;

        }

    }


    $departmentName =
        $departmentName
        ?: 'Unassigned Department';


    /*
    |--------------------------------------------------------------------------
    | Load Department Events
    |--------------------------------------------------------------------------
    */

    if (
        $totalEvents === null
        || $upcomingEventsCount === null
        || $ongoingEventsCount === null
        || $completedEventsCount === null
        || $upcomingEvents === null
        || $recentEvents === null
    ) {

        try {

            $departmentEventQuery =
                \App\Models\Event::query();


            if ($departmentId) {

                $departmentEventQuery->where(
                    'department_id',
                    $departmentId
                );

            } else {

                /*
                |--------------------------------------------------------------------------
                | No Department Assigned
                |--------------------------------------------------------------------------
                |
                | Department Staff without a department should not see events
                | belonging to another department.
                |
                */

                $departmentEventQuery->whereRaw(
                    '1 = 0'
                );

            }


            $totalEvents =
                $totalEvents
                ?? (clone $departmentEventQuery)
                    ->count();


            $upcomingEventsCount =
                $upcomingEventsCount
                ?? (clone $departmentEventQuery)
                    ->where(
                        'status',
                        'Upcoming'
                    )
                    ->count();


            $ongoingEventsCount =
                $ongoingEventsCount
                ?? (clone $departmentEventQuery)
                    ->where(
                        'status',
                        'Ongoing'
                    )
                    ->count();


            $completedEventsCount =
                $completedEventsCount
                ?? (clone $departmentEventQuery)
                    ->where(
                        'status',
                        'Completed'
                    )
                    ->count();


            $upcomingEvents =
                $upcomingEvents
                ?? (clone $departmentEventQuery)
                    ->where(
                        'status',
                        'Upcoming'
                    )
                    ->orderBy(
                        'event_date'
                    )
                    ->orderBy(
                        'start_time'
                    )
                    ->take(5)
                    ->get();


            $recentEvents =
                $recentEvents
                ?? (clone $departmentEventQuery)
                    ->orderByDesc(
                        'updated_at'
                    )
                    ->orderByDesc(
                        'event_date'
                    )
                    ->take(6)
                    ->get();

        } catch (\Throwable $e) {

            $totalEvents =
                $totalEvents
                ?? 0;

            $upcomingEventsCount =
                $upcomingEventsCount
                ?? 0;

            $ongoingEventsCount =
                $ongoingEventsCount
                ?? 0;

            $completedEventsCount =
                $completedEventsCount
                ?? 0;

            $upcomingEvents =
                $upcomingEvents
                ?? collect();

            $recentEvents =
                $recentEvents
                ?? collect();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Safe Values
    |--------------------------------------------------------------------------
    */

    $totalEvents =
        (int) ($totalEvents ?? 0);

    $upcomingEventsCount =
        (int) ($upcomingEventsCount ?? 0);

    $ongoingEventsCount =
        (int) ($ongoingEventsCount ?? 0);

    $completedEventsCount =
        (int) ($completedEventsCount ?? 0);

    $upcomingEvents =
        collect(
            $upcomingEvents ?? []
        );

    $recentEvents =
        collect(
            $recentEvents ?? []
        );

@endphp



<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .department-hero {

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

    .department-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }


    /*
    |--------------------------------------------------------------------------
    | QUICK ACTIONS
    |--------------------------------------------------------------------------
    */

    .department-action {

        transition:
            background-color .2s ease,
            border-color .2s ease,
            transform .2s ease;

    }


    .department-action:hover {

        transform:
            translateY(-1px);

    }

</style>



<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            department-hero
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
                    Department Workspace
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
                    {{ $dashboardUser?->name ?? 'Department Staff' }}
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
                    Manage department events, review event activity,
                    and monitor schedules from one central workspace.
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
                            class="h-4 w-4 text-[#E7C75B]"
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

                        {{ now()->format('F d, Y') }}
                    </div>


                    <div
                        class="
                            flex
                            min-w-0
                            items-center
                            gap-2
                        "
                    >
                        <span
                            class="
                                h-2
                                w-2
                                shrink-0
                                rounded-full
                                bg-green-400
                            "
                        ></span>

                        <span class="truncate">
                            {{ $departmentName }}
                        </span>
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
                        class="h-4 w-4"
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
    {{-- DEPARTMENT OVERVIEW --}}
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
                Department Overview
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Event Summary
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Live event totals for {{ $departmentName }}.
            </p>

        </div>


        <div
            class="
                department-section
                grid
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                sm:grid-cols-2
                xl:grid-cols-4
            "
        >


            {{-- TOTAL EVENTS --}}

            <div
                class="
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
                        items-start
                        justify-between
                        gap-4
                    "
                >

                    <div>

                        <p
                            class="
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-[0.16em]
                                text-gray-400
                            "
                        >
                            Total Events
                        </p>


                        <p
                            class="
                                mt-2
                                text-3xl
                                font-bold
                                tracking-tight
                                text-[#101064]
                            "
                        >
                            {{ number_format($totalEvents) }}
                        </p>


                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-400
                            "
                        >
                            Department event records
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
                            rounded-xl
                            bg-[#F1F2FA]
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
                                d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"
                            />
                        </svg>
                    </div>

                </div>

            </div>



            {{-- UPCOMING --}}

            <div
                class="
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
                        items-start
                        justify-between
                        gap-4
                    "
                >

                    <div>

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
                                tracking-tight
                                text-[#D4A017]
                            "
                        >
                            {{ number_format($upcomingEventsCount) }}
                        </p>


                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-400
                            "
                        >
                            Scheduled activities
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
                            rounded-xl
                            bg-[#FFF8E1]
                            text-[#A87900]
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
                                d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>

                </div>

            </div>



            {{-- ONGOING --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    sm:border-r
                    sm:border-b-0
                "
            >

                <div
                    class="
                        flex
                        items-start
                        justify-between
                        gap-4
                    "
                >

                    <div>

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
                                tracking-tight
                                text-green-600
                            "
                        >
                            {{ number_format($ongoingEventsCount) }}
                        </p>


                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-400
                            "
                        >
                            Currently active
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
                            rounded-xl
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
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            />
                        </svg>
                    </div>

                </div>

            </div>



            {{-- COMPLETED --}}

            <div class="px-6 py-6">

                <div
                    class="
                        flex
                        items-start
                        justify-between
                        gap-4
                    "
                >

                    <div>

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
                                tracking-tight
                                text-gray-600
                            "
                        >
                            {{ number_format($completedEventsCount) }}
                        </p>


                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-400
                            "
                        >
                            Finished activities
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
                            rounded-xl
                            bg-gray-100
                            text-gray-600
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

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- UPCOMING EVENTS + QUICK ACTIONS --}}
    {{-- ====================================================== --}}

    <section
        class="
            grid
            grid-cols-1
            gap-6
            xl:grid-cols-[minmax(0,2fr)_minmax(300px,1fr)]
        "
    >


        {{-- UPCOMING EVENTS --}}

        <div class="min-w-0">

            <div
                class="
                    mb-4
                    flex
                    items-end
                    justify-between
                    gap-4
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
                        Event Schedule
                    </p>


                    <h2
                        class="
                            mt-2
                            text-xl
                            font-bold
                            text-[#101064]
                        "
                    >
                        Upcoming Events
                    </h2>


                    <p
                        class="
                            mt-1
                            text-sm
                            text-gray-500
                        "
                    >
                        Next scheduled activities for your department.
                    </p>

                </div>


                <a
                    href="{{ route('events.index') }}"
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


            <div
                class="
                    department-section
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                "
            >

                @forelse($upcomingEvents as $event)

                    <a
                        href="{{ route('events.show', $event->event_id) }}"
                        class="
                            block
                            border-b
                            border-gray-100
                            px-6
                            py-5
                            transition
                            last:border-b-0
                            hover:bg-gray-50/70
                        "
                    >

                        <div
                            class="
                                flex
                                min-w-0
                                flex-col
                                gap-4
                                md:flex-row
                                md:items-center
                                md:justify-between
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
                                        h-11
                                        w-11
                                        shrink-0
                                        flex-col
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-[#F1F2FA]
                                        text-[#101064]
                                    "
                                >
                                    <span
                                        class="
                                            text-[9px]
                                            font-bold
                                            uppercase
                                            tracking-wider
                                            text-gray-400
                                        "
                                    >
                                        {{
                                            \Carbon\Carbon::parse(
                                                $event->event_date
                                            )->format('M')
                                        }}
                                    </span>

                                    <span
                                        class="
                                            text-base
                                            font-bold
                                            leading-none
                                        "
                                    >
                                        {{
                                            \Carbon\Carbon::parse(
                                                $event->event_date
                                            )->format('d')
                                        }}
                                    </span>
                                </div>


                                <div class="min-w-0">

                                    <h3
                                        class="
                                            truncate
                                            text-sm
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        {{ $event->event_name }}
                                    </h3>


                                    <div
                                        class="
                                            mt-2
                                            flex
                                            flex-wrap
                                            gap-x-4
                                            gap-y-1
                                            text-xs
                                            text-gray-400
                                        "
                                    >

                                        <span>
                                            {{
                                                \Carbon\Carbon::parse(
                                                    $event->start_time
                                                )->format('g:i A')
                                            }}
                                            –
                                            {{
                                                \Carbon\Carbon::parse(
                                                    $event->end_time
                                                )->format('g:i A')
                                            }}
                                        </span>


                                        <span>
                                            {{ $event->location }}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <span
                                class="
                                    inline-flex
                                    w-fit
                                    shrink-0
                                    items-center
                                    gap-2
                                    rounded-full
                                    bg-[#FFF8E1]
                                    px-3
                                    py-1
                                    text-xs
                                    font-semibold
                                    text-[#A87900]
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

                                Upcoming
                            </span>

                        </div>

                    </a>


                @empty

                    <div
                        class="
                            px-6
                            py-14
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
                                class="h-6 w-6"
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
                            No upcoming events
                        </p>


                        <p
                            class="
                                mx-auto
                                mt-1
                                max-w-md
                                text-sm
                                leading-6
                                text-gray-400
                            "
                        >
                            New scheduled department events will appear here.
                        </p>


                        @if($departmentId)

                            <a
                                href="{{ route('events.create') }}"
                                class="
                                    mt-5
                                    inline-flex
                                    items-center
                                    justify-center
                                    rounded-xl
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
                                Create Event
                            </a>

                        @endif

                    </div>

                @endforelse

            </div>

        </div>



        {{-- QUICK ACTIONS --}}

        <div class="min-w-0">

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
                    Department Tools
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Quick Actions
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Common event management tasks.
                </p>

            </div>


            <div
                class="
                    department-section
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                "
            >


                <a
                    href="{{ route('events.create') }}"
                    class="
                        department-action
                        group
                        flex
                        items-start
                        gap-4
                        border-b
                        border-gray-100
                        p-6
                        hover:bg-gray-50
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
                            rounded-xl
                            bg-[#F1F2FA]
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
                                d="M12 5v14m7-7H5"
                            />
                        </svg>
                    </div>


                    <div class="min-w-0 flex-1">

                        <div
                            class="
                                flex
                                items-center
                                justify-between
                                gap-3
                            "
                        >
                            <p
                                class="
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                Create Event
                            </p>

                            <span
                                class="
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
                                mt-1
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            Schedule a new activity for your department.
                        </p>

                    </div>

                </a>



                <a
                    href="{{ route('events.index') }}"
                    class="
                        department-action
                        group
                        flex
                        items-start
                        gap-4
                        p-6
                        hover:bg-gray-50
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
                            rounded-xl
                            bg-[#FFF8E1]
                            text-[#A87900]
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
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </div>


                    <div class="min-w-0 flex-1">

                        <div
                            class="
                                flex
                                items-center
                                justify-between
                                gap-3
                            "
                        >
                            <p
                                class="
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                Event Directory
                            </p>

                            <span
                                class="
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
                                mt-1
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            View and manage all department event records.
                        </p>

                    </div>

                </a>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- RECENT EVENTS --}}
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
                    Department Activity
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Recent Events
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Recently created or updated event records.
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

                {{ number_format($recentEvents->count()) }}
                Recent
            </div>

        </div>


        <div
            class="
                department-section
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >

            <div class="overflow-x-auto">

                <table
                    class="
                        w-full
                        min-w-[850px]
                        text-left
                    "
                >

                    <thead class="bg-gray-50">

                        <tr>

                            <th
                                class="
                                    px-6
                                    py-4
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-gray-400
                                "
                            >
                                Event
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-gray-400
                                "
                            >
                                Date
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-gray-400
                                "
                            >
                                Location
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-gray-400
                                "
                            >
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($recentEvents as $event)

                            @php

                                $statusConfig = match(
                                    $event->status
                                ) {

                                    'Upcoming' => [
                                        'class' =>
                                            'bg-[#FFF8E1] text-[#A87900]',
                                        'dot' =>
                                            'bg-[#D4A017]',
                                    ],

                                    'Ongoing' => [
                                        'class' =>
                                            'bg-green-50 text-green-700',
                                        'dot' =>
                                            'bg-green-500',
                                    ],

                                    'Completed' => [
                                        'class' =>
                                            'bg-gray-100 text-gray-600',
                                        'dot' =>
                                            'bg-gray-500',
                                    ],

                                    'Cancelled' => [
                                        'class' =>
                                            'bg-red-50 text-red-600',
                                        'dot' =>
                                            'bg-red-500',
                                    ],

                                    default => [
                                        'class' =>
                                            'bg-gray-100 text-gray-600',
                                        'dot' =>
                                            'bg-gray-400',
                                    ],

                                };

                            @endphp


                            <tr
                                class="
                                    transition
                                    hover:bg-gray-50/70
                                "
                            >

                                <td class="px-6 py-5">

                                    <a
                                        href="{{ route(
                                            'events.show',
                                            $event->event_id
                                        ) }}"
                                        class="
                                            font-semibold
                                            text-[#101064]
                                            transition
                                            hover:text-[#D4A017]
                                        "
                                    >
                                        {{ $event->event_name }}
                                    </a>

                                </td>


                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-sm
                                        text-gray-600
                                    "
                                >
                                    {{
                                        \Carbon\Carbon::parse(
                                            $event->event_date
                                        )->format('M d, Y')
                                    }}
                                </td>


                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-sm
                                        text-gray-600
                                    "
                                >
                                    {{ $event->location }}
                                </td>


                                <td class="px-6 py-5">

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
                                            {{ $statusConfig['class'] }}
                                        "
                                    >
                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                {{ $statusConfig['dot'] }}
                                            "
                                        ></span>

                                        {{ $event->status }}
                                    </span>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="
                                        px-6
                                        py-14
                                        text-center
                                    "
                                >
                                    <p
                                        class="
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        No department events yet
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            text-gray-400
                                        "
                                    >
                                        Events created for this department
                                        will appear here.
                                    </p>
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <div
                class="
                    flex
                    flex-col
                    gap-2
                    border-t
                    border-gray-100
                    bg-gray-50/50
                    px-6
                    py-4
                    text-xs
                    text-gray-400
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                "
            >

                <span>
                    Department event monitoring
                </span>

                <span>
                    DySign • {{ $departmentName }}
                </span>

            </div>

        </div>

    </section>


</div>


</x-admin-layout>
