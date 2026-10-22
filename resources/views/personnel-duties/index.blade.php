<x-admin-layout>

<style>
    .duty-hero {
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

<div class="w-full min-w-0 space-y-7">

    {{-- HERO --}}
    <section
        class="
            duty-hero
            relative
            overflow-hidden
            rounded-[28px]
            px-8
            py-9
            text-white
            lg:px-10
            lg:py-10
        "
    >
        <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>

        <div class="relative z-10 max-w-3xl">
            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.30em]
                    text-[#E7C75B]
                "
            >
                Personnel Duty Management
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">
                My Assigned Duties
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">
                Record your check-in and check-out for assigned events
                and monitor your duty completion status.
            </p>
        </div>
    </section>


    {{-- ALERTS --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4">
            <p class="text-sm font-bold text-green-700">
                Success
            </p>

            <p class="mt-1 text-sm text-green-600">
                {{ session('success') }}
            </p>
        </div>
    @endif


    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">
            <p class="text-sm font-bold text-red-700">
                Action could not be completed
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- SUMMARY --}}
    <section
        class="
            grid
            grid-cols-2
            overflow-hidden
            rounded-2xl
            border
            border-gray-200
            bg-white
            md:grid-cols-3
            xl:grid-cols-6
        "
    >
        @php
            $summaryCards = [
                [
                    'label' => 'Total Duties',
                    'value' => $summary['total'],
                    'color' => 'text-[#101064]',
                ],
                [
                    'label' => 'Assigned',
                    'value' => $summary['assigned'],
                    'color' => 'text-[#101064]',
                ],
                [
                    'label' => 'On Duty',
                    'value' => $summary['on_duty'],
                    'color' => 'text-blue-600',
                ],
                [
                    'label' => 'Completed',
                    'value' => $summary['completed'],
                    'color' => 'text-green-600',
                ],
                [
                    'label' => 'Missed',
                    'value' => $summary['missed'],
                    'color' => 'text-red-600',
                ],
                [
                    'label' => 'Incomplete',
                    'value' => $summary['incomplete'],
                    'color' => 'text-amber-600',
                ],
            ];
        @endphp

        @foreach($summaryCards as $card)
            <div class="border-b border-r border-gray-100 px-5 py-5">
                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    {{ $card['label'] }}
                </p>

                <p class="mt-2 text-2xl font-bold {{ $card['color'] }}">
                    {{ $card['value'] }}
                </p>
            </div>
        @endforeach
    </section>


    {{-- INFO --}}
    <section
        class="
            flex
            items-start
            gap-4
            rounded-2xl
            border
            border-blue-100
            bg-blue-50/60
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
                bg-[#101064]
                text-white
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
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
        </div>

        <div>
            <p class="text-sm font-bold text-[#101064]">
                Duty Check-In Rule
            </p>

            <p class="mt-1 text-xs leading-5 text-gray-600">
                Check-in and check-out actions become available only
                while the assigned event is ongoing.
            </p>
        </div>
    </section>


    {{-- DUTY LIST HEADER --}}
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
                Event Duties
            </p>

            <h2 class="mt-2 text-xl font-bold text-[#101064]">
                Duty Records
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Review your assigned events and duty completion history.
            </p>
        </div>


        <div class="space-y-5">

            @forelse($duties as $duty)

                @php
                    $statusClasses = match($duty->duty_status) {
                        'Completed' => 'bg-green-50 text-green-700 border-green-100',
                        'On Duty' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'Missed' => 'bg-red-50 text-red-700 border-red-100',
                        'Incomplete' => 'bg-amber-50 text-amber-700 border-amber-100',
                        default => 'bg-gray-100 text-gray-600 border-gray-200',
                    };

                    $hours =
                        $duty->completed_minutes !== null
                            ? intdiv((int) $duty->completed_minutes, 60)
                            : 0;

                    $minutes =
                        $duty->completed_minutes !== null
                            ? ((int) $duty->completed_minutes % 60)
                            : 0;
                @endphp


                <article
                    class="
                        overflow-hidden
                        rounded-2xl
                        border
                        border-gray-200
                        bg-white
                        shadow-sm
                    "
                >

                    {{-- CARD HEADER --}}
                    <div
                        class="
                            flex
                            flex-col
                            gap-4
                            border-b
                            border-gray-100
                            px-6
                            py-5
                            md:flex-row
                            md:items-center
                            md:justify-between
                        "
                    >

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        border
                                        px-3
                                        py-1
                                        text-xs
                                        font-bold
                                        {{ $statusClasses }}
                                    "
                                >
                                    {{ $duty->duty_status }}
                                </span>

                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        bg-gray-100
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-gray-600
                                    "
                                >
                                    {{ $duty->event_status }}
                                </span>
                            </div>

                            <h3
                                class="
                                    mt-3
                                    break-words
                                    text-xl
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                {{ $duty->event_name }}
                            </h3>
                        </div>


                        <div class="shrink-0">

                            @if(
                                $duty->event_status === 'Ongoing'
                                && !$duty->check_in_at
                            )
                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'personnel-duties.check-in',
                                            $duty->assignment_id
                                        )
                                    }}"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="
                                            inline-flex
                                            min-w-[150px]
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
                                        Check In
                                    </button>
                                </form>

                            @elseif(
                                $duty->event_status === 'Ongoing'
                                && $duty->check_in_at
                                && !$duty->check_out_at
                            )
                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'personnel-duties.check-out',
                                            $duty->assignment_id
                                        )
                                    }}"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="
                                            inline-flex
                                            min-w-[150px]
                                            items-center
                                            justify-center
                                            rounded-lg
                                            bg-green-600
                                            px-5
                                            py-3
                                            text-sm
                                            font-bold
                                            text-white
                                            transition
                                            hover:bg-green-700
                                        "
                                    >
                                        Check Out
                                    </button>
                                </form>

                            @elseif($duty->duty_status === 'Completed')
                                <span
                                    class="
                                        inline-flex
                                        min-w-[150px]
                                        justify-center
                                        rounded-lg
                                        bg-green-50
                                        px-4
                                        py-3
                                        text-xs
                                        font-bold
                                        text-green-700
                                    "
                                >
                                    Duty Completed
                                </span>

                            @elseif($duty->duty_status === 'Missed')
                                <span
                                    class="
                                        inline-flex
                                        min-w-[150px]
                                        justify-center
                                        rounded-lg
                                        bg-red-50
                                        px-4
                                        py-3
                                        text-xs
                                        font-bold
                                        text-red-700
                                    "
                                >
                                    Duty Missed
                                </span>

                            @elseif($duty->duty_status === 'Incomplete')
                                <span
                                    class="
                                        inline-flex
                                        min-w-[150px]
                                        justify-center
                                        rounded-lg
                                        bg-amber-50
                                        px-4
                                        py-3
                                        text-xs
                                        font-bold
                                        text-amber-700
                                    "
                                >
                                    Incomplete Duty
                                </span>

                            @else
                                <span
                                    class="
                                        inline-flex
                                        min-w-[150px]
                                        justify-center
                                        rounded-lg
                                        bg-gray-100
                                        px-4
                                        py-3
                                        text-xs
                                        font-semibold
                                        text-gray-500
                                    "
                                >
                                    Waiting for Event
                                </span>
                            @endif

                        </div>
                    </div>


                    {{-- EVENT INFORMATION --}}
                    <div
                        class="
                            grid
                            grid-cols-1
                            gap-0
                            sm:grid-cols-2
                            xl:grid-cols-4
                        "
                    >

                        <div class="border-b border-gray-100 px-6 py-5 sm:border-r">
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

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                {{
                                    \Carbon\Carbon::parse(
                                        $duty->event_date
                                    )->format('F d, Y')
                                }}
                            </p>
                        </div>


                        <div class="border-b border-gray-100 px-6 py-5 xl:border-r">
                            <p
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Event Time
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                {{
                                    \Carbon\Carbon::parse(
                                        $duty->start_time
                                    )->format('g:i A')
                                }}
                                –
                                {{
                                    \Carbon\Carbon::parse(
                                        $duty->end_time
                                    )->format('g:i A')
                                }}
                            </p>
                        </div>


                        <div class="border-b border-gray-100 px-6 py-5 sm:border-r">
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

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                {{ $duty->location ?? 'Not specified' }}
                            </p>
                        </div>


                        <div class="border-b border-gray-100 px-6 py-5">
                            <p
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Completed Duty Time
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                @if($duty->completed_minutes !== null)

                                    @if($hours > 0)
                                        {{ $hours }} hr
                                    @endif

                                    {{ $minutes }} min

                                @else
                                    —
                                @endif
                            </p>
                        </div>

                    </div>


                    {{-- CHECK IN / CHECK OUT --}}
                    <div
                        class="
                            grid
                            grid-cols-1
                            bg-gray-50/60
                            md:grid-cols-2
                        "
                    >

                        <div class="border-b border-gray-100 px-6 py-5 md:border-b-0 md:border-r">
                            <p
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Check-In Record
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                @if($duty->check_in_at)
                                    {{
                                        \Carbon\Carbon::parse(
                                            $duty->check_in_at
                                        )->format('F d, Y · g:i A')
                                    }}
                                @else
                                    Not recorded
                                @endif
                            </p>
                        </div>


                        <div class="px-6 py-5">
                            <p
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Check-Out Record
                            </p>

                            <p class="mt-2 text-sm font-semibold text-gray-700">
                                @if($duty->check_out_at)
                                    {{
                                        \Carbon\Carbon::parse(
                                            $duty->check_out_at
                                        )->format('F d, Y · g:i A')
                                    }}
                                @else
                                    Not recorded
                                @endif
                            </p>
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

                    <h3 class="mt-4 font-bold text-[#101064]">
                        No Assigned Duties
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Your assigned event duties will appear here.
                    </p>
                </div>

            @endforelse

        </div>
    </section>

</div>

</x-admin-layout>