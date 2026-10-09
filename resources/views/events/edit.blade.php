<x-admin-layout>

@php

    $currentUser = auth()->user();

    $currentRole =
        $currentUser->role->role_name ?? null;


    $statusConfig = match($event->status) {

        'Upcoming' => [
            'class' => 'bg-[#FFF9E7] text-[#B68A0D]',
            'dot' => 'bg-[#D4A017]',
        ],

        'Ongoing' => [
            'class' => 'bg-green-50 text-green-700',
            'dot' => 'bg-green-500',
        ],

        'Completed' => [
            'class' => 'bg-gray-100 text-gray-600',
            'dot' => 'bg-gray-500',
        ],

        'Cancelled' => [
            'class' => 'bg-red-50 text-red-600',
            'dot' => 'bg-red-500',
        ],

        default => [
            'class' => 'bg-gray-100 text-gray-600',
            'dot' => 'bg-gray-400',
        ],

    };

@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .event-edit-hero {

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

    .event-edit-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }

</style>



<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            event-edit-hero
            relative
            overflow-hidden
            rounded-[28px]
            px-7
            py-8
            text-white
            sm:px-8
            lg:px-10
            lg:py-9
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
                min-w-0
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
                    Edit Event
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
                    Update the official event record, schedule,
                    location, department, and lifecycle status.
                </p>



                <div
                    class="
                        mt-6
                        flex
                        flex-wrap
                        items-center
                        gap-x-3
                        gap-y-2
                        text-sm
                        text-white/70
                    "
                >

                    <span>
                        {{ $event->event_name }}
                    </span>


                    <span class="text-white/30">
                        •
                    </span>


                    <span>
                        Event #{{ $event->event_id }}
                    </span>


                    <span class="text-white/30">
                        •
                    </span>


                    <span
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
                                {{ $statusConfig['dot'] }}
                            "
                        ></span>

                        {{ $event->status }}

                    </span>

                </div>

            </div>



            <div class="shrink-0">

                <a
                    href="{{ route(
                        'events.show',
                        $event->event_id
                    ) }}"
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
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    Back to Event

                </a>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ====================================================== --}}

    @if($errors->any())

        <section
            class="
                event-edit-section
                overflow-hidden
                border
                border-red-200
                bg-white
            "
        >

            <div
                class="
                    flex
                    items-start
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


                <div class="min-w-0">

                    <p
                        class="
                            font-bold
                            text-red-700
                        "
                    >
                        Changes could not be saved
                    </p>


                    <ul
                        class="
                            mt-2
                            list-disc
                            space-y-1
                            pl-5
                            text-sm
                            text-red-600
                        "
                    >

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </section>

    @endif



    {{-- ====================================================== --}}
    {{-- FORM --}}
    {{-- ====================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'events.update',
            $event->event_id
        ) }}"
        class="space-y-8"
    >

        @csrf
        @method('PUT')



        {{-- ====================================================== --}}
        {{-- EVENT INFORMATION --}}
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
                    Event Record
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Event Information
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Update the official event identity,
                    location, and organizational scope.
                </p>

            </div>



            <div
                class="
                    event-edit-section
                    min-w-0
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
                        md:grid-cols-2
                    "
                >


                    {{-- EVENT NAME --}}

                    <div
                        class="
                            border-b
                            border-gray-100
                            px-6
                            py-6
                            md:border-r
                        "
                    >

                        <label
                            for="event_name"
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
                            Event Name
                            <span class="text-red-500">*</span>
                        </label>


                        <input
                            id="event_name"
                            type="text"
                            name="event_name"
                            value="{{ old(
                                'event_name',
                                $event->event_name
                            ) }}"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >

                    </div>



                    {{-- LOCATION --}}

                    <div
                        class="
                            border-b
                            border-gray-100
                            px-6
                            py-6
                        "
                    >

                        <label
                            for="location"
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
                            Location
                            <span class="text-red-500">*</span>
                        </label>


                        <input
                            id="location"
                            type="text"
                            name="location"
                            value="{{ old(
                                'location',
                                $event->location
                            ) }}"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >

                    </div>



                    {{-- DEPARTMENT --}}

                    <div
                        class="
                            border-b
                            border-gray-100
                            px-6
                            py-6
                            md:border-r
                            md:border-b-0
                        "
                    >

                        <label
                            for="department_id"
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
                                    min-h-[46px]
                                    items-center
                                    rounded-lg
                                    border
                                    border-gray-200
                                    bg-gray-50
                                    px-4
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                "
                            >
                                {{
                                    $currentUser
                                        ->department
                                        ->department_name
                                    ?? 'No department assigned'
                                }}
                            </div>


                            <input
                                type="hidden"
                                name="department_id"
                                value="{{ $currentUser->department_id }}"
                            >


                            <p
                                class="
                                    mt-2
                                    text-xs
                                    leading-5
                                    text-gray-400
                                "
                            >
                                Department Staff cannot transfer
                                an event to another department.
                            </p>


                        @else


                            <select
                                id="department_id"
                                name="department_id"
                                class="
                                    w-full
                                    rounded-lg
                                    border-gray-200
                                    px-4
                                    py-3
                                    text-sm
                                    text-gray-700
                                    focus:border-[#101064]
                                    focus:ring-[#101064]
                                "
                            >

                                <option
                                    value=""
                                    {{
                                        old(
                                            'department_id',
                                            $event->department_id
                                        ) === null
                                            || old(
                                                'department_id',
                                                $event->department_id
                                            ) === ''
                                                ? 'selected'
                                                : ''
                                    }}
                                >
                                    University-wide Event
                                </option>


                                @foreach($departments as $department)

                                    <option
                                        value="{{ $department->department_id }}"
                                        {{
                                            (string) old(
                                                'department_id',
                                                $event->department_id
                                            )
                                            ===
                                            (string) $department->department_id
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        {{ $department->department_name }}
                                    </option>

                                @endforeach

                            </select>


                            <p
                                class="
                                    mt-2
                                    text-xs
                                    leading-5
                                    text-gray-400
                                "
                            >
                                Changing the department may require
                                updating the assigned attendance personnel.
                            </p>

                        @endif

                    </div>



                    {{-- EVENT SCOPE INFO --}}

                    <div
                        class="
                            bg-gray-50/60
                            px-6
                            py-6
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
                            Current Event Scope
                        </p>


                        <div class="mt-3">

                            @if($event->department)

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
                                        text-[#8A6D00]
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

                                    Department Event

                                </span>

                            @else

                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-full
                                        bg-[#F0F1F8]
                                        px-3
                                        py-1
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
                                            bg-[#101064]
                                        "
                                    ></span>

                                    University-wide

                                </span>

                            @endif

                        </div>


                        <p
                            class="
                                mt-3
                                text-xs
                                leading-5
                                text-gray-500
                            "
                        >
                            Personnel assignments must remain
                            compatible with the event department.
                        </p>

                    </div>


                </div>

            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- EVENT SCHEDULE --}}
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
                    Event Date & Time
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Update the scheduled date and attendance window.
                </p>

            </div>



            <div
                class="
                    event-edit-section
                    grid
                    grid-cols-1
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                    md:grid-cols-3
                "
            >


                {{-- DATE --}}

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

                    <label
                        for="event_date"
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
                        <span class="text-red-500">*</span>
                    </label>


                    <input
                        id="event_date"
                        type="date"
                        name="event_date"
                        value="{{ old(
                            'event_date',
                            $event->event_date
                        ) }}"
                        required
                        class="
                            w-full
                            rounded-lg
                            border-gray-200
                            px-4
                            py-3
                            text-sm
                            text-gray-700
                            focus:border-[#101064]
                            focus:ring-[#101064]
                        "
                    >

                </div>



                {{-- START TIME --}}

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

                    <label
                        for="start_time"
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
                        Start Time
                        <span class="text-red-500">*</span>
                    </label>


                    <input
                        id="start_time"
                        type="time"
                        name="start_time"
                        value="{{ old(
                            'start_time',
                            \Carbon\Carbon::parse(
                                $event->start_time
                            )->format('H:i')
                        ) }}"
                        required
                        class="
                            w-full
                            rounded-lg
                            border-gray-200
                            px-4
                            py-3
                            text-sm
                            text-gray-700
                            focus:border-[#101064]
                            focus:ring-[#101064]
                        "
                    >

                </div>



                {{-- END TIME --}}

                <div
                    class="
                        px-6
                        py-6
                    "
                >

                    <label
                        for="end_time"
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
                        End Time
                        <span class="text-red-500">*</span>
                    </label>


                    <input
                        id="end_time"
                        type="time"
                        name="end_time"
                        value="{{ old(
                            'end_time',
                            \Carbon\Carbon::parse(
                                $event->end_time
                            )->format('H:i')
                        ) }}"
                        required
                        class="
                            w-full
                            rounded-lg
                            border-gray-200
                            px-4
                            py-3
                            text-sm
                            text-gray-700
                            focus:border-[#101064]
                            focus:ring-[#101064]
                        "
                    >

                </div>


            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- EVENT STATUS --}}
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
                    Lifecycle
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Event Status
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Update the current operational state
                    of this event.
                </p>

            </div>



            <div
                class="
                    event-edit-section
                    grid
                    grid-cols-1
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                    lg:grid-cols-[1fr_360px]
                "
            >


                {{-- STATUS SELECT --}}

                <div
                    class="
                        border-b
                        border-gray-100
                        px-6
                        py-6
                        lg:border-b-0
                        lg:border-r
                    "
                >

                    <label
                        for="status"
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
                        <span class="text-red-500">*</span>
                    </label>


                    <select
                        id="status"
                        name="status"
                        required
                        class="
                            w-full
                            rounded-lg
                            border-gray-200
                            px-4
                            py-3
                            text-sm
                            text-gray-700
                            focus:border-[#101064]
                            focus:ring-[#101064]
                        "
                    >

                        <option
                            value="Upcoming"
                            {{
                                old(
                                    'status',
                                    $event->status
                                ) === 'Upcoming'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            Upcoming
                        </option>


                        <option
                            value="Ongoing"
                            {{
                                old(
                                    'status',
                                    $event->status
                                ) === 'Ongoing'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            Ongoing
                        </option>


                        <option
                            value="Completed"
                            {{
                                old(
                                    'status',
                                    $event->status
                                ) === 'Completed'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            Completed
                        </option>


                        <option
                            value="Cancelled"
                            {{
                                old(
                                    'status',
                                    $event->status
                                ) === 'Cancelled'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            Cancelled
                        </option>

                    </select>

                </div>



                {{-- CURRENT STATUS --}}

                <div
                    class="
                        bg-gray-50/60
                        px-6
                        py-6
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
                        Current Status
                    </p>


                    <div class="mt-3">

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

                            {{ $event->status }}

                        </span>

                    </div>


                    <p
                        class="
                            mt-3
                            text-xs
                            leading-5
                            text-gray-500
                        "
                    >
                        Completed and cancelled events cannot
                        have their personnel assignments changed.
                    </p>

                </div>


            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- DESCRIPTION --}}
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
                    Event Details
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Description
                </h2>

            </div>



            <div
                class="
                    event-edit-section
                    border
                    border-gray-200
                    bg-white
                    px-6
                    py-6
                "
            >

                <label
                    for="description"
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
                    Event Description
                </label>


                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Provide relevant event information..."
                    class="
                        w-full
                        resize-y
                        rounded-lg
                        border-gray-200
                        px-4
                        py-3
                        text-sm
                        leading-6
                        text-gray-700
                        placeholder:text-gray-400
                        focus:border-[#101064]
                        focus:ring-[#101064]
                    "
                >{{ old(
                    'description',
                    $event->description
                ) }}</textarea>

            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- PERSONNEL NOTICE --}}
        {{-- ====================================================== --}}

        <section
            class="
                event-edit-section
                overflow-hidden
                border
                border-[#E7C75B]
                bg-white
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-5
                    px-6
                    py-6
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                "
            >


                <div
                    class="
                        flex
                        min-w-0
                        items-start
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
                            bg-[#FFF9E7]
                            text-[#B68A0D]
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
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8"
                            />
                        </svg>

                    </div>



                    <div class="min-w-0">

                        <p
                            class="
                                font-bold
                                text-[#101064]
                            "
                        >
                            Attendance Personnel
                        </p>


                        <p
                            class="
                                mt-1
                                max-w-2xl
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            Personnel assignment is managed separately
                            so schedule and event details do not
                            accidentally alter attendance staffing.
                        </p>

                    </div>

                </div>



                <a
                    href="{{ route(
                        'events.personnel',
                        $event->event_id
                    ) }}"
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
                    Manage Personnel
                    <span>→</span>
                </a>


            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- SAVE ACTIONS --}}
        {{-- ====================================================== --}}

        <section
            class="
                event-edit-section
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-4
                    px-6
                    py-5
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                "
            >


                <div>

                    <p
                        class="
                            text-xs
                            font-bold
                            text-[#101064]
                        "
                    >
                        Save Event Changes
                    </p>


                    <p
                        class="
                            mt-1
                            text-xs
                            leading-5
                            text-gray-400
                        "
                    >
                        Review the updated information before
                        saving this event record.
                    </p>

                </div>



                <div
                    class="
                        flex
                        flex-wrap
                        justify-end
                        gap-3
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
                            justify-center
                            rounded-lg
                            border
                            border-gray-200
                            bg-white
                            px-6
                            py-3
                            text-sm
                            font-semibold
                            text-gray-600
                            transition
                            hover:bg-gray-50
                        "
                    >
                        Cancel
                    </a>



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
                            py-3
                            text-sm
                            font-bold
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
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                        Save Changes

                    </button>

                </div>


            </div>

        </section>


    </form>


</div>

</x-admin-layout>