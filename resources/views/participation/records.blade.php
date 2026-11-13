<x-admin-layout>

<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .participation-hero {

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

    /* Student-inspired university portal finishes; no custom JS required. */
    .pr-hero-ring { position:absolute; width:290px; height:290px; border:1px solid rgba(255,255,255,.16); border-radius:50%; pointer-events:none; }
    .pr-summary-grid { gap:14px; border:0 !important; background:transparent !important; overflow:visible !important; }
    .pr-summary-grid > div { border:1px solid #e2e8f0 !important; border-radius:18px; background:#fff; box-shadow:0 3px 12px rgba(15,23,42,.035); }
    .pr-registry { border-radius:20px; box-shadow:0 6px 24px rgba(15,23,42,.035); }
    .pr-filter-input { width:100%; min-height:42px; border:1px solid #dbe3f0; border-radius:11px; padding:0 13px; font-size:13px; color:#101064; background:#fff; outline:none; }
    .pr-filter-input:focus { border-color:#D4A017; box-shadow:0 0 0 3px rgba(212,160,23,.13); }
    .pr-filter-label { display:block; margin-bottom:7px; font-size:10px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#74829c; }
</style>



<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            participation-hero
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


        <div aria-hidden="true" class="pr-hero-ring -right-12 -top-44"></div>
        <div aria-hidden="true" class="pr-hero-ring -right-40 -bottom-52"></div>

        <div
            class="
                relative
                z-10
                flex
                flex-col
                gap-7
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
                    Student Participation Records
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
                    Review consolidated student participation information
                    generated from RFID attendance records across school events.
                </p>

            </div>


            <div
                class="
                    hidden
                    shrink-0
                    items-center
                    justify-center
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
                        Record Source
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            font-semibold
                            text-white
                        "
                    >
                        RFID Attendance Database
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
                Participation Overview
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Record Summary
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Consolidated statistics calculated from the current
                DySign student and attendance database.
            </p>

        </div>


        <div
            class="
                pr-summary-grid
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
                            Total Students
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
                            {{ number_format($totalStudents ?? 0) }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Active student records
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
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H2v-2a4 4 0 014-4h1m5-2a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 10-2.83-4"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- PARTICIPATING STUDENTS --}}

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
                            Participating Students
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
                            {{ number_format($participatingStudents ?? 0) }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Students with attendance records
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



            {{-- EVENTS RECORDED --}}

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
                            Events Recorded
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
                            {{ number_format($eventsRecorded ?? 0) }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Events with attendance records
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
                            text-[#B8880A]
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
                                d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- ATTENDANCE ENTRIES --}}

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
                            Attendance Entries
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
                            {{ number_format($attendanceEntries ?? 0) }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Total RFID attendance records
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
                                d="M9 12h6m-6 4h6M9 8h6M5 4h14a2 2 0 012 2v14H3V6a2 2 0 012-2z"
                            />
                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- PARTICIPATION RECORDS --}}
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
                    Student Records
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Participation History
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Review attendance activity calculated from
                    each student's recorded event participation.
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

                Live Database Records

            </div>

        </div>



        {{-- Server-side filters: values persist through pagination links. --}}
        <form action="{{ route('participation.records') }}" method="GET"
            class="mb-4 rounded-[20px] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h3 class="text-sm font-bold text-[#101064]">Find Student Participation</h3>
                    <p class="mt-1 text-xs text-slate-500">Search the complete active student registry, not only the current page.</p>
                </div>
                <p class="text-xs font-semibold text-[#101064]">{{ number_format($records->total()) }} matching {{ $records->total() === 1 ? 'student' : 'students' }}</p>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
                <label class="block xl:col-span-2">
                    <span class="pr-filter-label">Search Student</span>
                    <input class="pr-filter-input" type="search" name="search" value="{{ $search }}"
                        maxlength="120" placeholder="Student number, full name, college or program..." />
                </label>
                <label class="block">
                    <span class="pr-filter-label">College</span>
                    <select class="pr-filter-input" name="college">
                        <option value="">All Colleges</option>
                        @foreach($colleges as $option)
                            <option value="{{ $option }}" @selected($college === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span class="pr-filter-label">Program</span>
                    <select class="pr-filter-input" name="program">
                        <option value="">All Programs</option>
                        @foreach($programs as $option)
                            <option value="{{ $option }}" @selected($program === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span class="pr-filter-label">Year Level</span>
                    <select class="pr-filter-input" name="year">
                        <option value="">All Years</option>
                        @foreach($years as $option)
                            <option value="{{ $option }}" @selected((string) $year === (string) $option)>Year {{ $option }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span class="pr-filter-label">Participation</span>
                    <select class="pr-filter-input" name="participation">
                        <option value="all" @selected($participation === 'all')>All Active Students</option>
                        <option value="with_records" @selected($participation === 'with_records')>With Attendance Records</option>
                        <option value="without_records" @selected($participation === 'without_records')>Without Attendance Records</option>
                    </select>
                </label>
                <label class="block">
                    <span class="pr-filter-label">Sort By</span>
                    <select class="pr-filter-input" name="sort">
                        <option value="most_events" @selected($sort === 'most_events')>Most Events Attended</option>
                        <option value="recent" @selected($sort === 'recent')>Recently Participated</option>
                        <option value="name" @selected($sort === 'name')>Student Name A–Z</option>
                    </select>
                </label>
                <label class="block">
                    <span class="pr-filter-label">Rows Per Page</span>
                    <select class="pr-filter-input" name="per_page">
                        @foreach([10, 25, 50] as $size)
                            <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }} students</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex items-end gap-2 md:col-span-2 xl:col-span-1">
                    <button type="submit" class="min-h-[42px] flex-1 rounded-xl bg-[#101064] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-[#0b0b4d]">Apply Filters</button>
                    <a href="{{ route('participation.records') }}" class="inline-flex min-h-[42px] items-center rounded-xl border border-slate-200 px-4 text-xs font-semibold text-slate-600 hover:bg-slate-50">Clear</a>
                </div>
            </div>
        </form>

        <div
            class="
                pr-registry
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
                                Student ID
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
                                Present
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
                                Late
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
                                Student Status
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

                            @endphp


                            <tr
                                class="
                                    transition
                                    hover:bg-gray-50/70
                                "
                            >

                                {{-- STUDENT NUMBER --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                        text-sm
                                        font-medium
                                        text-gray-500
                                    "
                                >
                                    {{ $record->student_number }}
                                </td>



                                {{-- STUDENT --}}

                                <td class="px-6 py-5">

                                    <div
                                        class="
                                            flex
                                            items-center
                                            gap-3
                                        "
                                    >

                                        {{-- PHOTO --}}

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



                                        {{-- STUDENT INFO --}}

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
                                                {{ $record->college }}
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

                                    {{ ($record->events_attended ?? 0) == 1
                                        ? 'Event'
                                        : 'Events'
                                    }}

                                </td>



                                {{-- PRESENT --}}

                                <td class="px-6 py-5">

                                    <span
                                        class="
                                            inline-flex
                                            min-w-[42px]
                                            items-center
                                            justify-center
                                            rounded-full
                                            bg-green-50
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            text-green-700
                                        "
                                    >
                                        {{ number_format(
                                            $record->present_count ?? 0
                                        ) }}
                                    </span>

                                </td>



                                {{-- LATE --}}

                                <td class="px-6 py-5">

                                    <span
                                        class="
                                            inline-flex
                                            min-w-[42px]
                                            items-center
                                            justify-center
                                            rounded-full
                                            bg-[#FFF8E1]
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            text-[#9A7000]
                                        "
                                    >
                                        {{ number_format(
                                            $record->late_count ?? 0
                                        ) }}
                                    </span>

                                </td>



                                {{-- STATUS --}}

                                <td class="px-6 py-5">

                                    @if($record->status === 'Active')

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

                                            Active

                                        </span>

                                    @else

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-gray-100
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-gray-600
                                            "
                                        >

                                            <span
                                                class="
                                                    h-1.5
                                                    w-1.5
                                                    rounded-full
                                                    bg-gray-400
                                                "
                                            ></span>

                                            {{ $record->status }}

                                        </span>

                                    @endif

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="
                                        px-6
                                        py-16
                                        text-center
                                    "
                                >

                                    <div
                                        class="
                                            mx-auto
                                            flex
                                            h-12
                                            w-12
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-[#F1F2FA]
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
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H2v-2a4 4 0 014-4h1m5-2a4 4 0 100-8 4 4 0 000 8"
                                            />
                                        </svg>

                                    </div>


                                    <p
                                        class="
                                            mt-4
                                            text-sm
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        No Matching Student Records
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-400
                                        "
                                    >
                                        Try adjusting your search or filters. If no filters are selected,
                                        there may be no active student records.
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
                        Showing <strong class="text-[#101064]">{{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }}</strong>
                        of <strong class="text-[#101064]">{{ number_format($records->total()) }}</strong> matching students
                        · Page {{ $records->currentPage() }} of {{ $records->lastPage() }}
                    </p>


                    @if(isset($records) && $records->hasPages())

                        <div class="text-sm">
                            {{ $records->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </section>


</div>

</x-admin-layout>