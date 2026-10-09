<x-admin-layout>

<style>

    .attendance-monitor-hero {

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


    @keyframes monitorPulse {

        0%, 100% {
            transform: scale(1);
            opacity: .45;
        }

        50% {
            transform: scale(1.1);
            opacity: 1;
        }

    }


    .monitor-pulse {
        animation: monitorPulse 1.8s ease-in-out infinite;
    }

</style>


<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            attendance-monitor-hero
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

            <div class="min-w-0 max-w-3xl">

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.32em]
                        text-[#E7C75B]
                    "
                >
                    Attendance Monitoring
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
                    @if($selectedEvent)

                        {{ $selectedEvent->event_name }}

                    @else

                        Select an Event

                    @endif
                </h1>


                <p
                    class="
                        mt-3
                        max-w-2xl
                        text-sm
                        leading-6
                        text-white/70
                    "
                >
                    @if($selectedEvent)

                        Monitor live RFID attendance records
                        and student attendance activity for
                        the selected event.

                    @else

                        Choose an event below to view its
                        attendance records and live RFID activity.

                    @endif
                </p>


                @if($selectedEvent)

                    <div
                        class="
                            mt-5
                            flex
                            flex-wrap
                            items-center
                            gap-x-3
                            gap-y-2
                            text-sm
                            text-white/75
                        "
                    >

                        <span>
                            {{ \Carbon\Carbon::parse(
                                $selectedEvent->event_date
                            )->format('F d, Y') }}
                        </span>

                        <span class="text-white/30">
                            •
                        </span>

                        <span>
                            {{ \Carbon\Carbon::parse(
                                $selectedEvent->start_time
                            )->format('g:i A') }}

                            –

                            {{ \Carbon\Carbon::parse(
                                $selectedEvent->end_time
                            )->format('g:i A') }}
                        </span>

                        <span class="text-white/30">
                            •
                        </span>

                        <span>
                            {{ $selectedEvent->location }}
                        </span>

                    </div>

                @endif

            </div>


            @if($selectedEvent)

                <a
                    href="{{ route(
                        'events.show',
                        $selectedEvent->event_id
                    ) }}"
                    class="
                        inline-flex
                        shrink-0
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

                    View Event

                </a>

            @endif

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- EVENT SELECTION --}}
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
                Event Selection
            </p>

            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Choose Attendance Session
            </h2>

            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Select the event whose attendance
                records you want to monitor.
            </p>

        </div>


        <form
            method="GET"
            action="{{ route('attendance.monitor') }}"
            class="
                border
                border-gray-200
                bg-white
                p-6
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-4
                    lg:flex-row
                    lg:items-end
                "
            >

                <div class="min-w-0 flex-1">

                    <label
                        for="event_id"
                        class="
                            mb-2
                            block
                            text-sm
                            font-semibold
                            text-gray-600
                        "
                    >
                        Event
                    </label>

                    <select
                        id="event_id"
                        name="event_id"
                        required
                        class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            bg-white
                            px-4
                            py-3
                            text-sm
                            text-gray-700
                            outline-none
                            transition
                            focus:border-[#D4A017]
                            focus:ring-2
                            focus:ring-[#D4A017]/20
                        "
                    >

                        <option value="">
                            Select an event
                        </option>

                        @foreach($events as $event)

                            <option
                                value="{{ $event->event_id }}"
                                @selected(
                                    $selectedEvent &&
                                    $selectedEvent->event_id ===
                                    $event->event_id
                                )
                            >

                                {{ $event->event_name }}

                                —

                                {{ \Carbon\Carbon::parse(
                                    $event->event_date
                                )->format('M d, Y') }}

                                —

                                {{ $event->status }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <button
                    type="submit"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        rounded-xl
                        bg-[#101064]
                        px-6
                        py-3
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-[#D4A017]
                        hover:text-[#101064]
                    "
                >
                    View Attendance
                </button>

            </div>

        </form>

    </section>



    @if(!$selectedEvent)

        {{-- ====================================================== --}}
        {{-- NO EVENT SELECTED --}}
        {{-- ====================================================== --}}

        <section
            class="
                border
                border-gray-200
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
                    h-14
                    w-14
                    items-center
                    justify-center
                    rounded-2xl
                    bg-[#F1F2FA]
                    text-[#101064]
                "
            >

                <svg
                    class="h-7 w-7"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
                    />
                </svg>

            </div>


            <h3
                class="
                    mt-5
                    text-lg
                    font-bold
                    text-[#101064]
                "
            >
                No Event Selected
            </h3>

            <p
                class="
                    mx-auto
                    mt-2
                    max-w-md
                    text-sm
                    leading-6
                    text-gray-500
                "
            >
                Select an event above to load its
                attendance information from the DySign database.
            </p>

        </section>


    @else


        {{-- ====================================================== --}}
        {{-- LIVE OVERVIEW --}}
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
                    Attendance Overview
                </p>

                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Monitoring Summary
                </h2>

            </div>


            <div
                class="
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


                {{-- EVENT STATUS --}}

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

                    <p
                        class="
                            text-[10px]
                            font-bold
                            uppercase
                            tracking-[0.16em]
                            text-gray-400
                        "
                    >
                        Event Status
                    </p>


                    <div class="mt-3">

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

                                @if($selectedEvent->status === 'Ongoing')
                                    bg-green-50
                                    text-green-700
                                @elseif($selectedEvent->status === 'Upcoming')
                                    bg-blue-50
                                    text-blue-700
                                @elseif($selectedEvent->status === 'Completed')
                                    bg-gray-100
                                    text-gray-600
                                @else
                                    bg-yellow-50
                                    text-yellow-700
                                @endif
                            "
                        >

                            <span
                                class="
                                    h-1.5
                                    w-1.5
                                    rounded-full

                                    @if($selectedEvent->status === 'Ongoing')
                                        bg-green-500
                                    @elseif($selectedEvent->status === 'Upcoming')
                                        bg-blue-500
                                    @else
                                        bg-gray-400
                                    @endif
                                "
                            ></span>

                            {{ $selectedEvent->status }}

                        </span>

                    </div>

                </div>



                {{-- RECORDED --}}

                <div
                    class="
                        border-b
                        border-gray-100
                        px-6
                        py-6
                        xl:border-r
                        xl:border-b-0
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
                        Recorded Attendance
                    </p>

                    <p
                        id="attendance_count"
                        class="
                            mt-2
                            text-3xl
                            font-bold
                            text-[#101064]
                        "
                    >
                        {{ $attendanceCount }}
                    </p>

                </div>



                {{-- PRESENT --}}

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

                    <p
                        class="
                            text-[10px]
                            font-bold
                            uppercase
                            tracking-[0.16em]
                            text-gray-400
                        "
                    >
                        Present
                    </p>

                    <p
                        id="present_count"
                        class="
                            mt-2
                            text-3xl
                            font-bold
                            text-green-600
                        "
                    >
                        {{ $presentCount }}
                    </p>

                </div>



                {{-- LATE --}}

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
                        Late
                    </p>

                    <p
                        id="late_count"
                        class="
                            mt-2
                            text-3xl
                            font-bold
                            text-[#D4A017]
                        "
                    >
                        {{ $lateCount }}
                    </p>

                </div>

            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- LIVE MONITORING --}}
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
                    Live Monitoring
                </p>

                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Latest Attendance Activity
                </h2>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    This page automatically checks the attendance
                    database for new RFID records.
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
                    xl:grid-cols-[330px_1fr]
                "
            >


                {{-- LIVE DATABASE FEED --}}

                <div
                    class="
                        flex
                        min-h-[320px]
                        flex-col
                        items-center
                        justify-center
                        border-b
                        border-gray-100
                        bg-[#101064]
                        px-8
                        py-10
                        text-center
                        text-white
                        xl:border-b-0
                        xl:border-r
                        xl:border-white/10
                    "
                >

                    <div
                        class="
                            relative
                            flex
                            h-24
                            w-24
                            items-center
                            justify-center
                            rounded-full
                            border
                            border-white/20
                            bg-white/10
                        "
                    >

                        <div
                            class="
                                monitor-pulse
                                absolute
                                h-16
                                w-16
                                rounded-full
                                border
                                border-[#D4A017]/70
                            "
                        ></div>

                        <svg
                            class="
                                relative
                                z-10
                                h-10
                                w-10
                                text-[#E7C75B]
                            "
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>

                    </div>


                    <p
                        class="
                            mt-7
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.3em]
                            text-[#E7C75B]
                        "
                    >
                        Database Feed
                    </p>


                    <h3
                        class="
                            mt-2
                            text-2xl
                            font-bold
                        "
                    >
                        Live Monitoring
                    </h3>


                    <p
                        class="
                            mt-3
                            max-w-xs
                            text-sm
                            leading-6
                            text-white/65
                        "
                    >
                        New attendance records for this
                        event will appear automatically.
                    </p>


                    <div
                        class="
                            mt-7
                            inline-flex
                            items-center
                            gap-2
                            rounded-full
                            bg-white/10
                            px-4
                            py-2
                            text-xs
                            font-semibold
                            text-white/80
                        "
                    >

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-green-400
                                animate-pulse
                            "
                        ></span>

                        Monitoring Active

                    </div>

                </div>



                {{-- LATEST ATTENDANCE --}}

                <div
                    id="latest_attendance_container"
                    class="
                        flex
                        min-h-[320px]
                        items-center
                        px-7
                        py-9
                        sm:px-10
                        lg:px-12
                    "
                >

                    @if($latestAttendance)

                        @php

                            $latestName = trim(
                                $latestAttendance->first_name
                                . ' '
                                . (
                                    $latestAttendance->middle_name
                                        ? $latestAttendance->middle_name . ' '
                                        : ''
                                )
                                . $latestAttendance->last_name
                            );

                        @endphp


                        <div
                            class="
                                flex
                                w-full
                                flex-col
                                gap-7
                                sm:flex-row
                                sm:items-center
                            "
                        >

                        <div
                            class="
                                flex
                                h-24
                                w-24
                                shrink-0
                                items-center
                                justify-center
                                overflow-hidden
                                rounded-2xl
                                bg-[#F1F2FA]
                                text-4xl
                                font-bold
                                text-[#101064]
                            "
                        >

                            @if($latestAttendance->photo_path)

                                <img
                                    src="{{ asset(
                                        'student_photos/'
                                        . basename($latestAttendance->photo_path)
                                    ) }}"
                                    alt="{{ $latestName }}"
                                    class="
                                        h-full
                                        w-full
                                        object-cover
                                    "
                                >

                            @else

                                {{ strtoupper(
                                    substr(
                                        $latestAttendance->first_name,
                                        0,
                                        1
                                    )
                                ) }}

                            @endif

                        </div>


                            <div>

                                <p
                                    class="
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-[0.24em]
                                        text-[#D4A017]
                                    "
                                >
                                    Latest Attendance
                                </p>

                                <h3
                                    class="
                                        mt-2
                                        text-2xl
                                        font-bold
                                        text-[#101064]
                                        md:text-3xl
                                    "
                                >
                                    {{ $latestName }}
                                </h3>


                                <div
                                    class="
                                        mt-3
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-x-3
                                        gap-y-2
                                        text-sm
                                        text-gray-500
                                    "
                                >

                                    <span>
                                        {{ $latestAttendance->student_number }}
                                    </span>

                                    <span class="text-gray-300">
                                        •
                                    </span>

                                    <span>
                                        {{ $latestAttendance->program_code }}
                                    </span>

                                    <span class="text-gray-300">
                                        •
                                    </span>

                                    <span>
                                        Year {{ $latestAttendance->year_level }}
                                    </span>

                                </div>


                                <div
                                    class="
                                        mt-5
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-3
                                    "
                                >

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

                                            @if($latestAttendance->status === 'Late')
                                                bg-yellow-50
                                                text-yellow-700
                                            @else
                                                bg-green-50
                                                text-green-700
                                            @endif
                                        "
                                    >
                                        {{ $latestAttendance->status }}
                                    </span>


                                    <span
                                        class="
                                            text-sm
                                            font-medium
                                            text-gray-500
                                        "
                                    >

                                        Recorded at

                                        {{ \Carbon\Carbon::parse(
                                            $latestAttendance->time_in
                                        )->format('h:i A') }}

                                    </span>

                                </div>

                            </div>

                        </div>


                    @else

                        <div class="w-full text-center">

                            <p
                                class="
                                    text-lg
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                No Attendance Yet
                            </p>

                            <p
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-500
                                "
                            >
                                Attendance records will appear
                                here once students are scanned.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- ATTENDANCE TABLE --}}
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
                    Attendance Records
                </p>

                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Live Attendance Feed
                </h2>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Latest attendance records stored for
                    {{ $selectedEvent->event_name }}.
                </p>

            </div>


            <div
                class="
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
                            min-w-[950px]
                            text-left
                        "
                    >

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.16em] text-gray-400">
                                    Time In
                                </th>

                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.16em] text-gray-400">
                                    Student
                                </th>

                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.16em] text-gray-400">
                                    Program
                                </th>

                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.16em] text-gray-400">
                                    Year
                                </th>

                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.16em] text-gray-400">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-[0.16em] text-gray-400">
                                    Scanned By
                                </th>

                            </tr>

                        </thead>


                        <tbody
                            id="attendance_table_body"
                            class="divide-y divide-gray-100"
                        >

                            @forelse($attendanceRecords as $record)

                                @php

                                    $recordName = trim(
                                        $record->first_name
                                        . ' '
                                        . (
                                            $record->middle_name
                                                ? $record->middle_name . ' '
                                                : ''
                                        )
                                        . $record->last_name
                                    );

                                @endphp


                                <tr
                                    class="
                                        transition
                                        hover:bg-gray-50/70
                                    "
                                >

                                    <td
                                        class="
                                            whitespace-nowrap
                                            px-6
                                            py-5
                                            text-sm
                                            font-semibold
                                            text-gray-600
                                        "
                                    >
                                        {{ \Carbon\Carbon::parse(
                                            $record->time_in
                                        )->format('h:i A') }}
                                    </td>


                                    <td class="px-6 py-5">

                                        <div
                                            class="
                                                flex
                                                items-center
                                                gap-3
                                            "
                                        >

                                    <div
                                        class="
                                            flex
                                            h-10
                                            w-10
                                            shrink-0
                                            items-center
                                            justify-center
                                            overflow-hidden
                                            rounded-xl
                                            bg-[#F1F2FA]
                                            text-sm
                                            font-bold
                                            text-[#101064]
                                        "
                                    >

                                        @if($record->photo_path)

                                            <img
                                                src="{{ asset(
                                                    'student_photos/'
                                                    . basename($record->photo_path)
                                                ) }}"
                                                alt="{{ $recordName }}"
                                                class="
                                                    h-full
                                                    w-full
                                                    object-cover
                                                "
                                            >

                                        @else

                                            {{ strtoupper(
                                                substr(
                                                    $record->first_name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        @endif

                                    </div>


                                            <div>

                                                <p
                                                    class="
                                                        font-semibold
                                                        text-[#101064]
                                                    "
                                                >
                                                    {{ $recordName }}
                                                </p>

                                                <p
                                                    class="
                                                        mt-0.5
                                                        text-xs
                                                        text-gray-400
                                                    "
                                                >
                                                    {{ $record->student_number }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    <td
                                        class="
                                            px-6
                                            py-5
                                            text-sm
                                            font-medium
                                            text-gray-600
                                        "
                                    >
                                        {{ $record->program_code }}
                                    </td>


                                    <td
                                        class="
                                            px-6
                                            py-5
                                            text-sm
                                            text-gray-600
                                        "
                                    >
                                        Year {{ $record->year_level }}
                                    </td>


                                    <td class="px-6 py-5">

                                        <span
                                            class="
                                                inline-flex
                                                rounded-full
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold

                                                @if($record->status === 'Late')
                                                    bg-yellow-50
                                                    text-yellow-700
                                                @else
                                                    bg-green-50
                                                    text-green-700
                                                @endif
                                            "
                                        >
                                            {{ $record->status }}
                                        </span>

                                    </td>


                                    <td
                                        class="
                                            px-6
                                            py-5
                                            text-sm
                                            text-gray-500
                                        "
                                    >
                                        {{ $record->scanned_by_name ?? 'Unknown' }}
                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="
                                            px-6
                                            py-14
                                            text-center
                                            text-sm
                                            text-gray-400
                                        "
                                    >
                                        No attendance records found
                                        for this event.
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
                        Showing the latest 50 attendance records
                    </span>

                    <span>
                        Auto-refreshes every 5 seconds
                    </span>

                </div>

            </div>

        </section>


        {{-- ====================================================== --}}
        {{-- LIVE DATABASE REFRESH --}}
        {{-- ====================================================== --}}

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    const feedUrl =
                        @json(
                            route(
                                'attendance.monitor.feed',
                                $selectedEvent->event_id
                            )
                        );


                    function escapeHtml(value) {

                        if (value === null || value === undefined) {
                            return '';
                        }

                        return String(value)
                            .replaceAll('&', '&amp;')
                            .replaceAll('<', '&lt;')
                            .replaceAll('>', '&gt;')
                            .replaceAll('"', '&quot;')
                            .replaceAll("'", '&#039;');
                    }


                    function statusBadge(status) {

                        if (status === 'Late') {

                            return `
                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        bg-yellow-50
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-yellow-700
                                    "
                                >
                                    Late
                                </span>
                            `;

                        }


                        return `
                            <span
                                class="
                                    inline-flex
                                    rounded-full
                                    bg-green-50
                                    px-3
                                    py-1
                                    text-xs
                                    font-semibold
                                    text-green-700
                                "
                            >
                                Present
                            </span>
                        `;
                    }


                    function updateLatest(record) {

                        const container =
                            document.getElementById(
                                'latest_attendance_container'
                            );


                        if (!record) {

                            container.innerHTML = `
                                <div class="w-full text-center">

                                    <p
                                        class="
                                            text-lg
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        No Attendance Yet
                                    </p>

                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            text-gray-500
                                        "
                                    >
                                        Attendance records will appear
                                        here once students are scanned.
                                    </p>

                                </div>
                            `;

                            return;
                        }


                        container.innerHTML = `

                            <div
                                class="
                                    flex
                                    w-full
                                    flex-col
                                    gap-7
                                    sm:flex-row
                                    sm:items-center
                                "
                            >

                            <div
                                class="
                                    flex
                                    h-24
                                    w-24
                                    shrink-0
                                    items-center
                                    justify-center
                                    overflow-hidden
                                    rounded-2xl
                                    bg-[#F1F2FA]
                                    text-4xl
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                ${
                                    record.photo_url
                                        ? `
                                            <img
                                                src="${escapeHtml(record.photo_url)}"
                                                alt="${escapeHtml(record.name)}"
                                                class="h-full w-full object-cover"
                                            >
                                        `
                                        : escapeHtml(record.initial)
                                }
                            </div>


                                <div>

                                    <p
                                        class="
                                            text-[10px]
                                            font-bold
                                            uppercase
                                            tracking-[0.24em]
                                            text-[#D4A017]
                                        "
                                    >
                                        Latest Attendance
                                    </p>


                                    <h3
                                        class="
                                            mt-2
                                            text-2xl
                                            font-bold
                                            text-[#101064]
                                            md:text-3xl
                                        "
                                    >
                                        ${escapeHtml(record.name)}
                                    </h3>


                                    <div
                                        class="
                                            mt-3
                                            flex
                                            flex-wrap
                                            items-center
                                            gap-x-3
                                            gap-y-2
                                            text-sm
                                            text-gray-500
                                        "
                                    >

                                        <span>
                                            ${escapeHtml(record.student_number)}
                                        </span>

                                        <span class="text-gray-300">
                                            •
                                        </span>

                                        <span>
                                            ${escapeHtml(record.program_code)}
                                        </span>

                                        <span class="text-gray-300">
                                            •
                                        </span>

                                        <span>
                                            Year ${escapeHtml(record.year_level)}
                                        </span>

                                    </div>


                                    <div
                                        class="
                                            mt-5
                                            flex
                                            flex-wrap
                                            items-center
                                            gap-3
                                        "
                                    >

                                        ${statusBadge(record.status)}

                                        <span
                                            class="
                                                text-sm
                                                font-medium
                                                text-gray-500
                                            "
                                        >
                                            Recorded at
                                            ${escapeHtml(record.time_in)}
                                        </span>

                                    </div>

                                </div>

                            </div>
                        `;
                    }


                    function updateTable(records) {

                        const body =
                            document.getElementById(
                                'attendance_table_body'
                            );


                        if (!records.length) {

                            body.innerHTML = `

                                <tr>

                                    <td
                                        colspan="6"
                                        class="
                                            px-6
                                            py-14
                                            text-center
                                            text-sm
                                            text-gray-400
                                        "
                                    >
                                        No attendance records
                                        found for this event.
                                    </td>

                                </tr>
                            `;

                            return;
                        }


                        body.innerHTML =
                            records.map(function (record) {

                                return `

                                    <tr
                                        class="
                                            transition
                                            hover:bg-gray-50/70
                                        "
                                    >

                                        <td
                                            class="
                                                whitespace-nowrap
                                                px-6
                                                py-5
                                                text-sm
                                                font-semibold
                                                text-gray-600
                                            "
                                        >
                                            ${escapeHtml(record.time_in)}
                                        </td>


                                        <td class="px-6 py-5">

                                            <div
                                                class="
                                                    flex
                                                    items-center
                                                    gap-3
                                                "
                                            >

                                        <div
                                            class="
                                                flex
                                                h-10
                                                w-10
                                                shrink-0
                                                items-center
                                                justify-center
                                                overflow-hidden
                                                rounded-xl
                                                bg-[#F1F2FA]
                                                text-sm
                                                font-bold
                                                text-[#101064]
                                            "
                                        >
                                            ${
                                                record.photo_url
                                                    ? `
                                                        <img
                                                            src="${escapeHtml(record.photo_url)}"
                                                            alt="${escapeHtml(record.name)}"
                                                            class="h-full w-full object-cover"
                                                        >
                                                    `
                                                    : escapeHtml(record.initial)
                                            }
                                        </div>


                                                <div>

                                                    <p
                                                        class="
                                                            font-semibold
                                                            text-[#101064]
                                                        "
                                                    >
                                                        ${escapeHtml(record.name)}
                                                    </p>

                                                    <p
                                                        class="
                                                            mt-0.5
                                                            text-xs
                                                            text-gray-400
                                                        "
                                                    >
                                                        ${escapeHtml(record.student_number)}
                                                    </p>

                                                </div>

                                            </div>

                                        </td>


                                        <td
                                            class="
                                                px-6
                                                py-5
                                                text-sm
                                                font-medium
                                                text-gray-600
                                            "
                                        >
                                            ${escapeHtml(record.program_code)}
                                        </td>


                                        <td
                                            class="
                                                px-6
                                                py-5
                                                text-sm
                                                text-gray-600
                                            "
                                        >
                                            Year ${escapeHtml(record.year_level)}
                                        </td>


                                        <td class="px-6 py-5">
                                            ${statusBadge(record.status)}
                                        </td>


                                        <td
                                            class="
                                                px-6
                                                py-5
                                                text-sm
                                                text-gray-500
                                            "
                                        >
                                            ${escapeHtml(record.scanned_by)}
                                        </td>

                                    </tr>
                                `;

                            })
                            .join('');
                    }


                    async function refreshAttendance() {

                        try {

                            const response =
                                await fetch(
                                    feedUrl,
                                    {
                                        headers: {
                                            'Accept':
                                                'application/json'
                                        }
                                    }
                                );


                            if (!response.ok) {
                                return;
                            }


                            const data =
                                await response.json();


                            document.getElementById(
                                'attendance_count'
                            ).textContent =
                                data.attendance_count;


                            document.getElementById(
                                'present_count'
                            ).textContent =
                                data.present_count;


                            document.getElementById(
                                'late_count'
                            ).textContent =
                                data.late_count;


                            updateLatest(
                                data.latest
                            );


                            updateTable(
                                data.records
                            );

                        }
                        catch (error) {

                            console.error(
                                'Attendance monitoring refresh failed:',
                                error
                            );

                        }
                    }


                    setInterval(
                        refreshAttendance,
                        5000
                    );

                }
            );

        </script>


    @endif


</div>

</x-admin-layout>