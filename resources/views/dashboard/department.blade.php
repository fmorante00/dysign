<x-admin-layout>

    <div class="space-y-8">

        <!-- HEADER -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <h1 class="text-3xl font-bold text-[#101064]">
                    Welcome back, {{ auth()->user()->name }}
                </h1>

                <p class="mt-2 text-gray-500">
                    Manage and monitor events for your department.
                </p>

            </div>

            <div class="rounded-xl bg-[#101064] px-5 py-3 text-white shadow-sm">

                <p class="text-xs uppercase tracking-wider text-blue-200">
                    Department
                </p>

                <p class="mt-1 font-semibold">
                    {{ $departmentName }}
                </p>

            </div>

        </div>


        <!-- SUMMARY CARDS -->
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">

            <!-- TOTAL EVENTS -->
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Total Events
                        </p>

                        <h2 class="mt-2 text-3xl font-bold text-[#101064]">
                            {{ $totalEvents }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Department events
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#101064] text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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


            <!-- UPCOMING -->
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Upcoming
                        </p>

                        <h2 class="mt-2 text-3xl font-bold text-[#D4A017]">
                            {{ $upcomingEventsCount }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Scheduled events
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-yellow-50 text-[#D4A017]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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


            <!-- ONGOING -->
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Ongoing
                        </p>

                        <h2 class="mt-2 text-3xl font-bold text-green-600">
                            {{ $ongoingEventsCount }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Currently ongoing
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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


            <!-- COMPLETED -->
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Completed
                        </p>

                        <h2 class="mt-2 text-3xl font-bold text-gray-600">
                            {{ $completedEventsCount }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Finished events
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        </div>


        <!-- MAIN GRID -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <!-- UPCOMING EVENTS -->
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm lg:col-span-2">

                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                    <div>

                        <h2 class="text-xl font-semibold text-[#101064]">
                            Upcoming Events
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Your department's next scheduled activities.
                        </p>

                    </div>

                    <a
                        href="{{ route('events.index') }}"
                        class="text-sm font-semibold text-[#101064] hover:text-[#D4A017]"
                    >
                        View All
                    </a>

                </div>


                @if($upcomingEvents->count())

                    <div class="divide-y divide-gray-100">

                        @foreach($upcomingEvents as $event)

                            <a
                                href="{{ route('events.show', $event->event_id) }}"
                                class="block px-6 py-5 transition hover:bg-gray-50"
                            >

                                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                                    <div class="min-w-0">

                                        <h3 class="truncate font-semibold text-[#101064]">
                                            {{ $event->event_name }}
                                        </h3>

                                        <div class="mt-2 flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500">

                                            <span>
                                                {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                                            </span>

                                            <span>
                                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                                -
                                                {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                            </span>

                                            <span>
                                                {{ $event->location }}
                                            </span>

                                        </div>

                                    </div>


                                    <span class="inline-flex w-fit rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        {{ $event->status }}
                                    </span>

                                </div>

                            </a>

                        @endforeach

                    </div>

                @else

                    <div class="px-6 py-12 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"
                                />
                            </svg>

                        </div>

                        <h3 class="mt-4 font-semibold text-gray-700">
                            No upcoming events
                        </h3>

                        <p class="mt-1 text-sm text-gray-400">
                            Your department does not have any upcoming events yet.
                        </p>

                        <a
                            href="{{ route('events.create') }}"
                            class="mt-5 inline-flex rounded-xl bg-[#101064] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0c0c4f]"
                        >
                            Create Event
                        </a>

                    </div>

                @endif

            </div>


            <!-- QUICK ACTIONS -->
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

                <h2 class="text-xl font-semibold text-[#101064]">
                    Quick Actions
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Common department event tasks.
                </p>


                <div class="mt-6 space-y-3">

                    <a
                        href="{{ route('events.create') }}"
                        class="flex items-center gap-4 rounded-xl border border-gray-100 p-4 transition hover:bg-gray-50"
                    >

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#101064] text-white">
                            <span class="text-xl">+</span>
                        </div>

                        <div>
                            <p class="font-semibold text-[#101064]">
                                Create Event
                            </p>

                            <p class="text-xs text-gray-400">
                                Add a new department event
                            </p>
                        </div>

                    </a>


                    <a
                        href="{{ route('events.index') }}"
                        class="flex items-center gap-4 rounded-xl border border-gray-100 p-4 transition hover:bg-gray-50"
                    >

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-50 text-[#D4A017]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>
                        </div>

                        <div>
                            <p class="font-semibold text-[#101064]">
                                Event Directory
                            </p>

                            <p class="text-xs text-gray-400">
                                View and manage department events
                            </p>
                        </div>

                    </a>

                </div>

            </div>

        </div>


        <!-- RECENT EVENTS -->
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-6 py-5">

                <h2 class="text-xl font-semibold text-[#101064]">
                    Recent Events
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Recently created or updated events in your department.
                </p>

            </div>


            @if($recentEvents->count())

                <div class="overflow-x-auto">

                    <table class="w-full text-left">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Event
                                </th>

                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Date
                                </th>

                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Location
                                </th>

                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach($recentEvents as $event)

                                <tr class="transition hover:bg-gray-50">

                                    <td class="px-6 py-4">

                                        <a
                                            href="{{ route('events.show', $event->event_id) }}"
                                            class="font-semibold text-[#101064] hover:text-[#D4A017]"
                                        >
                                            {{ $event->event_name }}
                                        </a>

                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}

                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        {{ $event->location }}

                                    </td>

                                    <td class="px-6 py-4">

                                        @if($event->status === 'Upcoming')

                                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                                Upcoming
                                            </span>

                                        @elseif($event->status === 'Ongoing')

                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Ongoing
                                            </span>

                                        @elseif($event->status === 'Completed')

                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                Completed
                                            </span>

                                        @else

                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                Cancelled
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="px-6 py-12 text-center">

                    <h3 class="font-semibold text-gray-700">
                        No events yet
                    </h3>

                    <p class="mt-1 text-sm text-gray-400">
                        Events created for your department will appear here.
                    </p>

                </div>

            @endif

        </div>

    </div>

</x-admin-layout>