<x-admin-layout>

<style>

    .eligibility-hero {
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

    .eligibility-card {
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
            eligibility-hero
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
                max-w-3xl
            "
        >

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
                    text-3xl
                    font-bold
                    tracking-tight
                    md:text-4xl
                "
            >
                Participant Eligibility
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
                Select an upcoming event and configure which students
                are required or allowed to participate.
            </p>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- AVAILABLE EVENTS --}}
    {{-- ====================================================== --}}

    <section>

        <div class="mb-5">

            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.28em]
                    text-[#D4A017]
                "
            >
                Event Selection
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Available Events
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Eligibility can be configured for upcoming events
                within your department.
            </p>

        </div>



        <div class="space-y-4">

            @forelse($events as $event)

                <div
                    class="
                        eligibility-card
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
                            gap-6
                            px-6
                            py-6
                            lg:flex-row
                            lg:items-center
                            lg:justify-between
                        "
                    >

                        {{-- EVENT INFO --}}

                        <div class="min-w-0">

                            <div
                                class="
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-2
                                "
                            >

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


                                <span
                                    class="
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    Event #{{ $event->event_id }}
                                </span>

                            </div>


                            <h3
                                class="
                                    mt-3
                                    break-words
                                    text-lg
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                {{ $event->event_name }}
                            </h3>


                            <div
                                class="
                                    mt-3
                                    flex
                                    flex-wrap
                                    gap-x-5
                                    gap-y-2
                                    text-sm
                                    text-gray-500
                                "
                            >

                                <span>
                                    {{
                                        \Carbon\Carbon::parse(
                                            $event->event_date
                                        )->format('F d, Y')
                                    }}
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


                                <span>
                                    {{ $event->location }}
                                </span>

                            </div>


                            <p
                                class="
                                    mt-2
                                    text-xs
                                    text-gray-400
                                "
                            >
                                {{
                                    $event->department?->department_name
                                    ?? 'University-wide'
                                }}
                            </p>

                        </div>



                        {{-- COUNTS + ACTION --}}

                        <div
                            class="
                                flex
                                shrink-0
                                flex-col
                                gap-4
                                sm:flex-row
                                sm:items-center
                            "
                        >

                            <div
                                class="
                                    flex
                                    gap-6
                                    rounded-xl
                                    bg-gray-50
                                    px-5
                                    py-3
                                "
                            >

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
                                        Rules
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        {{
                                            $event->eligibility_rules_count
                                        }}
                                    </p>

                                </div>


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
                                        Eligible
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        {{
                                            $event->eligible_students_count
                                        }}
                                    </p>

                                </div>

                            </div>


                            <a
                                href="{{ route(
                                    'events.eligibility.index',
                                    $event->event_id
                                ) }}"
                                class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    gap-2
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
                                Configure Eligibility

                                <span>
                                    →
                                </span>
                            </a>

                        </div>

                    </div>

                </div>


            @empty

                <div
                    class="
                        eligibility-card
                        border
                        border-gray-200
                        bg-white
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
                        No upcoming events available
                    </h3>


                    <p
                        class="
                            mx-auto
                            mt-2
                            max-w-lg
                            text-sm
                            leading-6
                            text-gray-400
                        "
                    >
                        Create an upcoming event first before
                        configuring participant eligibility.
                    </p>


                    <a
                        href="{{ route('events.index') }}"
                        class="
                            mt-5
                            inline-flex
                            rounded-lg
                            bg-[#101064]
                            px-5
                            py-2.5
                            text-sm
                            font-semibold
                            text-white
                            transition
                            hover:bg-[#D4A017]
                            hover:text-[#101064]
                        "
                    >
                        View Events
                    </a>

                </div>

            @endforelse

        </div>

    </section>


</div>

</x-admin-layout>