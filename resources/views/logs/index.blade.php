<x-admin-layout>

<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .logs-hero {
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
            logs-hero
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
                    System Management
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
                    Activity Logs
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
                    Monitor system activities, user actions,
                    and operational changes recorded across DySign.
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
                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
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
                        Log Type
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            font-semibold
                            text-white
                        "
                    >
                        System Audit Trail
                    </p>

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
                Activity Controls
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                System Activity History
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Search and filter recorded DySign operations.
            </p>

        </div>


        <div
            class="
                border
                border-gray-200
                bg-white
                p-6
            "
        >

            <div
                class="
                    grid
                    grid-cols-1
                    gap-4
                    lg:grid-cols-[1fr_260px_auto]
                    lg:items-end
                "
            >


                {{-- SEARCH --}}

                <div>

                    <label
                        class="
                            mb-2
                            block
                            text-sm
                            font-semibold
                            text-gray-600
                        "
                    >
                        Search Activity
                    </label>


                    <div class="relative">

                        <svg
                            class="
                                absolute
                                left-4
                                top-1/2
                                h-4
                                w-4
                                -translate-y-1/2
                                text-gray-400
                            "
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                            />
                        </svg>


                        <input
                            type="text"
                            placeholder="Search user, action, or activity..."
                            class="
                                w-full
                                rounded-xl
                                border
                                border-gray-300
                                bg-white
                                py-3
                                pl-11
                                pr-4
                                text-sm
                                text-gray-700
                                outline-none
                                transition
                                placeholder:text-gray-400
                                focus:border-[#D4A017]
                                focus:ring-2
                                focus:ring-[#D4A017]/20
                            "
                        >

                    </div>

                </div>



                {{-- MODULE FILTER --}}

                <div>

                    <label
                        class="
                            mb-2
                            block
                            text-sm
                            font-semibold
                            text-gray-600
                        "
                    >
                        Module
                    </label>


                    <select
                        class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            bg-white
                            px-4
                            py-3
                            text-sm
                            text-gray-700
                            outline-none
                            transition
                            focus:border-[#D4A017]
                            focus:ring-2
                            focus:ring-[#D4A017]/20
                        "
                    >

                        <option>
                            All Modules
                        </option>

                        <option>
                            Users
                        </option>

                        <option>
                            Students
                        </option>

                        <option>
                            RFID
                        </option>

                        <option>
                            Events
                        </option>

                    </select>

                </div>



                {{-- FILTER BUTTON --}}

                <div>

                    <button
                        type="button"
                        class="
                            inline-flex
                            w-full
                            items-center
                            justify-center
                            gap-2
                            rounded-xl
                            bg-[#101064]
                            px-6
                            py-3
                            text-sm
                            font-semibold
                            text-white
                            transition
                            hover:bg-[#D4A017]
                            hover:text-[#101064]
                            lg:w-auto
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
                                d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.7V19a1 1 0 01-.55.9l-4 2A1 1 0 018 21v-7.3L3.2 4.6A1 1 0 013 4z"
                            />
                        </svg>

                        Apply Filter

                    </button>

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ACTIVITY LOG TABLE --}}
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
                    Audit Trail
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Recorded Activities
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Chronological history of system and user activities.
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

                System Logs

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
                                Date & Time
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
                                User
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
                                Action
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
                                Module
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


                        {{-- LOG 1 --}}

                        <tr
                            class="
                                transition
                                hover:bg-gray-50/70
                            "
                        >

                            <td class="px-6 py-5">

                                <p
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    Sept 19, 2026
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    08:30 AM
                                </p>

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
                                            h-9
                                            w-9
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-[#F1F2FA]
                                            text-xs
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        SA
                                    </div>


                                    <span
                                        class="
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        System Administrator
                                    </span>

                                </div>

                            </td>


                            <td
                                class="
                                    px-6
                                    py-5
                                    text-sm
                                    text-gray-600
                                "
                            >
                                Created personnel account
                            </td>


                            <td class="px-6 py-5">

                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        bg-blue-50
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-blue-700
                                    "
                                >
                                    Users
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

                                    Success

                                </span>

                            </td>

                        </tr>



                        {{-- LOG 2 --}}

                        <tr
                            class="
                                transition
                                hover:bg-gray-50/70
                            "
                        >

                            <td class="px-6 py-5">

                                <p
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    Sept 19, 2026
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    09:15 AM
                                </p>

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
                                            h-9
                                            w-9
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-purple-50
                                            text-xs
                                            font-bold
                                            text-purple-700
                                        "
                                    >
                                        JR
                                    </div>


                                    <span
                                        class="
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        John Reyes
                                    </span>

                                </div>

                            </td>


                            <td
                                class="
                                    px-6
                                    py-5
                                    text-sm
                                    text-gray-600
                                "
                            >
                                Attendance scan recorded
                            </td>


                            <td class="px-6 py-5">

                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        bg-purple-50
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-purple-700
                                    "
                                >
                                    RFID
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

                                    Success

                                </span>

                            </td>

                        </tr>



                        {{-- LOG 3 --}}

                        <tr
                            class="
                                transition
                                hover:bg-gray-50/70
                            "
                        >

                            <td class="px-6 py-5">

                                <p
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    Sept 19, 2026
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    10:00 AM
                                </p>

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
                                            h-9
                                            w-9
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-[#F1F2FA]
                                            text-xs
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        SA
                                    </div>


                                    <span
                                        class="
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        System Administrator
                                    </span>

                                </div>

                            </td>


                            <td
                                class="
                                    px-6
                                    py-5
                                    text-sm
                                    text-gray-600
                                "
                            >
                                Created new event
                            </td>


                            <td class="px-6 py-5">

                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        bg-[#FFF8E1]
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-[#A87900]
                                    "
                                >
                                    Events
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

                                    Success

                                </span>

                            </td>

                        </tr>



                        {{-- LOG 4 --}}

                        <tr
                            class="
                                transition
                                hover:bg-gray-50/70
                            "
                        >

                            <td class="px-6 py-5">

                                <p
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    Sept 18, 2026
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    04:30 PM
                                </p>

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
                                            h-9
                                            w-9
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-[#F1F2FA]
                                            text-xs
                                            font-bold
                                            text-[#101064]
                                        "
                                    >
                                        SA
                                    </div>


                                    <span
                                        class="
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        System Administrator
                                    </span>

                                </div>

                            </td>


                            <td
                                class="
                                    px-6
                                    py-5
                                    text-sm
                                    text-gray-600
                                "
                            >
                                Updated student information
                            </td>


                            <td class="px-6 py-5">

                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        bg-green-50
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-green-700
                                    "
                                >
                                    Students
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

                                    Success

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
                    System activity audit trail
                </span>


                <span>
                    DySign • Activity Logs
                </span>

            </div>

        </div>

    </section>


</div>


</x-admin-layout>