<x-admin-layout>

@php

    $totalEvents = $events->count();

    $upcomingEvents = $events
        ->where('status', 'Upcoming')
        ->count();

    $ongoingEvents = $events
        ->where('status', 'Ongoing')
        ->count();

    $completedEvents = $events
        ->where('status', 'Completed')
        ->count();

    $cancelledEvents = $events
        ->where('status', 'Cancelled')
        ->count();

    $currentRole =
        auth()->user()->role->role_name ?? null;

@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .events-hero {

        background:
            linear-gradient(
                100deg,
                rgba(16, 16, 100, .97) 0%,
                rgba(16, 16, 100, .92) 52%,
                rgba(16, 16, 100, .74) 100%
            ),
            url('{{ asset('images/school.jpg') }}');

        background-size: cover;
        background-position: center;

    }


    /*
    |--------------------------------------------------------------------------
    | SECTION
    |--------------------------------------------------------------------------
    */

    .events-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }


    /*
    |--------------------------------------------------------------------------
    | SCROLLBAR
    |--------------------------------------------------------------------------
    */

    .events-scrollbar::-webkit-scrollbar {

        width: 6px;
        height: 6px;

    }


    .events-scrollbar::-webkit-scrollbar-track {

        background: #f3f4f6;

    }


    .events-scrollbar::-webkit-scrollbar-thumb {

        background: #d1d5db;
        border-radius: 9999px;

    }


    .events-scrollbar::-webkit-scrollbar-thumb:hover {

        background: #9ca3af;

    }

</style>



<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            events-hero
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

        {{-- GOLD ACCENT --}}

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
                min-w-0
                flex-col
                gap-8
                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >


            {{-- HERO CONTENT --}}

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
                    Event Administration
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
                    Event Management
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
                    Create, schedule, review, and manage
                    institutional events and their assigned
                    attendance personnel.
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


                    <div
                        class="
                            flex
                            items-center
                            gap-2
                        "
                    >

                        <svg
                            class="
                                h-4
                                w-4
                                text-[#E7C75B]
                            "
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 7V3m8 4V3M5 11h14M5 5h14v16H5z"
                            />
                        </svg>


                        <span>
                            {{ $totalEvents }}
                            event records
                        </span>

                    </div>



                    <div
                        class="
                            flex
                            items-center
                            gap-2
                        "
                    >

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-[#E7C75B]
                            "
                        ></span>


                        <span>
                            {{ $upcomingEvents }}
                            upcoming
                        </span>

                    </div>



                    <div
                        class="
                            flex
                            items-center
                            gap-2
                        "
                    >

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-green-400
                            "
                        ></span>


                        <span>
                            {{ $ongoingEvents }}
                            ongoing
                        </span>

                    </div>


                </div>

            </div>



            {{-- CREATE EVENT --}}

            <div class="shrink-0">

                <a
                    href="{{ route('events.create') }}"
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
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>

                    Create Event

                </a>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SUCCESS --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <section
            class="
                events-section
                overflow-hidden
                border
                border-green-200
                bg-white
            "
        >

            <div
                class="
                    flex
                    items-center
                    gap-4
                    px-6
                    py-5
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
                        rounded-full
                        bg-green-50
                        font-bold
                        text-green-600
                    "
                >
                    ✓
                </div>


                <div>

                    <p
                        class="
                            font-bold
                            text-[#101064]
                        "
                    >
                        Event Record Updated
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            text-gray-500
                        "
                    >
                        {{ session('success') }}
                    </p>

                </div>

            </div>

        </section>

    @endif



    {{-- ====================================================== --}}
    {{-- ERROR --}}
    {{-- ====================================================== --}}

    @if(session('error'))

        <section
            class="
                events-section
                overflow-hidden
                border
                border-red-200
                bg-white
            "
        >

            <div
                class="
                    flex
                    items-center
                    gap-4
                    px-6
                    py-5
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
                        rounded-full
                        bg-red-50
                        font-bold
                        text-red-600
                    "
                >
                    !
                </div>


                <p
                    class="
                        text-sm
                        font-semibold
                        text-red-600
                    "
                >
                    {{ session('error') }}
                </p>

            </div>

        </section>

    @endif



    {{-- ====================================================== --}}
    {{-- OVERVIEW --}}
    {{-- ====================================================== --}}

    <section class="min-w-0">


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


            <div
                class="
                    mt-2
                    flex
                    flex-col
                    gap-2
                    sm:flex-row
                    sm:items-end
                    sm:justify-between
                "
            >

                <h2
                    class="
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Event Record Summary
                </h2>


                <p
                    class="
                        text-sm
                        text-gray-400
                    "
                >
                    Current visible event records
                </p>

            </div>

        </div>



        <div
            class="
                grid
                min-w-0
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                sm:grid-cols-2
                xl:grid-cols-4
            "
        >


            {{-- TOTAL --}}

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
                        text-xs
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Total Events
                </p>


                <p
                    class="
                        mt-3
                        text-4xl
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ $totalEvents }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    Available event records
                </p>

            </div>



            {{-- UPCOMING --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    xl:border-b-0
                    xl:border-r
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


                <p
                    class="
                        mt-3
                        text-4xl
                        font-bold
                        text-[#D4A017]
                    "
                >
                    {{ $upcomingEvents }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    Scheduled events
                </p>

            </div>



            {{-- ONGOING --}}

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
                        text-xs
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Ongoing
                </p>


                <p
                    class="
                        mt-3
                        text-4xl
                        font-bold
                        text-green-600
                    "
                >
                    {{ $ongoingEvents }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    Currently active
                </p>

            </div>



            {{-- COMPLETED --}}

            <div
                class="
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
                    Completed
                </p>


                <p
                    class="
                        mt-3
                        text-4xl
                        font-bold
                        text-gray-600
                    "
                >
                    {{ $completedEvents }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    Finished events
                </p>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- EVENT DIRECTORY --}}
    {{-- ====================================================== --}}

    <section class="min-w-0">


        <div
            class="
                mb-4
                flex
                flex-col
                gap-4
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
                    Event Registry
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Event Directory
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Search and review institutional event records.
                </p>

            </div>



            <a
                href="{{ route('events.create') }}"
                class="
                    inline-flex
                    shrink-0
                    items-center
                    justify-center
                    gap-2
                    rounded-lg
                    border
                    border-[#D4A017]
                    bg-white
                    px-5
                    py-2.5
                    text-xs
                    font-semibold
                    text-[#8A6D00]
                    transition
                    hover:bg-[#FFF9E7]
                "
            >

                Create Event
                <span>→</span>

            </a>


        </div>



        <div
            class="
                events-section
                min-w-0
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >


            {{-- ====================================================== --}}
            {{-- FILTERS --}}
            {{-- ====================================================== --}}

            <form
                method="GET"
                action="{{ route('events.index') }}"
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-5
                "
            >


                <div
                    class="
                        grid
                        grid-cols-1
                        gap-3
                        md:grid-cols-2
                        xl:grid-cols-4
                    "
                >


                    {{-- SEARCH --}}

                    <div>

                        <label
                            class="
                                mb-2
                                block
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Search
                        </label>


                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Event name or location..."
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-2.5
                                text-sm
                                text-gray-700
                                placeholder:text-gray-400
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >

                    </div>



                    {{-- DEPARTMENT --}}

                    <div>

                        <label
                            class="
                                mb-2
                                block
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Department
                        </label>


                        @if($currentRole === 'Department Staff')

                            <div
                                class="
                                    flex
                                    h-[42px]
                                    items-center
                                    rounded-lg
                                    border
                                    border-gray-200
                                    bg-gray-50
                                    px-4
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >

                                {{
                                    auth()
                                        ->user()
                                        ->department
                                        ->department_name
                                    ?? 'No department assigned'
                                }}

                            </div>

                        @else

                            <select
                                name="department_id"
                                class="
                                    w-full
                                    rounded-lg
                                    border-gray-200
                                    px-4
                                    py-2.5
                                    text-sm
                                    text-gray-700
                                    focus:border-[#101064]
                                    focus:ring-[#101064]
                                "
                            >

                                <option value="">
                                    All Departments
                                </option>


                                @foreach($departments as $department)

                                    <option
                                        value="{{ $department->department_id }}"
                                        {{
                                            request('department_id')
                                            == $department->department_id
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        {{ $department->department_name }}
                                    </option>

                                @endforeach

                            </select>

                        @endif

                    </div>



                    {{-- STATUS --}}

                    <div>

                        <label
                            class="
                                mb-2
                                block
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Status
                        </label>


                        <select
                            name="status"
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-2.5
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >

                            <option value="">
                                All Statuses
                            </option>


                            <option
                                value="Upcoming"
                                {{ request('status') === 'Upcoming' ? 'selected' : '' }}
                            >
                                Upcoming
                            </option>


                            <option
                                value="Ongoing"
                                {{ request('status') === 'Ongoing' ? 'selected' : '' }}
                            >
                                Ongoing
                            </option>


                            <option
                                value="Completed"
                                {{ request('status') === 'Completed' ? 'selected' : '' }}
                            >
                                Completed
                            </option>


                            <option
                                value="Cancelled"
                                {{ request('status') === 'Cancelled' ? 'selected' : '' }}
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>



                    {{-- DATE --}}

                    <div>

                        <label
                            class="
                                mb-2
                                block
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Event Date
                        </label>


                        <input
                            type="date"
                            name="event_date"
                            value="{{ request('event_date') }}"
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-2.5
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >

                    </div>


                </div>



                {{-- FILTER ACTIONS --}}

                <div
                    class="
                        mt-4
                        flex
                        flex-wrap
                        justify-end
                        gap-2
                    "
                >


                    @if(
                        request('search')
                        || request('department_id')
                        || request('status')
                        || request('event_date')
                    )

                        <a
                            href="{{ route('events.index') }}"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                rounded-lg
                                border
                                border-gray-200
                                bg-white
                                px-5
                                py-2.5
                                text-xs
                                font-semibold
                                text-gray-500
                                transition
                                hover:bg-gray-50
                            "
                        >
                            Clear Filters
                        </a>

                    @endif



                    <button
                        type="submit"
                        class="
                            inline-flex
                            items-center
                            justify-center
                            gap-2
                            rounded-lg
                            bg-[#101064]
                            px-6
                            py-2.5
                            text-xs
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
                                d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                            />
                        </svg>

                        Search Events

                    </button>


                </div>


            </form>



            {{-- ====================================================== --}}
            {{-- TABLE --}}
            {{-- ====================================================== --}}

            <div
                class="
                    events-scrollbar
                    w-full
                    max-w-full
                    overflow-x-auto
                "
            >

                <table
                    class="
                        w-full
                        min-w-[1250px]
                        table-auto
                    "
                >


                    <thead
                        class="
                            border-b
                            border-gray-200
                            bg-gray-50
                        "
                    >

                        <tr>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Event
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Schedule
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Department
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Location
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Personnel
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Status
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-right
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Action
                            </th>


                        </tr>

                    </thead>



                    <tbody
                        class="
                            divide-y
                            divide-gray-100
                            bg-white
                        "
                    >


                        @forelse($events as $event)


                            <tr
                                class="
                                    transition
                                    hover:bg-gray-50
                                "
                            >


                                {{-- EVENT --}}

                                <td
                                    class="
                                        px-6
                                        py-5
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
                                                    d="M8 7V3m8 4V3M5 11h14M5 5h14v16H5z"
                                                />
                                            </svg>

                                        </div>


                                        <div class="min-w-0">

                                            <p
                                                class="
                                                    max-w-[280px]
                                                    truncate
                                                    font-semibold
                                                    text-[#101064]
                                                "
                                            >
                                                {{ $event->event_name }}
                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-gray-400
                                                "
                                            >
                                                Event #{{ $event->event_id }}
                                            </p>

                                        </div>

                                    </div>

                                </td>



                                {{-- SCHEDULE --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                    "
                                >

                                    <p
                                        class="
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        {{
                                            \Carbon\Carbon::parse(
                                                $event->event_date
                                            )->format('M d, Y')
                                        }}
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-400
                                        "
                                    >
                                        {{
                                            \Carbon\Carbon::parse(
                                                $event->start_time
                                            )->format('g:i A')
                                        }}

                                        —

                                        {{
                                            \Carbon\Carbon::parse(
                                                $event->end_time
                                            )->format('g:i A')
                                        }}
                                    </p>

                                </td>



                                {{-- DEPARTMENT --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                    "
                                >

                                    @if($event->department)

                                        <p
                                            class="
                                                max-w-[230px]
                                                break-words
                                                text-sm
                                                font-semibold
                                                text-gray-700
                                            "
                                        >
                                            {{
                                                $event
                                                    ->department
                                                    ->department_name
                                            }}
                                        </p>

                                    @else

                                        <span
                                            class="
                                                inline-flex
                                                rounded-full
                                                bg-[#F0F1F8]
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-[#101064]
                                            "
                                        >
                                            University-wide
                                        </span>

                                    @endif

                                </td>



                                {{-- LOCATION --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                    "
                                >

                                    <p
                                        class="
                                            max-w-[220px]
                                            break-words
                                            text-sm
                                            text-gray-600
                                        "
                                    >
                                        {{ $event->location }}
                                    </p>

                                </td>



                                {{-- ASSIGNED PERSONNEL --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                    "
                                >

                                    @if(($event->assignments_count ?? 0) > 0)

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-green-50
                                                px-3
                                                py-1
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


                                            {{
                                                $event->assignments_count
                                            }}

                                            {{
                                                $event->assignments_count == 1
                                                    ? 'Assigned'
                                                    : 'Assigned'
                                            }}

                                        </span>

                                    @else

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-gray-100
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-gray-500
                                            "
                                        >

                                            <span
                                                class="
                                                    h-1.5
                                                    w-1.5
                                                    rounded-full
                                                    bg-gray-400
                                                "
                                            ></span>

                                            None

                                        </span>

                                    @endif

                                </td>



                                {{-- STATUS --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                    "
                                >


                                    @if($event->status === 'Upcoming')

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-[#FFF9E7]
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-[#B68A0D]
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

                                            Upcoming

                                        </span>


                                    @elseif($event->status === 'Ongoing')

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-green-50
                                                px-3
                                                py-1
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

                                            Ongoing

                                        </span>


                                    @elseif($event->status === 'Completed')

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-gray-100
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-gray-600
                                            "
                                        >

                                            <span
                                                class="
                                                    h-1.5
                                                    w-1.5
                                                    rounded-full
                                                    bg-gray-500
                                                "
                                            ></span>

                                            Completed

                                        </span>


                                    @else

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-red-50
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-red-600
                                            "
                                        >

                                            <span
                                                class="
                                                    h-1.5
                                                    w-1.5
                                                    rounded-full
                                                    bg-red-500
                                                "
                                            ></span>

                                            Cancelled

                                        </span>

                                    @endif


                                </td>



                                {{-- ACTION --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                        text-right
                                    "
                                >

                                    <a
                                        href="{{ route(
                                            'events.show',
                                            $event->event_id
                                        ) }}"
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            text-xs
                                            font-semibold
                                            text-[#101064]
                                            transition
                                            hover:text-[#D4A017]
                                        "
                                    >

                                        View Event
                                        <span>→</span>

                                    </a>

                                </td>


                            </tr>


                        @empty


                            <tr>

                                <td
                                    colspan="7"
                                    class="
                                        px-6
                                        py-16
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
                                        No event records found
                                    </h3>


                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            text-gray-400
                                        "
                                    >
                                        Try changing the filters or
                                        create a new event.
                                    </p>


                                    <a
                                        href="{{ route('events.create') }}"
                                        class="
                                            mt-5
                                            inline-flex
                                            rounded-lg
                                            bg-[#101064]
                                            px-5
                                            py-2.5
                                            text-xs
                                            font-semibold
                                            text-white
                                            transition
                                            hover:bg-[#D4A017]
                                            hover:text-[#101064]
                                        "
                                    >
                                        Create Event
                                    </a>

                                </td>

                            </tr>


                        @endforelse


                    </tbody>

                </table>

            </div>



            {{-- FOOTER --}}

            <div
                class="
                    flex
                    flex-col
                    gap-2
                    border-t
                    border-gray-100
                    bg-gray-50/60
                    px-6
                    py-4
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                "
            >

                <p
                    class="
                        text-xs
                        text-gray-400
                    "
                >
                    Cancelled events remain in the system for
                    record and attendance history.
                </p>


                <p
                    class="
                        text-xs
                        font-semibold
                        text-[#101064]
                    "
                >
                    Showing {{ $events->count() }}
                    {{ $events->count() == 1 ? 'event' : 'events' }}
                </p>

            </div>


        </div>

    </section>


</div>

</x-admin-layout>