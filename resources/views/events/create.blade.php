<x-admin-layout>

@php
    $currentUser = auth()->user();
@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .event-create-hero {
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

    .event-create-section {
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
            event-create-hero
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
                    Event Management
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
                    Create Event
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
                    Record an approved institutional event by providing
                    its official information, venue, and schedule.
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

                    <div class="flex items-center gap-2">

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-[#E7C75B]
                            "
                        ></span>

                        Event status starts as Upcoming

                    </div>


                    <div class="flex items-center gap-2">

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-green-400
                            "
                        ></span>

                        Department-managed event record

                    </div>

                </div>

            </div>


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
    {{-- VALIDATION ERRORS --}}
    {{-- ====================================================== --}}

    @if($errors->any())

        <section
            class="
                event-create-section
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

                    <p class="font-bold text-red-700">
                        Please review the event information
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
        action="{{ route('events.store') }}"
        class="space-y-8"
    >

        @csrf



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
                    Enter the official event name, venue, and organizing
                    department information.
                </p>

            </div>


            <div
                class="
                    event-create-section
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
                            value="{{ old('event_name') }}"
                            placeholder="Enter official event name"
                            required
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
                            value="{{ old('location') }}"
                            placeholder="Enter event venue"
                            required
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



                    {{-- ORGANIZING DEPARTMENT --}}

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
                                mb-2
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Organizing Department
                        </p>


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


                        <p
                            class="
                                mt-2
                                text-xs
                                leading-5
                                text-gray-400
                            "
                        >
                            The organizing department is automatically
                            based on your Department Staff account.
                        </p>

                    </div>



                    {{-- EVENT SETUP INFO --}}

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
                            Event Setup
                        </p>


                        <div class="mt-3">

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

                                Department Managed

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
                            Participant eligibility and attendance
                            personnel assignments are configured separately
                            after the event record is created.
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
                    Define when the event will take place.
                </p>

            </div>


            <div
                class="
                    event-create-section
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
                        value="{{ old('event_date') }}"
                        min="{{ now()->toDateString() }}"
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
                        value="{{ old('start_time') }}"
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
                        value="{{ old('end_time') }}"
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


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Add additional information about the event when needed.
                </p>

            </div>


            <div
                class="
                    event-create-section
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
                    placeholder="Provide relevant information about the event..."
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
                >{{ old('description') }}</textarea>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    Optional. Include relevant information that helps
                    describe the purpose or details of the event.
                </p>

            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- ACTION AREA --}}
        {{-- ====================================================== --}}

        <section
            class="
                event-create-section
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
                        Ready to create this event?
                    </p>


                    <p
                        class="
                            mt-1
                            text-xs
                            leading-5
                            text-gray-400
                        "
                    >
                        The event record will be created under your
                        assigned department with an Upcoming status.
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
                        href="{{ route('events.index') }}"
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

                        Create Event

                    </button>

                </div>

            </div>

        </section>


    </form>


</div>

</x-admin-layout>