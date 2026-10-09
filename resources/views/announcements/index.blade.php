<x-admin-layout>

<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .announcement-hero {
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
            announcement-hero
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
                    Event Communication
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
                    Event Announcements
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
                    Create and manage event announcements,
                    reminders, and notifications for students
                    and authorized personnel.
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
                            d="M11 5h2m-7 4h12l2 3-2 3H6l-2-3 2-3zm3 6v4m6-4v4"
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
                        Communication Type
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            font-semibold
                            text-white
                        "
                    >
                        Event Notifications
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
                Announcement Overview
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Communication Summary
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Overview of announcement and notification activity.
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


            {{-- TOTAL ANNOUNCEMENTS --}}

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
                            Total Announcements
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
                            24
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
                                d="M8 10h8m-8 4h5M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H9l-4 3v-3a2 2 0 01-2-2V7a2 2 0 012-2z"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- SENT NOTIFICATIONS --}}

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
                            Sent Notifications
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
                            1,245
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
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- UPCOMING REMINDERS --}}

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
                            Upcoming Reminders
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
                            8
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
                                d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- RECIPIENTS --}}

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
                            Recipients
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
                            2,540
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

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- CREATE ANNOUNCEMENT --}}
    {{-- ====================================================== --}}

    <section>

        <div
            class="
                mb-4
                flex
                flex-col
                gap-4
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
                    Announcement Composer
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Create Announcement
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Prepare event information and select the intended recipients.
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

                New Announcement

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

            <div
                class="
                    grid
                    grid-cols-1
                    lg:grid-cols-[1fr_300px]
                "
            >


                {{-- MAIN COMPOSER --}}

                <div
                    class="
                        border-b
                        border-gray-100
                        px-7
                        py-7
                        lg:border-b-0
                        lg:border-r
                    "
                >

                    <div
                        class="
                            grid
                            grid-cols-1
                            gap-5
                            md:grid-cols-2
                        "
                    >


                        {{-- EVENT --}}

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
                                Select Event
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
                                    Freshmen Orientation 2026
                                </option>

                                <option>
                                    Leadership Training Seminar
                                </option>

                                <option>
                                    College Assembly
                                </option>

                            </select>

                        </div>



                        {{-- RECIPIENTS --}}

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
                                Recipients
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
                                    Expected Participants
                                </option>

                                <option>
                                    Assigned Personnel
                                </option>

                                <option>
                                    All Active Students
                                </option>

                                <option>
                                    Specific Students
                                </option>

                            </select>

                        </div>

                    </div>



                    {{-- MESSAGE --}}

                    <div class="mt-6">

                        <div
                            class="
                                mb-2
                                flex
                                items-center
                                justify-between
                                gap-4
                            "
                        >

                            <label
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >
                                Announcement Message
                            </label>


                            <span
                                class="
                                    text-xs
                                    text-gray-400
                                "
                            >
                                Message content
                            </span>

                        </div>


                        <textarea
                            rows="6"
                            class="
                                w-full
                                resize-none
                                rounded-xl
                                border
                                border-gray-300
                                bg-white
                                px-4
                                py-3
                                text-sm
                                leading-6
                                text-gray-700
                                outline-none
                                transition
                                placeholder:text-gray-400
                                focus:border-[#D4A017]
                                focus:ring-2
                                focus:ring-[#D4A017]/20
                            "
                            placeholder="Enter the event announcement or reminder..."
                        ></textarea>

                    </div>



                    {{-- ACTION --}}

                    <div
                        class="
                            mt-6
                            flex
                            justify-end
                        "
                    >

                        <button
                            type="button"
                            class="
                                inline-flex
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
                                    d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"
                                />
                            </svg>

                            Send Announcement

                        </button>

                    </div>

                </div>



                {{-- SIDE INFORMATION --}}

                <div
                    class="
                        bg-gray-50/60
                        px-7
                        py-7
                    "
                >

                    <p
                        class="
                            text-[10px]
                            font-bold
                            uppercase
                            tracking-[0.22em]
                            text-[#D4A017]
                        "
                    >
                        Delivery Guide
                    </p>


                    <h3
                        class="
                            mt-2
                            text-lg
                            font-bold
                            text-[#101064]
                        "
                    >
                        Before Sending
                    </h3>


                    <p
                        class="
                            mt-2
                            text-sm
                            leading-6
                            text-gray-500
                        "
                    >
                        Review the selected event, recipient group,
                        and announcement message before sending.
                    </p>



                    <div
                        class="
                            mt-6
                            space-y-4
                        "
                    >


                        <div
                            class="
                                flex
                                items-start
                                gap-3
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-8
                                    w-8
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-lg
                                    bg-white
                                    text-[#101064]
                                    shadow-sm
                                "
                            >
                                1
                            </div>


                            <div>

                                <p
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    Select the event
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-gray-400
                                    "
                                >
                                    Choose the event related to the message.
                                </p>

                            </div>

                        </div>



                        <div
                            class="
                                flex
                                items-start
                                gap-3
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-8
                                    w-8
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-lg
                                    bg-white
                                    text-[#101064]
                                    shadow-sm
                                "
                            >
                                2
                            </div>


                            <div>

                                <p
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    Choose recipients
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-gray-400
                                    "
                                >
                                    Define who should receive the announcement.
                                </p>

                            </div>

                        </div>



                        <div
                            class="
                                flex
                                items-start
                                gap-3
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-8
                                    w-8
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-lg
                                    bg-white
                                    text-[#101064]
                                    shadow-sm
                                "
                            >
                                3
                            </div>


                            <div>

                                <p
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    Review the message
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-gray-400
                                    "
                                >
                                    Confirm the information before sending.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- NOTIFICATION HISTORY --}}
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
                    Announcement Records
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Notification History
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Review previously sent and scheduled event notifications.
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

                Notification Archive

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
                        min-w-[820px]
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
                                Event
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
                                Recipients
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
                                Date Sent
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


                        {{-- NOTIFICATION 1 --}}

                        <tr
                            class="
                                transition
                                hover:bg-gray-50/70
                            "
                        >

                            <td class="px-6 py-5">

                                <p
                                    class="
                                        font-semibold
                                        text-[#101064]
                                    "
                                >
                                    Freshmen Orientation 2026
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    Event announcement
                                </p>

                            </td>


                            <td
                                class="
                                    px-6
                                    py-5
                                    text-sm
                                    text-gray-600
                                "
                            >
                                BSIT First Year Students
                            </td>


                            <td class="px-6 py-5">

                                <p
                                    class="
                                        text-sm
                                        font-medium
                                        text-gray-700
                                    "
                                >
                                    September 20, 2026
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    Notification sent
                                </p>

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

                                    Sent

                                </span>

                            </td>

                        </tr>



                        {{-- NOTIFICATION 2 --}}

                        <tr
                            class="
                                transition
                                hover:bg-gray-50/70
                            "
                        >

                            <td class="px-6 py-5">

                                <p
                                    class="
                                        font-semibold
                                        text-[#101064]
                                    "
                                >
                                    Leadership Training Seminar
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    Event reminder
                                </p>

                            </td>


                            <td
                                class="
                                    px-6
                                    py-5
                                    text-sm
                                    text-gray-600
                                "
                            >
                                Assigned Personnel
                            </td>


                            <td class="px-6 py-5">

                                <p
                                    class="
                                        text-sm
                                        font-medium
                                        text-gray-700
                                    "
                                >
                                    September 22, 2026
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    Scheduled delivery
                                </p>

                            </td>


                            <td class="px-6 py-5">

                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-full
                                        bg-blue-50
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-blue-700
                                    "
                                >

                                    <span
                                        class="
                                            h-1.5
                                            w-1.5
                                            rounded-full
                                            bg-blue-500
                                        "
                                    ></span>

                                    Scheduled

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
                    Event communication history
                </span>


                <span>
                    DySign • Event Announcements
                </span>

            </div>

        </div>

    </section>


</div>


</x-admin-layout>