<x-admin-layout>

@php

    $timezone = 'Asia/Manila';

    $startsAt = \Carbon\Carbon::parse(
        $event->event_date . ' ' . $event->start_time,
        $timezone
    );

    $endsAt = \Carbon\Carbon::parse(
        $event->event_date . ' ' . $event->end_time,
        $timezone
    );

    $now = \Carbon\Carbon::now($timezone);

    $effectiveStatus = match (true) {
        $event->status === 'Cancelled' => 'Cancelled',
        $now->lt($startsAt) => 'Upcoming',
        $now->lte($endsAt) => 'Ongoing',
        default => 'Completed',
    };

    $canScan = $effectiveStatus === 'Ongoing';

    $attendanceCount = $attendanceCount
        ?? \App\Models\AttendanceRecord::where(
            'event_id',
            $event->event_id
        )->count();

    $statusConfig = match ($effectiveStatus) {
        'Ongoing' => [
            'label' => 'Ongoing',
            'class' => 'bg-green-400/20 text-green-100',
            'dot' => 'bg-green-400',
        ],
        'Upcoming' => [
            'label' => 'Upcoming',
            'class' => 'bg-[#D4A017]/20 text-[#F7E6A7]',
            'dot' => 'bg-[#D4A017]',
        ],
        'Completed' => [
            'label' => 'Completed',
            'class' => 'bg-white/15 text-white',
            'dot' => 'bg-white/70',
        ],
        default => [
            'label' => 'Cancelled',
            'class' => 'bg-red-400/20 text-red-100',
            'dot' => 'bg-red-400',
        ],
    };
@endphp

<style>
    .assigned-event-hero {
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
            assigned-event-hero
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
            <div class="min-w-0 max-w-3xl">

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
                        break-words
                        text-3xl
                        font-bold
                        tracking-tight
                        md:text-4xl
                    "
                >
                    {{ $event->event_name }}
                </h1>

                <div
                    class="
                        mt-5
                        flex
                        flex-wrap
                        items-center
                        gap-3
                        text-sm
                        text-white/75
                    "
                >
                    <span>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                    </span>

                    <span class="text-white/30">•</span>

                    <span>
                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                        –
                        {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                    </span>

                    <span class="text-white/30">•</span>

                    <span>{{ $event->location }}</span>
                </div>

                <div class="mt-5">
                    <span
                        class="
                            inline-flex
                            items-center
                            gap-2
                            rounded-full
                            px-3
                            py-1.5
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

                        {{ $statusConfig['label'] }}
                    </span>
                </div>

            </div>

            <div class="shrink-0">

                @if($canScan)

                    <a
                        href="{{ route('attendance.index', $event->event_id) }}"
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
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            />
                        </svg>

                        Open RFID Scanner
                    </a>

                @elseif($effectiveStatus === 'Upcoming')

                    <div
                        class="
                            rounded-2xl
                            border
                            border-white/15
                            bg-white/10
                            px-5
                            py-4
                            backdrop-blur-sm
                        "
                    >
                        <p
                            class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-[0.22em]
                                text-[#E7C75B]
                            "
                        >
                            Attendance Opens
                        </p>

                        <p class="mt-1 font-semibold text-white">
                            {{ $startsAt->format('M d, Y • g:i A') }}
                        </p>
                    </div>

                @else

                    <div
                        class="
                            rounded-2xl
                            border
                            border-white/15
                            bg-white/10
                            px-5
                            py-4
                            backdrop-blur-sm
                        "
                    >
                        <p
                            class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-[0.22em]
                                text-[#E7C75B]
                            "
                        >
                            Attendance Session
                        </p>

                        <p class="mt-1 font-semibold text-white">
                            {{ $effectiveStatus === 'Cancelled' ? 'Cancelled' : 'Closed' }}
                        </p>
                    </div>

                @endif

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
                Event Overview
            </p>

            <h2 class="mt-2 text-xl font-bold text-[#101064]">
                Attendance Session
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Review the assigned event and current attendance availability.
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
                    Attendance Recorded
                </p>

                <p class="mt-2 text-3xl font-bold text-[#101064]">
                    {{ number_format($attendanceCount) }}
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    {{ $attendanceCount === 1 ? 'Student record' : 'Student records' }}
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
                    Scanner Status
                </p>

                <p
                    class="
                        mt-2
                        text-xl
                        font-bold
                        {{ $canScan ? 'text-green-600' : 'text-gray-500' }}
                    "
                >
                    {{ $canScan ? 'Available' : 'Closed' }}
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    @if($canScan)
                        RFID attendance may be recorded now.
                    @elseif($effectiveStatus === 'Upcoming')
                        Scanner opens automatically at the event start time.
                    @else
                        RFID attendance is no longer available.
                    @endif
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
                    Department
                </p>

                <p class="mt-2 text-lg font-bold text-[#101064]">
                    {{ $event->department->department_name ?? 'University-wide Event' }}
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    Assigned event
                </p>
            </div>

        </div>
    </section>


    {{-- DETAILS --}}
    <section
        class="
            grid
            grid-cols-1
            gap-6
            lg:grid-cols-2
        "
    >

        <div
            class="
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >
            <div class="border-b border-gray-100 px-6 py-5">
                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.25em]
                        text-[#D4A017]
                    "
                >
                    Event Information
                </p>

                <h2 class="mt-2 text-xl font-bold text-[#101064]">
                    Assignment Details
                </h2>
            </div>

            <div class="divide-y divide-gray-100">

                <div class="px-6 py-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Department
                    </p>

                    <p class="mt-1 font-semibold text-gray-700">
                        {{ $event->department->department_name ?? 'University-wide Event' }}
                    </p>
                </div>

                <div class="px-6 py-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Venue
                    </p>

                    <p class="mt-1 font-semibold text-gray-700">
                        {{ $event->location }}
                    </p>
                </div>

                <div class="px-6 py-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Date
                    </p>

                    <p class="mt-1 font-semibold text-gray-700">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                    </p>
                </div>

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
            <div class="border-b border-gray-100 px-6 py-5">
                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.25em]
                        text-[#D4A017]
                    "
                >
                    Event Schedule
                </p>

                <h2 class="mt-2 text-xl font-bold text-[#101064]">
                    Attendance Window
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2">

                <div
                    class="
                        border-b
                        border-gray-100
                        px-6
                        py-6
                        sm:border-b-0
                        sm:border-r
                    "
                >
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Starts
                    </p>

                    <p class="mt-2 text-2xl font-bold text-[#101064]">
                        {{ $startsAt->format('g:i A') }}
                    </p>
                </div>

                <div class="px-6 py-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Ends
                    </p>

                    <p class="mt-2 text-2xl font-bold text-[#101064]">
                        {{ $endsAt->format('g:i A') }}
                    </p>
                </div>

            </div>

            @if($effectiveStatus === 'Completed')
                <div
                    class="
                        border-t
                        border-gray-100
                        bg-gray-50
                        px-6
                        py-4
                        text-sm
                        text-gray-500
                    "
                >
                    This attendance session closed automatically when the scheduled end time was reached.
                </div>
            @endif
        </div>

    </section>


    <div>
        <a
            href="{{ route('my-events.index') }}"
            class="
                inline-flex
                items-center
                gap-2
                text-sm
                font-semibold
                text-[#101064]
                transition
                hover:text-[#D4A017]
            "
        >
            ← Back to Assigned Events
        </a>
    </div>

</div>

</x-admin-layout>
