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

<style>
    /* Announcement workspace: independent of arbitrary Tailwind grid compilation. */
    .dy-announcement-workspace { --dy-navy:#101064; --dy-gold:#d4a017; }
    .dy-announcement-workspace .dy-composer-grid { display:grid; grid-template-columns:minmax(0,1fr) 260px; }
    .dy-announcement-workspace .dy-recipient-grid { display:grid; grid-template-columns:minmax(0,1.25fr) minmax(270px,.8fr); }
    .dy-announcement-workspace .dy-picker-filters { display:grid; grid-template-columns:minmax(0,1.45fr) minmax(130px,.8fr) minmax(120px,.7fr); gap:12px; align-items:end; }
    .dy-announcement-workspace .dy-composer-target-filters { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
    .dy-announcement-workspace .dy-composer-target-filters #yearField { margin-top:0; }
    .dy-announcement-workspace .dy-composer-target-filters #specificStudentsField { grid-column:1/-1; }
    .dy-announcement-workspace .dy-history-filters { display:grid; grid-template-columns:minmax(0,1.4fr) minmax(150px,.7fr) 135px auto; gap:12px; align-items:end; }
    .dy-announcement-workspace .dy-control-label { display:block; margin-bottom:6px; color:#64748b; font-size:10px; font-weight:800; letter-spacing:.09em; text-transform:uppercase; }
    .dy-announcement-workspace .dy-control { display:block; width:100%; min-width:0; border:1px solid #dbe2ee; border-radius:11px; background:#fff; color:#101064; padding:10px 12px; font-size:13px; min-height:42px; }
    .dy-announcement-workspace .dy-control:focus { outline:2px solid rgba(212,160,23,.28); border-color:#d4a017; outline-offset:0; }
    .dy-announcement-workspace .dy-pager { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; padding-top:12px; border-top:1px solid #e7ebf4; }
    .dy-announcement-workspace .dy-pager-controls { display:flex; align-items:center; gap:8px; }
    .dy-announcement-workspace .dy-page-button { border:1px solid #d6deeb; border-radius:9px; background:#fff; padding:7px 12px; color:#101064; font-weight:700; font-size:12px; transition:background .15s; }
    .dy-announcement-workspace .dy-page-button:not(:disabled):hover { background:#eef1ff; }
    .dy-announcement-workspace .dy-page-button:disabled { opacity:.42; cursor:not-allowed; }
    .dy-announcement-workspace .dy-primary-soft { border:1px solid #c8d0ef; border-radius:10px; background:#f0f2ff; color:#101064; font-size:12px; font-weight:800; padding:9px 12px; }
    .dy-announcement-workspace .dy-primary-soft:hover:not(:disabled) { background:#e5eaff; }
    .dy-announcement-workspace .dy-primary-soft:disabled { opacity:.45; }
    .dy-announcement-workspace .dy-list-frame { height:332px; overflow:auto; overscroll-behavior:contain; }
    .dy-announcement-workspace .dy-selected-frame { height:280px; overflow:auto; overscroll-behavior:contain; }
    .dy-announcement-workspace .dy-preview-row { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:9px 0; border-bottom:1px solid #edf0f5; }
    .dy-announcement-workspace .dy-recipient-preview-list { margin-top:14px; max-height:315px; overflow:auto; }
    .dy-announcement-workspace .dy-history-body tr[hidden] { display:none!important; }
    .dy-announcement-workspace .dy-history-bar { border-bottom:1px solid #e5e9f2; background:#fbfcff; padding:16px 20px; }
    .dy-announcement-workspace .dy-subtle-note { font-size:11px; color:#718096; line-height:1.55; }
    @media (max-width:1200px) { .dy-announcement-workspace .dy-composer-grid { grid-template-columns:minmax(0,1fr); } .dy-announcement-workspace .dy-recipient-grid { grid-template-columns:minmax(0,1.2fr) minmax(250px,.8fr); } }
    @media (max-width:850px) { .dy-announcement-workspace .dy-recipient-grid { grid-template-columns:minmax(0,1fr); } .dy-announcement-workspace .dy-picker-filters { grid-template-columns:minmax(0,1fr) minmax(0,1fr); } .dy-announcement-workspace .dy-picker-search { grid-column:1/-1; } .dy-announcement-workspace .dy-history-filters { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media (max-width:580px) { .dy-announcement-workspace .dy-picker-filters,.dy-announcement-workspace .dy-history-filters,.dy-announcement-workspace .dy-composer-target-filters { grid-template-columns:minmax(0,1fr); } .dy-announcement-workspace .dy-picker-search { grid-column:auto; } .dy-announcement-workspace .dy-list-frame { height:300px; } }
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



<div class="dy-announcement-workspace min-w-0 space-y-8">


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
                            Sent / Partial
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
                        dy-composer-grid
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
                            class="dy-composer-target-filters mt-5"
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



                            {{-- SPECIFIC STUDENTS: paginated, searchable recipient directory --}}
                            <div id="specificStudentsField" class="hidden">
                                <div class="overflow-hidden rounded-2xl border border-[#DFE5F0] bg-white shadow-sm">
                                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E6EAF3] bg-[#FBFCFF] px-5 py-4">
                                        <div>
                                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#B78716]">Recipient directory</p>
                                            <h3 class="mt-1 text-base font-bold text-[#101064]">Select individual students</h3>
                                            <p class="mt-1 text-xs text-slate-500">Search and choose recipients. Selections stay saved when changing pages or filters.</p>
                                        </div>
                                        <span id="studentPickerCount" aria-live="polite" class="rounded-full bg-[#EEF1FF] px-3 py-1.5 text-xs font-bold text-[#101064]">0 selected</span>
                                    </div>
                                    <div class="dy-recipient-grid">
                                        <div class="min-w-0 border-b border-[#E6EAF3] p-4 sm:p-5 lg:border-b-0 lg:border-r">
                                            <div class="dy-picker-filters">
                                                <div class="dy-picker-search">
                                                    <label for="studentPickerSearch" class="dy-control-label">Search by name or ID</label>
                                                    <input id="studentPickerSearch" type="search" autocomplete="off" placeholder="Find a student..." class="dy-control">
                                                </div>
                                                <div>
                                                    <label for="studentPickerProgram" class="dy-control-label">Program</label>
                                                    <select id="studentPickerProgram" class="dy-control">
                                                        <option value="">All programs</option>
                                                        @foreach($students->pluck('program_code')->filter()->unique()->sort()->values() as $program)
                                                            <option value="{{ $program }}">{{ $program }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="studentPickerYear" class="dy-control-label">Year Level</label>
                                                    <select id="studentPickerYear" class="dy-control">
                                                        <option value="">All years</option>
                                                        @foreach($students->pluck('year_level')->filter()->unique()->sort()->values() as $year)
                                                            <option value="{{ $year }}">Year {{ $year }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                                                <div class="flex items-center gap-2">
                                                    <label for="studentPickerPerPage" class="dy-control-label !mb-0">Show</label>
                                                    <select id="studentPickerPerPage" class="dy-control !w-[78px] !min-h-[34px] !py-1.5">
                                                        <option value="10" selected>10</option>
                                                        <option value="20">20</option>
                                                    </select>
                                                    <span class="text-xs text-slate-500">students / page</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button type="button" id="studentPickerResetFilters" class="dy-page-button">Reset filters</button>
                                                    <button type="button" id="studentPickerSelectShown" class="dy-primary-soft">Select page</button>
                                                </div>
                                            </div>
                                            <div id="studentPickerList" role="group" aria-label="Available students" class="dy-list-frame mt-3 divide-y divide-[#EDF0F6] rounded-xl border border-[#E6EAF3] bg-white p-2"></div>
                                            <div class="dy-pager mt-3">
                                                <span id="studentPickerShowing" aria-live="polite" class="text-xs text-slate-500">Loading students...</span>
                                                <div class="dy-pager-controls">
                                                    <button id="studentPickerPrev" type="button" class="dy-page-button">Previous</button>
                                                    <span id="studentPickerPageLabel" class="min-w-[75px] text-center text-xs font-bold text-[#101064]">1 / 1</span>
                                                    <button id="studentPickerNext" type="button" class="dy-page-button">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="min-w-0 bg-[#F7F9FF] p-4 sm:p-5">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#B78716]">Selection summary</p>
                                                    <h4 class="mt-1 text-sm font-bold text-[#101064]">Selected recipients</h4>
                                                    <p id="studentPickerSelectedHint" class="mt-1 text-xs text-slate-500">No students selected</p>
                                                </div>
                                                <button id="studentPickerClear" type="button" class="dy-page-button">Clear all</button>
                                            </div>
                                            <div id="studentPickerSelected" aria-live="polite" class="dy-selected-frame mt-4 space-y-2"></div>
                                            <div class="dy-pager mt-3">
                                                <span id="studentPickerSelectedShowing" class="text-xs text-slate-500">0 selected</span>
                                                <div class="dy-pager-controls">
                                                    <button id="studentPickerSelectedPrev" type="button" class="dy-page-button">Previous</button>
                                                    <span id="studentPickerSelectedPageLabel" class="min-w-[60px] text-center text-xs font-bold text-[#101064]">1 / 1</span>
                                                    <button id="studentPickerSelectedNext" type="button" class="dy-page-button">Next</button>
                                                </div>
                                            </div>
                                            <p class="dy-subtle-note mt-3">Only the selected students will receive this announcement. Other recipients are not affected.</p>
                                        </div>
                                    </div>
                                </div>
                                {{-- Keep original multi-select as form source of truth for API. --}}
                                <select name="specific_student_ids[]" id="specific_student_ids" multiple class="hidden" tabindex="-1" aria-hidden="true">
                                    @foreach($students as $student)
                                        <option
                                            value="{{ $student->student_id }}"
                                            data-student-number="{{ $student->student_number }}"
                                            data-student-name="{{ trim($student->last_name.', '.$student->first_name) }}"
                                            data-program="{{ $student->program_code ?? '' }}"
                                            data-year="{{ $student->year_level ?? '' }}"
                                        >{{ $student->student_number }} — {{ $student->last_name }}, {{ $student->first_name }}</option>
                                    @endforeach
                                </select>
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


                            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-[#E5EAF4] pt-3">
                                <p class="text-xs text-slate-500">In-app announcements remain available even without a valid email.</p>
                                <button type="button" id="recipientPreviewToggle" class="dy-page-button hidden" aria-expanded="false">Show recipients</button>
                            </div>
                            <div id="recipientPreviewDetails" class="hidden">
                                <div id="recipientPreviewList" class="dy-recipient-preview-list"></div>
                                <div class="dy-pager mt-2">
                                    <span id="recipientPreviewShowing" class="text-xs text-slate-500"></span>
                                    <div class="dy-pager-controls">
                                        <button type="button" id="recipientPreviewPrev" class="dy-page-button">Previous</button>
                                        <span id="recipientPreviewPageLabel" class="min-w-[60px] text-center text-xs font-bold text-[#101064]">1 / 1</span>
                                        <button type="button" id="recipientPreviewNext" class="dy-page-button">Next</button>
                                    </div>
                                </div>
                            </div>

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
                            stores the announcement and sends
                            notifications using the configured mail mode.
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
                                        Notify recipients
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            leading-5
                                            text-gray-400
                                        "
                                    >
                                        Delivery uses the configured mail
                                        mode. Students without a real email
                                        still receive in-app announcements.
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
            <div class="dy-history-bar">
                <div class="dy-history-filters">
                    <div>
                        <label for="announcementHistorySearch" class="dy-control-label">Search announcements</label>
                        <input id="announcementHistorySearch" type="search" class="dy-control" placeholder="Event, subject or audience...">
                    </div>
                    <div>
                        <label for="announcementHistoryStatus" class="dy-control-label">Delivery status</label>
                        <select id="announcementHistoryStatus" class="dy-control">
                            <option value="">All statuses</option>
                            <option value="Sent">Sent</option>
                            <option value="Partially Sent">Partially Sent</option>
                            <option value="Processing">Processing</option>
                            <option value="Failed">Failed</option>
                        </select>
                    </div>
                    <div>
                        <label for="announcementHistoryPerPage" class="dy-control-label">Rows per page</label>
                        <select id="announcementHistoryPerPage" class="dy-control">
                            <option value="10" selected>10 announcements</option>
                            <option value="25">25 announcements</option>
                            <option value="50">50 announcements</option>
                        </select>
                    </div>
                    <button type="button" id="announcementHistoryReset" class="dy-page-button !min-h-[42px]">Reset</button>
                </div>
            </div>
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



                    <tbody class="dy-history-body divide-y divide-gray-100">

                        @forelse(
                            $announcementCollection->sortByDesc('created_at')
                            as $announcement
                        )

                            <tr
                                data-announcement-row
                                data-status="{{ $announcement->status ?? 'Processing' }}"
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



            <div class="dy-pager border-t border-[#E8ECF4] bg-[#FAFBFF] px-5 py-4">
                <span id="announcementHistoryShowing" class="text-xs text-slate-500">Announcement history</span>
                <div class="dy-pager-controls">
                    <button id="announcementHistoryPrev" class="dy-page-button" type="button">Previous</button>
                    <span id="announcementHistoryPageLabel" class="min-w-[80px] text-center text-xs font-bold text-[#101064]">1 / 1</span>
                    <button id="announcementHistoryNext" class="dy-page-button" type="button">Next</button>
                </div>
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


        const studentPickerSearch = document.getElementById('studentPickerSearch');
        const studentPickerProgram = document.getElementById('studentPickerProgram');
        const studentPickerYear = document.getElementById('studentPickerYear');
        const studentPickerPerPage = document.getElementById('studentPickerPerPage');
        const studentPickerResetFilters = document.getElementById('studentPickerResetFilters');
        const studentPickerList = document.getElementById('studentPickerList');
        const studentPickerCount = document.getElementById('studentPickerCount');
        const studentPickerShowing = document.getElementById('studentPickerShowing');
        const studentPickerPageLabel = document.getElementById('studentPickerPageLabel');
        const studentPickerPrev = document.getElementById('studentPickerPrev');
        const studentPickerNext = document.getElementById('studentPickerNext');
        const studentPickerSelected = document.getElementById('studentPickerSelected');
        const studentPickerSelectedHint = document.getElementById('studentPickerSelectedHint');
        const studentPickerSelectedShowing = document.getElementById('studentPickerSelectedShowing');
        const studentPickerSelectedPageLabel = document.getElementById('studentPickerSelectedPageLabel');
        const studentPickerSelectedPrev = document.getElementById('studentPickerSelectedPrev');
        const studentPickerSelectedNext = document.getElementById('studentPickerSelectedNext');
        const studentPickerSelectShown = document.getElementById('studentPickerSelectShown');
        const studentPickerClear = document.getElementById('studentPickerClear');

        // Keep the original multi-select as the single source of truth for FormData.
        // This retains selected students across searches and page changes.
        const pickerStudents = Array.from(specificStudents.options).map(option => ({
            option,
            number: option.dataset.studentNumber || '',
            name: option.dataset.studentName || option.textContent.trim(),
            program: option.dataset.program || '',
            year: option.dataset.year || '',
        }));
        let pickerPage = 1;
        let selectedPage = 1;
        const SELECTED_PAGE_SIZE = 5;

        function matchingPickerStudents() {
            const query = studentPickerSearch.value.trim().toLocaleLowerCase();
            const program = studentPickerProgram.value;
            const year = studentPickerYear.value;
            return pickerStudents.filter(student => {
                if (program && student.program !== program) return false;
                if (year && student.year !== year) return false;
                return !query || [student.name, student.number, student.program, student.year]
                    .some(value => value.toLocaleLowerCase().includes(query));
            });
        }

        function pickerIdentity(student) {
            return [student.program, student.year ? `Year ${student.year}` : '']
                .filter(Boolean).join(' · ') || 'Student';
        }

        function pickerCurrentPageItems() {
            const perPage = Number(studentPickerPerPage.value) || 10;
            const matching = matchingPickerStudents();
            return matching.slice((pickerPage - 1) * perPage, pickerPage * perPage);
        }

        function createPagerState(total, perPage, requestedPage, prev, next, label) {
            const pages = Math.max(1, Math.ceil(total / perPage));
            const page = Math.max(1, Math.min(requestedPage, pages));
            prev.disabled = page <= 1;
            next.disabled = page >= pages;
            label.textContent = `Page ${page} / ${pages}`;
            return page;
        }

        function updateStudentPicker() {
            const filtered = matchingPickerStudents();
            const perPage = Number(studentPickerPerPage.value) || 10;
            pickerPage = createPagerState(filtered.length, perPage, pickerPage,
                studentPickerPrev, studentPickerNext, studentPickerPageLabel);
            const shown = pickerCurrentPageItems();
            const selected = pickerStudents.filter(student => student.option.selected);
            selectedPage = createPagerState(selected.length, SELECTED_PAGE_SIZE, selectedPage,
                studentPickerSelectedPrev, studentPickerSelectedNext, studentPickerSelectedPageLabel);
            const selectedShown = selected.slice((selectedPage - 1) * SELECTED_PAGE_SIZE, selectedPage * SELECTED_PAGE_SIZE);

            studentPickerCount.textContent = `${selected.length} selected`;
            const first = shown.length ? (pickerPage - 1) * perPage + 1 : 0;
            const last = first ? first + shown.length - 1 : 0;
            studentPickerShowing.textContent = `Showing ${first}–${last} of ${filtered.length} students`;
            studentPickerSelectedHint.textContent = selected.length
                ? `${selected.length} students selected across all pages` : 'No students selected';
            studentPickerSelectedShowing.textContent = selected.length
                ? `${(selectedPage - 1) * SELECTED_PAGE_SIZE + 1}–${(selectedPage - 1) * SELECTED_PAGE_SIZE + selectedShown.length} of ${selected.length}`
                : '0 selected';
            studentPickerSelectShown.disabled = shown.length === 0 || shown.every(student => student.option.selected);
            studentPickerClear.disabled = selected.length === 0;
            studentPickerList.replaceChildren();
            studentPickerSelected.replaceChildren();

            if (!shown.length) {
                const empty = document.createElement('p');
                empty.className = 'rounded-xl bg-slate-50 px-4 py-12 text-center text-sm text-slate-500';
                empty.textContent = 'No students match your filters.';
                studentPickerList.appendChild(empty);
            }
            for (const student of shown) {
                const row = document.createElement('label');
                row.className = 'flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 transition ' +
                    (student.option.selected ? 'bg-[#F0F2FF]' : 'hover:bg-slate-50');
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.checked = student.option.selected;
                checkbox.className = 'h-4 w-4 shrink-0 accent-[#101064]';
                checkbox.setAttribute('aria-label', `Select ${student.name}`);
                checkbox.addEventListener('change', () => {
                    student.option.selected = checkbox.checked;
                    announcePickerChange();
                });
                const details = document.createElement('div');
                details.className = 'min-w-0 flex-1';
                const name = document.createElement('p');
                name.className = 'truncate text-sm font-semibold text-[#101064]';
                name.textContent = student.name;
                const meta = document.createElement('p');
                meta.className = 'mt-0.5 truncate text-[11px] text-slate-500';
                meta.textContent = `${student.number} · ${pickerIdentity(student)}`;
                details.append(name, meta);
                row.append(checkbox, details);
                studentPickerList.appendChild(row);
            }
            if (!selected.length) {
                const empty = document.createElement('div');
                empty.className = 'rounded-xl border border-dashed border-[#D5DDEF] bg-white px-4 py-10 text-center text-xs text-slate-500';
                empty.textContent = 'Your selected students will appear here.';
                studentPickerSelected.appendChild(empty);
            }
            for (const student of selectedShown) {
                const row = document.createElement('div');
                row.className = 'flex items-center justify-between gap-2 rounded-lg border border-[#E4E9F4] bg-white px-3 py-2.5';
                const details = document.createElement('div');
                details.className = 'min-w-0';
                const name = document.createElement('p');
                name.className = 'truncate text-xs font-bold text-[#101064]';
                name.textContent = student.name;
                const meta = document.createElement('p');
                meta.className = 'truncate text-[11px] text-slate-500';
                meta.textContent = `${student.number} · ${pickerIdentity(student)}`;
                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'shrink-0 rounded-lg px-2 py-1 text-xs font-bold text-[#101064] hover:bg-[#EFF1FB]';
                remove.textContent = 'Remove';
                remove.setAttribute('aria-label', `Remove ${student.name}`);
                remove.addEventListener('click', () => {
                    student.option.selected = false;
                    announcePickerChange();
                });
                details.append(name, meta);
                row.append(details, remove);
                studentPickerSelected.appendChild(row);
            }
        }

        function announcePickerChange() {
            updateStudentPicker();
            specificStudents.dispatchEvent(new Event('change', { bubbles: true }));
        }
        function resetPickerPage() { pickerPage = 1; updateStudentPicker(); }
        studentPickerSearch.addEventListener('input', resetPickerPage);
        studentPickerProgram.addEventListener('change', resetPickerPage);
        studentPickerYear.addEventListener('change', resetPickerPage);
        studentPickerPerPage.addEventListener('change', resetPickerPage);
        studentPickerResetFilters.addEventListener('click', () => {
            studentPickerSearch.value = '';
            studentPickerProgram.value = '';
            studentPickerYear.value = '';
            studentPickerPerPage.value = '10';
            resetPickerPage();
        });
        studentPickerPrev.addEventListener('click', () => { pickerPage--; updateStudentPicker(); });
        studentPickerNext.addEventListener('click', () => { pickerPage++; updateStudentPicker(); });
        studentPickerSelectedPrev.addEventListener('click', () => { selectedPage--; updateStudentPicker(); });
        studentPickerSelectedNext.addEventListener('click', () => { selectedPage++; updateStudentPicker(); });
        studentPickerSelectShown.addEventListener('click', () => {
            for (const student of pickerCurrentPageItems()) student.option.selected = true;
            announcePickerChange();
        });
        studentPickerClear.addEventListener('click', () => {
            for (const student of pickerStudents) student.option.selected = false;
            selectedPage = 1;
            announcePickerChange();
        });

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

        const recipientPreviewToggle = document.getElementById('recipientPreviewToggle');
        const recipientPreviewDetails = document.getElementById('recipientPreviewDetails');
        const recipientPreviewShowing = document.getElementById('recipientPreviewShowing');
        const recipientPreviewPageLabel = document.getElementById('recipientPreviewPageLabel');
        const recipientPreviewPrev = document.getElementById('recipientPreviewPrev');
        const recipientPreviewNext = document.getElementById('recipientPreviewNext');
        let previewRecipients = [];
        let previewPage = 1;
        const PREVIEW_PER_PAGE = 5;
        function renderPreviewPage() {
            previewPage = createPagerState(previewRecipients.length, PREVIEW_PER_PAGE, previewPage,
                recipientPreviewPrev, recipientPreviewNext, recipientPreviewPageLabel);
            const shown = previewRecipients.slice((previewPage - 1) * PREVIEW_PER_PAGE, previewPage * PREVIEW_PER_PAGE);
            recipientPreviewList.replaceChildren();
            for (const recipient of shown) {
                const row = document.createElement('div');
                row.className = 'dy-preview-row';
                const name = document.createElement('span');
                name.className = 'min-w-0 truncate text-xs font-semibold text-[#101064]';
                name.textContent = recipient.name || 'Recipient';
                const address = document.createElement('span');
                address.className = 'max-w-[45%] truncate text-[11px] text-slate-500';
                address.textContent = recipient.email === 'unavailable@invalid' ? 'In-app only' : (recipient.email || 'In-app only');
                row.append(name,address);
                recipientPreviewList.appendChild(row);
            }
            const first = shown.length ? (previewPage - 1) * PREVIEW_PER_PAGE + 1 : 0;
            const last = first ? first + shown.length - 1 : 0;
            recipientPreviewShowing.textContent = `${first}–${last} of ${previewRecipients.length} preview records`;
        }
        recipientPreviewToggle.addEventListener('click', () => {
            const show = recipientPreviewDetails.classList.contains('hidden');
            recipientPreviewDetails.classList.toggle('hidden', !show);
            recipientPreviewToggle.setAttribute('aria-expanded', String(show));
            recipientPreviewToggle.textContent = show ? 'Hide recipients' : 'Show recipients';
        });
        recipientPreviewPrev.addEventListener('click', () => { previewPage--; renderPreviewPage(); });
        recipientPreviewNext.addEventListener('click', () => { previewPage++; renderPreviewPage(); });



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
        let previewRequestSequence = 0;



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

            const requestSequence = ++previewRequestSequence;
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

                if (requestSequence !== previewRequestSequence) return;


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
                        `${data.count} targeted recipient${data.count === 1 ? '' : 's'} (${data.email_ready_count ?? 0} email-ready, ${data.in_app_only_count ?? 0} in-app only).`;


                previewRecipients = Array.isArray(data.recipients) ? data.recipients : [];
                previewPage = 1;
                recipientPreviewToggle.classList.toggle('hidden', previewRecipients.length === 0);
                recipientPreviewDetails.classList.add('hidden');
                recipientPreviewToggle.setAttribute('aria-expanded', 'false');
                recipientPreviewToggle.textContent = 'Show recipients';
                if (previewRecipients.length) renderPreviewPage();

            }
            catch (
                error
            ) {

                if (requestSequence !== previewRequestSequence) return;
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
                    studentPickerSearch.value = '';
                    studentPickerProgram.value = '';
                    studentPickerYear.value = '';
                    studentPickerPerPage.value = '10';
                    pickerPage = 1;
                    selectedPage = 1;
                    updateStudentPicker();


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

        updateStudentPicker();
        updateRecipientFields();

        // Announcement history: client-side filters over the records provided by controller.
        // KPI cards remain totals for all department announcements, independent of filters.
        const historyRows = Array.from(document.querySelectorAll('[data-announcement-row]'));
        const historySearch = document.getElementById('announcementHistorySearch');
        const historyStatus = document.getElementById('announcementHistoryStatus');
        const historyPerPage = document.getElementById('announcementHistoryPerPage');
        const historyReset = document.getElementById('announcementHistoryReset');
        const historyPrev = document.getElementById('announcementHistoryPrev');
        const historyNext = document.getElementById('announcementHistoryNext');
        const historyPageLabel = document.getElementById('announcementHistoryPageLabel');
        const historyShowing = document.getElementById('announcementHistoryShowing');
        let historyPage = 1;
        const historyEmpty = document.createElement('tr');
        historyEmpty.innerHTML = '<td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">No announcements match your filters.</td>';
        if (historyRows.length) historyRows[0].parentNode.appendChild(historyEmpty);
        function renderHistory() {
            const query = historySearch.value.trim().toLocaleLowerCase();
            const status = historyStatus.value;
            const perPage = Number(historyPerPage.value) || 10;
            const matching = historyRows.filter(row =>
                (!status || row.dataset.status === status) &&
                (!query || row.textContent.toLocaleLowerCase().includes(query))
            );
            historyPage = createPagerState(matching.length, perPage, historyPage,
                historyPrev, historyNext, historyPageLabel);
            const start = (historyPage - 1) * perPage;
            const current = matching.slice(start, start + perPage);
            for (const row of historyRows) row.hidden = true;
            for (const row of current) row.hidden = false;
            historyEmpty.hidden = matching.length > 0 || historyRows.length === 0;
            const first = current.length ? start + 1 : 0;
            const last = current.length ? start + current.length : 0;
            historyShowing.textContent = `Showing ${first}–${last} of ${matching.length} announcements`;
        }
        const resetHistoryPage = () => { historyPage = 1; renderHistory(); };
        historySearch.addEventListener('input', resetHistoryPage);
        historyStatus.addEventListener('change', resetHistoryPage);
        historyPerPage.addEventListener('change', resetHistoryPage);
        historyPrev.addEventListener('click', () => { historyPage--; renderHistory(); });
        historyNext.addEventListener('click', () => { historyPage++; renderHistory(); });
        historyReset.addEventListener('click', () => {
            historySearch.value = '';
            historyStatus.value = '';
            historyPerPage.value = '10';
            resetHistoryPage();
        });
        renderHistory();


    }
);

</script>

</x-admin-layout>