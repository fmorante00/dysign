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

</style>



<div class="min-w-0 space-y-8">


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
                            {{ $students->count() }} student records
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
                            {{ $students->whereNotNull('rfid_identifier')->count() }}
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
    {{-- STUDENT OVERVIEW --}}
    {{-- ====================================================== --}}

    <section class="min-w-0">


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
                Overview
            </p>


            <div
                class="
                    mt-2
                    flex
                    min-w-0
                    flex-col
                    gap-2
                    sm:flex-row
                    sm:items-end
                    sm:justify-between
                "
            >

                <h2
                    class="
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Student Record Summary
                </h2>


                <p
                    class="
                        text-sm
                        text-gray-400
                    "
                >
                    Current imported student information
                </p>

            </div>

        </div>



        <div
            class="
                grid
                min-w-0
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                md:grid-cols-3
            "
        >


            {{-- TOTAL STUDENTS --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-b-0
                    md:border-r
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
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Total Students
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-[#101064]
                            "
                        >
                            {{ $students->count() }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Imported student records
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
                            rounded-full
                            bg-[#F0F1F8]
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
                                d="M12 14L3 9l9-5 9 5-9 5zm-6-2v5c3 2 9 2 12 0v-5"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- RFID ASSIGNED --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-b-0
                    md:border-r
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
                                tracking-wider
                                text-gray-400
                            "
                        >
                            RFID Assigned
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-green-600
                            "
                        >
                            {{ $students->whereNotNull('rfid_identifier')->count() }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Available for attendance
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
                            rounded-full
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



            {{-- MISSING RFID --}}

            <div
                class="
                    min-w-0
                    px-6
                    py-6
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
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Missing RFID
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-[#D4A017]
                            "
                        >
                            {{ $students->whereNull('rfid_identifier')->count() }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Requires data verification
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
                            rounded-full
                            bg-[#FFF9E7]
                            text-[#B68A0D]
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
                                d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                            />
                        </svg>

                    </div>

                </div>

            </div>


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
                    Search and review imported student records.
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
                        xl:grid-cols-4
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


                            @foreach($students->pluck('college')->unique() as $college)

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


                            @foreach($students->pluck('program_code')->unique() as $program)

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
                            href="{{ route('students.index') }}"
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
                                class="
                                    transition
                                    hover:bg-gray-50
                                "
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
                                        Try changing your search or filter options.
                                    </p>

                                </td>

                            </tr>


                        @endforelse


                    </tbody>

                </table>

            </div>


        </div>

    </section>


</div>

</x-admin-layout>