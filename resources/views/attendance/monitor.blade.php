<x-admin-layout>

<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .attendance-monitor-hero {
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


    /*
    |--------------------------------------------------------------------------
    | RFID PULSE
    |--------------------------------------------------------------------------
    */

    @keyframes rfidPulse {

        0%, 100% {
            transform: scale(1);
            opacity: .45;
        }

        50% {
            transform: scale(1.1);
            opacity: 1;
        }

    }


    .rfid-pulse {
        animation:
            rfidPulse
            1.8s
            ease-in-out
            infinite;
    }

</style>



<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            attendance-monitor-hero
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

            <div class="min-w-0 max-w-3xl">

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.32em]
                        text-[#E7C75B]
                    "
                >
                    Attendance Monitoring
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
                    Leadership Seminar
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
                    Monitor live RFID attendance activity and review
                    student attendance transactions during the event.
                </p>


                <div
                    class="
                        mt-5
                        flex
                        flex-wrap
                        items-center
                        gap-x-3
                        gap-y-2
                        text-sm
                        text-white/75
                    "
                >

                    <span>
                        Live RFID Session
                    </span>


                    <span class="text-white/30">
                        •
                    </span>


                    <span>
                        Administrator Monitoring
                    </span>


                    <span class="text-white/30">
                        •
                    </span>


                    <span
                        class="
                            inline-flex
                            items-center
                            gap-2
                        "
                    >

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-green-400
                                animate-pulse
                            "
                        ></span>

                        Active

                    </span>

                </div>

            </div>



            <a
                href="{{ route('events.show', 1) }}"
                class="
                    inline-flex
                    shrink-0
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

                Back to Event

            </a>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- LIVE OVERVIEW --}}
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
                Live Overview
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Attendance Session
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Current RFID monitoring status and attendance activity.
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


            {{-- EVENT STATUS --}}

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

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.16em]
                        text-gray-400
                    "
                >
                    Event Status
                </p>


                <div class="mt-3">

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

                        Active

                    </span>

                </div>

            </div>



            {{-- SCANNER STATUS --}}

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

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.16em]
                        text-gray-400
                    "
                >
                    Scanner Status
                </p>


                <div
                    class="
                        mt-3
                        inline-flex
                        items-center
                        gap-2
                        text-sm
                        font-semibold
                        text-green-700
                    "
                >

                    <span
                        class="
                            h-2
                            w-2
                            rounded-full
                            bg-green-500
                            animate-pulse
                        "
                    ></span>

                    Connected

                </div>

            </div>



            {{-- ATTENDANCE COUNT --}}

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

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.16em]
                        text-gray-400
                    "
                >
                    Recorded Attendance
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
                    2
                </p>

            </div>



            {{-- CURRENT TIME --}}

            <div class="px-6 py-6">

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.16em]
                        text-gray-400
                    "
                >
                    Current Time
                </p>


                <p
                    id="monitor_live_clock"
                    class="
                        mt-2
                        text-2xl
                        font-bold
                        tracking-tight
                        text-[#101064]
                    "
                >
                    --:--:--
                </p>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- RFID MONITORING --}}
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
                RFID Monitoring
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Live Scanner Activity
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Observe RFID scanner activity and the most recent
                attendance transaction.
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
                xl:grid-cols-[360px_1fr]
            "
        >


            {{-- SCANNER PANEL --}}

            <div
                class="
                    flex
                    min-h-[340px]
                    flex-col
                    items-center
                    justify-center
                    border-b
                    border-gray-100
                    bg-[#101064]
                    px-8
                    py-10
                    text-center
                    text-white
                    xl:border-b-0
                    xl:border-r
                    xl:border-white/10
                "
            >

                <div
                    class="
                        relative
                        flex
                        h-24
                        w-24
                        items-center
                        justify-center
                        rounded-full
                        border
                        border-white/20
                        bg-white/10
                    "
                >

                    <div
                        class="
                            rfid-pulse
                            absolute
                            h-16
                            w-16
                            rounded-full
                            border
                            border-[#D4A017]/70
                        "
                    ></div>


                    <svg
                        class="
                            relative
                            z-10
                            h-10
                            w-10
                            text-[#E7C75B]
                        "
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 7h8M8 12h8M8 17h5"
                        />
                    </svg>

                </div>


                <p
                    class="
                        mt-7
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-[0.3em]
                        text-[#E7C75B]
                    "
                >
                    RFID Reader
                </p>


                <h3
                    class="
                        mt-2
                        text-2xl
                        font-bold
                    "
                >
                    Ready for Scan
                </h3>


                <p
                    class="
                        mt-3
                        max-w-xs
                        text-sm
                        leading-6
                        text-white/65
                    "
                >
                    The attendance station is waiting for
                    student RFID identification.
                </p>


                <div
                    class="
                        mt-7
                        inline-flex
                        items-center
                        gap-2
                        rounded-full
                        bg-white/10
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        text-white/80
                    "
                >

                    <span
                        class="
                            h-2
                            w-2
                            rounded-full
                            bg-green-400
                            animate-pulse
                        "
                    ></span>

                    Scanner Connected

                </div>

            </div>



            {{-- LATEST ACTIVITY --}}

            <div
                class="
                    flex
                    min-h-[340px]
                    items-center
                    px-7
                    py-9
                    sm:px-10
                    lg:px-12
                "
            >

                <div
                    class="
                        flex
                        w-full
                        flex-col
                        gap-7
                        sm:flex-row
                        sm:items-center
                    "
                >

                    <div
                        class="
                            flex
                            h-24
                            w-24
                            shrink-0
                            items-center
                            justify-center
                            rounded-2xl
                            bg-[#F1F2FA]
                            text-4xl
                            font-bold
                            text-[#101064]
                        "
                    >
                        M
                    </div>


                    <div class="min-w-0">

                        <p
                            class="
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-[0.24em]
                                text-[#D4A017]
                            "
                        >
                            Latest Attendance
                        </p>


                        <h3
                            class="
                                mt-2
                                text-2xl
                                font-bold
                                text-[#101064]
                                md:text-3xl
                            "
                        >
                            Maria Santos
                        </h3>


                        <div
                            class="
                                mt-3
                                flex
                                flex-wrap
                                items-center
                                gap-x-3
                                gap-y-2
                                text-sm
                                text-gray-500
                            "
                        >

                            <span>
                                2026-0002
                            </span>


                            <span class="text-gray-300">
                                •
                            </span>


                            <span>
                                BSACC
                            </span>


                            <span class="text-gray-300">
                                •
                            </span>


                            <span>
                                Student
                            </span>

                        </div>


                        <div
                            class="
                                mt-5
                                flex
                                flex-wrap
                                items-center
                                gap-3
                            "
                        >

                            <span
                                class="
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-full
                                    bg-green-50
                                    px-3
                                    py-1
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

                                Present

                            </span>


                            <span
                                class="
                                    text-sm
                                    font-medium
                                    text-gray-500
                                "
                            >
                                Recorded at 08:02 AM
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- LIVE ATTENDANCE RECORDS --}}
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
                    Attendance Records
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Live Attendance Feed
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Recent student RFID attendance transactions
                    recorded during the event.
                </p>

            </div>


            <div
                class="
                    inline-flex
                    w-fit
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
                        animate-pulse
                    "
                ></span>

                Live Monitoring

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
                        min-w-[760px]
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
                                Time In
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
                                Status
                            </th>

                        </tr>

                    </thead>



                    <tbody class="divide-y divide-gray-100">


                        {{-- RECORD 1 --}}

                        <tr
                            class="
                                transition
                                hover:bg-gray-50/70
                            "
                        >

                            <td
                                class="
                                    whitespace-nowrap
                                    px-6
                                    py-5
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >
                                08:01 AM
                            </td>


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
                                            2026-0001
                                        </p>

                                    </div>

                                </div>

                            </td>


                            <td
                                class="
                                    px-6
                                    py-5
                                    text-sm
                                    font-medium
                                    text-gray-600
                                "
                            >
                                BSIT
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
                                        py-1
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

                                    Present

                                </span>

                            </td>

                        </tr>



                        {{-- RECORD 2 --}}

                        <tr
                            class="
                                transition
                                hover:bg-gray-50/70
                            "
                        >

                            <td
                                class="
                                    whitespace-nowrap
                                    px-6
                                    py-5
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >
                                08:02 AM
                            </td>


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
                                            2026-0002
                                        </p>

                                    </div>

                                </div>

                            </td>


                            <td
                                class="
                                    px-6
                                    py-5
                                    text-sm
                                    font-medium
                                    text-gray-600
                                "
                            >
                                BSACC
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
                                        py-1
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

                                    Present

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
                    Monitoring active attendance transactions
                </span>


                <span>
                    DySign • RFID Attendance Monitoring
                </span>

            </div>

        </div>

    </section>


</div>



{{-- ====================================================== --}}
{{-- LIVE CLOCK --}}
{{-- ====================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        function updateMonitorClock() {

            const clock =
                document.getElementById(
                    'monitor_live_clock'
                );


            if (!clock) {
                return;
            }


            const now =
                new Date();


            clock.textContent =
                now.toLocaleTimeString(
                    [],
                    {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    }
                );

        }


        updateMonitorClock();


        setInterval(
            updateMonitorClock,
            1000
        );

    }
);

</script>


</x-admin-layout>