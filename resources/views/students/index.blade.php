<x-admin-layout>

<style>

    /*
    |--------------------------------------------------------------------------
    | STUDENT RECORDS HERO
    |--------------------------------------------------------------------------
    */

    .student-records-hero {
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


    /*
    |--------------------------------------------------------------------------
    | SECTIONS
    |--------------------------------------------------------------------------
    */

    .student-records-section {
        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT PHOTO
    |--------------------------------------------------------------------------
    */

    .student-directory-photo {
        width: 44px !important;
        height: 44px !important;

        min-width: 44px !important;
        min-height: 44px !important;

        max-width: 44px !important;
        max-height: 44px !important;

        object-fit: cover !important;
        border-radius: 9999px;
    }


    /*
    |--------------------------------------------------------------------------
    | SCROLLBAR
    |--------------------------------------------------------------------------
    */

    .student-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .student-scrollbar::-webkit-scrollbar-track {
        background: #f3f4f6;
    }

    .student-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 9999px;
    }

    .student-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }


    /* University-style design aligned with the updated DySign admin modules. */
    .student-data-page {
        --dy-navy: #101064;
        --dy-gold: #D4A017;
        --dy-stroke: #e3e8f1;
    }
    .student-data-page .student-records-hero {
        box-shadow: 0 18px 34px -27px rgba(16,16,100,.65);
    }
    .student-data-page .student-records-section {
        border: 1px solid var(--dy-stroke);
        border-radius: 20px;
        box-shadow: 0 7px 22px -20px rgba(17,24,39,.25);
    }
    .student-data-page .dy-student-stat {
        min-width: 0;
        padding: 22px;
        border: 1px solid var(--dy-stroke);
        border-radius: 19px;
        background: white;
        box-shadow: 0 5px 18px -16px rgba(17,24,39,.3);
    }
    .student-data-page .dy-student-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 14px;
    }
    .student-data-page .dy-student-filter {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .student-data-page .dy-student-filter:focus-within {
        border-color: #a5a9d3;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(16,16,100,.05);
    }
    .student-data-page .dy-student-filter :is(input,select) {
        background: transparent;
    }
    .student-data-page .dy-student-row {transition: background-color .15s ease;}
    .student-data-page .dy-student-row:hover {background: #f8faff;}
    .student-data-page .dy-student-pager {
        background: #fafbff;
        border-top: 1px solid #e8ecf4;
    }
    .student-data-page .dy-student-pager nav {max-width: 100%;}
    @media (prefers-reduced-motion: reduce) {
        .student-data-page .dy-student-row {transition:none;}
    }

</style>

@include('partials.student-university-styles')



<div class="dy-student-university student-data-page min-w-0 space-y-7 pb-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            student-records-hero
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

                {{-- Matching the translucent circular motifs used in the Student Dashboard. --}}
        <div class="dy-hero-circle dy-hero-circle--outer" aria-hidden="true"></div>
        <div class="dy-hero-circle dy-hero-circle--inner" aria-hidden="true"></div>
        <div class="dy-hero-glow" aria-hidden="true"></div>

        {{-- GOLD ACCENT --}}

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
                min-w-0
                flex-col
                gap-8
                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >


            {{-- HERO CONTENT --}}

            <div class="min-w-0 max-w-3xl">

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.35em]
                        text-[#E7C75B]
                    "
                >
                    Student Registry
                </p>


                <h1
                    class="
                        mt-3
                        break-words
                        text-3xl
                        font-bold
                        tracking-tight
                        md:text-4xl
                    "
                >
                    Student Records
                </h1>


                <p
                    class="
                        mt-3
                        max-w-2xl
                        text-sm
                        leading-6
                        text-white/75
                    "
                >
                    Manage imported student identity records,
                    academic information, RFID credentials,
                    and attendance identification data.
                </p>



                <div
                    class="
                        mt-6
                        flex
                        flex-wrap
                        gap-x-8
                        gap-y-3
                        text-sm
                        text-white/70
                    "
                >


                    {{-- TOTAL STUDENTS --}}

                    <div
                        class="
                            flex
                            items-center
                            gap-2
                        "
                    >

                        <svg
                            class="
                                h-4
                                w-4
                                shrink-0
                                text-[#E7C75B]
                            "
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 14L3 9l9-5 9 5-9 5zm-6-2v5c3 2 9 2 12 0v-5"
                            />
                        </svg>

                        <span>
                            {{ number_format($totalStudents) }} student records
                        </span>

                    </div>



                    {{-- RFID READY --}}

                    <div
                        class="
                            flex
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
                            "
                        ></span>

                        <span>
                            {{ number_format($rfidAssignedCount) }}
                            RFID ready
                        </span>

                    </div>


                </div>

            </div>



            {{-- IMPORT BUTTON --}}

            <div class="shrink-0">

                <a
                    href="{{ route('students.import') }}"
                    class="
                        inline-flex
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
                            d="M12 16V4m0 0L7 9m5-5l5 5M5 20h14"
                        />
                    </svg>

                    Import Students

                </a>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- IMPORT RESULT --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <section
            class="
                student-records-section
                overflow-hidden
                border
                border-green-200
                bg-white
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-4
                    px-6
                    py-5
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                "
            >

                <div
                    class="
                        flex
                        items-center
                        gap-4
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
                            rounded-full
                            bg-green-50
                            font-bold
                            text-green-600
                        "
                    >
                        ✓
                    </div>


                    <div>

                        <p
                            class="
                                font-bold
                                text-[#101064]
                            "
                        >
                            Student Import Completed
                        </p>

                        <p
                            class="
                                mt-1
                                text-sm
                                text-gray-500
                            "
                        >
                            The uploaded student records were processed successfully.
                        </p>

                    </div>

                </div>



                <div
                    class="
                        flex
                        flex-wrap
                        gap-x-6
                        gap-y-2
                        text-sm
                    "
                >

                    <span class="text-gray-500">
                        Added
                        <strong class="ml-1 text-green-600">
                            {{ session('success')['created'] }}
                        </strong>
                    </span>


                    <span class="text-gray-500">
                        Updated
                        <strong class="ml-1 text-[#101064]">
                            {{ session('success')['updated'] }}
                        </strong>
                    </span>


                    <span class="text-gray-500">
                        Failed
                        <strong class="ml-1 text-red-500">
                            {{ session('success')['failed'] }}
                        </strong>
                    </span>

                </div>

            </div>

        </section>

    @endif



    {{-- ====================================================== --}}
    {{-- REGISTRAR OVERVIEW · COUNTS FROM ALL STUDENT RECORDS --}}
    {{-- ====================================================== --}}
    <section class="min-w-0" aria-labelledby="dy-student-overview">
        <div class="mb-4 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-end">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[.25em] text-[#B58C11]">Registrar Overview</p>
                <h2 id="dy-student-overview" class="mt-2 text-xl font-bold tracking-tight text-[#101064]">Institutional Student Snapshot</h2>
                <p class="mt-1 text-sm text-slate-500">Campus-wide enrollment records and RFID credential coverage.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600">
                {{ number_format($colleges->count()) }} {{ $colleges->count() === 1 ? 'college' : 'colleges' }} represented
            </span>
        </div>
        <div class="grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="dy-student-stat">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Students</p>
                        <p class="mt-2 text-3xl font-extrabold tracking-tight text-[#101064]">{{ number_format($totalStudents) }}</p>
                    </div>
                    <div class="dy-student-stat-icon bg-[#F0F1FF] text-[#101064]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14 3 9l9-5 9 5-9 5ZM6 12v5c4 3 8 3 12 0v-5"/></svg>
                    </div>
                </div>
                <p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">All registered student records</p>
            </article>
            <article class="dy-student-stat">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">RFID Assigned</p>
                        <p class="mt-2 text-3xl font-extrabold tracking-tight text-emerald-700">{{ number_format($rfidAssignedCount) }}</p>
                    </div>
                    <div class="dy-student-stat-icon bg-emerald-50 text-emerald-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3"/><path stroke-linecap="round" d="M7 9h4M7 13h7M17 10h.01"/></svg>
                    </div>
                </div>
                <p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Student records with RFID identifiers</p>
            </article>
            <article class="dy-student-stat">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">RFID Not Assigned</p>
                        <p class="mt-2 text-3xl font-extrabold tracking-tight text-[#B5890F]">{{ number_format($missingRfidCount) }}</p>
                    </div>
                    <div class="dy-student-stat-icon bg-amber-50 text-amber-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v6m0 4h.01"/></svg>
                    </div>
                </div>
                <p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Records requiring RFID assignment</p>
            </article>
            <article class="dy-student-stat">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Active Records</p>
                        <p class="mt-2 text-3xl font-extrabold tracking-tight text-[#101064]">{{ number_format($activeStudentsCount) }}</p>
                    </div>
                    <div class="dy-student-stat-icon bg-slate-100 text-[#101064]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg>
                    </div>
                </div>
                <p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Records marked with Active status</p>
            </article>
        </div>
    </section>


    {{-- ====================================================== --}}
    {{-- STUDENT DIRECTORY --}}
    {{-- ====================================================== --}}

    <section class="min-w-0">


        {{-- SECTION HEADER --}}

        <div
            class="
                mb-4
                flex
                min-w-0
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
                    Registrar Records
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Student Directory
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Search, filter, and review imported student records by page.
                </p>

            </div>


            <a
                href="{{ route('students.import') }}"
                class="
                    inline-flex
                    shrink-0
                    items-center
                    justify-center
                    gap-2
                    rounded-lg
                    border
                    border-[#D4A017]
                    bg-white
                    px-5
                    py-2.5
                    text-xs
                    font-semibold
                    text-[#8A6D00]
                    transition
                    hover:bg-[#FFF9E7]
                "
            >

                Import Students

                <span>→</span>

            </a>

        </div>



        <div
            class="
                student-records-section
                min-w-0
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >


            {{-- ====================================================== --}}
            {{-- FILTERS --}}
            {{-- ====================================================== --}}

            <form
                method="GET"
                action="{{ route('students.index') }}"
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-5
                "
            >


                <div
                    class="
                        grid
                        min-w-0
                        grid-cols-1
                        gap-3
                        md:grid-cols-2
                        xl:grid-cols-5
                    "
                >


                    {{-- SEARCH --}}

                    <div class="min-w-0">

                        <label
                            class="
                                mb-2
                                block
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Search
                        </label>


                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Student number or name..."
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-2.5
                                text-sm
                                text-gray-700
                                placeholder:text-gray-400
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >

                    </div>



                    {{-- COLLEGE --}}

                    <div class="min-w-0">

                        <label
                            class="
                                mb-2
                                block
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            College
                        </label>


                        <select
                            name="college"
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-2.5
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >

                            <option value="">
                                All Colleges
                            </option>


                            @foreach($colleges as $college)

                                <option
                                    value="{{ $college }}"
                                    {{ request('college') == $college ? 'selected' : '' }}
                                >
                                    {{ $college }}
                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- PROGRAM --}}

                    <div class="min-w-0">

                        <label
                            class="
                                mb-2
                                block
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Program
                        </label>


                        <select
                            name="program_code"
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-2.5
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >

                            <option value="">
                                All Programs
                            </option>


                            @foreach($programs as $program)

                                <option
                                    value="{{ $program }}"
                                    {{ request('program_code') == $program ? 'selected' : '' }}
                                >
                                    {{ $program }}
                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- YEAR LEVEL --}}

                    <div class="min-w-0">

                        <label
                            class="
                                mb-2
                                block
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Year Level
                        </label>


                        <select
                            name="year_level"
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-2.5
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >

                            <option value="">
                                All Year Levels
                            </option>


                            @for($year = 1; $year <= 5; $year++)

                                <option
                                    value="{{ $year }}"
                                    {{ request('year_level') == $year ? 'selected' : '' }}
                                >
                                    Year {{ $year }}
                                </option>

                            @endfor

                        </select>

                    </div>

                    {{-- ROWS PER PAGE --}}
                    <div class="min-w-0">
                        <label for="student-page-size" class="mb-2 block text-[10px] font-semibold uppercase tracking-wider text-gray-400">Rows per page</label>
                        <select id="student-page-size" name="per_page" class="w-full rounded-lg border-gray-200 px-4 py-2.5 text-sm text-gray-700 focus:border-[#101064] focus:ring-[#101064]">
                            @foreach([10, 25, 50] as $pageOption)
                                <option value="{{ $pageOption }}" {{ $perPage === $pageOption ? 'selected' : '' }}>{{ $pageOption }} students</option>
                            @endforeach
                        </select>
                    </div>

                </div>



                {{-- FILTER ACTIONS --}}

                <div
                    class="
                        mt-4
                        flex
                        flex-wrap
                        justify-end
                        gap-2
                    "
                >


                    @if(
                        request('search')
                        || request('college')
                        || request('program_code')
                        || request('year_level')
                    )

                        <a
                            href="{{ route('students.index', ['per_page' => $perPage]) }}"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                rounded-lg
                                border
                                border-gray-200
                                bg-white
                                px-5
                                py-2.5
                                text-xs
                                font-semibold
                                text-gray-500
                                transition
                                hover:bg-gray-50
                            "
                        >
                            Clear Filters
                        </a>

                    @endif


                    <button
                        type="submit"
                        class="
                            inline-flex
                            items-center
                            justify-center
                            gap-2
                            rounded-lg
                            bg-[#101064]
                            px-6
                            py-2.5
                            text-xs
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
                                d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                            />
                        </svg>

                        Search Records

                    </button>

                </div>

            </form>



            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-[#FBFCFF] px-6 py-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full bg-[#F0F1FF] px-3 py-1 text-xs font-bold text-[#101064]">
                        {{ number_format($students->total()) }} matching {{ $students->total() === 1 ? 'student' : 'students' }}
                    </span>
                    <span class="text-xs text-slate-500">Page {{ number_format($students->currentPage()) }} of {{ number_format($students->lastPage()) }}</span>
                </div>
                <span class="text-xs text-slate-500">Showing {{ number_format($students->firstItem() ?? 0) }}–{{ number_format($students->lastItem() ?? 0) }} of {{ number_format($students->total()) }}</span>
            </div>

            {{-- ====================================================== --}}
            {{-- TABLE --}}
            {{-- ====================================================== --}}

            <div
                class="
                    student-scrollbar
                    w-full
                    max-w-full
                    overflow-x-auto
                "
            >

                <table
                    class="
                        w-full
                        min-w-[1050px]
                        table-auto
                    "
                >


                    {{-- TABLE HEAD --}}

                    <thead
                        class="
                            border-b
                            border-gray-200
                            bg-gray-50
                        "
                    >

                        <tr>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Student
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Student Number
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Program
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Year Level
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                RFID
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Status
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-right
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Action
                            </th>


                        </tr>

                    </thead>



                    {{-- TABLE BODY --}}

                    <tbody
                        class="
                            divide-y
                            divide-gray-100
                            bg-white
                        "
                    >


                        @forelse($students as $student)


                            <tr
                                class="dy-student-row"
                            >


                                {{-- STUDENT IDENTITY --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                    "
                                >

                                    <div
                                        class="
                                            flex
                                            min-w-0
                                            items-center
                                            gap-4
                                        "
                                    >


                                        {{-- PHOTO --}}

                                        @if($student->photo_path)


                                            <img
                                                src="{{ asset($student->photo_path) }}"
                                                alt="{{ $student->first_name }} {{ $student->last_name }}"
                                                class="
                                                    student-directory-photo
                                                    shrink-0
                                                    border
                                                    border-gray-200
                                                    bg-white
                                                "
                                                onerror="
                                                    this.style.display='none';
                                                    this.nextElementSibling.style.display='flex';
                                                "
                                            >


                                            <div
                                                style="display:none;"
                                                class="
                                                    h-11
                                                    w-11
                                                    shrink-0
                                                    items-center
                                                    justify-center
                                                    rounded-full
                                                    bg-[#101064]
                                                    text-sm
                                                    font-bold
                                                    text-white
                                                "
                                            >

                                                {{ strtoupper(
                                                    substr(
                                                        $student->first_name,
                                                        0,
                                                        1
                                                    )
                                                ) }}

                                            </div>


                                        @else


                                            <div
                                                class="
                                                    flex
                                                    h-11
                                                    w-11
                                                    shrink-0
                                                    items-center
                                                    justify-center
                                                    rounded-full
                                                    bg-[#101064]
                                                    text-sm
                                                    font-bold
                                                    text-white
                                                "
                                            >

                                                {{ strtoupper(
                                                    substr(
                                                        $student->first_name,
                                                        0,
                                                        1
                                                    )
                                                ) }}

                                            </div>


                                        @endif



                                        {{-- NAME + EMAIL --}}

                                        <div class="min-w-0">

                                            <p
                                                class="
                                                    max-w-[260px]
                                                    truncate
                                                    font-semibold
                                                    text-[#101064]
                                                "
                                            >

                                                {{ $student->first_name }}

                                                @if($student->middle_name)
                                                    {{ $student->middle_name }}
                                                @endif

                                                {{ $student->last_name }}

                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    max-w-[260px]
                                                    truncate
                                                    text-xs
                                                    text-gray-400
                                                "
                                            >
                                                {{ $student->email ?? 'No email provided' }}
                                            </p>

                                        </div>

                                    </div>

                                </td>



                                {{-- STUDENT NUMBER --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                    "
                                >
                                    {{ $student->student_number }}
                                </td>



                                {{-- PROGRAM --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-sm
                                        font-semibold
                                        text-[#101064]
                                    "
                                >
                                    {{ $student->program_code }}
                                </td>



                                {{-- YEAR --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                        text-sm
                                        text-gray-600
                                    "
                                >
                                    Year {{ $student->year_level }}
                                </td>



                                {{-- RFID STATUS --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                    "
                                >


                                    @if($student->rfid_identifier)


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

                                            Assigned

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
                                                text-gray-500
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

                                            Missing

                                        </span>


                                    @endif


                                </td>



                                {{-- RECORD STATUS --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                    "
                                >


                                    @if($student->status === 'Active')


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
                                                text-gray-500
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

                                            Inactive

                                        </span>


                                    @endif


                                </td>



                                {{-- ACTION --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                        text-right
                                    "
                                >

                                    <a
                                        href="{{ route('students.show', $student->student_id) }}"
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            text-xs
                                            font-semibold
                                            text-[#101064]
                                            transition
                                            hover:text-[#D4A017]
                                        "
                                    >

                                        View Profile

                                        <span>→</span>

                                    </a>

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
                                            h-1
                                            w-12
                                            bg-[#D4A017]
                                        "
                                    ></div>


                                    <h3
                                        class="
                                            mt-5
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        No student records found
                                    </h3>


                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            text-gray-400
                                        "
                                    >
                                        Try changing your search or filter options. The registry totals above reflect all student records.
                                    </p>

                                </td>

                            </tr>


                        @endforelse


                    </tbody>

                </table>

            </div>

            <div class="dy-student-pager flex flex-col gap-4 px-6 py-5 lg:flex-row lg:items-center lg:justify-between" aria-label="Student directory pagination">
                <div>
                    <p class="text-sm font-bold text-[#101064]">Student Directory</p>
                    <p class="mt-1 text-xs text-slate-500">
                        Showing {{ number_format($students->firstItem() ?? 0) }}–{{ number_format($students->lastItem() ?? 0) }}
                        of {{ number_format($students->total()) }} matching records · {{ $perPage }} per page
                    </p>
                </div>
                <div class="min-w-0 text-sm">
                    {{ $students->onEachSide(1)->links() }}
                </div>
            </div>

        </div>

    </section>


</div>

</x-admin-layout>