<x-admin-layout>

<style>
    .personnel-availability-hero {
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
            personnel-availability-hero
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
                Personnel Planning
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
                Attendance Personnel Availability
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
                Review unavailable periods before assigning Attendance
                Personnel to an event.
            </p>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- AVAILABILITY RULE --}}
    {{-- ====================================================== --}}

    <section
        class="
            flex
            flex-col
            gap-4
            rounded-2xl
            border
            border-blue-100
            bg-blue-50
            px-5
            py-4
            sm:flex-row
            sm:items-center
            sm:justify-between
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
                Availability Rule
            </p>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-600
                "
            >
                Personnel are considered available unless an unavailable
                period overlaps the event schedule.
            </p>

        </div>


        <span
            class="
                w-fit
                rounded-full
                bg-white
                px-4
                py-2
                text-xs
                font-bold
                text-[#101064]
            "
        >
            View Only
        </span>

    </section>



    {{-- ====================================================== --}}
    {{-- DATE FILTER --}}
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
                Schedule Filter
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                View Personnel Availability
            </h2>

        </div>


        <form
            method="GET"
            action="{{ route('personnel-availability.staff') }}"
            class="
                flex
                flex-col
                gap-4
                p-5
                sm:flex-row
                sm:items-end
            "
        >

            <div>

                <label
                    for="date"
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Schedule Date
                </label>


                <input
                    id="date"
                    type="date"
                    name="date"
                    value="{{ $selectedDate }}"
                    required
                    class="
                        mt-2
                        rounded-lg
                        border-gray-200
                        text-sm
                        focus:border-[#101064]
                        focus:ring-[#101064]
                    "
                >

            </div>


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
                View Schedule
            </button>

        </form>

    </section>



    {{-- ====================================================== --}}
    {{-- PERSONNEL --}}
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
                {{
                    \Carbon\Carbon::parse(
                        $selectedDate
                    )->format('F d, Y')
                }}
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Personnel Schedule
            </h2>


            <p class="mt-1 text-sm text-gray-500">
                Review declared unavailable periods for Attendance Personnel.
            </p>

        </div>


        <div class="space-y-4">

            @forelse($personnel as $person)

                <article
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
                            grid
                            grid-cols-1
                            lg:grid-cols-[280px_1fr]
                        "
                    >

                        {{-- PERSONNEL INFORMATION --}}

                        <div
                            class="
                                border-b
                                border-gray-100
                                p-6
                                lg:border-b-0
                                lg:border-r
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


                            <h3
                                class="
                                    mt-2
                                    text-lg
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                {{ $person->first_name }}
                                {{ $person->last_name }}
                            </h3>


                            @if($person->position)

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $person->position }}
                                </p>

                            @endif


                            <p
                                class="
                                    mt-3
                                    text-xs
                                    font-semibold
                                    text-gray-400
                                "
                            >
                                {{
                                    $person
                                        ->user
                                        ?->department
                                        ?->department_name
                                    ?? 'University-wide Personnel'
                                }}
                            </p>

                        </div>



                        {{-- AVAILABILITY INFORMATION --}}

                        <div class="p-6">

                            @if($person->availabilities->isEmpty())

                                <div
                                    class="
                                        flex
                                        flex-col
                                        gap-3
                                        rounded-xl
                                        border
                                        border-green-100
                                        bg-green-50
                                        px-5
                                        py-4
                                        sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                    "
                                >

                                    <div>

                                        <p
                                            class="
                                                text-sm
                                                font-bold
                                                text-green-700
                                            "
                                        >
                                            No unavailable period declared
                                        </p>


                                        <p
                                            class="
                                                mt-1
                                                text-xs
                                                text-green-600
                                            "
                                        >
                                            This personnel is considered available
                                            for the selected date.
                                        </p>

                                    </div>


                                    <span
                                        class="
                                            w-fit
                                            rounded-full
                                            bg-white
                                            px-3
                                            py-1
                                            text-xs
                                            font-bold
                                            text-green-700
                                        "
                                    >
                                        Available
                                    </span>

                                </div>


                            @else

                                <div>

                                    <div
                                        class="
                                            mb-4
                                            flex
                                            flex-wrap
                                            items-center
                                            justify-between
                                            gap-3
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
                                                Unavailable Periods
                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-gray-500
                                                "
                                            >
                                                Personnel cannot be assigned during these periods.
                                            </p>

                                        </div>


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
                                            {{ $person->availabilities->count() }}
                                            {{
                                                \Illuminate\Support\Str::plural(
                                                    'period',
                                                    $person->availabilities->count()
                                                )
                                            }}
                                        </span>

                                    </div>


                                    <div class="space-y-3">

                                        @foreach(
                                            $person->availabilities
                                            as $availability
                                        )

                                            <div
                                                class="
                                                    flex
                                                    flex-col
                                                    gap-3
                                                    rounded-xl
                                                    border
                                                    border-red-100
                                                    bg-red-50/50
                                                    px-4
                                                    py-4
                                                    sm:flex-row
                                                    sm:items-center
                                                    sm:justify-between
                                                "
                                            >

                                                <div>

                                                    <p
                                                        class="
                                                            text-sm
                                                            font-bold
                                                            text-gray-700
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
                                                                mt-1
                                                                text-xs
                                                                text-gray-500
                                                            "
                                                        >
                                                            {{ $availability->reason }}
                                                        </p>

                                                    @endif

                                                </div>


                                                <span
                                                    class="
                                                        w-fit
                                                        rounded-full
                                                        bg-white
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

                                        @endforeach

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </article>


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

                    <h3
                        class="
                            font-bold
                            text-[#101064]
                        "
                    >
                        No Attendance Personnel found
                    </h3>


                    <p class="mt-2 text-sm text-gray-500">
                        There are currently no eligible Attendance Personnel
                        accounts for your department.
                    </p>

                </div>

            @endforelse

        </div>

    </section>

</div>

</x-admin-layout>