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
                Prepare the announcement, select recipients,
                and optionally attach images or PDF documents.
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


    @if(session('success'))

        <div
            class="
                mb-5
                rounded-xl
                border
                border-green-200
                bg-green-50
                px-5
                py-4
                text-sm
                font-medium
                text-green-700
            "
        >
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div
            class="
                mb-5
                rounded-xl
                border
                border-red-200
                bg-red-50
                px-5
                py-4
                text-sm
                font-medium
                text-red-700
            "
        >
            {{ session('error') }}
        </div>

    @endif


    @if($errors->any())

        <div
            class="
                mb-5
                rounded-xl
                border
                border-red-200
                bg-red-50
                px-5
                py-4
                text-sm
                text-red-700
            "
        >

            <p class="font-semibold">
                Please check the announcement details.
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('announcements.store') }}"
        enctype="multipart/form-data"
        id="announcementForm"
    >

        @csrf


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
                                name="event_id"
                                id="event_id"
                                required
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

                                <option value="">
                                    Select an event
                                </option>

                                @foreach($events as $event)

                                    <option
                                        value="{{ $event->event_id }}"
                                        @selected(
                                            old('event_id') ==
                                            $event->event_id
                                        )
                                    >
                                        {{ $event->event_name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- RECIPIENT TYPE --}}

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
                                name="recipient_type"
                                id="recipient_type"
                                required
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

                                <option value="">
                                    Select recipients
                                </option>

                                <option
                                    value="all_students"
                                    @selected(
                                        old('recipient_type')
                                        === 'all_students'
                                    )
                                >
                                    All Active Students
                                </option>

                                <option
                                    value="assigned_personnel"
                                    @selected(
                                        old('recipient_type')
                                        === 'assigned_personnel'
                                    )
                                >
                                    Assigned Personnel
                                </option>

                                <option
                                    value="specific_students"
                                    @selected(
                                        old('recipient_type')
                                        === 'specific_students'
                                    )
                                >
                                    Specific Students
                                </option>

                                <option
                                    value="college"
                                    @selected(
                                        old('recipient_type')
                                        === 'college'
                                    )
                                >
                                    Students by College
                                </option>

                                <option
                                    value="year"
                                    @selected(
                                        old('recipient_type')
                                        === 'year'
                                    )
                                >
                                    Students by Year Level
                                </option>

                                <option
                                    value="college_year"
                                    @selected(
                                        old('recipient_type')
                                        === 'college_year'
                                    )
                                >
                                    College + Year Level
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- DYNAMIC RECIPIENT OPTIONS --}}

                    <div
                        id="recipientOptions"
                        class="mt-5"
                    >


                        {{-- COLLEGE --}}

                        <div
                            id="collegeField"
                            class="hidden"
                        >

                            <label
                                class="
                                    mb-2
                                    block
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >
                                College
                            </label>

                            <select
                                name="college"
                                id="college"
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

                                <option value="">
                                    Select college
                                </option>

                                @foreach($colleges as $college)

                                    <option
                                        value="{{ $college }}"
                                        @selected(
                                            old('college')
                                            === $college
                                        )
                                    >
                                        {{ $college }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- YEAR LEVEL --}}

                        <div
                            id="yearField"
                            class="mt-5 hidden"
                        >

                            <label
                                class="
                                    mb-2
                                    block
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >
                                Year Level
                            </label>

                            <select
                                name="year_level"
                                id="year_level"
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

                                <option value="">
                                    Select year level
                                </option>

                                @foreach($yearLevels as $year)

                                    <option
                                        value="{{ $year }}"
                                        @selected(
                                            old('year_level')
                                            == $year
                                        )
                                    >
                                        Year {{ $year }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- SPECIFIC STUDENTS --}}

                        <div
                            id="specificStudentsField"
                            class="hidden"
                        >

                            <label
                                class="
                                    mb-2
                                    block
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >
                                Select Students
                            </label>

                            <select
                                name="specific_student_ids[]"
                                multiple
                                size="8"
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

                                @foreach($students as $student)

                                    <option
                                        value="{{ $student->student_id }}"
                                    >
                                        {{ $student->student_number }}
                                        —
                                        {{ $student->last_name }},
                                        {{ $student->first_name }}

                                        @if($student->program_code)
                                            —
                                            {{ $student->program_code }}
                                        @endif

                                        @if($student->year_level)
                                            Year {{ $student->year_level }}
                                        @endif
                                    </option>

                                @endforeach

                            </select>

                            <p
                                class="
                                    mt-2
                                    text-xs
                                    text-gray-400
                                "
                            >
                                Hold Ctrl while clicking to select
                                multiple students.
                            </p>

                        </div>

                    </div>


                    {{-- SUBJECT --}}

                    <div class="mt-6">

                        <label
                            class="
                                mb-2
                                block
                                text-sm
                                font-semibold
                                text-gray-600
                            "
                        >
                            Email Subject
                        </label>

                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject') }}"
                            maxlength="255"
                            placeholder="Example: Orientation Reminder"
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
                                placeholder:text-gray-400
                                focus:border-[#D4A017]
                                focus:ring-2
                                focus:ring-[#D4A017]/20
                            "
                        >

                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            If left blank, DySign will automatically
                            use the event name as the subject.
                        </p>

                    </div>


                    {{-- MESSAGE --}}

                    <div class="mt-6">

                        <label
                            class="
                                mb-2
                                block
                                text-sm
                                font-semibold
                                text-gray-600
                            "
                        >
                            Announcement Message
                        </label>

                        <textarea
                            name="message"
                            rows="7"
                            required
                            maxlength="5000"
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
                        >{{ old('message') }}</textarea>

                    </div>


                    {{-- ATTACHMENTS --}}

                    <div class="mt-6">

                        <label
                            class="
                                mb-2
                                block
                                text-sm
                                font-semibold
                                text-gray-600
                            "
                        >
                            Attach Files
                        </label>

                        <div
                            class="
                                rounded-xl
                                border
                                border-dashed
                                border-gray-300
                                bg-gray-50/60
                                p-5
                            "
                        >

                            <input
                                type="file"
                                name="attachments[]"
                                id="attachments"
                                multiple
                                accept=".jpg,.jpeg,.png,.pdf"
                                class="
                                    block
                                    w-full
                                    text-sm
                                    text-gray-600
                                    file:mr-4
                                    file:rounded-lg
                                    file:border-0
                                    file:bg-[#101064]
                                    file:px-4
                                    file:py-2.5
                                    file:text-sm
                                    file:font-semibold
                                    file:text-white
                                    hover:file:bg-[#D4A017]
                                    hover:file:text-[#101064]
                                "
                            >

                            <p
                                class="
                                    mt-3
                                    text-xs
                                    leading-5
                                    text-gray-400
                                "
                            >
                                Up to 5 files. JPG, JPEG, PNG, or PDF.
                                Maximum 10 MB per file.
                            </p>

                            <div
                                id="selectedFiles"
                                class="
                                    mt-3
                                    hidden
                                    space-y-2
                                "
                            ></div>

                        </div>

                    </div>


                    {{-- RECIPIENT PREVIEW --}}

                    <div
                        id="recipientPreview"
                        class="
                            mt-6
                            hidden
                            rounded-xl
                            border
                            border-gray-200
                            bg-gray-50
                            px-5
                            py-4
                        "
                    >

                        <p
                            class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-[0.18em]
                                text-[#D4A017]
                            "
                        >
                            Recipient Preview
                        </p>

                        <p
                            id="recipientPreviewCount"
                            class="
                                mt-2
                                text-sm
                                font-semibold
                                text-[#101064]
                            "
                        ></p>

                    </div>


                    {{-- ACTION --}}

                    <div
                        class="
                            mt-7
                            flex
                            justify-end
                        "
                    >

                        <button
                            type="submit"
                            id="sendAnnouncementButton"
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
                                disabled:cursor-not-allowed
                                disabled:opacity-60
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

                            <span id="sendButtonText">
                                Send Announcement
                            </span>

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
                        Review the event, recipients,
                        announcement message, and attachments
                        before sending.
                    </p>


                    <div class="mt-6 space-y-5">

                        <div class="flex gap-3">

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
                                    text-sm
                                    font-bold
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
                                    Choose the event related
                                    to the announcement.
                                </p>

                            </div>

                        </div>


                        <div class="flex gap-3">

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
                                    text-sm
                                    font-bold
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
                                    DySign will only send to
                                    records with valid email addresses.
                                </p>

                            </div>

                        </div>


                        <div class="flex gap-3">

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
                                    text-sm
                                    font-bold
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
                                    Add attachments
                                </p>

                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-gray-400
                                    "
                                >
                                    Optional JPG, PNG, or PDF
                                    files will be included in the email.
                                </p>

                            </div>

                        </div>


                        <div class="flex gap-3">

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
                                    text-sm
                                    font-bold
                                    text-[#101064]
                                    shadow-sm
                                "
                            >
                                4
                            </div>

                            <div>

                                <p
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    Send and record
                                </p>

                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-gray-400
                                    "
                                >
                                    Delivery results are stored
                                    in the announcement history.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</section>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const recipientType =
        document.getElementById('recipient_type');

    const eventSelect =
        document.getElementById('event_id');

    const collegeField =
        document.getElementById('collegeField');

    const yearField =
        document.getElementById('yearField');

    const specificStudentsField =
        document.getElementById('specificStudentsField');

    const attachmentInput =
        document.getElementById('attachments');

    const selectedFiles =
        document.getElementById('selectedFiles');

    const form =
        document.getElementById('announcementForm');

    const sendButton =
        document.getElementById('sendAnnouncementButton');

    const sendButtonText =
        document.getElementById('sendButtonText');


    function updateRecipientFields() {

        const value = recipientType.value;

        collegeField.classList.add('hidden');
        yearField.classList.add('hidden');
        specificStudentsField.classList.add('hidden');


        if (value === 'college') {
            collegeField.classList.remove('hidden');
        }


        if (value === 'year') {
            yearField.classList.remove('hidden');
        }


        if (value === 'college_year') {

            collegeField.classList.remove('hidden');
            yearField.classList.remove('hidden');

        }


        if (value === 'specific_students') {
            specificStudentsField.classList.remove('hidden');
        }
    }


    recipientType.addEventListener(
        'change',
        updateRecipientFields
    );


    updateRecipientFields();


    attachmentInput.addEventListener(
        'change',
        function () {

            selectedFiles.innerHTML = '';

            const files = Array.from(
                attachmentInput.files
            );


            if (files.length === 0) {

                selectedFiles.classList.add('hidden');
                return;

            }


            selectedFiles.classList.remove('hidden');


            files.forEach(function (file) {

                const item =
                    document.createElement('div');

                item.className =
                    'rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs text-gray-600';

                item.textContent =
                    file.name;

                selectedFiles.appendChild(item);

            });

        }
    );


    form.addEventListener(
        'submit',
        function () {

            sendButton.disabled = true;

            sendButtonText.textContent =
                'Sending...';

        }
    );

});
</script>



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