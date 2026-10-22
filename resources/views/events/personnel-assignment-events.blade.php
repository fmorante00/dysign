<x-admin-layout>

@php

    $events =
        $events
        ?? collect();

    $totalEvents =
        $events->count();

    $assignedEvents =
        $events
            ->filter(
                fn ($event) =>
                    ($event->assignments_count ?? 0) > 0
            )
            ->count();

    $unassignedEvents =
        max(
            $totalEvents - $assignedEvents,
            0
        );

@endphp


<style>
    .personnel-events-hero {
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
            personnel-events-hero
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
                gap-7
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
                    Personnel Assignment Management
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
                    Available Events
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
                    Select an upcoming event to assign Attendance Personnel
                    and review scheduling availability.
                </p>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SUMMARY --}}
    {{-- ====================================================== --}}

    <section
        class="
            grid
            grid-cols-1
            overflow-hidden
            border
            border-gray-200
            bg-white
            sm:grid-cols-3
        "
    >

        <div
            class="
                border-b
                border-gray-100
                p-5
                sm:border-b-0
                sm:border-r
            "
        >

            <p
                class="
                    text-[10px]
                    font-bold
                    uppercase
                    tracking-wider
                    text-gray-400
                "
            >
                Upcoming Events
            </p>


            <p
                class="
                    mt-2
                    text-2xl
                    font-bold
                    text-[#101064]
                "
            >
                {{ $totalEvents }}
            </p>

        </div>


        <div
            class="
                border-b
                border-gray-100
                p-5
                sm:border-b-0
                sm:border-r
            "
        >

            <p
                class="
                    text-[10px]
                    font-bold
                    uppercase
                    tracking-wider
                    text-gray-400
                "
            >
                With Personnel
            </p>


            <p
                class="
                    mt-2
                    text-2xl
                    font-bold
                    text-green-600
                "
            >
                {{ $assignedEvents }}
            </p>

        </div>


        <div class="p-5">

            <p
                class="
                    text-[10px]
                    font-bold
                    uppercase
                    tracking-wider
                    text-gray-400
                "
            >
                Needs Assignment
            </p>


            <p
                class="
                    mt-2
                    text-2xl
                    font-bold
                    text-[#D4A017]
                "
            >
                {{ $unassignedEvents }}
            </p>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- EVENT LIST --}}
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
                Upcoming Events
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Select Event
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Personnel assignments can only be managed while an event
                is still upcoming.
            </p>

        </div>


        <div class="space-y-4">

            @forelse($events as $event)

                @php

                    $assignmentCount =
                        (int)
                        ($event->assignments_count ?? 0);

                @endphp


                <article
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
                            lg:grid-cols-[1fr_230px]
                        "
                    >


                        {{-- EVENT INFORMATION --}}

                        <div class="p-6">

                            <div
                                class="
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-3
                                "
                            >

                                <span
                                    class="
                                        rounded-full
                                        bg-blue-50
                                        px-3
                                        py-1
                                        text-xs
                                        font-bold
                                        text-[#101064]
                                    "
                                >
                                    Upcoming
                                </span>


                                @if($assignmentCount > 0)

                                    <span
                                        class="
                                            rounded-full
                                            bg-green-50
                                            px-3
                                            py-1
                                            text-xs
                                            font-bold
                                            text-green-700
                                        "
                                    >
                                        Personnel Assigned
                                    </span>

                                @else

                                    <span
                                        class="
                                            rounded-full
                                            bg-amber-50
                                            px-3
                                            py-1
                                            text-xs
                                            font-bold
                                            text-amber-700
                                        "
                                    >
                                        Needs Assignment
                                    </span>

                                @endif

                            </div>


                            <h3
                                class="
                                    mt-4
                                    text-xl
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                {{ $event->event_name }}
                            </h3>


                            @if($event->description)

                                <p
                                    class="
                                        mt-2
                                        max-w-3xl
                                        text-sm
                                        leading-6
                                        text-gray-500
                                    "
                                >
                                    {{
                                        \Illuminate\Support\Str::limit(
                                            $event->description,
                                            150
                                        )
                                    }}
                                </p>

                            @endif


                            <div
                                class="
                                    mt-5
                                    grid
                                    grid-cols-1
                                    gap-4
                                    sm:grid-cols-2
                                    xl:grid-cols-4
                                "
                            >


                                {{-- DATE --}}

                                <div>

                                    <p
                                        class="
                                            text-[10px]
                                            font-bold
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
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        {{
                                            \Carbon\Carbon::parse(
                                                $event->event_date
                                            )->format(
                                                'F d, Y'
                                            )
                                        }}
                                    </p>

                                </div>



                                {{-- TIME --}}

                                <div>

                                    <p
                                        class="
                                            text-[10px]
                                            font-bold
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
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >

                                        {{
                                            \Carbon\Carbon::parse(
                                                $event->start_time
                                            )->format(
                                                'g:i A'
                                            )
                                        }}

                                        –

                                        {{
                                            \Carbon\Carbon::parse(
                                                $event->end_time
                                            )->format(
                                                'g:i A'
                                            )
                                        }}

                                    </p>

                                </div>



                                {{-- LOCATION --}}

                                <div>

                                    <p
                                        class="
                                            text-[10px]
                                            font-bold
                                            uppercase
                                            tracking-wider
                                            text-gray-400
                                        "
                                    >
                                        Location
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        {{
                                            $event->location
                                            ?? 'Not specified'
                                        }}
                                    </p>

                                </div>



                                {{-- PERSONNEL COUNT --}}

                                <div>

                                    <p
                                        class="
                                            text-[10px]
                                            font-bold
                                            uppercase
                                            tracking-wider
                                            text-gray-400
                                        "
                                    >
                                        Assigned Personnel
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        {{ $assignmentCount }}
                                    </p>

                                </div>

                            </div>

                        </div>



                        {{-- ACTION --}}

                        <div
                            class="
                                flex
                                items-center
                                border-t
                                border-gray-100
                                bg-gray-50
                                p-6
                                lg:border-l
                                lg:border-t-0
                            "
                        >

                            <a
                                href="{{
                                    route(
                                        'events.personnel-assignment.index',
                                        $event->event_id
                                    )
                                }}"
                                class="
                                    inline-flex
                                    w-full
                                    items-center
                                    justify-center
                                    rounded-lg
                                    bg-[#101064]
                                    px-5
                                    py-3
                                    text-sm
                                    font-bold
                                    text-white
                                    transition
                                    hover:bg-[#D4A017]
                                    hover:text-[#101064]
                                "
                            >

                                Manage Personnel

                                <svg
                                    class="ml-2 h-4 w-4"
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

                </article>


            @empty

                <div
                    class="
                        border
                        border-dashed
                        border-gray-300
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
                            h-12
                            w-12
                            items-center
                            justify-center
                            rounded-full
                            bg-gray-100
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
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 7V3m8 4V3M5 11h14M5 7h14v14H5z"
                            />
                        </svg>

                    </div>


                    <h3
                        class="
                            mt-4
                            font-bold
                            text-[#101064]
                        "
                    >
                        No upcoming events available
                    </h3>


                    <p
                        class="
                            mt-2
                            text-sm
                            text-gray-500
                        "
                    >
                        Upcoming events from your department will appear here.
                    </p>

                </div>

            @endforelse

        </div>

    </section>


</div>

</x-admin-layout>