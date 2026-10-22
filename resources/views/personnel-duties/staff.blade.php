<x-admin-layout>

<style>
    .duty-monitoring-hero {
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
            duty-monitoring-hero
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
                Personnel Duty Monitoring
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">
                Attendance Personnel Duties
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">
                Monitor duty attendance, check-in and check-out records,
                completed duty time, missed duties, and incomplete duties.
            </p>
        </div>
    </section>


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
            $cards = [
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

        @foreach($cards as $card)
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


    {{-- AUTOMATION INFO --}}
    <section
        class="
            flex
            flex-col
            gap-4
            rounded-2xl
            border
            border-blue-100
            bg-blue-50/60
            px-5
            py-4
            sm:flex-row
            sm:items-center
            sm:justify-between
        "
    >
        <div>
            <p class="text-sm font-bold text-[#101064]">
                Automatic Duty Status
            </p>

            <p class="mt-1 text-xs leading-5 text-gray-600">
                DySign automatically determines whether personnel are
                Assigned, On Duty, Completed, Missed, or Incomplete.
            </p>
        </div>

        <span
            class="
                w-fit
                rounded-full
                bg-white
                px-3
                py-1.5
                text-xs
                font-bold
                text-[#101064]
            "
        >
            View Only
        </span>
    </section>


    {{-- FILTERS --}}
    <section
        class="
            overflow-hidden
            rounded-2xl
            border
            border-gray-200
            bg-white
        "
    >
        <div class="border-b border-gray-100 px-6 py-5">
            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.25em]
                    text-[#D4A017]
                "
            >
                Records Filter
            </p>

            <h2 class="mt-2 text-lg font-bold text-[#101064]">
                Filter Duty Records
            </h2>
        </div>


        <form
            method="GET"
            action="{{ route('personnel-duties.staff') }}"
            class="
                grid
                grid-cols-1
                gap-5
                px-6
                py-5
                md:grid-cols-3
                md:items-end
            "
        >

            <div>
                <label
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Event
                </label>

                <select
                    name="event"
                    class="
                        mt-2
                        block
                        w-full
                        rounded-lg
                        border-gray-200
                        bg-white
                        text-sm
                        focus:border-[#101064]
                        focus:ring-[#101064]
                    "
                >
                    <option value="">
                        All Events
                    </option>

                    @foreach($events as $event)
                        <option
                            value="{{ $event->event_id }}"
                            @selected(
                                request('event') == $event->event_id
                            )
                        >
                            {{ $event->event_name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div>
                <label
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Duty Status
                </label>

                <select
                    name="status"
                    class="
                        mt-2
                        block
                        w-full
                        rounded-lg
                        border-gray-200
                        bg-white
                        text-sm
                        focus:border-[#101064]
                        focus:ring-[#101064]
                    "
                >
                    <option value="">
                        All Statuses
                    </option>

                    @foreach([
                        'Assigned',
                        'On Duty',
                        'Completed',
                        'Missed',
                        'Incomplete'
                    ] as $status)
                        <option
                            value="{{ $status }}"
                            @selected(request('status') === $status)
                        >
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    class="
                        flex-1
                        rounded-lg
                        bg-[#101064]
                        px-5
                        py-2.5
                        text-sm
                        font-bold
                        text-white
                        transition
                        hover:bg-[#D4A017]
                        hover:text-[#101064]
                    "
                >
                    Apply Filter
                </button>

                <a
                    href="{{ route('personnel-duties.staff') }}"
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
                    Clear
                </a>
            </div>

        </form>
    </section>


    {{-- RECORDS --}}
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
                Duty Records
            </p>

            <h2 class="mt-2 text-xl font-bold text-[#101064]">
                Personnel Monitoring
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Review Attendance Personnel duty performance for your
                department events.
            </p>
        </div>


        <div
            class="
                w-full
                overflow-hidden
                rounded-2xl
                border
                border-gray-200
                bg-white
            "
        >

            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[1050px]">

                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Personnel
                            </th>

                            <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Event
                            </th>

                            <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Schedule
                            </th>

                            <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Check-In
                            </th>

                            <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Check-Out
                            </th>

                            <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Duty Time
                            </th>

                            <th class="px-5 py-4 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Status
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($duties as $duty)

                            @php
                                $statusClasses = match($duty->duty_status) {
                                    'Completed' => 'bg-green-50 text-green-700',
                                    'On Duty' => 'bg-blue-50 text-blue-700',
                                    'Missed' => 'bg-red-50 text-red-700',
                                    'Incomplete' => 'bg-amber-50 text-amber-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };

                                $hours =
                                    $duty->completed_minutes !== null
                                        ? intdiv(
                                            (int) $duty->completed_minutes,
                                            60
                                        )
                                        : 0;

                                $minutes =
                                    $duty->completed_minutes !== null
                                        ? ((int) $duty->completed_minutes % 60)
                                        : 0;
                            @endphp


                            <tr class="transition hover:bg-gray-50/60">

                                {{-- PERSONNEL --}}
                                <td class="px-5 py-5 align-top">
                                    <p class="whitespace-nowrap font-bold text-[#101064]">
                                        {{
                                            trim(
                                                $duty->first_name
                                                . ' '
                                                . $duty->last_name
                                            )
                                        }}
                                    </p>

                                    <p class="mt-1 whitespace-nowrap text-xs text-gray-400">
                                        {{
                                            $duty->position
                                            ?? 'Attendance Personnel'
                                        }}
                                    </p>
                                </td>


                                {{-- EVENT --}}
                                <td class="px-5 py-5 align-top">
                                    <p class="font-semibold text-gray-700">
                                        {{ $duty->event_name }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $duty->location ?? 'No location' }}
                                    </p>
                                </td>


                                {{-- SCHEDULE --}}
                                <td class="px-5 py-5 align-top">
                                    <p class="whitespace-nowrap text-sm font-semibold text-gray-700">
                                        {{
                                            \Carbon\Carbon::parse(
                                                $duty->event_date
                                            )->format('M d, Y')
                                        }}
                                    </p>

                                    <p class="mt-1 whitespace-nowrap text-xs text-gray-400">
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
                                </td>


                                {{-- CHECK-IN --}}
                                <td class="px-5 py-5 align-top">
                                    <p class="whitespace-nowrap text-sm text-gray-600">
                                        @if($duty->check_in_at)
                                            {{
                                                \Carbon\Carbon::parse(
                                                    $duty->check_in_at
                                                )->format('g:i A')
                                            }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </td>


                                {{-- CHECK-OUT --}}
                                <td class="px-5 py-5 align-top">
                                    <p class="whitespace-nowrap text-sm text-gray-600">
                                        @if($duty->check_out_at)
                                            {{
                                                \Carbon\Carbon::parse(
                                                    $duty->check_out_at
                                                )->format('g:i A')
                                            }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </td>


                                {{-- DUTY TIME --}}
                                <td class="px-5 py-5 align-top">
                                    <p class="whitespace-nowrap text-sm font-semibold text-gray-700">
                                        @if($duty->completed_minutes !== null)

                                            @if($hours > 0)
                                                {{ $hours }} hr
                                            @endif

                                            {{ $minutes }} min

                                        @else
                                            —
                                        @endif
                                    </p>
                                </td>


                                {{-- STATUS --}}
                                <td class="px-5 py-5 align-top">
                                    <span
                                        class="
                                            inline-flex
                                            whitespace-nowrap
                                            rounded-full
                                            px-3
                                            py-1
                                            text-xs
                                            font-bold
                                            {{ $statusClasses }}
                                        "
                                    >
                                        {{ $duty->duty_status }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
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
                                        No Duty Records Found
                                    </h3>

                                    <p class="mt-2 text-sm text-gray-500">
                                        Attendance Personnel duty records will
                                        appear here once personnel are assigned.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </section>

</div>

</x-admin-layout>