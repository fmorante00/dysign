<x-admin-layout>

<style>

    /*

    |--------------------------------------------------------------------------

    | HERO

    |--------------------------------------------------------------------------

    */

    .participation-report-hero {

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

            participation-report-hero

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

                flex-col

                gap-8

                lg:flex-row

                lg:items-center

                lg:justify-between

            "

        >

            <div class="max-w-3xl">

                <p

                    class="

                        text-xs

                        font-semibold

                        uppercase

                        tracking-[0.32em]

                        text-[#E7C75B]

                    "

                >

                    Reports & Analytics

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

                    Participation Reports

                </h1>

                <p

                    class="

                        mt-3

                        max-w-2xl

                        text-sm

                        leading-6

                        text-white/70

                    "

                >

                    Generate and review student participation reports

                    based on finalized participation records and

                    evaluation results.

                </p>

            </div>

            <div

                class="

                    hidden

                    shrink-0

                    items-center

                    gap-4

                    rounded-2xl

                    border

                    border-white/15

                    bg-white/10

                    px-6

                    py-5

                    backdrop-blur-sm

                    lg:flex

                "

            >

                <div

                    class="

                        flex

                        h-11

                        w-11

                        items-center

                        justify-center

                        rounded-xl

                        bg-white/10

                        text-[#E7C75B]

                    "

                >

                    <svg

                        class="h-6 w-6"

                        fill="none"

                        stroke="currentColor"

                        viewBox="0 0 24 24"

                    >

                        <path

                            stroke-width="1.8"

                            stroke-linecap="round"

                            stroke-linejoin="round"

                            d="M4 19h16M6 16V9m4 7V5m4 11v-4m4 4V7"

                        />

                    </svg>

                </div>

                <div>

                    <p

                        class="

                            text-[10px]

                            font-semibold

                            uppercase

                            tracking-[0.22em]

                            text-[#E7C75B]

                        "

                    >

                        Report Type

                    </p>

                    <p

                        class="

                            mt-1

                            text-sm

                            font-semibold

                            text-white

                        "

                    >

                        Student Participation

                    </p>

                </div>

            </div>

        </div>

    </section>

    {{-- ====================================================== --}}

    {{-- SUMMARY --}}

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

                Report Overview

            </p>

            <h2

                class="

                    mt-2

                    text-xl

                    font-bold

                    text-[#101064]

                "

            >

                Participation Summary

            </h2>

            <p

                class="

                    mt-1

                    text-sm

                    text-gray-500

                "

            >

                Summary of student participation evaluation results.

            </p>

        </div>

        <div

            class="

                grid

                grid-cols-1

                overflow-hidden

                border

                border-gray-200

                bg-white

                sm:grid-cols-2

                xl:grid-cols-4

            "

        >

            {{-- TOTAL STUDENTS --}}

            <div

                class="

                    border-b

                    border-gray-100

                    px-6

                    py-6

                    sm:border-r

                    xl:border-b-0

                "

            >

                <div

                    class="

                        flex

                        items-start

                        justify-between

                        gap-4

                    "

                >

                    <div>

                        <p

                            class="

                                text-[10px]

                                font-bold

                                uppercase

                                tracking-[0.16em]

                                text-gray-400

                            "

                        >

                            Total Students Evaluated

                        </p>

                        <p

                            class="

                                mt-2

                                text-3xl

                                font-bold

                                tracking-tight

                                text-[#101064]

                            "

                        >

                            {{ $summary['total'] }}

                        </p>

                    </div>

                    <div

                        class="

                            flex

                            h-10

                            w-10

                            shrink-0

                            items-center

                            justify-center

                            rounded-xl

                            bg-[#F1F2FA]

                            text-[#101064]

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

                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm7 10v-2a4 4 0 00-3-3.87"

                            />

                        </svg>

                    </div>

                </div>

            </div>

            {{-- HIGHLY PARTICIPATIVE --}}

            <div

                class="

                    border-b

                    border-gray-100

                    px-6

                    py-6

                    xl:border-r

                    xl:border-b-0

                "

            >

                <div

                    class="

                        flex

                        items-start

                        justify-between

                        gap-4

                    "

                >

                    <div>

                        <p

                            class="

                                text-[10px]

                                font-bold

                                uppercase

                                tracking-[0.16em]

                                text-gray-400

                            "

                        >

                            Highly Participative

                        </p>

                        <p

                            class="

                                mt-2

                                text-3xl

                                font-bold

                                tracking-tight

                                text-green-600

                            "

                        >

                            {{ $summary['highly'] }}

                        </p>

                    </div>

                    <div

                        class="

                            flex

                            h-10

                            w-10

                            shrink-0

                            items-center

                            justify-center

                            rounded-xl

                            bg-green-50

                            text-green-600

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

                                d="M12 3l2.3 4.7 5.2.8-3.8 3.7.9 5.2-4.6-2.4-4.6 2.4.9-5.2-3.8-3.7 5.2-.8L12 3z"

                            />

                        </svg>

                    </div>

                </div>

            </div>

            {{-- PARTICIPATIVE --}}

            <div

                class="

                    border-b

                    border-gray-100

                    px-6

                    py-6

                    sm:border-r

                    sm:border-b-0

                "

            >

                <div

                    class="

                        flex

                        items-start

                        justify-between

                        gap-4

                    "

                >

                    <div>

                        <p

                            class="

                                text-[10px]

                                font-bold

                                uppercase

                                tracking-[0.16em]

                                text-gray-400

                            "

                        >

                            Participative

                        </p>

                        <p

                            class="

                                mt-2

                                text-3xl

                                font-bold

                                tracking-tight

                                text-[#D4A017]

                            "

                        >

                            {{ $summary['participative'] }}

                        </p>

                    </div>

                    <div

                        class="

                            flex

                            h-10

                            w-10

                            shrink-0

                            items-center

                            justify-center

                            rounded-xl

                            bg-[#FFF8E1]

                            text-[#A87900]

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

                                d="M5 13l4 4L19 7"

                            />

                        </svg>

                    </div>

                </div>

            </div>

            {{-- LOW PARTICIPATION --}}

            <div class="px-6 py-6">

                <div

                    class="

                        flex

                        items-start

                        justify-between

                        gap-4

                    "

                >

                    <div>

                        <p

                            class="

                                text-[10px]

                                font-bold

                                uppercase

                                tracking-[0.16em]

                                text-gray-400

                            "

                        >

                            Low Participation

                        </p>

                        <p

                            class="

                                mt-2

                                text-3xl

                                font-bold

                                tracking-tight

                                text-red-600

                            "

                        >

                            {{ $summary['low'] }}

                        </p>

                    </div>

                    <div

                        class="

                            flex

                            h-10

                            w-10

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

                                stroke-width="1.8"

                                stroke-linecap="round"

                                stroke-linejoin="round"

                                d="M12 9v4m0 4h.01M10.3 3.6L2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.6a2 2 0 00-3.4 0z"

                            />

                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- ====================================================== --}}

    {{-- FILTERS --}}

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

                Report Controls

            </p>

            <h2

                class="

                    mt-2

                    text-xl

                    font-bold

                    text-[#101064]

                "

            >

                Report Filters

            </h2>

            <p

                class="

                    mt-1

                    text-sm

                    text-gray-500

                "

            >

                Filter participation results by program, year level,

                and evaluation classification.

            </p>

        </div>

        <div class="border border-gray-200 bg-white p-6">
            <form method="GET" action="{{ route('reports.participation') }}"
                  class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-[1fr_220px_1fr_auto] xl:items-end">
                <input type="hidden" name="generated" value="1">

                <div>
                    <label for="report-program" class="mb-2 block text-sm font-semibold text-gray-600">Program</label>
                    <select id="report-program" name="program"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#D4A017] focus:ring-2 focus:ring-[#D4A017]/20">
                        <option value="">All Programs</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->program_code }}" @selected(request('program') === $program->program_code)>
                                {{ $program->program_code }} — {{ $program->program_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="report-year" class="mb-2 block text-sm font-semibold text-gray-600">Year Level</label>
                    <select id="report-year" name="year_level"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#D4A017] focus:ring-2 focus:ring-[#D4A017]/20">
                        <option value="">All Year Levels</option>
                        @foreach ($yearLevels as $year)
                            <option value="{{ $year }}" @selected((string) request('year_level') === (string) $year)>
                                Year {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="report-classification" class="mb-2 block text-sm font-semibold text-gray-600">Classification</label>
                    <select id="report-classification" name="classification"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#D4A017] focus:ring-2 focus:ring-[#D4A017]/20">
                        <option value="">All Classifications</option>
                        @foreach ([
                            'Highly Participative',
                            'Participative',
                            'Moderately Participative',
                            'Low Participation'
                        ] as $classification)

                            <option
                                value="{{ $classification }}"
                                @selected(
                                    request('classification')
                                    === $classification
                                )
                            >
                                {{ $classification }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#101064] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#D4A017] hover:text-[#101064] xl:w-auto">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.7V19a1 1 0 01-.55.9l-4 2A1 1 0 018 21v-7.3L3.2 4.6A1 1 0 013 4z" />
                        </svg>
                        Generate Report
                    </button>
                </div>
            </form>
        </div>

    </section>

    {{-- ====================================================== --}}

    {{-- PARTICIPATION RESULTS --}}

    {{-- ====================================================== --}}

    <section>

        <div

            class="

                mb-4

                flex

                flex-col

                gap-3

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

                    Participation Data

                </p>

                <h2

                    class="

                        mt-2

                        text-xl

                        font-bold

                        text-[#101064]

                    "

                >

                    Student Participation Results

                </h2>

                <p

                    class="

                        mt-1

                        text-sm

                        text-gray-500

                    "

                >

                    Displays student participation classification

                    and evaluation results.

                </p>

            </div>

            <div

                class="

                    inline-flex

                    w-fit

                    items-center

                    gap-2

                    rounded-full

                    bg-[#F1F2FA]

                    px-3

                    py-1.5

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

                        bg-[#D4A017]

                    "

                ></span>

                Participation Report

            </div>

        </div>

        <div

            class="

                overflow-hidden

                border

                border-gray-200

                bg-white

            "

        >

            <div class="overflow-x-auto">

                <table

                    class="

                        w-full

                        min-w-[900px]

                        text-left

                    "

                >

                    <thead class="bg-gray-50">

                        <tr>

                            <th

                                class="

                                    px-6

                                    py-4

                                    text-[10px]

                                    font-bold

                                    uppercase

                                    tracking-[0.16em]

                                    text-gray-400

                                "

                            >

                                Student

                            </th>

                            <th

                                class="

                                    px-6

                                    py-4

                                    text-[10px]

                                    font-bold

                                    uppercase

                                    tracking-[0.16em]

                                    text-gray-400

                                "

                            >

                                Program

                            </th>

                            <th

                                class="

                                    px-6

                                    py-4

                                    text-[10px]

                                    font-bold

                                    uppercase

                                    tracking-[0.16em]

                                    text-gray-400

                                "

                            >

                                Events Attended

                            </th>

                            <th

                                class="

                                    px-6

                                    py-4

                                    text-[10px]

                                    font-bold

                                    uppercase

                                    tracking-[0.16em]

                                    text-gray-400

                                "

                            >

                                Participation Rate

                            </th>

                            <th

                                class="

                                    px-6

                                    py-4

                                    text-[10px]

                                    font-bold

                                    uppercase

                                    tracking-[0.16em]

                                    text-gray-400

                                "

                            >

                                Classification

                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @if (! $generated)
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-[#F1F2FA] text-[#101064]">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                                  d="M4 19h16M6 16V9m4 7V5m4 11v-4m4 4V7" />
                                        </svg>
                                    </div>
                                    <p class="mt-4 font-semibold text-[#101064]">Generate a participation report</p>
                                    <p class="mx-auto mt-1 max-w-md text-sm leading-6 text-gray-400">
                                        Select your preferred filters and click Generate Report to display student participation results.
                                    </p>
                                </td>
                            </tr>
                        @elseif ($results->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <p class="font-semibold text-[#101064]">No participation records found</p>
                                    <p class="mt-1 text-sm text-gray-400">No students matched the selected filters.</p>
                                </td>
                            </tr>
                        @else
                            @foreach ($results as $student)
                                <tr class="transition hover:bg-gray-50/70">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-[#101064]">{{ $student->student_name }}</p>
                                        <p class="mt-1 text-xs text-gray-400">{{ $student->student_number }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-700">{{ $student->program_code }}</p>
                                        <p class="mt-1 text-xs text-gray-400">Year {{ $student->year_level }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-700">
                                            {{ $student->events_attended }} / {{ $student->total_applicable_events }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-400">Events</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-[#101064]">
                                            {{ number_format($student->participation_rate, 1) }}%
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $badgeClass = match ($student->classification) {
                                                'Highly Participative' => 'bg-green-50 text-green-700',
                                                'Participative' => 'bg-[#FFF8E1] text-[#A87900]',
                                                default => 'bg-red-50 text-red-600',
                                            };
                                        @endphp
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                            {{ $student->classification }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>

            </div>

            {{-- TABLE FOOTER --}}

            <div

                class="

                    flex

                    flex-col

                    gap-2

                    border-t

                    border-gray-100

                    bg-gray-50/50

                    px-6

                    py-4

                    text-xs

                    text-gray-400

                    sm:flex-row

                    sm:items-center

                    sm:justify-between

                "

            >

                <span>

                    Student participation reporting

                </span>

                <span>

                    DySign • Participation Reports

                </span>

            </div>

        </div>

    </section>

</div>

</x-admin-layout>