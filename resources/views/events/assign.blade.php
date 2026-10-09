<x-admin-layout>

@php

    /*
    |--------------------------------------------------------------------------
    | PAGE STATE
    |--------------------------------------------------------------------------
    */

    $readOnly = in_array(
        $event->status,
        ['Completed', 'Cancelled'],
        true
    );


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


    /*
    |--------------------------------------------------------------------------
    | ELIGIBLE PERSONNEL
    |--------------------------------------------------------------------------
    */

    $eligibleIds = $availablePersonnel
        ->pluck('personnel_id')
        ->map(fn ($id) => (int) $id)
        ->values();


    /*
    |--------------------------------------------------------------------------
    | CURRENT VALID SELECTION
    |--------------------------------------------------------------------------
    |
    | Only assignments that are still eligible are editable.
    |
    */

    $initialSelected = $assignedPersonnelIds
        ->map(fn ($id) => (int) $id)
        ->intersect($eligibleIds)
        ->values();


    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENTS NO LONGER ELIGIBLE
    |--------------------------------------------------------------------------
    |
    | Example:
    | - personnel account became inactive
    | - account setup became incomplete
    | - role was changed
    | - department assignment changed
    |
    */

    $invalidAssignments = $event->assignments
        ->filter(function ($assignment) use ($eligibleIds) {

            return !$eligibleIds->contains(
                (int) $assignment->personnel_id
            );

        });


    /*
    |--------------------------------------------------------------------------
    | ALPINE PERSONNEL DATA
    |--------------------------------------------------------------------------
    */

    $personnelOptions = $availablePersonnel
        ->map(function ($person) {

            return [

                'id' =>
                    (int) $person->personnel_id,

                'name' =>
                    trim(
                        $person->first_name
                        . ' '
                        . $person->last_name
                    ),

                'initial' =>
                    strtoupper(
                        substr(
                            $person->first_name ?? '?',
                            0,
                            1
                        )
                    ),

                'position' =>
                    $person->position,

                'email' =>
                    $person->user?->email ?? '',

                'department' =>
                    $person->user?->department?->department_name
                    ?? 'University-wide',

            ];

        })
        ->values();

@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .event-personnel-hero {

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

    .event-personnel-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }


    /*
    |--------------------------------------------------------------------------
    | SCROLLBAR
    |--------------------------------------------------------------------------
    */

    .personnel-scrollbar::-webkit-scrollbar {

        width: 6px;
        height: 6px;

    }


    .personnel-scrollbar::-webkit-scrollbar-track {

        background: #f3f4f6;

    }


    .personnel-scrollbar::-webkit-scrollbar-thumb {

        background: #d1d5db;
        border-radius: 9999px;

    }

</style>



<div
    class="min-w-0 space-y-8"

    x-data="{

        search: '',

        personnel:
            @js($personnelOptions),

        selected:
            @js($initialSelected),


        get filteredPersonnel() {

            const keyword =
                this.search
                    .toLowerCase()
                    .trim();


            if (!keyword) {

                return this.personnel;

            }


            return this.personnel.filter(person => {

                return [

                    person.name,
                    person.position,
                    person.department,
                    person.email

                ].some(value =>

                    String(value ?? '')
                        .toLowerCase()
                        .includes(keyword)

                );

            });

        },


        get selectedPersonnel() {

            return this.personnel.filter(person =>

                this.selected.includes(
                    Number(person.id)
                )

            );

        },


        selectVisible() {

            this.filteredPersonnel.forEach(person => {

                const id =
                    Number(person.id);


                if (!this.selected.includes(id)) {

                    this.selected.push(id);

                }

            });

        },


        clearSelection() {

            this.selected = [];

        },


        removePersonnel(id) {

            id = Number(id);


            this.selected =
                this.selected.filter(
                    selectedId =>
                        Number(selectedId) !== id
                );

        }

    }"
>


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            event-personnel-hero
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
                    Event Staffing
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
                    {{ $readOnly
                        ? 'Assigned Personnel'
                        : 'Manage Personnel'
                    }}
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

                    @if($readOnly)

                        Review the attendance personnel assigned
                        to this event.

                    @else

                        Assign eligible attendance personnel
                        responsible for RFID and attendance
                        operations.

                    @endif

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
                        {{
                            \Carbon\Carbon::parse(
                                $event->event_date
                            )->format('M d, Y')
                        }}
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
    {{-- VALIDATION --}}
    {{-- ====================================================== --}}

    @if($errors->any())

        <section
            class="
                event-personnel-section
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


                <div>

                    <p class="font-bold text-red-700">
                        Personnel assignments could not be saved
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
    {{-- LOCKED NOTICE --}}
    {{-- ====================================================== --}}

    @if($readOnly)

        <section
            class="
                event-personnel-section
                border
                border-gray-200
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
                        bg-gray-100
                        text-gray-500
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
                            d="M7 11V7a5 5 0 0110 0v4m-11 0h12v10H6V11z"
                        />
                    </svg>

                </div>


                <div>

                    <p
                        class="
                            font-bold
                            text-[#101064]
                        "
                    >
                        Personnel Assignments Locked
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            leading-6
                            text-gray-500
                        "
                    >
                        Personnel assignments cannot be changed
                        because this event is already
                        {{ strtolower($event->status) }}.
                    </p>

                </div>

            </div>

        </section>

    @endif



    {{-- ====================================================== --}}
    {{-- EVENT SUMMARY --}}
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
                Event Reference
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Event Summary
            </h2>

        </div>



        <div
            class="
                event-personnel-section
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


            {{-- EVENT --}}

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
                    Event
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-[#101064]
                    "
                >
                    {{ $event->event_name }}
                </p>

            </div>



            {{-- DEPARTMENT --}}

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
                    Department
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
                        $event->department?->department_name
                        ?? 'University-wide'
                    }}
                </p>

            </div>



            {{-- SCHEDULE --}}

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
                    Schedule
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

                    –

                    {{
                        \Carbon\Carbon::parse(
                            $event->end_time
                        )->format('g:i A')
                    }}
                </p>

            </div>



            {{-- STATUS --}}

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
                    Status
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

                        {{ $event->status }}

                    </span>

                </div>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- OLD / INELIGIBLE ASSIGNMENTS --}}
    {{-- ====================================================== --}}

    @if($invalidAssignments->isNotEmpty())

        <section class="min-w-0">

            <div class="mb-4">

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.28em]
                        text-red-500
                    "
                >
                    Attention Required
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Ineligible Existing Assignments
                </h2>

            </div>



            <div
                class="
                    event-personnel-section
                    overflow-hidden
                    border
                    border-red-200
                    bg-white
                "
            >

                @foreach($invalidAssignments as $assignment)

                    @php

                        $oldPerson =
                            $assignment->personnel;

                        $oldUser =
                            $oldPerson?->user;

                    @endphp


                    <div
                        class="
                            flex
                            flex-col
                            gap-3
                            border-b
                            border-red-100
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
                                items-center
                                gap-4
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
                                {{
                                    strtoupper(
                                        substr(
                                            $oldPerson?->first_name
                                            ?? '?',
                                            0,
                                            1
                                        )
                                    )
                                }}
                            </div>


                            <div>

                                <p
                                    class="
                                        font-semibold
                                        text-[#101064]
                                    "
                                >
                                    {{
                                        trim(
                                            ($oldPerson?->first_name ?? '')
                                            . ' '
                                            . ($oldPerson?->last_name ?? '')
                                        )
                                        ?: 'Unknown Personnel'
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
                                        $oldUser?->role?->role_name
                                        ?? 'Unknown Role'
                                    }}

                                    •

                                    {{
                                        $oldUser?->department?->department_name
                                        ?? 'University-wide'
                                    }}
                                </p>

                            </div>

                        </div>


                        <span
                            class="
                                inline-flex
                                w-fit
                                rounded-full
                                bg-red-50
                                px-3
                                py-1
                                text-xs
                                font-semibold
                                text-red-600
                            "
                        >
                            No longer eligible
                        </span>

                    </div>

                @endforeach


                @if(!$readOnly)

                    <div
                        class="
                            border-t
                            border-red-100
                            bg-red-50/40
                            px-6
                            py-4
                        "
                    >

                        <p
                            class="
                                text-xs
                                leading-5
                                text-red-600
                            "
                        >
                            These assignments no longer meet the
                            current eligibility rules. They will
                            not be included when you save the
                            updated assignment list.
                        </p>

                    </div>

                @endif

            </div>

        </section>

    @endif



    {{-- ====================================================== --}}
    {{-- READ ONLY PERSONNEL --}}
    {{-- ====================================================== --}}

    @if($readOnly)

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
                    Staffing Record
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
                    Final personnel assignment recorded
                    for this event.
                </p>

            </div>



            <div
                class="
                    event-personnel-section
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                "
            >

                @forelse($event->assignments as $assignment)

                    @php

                        $person =
                            $assignment->personnel;

                        $user =
                            $person?->user;

                    @endphp


                    <div
                        class="
                            flex
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
                                    bg-[#101064]
                                    font-bold
                                    text-white
                                "
                            >
                                {{
                                    strtoupper(
                                        substr(
                                            $person?->first_name
                                            ?? '?',
                                            0,
                                            1
                                        )
                                    )
                                }}
                            </div>


                            <div>

                                <p
                                    class="
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


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    {{
                                        $person?->position
                                        ?? 'Attendance Personnel'
                                    }}

                                    •

                                    {{
                                        $user?->department?->department_name
                                        ?? 'University-wide'
                                    }}
                                </p>

                            </div>

                        </div>


                        <span
                            class="
                                inline-flex
                                w-fit
                                rounded-full
                                bg-[#F0F1F8]
                                px-3
                                py-1
                                text-xs
                                font-semibold
                                text-[#101064]
                            "
                        >
                            Assigned
                        </span>

                    </div>


                @empty

                    <div
                        class="
                            px-6
                            py-14
                            text-center
                        "
                    >

                        <p
                            class="
                                font-semibold
                                text-[#101064]
                            "
                        >
                            No personnel assignment recorded
                        </p>

                    </div>

                @endforelse

            </div>

        </section>


    @else


        {{-- ====================================================== --}}
        {{-- EDITABLE PERSONNEL FORM --}}
        {{-- ====================================================== --}}

        <form
            method="POST"
            action="{{ route(
                'events.personnel.update',
                $event->event_id
            ) }}"
            class="space-y-8"
        >

            @csrf
            @method('PUT')



            <section class="min-w-0">


                <div
                    class="
                        mb-4
                        flex
                        flex-col
                        gap-4
                        lg:flex-row
                        lg:items-end
                        lg:justify-between
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
                            Personnel Assignment
                        </p>


                        <h2
                            class="
                                mt-2
                                text-xl
                                font-bold
                                text-[#101064]
                            "
                        >
                            Attendance Personnel
                        </h2>


                        <p
                            class="
                                mt-1
                                text-sm
                                text-gray-400
                            "
                        >
                            Select one or more eligible personnel
                            for this event.
                        </p>

                    </div>



                    <div
                        class="
                            flex
                            flex-wrap
                            gap-2
                        "
                    >

                        <button
                            type="button"
                            @click="selectVisible()"
                            class="
                                rounded-lg
                                border
                                border-gray-200
                                bg-white
                                px-4
                                py-2
                                text-xs
                                font-semibold
                                text-gray-600
                                transition
                                hover:bg-gray-50
                            "
                        >
                            Select Visible
                        </button>


                        <button
                            type="button"
                            @click="clearSelection()"
                            class="
                                rounded-lg
                                border
                                border-red-200
                                bg-white
                                px-4
                                py-2
                                text-xs
                                font-semibold
                                text-red-500
                                transition
                                hover:bg-red-50
                            "
                        >
                            Clear Selection
                        </button>

                    </div>


                </div>



                <div
                    class="
                        grid
                        min-w-0
                        grid-cols-1
                        gap-6
                        xl:grid-cols-2
                    "
                >


                    {{-- ====================================================== --}}
                    {{-- AVAILABLE --}}
                    {{-- ====================================================== --}}

                    <div
                        class="
                            event-personnel-section
                            min-w-0
                            overflow-hidden
                            border
                            border-gray-200
                            bg-white
                        "
                    >


                        {{-- HEADER --}}

                        <div
                            class="
                                border-b
                                border-gray-100
                                px-6
                                py-5
                            "
                        >

                            <div
                                class="
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                "
                            >

                                <div>

                                    <h3
                                        class="
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        Available Personnel
                                    </h3>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-400
                                        "
                                    >
                                        Active and fully configured
                                        Attendance Personnel
                                    </p>

                                </div>


                                <span
                                    class="
                                        rounded-full
                                        bg-[#F0F1F8]
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-[#101064]
                                    "
                                    x-text="
                                        filteredPersonnel.length
                                    "
                                ></span>

                            </div>



                            {{-- SEARCH --}}

                            <div class="mt-4">

                                <input
                                    type="text"
                                    x-model="search"
                                    placeholder="Search personnel, department, position..."
                                    class="
                                        w-full
                                        rounded-lg
                                        border-gray-200
                                        px-4
                                        py-3
                                        text-sm
                                        text-gray-700
                                        placeholder:text-gray-400
                                        focus:border-[#101064]
                                        focus:ring-[#101064]
                                    "
                                >

                            </div>

                        </div>



                        {{-- PERSONNEL LIST --}}

                        <div
                            class="
                                personnel-scrollbar
                                max-h-[560px]
                                overflow-y-auto
                            "
                        >


                            <template
                                x-for="
                                    person in filteredPersonnel
                                "
                                :key="person.id"
                            >

                                <label
                                    class="
                                        flex
                                        cursor-pointer
                                        items-center
                                        gap-4
                                        border-b
                                        border-gray-100
                                        px-6
                                        py-5
                                        transition
                                        last:border-b-0
                                        hover:bg-gray-50
                                    "
                                    :class="
                                        selected.includes(
                                            Number(person.id)
                                        )
                                        ? 'bg-[#F8F8FD]'
                                        : 'bg-white'
                                    "
                                >


                                    <input
                                        type="checkbox"
                                        name="personnel_ids[]"
                                        :value="person.id"
                                        x-model.number="selected"
                                        class="
                                            h-5
                                            w-5
                                            shrink-0
                                            rounded
                                            border-gray-300
                                            text-[#101064]
                                            focus:ring-[#101064]
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
                                            bg-[#101064]
                                            text-sm
                                            font-bold
                                            text-white
                                        "
                                        x-text="person.initial"
                                    ></div>



                                    <div
                                        class="
                                            min-w-0
                                            flex-1
                                        "
                                    >

                                        <p
                                            class="
                                                truncate
                                                font-semibold
                                                text-[#101064]
                                            "
                                            x-text="person.name"
                                        ></p>


                                        <p
                                            class="
                                                mt-1
                                                truncate
                                                text-xs
                                                text-gray-400
                                            "
                                        >

                                            <span
                                                x-text="
                                                    person.position
                                                "
                                            ></span>

                                            <span>
                                                •
                                            </span>

                                            <span
                                                x-text="
                                                    person.department
                                                "
                                            ></span>

                                        </p>


                                        <p
                                            class="
                                                mt-1
                                                truncate
                                                text-xs
                                                text-gray-300
                                            "
                                            x-text="person.email"
                                        ></p>

                                    </div>



                                    <div
                                        class="
                                            shrink-0
                                        "
                                    >

                                        <span
                                            x-show="
                                                selected.includes(
                                                    Number(person.id)
                                                )
                                            "
                                            class="
                                                inline-flex
                                                rounded-full
                                                bg-green-50
                                                px-3
                                                py-1
                                                text-[10px]
                                                font-semibold
                                                text-green-700
                                            "
                                        >
                                            Selected
                                        </span>

                                    </div>


                                </label>

                            </template>



                            {{-- NO RESULTS --}}

                            <div
                                x-show="
                                    filteredPersonnel.length === 0
                                "
                                style="display:none;"
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


                                <p
                                    class="
                                        mt-5
                                        font-semibold
                                        text-[#101064]
                                    "
                                >
                                    No eligible personnel found
                                </p>


                                <p
                                    class="
                                        mt-2
                                        text-sm
                                        text-gray-400
                                    "
                                >
                                    Try changing your search.
                                </p>

                            </div>


                        </div>


                    </div>



                    {{-- ====================================================== --}}
                    {{-- SELECTED --}}
                    {{-- ====================================================== --}}

                    <div
                        class="
                            event-personnel-section
                            min-w-0
                            overflow-hidden
                            border
                            border-gray-200
                            bg-white
                        "
                    >


                        {{-- HEADER --}}

                        <div
                            class="
                                border-b
                                border-gray-100
                                bg-gray-50/60
                                px-6
                                py-5
                            "
                        >

                            <div
                                class="
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                "
                            >

                                <div>

                                    <h3
                                        class="
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        Selected Personnel
                                    </h3>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-400
                                        "
                                    >
                                        Personnel that will be
                                        assigned to this event
                                    </p>

                                </div>


                                <span
                                    class="
                                        rounded-full
                                        bg-[#101064]
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-white
                                    "
                                    x-text="
                                        selectedPersonnel.length
                                    "
                                ></span>

                            </div>

                        </div>



                        <div
                            class="
                                personnel-scrollbar
                                max-h-[560px]
                                overflow-y-auto
                            "
                        >


                            <template
                                x-for="
                                    person in selectedPersonnel
                                "
                                :key="'selected-' + person.id"
                            >

                                <div
                                    class="
                                        flex
                                        items-center
                                        gap-4
                                        border-b
                                        border-gray-100
                                        px-6
                                        py-5
                                        last:border-b-0
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
                                            bg-[#101064]
                                            text-sm
                                            font-bold
                                            text-white
                                        "
                                        x-text="
                                            person.initial
                                        "
                                    ></div>



                                    <div
                                        class="
                                            min-w-0
                                            flex-1
                                        "
                                    >

                                        <p
                                            class="
                                                truncate
                                                font-semibold
                                                text-[#101064]
                                            "
                                            x-text="
                                                person.name
                                            "
                                        ></p>


                                        <p
                                            class="
                                                mt-1
                                                truncate
                                                text-xs
                                                text-gray-400
                                            "
                                        >

                                            <span
                                                x-text="
                                                    person.position
                                                "
                                            ></span>

                                            <span>
                                                •
                                            </span>

                                            <span
                                                x-text="
                                                    person.department
                                                "
                                            ></span>

                                        </p>

                                    </div>



                                    <button
                                        type="button"
                                        @click="
                                            removePersonnel(
                                                person.id
                                            )
                                        "
                                        class="
                                            shrink-0
                                            text-xs
                                            font-semibold
                                            text-red-500
                                            transition
                                            hover:text-red-700
                                        "
                                    >
                                        Remove
                                    </button>

                                </div>

                            </template>



                            {{-- EMPTY SELECTION --}}

                            <div
                                x-show="
                                    selectedPersonnel.length === 0
                                "
                                style="display:none;"
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
                                    No personnel selected
                                </h3>


                                <p
                                    class="
                                        mt-2
                                        text-sm
                                        leading-6
                                        text-gray-400
                                    "
                                >
                                    Select at least one Attendance
                                    Personnel from the available
                                    personnel list.
                                </p>

                            </div>


                        </div>


                    </div>


                </div>

            </section>



            {{-- ====================================================== --}}
            {{-- ASSIGNMENT INFORMATION --}}
            {{-- ====================================================== --}}

            <section
                class="
                    event-personnel-section
                    overflow-hidden
                    border
                    border-[#E7C75B]
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
                                d="M12 9v4m0 4h.01M5 19h14L12 5 5 19z"
                            />
                        </svg>

                    </div>


                    <div>

                        <p
                            class="
                                font-bold
                                text-[#101064]
                            "
                        >
                            Assignment Requirements
                        </p>


                        <p
                            class="
                                mt-1
                                max-w-4xl
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            Only active Attendance Personnel
                            accounts that have completed account
                            setup and are eligible for this
                            event's department are displayed.
                            At least one personnel assignment
                            is required.
                        </p>

                    </div>

                </div>

            </section>



            {{-- ====================================================== --}}
            {{-- SAVE --}}
            {{-- ====================================================== --}}

            <section
                class="
                    event-personnel-section
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
                            Save Personnel Assignment
                        </p>


                        <p
                            class="
                                mt-1
                                text-xs
                                leading-5
                                text-gray-400
                            "
                        >
                            Selected personnel will receive
                            access to this event through their
                            assigned-events workflow.
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
                            :disabled="
                                selectedPersonnel.length === 0
                            "
                            :class="
                                selectedPersonnel.length === 0
                                ? 'cursor-not-allowed bg-gray-300 text-gray-500'
                                : 'bg-[#101064] text-white hover:bg-[#D4A017] hover:text-[#101064]'
                            "
                            class="
                                inline-flex
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                px-6
                                py-3
                                text-sm
                                font-bold
                                transition
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

                            Save Assignments

                        </button>

                    </div>


                </div>

            </section>


        </form>

    @endif


</div>

</x-admin-layout>