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
    | Schedule-Based Event Status
    |--------------------------------------------------------------------------
    |
    | We calculate each status here using $item instead of $event so the
    | Blade view never references $event before the @forelse loop begins.
    |
    */

    $computedStatuses = [];

    $ongoingCount = 0;

    $upcomingCount = 0;

    $completedCount = 0;


    foreach ($events as $item) {

        if (
            $item->status
            === 'Cancelled'
        ) {

            $computedStatuses[
                $item->event_id
            ] = 'Cancelled';

            continue;
        }


        $startsAt =
            \Carbon\Carbon::parse(
                $item->event_date
                . ' '
                . $item->start_time,
                $timezone
            );


        $endsAt =
            \Carbon\Carbon::parse(
                $item->event_date
                . ' '
                . $item->end_time,
                $timezone
            );


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
</style>

<div class="min-w-0 space-y-8">

    {{-- HERO --}}
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
        <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>

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
                    <div class="flex items-center gap-2">
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

                        {{ $now->format('F d, Y') }}
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-green-400"></span>
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
                        class="h-6 w-6"
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

                    <p class="mt-1 text-sm font-semibold text-white">
                        Automatic Event Window
                    </p>
                </div>
            </div>

        </div>
    </section>


    {{-- SUMMARY --}}
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

            <h2 class="mt-2 text-xl font-bold text-[#101064]">
                Event Status
            </h2>

            <p class="mt-1 text-sm text-gray-500">
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

                <p class="mt-2 text-3xl font-bold text-green-600">
                    {{ number_format($ongoingCount) }}
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    Scanner available now
                </p>
            </div>

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

                <p class="mt-2 text-3xl font-bold text-[#D4A017]">
                    {{ number_format($upcomingCount) }}
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    Scheduled assignments
                </p>
            </div>

            <div class="px-6 py-6">
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

                <p class="mt-2 text-3xl font-bold text-gray-600">
                    {{ number_format($completedCount) }}
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    Attendance sessions closed
                </p>
            </div>

        </div>
    </section>


    {{-- ASSIGNED EVENTS --}}
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

                <h2 class="mt-2 text-xl font-bold text-[#101064]">
                    Assigned Events
                </h2>

                <p class="mt-1 text-sm text-gray-500">
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
                <span class="h-1.5 w-1.5 rounded-full bg-[#D4A017]"></span>

                {{ number_format($events->count()) }}
                {{ $events->count() === 1 ? 'Event' : 'Events' }}
            </div>
        </div>


        <div
            class="
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >

            @forelse($events as $event)

                @php

                    $status =
                        $computedStatuses[
                            $event->event_id
                        ]
                        ?? $event->status;


                    $startsAt =
                        \Carbon\Carbon::parse(
                            $event->event_date
                            . ' '
                            . $event->start_time,
                            $timezone
                        );


                    $endsAt =
                        \Carbon\Carbon::parse(
                            $event->event_date
                            . ' '
                            . $event->end_time,
                            $timezone
                        );

                    $statusStyle = match ($status) {
                        'Ongoing' => [
                            'class' => 'bg-green-50 text-green-700',
                            'dot' => 'bg-green-500',
                        ],
                        'Upcoming' => [
                            'class' => 'bg-[#FFF8E1] text-[#A87900]',
                            'dot' => 'bg-[#D4A017]',
                        ],
                        'Completed' => [
                            'class' => 'bg-gray-100 text-gray-600',
                            'dot' => 'bg-gray-500',
                        ],
                        default => [
                            'class' => 'bg-red-50 text-red-600',
                            'dot' => 'bg-red-500',
                        ],
                    };

                @endphp

                <div
                    class="
                        border-b
                        border-gray-100
                        p-6
                        transition
                        last:border-b-0
                        hover:bg-gray-50/60
                        lg:px-7
                    "
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


                            <p class="mt-2 text-sm text-gray-500">
                                {{ $event->department->department_name ?? 'University-wide Event' }}
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
                                <span>
                                    {{ $startsAt->format('F d, Y') }}
                                </span>

                                <span>
                                    {{ $startsAt->format('g:i A') }}
                                    –
                                    {{ $endsAt->format('g:i A') }}
                                </span>

                                <span>
                                    {{ $event->location }}
                                </span>
                            </div>

                        </div>


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
                            <div class="hidden min-w-[90px] text-right md:block">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Attendance
                                </p>

                                <p class="mt-1 text-2xl font-bold text-[#101064]">
                                    {{ number_format($event->attendance_count ?? 0) }}
                                </p>
                            </div>


                            <a
                                href="{{ route('my-events.show', $event->event_id) }}"
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
                                {{ $status === 'Ongoing' ? 'Open Attendance' : 'View Event' }}

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

                </div>

            @empty

                <div class="px-6 py-16 text-center">

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

                    <p class="mt-4 font-semibold text-[#101064]">
                        No assigned events found
                    </p>

                    <p class="mt-1 text-sm text-gray-400">
                        Events assigned to your personnel account will appear here.
                    </p>
                </div>

            @endforelse

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
                <span>Assigned event monitoring</span>
                <span>DySign • Attendance Personnel</span>
            </div>

        </div>

    </section>

</div>

</x-admin-layout>
