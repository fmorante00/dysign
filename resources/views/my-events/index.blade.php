<x-admin-layout>

<div class="space-y-8">

    <!-- HEADER -->

    <div>

        <h1 class="text-3xl font-bold text-[#101064]">
            My Assigned Events
        </h1>

        <p class="mt-2 text-gray-500">
            View your assigned events and manage attendance operations.
        </p>

    </div>



    <!-- SUMMARY -->

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

        <!-- ONGOING -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <p class="text-sm text-gray-500">
                Ongoing
            </p>

            <h2 class="mt-2 text-3xl font-bold text-green-600">
                {{ $events->where('status', 'Ongoing')->count() }}
            </h2>

        </div>


        <!-- UPCOMING -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <p class="text-sm text-gray-500">
                Upcoming
            </p>

            <h2 class="mt-2 text-3xl font-bold text-[#101064]">
                {{ $events->where('status', 'Upcoming')->count() }}
            </h2>

        </div>


        <!-- COMPLETED -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <p class="text-sm text-gray-500">
                Completed
            </p>

            <h2 class="mt-2 text-3xl font-bold text-gray-600">
                {{ $events->where('status', 'Completed')->count() }}
            </h2>

        </div>


        <!-- CANCELLED -->

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

            <p class="text-sm text-gray-500">
                Cancelled
            </p>

            <h2 class="mt-2 text-3xl font-bold text-red-600">
                {{ $events->where('status', 'Cancelled')->count() }}
            </h2>

        </div>

    </div>



    <!-- EVENT LIST -->

    <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">

        <div class="mb-6 flex items-center justify-between">

            <div>

                <h2 class="text-xl font-semibold text-[#101064]">
                    Assigned Event List
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Events currently assigned to your account.
                </p>

            </div>


            <span class="rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600">

                {{ $events->count() }}
                {{ $events->count() === 1 ? 'Event' : 'Events' }}

            </span>

        </div>



        @if($events->count() > 0)

            <div class="space-y-5">

                @foreach($events as $event)

                    <div
                        class="rounded-2xl border border-gray-200 p-6 transition hover:border-gray-300 hover:shadow-md"
                    >

                        <!-- TOP -->

                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">


                            <div>

                                <h3 class="text-xl font-semibold text-[#101064]">
                                    {{ $event->event_name }}
                                </h3>


                                <p class="mt-1 text-sm text-gray-500">

                                    {{ $event->department->department_name ?? 'University-wide Event' }}

                                </p>

                            </div>



                            <!-- STATUS -->

                            <div>

                                @if($event->status === 'Ongoing')

                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Ongoing
                                    </span>

                                @elseif($event->status === 'Upcoming')

                                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        Upcoming
                                    </span>

                                @elseif($event->status === 'Completed')

                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                        Completed
                                    </span>

                                @elseif($event->status === 'Cancelled')

                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Cancelled
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                        {{ $event->status }}
                                    </span>

                                @endif

                            </div>

                        </div>



                        <!-- INFORMATION -->

                        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">


                            <!-- DATE -->

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Date
                                </p>

                                <p class="mt-1 font-semibold text-gray-700">

                                    {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}

                                </p>

                            </div>



                            <!-- TIME -->

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Time
                                </p>

                                <p class="mt-1 font-semibold text-gray-700">

                                    {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}

                                    -

                                    {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}

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



                            <!-- ATTENDANCE -->

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Attendance Recorded
                                </p>

                                <p class="mt-1 font-semibold text-[#101064]">

                                    {{ $event->attendance_count }}

                                    {{ $event->attendance_count === 1 ? 'Student' : 'Students' }}

                                </p>

                            </div>

                        </div>



                        <!-- ACTIONS -->

                        <div class="mt-6 flex flex-col gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">


                            <!-- SCANNER AVAILABILITY -->

                            <div>

                                @if($event->can_scan)

                                    <p class="text-sm text-green-600">
                                        Attendance scanning available
                                    </p>

                                @elseif($event->status === 'Completed')

                                    <p class="text-sm text-gray-500">
                                        Attendance scanning has ended
                                    </p>

                                @elseif($event->status === 'Cancelled')

                                    <p class="text-sm text-red-500">
                                        This event was cancelled
                                    </p>

                                @endif

                            </div>



                            <div class="flex items-center gap-3">

                                <a
                                    href="{{ route('my-events.show', $event->event_id) }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-[#101064] px-6 py-3 font-semibold text-white transition hover:bg-[#D4A017]"
                                >
                                    Open Event
                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <!-- EMPTY STATE -->

            <div class="py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">

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
                            d="M8 7V3m8 4V3M5 11h14M5 7h14v14H5z"
                        />
                    </svg>

                </div>


                <h3 class="mt-4 font-semibold text-gray-700">
                    No assigned events
                </h3>


                <p class="mt-2 text-sm text-gray-500">
                    Events assigned to you will appear here.
                </p>

            </div>

        @endif

    </div>

</div>

</x-admin-layout>