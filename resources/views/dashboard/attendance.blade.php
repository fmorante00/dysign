<x-admin-layout>

<style>
    .attendance-hero {
        background:
            linear-gradient(
                100deg,
                rgba(16,16,100,.96) 0%,
                rgba(16,16,100,.89) 55%,
                rgba(16,16,100,.72) 100%
            ),
            url('{{ asset('images/school.jpg') }}');

        background-size: cover;
        background-position: center;
    }
</style>


<div class="space-y-8">


    <!-- ====================================================== -->
    <!-- HERO -->
    <!-- ====================================================== -->

    <section
        class="
        attendance-hero
        relative
        overflow-hidden
        rounded-[28px]
        px-8
        py-8
        text-white
        lg:px-10
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


            <div>

                <p
                    class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.35em]
                    text-[#E7C75B]
                    "
                >
                    Attendance Personnel
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
                    Good day, {{ auth()->user()->name }}
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
                    Manage your assigned events and monitor attendance
                    activities from one place.
                </p>

            </div>



            <a
                href="{{ route('my-events.index') }}"
                class="
                inline-flex
                shrink-0
                items-center
                justify-center
                gap-2
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

                My Assigned Events

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

    </section>





    <!-- ====================================================== -->
    <!-- ATTENDANCE OVERVIEW -->
    <!-- ====================================================== -->

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
                Overview
            </p>

            <h2
                class="
                mt-2
                text-xl
                font-bold
                text-[#101064]
                "
            >
                Attendance Activity
            </h2>

        </div>



        <div
            class="
            grid
            grid-cols-2
            overflow-hidden
            border
            border-gray-200
            bg-white
            md:grid-cols-4
            "
        >


            <!-- ASSIGNED -->

            <div
                class="
                border-b
                border-r
                border-gray-100
                px-6
                py-6
                md:border-b-0
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
                    Assigned Events
                </p>


                <div
                    class="
                    mt-2
                    flex
                    items-end
                    gap-2
                    "
                >

                    <span
                        class="
                        text-4xl
                        font-bold
                        text-[#101064]
                        "
                    >
                        {{ $totalAssignedEvents }}
                    </span>

                </div>

            </div>



            <!-- UPCOMING -->

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
                    text-xs
                    font-semibold
                    uppercase
                    tracking-wider
                    text-gray-400
                    "
                >
                    Upcoming
                </p>


                <div
                    class="
                    mt-2
                    flex
                    items-end
                    gap-2
                    "
                >

                    <span
                        class="
                        text-4xl
                        font-bold
                        text-[#D4A017]
                        "
                    >
                        {{ $upcomingEventsCount }}
                    </span>

                </div>

            </div>



            <!-- TODAY -->

            <div
                class="
                border-r
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
                    tracking-wider
                    text-gray-400
                    "
                >
                    Scanned Today
                </p>


                <div
                    class="
                    mt-2
                    flex
                    items-end
                    gap-2
                    "
                >

                    <span
                        class="
                        text-4xl
                        font-bold
                        text-green-600
                        "
                    >
                        {{ $todayScanned }}
                    </span>

                </div>

            </div>



            <!-- TOTAL -->

            <div class="px-6 py-6">

                <p
                    class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-wider
                    text-gray-400
                    "
                >
                    Total Scans
                </p>


                <div
                    class="
                    mt-2
                    flex
                    items-end
                    gap-2
                    "
                >

                    <span
                        class="
                        text-4xl
                        font-bold
                        text-[#101064]
                        "
                    >
                        {{ $totalScanned }}
                    </span>

                </div>

            </div>


        </div>

    </section>





    <!-- ====================================================== -->
    <!-- CURRENT / NEXT EVENT -->
    <!-- ====================================================== -->

    @if($currentEvent)

        <section
            class="
            overflow-hidden
            border
            border-gray-200
            bg-white
            "
        >

            <div
                class="
                grid
                grid-cols-1
                lg:grid-cols-[8px_1fr]
                "
            >


                <div class="bg-[#D4A017]"></div>


                <div class="p-7 lg:p-8">


                    <div
                        class="
                        flex
                        flex-col
                        gap-7
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                        "
                    >


                        <!-- DETAILS -->

                        <div class="min-w-0">


                            <div
                                class="
                                flex
                                flex-wrap
                                items-center
                                gap-3
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
                                    @if($currentEvent->status === 'Ongoing')
                                        Current Assigned Event
                                    @else
                                        Next Assigned Event
                                    @endif
                                </p>


                                @if($currentEvent->status === 'Ongoing')

                                    <span
                                        class="
                                        inline-flex
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

                                        Ongoing

                                    </span>

                                @elseif($currentEvent->status === 'Upcoming')

                                    <span
                                        class="
                                        text-xs
                                        font-semibold
                                        text-[#101064]
                                        "
                                    >
                                        Upcoming
                                    </span>

                                @endif

                            </div>



                            <h2
                                class="
                                mt-3
                                text-3xl
                                font-bold
                                text-[#101064]
                                "
                            >
                                {{ $currentEvent->event_name }}
                            </h2>



                            <p
                                class="
                                mt-2
                                text-sm
                                text-gray-500
                                "
                            >
                                {{ $currentEvent->department->department_name ?? 'University-wide Event' }}
                            </p>



                            <div
                                class="
                                mt-6
                                flex
                                flex-wrap
                                gap-x-8
                                gap-y-4
                                "
                            >


                                <div>

                                    <p
                                        class="
                                        text-[11px]
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                        "
                                    >
                                        Date
                                    </p>

                                    <p
                                        class="
                                        mt-1
                                        font-semibold
                                        text-gray-700
                                        "
                                    >
                                        {{ \Carbon\Carbon::parse($currentEvent->event_date)->format('F d, Y') }}
                                    </p>

                                </div>



                                <div>

                                    <p
                                        class="
                                        text-[11px]
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                        "
                                    >
                                        Time
                                    </p>

                                    <p
                                        class="
                                        mt-1
                                        font-semibold
                                        text-gray-700
                                        "
                                    >
                                        {{ \Carbon\Carbon::parse($currentEvent->start_time)->format('g:i A') }}
                                        –
                                        {{ \Carbon\Carbon::parse($currentEvent->end_time)->format('g:i A') }}
                                    </p>

                                </div>



                                <div>

                                    <p
                                        class="
                                        text-[11px]
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                        "
                                    >
                                        Venue
                                    </p>

                                    <p
                                        class="
                                        mt-1
                                        font-semibold
                                        text-gray-700
                                        "
                                    >
                                        {{ $currentEvent->location }}
                                    </p>

                                </div>


                            </div>

                        </div>





                        <!-- ACTION -->

                        <div
                            class="
                            flex
                            shrink-0
                            flex-col
                            gap-5
                            lg:items-end
                            "
                        >


                            <div class="lg:text-right">

                                <p
                                    class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                    "
                                >
                                    Attendance Recorded
                                </p>

                                <p
                                    class="
                                    mt-1
                                    text-4xl
                                    font-bold
                                    text-[#101064]
                                    "
                                >
                                    {{ $currentEventScanned }}
                                </p>

                            </div>



                            <div class="flex flex-wrap gap-3">

                                <a
                                    href="{{ route('my-events.show', $currentEvent->event_id) }}"
                                    class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    rounded-xl
                                    border
                                    border-gray-300
                                    bg-white
                                    px-5
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-[#101064]
                                    transition
                                    hover:border-[#101064]
                                    "
                                >
                                    View Event
                                </a>


                                <a
                                    href="{{ route('attendance.index', $currentEvent->event_id) }}"
                                    class="
                                    inline-flex
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
                                            d="M13 10V3L4 14h7v7l9-11h-7z"
                                        />
                                    </svg>

                                    Start Attendance

                                </a>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </section>


    @else


        <section
            class="
            border
            border-gray-200
            bg-white
            px-8
            py-10
            text-center
            "
        >

            <div
                class="
                mx-auto
                h-1
                w-14
                bg-[#D4A017]
                "
            ></div>


            <h2
                class="
                mt-5
                text-xl
                font-bold
                text-[#101064]
                "
            >
                No Current Assigned Event
            </h2>


            <p
                class="
                mt-2
                text-sm
                text-gray-500
                "
            >
                You currently have no ongoing or upcoming assigned event.
            </p>


            <a
                href="{{ route('my-events.index') }}"
                class="
                mt-6
                inline-flex
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
                View My Assigned Events
            </a>

        </section>

    @endif





    <!-- ====================================================== -->
    <!-- ACTIVITY -->
    <!-- ====================================================== -->

    <section
        class="
        grid
        grid-cols-1
        gap-8
        xl:grid-cols-3
        "
    >


        <!-- RECENT SCANS -->

        <div
            class="
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
                items-end
                justify-between
                border-b
                border-gray-100
                px-7
                py-6
                "
            >

                <div>

                    <p
                        class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.25em]
                        text-[#D4A017]
                        "
                    >
                        Live Records
                    </p>

                    <h2
                        class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                        "
                    >
                        Recent RFID Scans
                    </h2>

                </div>


                <span
                    class="
                    text-xs
                    font-medium
                    text-gray-400
                    "
                >
                    Latest attendance activity
                </span>

            </div>



            @if($recentAttendance->count())


                <div class="divide-y divide-gray-100">


                    @foreach($recentAttendance as $record)

                        <div
                            class="
                            flex
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
                                    font-bold
                                    text-[#101064]
                                    "
                                >
                                    {{ strtoupper(substr($record->student->first_name ?? 'S', 0, 1)) }}
                                </div>



                                <div class="min-w-0">


                                    <p
                                        class="
                                        truncate
                                        font-semibold
                                        text-[#101064]
                                        "
                                    >
                                        {{ $record->student->first_name ?? '' }}
                                        {{ $record->student->last_name ?? 'Unknown Student' }}
                                    </p>


                                    <p
                                        class="
                                        mt-1
                                        text-sm
                                        text-gray-500
                                        "
                                    >
                                        {{ $record->student->student_number ?? 'No student number' }}

                                        @if($record->student && $record->student->program_code)
                                            <span class="mx-1 text-gray-300">•</span>
                                            {{ $record->student->program_code }}
                                        @endif
                                    </p>


                                    @if($record->event)

                                        <p
                                            class="
                                            mt-1
                                            truncate
                                            text-xs
                                            text-gray-400
                                            "
                                        >
                                            {{ $record->event->event_name }}
                                        </p>

                                    @endif


                                </div>

                            </div>




                            <div
                                class="
                                flex
                                shrink-0
                                items-center
                                gap-5
                                md:text-right
                                "
                            >


                                <div>

                                    <p
                                        class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                        "
                                    >
                                        {{ \Carbon\Carbon::parse($record->time_in)->format('h:i A') }}
                                    </p>

                                    <p
                                        class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                        "
                                    >
                                        {{ \Carbon\Carbon::parse($record->time_in)->format('M d, Y') }}
                                    </p>

                                </div>



                                @if($record->status === 'Late')

                                    <span
                                        class="
                                        text-xs
                                        font-semibold
                                        text-[#B7791F]
                                        "
                                    >
                                        Late
                                    </span>

                                @else

                                    <span
                                        class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        text-xs
                                        font-semibold
                                        text-green-600
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

                                @endif


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
                        No attendance scans yet
                    </h3>


                    <p
                        class="
                        mt-2
                        text-sm
                        text-gray-400
                        "
                    >
                        Student attendance records will appear here after scanning.
                    </p>

                </div>


            @endif


        </div>





        <!-- UPCOMING -->

        <div
            class="
            overflow-hidden
            border
            border-gray-200
            bg-white
            "
        >


            <div
                class="
                flex
                items-end
                justify-between
                border-b
                border-gray-100
                px-6
                py-6
                "
            >


                <div>

                    <p
                        class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.25em]
                        text-[#D4A017]
                        "
                    >
                        Schedule
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

                </div>


                <a
                    href="{{ route('my-events.index') }}"
                    class="
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




            @if($upcomingEvents->count())


                <div class="divide-y divide-gray-100">


                    @foreach($upcomingEvents as $event)

                        <a
                            href="{{ route('my-events.show', $event->event_id) }}"
                            class="
                            block
                            px-6
                            py-5
                            transition
                            hover:bg-gray-50
                            "
                        >


                            <div
                                class="
                                flex
                                items-start
                                gap-4
                                "
                            >


                                <div
                                    class="
                                    mt-1
                                    flex
                                    w-12
                                    shrink-0
                                    flex-col
                                    items-center
                                    border
                                    border-gray-200
                                    bg-gray-50
                                    py-2
                                    "
                                >

                                    <span
                                        class="
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-[#D4A017]
                                        "
                                    >
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M') }}
                                    </span>


                                    <span
                                        class="
                                        text-lg
                                        font-bold
                                        text-[#101064]
                                        "
                                    >
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('d') }}
                                    </span>

                                </div>




                                <div class="min-w-0">


                                    <h3
                                        class="
                                        truncate
                                        font-semibold
                                        text-[#101064]
                                        "
                                    >
                                        {{ $event->event_name }}
                                    </h3>


                                    <p
                                        class="
                                        mt-2
                                        text-sm
                                        text-gray-500
                                        "
                                    >
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                    </p>


                                    <p
                                        class="
                                        mt-1
                                        truncate
                                        text-sm
                                        text-gray-500
                                        "
                                    >
                                        {{ $event->location }}
                                    </p>


                                    <p
                                        class="
                                        mt-2
                                        truncate
                                        text-xs
                                        text-gray-400
                                        "
                                    >
                                        {{ $event->department->department_name ?? 'University-wide Event' }}
                                    </p>


                                </div>


                            </div>


                        </a>

                    @endforeach


                </div>


            @else


                <div class="px-6 py-12 text-center">

                    <div
                        class="
                        mx-auto
                        h-1
                        w-10
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
                        No upcoming events
                    </h3>

                    <p
                        class="
                        mt-2
                        text-sm
                        text-gray-400
                        "
                    >
                        New assignments will appear here.
                    </p>

                </div>


            @endif


        </div>


    </section>


</div>

</x-admin-layout>