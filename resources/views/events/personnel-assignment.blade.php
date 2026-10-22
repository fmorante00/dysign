<x-admin-layout>

@php

    $assignedCount =
        $personnel
            ->where('is_assigned', true)
            ->count();

    $conflictCount =
        $personnel
            ->where('has_schedule_conflict', true)
            ->count();

    $availableCount =
        $personnel
            ->where('has_schedule_conflict', false)
            ->count();

@endphp


<style>

    [x-cloak] {
        display: none !important;
    }

    .assignment-hero {
        background:
            linear-gradient(
                100deg,
                rgba(16, 16, 100, .97) 0%,
                rgba(16, 16, 100, .91) 55%,
                rgba(16, 16, 100, .72) 100%
            ),
            url('{{ asset('images/school.jpg') }}');

        background-size: cover;
        background-position: center;
    }

    .assignment-card {
        box-shadow:
            0 1px 2px rgba(15, 23, 42, .03);
    }

</style>


<div
    class="min-w-0 space-y-7"
    x-data="{
        search: ''
    }"
>

    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            assignment-hero
            relative
            overflow-hidden
            rounded-[26px]
            px-8
            py-8
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

            <div class="min-w-0">

                <p
                    class="
                        text-xs
                        font-bold
                        uppercase
                        tracking-[0.32em]
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
                    "
                >
                    {{ $event->event_name }}
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
                    Assign Attendance Personnel and automatically
                    prevent conflicting assignments with overlapping
                    event schedules.
                </p>


                <div
                    class="
                        mt-5
                        flex
                        flex-wrap
                        items-center
                        gap-3
                        text-xs
                        text-white/65
                    "
                >

                    <span>
                        Event #{{ $event->event_id }}
                    </span>

                    <span>
                        •
                    </span>

                    <span>
                        {{
                            $event->event_date
                                ? $event->event_date->format('F d, Y')
                                : '—'
                        }}
                    </span>

                    <span>
                        •
                    </span>

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

                </div>

            </div>


            <a
                href="{{ route(
                    'events.personnel-assignment.events'
                ) }}"
                class="
                    inline-flex
                    shrink-0
                    items-center
                    justify-center
                    rounded-xl
                    bg-[#D4A017]
                    px-5
                    py-3
                    text-sm
                    font-bold
                    text-[#101064]
                    transition
                    hover:bg-white
                "
            >
                ← Available Events
            </a>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SUCCESS --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <div
            class="
                assignment-card
                flex
                items-center
                gap-4
                border
                border-green-200
                bg-green-50
                px-5
                py-4
            "
        >

            <div
                class="
                    flex
                    h-9
                    w-9
                    shrink-0
                    items-center
                    justify-center
                    rounded-full
                    bg-green-100
                    font-bold
                    text-green-700
                "
            >
                ✓
            </div>


            <div>

                <p
                    class="
                        text-sm
                        font-bold
                        text-green-800
                    "
                >
                    Assignments Updated
                </p>

                <p
                    class="
                        mt-1
                        text-sm
                        text-green-700
                    "
                >
                    {{ session('success') }}
                </p>

            </div>

        </div>

    @endif



    {{-- ====================================================== --}}
    {{-- ERRORS --}}
    {{-- ====================================================== --}}

    @if($errors->any())

        <div
            class="
                assignment-card
                border
                border-red-200
                bg-red-50
                px-5
                py-4
            "
        >

            <p
                class="
                    text-sm
                    font-bold
                    text-red-700
                "
            >
                Assignment could not be saved
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

    @endif



    {{-- ====================================================== --}}
    {{-- SUMMARY --}}
    {{-- ====================================================== --}}

    <section
        class="
            assignment-card
            grid
            overflow-hidden
            border
            border-gray-200
            bg-white
            sm:grid-cols-2
            xl:grid-cols-4
        "
    >

        <div
            class="
                border-b
                border-gray-100
                px-5
                py-5
                sm:border-r
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
                Attendance Personnel
            </p>

            <p
                class="
                    mt-1
                    text-2xl
                    font-bold
                    text-[#101064]
                "
            >
                {{ $personnel->count() }}
            </p>

        </div>


        <div
            class="
                border-b
                border-gray-100
                px-5
                py-5
                xl:border-r
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
                Assigned
            </p>

            <p
                class="
                    mt-1
                    text-2xl
                    font-bold
                    text-[#101064]
                "
            >
                {{ $assignedCount }}
            </p>

        </div>


        <div
            class="
                border-b
                border-gray-100
                px-5
                py-5
                sm:border-r
                sm:border-b-0
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
                Available
            </p>

            <p
                class="
                    mt-1
                    text-2xl
                    font-bold
                    text-green-600
                "
            >
                {{ $availableCount }}
            </p>

        </div>


        <div
            class="
                px-5
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
                Schedule Conflicts
            </p>

            <p
                class="
                    mt-1
                    text-2xl
                    font-bold
                    text-red-600
                "
            >
                {{ $conflictCount }}
            </p>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ASSIGNMENT --}}
    {{-- ====================================================== --}}

    <section>

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
                        font-bold
                        uppercase
                        tracking-[0.25em]
                        text-[#D4A017]
                    "
                >
                    Attendance Personnel
                </p>


                <h2
                    class="
                        mt-1
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Assign Personnel
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Select one or more personnel for this event.
                </p>

            </div>



            {{-- SEARCH --}}

            <div class="w-full lg:w-80">

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
                    Search Personnel
                </label>


                <input
                    type="text"
                    x-model="search"
                    placeholder="Search name or position..."
                    class="
                        w-full
                        rounded-lg
                        border-gray-200
                        px-4
                        py-2.5
                        text-sm
                        focus:border-[#101064]
                        focus:ring-[#101064]
                    "
                >

            </div>

        </div>



        <form
            method="POST"
            action="{{ route(
                'events.personnel-assignment.update',
                $event->event_id
            ) }}"
        >

            @csrf
            @method('PUT')


            <div
                class="
                    assignment-card
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                "
            >

                @forelse($personnel as $person)

                    @php

                        $fullName =
                            trim(
                                $person->first_name
                                . ' '
                                . $person->last_name
                            );

                        $department =
                            $person->user?->department?->department_name
                            ?? 'University-wide';

                    @endphp


                    <div
                        x-show="
                            search === ''
                            ||
                            @js(strtolower($fullName))
                                .includes(
                                    search.toLowerCase()
                                )
                            ||
                            @js(strtolower($person->position ?? ''))
                                .includes(
                                    search.toLowerCase()
                                )
                        "
                        class="
                            border-b
                            border-gray-100
                            last:border-b-0
                        "
                    >

                        <label
                            class="
                                flex
                                items-start
                                gap-5
                                px-6
                                py-5
                                transition
                                {{
                                    $person->has_schedule_conflict
                                        ? 'bg-red-50/40'
                                        : 'cursor-pointer hover:bg-gray-50'
                                }}
                            "
                        >

                            {{-- CHECKBOX --}}

                            <div class="pt-1">

                                <input
                                    type="checkbox"
                                    name="personnel_ids[]"
                                    value="{{ $person->personnel_id }}"

                                    {{
                                        $person->is_assigned
                                            ? 'checked'
                                            : ''
                                    }}

                                    {{
                                        $person->has_schedule_conflict
                                            ? 'disabled'
                                            : ''
                                    }}

                                    class="
                                        h-4
                                        w-4
                                        rounded
                                        border-gray-300
                                        text-[#101064]
                                        focus:ring-[#D4A017]
                                    "
                                >

                            </div>



                            {{-- PERSONNEL INFO --}}

                            <div
                                class="
                                    min-w-0
                                    flex-1
                                "
                            >

                                <div
                                    class="
                                        flex
                                        flex-col
                                        gap-3
                                        lg:flex-row
                                        lg:items-center
                                        lg:justify-between
                                    "
                                >

                                    <div>

                                        <p
                                            class="
                                                text-sm
                                                font-bold
                                                text-[#101064]
                                            "
                                        >
                                            {{ $fullName }}
                                        </p>


                                        <p
                                            class="
                                                mt-1
                                                text-xs
                                                text-gray-400
                                            "
                                        >
                                            {{ $person->position ?? 'Personnel' }}

                                            <span class="mx-1">
                                                •
                                            </span>

                                            {{ $department }}
                                        </p>

                                    </div>



                                    {{-- STATUS --}}

                                    <div
                                        class="
                                            flex
                                            flex-wrap
                                            items-center
                                            gap-2
                                        "
                                    >

                                        @if($person->is_assigned)

                                            <span
                                                class="
                                                    rounded-full
                                                    bg-blue-50
                                                    px-3
                                                    py-1
                                                    text-xs
                                                    font-semibold
                                                    text-[#101064]
                                                "
                                            >
                                                Assigned
                                            </span>

                                        @endif


                                        @if($person->has_schedule_conflict)

                                            <span
                                                class="
                                                    rounded-full
                                                    bg-red-50
                                                    px-3
                                                    py-1
                                                    text-xs
                                                    font-semibold
                                                    text-red-600
                                                "
                                            >
                                                Schedule Conflict
                                            </span>

                                        @else

                                            <span
                                                class="
                                                    rounded-full
                                                    bg-green-50
                                                    px-3
                                                    py-1
                                                    text-xs
                                                    font-semibold
                                                    text-green-700
                                                "
                                            >
                                                Available
                                            </span>

                                        @endif

                                    </div>

                                </div>



                                @if($person->has_schedule_conflict)

                                    <div
                                        class="
                                            mt-3
                                            rounded-lg
                                            border
                                            border-red-100
                                            bg-white
                                            px-4
                                            py-3
                                        "
                                    >

                                        <p
                                            class="
                                                text-xs
                                                leading-5
                                                text-red-600
                                            "
                                        >
                                            This personnel is already assigned
                                            to an overlapping event:

                                            <span class="font-semibold">
                                                {{
                                                    $person->conflicting_event_name
                                                    ?? 'Another event'
                                                }}
                                            </span>
                                        </p>

                                    </div>

                                @endif

                            </div>

                        </label>

                    </div>


                @empty

                    <div
                        class="
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
                                bg-amber-50
                                text-[#D4A017]
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
                                    d="M12 8v4m0 4h.01"
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
                            No Available Attendance Personnel
                        </h3>


                        <p
                            class="
                                mx-auto
                                mt-2
                                max-w-lg
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            All eligible Attendance Personnel are unavailable during
                            this event schedule or already assigned to another
                            overlapping event.
                        </p>

                    </div>

                @endforelse



                @if($personnel->isNotEmpty())

                    <div
                        class="
                            flex
                            flex-col
                            gap-4
                            border-t
                            border-gray-200
                            bg-gray-50
                            px-6
                            py-5
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
                        "
                    >

                        <p
                            class="
                                max-w-2xl
                                text-xs
                                leading-5
                                text-gray-500
                            "
                        >
                            Personnel with overlapping event schedules
                            cannot be selected. Existing assignments that
                            are unchecked will be removed when changes
                            are saved.
                        </p>


                        <button
                            type="submit"
                            class="
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
                            Save Assignments
                        </button>

                    </div>

                @endif

            </div>

        </form>

    </section>

</div>

</x-admin-layout>