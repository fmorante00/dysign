<x-admin-layout>

@php

    /*
    |--------------------------------------------------------------------------
    | EVENT STATUS
    |--------------------------------------------------------------------------
    */

    $statusConfig = match($event->status) {

        'Upcoming' => [
            'label' => 'Upcoming',
            'class' => 'bg-[#FFF9E7] text-[#B68A0D]',
            'dot' => 'bg-[#D4A017]',
        ],

        'Ongoing' => [
            'label' => 'Ongoing',
            'class' => 'bg-green-50 text-green-700',
            'dot' => 'bg-green-500',
        ],

        'Completed' => [
            'label' => 'Completed',
            'class' => 'bg-gray-100 text-gray-600',
            'dot' => 'bg-gray-500',
        ],

        'Cancelled' => [
            'label' => 'Cancelled',
            'class' => 'bg-red-50 text-red-600',
            'dot' => 'bg-red-500',
        ],

        default => [
            'label' => $event->status,
            'class' => 'bg-gray-100 text-gray-600',
            'dot' => 'bg-gray-400',
        ],

    };


    $assignedCount =
        $event->assignments->count();

@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .event-profile-hero {

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
    | SECTIONS
    |--------------------------------------------------------------------------
    */

    .event-profile-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }

</style>



<div
    class="min-w-0 space-y-8"

    x-data="{

        showCancelModal: false

    }"
>


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            event-profile-hero
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


            {{-- EVENT INFORMATION --}}

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
                    Event Record
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
                    {{ $event->event_name }}
                </h1>



                <div
                    class="
                        mt-4
                        flex
                        flex-wrap
                        items-center
                        gap-x-3
                        gap-y-2
                        text-sm
                        text-white/75
                    "
                >


                    {{-- DATE --}}

                    <span>
                        {{
                            \Carbon\Carbon::parse(
                                $event->event_date
                            )->format('F d, Y')
                        }}
                    </span>


                    <span class="text-white/30">
                        •
                    </span>


                    {{-- TIME --}}

                    <span>
                        {{
                            \Carbon\Carbon::parse(
                                $event->start_time
                            )->format('g:i A')
                        }}

                        –

                        {{
                            \Carbon\Carbon::parse(
                                $event->end_time
                            )->format('g:i A')
                        }}
                    </span>


                    <span class="text-white/30">
                        •
                    </span>


                    {{-- LOCATION --}}

                    <span class="break-words">
                        {{ $event->location }}
                    </span>


                    <span class="text-white/30">
                        •
                    </span>


                    {{-- STATUS --}}

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

                        {{ $statusConfig['label'] }}

                    </span>


                </div>

            </div>



            {{-- BACK BUTTON --}}

            <div class="shrink-0">

                <a
                    href="{{ route('events.index') }}"
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

                    Back to Events

                </a>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <section
            class="
                event-profile-section
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
                        Action Completed
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
    {{-- ERROR MESSAGE --}}
    {{-- ====================================================== --}}

    @if(session('error'))

        <section
            class="
                event-profile-section
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
                Event Information
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Event Details
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Official schedule and organizational information
                for this event.
            </p>

        </div>



        <div
            class="
                event-profile-section
                grid
                min-w-0
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                md:grid-cols-2
                xl:grid-cols-4
            "
        >


            {{-- EVENT NAME --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-r
                    xl:border-b-0
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
                    Event Name
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ $event->event_name }}
                </p>

            </div>



            {{-- DEPARTMENT --}}

            <div
                class="
                    min-w-0
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
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Department
                </p>


                @if($event->department)

                    <p
                        class="
                            mt-2
                            break-words
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

                    <div class="mt-2">

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
                            University-wide Event
                        </span>

                    </div>

                @endif

            </div>



            {{-- LOCATION --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-r
                    md:border-b-0
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
                    Location
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $event->location }}
                </p>

            </div>



            {{-- STATUS --}}

            <div
                class="
                    min-w-0
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
                    Event Status
                </p>


                <div class="mt-2">

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

                        {{ $statusConfig['label'] }}

                    </span>

                </div>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SCHEDULE --}}
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
                Event Schedule
            </h2>

        </div>



        <div
            class="
                event-profile-section
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

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Event Date
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    {{
                        \Carbon\Carbon::parse(
                            $event->event_date
                        )->format('F d, Y')
                    }}
                </p>

            </div>



            {{-- START --}}

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
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Start Time
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    {{
                        \Carbon\Carbon::parse(
                            $event->start_time
                        )->format('g:i A')
                    }}
                </p>

            </div>



            {{-- END --}}

            <div
                class="
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
                    End Time
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    {{
                        \Carbon\Carbon::parse(
                            $event->end_time
                        )->format('g:i A')
                    }}
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
                Event Description
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Event Overview
            </h2>

        </div>



        <div
            class="
                event-profile-section
                border
                border-gray-200
                bg-white
                px-6
                py-6
            "
        >

            @if($event->description)

                <p
                    class="
                        whitespace-pre-line
                        text-sm
                        leading-7
                        text-gray-600
                    "
                >
                    {{ $event->description }}
                </p>

            @else

                <p
                    class="
                        text-sm
                        italic
                        text-gray-400
                    "
                >
                    No description was provided for this event.
                </p>

            @endif

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ASSIGNED PERSONNEL --}}
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
                    Event Staffing
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Assigned Attendance Personnel
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Personnel currently responsible for
                    attendance operations.
                </p>

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

                @if(
                    in_array(
                        $event->status,
                        ['Completed', 'Cancelled']
                    )
                )

                    View Personnel

                @else

                    Manage Personnel

                @endif

                <span>→</span>

            </a>

        </div>



        <div
            class="
                event-profile-section
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >


            {{-- ASSIGNMENT SUMMARY --}}

            <div
                class="
                    flex
                    flex-col
                    gap-4
                    border-b
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
                        text-gray-500
                    "
                >
                    Active personnel assignments for this event
                </p>


                <span
                    class="
                        inline-flex
                        w-fit
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

                    {{ $assignedCount }}

                    {{
                        $assignedCount == 1
                            ? 'person assigned'
                            : 'people assigned'
                    }}

                </span>

            </div>



            @forelse($event->assignments as $assignment)

                @php

                    $person =
                        $assignment->personnel;

                    $personUser =
                        $person?->user;

                    $personDepartment =
                        $personUser?->department;

                    $personRole =
                        $personUser?->role;

                @endphp


                <div
                    class="
                        flex
                        min-w-0
                        flex-col
                        gap-4
                        border-b
                        border-gray-100
                        px-6
                        py-5
                        last:border-b-0
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
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

                        {{-- INITIAL --}}

                        <div
                            class="
                                flex
                                h-11
                                w-11
                                shrink-0
                                items-center
                                justify-center
                                rounded-full
                                bg-[#101064]
                                text-sm
                                font-bold
                                text-white
                            "
                        >
                            {{
                                strtoupper(
                                    substr(
                                        $person?->first_name ?? '?',
                                        0,
                                        1
                                    )
                                )
                            }}
                        </div>



                        <div class="min-w-0">

                            <p
                                class="
                                    break-words
                                    font-semibold
                                    text-[#101064]
                                "
                            >
                                {{
                                    trim(
                                        ($person?->first_name ?? '')
                                        . ' '
                                        . ($person?->last_name ?? '')
                                    )
                                    ?: 'Unknown Personnel'
                                }}
                            </p>


                            <div
                                class="
                                    mt-1
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-x-2
                                    gap-y-1
                                    text-xs
                                    text-gray-400
                                "
                            >

                                <span>
                                    {{
                                        $personRole?->role_name
                                        ?? 'Attendance Personnel'
                                    }}
                                </span>


                                @if($personDepartment)

                                    <span>•</span>

                                    <span>
                                        {{
                                            $personDepartment
                                                ->department_name
                                        }}
                                    </span>

                                @else

                                    <span>•</span>

                                    <span>
                                        University-wide
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>



                    {{-- ACCOUNT STATUS --}}

                    @if(
                        $personUser
                        && $personUser->status === 'Active'
                        && !$personUser->must_change_password
                    )

                        <span
                            class="
                                inline-flex
                                w-fit
                                shrink-0
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

                            Active Personnel

                        </span>


                    @elseif(
                        $personUser
                        && $personUser->status === 'Inactive'
                    )

                        <span
                            class="
                                inline-flex
                                w-fit
                                shrink-0
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

                            Inactive Account

                        </span>


                    @else

                        <span
                            class="
                                inline-flex
                                w-fit
                                shrink-0
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

                            Setup Pending

                        </span>

                    @endif


                </div>


            @empty


                <div
                    class="
                        px-6
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
                        No personnel assigned
                    </h3>


                    <p
                        class="
                            mt-2
                            text-sm
                            text-gray-400
                        "
                    >
                        This event currently has no attendance
                        personnel assignment.
                    </p>


                    @if(
                        !in_array(
                            $event->status,
                            ['Completed', 'Cancelled']
                        )
                    )

                        <a
                            href="{{ route(
                                'events.personnel',
                                $event->event_id
                            ) }}"
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
                            Assign Personnel
                        </a>

                    @endif

                </div>


            @endforelse


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SYSTEM RECORD --}}
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
                System Record
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Record Information
            </h2>

        </div>



        <div
            class="
                event-profile-section
                grid
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                md:grid-cols-2
                xl:grid-cols-4
            "
        >


            {{-- EVENT ID --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-5
                    md:border-r
                    xl:border-b-0
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
                    Event ID
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $event->event_id }}
                </p>

            </div>



            {{-- CREATED BY --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-5
                    xl:border-b-0
                    xl:border-r
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
                    Created By
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >
                    {{
                        $event->creator?->name
                        ?? 'Unknown'
                    }}
                </p>

            </div>



            {{-- CREATED --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-5
                    md:border-r
                    md:border-b-0
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
                    Record Added
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    {{
                        $event->created_at
                            ? $event->created_at
                                ->format('M d, Y • g:i A')
                            : '—'
                    }}
                </p>

            </div>



            {{-- UPDATED --}}

            <div
                class="
                    px-6
                    py-5
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
                    Last Updated
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    {{
                        $event->updated_at
                            ? $event->updated_at
                                ->format('M d, Y • g:i A')
                            : '—'
                    }}
                </p>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- MANAGEMENT ACTIONS --}}
    {{-- ====================================================== --}}

    <section
        class="
            event-profile-section
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
                gap-5
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
                    Event Management
                </p>


                <p
                    class="
                        mt-1
                        text-xs
                        leading-5
                        text-gray-400
                    "
                >
                    Update event information, manage personnel,
                    or cancel this event.
                </p>

            </div>



            <div
                class="
                    flex
                    flex-wrap
                    gap-3
                "
            >


                {{-- PERSONNEL --}}

                <a
                    href="{{ route(
                        'events.personnel',
                        $event->event_id
                    ) }}"
                    class="
                        inline-flex
                        items-center
                        justify-center
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

                    @if(
                        in_array(
                            $event->status,
                            ['Completed', 'Cancelled']
                        )
                    )
                        View Personnel
                    @else
                        Manage Personnel
                    @endif

                </a>



                {{-- EDIT --}}

                @if($event->status !== 'Cancelled')

                    <a
                        href="{{ route(
                            'events.edit',
                            $event->event_id
                        ) }}"
                        class="
                            inline-flex
                            items-center
                            justify-center
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
                        Edit Event
                    </a>

                @endif



                {{-- CANCEL --}}

                @if(
                    $event->status !== 'Cancelled'
                    && $event->status !== 'Completed'
                )

                    <button
                        type="button"
                        @click="showCancelModal = true"
                        class="
                            inline-flex
                            items-center
                            justify-center
                            rounded-lg
                            border
                            border-red-200
                            bg-white
                            px-5
                            py-2.5
                            text-xs
                            font-semibold
                            text-red-600
                            transition
                            hover:bg-red-50
                        "
                    >
                        Cancel Event
                    </button>

                @endif


            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- CANCEL FORM --}}
    {{-- ====================================================== --}}

    <form
        id="cancel-event-form"
        method="POST"
        action="{{ route(
            'events.destroy',
            $event->event_id
        ) }}"
        class="hidden"
    >
        @csrf
        @method('DELETE')
    </form>



    {{-- ====================================================== --}}
    {{-- CANCEL EVENT MODAL --}}
    {{-- ====================================================== --}}

    <div
        x-show="showCancelModal"
        x-transition.opacity
        style="display:none;"
        class="
            fixed
            inset-0
            z-[9999]
            flex
            items-center
            justify-center
            bg-black/50
            px-4
            py-6
            backdrop-blur-[2px]
        "
    >


        <div
            @click.outside="
                showCancelModal = false
            "
            class="
                w-full
                max-w-md
                overflow-hidden
                rounded-2xl
                bg-white
                shadow-2xl
            "
        >


            {{-- MODAL HEADER --}}

            <div
                class="
                    relative
                    bg-[#101064]
                    px-6
                    py-5
                    text-white
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
                        flex
                        items-center
                        justify-between
                        gap-4
                    "
                >

                    <div>

                        <p
                            class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-[0.25em]
                                text-[#E7C75B]
                            "
                        >
                            Event Management
                        </p>


                        <h2
                            class="
                                mt-2
                                text-xl
                                font-bold
                            "
                        >
                            Confirm Event Cancellation
                        </h2>

                    </div>


                    <button
                        type="button"
                        @click="
                            showCancelModal = false
                        "
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-full
                            text-white/70
                            transition
                            hover:bg-white/10
                            hover:text-white
                        "
                    >
                        ×
                    </button>

                </div>

            </div>



            {{-- MODAL BODY --}}

            <div
                class="
                    px-6
                    py-6
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
                            flex
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-red-50
                            text-lg
                            font-bold
                            text-red-600
                        "
                    >
                        !
                    </div>


                    <div>

                        <p
                            class="
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            Are you sure you want to cancel
                            this event?
                        </p>


                        <p
                            class="
                                mt-3
                                break-words
                                font-bold
                                text-[#101064]
                            "
                        >
                            {{ $event->event_name }}
                        </p>


                        <p
                            class="
                                mt-3
                                text-xs
                                leading-5
                                text-gray-400
                            "
                        >
                            The event will remain in DySign
                            for record and attendance history,
                            but its status will become Cancelled.
                        </p>

                    </div>

                </div>

            </div>



            {{-- MODAL FOOTER --}}

            <div
                class="
                    flex
                    justify-end
                    gap-3
                    border-t
                    border-gray-100
                    bg-gray-50
                    px-6
                    py-4
                "
            >

                <button
                    type="button"
                    @click="
                        showCancelModal = false
                    "
                    class="
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        px-5
                        py-2.5
                        text-sm
                        font-semibold
                        text-gray-600
                        transition
                        hover:bg-gray-50
                    "
                >
                    Keep Event
                </button>


                <button
                    type="button"
                    @click="
                        document
                            .getElementById(
                                'cancel-event-form'
                            )
                            .submit()
                    "
                    class="
                        rounded-lg
                        bg-red-600
                        px-5
                        py-2.5
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-red-700
                    "
                >
                    Cancel Event
                </button>

            </div>


        </div>

    </div>


</div>

</x-admin-layout>