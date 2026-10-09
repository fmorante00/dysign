<x-admin-layout>

<style>

    /*
    |--------------------------------------------------------------------------
    | IMPORT HERO
    |--------------------------------------------------------------------------
    */

    .student-import-hero {

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
    | SECTION
    |--------------------------------------------------------------------------
    */

    .student-import-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }

</style>



<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            student-import-hero
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
                    Registrar Data Import
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
                    Import Student Data
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
                    Upload official student information from the registrar
                    into DySign, including academic records, RFID identifiers,
                    email addresses, and student profile photos.
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
                                d="M12 16V4m0 0L7 9m5-5l5 5M5 20h14"
                            />
                        </svg>

                        <span>
                            Excel, CSV, and ZIP supported
                        </span>

                    </div>



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

                        Student photo import supported

                    </div>


                </div>

            </div>



            {{-- BACK BUTTON --}}

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
    {{-- ERROR MESSAGE --}}
    {{-- ====================================================== --}}

    @if($errors->any())

        <div
            class="
                student-import-section
                overflow-hidden
                border
                border-red-200
                bg-white
            "
        >

            <div
                class="
                    flex
                    items-start
                    gap-4
                    px-6
                    py-5
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
                        bg-red-50
                        font-bold
                        text-red-600
                    "
                >
                    !
                </div>


                <div class="min-w-0">

                    <p
                        class="
                            font-bold
                            text-red-700
                        "
                    >
                        Import could not be completed
                    </p>


                    <p
                        class="
                            mt-1
                            break-words
                            text-sm
                            text-red-600
                        "
                    >
                        {{ $errors->first() }}
                    </p>

                </div>

            </div>

        </div>

    @endif



    {{-- ====================================================== --}}
    {{-- IMPORT GUIDELINES --}}
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
                Import Guidelines
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
                    Student Import Requirements
                </h2>


                <p
                    class="
                        text-sm
                        text-gray-400
                    "
                >
                    Prepare your file before uploading
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


            {{-- STEP 1 --}}

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
                        gap-4
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
                            bg-[#F0F1F8]
                            text-sm
                            font-bold
                            text-[#101064]
                        "
                    >
                        1
                    </div>


                    <div class="min-w-0">

                        <p
                            class="
                                font-bold
                                text-[#101064]
                            "
                        >
                            Prepare Student Data
                        </p>


                        <p
                            class="
                                mt-2
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            Use an Excel or CSV file containing the required
                            student information and RFID identifiers.
                        </p>

                    </div>

                </div>

            </div>



            {{-- STEP 2 --}}

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
                        gap-4
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
                            bg-[#FFF9E7]
                            text-sm
                            font-bold
                            text-[#B68A0D]
                        "
                    >
                        2
                    </div>


                    <div class="min-w-0">

                        <p
                            class="
                                font-bold
                                text-[#101064]
                            "
                        >
                            Include Student Photos
                        </p>


                        <p
                            class="
                                mt-2
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            For profile photos, upload a ZIP containing the
                            spreadsheet together with a
                            <strong>photos</strong> folder.
                        </p>

                    </div>

                </div>

            </div>



            {{-- STEP 3 --}}

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
                        gap-4
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
                            bg-green-50
                            text-sm
                            font-bold
                            text-green-600
                        "
                    >
                        3
                    </div>


                    <div class="min-w-0">

                        <p
                            class="
                                font-bold
                                text-[#101064]
                            "
                        >
                            Import Records
                        </p>


                        <p
                            class="
                                mt-2
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            Existing student numbers will be updated while
                            new student records will be added automatically.
                        </p>

                    </div>

                </div>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- UPLOAD STUDENT FILE --}}
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
                File Upload
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Upload Student File
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Select the registrar file that will be processed by DySign.
            </p>

        </div>



        <form
            action="{{ route('students.process-import') }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{
                fileName: '',
                fileSize: '',
                hasFile: false,

                selectFile(event) {

                    const file = event.target.files[0];

                    if (!file) {

                        this.fileName = '';
                        this.fileSize = '';
                        this.hasFile = false;

                        return;
                    }

                    this.fileName = file.name;

                    const size =
                        file.size / 1024 / 1024;

                    this.fileSize =
                        size >= 1
                            ? size.toFixed(2) + ' MB'
                            : (file.size / 1024).toFixed(2) + ' KB';

                    this.hasFile = true;
                }
            }"
        >

            @csrf



            <div
                class="
                    student-import-section
                    min-w-0
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                "
            >


                {{-- ====================================================== --}}
                {{-- UPLOAD AREA --}}
                {{-- ====================================================== --}}

                <div class="px-6 py-6 sm:px-8 sm:py-8">


                    <div
                        class="
                            relative
                            overflow-hidden
                            border-2
                            border-dashed
                            border-gray-200
                            bg-gray-50/40
                            px-6
                            py-12
                            text-center
                            transition
                            hover:border-[#D4A017]
                            hover:bg-[#FFFDF7]
                        "
                    >


                        {{-- ICON --}}

                        <div
                            class="
                                mx-auto
                                flex
                                h-14
                                w-14
                                items-center
                                justify-center
                                rounded-full
                                bg-[#FFF9E7]
                                text-[#B68A0D]
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
                                    d="M12 16V4m0 0L7 9m5-5l5 5M5 20h14"
                                />
                            </svg>

                        </div>



                        {{-- UPLOAD TEXT --}}

                        <h3
                            class="
                                mt-5
                                font-bold
                                text-[#101064]
                            "
                        >
                            Select Student Import File
                        </h3>


                        <p
                            class="
                                mx-auto
                                mt-2
                                max-w-xl
                                text-sm
                                leading-6
                                text-gray-400
                            "
                        >
                            Upload an Excel or CSV file for student records.
                            Use a ZIP file when importing student photos together
                            with the spreadsheet.
                        </p>



                        {{-- CHOOSE FILE BUTTON --}}

                        <div class="mt-6">

                            <label
                                class="
                                    inline-flex
                                    cursor-pointer
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-lg
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
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V3m0 0L8 7m4-4l4 4"
                                    />
                                </svg>

                                Choose File


                                <input
                                    type="file"
                                    name="file"
                                    accept=".xlsx,.xls,.csv,.zip"
                                    required
                                    class="hidden"
                                    @change="selectFile($event)"
                                >

                            </label>

                        </div>



                        {{-- ACCEPTED FORMATS --}}

                        <div
                            class="
                                mt-6
                                flex
                                flex-wrap
                                items-center
                                justify-center
                                gap-2
                                text-xs
                                text-gray-400
                            "
                        >

                            <span>
                                Accepted formats:
                            </span>


                            <span
                                class="
                                    border
                                    border-gray-200
                                    bg-white
                                    px-2.5
                                    py-1
                                    font-semibold
                                    text-gray-500
                                "
                            >
                                XLSX
                            </span>


                            <span
                                class="
                                    border
                                    border-gray-200
                                    bg-white
                                    px-2.5
                                    py-1
                                    font-semibold
                                    text-gray-500
                                "
                            >
                                XLS
                            </span>


                            <span
                                class="
                                    border
                                    border-gray-200
                                    bg-white
                                    px-2.5
                                    py-1
                                    font-semibold
                                    text-gray-500
                                "
                            >
                                CSV
                            </span>


                            <span
                                class="
                                    border
                                    border-[#E7C75B]
                                    bg-[#FFF9E7]
                                    px-2.5
                                    py-1
                                    font-semibold
                                    text-[#8A6D00]
                                "
                            >
                                ZIP + Photos
                            </span>

                        </div>


                    </div>



                    {{-- ====================================================== --}}
                    {{-- SELECTED FILE --}}
                    {{-- ====================================================== --}}

                    <div
                        x-show="hasFile"
                        x-transition
                        style="display:none;"
                        class="
                            mt-5
                            border
                            border-green-200
                            bg-green-50
                        "
                    >

                        <div
                            class="
                                flex
                                min-w-0
                                flex-col
                                gap-4
                                px-5
                                py-4
                                sm:flex-row
                                sm:items-center
                                sm:justify-between
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

                                <div
                                    class="
                                        flex
                                        h-10
                                        w-10
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


                                <div class="min-w-0">

                                    <p
                                        class="
                                            truncate
                                            font-semibold
                                            text-green-700
                                        "
                                        x-text="fileName"
                                    ></p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-green-600
                                        "
                                    >
                                        Ready for import
                                        <span class="mx-1">•</span>
                                        <span x-text="fileSize"></span>
                                    </p>

                                </div>

                            </div>



                            <span
                                class="
                                    shrink-0
                                    text-xs
                                    font-semibold
                                    text-green-600
                                "
                            >
                                File Selected
                            </span>


                        </div>

                    </div>


                </div>



                {{-- ====================================================== --}}
                {{-- ZIP INFORMATION --}}
                {{-- ====================================================== --}}

                <div
                    class="
                        border-t
                        border-gray-100
                        bg-gray-50/70
                        px-6
                        py-5
                        sm:px-8
                    "
                >

                    <div
                        class="
                            flex
                            min-w-0
                            flex-col
                            gap-4
                            md:flex-row
                            md:items-center
                            md:justify-between
                        "
                    >


                        <div class="min-w-0">

                            <p
                                class="
                                    text-xs
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                Importing Student Photos?
                            </p>


                            <p
                                class="
                                    mt-1
                                    text-xs
                                    leading-5
                                    text-gray-400
                                "
                            >
                                ZIP files should contain the spreadsheet and
                                a folder named
                                <strong class="text-gray-600">
                                    photos
                                </strong>
                                with filenames matching the spreadsheet photo column.
                            </p>

                        </div>



                        <div
                            class="
                                shrink-0
                                border-l-4
                                border-[#D4A017]
                                bg-white
                                px-4
                                py-3
                                text-xs
                                text-gray-500
                            "
                        >

                            <div class="font-mono leading-5">

                                students.xlsx
                                <br>

                                photos/
                                <br>

                                └── 2024-03184.jpg

                            </div>

                        </div>


                    </div>

                </div>



                {{-- ====================================================== --}}
                {{-- FOOTER --}}
                {{-- ====================================================== --}}

                <div
                    class="
                        flex
                        flex-col-reverse
                        gap-3
                        border-t
                        border-gray-100
                        px-6
                        py-5
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                        sm:px-8
                    "
                >


                    <p
                        class="
                            text-xs
                            leading-5
                            text-gray-400
                        "
                    >
                        Existing student numbers will be updated instead of duplicated.
                    </p>



                    <button
                        type="submit"
                        class="
                            inline-flex
                            shrink-0
                            items-center
                            justify-center
                            gap-2
                            rounded-lg
                            bg-[#D4A017]
                            px-7
                            py-3
                            text-sm
                            font-bold
                            text-[#101064]
                            transition
                            hover:bg-[#101064]
                            hover:text-white
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
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                        Import Students

                    </button>


                </div>


            </div>

        </form>

    </section>


</div>

</x-admin-layout>