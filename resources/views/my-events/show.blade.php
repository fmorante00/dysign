<x-admin-layout>

<div class="space-y-8">

    <!-- ========================================================= -->
    <!-- FLASH MESSAGE -->
    <!-- ========================================================= -->

    @if(session('error'))

        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>

    @endif



    <!-- ========================================================= -->
    <!-- HEADER -->
    <!-- ========================================================= -->

    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-[#101064]">
                {{ $event->event_name }}
            </h1>

            <p class="mt-2 text-gray-500">
                Event details and attendance operations.
            </p>

        </div>


        <a
            href="{{ route('my-events.index') }}"
            class="inline-flex w-fit items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-3 font-semibold text-gray-600 transition hover:bg-gray-50"
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
                    d="M15 19l-7-7 7-7"
                />
            </svg>

            Back to My Events

        </a>

    </div>



    <!-- ========================================================= -->
    <!-- STATUS CARD -->
    <!-- ========================================================= -->

    <div class="rounded-3xl bg-[#101064] p-8 text-white shadow-sm">

        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">

            <div>

                <p class="text-sm uppercase tracking-wide text-blue-200">
                    Attendance Status
                </p>


                <h2 class="mt-2 text-3xl font-bold">
                    {{ $event->status }}
                </h2>


                <p class="mt-4 max-w-2xl text-blue-100">

                    @if($event->status === 'Ongoing')

                        This event is currently ongoing. Attendance scanning is available.

                    @elseif($event->status === 'Upcoming')

                        This event is upcoming. Attendance scanning is available for preparation and testing.

                    @elseif($event->status === 'Completed')

                        This event has been completed. Attendance records are now view-only.

                    @elseif($event->status === 'Cancelled')

                        This event has been cancelled. Attendance scanning is unavailable.

                    @else

                        You are assigned as Attendance Personnel for this event.

                    @endif

                </p>

            </div>



            <!-- STATUS BADGE -->

            <div>

                @if($event->status === 'Ongoing')

                    <span class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                        Ongoing
                    </span>

                @elseif($event->status === 'Upcoming')

                    <span class="inline-flex rounded-full bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-700">
                        Upcoming
                    </span>

                @elseif($event->status === 'Completed')

                    <span class="inline-flex rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700">
                        Completed
                    </span>

                @elseif($event->status === 'Cancelled')

                    <span class="inline-flex rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-700">
                        Cancelled
                    </span>

                @else

                    <span class="inline-flex rounded-full bg-white/20 px-4 py-2 text-sm font-semibold text-white">
                        {{ $event->status }}
                    </span>

                @endif

            </div>

        </div>

    </div>



    <!-- ========================================================= -->
    <!-- SUMMARY -->
    <!-- ========================================================= -->

    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

        <!-- ASSIGNMENT -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <p class="text-sm text-gray-500">
                Assignment
            </p>

            <h3 class="mt-2 text-lg font-bold text-[#101064]">
                Attendance Personnel
            </h3>

            <p class="mt-1 text-sm text-gray-400">
                Assigned to your account
            </p>

        </div>


        <!-- ATTENDANCE COUNT -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <p class="text-sm text-gray-500">
                Attendance Recorded
            </p>

            <h3 class="mt-2 text-3xl font-bold text-[#101064]">
                {{ $attendanceCount }}
            </h3>

            <p class="mt-1 text-sm text-gray-400">

                {{ $attendanceCount === 1 ? 'Student recorded' : 'Students recorded' }}

            </p>

        </div>


        <!-- SCANNER -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <p class="text-sm text-gray-500">
                Scanner
            </p>


            @if($canScan)

                <h3 class="mt-2 text-lg font-bold text-green-600">
                    Available
                </h3>

                <p class="mt-1 text-sm text-gray-400">
                    RFID attendance can be recorded
                </p>

            @else

                <h3 class="mt-2 text-lg font-bold text-gray-600">
                    Closed
                </h3>

                <p class="mt-1 text-sm text-gray-400">
                    Attendance scanning is disabled
                </p>

            @endif

        </div>

    </div>



    <!-- ========================================================= -->
    <!-- EVENT INFORMATION -->
    <!-- ========================================================= -->

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">


        <!-- EVENT DETAILS -->

        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">

            <h2 class="text-xl font-semibold text-[#101064]">
                Event Information
            </h2>


            <div class="mt-6 space-y-6">


                <!-- DEPARTMENT -->

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Department
                    </p>

                    <p class="mt-1 font-semibold text-gray-700">
                        {{ $event->department->department_name ?? 'University-wide Event' }}
                    </p>

                </div>



                <!-- VENUE -->

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Venue
                    </p>

                    <p class="mt-1 font-semibold text-gray-700">
                        {{ $event->location }}
                    </p>

                </div>



                <!-- DESCRIPTION -->

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Description
                    </p>

                    <p class="mt-1 leading-relaxed text-gray-700">
                        {{ $event->description ?? 'No description provided.' }}
                    </p>

                </div>

            </div>

        </div>



        <!-- SCHEDULE -->

        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">

            <h2 class="text-xl font-semibold text-[#101064]">
                Schedule
            </h2>


            <div class="mt-6 space-y-6">


                <!-- DATE -->

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Date
                    </p>

                    <p class="mt-1 font-semibold text-gray-700">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                    </p>

                </div>



                <!-- START -->

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Start Time
                    </p>

                    <p class="mt-1 font-semibold text-gray-700">
                        {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                    </p>

                </div>



                <!-- END -->

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        End Time
                    </p>

                    <p class="mt-1 font-semibold text-gray-700">
                        {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                    </p>

                </div>

            </div>

        </div>

    </div>



    <!-- ========================================================= -->
    <!-- ATTENDANCE OPERATIONS -->
    <!-- ========================================================= -->

    <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">

        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">


            <div>

                <h2 class="text-xl font-semibold text-[#101064]">
                    Attendance Operations
                </h2>


                @if($canScan)

                    <p class="mt-2 text-gray-500">
                        Open the RFID scanner to record student attendance for this event.
                    </p>

                @elseif($event->status === 'Completed')

                    <p class="mt-2 text-gray-500">
                        This event has been completed. Existing attendance records are preserved.
                    </p>

                @elseif($event->status === 'Cancelled')

                    <p class="mt-2 text-gray-500">
                        This event was cancelled. Attendance scanning is disabled.
                    </p>

                @else

                    <p class="mt-2 text-gray-500">
                        Attendance scanning is currently unavailable.
                    </p>

                @endif

            </div>



            <!-- ACTION -->

            @if($canScan)

                <a
                    href="{{ route('attendance.index', $event->event_id) }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#101064] px-8 py-3 font-semibold text-white transition hover:bg-[#D4A017]"
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

                    Start Attendance

                </a>

            @else

                <button
                    type="button"
                    disabled
                    class="inline-flex shrink-0 cursor-not-allowed items-center justify-center rounded-xl bg-gray-200 px-8 py-3 font-semibold text-gray-500"
                >
                    Attendance Closed
                </button>

            @endif

        </div>

    </div>

</div>

</x-admin-layout>