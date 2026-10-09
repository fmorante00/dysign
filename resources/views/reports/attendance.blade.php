<x-admin-layout>

<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .attendance-report-hero {

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


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            attendance-report-hero
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
                        tracking-[0.32em]
                        text-[#E7C75B]
                    "
                >
                    Reports & Analytics
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
                    Attendance Reports
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
                    Generate and review official attendance reports
                    using recorded RFID attendance information
                    from school events.
                </p>

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
                            d="M9 17v-6m4 6V7m4 10v-3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"
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
                        Report Type
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            font-semibold
                            text-white
                        "
                    >
                        Event Attendance
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
                Report Overview
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Attendance Summary
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Summary calculated directly from recorded RFID
                attendance information.
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

                <div class="flex items-start justify-between gap-4">

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
                            Recorded Events
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
                            {{ number_format($totalEvents ?? 0) }}
                        </p>


                        <p class="mt-2 text-xs text-gray-400">
                            Events with attendance entries
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
                                d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- PRESENT --}}

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

                <div class="flex items-start justify-between gap-4">

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
                            Total Present
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
                            {{ number_format($totalPresent ?? 0) }}
                        </p>


                        <p class="mt-2 text-xs text-gray-400">
                            On-time attendance entries
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
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- LATE --}}

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

                <div class="flex items-start justify-between gap-4">

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
                            Late Attendance
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
                            {{ number_format($totalLate ?? 0) }}
                        </p>


                        <p class="mt-2 text-xs text-gray-400">
                            Attendance marked as late
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



            {{-- ON-TIME RATE --}}

            <div class="px-6 py-6">

                <div class="flex items-start justify-between gap-4">

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
                            On-Time Rate
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
                            {{ number_format($onTimeRate ?? 0, 1) }}%
                        </p>


                        <p class="mt-2 text-xs text-gray-400">
                            Present versus total entries
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
                                d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- FILTERS --}}
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
                Report Controls
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Report Filters
            </h2>


            <p class="mt-1 text-sm text-gray-500">
                Select an event and attendance status,
                then generate the report.
            </p>

        </div>


        <form
            method="GET"
            action="{{ route('reports.attendance') }}"
            class="
                border
                border-gray-200
                bg-white
                p-6
            "
        >

            <input
                type="hidden"
                name="generated"
                value="1"
            >


            <div
                class="
                    grid
                    grid-cols-1
                    gap-5
                    lg:grid-cols-[1fr_280px_auto]
                    lg:items-end
                "
            >


                {{-- EVENT --}}

                <div>

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

                        <option value="all">
                            All Events
                        </option>


                        @foreach($events as $event)

                            <option
                                value="{{ $event->event_id }}"
                                @selected(
                                    (string) $selectedEvent ===
                                    (string) $event->event_id
                                )
                            >
                                {{ $event->event_name }}
                                —
                                {{ \Carbon\Carbon::parse(
                                    $event->event_date
                                )->format('M d, Y') }}
                            </option>

                        @endforeach

                    </select>

                </div>



                {{-- STATUS --}}

                <div>

                    <label
                        for="status"
                        class="
                            mb-2
                            block
                            text-sm
                            font-semibold
                            text-gray-600
                        "
                    >
                        Attendance Status
                    </label>


                    <select
                        id="status"
                        name="status"
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

                        <option
                            value="all"
                            @selected(
                                $selectedStatus === 'all'
                            )
                        >
                            All
                        </option>


                        <option
                            value="Present"
                            @selected(
                                $selectedStatus === 'Present'
                            )
                        >
                            Present
                        </option>


                        <option
                            value="Late"
                            @selected(
                                $selectedStatus === 'Late'
                            )
                        >
                            Late
                        </option>

                    </select>

                </div>



                {{-- ACTIONS --}}

                <div
                    class="
                        flex
                        flex-col
                        gap-2
                        sm:flex-row
                    "
                >

                    <button
                        type="submit"
                        class="
                            inline-flex
                            w-full
                            items-center
                            justify-center
                            gap-2
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
                            lg:w-auto
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
                                d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.7V19a1 1 0 01-.55.9l-4 2A1 1 0 018 21v-7.3L3.2 4.6A1 1 0 013 4z"
                            />
                        </svg>

                        Generate Report

                    </button>


                    @if($generated)

                        <a
                            href="{{ route(
                                'reports.attendance'
                            ) }}"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                rounded-xl
                                border
                                border-gray-200
                                px-5
                                py-3
                                text-sm
                                font-semibold
                                text-gray-500
                                transition
                                hover:bg-gray-50
                            "
                        >
                            Reset
                        </a>

                    @endif

                </div>

            </div>

        </form>

    </section>



    {{-- ====================================================== --}}
    {{-- ATTENDANCE RECORDS --}}
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
                    Attendance Data
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Attendance Records
                </h2>


                <p class="mt-1 text-sm text-gray-500">

                    @if($generated)

                        @if($selectedEventData)

                            Report for
                            <span class="font-semibold text-gray-700">
                                {{ $selectedEventData->event_name }}
                            </span>

                        @else

                            Attendance records across all events

                        @endif

                    @else

                        Generate a report to display attendance records.

                    @endif

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

                @if($generated && $records)

                    {{ number_format($records->total()) }}
                    Record{{ $records->total() === 1 ? '' : 's' }}

                @else

                    Attendance Report

                @endif

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

            <div class="overflow-x-auto">

                <table
                    class="
                        w-full
                        min-w-[1100px]
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
                                Student
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
                                Event Date
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
                                Time In
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
                                Scanned By
                            </th>

                        </tr>

                    </thead>



                    <tbody class="divide-y divide-gray-100">

                        @if(
                            !$generated
                        )

                            <tr>

                                <td
                                    colspan="6"
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
                                            class="h-6 w-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.7V19a1 1 0 01-.55.9l-4 2A1 1 0 018 21v-7.3L3.2 4.6A1 1 0 013 4z"
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
                                        Generate an Attendance Report
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
                                        Select the report filters above,
                                        then click Generate Report.
                                    </p>

                                </td>

                            </tr>


                        @elseif(
                            $records
                            && $records->count()
                        )

                            @foreach(
                                $records
                                as $record
                            )

                                @php

                                    $studentName =
                                        trim(
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

                                    {{-- STUDENT --}}

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
                                                    h-11
                                                    w-11
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

                                                @if(
                                                    !empty(
                                                        $record->photo_path
                                                    )
                                                )

                                                    <img
                                                        src="{{ asset(
                                                            'student_photos/'
                                                            . basename(
                                                                $record->photo_path
                                                            )
                                                        ) }}"
                                                        alt="{{ $studentName }}"
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


                                            <div class="min-w-0">

                                                <p
                                                    class="
                                                        font-semibold
                                                        text-[#101064]
                                                    "
                                                >
                                                    {{ $studentName }}
                                                </p>


                                                <p
                                                    class="
                                                        mt-0.5
                                                        text-xs
                                                        text-gray-400
                                                    "
                                                >
                                                    {{ $record->student_number }}
                                                    •
                                                    {{ $record->program_code }}
                                                    {{ $record->year_level }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>



                                    {{-- EVENT --}}

                                    <td class="px-6 py-5">

                                        <p
                                            class="
                                                text-sm
                                                font-semibold
                                                text-gray-700
                                            "
                                        >
                                            {{ $record->event_name }}
                                        </p>


                                        <p
                                            class="
                                                mt-1
                                                text-xs
                                                text-gray-400
                                            "
                                        >
                                            {{ $record->location }}
                                        </p>

                                    </td>



                                    {{-- DATE --}}

                                    <td
                                        class="
                                            whitespace-nowrap
                                            px-6
                                            py-5
                                            text-sm
                                            text-gray-600
                                        "
                                    >

                                        {{ \Carbon\Carbon::parse(
                                            $record->event_date
                                        )->format('M d, Y') }}

                                    </td>



                                    {{-- TIME IN --}}

                                    <td
                                        class="
                                            whitespace-nowrap
                                            px-6
                                            py-5
                                        "
                                    >

                                        @if($record->time_in)

                                            <p
                                                class="
                                                    text-sm
                                                    font-semibold
                                                    text-gray-700
                                                "
                                            >
                                                {{ \Carbon\Carbon::parse(
                                                    $record->time_in
                                                )->format('h:i A') }}
                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-gray-400
                                                "
                                            >
                                                {{ \Carbon\Carbon::parse(
                                                    $record->time_in
                                                )->format('M d, Y') }}
                                            </p>

                                        @else

                                            <span class="text-sm text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>



                                    {{-- STATUS --}}

                                    <td class="px-6 py-5">

                                        @if(
                                            $record->status ===
                                            'Present'
                                        )

                                            <span
                                                class="
                                                    inline-flex
                                                    items-center
                                                    gap-2
                                                    rounded-full
                                                    bg-green-50
                                                    px-3
                                                    py-1.5
                                                    text-xs
                                                    font-semibold
                                                    text-green-700
                                                "
                                            >

                                                <span
                                                    class="
                                                        h-1.5
                                                        w-1.5
                                                        rounded-full
                                                        bg-green-500
                                                    "
                                                ></span>

                                                Present

                                            </span>

                                        @else

                                            <span
                                                class="
                                                    inline-flex
                                                    items-center
                                                    gap-2
                                                    rounded-full
                                                    bg-[#FFF8E1]
                                                    px-3
                                                    py-1.5
                                                    text-xs
                                                    font-semibold
                                                    text-[#9A7000]
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

                                                Late

                                            </span>

                                        @endif

                                    </td>



                                    {{-- SCANNED BY --}}

                                    <td class="px-6 py-5">

                                        <p
                                            class="
                                                text-sm
                                                font-medium
                                                text-gray-600
                                            "
                                        >
                                            {{
                                                $record->scanned_by_name
                                                ?? 'Unknown Personnel'
                                            }}
                                        </p>

                                    </td>

                                </tr>

                            @endforeach


                        @else

                            <tr>

                                <td
                                    colspan="6"
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
                                            class="h-6 w-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 17v-6m4 6V7m4 10v-3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"
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
                                        No Attendance Records Found
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
                                        No attendance records match
                                        the selected report filters.
                                    </p>

                                </td>

                            </tr>

                        @endif

                    </tbody>

                </table>

            </div>



            {{-- TABLE FOOTER --}}

            <div
                class="
                    border-t
                    border-gray-100
                    bg-gray-50/50
                    px-6
                    py-4
                "
            >

                <div
                    class="
                        flex
                        flex-col
                        gap-4
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                    "
                >

                    <div>

                        <p
                            class="
                                text-xs
                                font-medium
                                text-gray-500
                            "
                        >
                            DySign • Attendance Reports
                        </p>


                        @if(
                            $generated
                            && $records
                        )

                            <p
                                class="
                                    mt-1
                                    text-xs
                                    text-gray-400
                                "
                            >
                                {{ number_format(
                                    $records->total()
                                ) }}
                                attendance
                                {{ $records->total() === 1
                                    ? 'record'
                                    : 'records'
                                }}
                                found.
                            </p>

                        @else

                            <p
                                class="
                                    mt-1
                                    text-xs
                                    text-gray-400
                                "
                            >
                                Official RFID attendance reporting
                            </p>

                        @endif

                    </div>


                    @if(
                        $generated
                        && $records
                        && $records->hasPages()
                    )

                        <div>
                            {{ $records->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </section>


</div>


</x-admin-layout>