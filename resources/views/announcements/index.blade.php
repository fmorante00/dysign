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
                rgba(16, 16, 100, .97) 0%,
                rgba(16, 16, 100, .92) 52%,
                rgba(16, 16, 100, .74) 100%
            ),
            url('{{ asset('images/school.jpg') }}');

        background-size: cover;
        background-position: center;
    }

</style>



@php

    /*
    |--------------------------------------------------------------------------
    | Announcement Summary
    |--------------------------------------------------------------------------
    |
    | We calculate the cards directly from the announcements already
    | supplied by AnnouncementController@index.
    |
    */

    $announcementCollection =
        collect($announcements ?? []);


    $totalAnnouncements =
        $announcementCollection->count();


    $totalRecipients =
        $announcementCollection->sum(
            function ($announcement) {

                return
                    (int) (
                        $announcement->recipient_count
                        ?? 0
                    );

            }
        );


    $sentAnnouncements =
        $announcementCollection->filter(
            function ($announcement) {

                return in_array(
                    $announcement->status,
                    [
                        'Sent',
                        'Partially Sent',
                    ]
                );

            }
        )->count();


    $processingAnnouncements =
        $announcementCollection->where(
            'status',
            'Processing'
        )->count();

@endphp



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
                    Create and publish event announcements
                    to eligible students, selected students,
                    colleges, year levels, and assigned personnel.
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
                        Module
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            font-semibold
                            text-white
                        "
                    >
                        Announcement & Publication
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
                Current announcement and delivery activity.
            </p>

        </div>



        <div
            class="
                grid
                grid-cols-1
                overflow-hidden
                rounded-2xl
                border
                border-gray-200
                bg-white
                shadow-sm
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
                            {{ number_format($totalAnnouncements) }}
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



            {{-- TOTAL RECIPIENTS --}}

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
                            Total Recipients
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
                            {{ number_format($totalRecipients) }}
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



            {{-- SENT --}}

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
                            Delivered
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
                            {{ number_format($sentAnnouncements) }}
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



            {{-- PROCESSING --}}

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
                            Processing
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
                            {{ number_format($processingAnnouncements) }}
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
                    Select an event, automatically resolve recipients,
                    and publish through the DySign announcement API.
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

                Announcement

            </div>

        </div>



        {{-- ====================================================== --}}
        {{-- AJAX SUCCESS / ERROR --}}
        {{-- ====================================================== --}}

        <div
            id="announcementSuccess"
            class="
                mb-5
                hidden
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
        ></div>


        <div
            id="announcementError"
            class="
                mb-5
                hidden
                rounded-xl
                border
                border-red-200
                bg-red-50
                px-5
                py-4
                text-sm
                text-red-700
            "
        ></div>



        <form
            id="announcementForm"
            enctype="multipart/form-data"
        >

            <div
                class="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-gray-200
                    bg-white
                    shadow-sm
                "
            >

                <div
                    class="
                        grid
                        grid-cols-1
                        lg:grid-cols-[1fr_300px]
                    "
                >


                    {{-- ====================================================== --}}
                    {{-- MAIN COMPOSER --}}
                    {{-- ====================================================== --}}

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
                                    for="event_id"
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
                                        >
                                            {{ $event->event_name }}

                                            @if($event->event_date)
                                                —
                                                {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                            @endif
                                        </option>

                                    @endforeach

                                </select>

                            </div>



                            {{-- RECIPIENT TYPE --}}

                            <div>

                                <label
                                    for="recipient_type"
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


                                    <option value="expected_participants">
                                        Expected / Eligible Participants
                                    </option>


                                    <option value="specific_students">
                                        Specific Students
                                    </option>


                                    <option value="college">
                                        Students by College
                                    </option>


                                    <option value="year">
                                        Students by Year Level
                                    </option>


                                    <option value="college_year">
                                        College + Year Level
                                    </option>


                                    <option value="all_students">
                                        All Active Students
                                    </option>


                                    <option value="assigned_personnel">
                                        Assigned Personnel
                                    </option>

                                </select>

                            </div>

                        </div>



                        {{-- ====================================================== --}}
                        {{-- DYNAMIC RECIPIENT OPTIONS --}}
                        {{-- ====================================================== --}}

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
                                    for="college"
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

                                        <option value="{{ $college }}">
                                            {{ $college }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>



                            {{-- YEAR --}}

                            <div
                                id="yearField"
                                class="mt-5 hidden"
                            >

                                <label
                                    for="year_level"
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

                                        <option value="{{ $year }}">
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
                                    for="specific_student_ids"
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
                                    id="specific_student_ids"
                                    multiple
                                    size="9"
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
                                                • Year {{ $student->year_level }}
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



                        {{-- ====================================================== --}}
                        {{-- RECIPIENT PREVIEW --}}
                        {{-- ====================================================== --}}

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


                                <div
                                    id="previewLoading"
                                    class="
                                        hidden
                                        text-xs
                                        font-medium
                                        text-gray-400
                                    "
                                >
                                    Checking...
                                </div>

                            </div>


                            <div
                                id="recipientPreviewList"
                                class="
                                    mt-3
                                    hidden
                                    space-y-1
                                    border-t
                                    border-gray-200
                                    pt-3
                                    text-xs
                                    text-gray-500
                                "
                            ></div>

                        </div>



                        {{-- ====================================================== --}}
                        {{-- SUBJECT --}}
                        {{-- ====================================================== --}}

                        <div class="mt-6">

                            <label
                                for="subject"
                                class="
                                    mb-2
                                    block
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >
                                Announcement Subject
                            </label>


                            <input
                                type="text"
                                name="subject"
                                id="subject"
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
                                If left blank, DySign can automatically
                                use the event name as the subject.
                            </p>

                        </div>



                        {{-- ====================================================== --}}
                        {{-- MESSAGE --}}
                        {{-- ====================================================== --}}

                        <div class="mt-6">

                            <label
                                for="message"
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
                                id="message"
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
                            ></textarea>

                        </div>



                        {{-- ====================================================== --}}
                        {{-- ATTACHMENTS --}}
                        {{-- ====================================================== --}}

                        <div class="mt-6">

                            <label
                                for="attachments"
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



                        {{-- ====================================================== --}}
                        {{-- SUBMIT --}}
                        {{-- ====================================================== --}}

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
                                    Publish Announcement
                                </span>

                            </button>

                        </div>

                    </div>



                    {{-- ====================================================== --}}
                    {{-- SIDE INFORMATION --}}
                    {{-- ====================================================== --}}

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
                            Automated Delivery
                        </p>


                        <h3
                            class="
                                mt-2
                                text-lg
                                font-bold
                                text-[#101064]
                            "
                        >
                            How It Works
                        </h3>


                        <p
                            class="
                                mt-2
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            DySign automatically resolves recipients,
                            stores the announcement, and queues email
                            delivery.
                        </p>



                        <div class="mt-6 space-y-5">


                            {{-- STEP 1 --}}

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
                                        Choose which event the
                                        announcement belongs to.
                                    </p>

                                </div>

                            </div>



                            {{-- STEP 2 --}}

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
                                        Resolve recipients
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            leading-5
                                            text-gray-400
                                        "
                                    >
                                        Expected Participants automatically
                                        uses the event eligibility roster.
                                    </p>

                                </div>

                            </div>



                            {{-- STEP 3 --}}

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
                                        Queue notification
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            leading-5
                                            text-gray-400
                                        "
                                    >
                                        Email delivery runs through the
                                        Laravel queue instead of blocking
                                        the page.
                                    </p>

                                </div>

                            </div>



                            {{-- STEP 4 --}}

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
                                        Publish to Student Account
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            leading-5
                                            text-gray-400
                                        "
                                    >
                                        Target students can also view
                                        the announcement inside their
                                        DySign Student Account.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

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
                    Review announcements stored in the DySign database.
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

                {{ $totalAnnouncements }}
                Record{{ $totalAnnouncements === 1 ? '' : 's' }}

            </div>

        </div>



        <div
            class="
                overflow-hidden
                rounded-2xl
                border
                border-gray-200
                bg-white
                shadow-sm
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
                                Event / Announcement
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
                                Audience
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
                                Published
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

                        @forelse(
                            $announcementCollection->sortByDesc('created_at')
                            as $announcement
                        )

                            <tr
                                class="
                                    transition
                                    hover:bg-gray-50/70
                                "
                            >

                                {{-- EVENT / SUBJECT --}}

                                <td class="px-6 py-5">

                                    <p
                                        class="
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        {{ $announcement->event->event_name ?? 'Event' }}
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-500
                                        "
                                    >
                                        {{ $announcement->subject }}
                                    </p>

                                </td>



                                {{-- AUDIENCE --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-sm
                                        text-gray-600
                                    "
                                >

                                    @switch($announcement->audience_type)

                                        @case('All')
                                            All Active Students
                                            @break


                                        @case('College')
                                            {{ $announcement->college ?? 'College' }}
                                            @break


                                        @case('Year')
                                            Year {{ $announcement->year_level ?? '—' }}
                                            @break


                                        @case('CollegeYear')
                                            {{ $announcement->college ?? 'College' }}
                                            /
                                            Year {{ $announcement->year_level ?? '—' }}
                                            @break


                                        @case('Import')
                                            Targeted Recipients
                                            @break


                                        @default
                                            {{ $announcement->audience_type ?? 'Targeted' }}

                                    @endswitch

                                </td>



                                {{-- RECIPIENT COUNT --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    {{ number_format($announcement->recipient_count ?? 0) }}
                                </td>



                                {{-- DATE --}}

                                <td class="px-6 py-5">

                                    <p
                                        class="
                                            text-sm
                                            font-medium
                                            text-gray-700
                                        "
                                    >

                                        @if($announcement->sent_at)

                                            {{ \Carbon\Carbon::parse(
                                                $announcement->sent_at
                                            )->format('M d, Y') }}

                                        @elseif($announcement->created_at)

                                            {{ \Carbon\Carbon::parse(
                                                $announcement->created_at
                                            )->format('M d, Y') }}

                                        @else

                                            —

                                        @endif

                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-400
                                        "
                                    >

                                        @if($announcement->sent_at)

                                            {{ \Carbon\Carbon::parse(
                                                $announcement->sent_at
                                            )->format('h:i A') }}

                                        @elseif($announcement->created_at)

                                            {{ \Carbon\Carbon::parse(
                                                $announcement->created_at
                                            )->format('h:i A') }}

                                        @endif

                                    </p>

                                </td>



                                {{-- STATUS --}}

                                <td class="px-6 py-5">

                                    @php

                                        $status =
                                            $announcement->status
                                            ?? 'Processing';

                                        $statusClasses =
                                            match ($status) {

                                                'Sent' =>
                                                    'bg-green-50 text-green-700',

                                                'Partially Sent' =>
                                                    'bg-yellow-50 text-yellow-700',

                                                'Failed' =>
                                                    'bg-red-50 text-red-700',

                                                default =>
                                                    'bg-blue-50 text-blue-700',

                                            };

                                        $dotClasses =
                                            match ($status) {

                                                'Sent' =>
                                                    'bg-green-500',

                                                'Partially Sent' =>
                                                    'bg-yellow-500',

                                                'Failed' =>
                                                    'bg-red-500',

                                                default =>
                                                    'bg-blue-500',

                                            };

                                    @endphp


                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-full
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            {{ $statusClasses }}
                                        "
                                    >

                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                {{ $dotClasses }}
                                            "
                                        ></span>


                                        {{ $status }}

                                    </span>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="
                                        px-6
                                        py-12
                                        text-center
                                    "
                                >

                                    <p
                                        class="
                                            text-sm
                                            font-semibold
                                            text-gray-600
                                        "
                                    >
                                        No announcements yet.
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-400
                                        "
                                    >
                                        Published announcements will
                                        appear here.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>



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



{{-- ====================================================== --}}
{{-- JAVASCRIPT --}}
{{-- ====================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | ELEMENTS
        |--------------------------------------------------------------------------
        */

        const form =
            document.getElementById(
                'announcementForm'
            );


        const eventSelect =
            document.getElementById(
                'event_id'
            );


        const recipientType =
            document.getElementById(
                'recipient_type'
            );


        const collegeField =
            document.getElementById(
                'collegeField'
            );


        const collegeSelect =
            document.getElementById(
                'college'
            );


        const yearField =
            document.getElementById(
                'yearField'
            );


        const yearSelect =
            document.getElementById(
                'year_level'
            );


        const specificStudentsField =
            document.getElementById(
                'specificStudentsField'
            );


        const specificStudents =
            document.getElementById(
                'specific_student_ids'
            );


        const attachmentInput =
            document.getElementById(
                'attachments'
            );


        const selectedFiles =
            document.getElementById(
                'selectedFiles'
            );


        const recipientPreview =
            document.getElementById(
                'recipientPreview'
            );


        const recipientPreviewCount =
            document.getElementById(
                'recipientPreviewCount'
            );


        const recipientPreviewList =
            document.getElementById(
                'recipientPreviewList'
            );


        const previewLoading =
            document.getElementById(
                'previewLoading'
            );


        const sendButton =
            document.getElementById(
                'sendAnnouncementButton'
            );


        const sendButtonText =
            document.getElementById(
                'sendButtonText'
            );


        const successBox =
            document.getElementById(
                'announcementSuccess'
            );


        const errorBox =
            document.getElementById(
                'announcementError'
            );


        const csrfToken =
            document
                .querySelector(
                    'meta[name="csrf-token"]'
                )
                ?.getAttribute(
                    'content'
                );


        let previewTimer = null;



        /*
        |--------------------------------------------------------------------------
        | ALERTS
        |--------------------------------------------------------------------------
        */

        function hideAlerts() {

            successBox
                .classList
                .add(
                    'hidden'
                );


            errorBox
                .classList
                .add(
                    'hidden'
                );


            successBox.textContent = '';
            errorBox.innerHTML = '';

        }



        function showSuccess(
            message
        ) {

            errorBox
                .classList
                .add(
                    'hidden'
                );


            successBox.textContent =
                message;


            successBox
                .classList
                .remove(
                    'hidden'
                );


            successBox.scrollIntoView({
                behavior:
                    'smooth',

                block:
                    'center',
            });

        }



        function showError(
            message
        ) {

            successBox
                .classList
                .add(
                    'hidden'
                );


            errorBox.textContent =
                message;


            errorBox
                .classList
                .remove(
                    'hidden'
                );


            errorBox.scrollIntoView({
                behavior:
                    'smooth',

                block:
                    'center',
            });

        }



        /*
        |--------------------------------------------------------------------------
        | VALIDATION ERROR PARSER
        |--------------------------------------------------------------------------
        */

        function validationMessage(
            data
        ) {

            if (
                data?.errors
                && typeof data.errors
                    === 'object'
            ) {

                const messages = [];


                Object
                    .values(
                        data.errors
                    )
                    .forEach(
                        function (errors) {

                            if (
                                Array.isArray(
                                    errors
                                )
                            ) {

                                errors.forEach(
                                    function (error) {

                                        messages.push(
                                            error
                                        );

                                    }
                                );

                            }

                        }
                    );


                if (
                    messages.length > 0
                ) {

                    return messages.join(
                        ' '
                    );

                }

            }


            return (
                data?.message
                ?? 'Something went wrong.'
            );

        }



        /*
        |--------------------------------------------------------------------------
        | RECIPIENT FIELDS
        |--------------------------------------------------------------------------
        */

        function updateRecipientFields() {

            const value =
                recipientType.value;


            collegeField
                .classList
                .add(
                    'hidden'
                );


            yearField
                .classList
                .add(
                    'hidden'
                );


            specificStudentsField
                .classList
                .add(
                    'hidden'
                );


            collegeSelect.required =
                false;


            yearSelect.required =
                false;


            if (
                value === 'college'
            ) {

                collegeField
                    .classList
                    .remove(
                        'hidden'
                    );


                collegeSelect.required =
                    true;

            }


            if (
                value === 'year'
            ) {

                yearField
                    .classList
                    .remove(
                        'hidden'
                    );


                yearSelect.required =
                    true;

            }


            if (
                value === 'college_year'
            ) {

                collegeField
                    .classList
                    .remove(
                        'hidden'
                    );


                yearField
                    .classList
                    .remove(
                        'hidden'
                    );


                collegeSelect.required =
                    true;


                yearSelect.required =
                    true;

            }


            if (
                value === 'specific_students'
            ) {

                specificStudentsField
                    .classList
                    .remove(
                        'hidden'
                    );

            }


            schedulePreview();

        }



        /*
        |--------------------------------------------------------------------------
        | BUILD PREVIEW PAYLOAD
        |--------------------------------------------------------------------------
        */

        function previewPayload() {

            const studentIds =
                Array.from(
                    specificStudents
                        .selectedOptions
                )
                .map(
                    function (option) {

                        return Number(
                            option.value
                        );

                    }
                );


            return {

                event_id:
                    eventSelect.value
                        ? Number(
                            eventSelect.value
                        )
                        : null,


                recipient_type:
                    recipientType.value,


                college:
                    collegeSelect.value
                        || null,


                year_level:
                    yearSelect.value
                        ? Number(
                            yearSelect.value
                        )
                        : null,


                specific_student_ids:
                    studentIds,

            };

        }



        /*
        |--------------------------------------------------------------------------
        | CAN PREVIEW?
        |--------------------------------------------------------------------------
        */

        function canPreview(
            payload
        ) {

            if (
                !payload.event_id
                || !payload.recipient_type
            ) {

                return false;

            }


            if (
                payload.recipient_type
                === 'college'
                && !payload.college
            ) {

                return false;

            }


            if (
                payload.recipient_type
                === 'year'
                && !payload.year_level
            ) {

                return false;

            }


            if (
                payload.recipient_type
                === 'college_year'
                &&
                (
                    !payload.college
                    || !payload.year_level
                )
            ) {

                return false;

            }


            if (
                payload.recipient_type
                === 'specific_students'
                &&
                payload
                    .specific_student_ids
                    .length === 0
            ) {

                return false;

            }


            return true;

        }



        /*
        |--------------------------------------------------------------------------
        | RECIPIENT PREVIEW API
        |--------------------------------------------------------------------------
        */

        async function loadRecipientPreview() {

            const payload =
                previewPayload();


            if (
                !canPreview(
                    payload
                )
            ) {

                recipientPreview
                    .classList
                    .add(
                        'hidden'
                    );


                return;

            }


            recipientPreview
                .classList
                .remove(
                    'hidden'
                );


            recipientPreviewCount
                .textContent =
                    'Checking recipients...';


            recipientPreviewList
                .classList
                .add(
                    'hidden'
                );


            previewLoading
                .classList
                .remove(
                    'hidden'
                );


            try {

                const response =
                    await fetch(
                        '/api/announcements/preview-recipients',
                        {

                            method:
                                'POST',

                            credentials:
                                'same-origin',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',

                                'X-CSRF-TOKEN':
                                    csrfToken,

                            },

                            body:
                                JSON.stringify(
                                    payload
                                ),

                        }
                    );


                const data =
                    await response.json();


                previewLoading
                    .classList
                    .add(
                        'hidden'
                    );


                if (
                    !response.ok
                    || !data.success
                ) {

                    recipientPreviewCount
                        .textContent =
                            validationMessage(
                                data
                            );


                    recipientPreviewList
                        .classList
                        .add(
                            'hidden'
                        );


                    return;

                }


                recipientPreviewCount
                    .textContent =
                        `${data.count} recipient${data.count === 1 ? '' : 's'} will receive this announcement.`;


                if (
                    Array.isArray(
                        data.recipients
                    )
                    && data.recipients.length > 0
                ) {

                    recipientPreviewList
                        .innerHTML =
                            data.recipients
                                .slice(
                                    0,
                                    10
                                )
                                .map(
                                    function (
                                        recipient
                                    ) {

                                        const name =
                                            escapeHtml(
                                                recipient.name
                                                ?? 'Recipient'
                                            );


                                        const email =
                                            escapeHtml(
                                                recipient.email
                                                ?? ''
                                            );


                                        return `
                                            <div
                                                class="
                                                    flex
                                                    items-center
                                                    justify-between
                                                    gap-4
                                                "
                                            >
                                                <span>
                                                    ${name}
                                                </span>

                                                <span
                                                    class="
                                                        text-gray-400
                                                    "
                                                >
                                                    ${email}
                                                </span>
                                            </div>
                                        `;

                                    }
                                )
                                .join(
                                    ''
                                );


                    if (
                        data.recipients.length > 10
                    ) {

                        recipientPreviewList
                            .insertAdjacentHTML(
                                'beforeend',
                                `
                                    <p
                                        class="
                                            pt-2
                                            font-medium
                                            text-[#101064]
                                        "
                                    >
                                        + ${
                                            data.recipients.length
                                            - 10
                                        } more recipient(s)
                                    </p>
                                `
                            );

                    }


                    recipientPreviewList
                        .classList
                        .remove(
                            'hidden'
                        );

                }

            }
            catch (
                error
            ) {

                console.error(
                    error
                );


                previewLoading
                    .classList
                    .add(
                        'hidden'
                    );


                recipientPreviewCount
                    .textContent =
                        'Unable to preview recipients.';


                recipientPreviewList
                    .classList
                    .add(
                        'hidden'
                    );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | PREVIEW DEBOUNCE
        |--------------------------------------------------------------------------
        */

        function schedulePreview() {

            clearTimeout(
                previewTimer
            );


            previewTimer =
                setTimeout(
                    loadRecipientPreview,
                    300
                );

        }



        /*
        |--------------------------------------------------------------------------
        | ATTACHMENT PREVIEW
        |--------------------------------------------------------------------------
        */

        attachmentInput
            .addEventListener(
                'change',
                function () {

                    selectedFiles.innerHTML =
                        '';


                    const files =
                        Array.from(
                            attachmentInput.files
                        );


                    if (
                        files.length === 0
                    ) {

                        selectedFiles
                            .classList
                            .add(
                                'hidden'
                            );


                        return;

                    }


                    selectedFiles
                        .classList
                        .remove(
                            'hidden'
                        );


                    files.forEach(
                        function (
                            file
                        ) {

                            const item =
                                document
                                    .createElement(
                                        'div'
                                    );


                            const sizeMb =
                                (
                                    file.size
                                    / 1024
                                    / 1024
                                )
                                .toFixed(
                                    2
                                );


                            item.className =
                                'flex items-center justify-between gap-4 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs text-gray-600';


                            item.innerHTML =
                                `
                                    <span>
                                        ${escapeHtml(
                                            file.name
                                        )}
                                    </span>

                                    <span
                                        class="
                                            shrink-0
                                            text-gray-400
                                        "
                                    >
                                        ${sizeMb} MB
                                    </span>
                                `;


                            selectedFiles
                                .appendChild(
                                    item
                                );

                        }
                    );

                }
            );



        /*
        |--------------------------------------------------------------------------
        | SUBMIT ANNOUNCEMENT THROUGH REST API
        |--------------------------------------------------------------------------
        */

        form.addEventListener(
            'submit',
            async function (
                event
            ) {

                event.preventDefault();


                hideAlerts();


                if (
                    !form.checkValidity()
                ) {

                    form.reportValidity();

                    return;

                }


                const type =
                    recipientType.value;


                if (
                    type
                    === 'specific_students'
                    &&
                    specificStudents
                        .selectedOptions
                        .length === 0
                ) {

                    showError(
                        'Please select at least one student.'
                    );


                    return;

                }


                sendButton.disabled =
                    true;


                sendButtonText.textContent =
                    'Publishing...';


                try {

                    /*
                    |--------------------------------------------------------------------------
                    | FormData automatically includes:
                    |
                    | event_id
                    | recipient_type
                    | college
                    | year_level
                    | specific_student_ids[]
                    | subject
                    | message
                    | attachments[]
                    |--------------------------------------------------------------------------
                    */

                    const formData =
                        new FormData(
                            form
                        );


                    const response =
                        await fetch(
                            '/api/announcements',
                            {

                                method:
                                    'POST',

                                credentials:
                                    'same-origin',

                                headers: {

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'X-CSRF-TOKEN':
                                        csrfToken,

                                },

                                body:
                                    formData,

                            }
                        );


                    const data =
                        await response.json();


                    if (
                        !response.ok
                        || !data.success
                    ) {

                        throw new Error(
                            validationMessage(
                                data
                            )
                        );

                    }


                    showSuccess(
                        data.message
                        ?? 'Announcement published successfully. Email delivery has been queued.'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | RESET FORM
                    |--------------------------------------------------------------------------
                    */

                    form.reset();


                    selectedFiles.innerHTML =
                        '';


                    selectedFiles
                        .classList
                        .add(
                            'hidden'
                        );


                    recipientPreview
                        .classList
                        .add(
                            'hidden'
                        );


                    updateRecipientFields();


                    /*
                    |--------------------------------------------------------------------------
                    | Reload after a short delay so summary and history
                    | show the newly created announcement.
                    |--------------------------------------------------------------------------
                    */

                    setTimeout(
                        function () {

                            window.location.reload();

                        },
                        900
                    );

                }
                catch (
                    error
                ) {

                    console.error(
                        error
                    );


                    showError(
                        error.message
                        ?? 'Unable to publish announcement.'
                    );

                }
                finally {

                    sendButton.disabled =
                        false;


                    sendButtonText.textContent =
                        'Publish Announcement';

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | HTML ESCAPING
        |--------------------------------------------------------------------------
        */

        function escapeHtml(
            value
        ) {

            return String(
                value ?? ''
            )
            .replaceAll(
                '&',
                '&amp;'
            )
            .replaceAll(
                '<',
                '&lt;'
            )
            .replaceAll(
                '>',
                '&gt;'
            )
            .replaceAll(
                '"',
                '&quot;'
            )
            .replaceAll(
                "'",
                '&#039;'
            );

        }



        /*
        |--------------------------------------------------------------------------
        | LISTENERS
        |--------------------------------------------------------------------------
        */

        recipientType
            .addEventListener(
                'change',
                updateRecipientFields
            );


        eventSelect
            .addEventListener(
                'change',
                schedulePreview
            );


        collegeSelect
            .addEventListener(
                'change',
                schedulePreview
            );


        yearSelect
            .addEventListener(
                'change',
                schedulePreview
            );


        specificStudents
            .addEventListener(
                'change',
                schedulePreview
            );



        /*
        |--------------------------------------------------------------------------
        | INITIAL STATE
        |--------------------------------------------------------------------------
        */

        updateRecipientFields();

    }
);

</script>

</x-admin-layout>