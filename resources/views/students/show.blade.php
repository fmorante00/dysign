<x-admin-layout>

<style>

    /*
    |--------------------------------------------------------------------------
    | STUDENT PROFILE HERO
    |--------------------------------------------------------------------------
    */

    .student-profile-hero {
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

    .student-profile-section {
        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE PHOTO
    |--------------------------------------------------------------------------
    */

    .student-profile-photo {
        width: 88px !important;
        height: 88px !important;

        min-width: 88px !important;
        min-height: 88px !important;

        max-width: 88px !important;
        max-height: 88px !important;

        object-fit: cover !important;

        border-radius: 9999px;
    }


    .student-profile-initial {
        width: 88px !important;
        height: 88px !important;

        min-width: 88px !important;
        min-height: 88px !important;

        max-width: 88px !important;
        max-height: 88px !important;
    }

</style>



<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            student-profile-hero
            relative
            overflow-hidden
            rounded-[28px]
            px-7
            py-8
            text-white
            sm:px-8
            lg:px-10
            lg:py-8
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
                gap-7
                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >


            {{-- ====================================================== --}}
            {{-- STUDENT IDENTITY --}}
            {{-- ====================================================== --}}

            <div
                class="
                    flex
                    min-w-0
                    flex-col
                    gap-5
                    sm:flex-row
                    sm:items-center
                "
            >


                {{-- PHOTO --}}

                <div class="shrink-0">

                    @if($student->photo_path)

                        <img
                            src="{{ asset($student->photo_path) }}"
                            alt="{{ $student->first_name }} {{ $student->last_name }}"
                            class="
                                student-profile-photo
                                shrink-0
                                border-4
                                border-white/25
                                bg-white
                                shadow-lg
                            "
                            onerror="
                                this.style.display='none';
                                this.nextElementSibling.style.display='flex';
                            "
                        >


                        {{-- FALLBACK INITIAL --}}

                        <div
                            style="display:none;"
                            class="
                                student-profile-initial
                                shrink-0
                                items-center
                                justify-center
                                rounded-full
                                border-4
                                border-white/25
                                bg-white/10
                                text-2xl
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
                                student-profile-initial
                                flex
                                shrink-0
                                items-center
                                justify-center
                                rounded-full
                                border-4
                                border-white/25
                                bg-white/10
                                text-2xl
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

                </div>



                {{-- STUDENT DETAILS --}}

                <div class="min-w-0">


                    <p
                        class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-[0.35em]
                            text-[#E7C75B]
                        "
                    >
                        Student Profile
                    </p>



                    <h1
                        class="
                            mt-2
                            break-words
                            text-2xl
                            font-bold
                            tracking-tight
                            md:text-3xl
                        "
                    >

                        {{ $student->first_name }}

                        @if($student->middle_name)
                            {{ $student->middle_name }}
                        @endif

                        {{ $student->last_name }}

                    </h1>



                    {{-- META DETAILS --}}

                    <div
                        class="
                            mt-3
                            flex
                            flex-wrap
                            items-center
                            gap-2
                            text-sm
                            text-white/75
                        "
                    >


                        <span>
                            {{ $student->student_number }}
                        </span>


                        <span class="text-white/30">
                            •
                        </span>


                        <span>
                            {{ $student->program_code }}
                        </span>


                        <span class="text-white/30">
                            •
                        </span>


                        <span>
                            Year {{ $student->year_level }}
                        </span>


                        <span class="text-white/30">
                            •
                        </span>


                        <span
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

                                    {{ $student->status === 'Active'
                                        ? 'bg-green-400'
                                        : 'bg-gray-400'
                                    }}
                                "
                            ></span>


                            {{ $student->status }}

                        </span>


                    </div>

                </div>

            </div>



            {{-- ====================================================== --}}
            {{-- BACK BUTTON --}}
            {{-- ====================================================== --}}

            <div class="shrink-0">

                <a
                    href="{{ route('students.index') }}"
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
                            d="M15 19l-7-7 7-7"
                        />

                    </svg>


                    Back to Records

                </a>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- STUDENT INFORMATION --}}
    {{-- ====================================================== --}}

    <section class="min-w-0">


        {{-- SECTION HEADER --}}

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
                Identity Record
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Student Information
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Official identifying information imported into DySign.
            </p>

        </div>



        {{-- INFORMATION GRID --}}

        <div
            class="
                student-profile-section
                grid
                min-w-0
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                sm:grid-cols-2
                xl:grid-cols-4
            "
        >


            {{-- STUDENT NUMBER --}}

            <div
                class="
                    min-w-0
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
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Student Number
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ $student->student_number }}
                </p>

            </div>



            {{-- EMAIL --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    xl:border-b-0
                    xl:border-r
                "
            >

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Email Address
                </p>


                <p
                    class="
                        mt-2
                        break-all
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $student->email ?? 'Not provided' }}
                </p>

            </div>



            {{-- STUDENT CLASSIFICATION --}}

            <div
                class="
                    min-w-0
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
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Student Classification
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $student->student_status }}
                </p>

            </div>



            {{-- RECORD STATUS --}}

            <div
                class="
                    min-w-0
                    px-6
                    py-6
                "
            >

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Record Status
                </p>



                <div class="mt-2">


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
                                    h-2
                                    w-2
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
                                    h-2
                                    w-2
                                    rounded-full
                                    bg-gray-400
                                "
                            ></span>

                            Inactive

                        </span>


                    @endif


                </div>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ACADEMIC INFORMATION --}}
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
                Academic Record
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Academic Information
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Current college, program, and year-level information.
            </p>

        </div>



        <div
            class="
                student-profile-section
                grid
                min-w-0
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                md:grid-cols-2
            "
        >


            {{-- COLLEGE --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-r
                "
            >

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    College
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $student->college }}
                </p>

            </div>



            {{-- PROGRAM --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                "
            >

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Program
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $student->program_name }}
                </p>

            </div>



            {{-- PROGRAM CODE --}}

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

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Program Code
                </p>


                <p
                    class="
                        mt-2
                        text-lg
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ $student->program_code }}
                </p>

            </div>



            {{-- YEAR LEVEL --}}

            <div
                class="
                    min-w-0
                    px-6
                    py-6
                "
            >

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Year Level
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    Year {{ $student->year_level }}
                </p>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- RFID INFORMATION --}}
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
                Attendance Credential
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                RFID Identifier
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                RFID UID associated with this student for attendance identification.
            </p>

        </div>



        <div
            class="
                student-profile-section
                min-w-0
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >


            @if($student->rfid_identifier)


                <div
                    class="
                        flex
                        min-w-0
                        flex-col
                        gap-6
                        px-6
                        py-6
                        md:flex-row
                        md:items-center
                        md:justify-between
                    "
                >


                    {{-- RFID VALUE --}}

                    <div class="min-w-0">

                        <p
                            class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            RFID UID
                        </p>


                        <p
                            class="
                                mt-2
                                break-all
                                text-2xl
                                font-bold
                                tracking-wider
                                text-[#101064]
                            "
                        >
                            {{ $student->rfid_identifier }}
                        </p>

                    </div>



                    {{-- RFID STATUS --}}

                    <div
                        class="
                            flex
                            shrink-0
                            items-center
                            gap-3
                            border-l-4
                            border-green-500
                            bg-green-50
                            px-5
                            py-4
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
                                rounded-full
                                bg-green-100
                                font-bold
                                text-green-700
                            "
                        >
                            ✓
                        </div>



                        <div>

                            <p
                                class="
                                    text-sm
                                    font-semibold
                                    text-green-700
                                "
                            >
                                RFID Assigned
                            </p>


                            <p
                                class="
                                    mt-1
                                    text-xs
                                    text-green-600
                                "
                            >
                                Available for attendance identification
                            </p>

                        </div>

                    </div>


                </div>


            @else


                <div
                    class="
                        px-6
                        py-12
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
                            font-bold
                            text-[#101064]
                        "
                    >
                        No RFID identifier available
                    </h3>


                    <p
                        class="
                            mt-2
                            text-sm
                            text-gray-400
                        "
                    >
                        This student requires RFID credential verification.
                    </p>

                </div>


            @endif


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SYSTEM RECORD INFORMATION --}}
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
                System Record
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Record Information
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Internal DySign record and import information.
            </p>

        </div>



        <div
            class="
                student-profile-section
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


            {{-- RECORD ID --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-5
                    md:border-b-0
                    md:border-r
                "
            >

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Record ID
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $student->student_id }}
                </p>

            </div>



            {{-- CREATED AT --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-5
                    md:border-b-0
                    md:border-r
                "
            >

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Record Added
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >

                    {{ $student->created_at
                        ? $student->created_at->format('M d, Y • g:i A')
                        : '—'
                    }}

                </p>

            </div>



            {{-- UPDATED AT --}}

            <div
                class="
                    min-w-0
                    px-6
                    py-5
                "
            >

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Last Updated
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >

                    {{ $student->updated_at
                        ? $student->updated_at->format('M d, Y • g:i A')
                        : '—'
                    }}

                </p>

            </div>


        </div>

    </section>


</div>

</x-admin-layout>