<x-admin-layout>

<div class="space-y-8">


    <!-- HEADER -->

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-[#101064]">
                Good day, {{ auth()->user()->name }}
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                View your assigned events and manage attendance operations.
            </p>

        </div>


        <a
            href="{{ route('my-events.index') }}"
            class="inline-flex items-center justify-center rounded-xl bg-[#101064] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#0c0c4f]"
        >
            View My Assigned Events
        </a>

    </div>





    <!-- SUMMARY CARDS -->

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">


        <!-- TOTAL ASSIGNED EVENTS -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Assigned Events
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-[#101064]">
                        {{ $totalAssignedEvents }}
                    </h2>

                    <p class="mt-1 text-xs text-gray-400">
                        Total events assigned to you
                    </p>

                </div>


                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#101064] text-white">

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
                            d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"
                        />
                    </svg>

                </div>

            </div>

        </div>




        <!-- UPCOMING EVENTS -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Upcoming
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-[#D4A017]">
                        {{ $upcomingEventsCount }}
                    </h2>

                    <p class="mt-1 text-xs text-gray-400">
                        Upcoming assigned events
                    </p>

                </div>


                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-50 text-[#D4A017]">

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
                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                </div>

            </div>

        </div>




        <!-- TODAY SCANNED -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Scanned Today
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-green-600">
                        {{ $todayScanned }}
                    </h2>

                    <p class="mt-1 text-xs text-gray-400">
                        Attendance recorded today
                    </p>

                </div>


                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">

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
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                </div>

            </div>

        </div>




        <!-- TOTAL SCANS -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total Scans
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-[#101064]">
                        {{ $totalScanned }}
                    </h2>

                    <p class="mt-1 text-xs text-gray-400">
                        Across your assigned events
                    </p>

                </div>


                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-[#101064]">

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
                            d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                    </svg>

                </div>

            </div>

        </div>

    </div>





    <!-- CURRENT / NEXT EVENT -->

    @if($currentEvent)

        <div class="overflow-hidden rounded-3xl bg-[#101064] text-white shadow-sm">

            <div class="p-8">

                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">


                    <div>

                        <p class="text-xs font-semibold uppercase tracking-widest text-blue-200">

                            @if($currentEvent->status === 'Ongoing')
                                Current Assigned Event
                            @else
                                Next Assigned Event
                            @endif

                        </p>


                        <h2 class="mt-3 text-3xl font-bold">
                            {{ $currentEvent->event_name }}
                        </h2>


                        <div class="mt-5 flex flex-wrap gap-x-6 gap-y-3 text-sm text-blue-100">

                            <span>
                                {{ \Carbon\Carbon::parse($currentEvent->event_date)->format('F d, Y') }}
                            </span>


                            <span>
                                {{ \Carbon\Carbon::parse($currentEvent->start_time)->format('g:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($currentEvent->end_time)->format('g:i A') }}
                            </span>


                            <span>
                                {{ $currentEvent->location }}
                            </span>


                            <span>
                                {{ $currentEvent->department->department_name ?? 'University-wide Event' }}
                            </span>

                        </div>

                    </div>




                    <div class="flex flex-col items-start gap-4 lg:items-end">

                        @if($currentEvent->status === 'Ongoing')

                            <span class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                                Ongoing
                            </span>

                        @elseif($currentEvent->status === 'Upcoming')

                            <span class="rounded-full bg-[#D4A017] px-4 py-2 text-sm font-semibold text-[#101064]">
                                Upcoming
                            </span>

                        @endif


                        <div class="text-left lg:text-right">

                            <p class="text-xs uppercase tracking-wider text-blue-200">
                                Attendance Recorded
                            </p>

                            <p class="mt-1 text-3xl font-bold">
                                {{ $currentEventScanned }}
                            </p>

                        </div>

                    </div>

                </div>



                <div class="mt-8 flex flex-wrap gap-3">

                    <a
                        href="{{ route('my-events.show', $currentEvent->event_id) }}"
                        class="inline-flex items-center rounded-xl border border-white/20 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                    >
                        View Event
                    </a>


                    <a
                        href="{{ route('attendance.index', $currentEvent->event_id) }}"
                        class="inline-flex items-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-[#101064] transition hover:bg-gray-100"
                    >
                        Start Attendance
                    </a>

                </div>

            </div>

        </div>


    @else

        <div class="rounded-3xl border border-gray-100 bg-white p-10 text-center shadow-sm">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                <svg
                    class="h-7 w-7"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"
                    />
                </svg>

            </div>


            <h2 class="mt-4 text-xl font-semibold text-[#101064]">
                No current assigned event
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                You currently have no ongoing or upcoming assigned event.
            </p>


            <a
                href="{{ route('my-events.index') }}"
                class="mt-6 inline-flex rounded-xl bg-[#101064] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#0c0c4f]"
            >
                View My Assigned Events
            </a>

        </div>

    @endif





    <!-- MAIN GRID -->

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">


        <!-- RECENT RFID SCANS -->

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm xl:col-span-2">


            <div class="border-b border-gray-100 px-6 py-5">

                <h2 class="text-xl font-semibold text-[#101064]">
                    Recent RFID Scans
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Latest attendance records from your assigned events.
                </p>

            </div>



            @if($recentAttendance->count())


                <div class="divide-y divide-gray-100">


                    @foreach($recentAttendance as $record)

                        <div class="flex flex-col gap-4 px-6 py-5 transition hover:bg-gray-50 md:flex-row md:items-center md:justify-between">


                            <div class="flex items-center gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#101064] font-semibold text-white">

                                    {{ strtoupper(substr($record->student->first_name ?? 'S', 0, 1)) }}

                                </div>


                                <div>

                                    <p class="font-semibold text-[#101064]">

                                        {{ $record->student->first_name ?? '' }}
                                        {{ $record->student->last_name ?? 'Unknown Student' }}

                                    </p>


                                    <p class="mt-1 text-sm text-gray-500">

                                        {{ $record->student->student_number ?? 'No student number' }}

                                        @if($record->student && $record->student->program_code)
                                            • {{ $record->student->program_code }}
                                        @endif

                                    </p>


                                    @if($record->event)

                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ $record->event->event_name }}
                                        </p>

                                    @endif

                                </div>

                            </div>



                            <div class="flex items-center gap-4 md:text-right">

                                <div>

                                    <p class="text-sm font-medium text-gray-700">

                                        {{ \Carbon\Carbon::parse($record->time_in)->format('h:i A') }}

                                    </p>


                                    <p class="mt-1 text-xs text-gray-400">

                                        {{ \Carbon\Carbon::parse($record->time_in)->format('M d, Y') }}

                                    </p>

                                </div>


                                @if($record->status === 'Late')

                                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        Late
                                    </span>

                                @else

                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Present
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endforeach


                </div>


            @else


                <div class="px-6 py-12 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">

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
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>

                    <h3 class="mt-4 font-semibold text-gray-700">
                        No attendance scans yet
                    </h3>

                    <p class="mt-1 text-sm text-gray-400">
                        Students you scan during assigned events will appear here.
                    </p>

                </div>


            @endif


        </div>





        <!-- UPCOMING ASSIGNED EVENTS -->

        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">


            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                <div>

                    <h2 class="text-xl font-semibold text-[#101064]">
                        Upcoming Events
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Your next assignments.
                    </p>

                </div>


                <a
                    href="{{ route('my-events.index') }}"
                    class="text-sm font-semibold text-[#D4A017] hover:underline"
                >
                    View All
                </a>

            </div>



            @if($upcomingEvents->count())


                <div class="divide-y divide-gray-100">


                    @foreach($upcomingEvents as $event)

                        <a
                            href="{{ route('my-events.show', $event->event_id) }}"
                            class="block px-6 py-5 transition hover:bg-gray-50"
                        >

                            <div class="flex items-start justify-between gap-4">

                                <div class="min-w-0">

                                    <h3 class="truncate font-semibold text-[#101064]">
                                        {{ $event->event_name }}
                                    </h3>


                                    <p class="mt-2 text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                    </p>


                                    <p class="mt-1 text-sm text-gray-500">

                                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}

                                        • {{ $event->location }}

                                    </p>


                                    <p class="mt-2 text-xs text-gray-400">
                                        {{ $event->department->department_name ?? 'University-wide Event' }}
                                    </p>

                                </div>


                                <span class="shrink-0 rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                    Upcoming
                                </span>

                            </div>

                        </a>

                    @endforeach


                </div>


            @else


                <div class="px-6 py-12 text-center">

                    <h3 class="font-semibold text-gray-700">
                        No upcoming events
                    </h3>

                    <p class="mt-2 text-sm text-gray-400">
                        New event assignments will appear here.
                    </p>

                </div>


            @endif


        </div>


    </div>





    <!-- QUICK ACTIONS -->

    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

        <h2 class="text-xl font-semibold text-[#101064]">
            Quick Actions
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Common attendance tasks.
        </p>


        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">


            <a
                href="{{ route('my-events.index') }}"
                class="flex items-center gap-4 rounded-xl border border-gray-100 p-5 transition hover:border-[#101064]/20 hover:bg-gray-50"
            >

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#101064] text-white">

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
                            d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"
                        />
                    </svg>

                </div>


                <div>

                    <p class="font-semibold text-[#101064]">
                        My Assigned Events
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        View all events assigned to you.
                    </p>

                </div>

            </a>



            @if($currentEvent)

                <a
                    href="{{ route('attendance.index', $currentEvent->event_id) }}"
                    class="flex items-center gap-4 rounded-xl border border-gray-100 p-5 transition hover:border-[#D4A017]/30 hover:bg-gray-50"
                >

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-yellow-50 text-[#D4A017]">

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

                    </div>


                    <div>

                        <p class="font-semibold text-[#101064]">
                            Start Attendance
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Open the RFID scanner for your current event.
                        </p>

                    </div>

                </a>

            @else

                <div class="flex items-center gap-4 rounded-xl border border-gray-100 bg-gray-50 p-5">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gray-100 text-gray-400">

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

                    </div>


                    <div>

                        <p class="font-semibold text-gray-500">
                            Start Attendance
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            No current or upcoming event available.
                        </p>

                    </div>

                </div>

            @endif


        </div>

    </div>


</div>

</x-admin-layout>