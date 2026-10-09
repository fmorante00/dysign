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
                    Evaluate and classify student participation using
                    approved attendance records, participation indicators,
                    compliance information, and evaluation criteria.
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
                        Criteria-Based Classification
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
                Current distribution of student participation classifications.
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


            {{-- STUDENTS EVALUATED --}}

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
                            Students Evaluated
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
                            1,542
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
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm8 0l2 2 4-4"
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
                            430
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
                                text-blue-600
                            "
                        >
                            700
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
                            bg-blue-50
                            text-blue-600
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
                            120
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
                    Review attendance, participation rate, compliance status,
                    and the resulting student classification.
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
                                Attendance Rate
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
                                Compliance
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


                        {{-- STUDENT 1 --}}

                        <tr
                            class="
                                transition
                                hover:bg-gray-50/70
                            "
                        >

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
                                            h-10
                                            w-10
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-[#F1F2FA]
                                            text-sm
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        J
                                    </div>


                                    <div>

                                        <p
                                            class="
                                                font-semibold
                                                text-[#101064]
                                            "
                                        >
                                            Juan Dela Cruz
                                        </p>


                                        <p
                                            class="
                                                mt-0.5
                                                text-xs
                                                text-gray-400
                                            "
                                        >
                                            Student participant
                                        </p>

                                    </div>

                                </div>

                            </td>



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
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        95%
                                    </span>


                                    <div
                                        class="
                                            h-1.5
                                            w-20
                                            overflow-hidden
                                            rounded-full
                                            bg-gray-100
                                        "
                                    >

                                        <div
                                            class="
                                                h-full
                                                w-[95%]
                                                rounded-full
                                                bg-green-500
                                            "
                                        ></div>

                                    </div>

                                </div>

                            </td>



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
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        92%
                                    </span>


                                    <div
                                        class="
                                            h-1.5
                                            w-20
                                            overflow-hidden
                                            rounded-full
                                            bg-gray-100
                                        "
                                    >

                                        <div
                                            class="
                                                h-full
                                                w-[92%]
                                                rounded-full
                                                bg-[#101064]
                                            "
                                        ></div>

                                    </div>

                                </div>

                            </td>



                            <td class="px-6 py-5">

                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        text-sm
                                        font-medium
                                        text-green-700
                                    "
                                >

                                    <span
                                        class="
                                            flex
                                            h-5
                                            w-5
                                            items-center
                                            justify-center
                                            rounded-full
                                            bg-green-50
                                        "
                                    >
                                        ✓
                                    </span>

                                    Completed

                                </span>

                            </td>



                            <td class="px-6 py-5">

                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-full
                                        bg-green-50
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        text-green-700
                                    "
                                >

                                    <span
                                        class="
                                            h-1.5
                                            w-1.5
                                            rounded-full
                                            bg-green-500
                                        "
                                    ></span>

                                    Highly Participative

                                </span>

                            </td>

                        </tr>



                        {{-- STUDENT 2 --}}

                        <tr
                            class="
                                transition
                                hover:bg-gray-50/70
                            "
                        >

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
                                            h-10
                                            w-10
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-[#FFF8E1]
                                            text-sm
                                            font-bold
                                            text-[#A87900]
                                        "
                                    >
                                        M
                                    </div>


                                    <div>

                                        <p
                                            class="
                                                font-semibold
                                                text-[#101064]
                                            "
                                        >
                                            Maria Santos
                                        </p>


                                        <p
                                            class="
                                                mt-0.5
                                                text-xs
                                                text-gray-400
                                            "
                                        >
                                            Student participant
                                        </p>

                                    </div>

                                </div>

                            </td>



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
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        80%
                                    </span>


                                    <div
                                        class="
                                            h-1.5
                                            w-20
                                            overflow-hidden
                                            rounded-full
                                            bg-gray-100
                                        "
                                    >

                                        <div
                                            class="
                                                h-full
                                                w-[80%]
                                                rounded-full
                                                bg-[#D4A017]
                                            "
                                        ></div>

                                    </div>

                                </div>

                            </td>



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
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        75%
                                    </span>


                                    <div
                                        class="
                                            h-1.5
                                            w-20
                                            overflow-hidden
                                            rounded-full
                                            bg-gray-100
                                        "
                                    >

                                        <div
                                            class="
                                                h-full
                                                w-[75%]
                                                rounded-full
                                                bg-[#D4A017]
                                            "
                                        ></div>

                                    </div>

                                </div>

                            </td>



                            <td class="px-6 py-5">

                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        text-sm
                                        font-medium
                                        text-[#A87900]
                                    "
                                >

                                    <span
                                        class="
                                            flex
                                            h-5
                                            w-5
                                            items-center
                                            justify-center
                                            rounded-full
                                            bg-[#FFF8E1]
                                        "
                                    >
                                        !
                                    </span>

                                    Incomplete

                                </span>

                            </td>



                            <td class="px-6 py-5">

                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-full
                                        bg-[#FFF8E1]
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        text-[#A87900]
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

                                    Moderately Participative

                                </span>

                            </td>

                        </tr>


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
                    Student participation classifications
                </span>


                <span>
                    DySign • Participation Evaluation
                </span>

            </div>

        </div>

    </section>



{{-- ====================================================== --}}
{{-- EVALUATION INFORMATION --}}
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
            flex
            flex-col
            gap-6
            px-7
            py-6
            lg:flex-row
            lg:items-center
            lg:justify-between
        "
    >

        {{-- LEFT CONTENT --}}

        <div class="max-w-4xl">

            <p
                class="
                    text-[10px]
                    font-bold
                    uppercase
                    tracking-[0.25em]
                    text-[#D4A017]
                "
            >
                Evaluation Process
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
                    text-sm
                    leading-6
                    text-gray-500
                "
            >
                Student classifications are recalculated using the latest
                participation records, attendance indicators, compliance
                information, and approved evaluation criteria.
            </p>

        </div>



        {{-- RIGHT STATUS --}}

        <div
            class="
                flex
                shrink-0
                items-center
                gap-4
                rounded-xl
                border
                border-green-100
                bg-green-50
                px-5
                py-4
            "
        >

            <div
                class="
                    flex
                    h-10
                    w-10
                    items-center
                    justify-center
                    rounded-xl
                    bg-white
                    text-green-600
                    shadow-sm
                "
            >

                <svg
                    class="h-5 w-5"
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

            </div>


            <div>

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.14em]
                        text-green-600/70
                    "
                >
                    Evaluation Status
                </p>


                <p
                    class="
                        mt-1
                        text-sm
                        font-bold
                        text-green-700
                    "
                >
                    Ready for Re-Evaluation
                </p>

            </div>

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
            <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>


        {{-- CONTENT --}}

        <div class="px-7 pb-6 pt-8">

            <div
                class="
                    flex
                    h-11
                    w-11
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
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 4v6h6M20 20v-6h-6M5.6 15A7 7 0 0018 18.4M18.4 9A7 7 0 006 5.6"
                    />
                </svg>
            </div>


            <p
                class="
                    mt-5
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
                    mt-1.5
                    text-xl
                    font-bold
                    tracking-tight
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
                DySign will recalculate student participation
                classifications using the latest attendance,
                participation, and compliance records.
            </p>


            <div
                class="
                    mt-5
                    flex
                    items-start
                    gap-3
                    rounded-xl
                    bg-gray-50
                    px-4
                    py-3.5
                "
            >

                <svg
                    class="
                        mt-0.5
                        h-4
                        w-4
                        shrink-0
                        text-[#D4A017]
                    "
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v3m0 4h.01M10.3 3.6L2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.6a2 2 0 00-3.4 0z"
                    />
                </svg>


                <p
                    class="
                        text-xs
                        leading-5
                        text-gray-500
                    "
                >
                    Existing evaluation results may change after
                    re-evaluation.
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


            <button
                type="button"
                class="
                    inline-flex
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

                Confirm Evaluation
            </button>

        </div>

    </div>

</div>
</div>



{{-- ====================================================== --}}
{{-- MODAL SCRIPT --}}
{{-- ====================================================== --}}

<script>

    function openEvaluationModal() {

        const modal =
            document.getElementById(
                'evaluationModal'
            );


        modal.classList.remove(
            'hidden'
        );


        modal.classList.add(
            'flex'
        );


        document.body.style.overflow =
            'hidden';

    }



    function closeEvaluationModal() {

        const modal =
            document.getElementById(
                'evaluationModal'
            );


        modal.classList.add(
            'hidden'
        );


        modal.classList.remove(
            'flex'
        );


        document.body.style.overflow =
            '';

    }



    function closeEvaluationModalOnBackdrop(
        event
    ) {

        if (
            event.target.id
            === 'evaluationModal'
        ) {

            closeEvaluationModal();

        }

    }



    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key
                === 'Escape'
            ) {

                closeEvaluationModal();

            }

        }
    );

</script>


</x-admin-layout>