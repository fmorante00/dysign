<x-admin-layout>

<style>
    .availability-hero {
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
            availability-hero
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


        <div class="relative z-10 max-w-3xl">

            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.35em]
                    text-[#E7C75B]
                "
            >
                Attendance Personnel
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
                Availability Management
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
                Record only the periods when you are unavailable for
                attendance duties. Any time not listed here is treated
                as available.
            </p>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <div
            class="
                rounded-xl
                border
                border-green-200
                bg-green-50
                px-5
                py-4
                text-sm
                font-semibold
                text-green-700
            "
        >
            {{ session('success') }}
        </div>

    @endif



    {{-- ====================================================== --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ====================================================== --}}

    @if($errors->any())

        <div
            class="
                rounded-xl
                border
                border-red-200
                bg-red-50
                px-5
                py-4
                text-sm
                text-red-700
            "
        >

            <p class="font-bold">
                Please review the following:
            </p>


            <ul class="mt-2 list-disc space-y-1 pl-5">

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
            grid
            grid-cols-1
            gap-4
            md:grid-cols-3
        "
    >

        {{-- DEFAULT STATUS --}}

        <div
            class="
                rounded-2xl
                border
                border-gray-200
                bg-white
                p-5
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
                Default Status
            </p>


            <div class="mt-4 flex items-center gap-3">

                <span
                    class="
                        h-3
                        w-3
                        rounded-full
                        bg-green-500
                    "
                ></span>


                <p
                    class="
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Available
                </p>

            </div>


            <p
                class="
                    mt-2
                    text-xs
                    leading-5
                    text-gray-500
                "
            >
                You are considered available unless you declare an
                unavailable period.
            </p>

        </div>



        {{-- UPCOMING COUNT --}}

        <div
            class="
                rounded-2xl
                border
                border-gray-200
                bg-white
                p-5
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
                Upcoming Unavailable Periods
            </p>


            <p
                class="
                    mt-4
                    text-3xl
                    font-bold
                    text-[#101064]
                "
            >
                {{ $upcomingAvailabilities->count() }}
            </p>


            <p class="mt-2 text-xs text-gray-500">
                Future periods currently marked as unavailable.
            </p>

        </div>



        {{-- RULE --}}

        <div
            class="
                rounded-2xl
                border
                border-gray-200
                bg-white
                p-5
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
                Scheduling Rule
            </p>


            <p
                class="
                    mt-4
                    text-sm
                    font-semibold
                    leading-6
                    text-gray-700
                "
            >
                Unlisted time periods remain available for assignment.
            </p>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- CREATE UNAVAILABLE PERIOD --}}
    {{-- ====================================================== --}}

    <section
        class="
            overflow-hidden
            rounded-2xl
            border
            border-gray-200
            bg-white
        "
    >

        <div
            class="
                border-b
                border-gray-100
                px-6
                py-5
            "
        >

            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.28em]
                    text-[#D4A017]
                "
            >
                New Schedule
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Mark Unavailable Time
            </h2>


            <p class="mt-1 text-sm text-gray-500">
                Specify only the period when you cannot be assigned to
                attendance duty.
            </p>

        </div>


        <form
            method="POST"
            action="{{ route('personnel-availability.store') }}"
        >

            @csrf


            <div
                class="
                    grid
                    grid-cols-1
                    lg:grid-cols-3
                "
            >

                {{-- DATE --}}

                <div
                    class="
                        border-b
                        border-gray-100
                        p-5
                        lg:border-b-0
                        lg:border-r
                    "
                >

                    <label
                        for="availability_date"
                        class="
                            text-[10px]
                            font-bold
                            uppercase
                            tracking-wider
                            text-gray-400
                        "
                    >
                        Date
                    </label>


                    <input
                        id="availability_date"
                        type="date"
                        name="availability_date"
                        value="{{ old('availability_date') }}"
                        min="{{ now('Asia/Manila')->toDateString() }}"
                        required
                        class="
                            mt-2
                            w-full
                            rounded-lg
                            border-gray-200
                            text-sm
                            focus:border-[#101064]
                            focus:ring-[#101064]
                        "
                    >

                </div>



                {{-- START --}}

                <div
                    class="
                        border-b
                        border-gray-100
                        p-5
                        lg:border-b-0
                        lg:border-r
                    "
                >

                    <label
                        for="start_time"
                        class="
                            text-[10px]
                            font-bold
                            uppercase
                            tracking-wider
                            text-gray-400
                        "
                    >
                        Unavailable From
                    </label>


                    <input
                        id="start_time"
                        type="time"
                        name="start_time"
                        value="{{ old('start_time') }}"
                        required
                        class="
                            mt-2
                            w-full
                            rounded-lg
                            border-gray-200
                            text-sm
                            focus:border-[#101064]
                            focus:ring-[#101064]
                        "
                    >

                </div>



                {{-- END --}}

                <div class="p-5">

                    <label
                        for="end_time"
                        class="
                            text-[10px]
                            font-bold
                            uppercase
                            tracking-wider
                            text-gray-400
                        "
                    >
                        Unavailable Until
                    </label>


                    <input
                        id="end_time"
                        type="time"
                        name="end_time"
                        value="{{ old('end_time') }}"
                        required
                        class="
                            mt-2
                            w-full
                            rounded-lg
                            border-gray-200
                            text-sm
                            focus:border-[#101064]
                            focus:ring-[#101064]
                        "
                    >

                </div>

            </div>



            {{-- REASON --}}

            <div
                class="
                    border-t
                    border-gray-100
                    p-5
                "
            >

                <label
                    for="reason"
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Reason
                    <span
                        class="
                            font-normal
                            normal-case
                        "
                    >
                        (optional)
                    </span>
                </label>


                <textarea
                    id="reason"
                    name="reason"
                    rows="3"
                    placeholder="Example: Meeting, class schedule, official duty, or personal commitment..."
                    class="
                        mt-2
                        w-full
                        rounded-lg
                        border-gray-200
                        text-sm
                        focus:border-[#101064]
                        focus:ring-[#101064]
                    "
                >{{ old('reason') }}</textarea>

            </div>



            {{-- SUBMIT --}}

            <div
                class="
                    flex
                    justify-end
                    border-t
                    border-gray-100
                    bg-gray-50
                    px-5
                    py-4
                "
            >

                <button
                    type="submit"
                    class="
                        rounded-lg
                        bg-[#101064]
                        px-6
                        py-2.5
                        text-sm
                        font-bold
                        text-white
                        transition
                        hover:bg-[#D4A017]
                        hover:text-[#101064]
                    "
                >
                    Add Unavailable Time
                </button>

            </div>

        </form>

    </section>



    {{-- ====================================================== --}}
    {{-- UPCOMING --}}
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
                My Schedule
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Upcoming Unavailable Periods
            </h2>


            <p class="mt-1 text-sm text-gray-500">
                These periods will be considered during event personnel assignment.
            </p>

        </div>


        <div class="space-y-4">

            @forelse($upcomingAvailabilities as $availability)

                <div
                    class="
                        overflow-hidden
                        rounded-2xl
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
                            p-6
                            lg:flex-row
                            lg:items-center
                            lg:justify-between
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
                                    rounded-xl
                                    bg-red-50
                                    text-red-600
                                "
                            >

                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"
                                    />
                                </svg>

                            </div>


                            <div class="min-w-0">

                                <div
                                    class="
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-3
                                    "
                                >

                                    <h3
                                        class="
                                            text-lg
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        {{
                                            $availability
                                                ->availability_date
                                                ->format('F d, Y')
                                        }}
                                    </h3>


                                    <span
                                        class="
                                            rounded-full
                                            bg-red-50
                                            px-3
                                            py-1
                                            text-xs
                                            font-bold
                                            text-red-600
                                        "
                                    >
                                        Unavailable
                                    </span>

                                </div>


                                <p
                                    class="
                                        mt-2
                                        text-sm
                                        font-semibold
                                        text-gray-600
                                    "
                                >
                                    {{
                                        \Carbon\Carbon::parse(
                                            $availability->start_time
                                        )->format('g:i A')
                                    }}

                                    –

                                    {{
                                        \Carbon\Carbon::parse(
                                            $availability->end_time
                                        )->format('g:i A')
                                    }}
                                </p>


                                @if($availability->reason)

                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            text-gray-500
                                        "
                                    >
                                        {{ $availability->reason }}
                                    </p>

                                @endif

                            </div>

                        </div>



                        {{-- ACTIONS --}}

                        <div class="flex flex-wrap gap-2">

                            <details>

                                <summary
                                    class="
                                        cursor-pointer
                                        list-none
                                        rounded-lg
                                        border
                                        border-gray-200
                                        px-4
                                        py-2
                                        text-sm
                                        font-semibold
                                        text-[#101064]
                                        transition
                                        hover:border-[#101064]
                                    "
                                >
                                    Edit
                                </summary>


                                <div
                                    class="
                                        mt-4
                                        rounded-xl
                                        border
                                        border-gray-200
                                        bg-gray-50
                                        p-4
                                    "
                                >

                                    <form
                                        method="POST"
                                        action="{{
                                            route(
                                                'personnel-availability.update',
                                                $availability->availability_id
                                            )
                                        }}"
                                        class="
                                            grid
                                            grid-cols-1
                                            gap-4
                                            sm:grid-cols-2
                                        "
                                    >

                                        @csrf
                                        @method('PUT')


                                        <div class="sm:col-span-2">

                                            <label
                                                class="
                                                    text-[10px]
                                                    font-bold
                                                    uppercase
                                                    text-gray-400
                                                "
                                            >
                                                Date
                                            </label>


                                            <input
                                                type="date"
                                                name="availability_date"
                                                value="{{
                                                    $availability
                                                        ->availability_date
                                                        ->format('Y-m-d')
                                                }}"
                                                min="{{
                                                    now('Asia/Manila')
                                                        ->toDateString()
                                                }}"
                                                required
                                                class="
                                                    mt-1
                                                    w-full
                                                    rounded-lg
                                                    border-gray-200
                                                    text-sm
                                                "
                                            >

                                        </div>


                                        <div>

                                            <label
                                                class="
                                                    text-[10px]
                                                    font-bold
                                                    uppercase
                                                    text-gray-400
                                                "
                                            >
                                                From
                                            </label>


                                            <input
                                                type="time"
                                                name="start_time"
                                                value="{{
                                                    \Carbon\Carbon::parse(
                                                        $availability->start_time
                                                    )->format('H:i')
                                                }}"
                                                required
                                                class="
                                                    mt-1
                                                    w-full
                                                    rounded-lg
                                                    border-gray-200
                                                    text-sm
                                                "
                                            >

                                        </div>


                                        <div>

                                            <label
                                                class="
                                                    text-[10px]
                                                    font-bold
                                                    uppercase
                                                    text-gray-400
                                                "
                                            >
                                                Until
                                            </label>


                                            <input
                                                type="time"
                                                name="end_time"
                                                value="{{
                                                    \Carbon\Carbon::parse(
                                                        $availability->end_time
                                                    )->format('H:i')
                                                }}"
                                                required
                                                class="
                                                    mt-1
                                                    w-full
                                                    rounded-lg
                                                    border-gray-200
                                                    text-sm
                                                "
                                            >

                                        </div>


                                        <div class="sm:col-span-2">

                                            <label
                                                class="
                                                    text-[10px]
                                                    font-bold
                                                    uppercase
                                                    text-gray-400
                                                "
                                            >
                                                Reason
                                            </label>


                                            <textarea
                                                name="reason"
                                                rows="2"
                                                class="
                                                    mt-1
                                                    w-full
                                                    rounded-lg
                                                    border-gray-200
                                                    text-sm
                                                "
                                            >{{ $availability->reason }}</textarea>

                                        </div>


                                        <button
                                            type="submit"
                                            class="
                                                rounded-lg
                                                bg-[#101064]
                                                px-5
                                                py-2.5
                                                text-sm
                                                font-bold
                                                text-white
                                                sm:col-span-2
                                            "
                                        >
                                            Save Changes
                                        </button>

                                    </form>

                                </div>

                            </details>



                            <form
                                method="POST"
                                action="{{
                                    route(
                                        'personnel-availability.destroy',
                                        $availability->availability_id
                                    )
                                }}"
                                onsubmit="
                                    return confirm(
                                        'Remove this unavailable schedule?'
                                    );
                                "
                            >

                                @csrf
                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="
                                        rounded-lg
                                        border
                                        border-red-200
                                        px-4
                                        py-2
                                        text-sm
                                        font-semibold
                                        text-red-600
                                        transition
                                        hover:bg-red-50
                                    "
                                >
                                    Remove
                                </button>

                            </form>

                        </div>

                    </div>

                </div>


            @empty

                <div
                    class="
                        rounded-2xl
                        border
                        border-dashed
                        border-gray-300
                        bg-white
                        px-6
                        py-14
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
                            bg-green-50
                            text-xl
                            font-bold
                            text-green-600
                        "
                    >
                        ✓
                    </div>


                    <h3
                        class="
                            mt-4
                            font-bold
                            text-[#101064]
                        "
                    >
                        No unavailable periods
                    </h3>


                    <p class="mt-2 text-sm text-gray-500">
                        You are currently considered available for future assignments.
                    </p>

                </div>

            @endforelse

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- HISTORY --}}
    {{-- ====================================================== --}}

    @if($pastAvailabilities->isNotEmpty())

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
                    History
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Previous Unavailable Periods
                </h2>

            </div>


            <div
                class="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-gray-200
                    bg-white
                "
            >

                @foreach($pastAvailabilities as $availability)

                    <div
                        class="
                            flex
                            flex-col
                            gap-3
                            border-b
                            border-gray-100
                            px-6
                            py-4
                            last:border-b-0
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
                        "
                    >

                        <div>

                            <p class="font-bold text-gray-700">
                                {{
                                    $availability
                                        ->availability_date
                                        ->format('F d, Y')
                                }}
                            </p>


                            <p class="mt-1 text-sm text-gray-500">

                                {{
                                    \Carbon\Carbon::parse(
                                        $availability->start_time
                                    )->format('g:i A')
                                }}

                                –

                                {{
                                    \Carbon\Carbon::parse(
                                        $availability->end_time
                                    )->format('g:i A')
                                }}

                            </p>

                        </div>


                        <span
                            class="
                                rounded-full
                                bg-gray-100
                                px-3
                                py-1
                                text-xs
                                font-semibold
                                text-gray-500
                            "
                        >
                            Past Record
                        </span>

                    </div>

                @endforeach

            </div>

        </section>

    @endif

</div>

</x-admin-layout>