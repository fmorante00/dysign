<x-admin-layout>

<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .evaluation-hero {

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
    {{-- SUCCESS MESSAGE --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <div
            class="
                flex
                items-start
                gap-3
                border
                border-green-200
                bg-green-50
                px-5
                py-4
                text-sm
                text-green-700
            "
        >

            <span
                class="
                    flex
                    h-6
                    w-6
                    shrink-0
                    items-center
                    justify-center
                    rounded-full
                    bg-green-100
                    font-bold
                "
            >
                ✓
            </span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif



    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            evaluation-hero
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
                    Participation Management
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
                    Participation Evaluation Management
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
                    Automatically evaluate and classify student participation
                    using recorded event attendance from the DySign database.
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
                            d="M9 12l2 2 4-4m5.6-4.5A11.9 11.9 0 0112 3a11.9 11.9 0 01-8.6 2.5A12 12 0 003 9c0 5.6 3.8 10.3 9 11.7 5.2-1.4 9-6.1 9-11.7a12 12 0 00-.4-3.5z"
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
                        Evaluation Method
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            font-semibold
                            text-white
                        "
                    >
                        Automated Classification
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
                Evaluation Overview
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Classification Summary
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Current distribution of automatically calculated
                participation classifications.
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
                xl:grid-cols-5
            "
        >


            {{-- STUDENTS EVALUATED --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-5
                    py-6
                    sm:border-r
                    xl:border-b-0
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.14em]
                        text-gray-400
                    "
                >
                    Students Evaluated
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ number_format($studentsEvaluated ?? 0) }}
                </p>

            </div>



            {{-- HIGHLY PARTICIPATIVE --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-5
                    py-6
                    xl:border-r
                    xl:border-b-0
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.14em]
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
                        text-green-600
                    "
                >
                    {{ number_format(
                        $highlyParticipativeCount ?? 0
                    ) }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    90–100%
                </p>

            </div>



            {{-- PARTICIPATIVE --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-5
                    py-6
                    sm:border-r
                    xl:border-r
                    xl:border-b-0
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.14em]
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
                        text-blue-600
                    "
                >
                    {{ number_format(
                        $participativeCount ?? 0
                    ) }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    75–89%
                </p>

            </div>



            {{-- MODERATELY PARTICIPATIVE --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-5
                    py-6
                    xl:border-r
                    xl:border-b-0
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.14em]
                        text-gray-400
                    "
                >
                    Moderately Participative
                </p>


                <p
                    class="
                        mt-2
                        text-3xl
                        font-bold
                        text-[#D4A017]
                    "
                >
                    {{ number_format(
                        $moderatelyParticipativeCount ?? 0
                    ) }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    60–74%
                </p>

            </div>



            {{-- LOW PARTICIPATION --}}

            <div class="px-5 py-6">

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.14em]
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
                        text-red-600
                    "
                >
                    {{ number_format(
                        $lowParticipationCount ?? 0
                    ) }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    Below 60%
                </p>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- EVALUATION TABLE --}}
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
                        font-semibold
                        uppercase
                        tracking-[0.28em]
                        text-[#D4A017]
                    "
                >
                    Student Evaluation
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Participation Classification
                </h2>


                <p
                    class="
                        mt-1
                        max-w-2xl
                        text-sm
                        text-gray-500
                    "
                >
                    Classification is automatically calculated from
                    events attended compared with total recorded events.
                </p>

            </div>



            <button
                type="button"
                onclick="openEvaluationModal()"
                class="
                    inline-flex
                    w-fit
                    items-center
                    justify-center
                    gap-2
                    rounded-xl
                    bg-[#101064]
                    px-5
                    py-3
                    text-sm
                    font-semibold
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
                        d="M4 4v6h6M20 20v-6h-6M5.6 15A7 7 0 0018 18.4M18.4 9A7 7 0 006 5.6"
                    />
                </svg>

                Re-Evaluate Students

            </button>

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
                        min-w-[1100px]
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
                                Total Events
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
                                Evaluation Result
                            </th>

                        </tr>

                    </thead>



                    <tbody class="divide-y divide-gray-100">

                        @forelse($records as $record)

                            @php

                                $studentName = trim(
                                    $record->first_name
                                    . ' '
                                    . (
                                        $record->middle_name
                                            ? $record->middle_name . ' '
                                            : ''
                                    )
                                    . $record->last_name
                                );


                                $classificationConfig = match(
                                    $record->classification
                                ) {

                                    'Highly Participative' => [
                                        'badge' =>
                                            'bg-green-50 text-green-700',
                                        'dot' =>
                                            'bg-green-500',
                                        'bar' =>
                                            'bg-green-500',
                                    ],

                                    'Participative' => [
                                        'badge' =>
                                            'bg-blue-50 text-blue-700',
                                        'dot' =>
                                            'bg-blue-500',
                                        'bar' =>
                                            'bg-blue-500',
                                    ],

                                    'Moderately Participative' => [
                                        'badge' =>
                                            'bg-[#FFF8E1] text-[#9A7000]',
                                        'dot' =>
                                            'bg-[#D4A017]',
                                        'bar' =>
                                            'bg-[#D4A017]',
                                    ],

                                    'Low Participation' => [
                                        'badge' =>
                                            'bg-red-50 text-red-700',
                                        'dot' =>
                                            'bg-red-500',
                                        'bar' =>
                                            'bg-red-500',
                                    ],

                                    default => [
                                        'badge' =>
                                            'bg-gray-100 text-gray-600',
                                        'dot' =>
                                            'bg-gray-400',
                                        'bar' =>
                                            'bg-gray-400',
                                    ],

                                };

                            @endphp



                            <tr
                                class="
                                    transition
                                    hover:bg-gray-50/70
                                "
                            >


                                {{-- STUDENT --}}

                                <td class="px-6 py-5">

                                    <div
                                        class="
                                            flex
                                            items-center
                                            gap-3
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
                                                overflow-hidden
                                                rounded-xl
                                                bg-[#F1F2FA]
                                                text-sm
                                                font-bold
                                                text-[#101064]
                                            "
                                        >

                                            @if(!empty($record->photo_path))

                                                <img
                                                    src="{{ asset(
                                                        'student_photos/'
                                                        . basename(
                                                            $record->photo_path
                                                        )
                                                    ) }}"
                                                    alt="{{ $studentName }}"
                                                    class="
                                                        h-full
                                                        w-full
                                                        object-cover
                                                    "
                                                >

                                            @else

                                                {{ strtoupper(
                                                    substr(
                                                        $record->first_name,
                                                        0,
                                                        1
                                                    )
                                                ) }}

                                            @endif

                                        </div>


                                        <div class="min-w-0">

                                            <p
                                                class="
                                                    font-semibold
                                                    text-[#101064]
                                                "
                                            >
                                                {{ $studentName }}
                                            </p>


                                            <p
                                                class="
                                                    mt-0.5
                                                    text-xs
                                                    text-gray-400
                                                "
                                            >
                                                {{ $record->student_number }}
                                            </p>

                                        </div>

                                    </div>

                                </td>



                                {{-- PROGRAM --}}

                                <td class="px-6 py-5">

                                    <p
                                        class="
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        {{ $record->program_code }}
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-400
                                        "
                                    >
                                        Year {{ $record->year_level }}
                                    </p>

                                </td>



                                {{-- EVENTS ATTENDED --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    {{ number_format(
                                        $record->events_attended ?? 0
                                    ) }}
                                </td>



                                {{-- TOTAL EVENTS --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-sm
                                        text-gray-600
                                    "
                                >
                                    {{ number_format(
                                        $record->total_recorded_events ?? 0
                                    ) }}
                                </td>



                                {{-- PARTICIPATION RATE --}}

                                <td class="px-6 py-5">

                                    <div
                                        class="
                                            flex
                                            items-center
                                            gap-3
                                        "
                                    >

                                        <span
                                            class="
                                                w-14
                                                text-sm
                                                font-semibold
                                                text-gray-700
                                            "
                                        >
                                            {{
                                                number_format(
                                                    $record->participation_rate ?? 0,
                                                    0
                                                )
                                            }}%
                                        </span>


                                        <div
                                            class="
                                                h-1.5
                                                w-24
                                                overflow-hidden
                                                rounded-full
                                                bg-gray-100
                                            "
                                        >

                                            <div
                                                class="
                                                    h-full
                                                    rounded-full
                                                    {{ $classificationConfig['bar'] }}
                                                "
                                                style="
                                                    width:
                                                    {{
                                                        min(
                                                            100,
                                                            max(
                                                                0,
                                                                $record->participation_rate ?? 0
                                                            )
                                                        )
                                                    }}%;
                                                "
                                            ></div>

                                        </div>

                                    </div>

                                </td>



                                {{-- RESULT --}}

                                <td class="px-6 py-5">

                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-full
                                            px-3
                                            py-1.5
                                            text-xs
                                            font-semibold
                                            {{ $classificationConfig['badge'] }}
                                        "
                                    >

                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                {{ $classificationConfig['dot'] }}
                                            "
                                        ></span>

                                        {{ $record->classification }}

                                    </span>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="
                                        px-6
                                        py-16
                                        text-center
                                    "
                                >

                                    <p
                                        class="
                                            text-sm
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        No Evaluation Records Available
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-400
                                        "
                                    >
                                        Student evaluation records will
                                        appear once student data is available.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>



            {{-- TABLE FOOTER --}}

            <div
                class="
                    border-t
                    border-gray-100
                    bg-gray-50/50
                    px-6
                    py-4
                "
            >

                <div
                    class="
                        flex
                        flex-col
                        gap-4
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                    "
                >

                    <p
                        class="
                            text-xs
                            text-gray-400
                        "
                    >
                        Classification based on
                        {{ number_format($totalRecordedEvents ?? 0) }}
                        recorded event(s).
                    </p>


                    @if(isset($records) && $records->hasPages())

                        <div>
                            {{ $records->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- EVALUATION CRITERIA --}}
    {{-- ====================================================== --}}

    <section
        class="
            overflow-hidden
            border
            border-gray-200
            bg-white
        "
    >

        <div
            class="
                border-b
                border-gray-100
                px-7
                py-5
            "
        >

            <p
                class="
                    text-[10px]
                    font-bold
                    uppercase
                    tracking-[0.25em]
                    text-[#D4A017]
                "
            >
                Evaluation Criteria
            </p>


            <h3
                class="
                    mt-2
                    text-lg
                    font-bold
                    text-[#101064]
                "
            >
                Automated Participation Classification
            </h3>


            <p
                class="
                    mt-2
                    max-w-3xl
                    text-sm
                    leading-6
                    text-gray-500
                "
            >
                Participation rate is calculated by dividing
                the student's attended events by the total number
                of events with recorded attendance.
            </p>

        </div>


        <div
            class="
                grid
                grid-cols-1
                md:grid-cols-2
                xl:grid-cols-4
            "
        >

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

                <p class="text-sm font-bold text-green-700">
                    Highly Participative
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    90% – 100%
                </p>

            </div>


            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-5
                    xl:border-r
                    xl:border-b-0
                "
            >

                <p class="text-sm font-bold text-blue-700">
                    Participative
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    75% – 89%
                </p>

            </div>


            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-5
                    md:border-r
                    md:border-b-0
                    xl:border-r
                "
            >

                <p class="text-sm font-bold text-[#9A7000]">
                    Moderately Participative
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    60% – 74%
                </p>

            </div>


            <div class="px-6 py-5">

                <p class="text-sm font-bold text-red-700">
                    Low Participation
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    Below 60%
                </p>

            </div>

        </div>

    </section>


</div>


{{-- ====================================================== --}}
{{-- RE-EVALUATION MODAL --}}
{{-- ====================================================== --}}

<div
    id="evaluationModal"
    class="
        fixed
        inset-0
        z-50
        hidden
        items-center
        justify-center
        bg-[#080821]/45
        px-4
        backdrop-blur-[2px]
    "
    onclick="closeEvaluationModalOnBackdrop(event)"
>

    <div
        class="
            relative
            w-full
            overflow-hidden
            rounded-[22px]
            bg-white
            shadow-2xl
        "
        style="max-width: 430px;"
    >

        {{-- GOLD ACCENT --}}

        <div
            class="
                absolute
                left-0
                top-0
                h-1
                w-full
                bg-[#D4A017]
            "
        ></div>


        {{-- CLOSE BUTTON --}}

        <button
            type="button"
            onclick="closeEvaluationModal()"
            class="
                absolute
                right-5
                top-5
                flex
                h-9
                w-9
                items-center
                justify-center
                rounded-lg
                text-gray-400
                transition
                hover:bg-gray-100
                hover:text-gray-700
            "
        >
            ✕
        </button>


        {{-- CONTENT --}}

        <div class="px-7 pb-6 pt-8">

            <p
                class="
                    text-[10px]
                    font-bold
                    uppercase
                    tracking-[0.22em]
                    text-[#D4A017]
                "
            >
                Participation Evaluation
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Re-Evaluate Students?
            </h2>


            <p
                class="
                    mt-3
                    text-sm
                    leading-6
                    text-gray-500
                "
            >
                DySign will recalculate all student participation
                rates and classifications using the latest
                attendance records.
            </p>


            <div
                class="
                    mt-5
                    rounded-xl
                    bg-gray-50
                    px-4
                    py-3.5
                "
            >

                <p
                    class="
                        text-xs
                        leading-5
                        text-gray-500
                    "
                >
                    The current participation classifications may
                    change depending on the latest recorded attendance.
                </p>

            </div>

        </div>


        {{-- ACTIONS --}}

        <div
            class="
                flex
                items-center
                justify-end
                gap-3
                border-t
                border-gray-100
                px-7
                py-5
            "
        >

            <button
                id="cancelEvaluationButton"
                type="button"
                onclick="closeEvaluationModal()"
                class="
                    rounded-xl
                    px-5
                    py-2.5
                    text-sm
                    font-semibold
                    text-gray-500
                    transition
                    hover:bg-gray-100
                    hover:text-gray-700
                "
            >
                Cancel
            </button>


            <form
                id="reevaluateForm"
                method="POST"
                action="{{ route('participation.evaluation.reevaluate') }}"
                onsubmit="return handleReevaluationSubmit()"
            >

                @csrf


                <button
                    id="confirmEvaluationButton"
                    type="submit"
                    class="
                        inline-flex
                        min-w-[145px]
                        items-center
                        justify-center
                        gap-2
                        rounded-xl
                        bg-[#101064]
                        px-5
                        py-2.5
                        text-sm
                        font-semibold
                        text-white
                        shadow-sm
                        transition
                        hover:bg-[#0C0C50]
                        disabled:cursor-not-allowed
                        disabled:opacity-60
                    "
                >

                    {{-- LOADING ICON --}}

                    <svg
                        id="evaluationLoadingIcon"
                        class="
                            hidden
                            h-4
                            w-4
                            animate-spin
                        "
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >

                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>

                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="
                                M4 12a8 8 0 018-8
                                V0C5.373 0 0 5.373 0 12h4zm2
                                5.291A7.962 7.962 0 014 12H0
                                c0 3.042 1.135 5.824 3 7.938
                                l3-2.647z
                            "
                        ></path>

                    </svg>


                    <span id="confirmEvaluationText">
                        Confirm Evaluation
                    </span>

                </button>

            </form>

        </div>

    </div>

</div>



{{-- ====================================================== --}}
{{-- MODAL SCRIPT --}}
{{-- ====================================================== --}}

<script>

    /*
    |--------------------------------------------------------------------------
    | OPEN MODAL
    |--------------------------------------------------------------------------
    */

    function openEvaluationModal() {

        const modal =
            document.getElementById(
                'evaluationModal'
            );

        if (!modal) {
            return;
        }


        modal.classList.remove(
            'hidden'
        );

        modal.classList.add(
            'flex'
        );


        document.body.style.overflow =
            'hidden';

    }



    /*
    |--------------------------------------------------------------------------
    | CLOSE MODAL
    |--------------------------------------------------------------------------
    */

    function closeEvaluationModal() {

        const modal =
            document.getElementById(
                'evaluationModal'
            );

        if (!modal) {
            return;
        }


        modal.classList.add(
            'hidden'
        );

        modal.classList.remove(
            'flex'
        );


        document.body.style.overflow =
            '';

    }



    /*
    |--------------------------------------------------------------------------
    | BACKDROP CLOSE
    |--------------------------------------------------------------------------
    */

    function closeEvaluationModalOnBackdrop(
        event
    ) {

        if (
            event.target.id ===
            'evaluationModal'
        ) {

            closeEvaluationModal();

        }

    }



    /*
    |--------------------------------------------------------------------------
    | RE-EVALUATION SUBMIT
    |--------------------------------------------------------------------------
    */

    function handleReevaluationSubmit() {

        const button =
            document.getElementById(
                'confirmEvaluationButton'
            );

        const buttonText =
            document.getElementById(
                'confirmEvaluationText'
            );

        const loadingIcon =
            document.getElementById(
                'evaluationLoadingIcon'
            );

        const cancelButton =
            document.getElementById(
                'cancelEvaluationButton'
            );


        /*
        |--------------------------------------------------------------------------
        | PREVENT DOUBLE SUBMISSION
        |--------------------------------------------------------------------------
        */

        if (
            button &&
            button.disabled
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | SHOW PROCESSING STATE
        |--------------------------------------------------------------------------
        */

        if (button) {

            button.disabled = true;

        }


        if (cancelButton) {

            cancelButton.disabled = true;

            cancelButton.classList.add(
                'opacity-50',
                'cursor-not-allowed'
            );

        }


        if (buttonText) {

            buttonText.textContent =
                'Evaluating...';

        }


        if (loadingIcon) {

            loadingIcon.classList.remove(
                'hidden'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE MODAL VISUALLY
        |--------------------------------------------------------------------------
        |
        | The form submission will still continue normally.
        |
        */

        setTimeout(
            function () {

                const modal =
                    document.getElementById(
                        'evaluationModal'
                    );

                if (modal) {

                    modal.classList.add(
                        'hidden'
                    );

                    modal.classList.remove(
                        'flex'
                    );

                }


                document.body.style.overflow =
                    '';

            },
            150
        );


        /*
        |--------------------------------------------------------------------------
        | ALLOW FORM TO SUBMIT
        |--------------------------------------------------------------------------
        */

        return true;

    }



    /*
    |--------------------------------------------------------------------------
    | ESC KEY
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key ===
                'Escape'
            ) {

                closeEvaluationModal();

            }

        }
    );

</script>


</x-admin-layout>