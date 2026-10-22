<x-admin-layout>

@php

    $canModify =
        $event->status === 'Upcoming';

    $requiredCount =
        $eligibleStudents
            ->where('participation_type', 'Required')
            ->count();

    $optionalCount =
        $eligibleStudents
            ->where('participation_type', 'Optional')
            ->count();

    $criteriaRuleCount =
        $rules
            ->where('rule_type', 'Criteria')
            ->count();

    $specificRuleCount =
        $rules
            ->where('rule_type', 'Specific')
            ->count();

@endphp


<style>

    [x-cloak] {
        display: none !important;
    }

    .eligibility-hero {
        background:
            linear-gradient(
                100deg,
                rgba(16, 16, 100, .97) 0%,
                rgba(16, 16, 100, .91) 55%,
                rgba(16, 16, 100, .73) 100%
            ),
            url('{{ asset('images/school.jpg') }}');

        background-size: cover;
        background-position: center;
    }

    .module-card {
        box-shadow:
            0 1px 2px rgba(15, 23, 42, .03);
    }

</style>



<div
    class="min-w-0 space-y-7"
    x-data="{
        mode: '{{ old('selection_mode', 'criteria') }}',

        search: '',

        selectedStudent: '{{ old('student_id', '') }}',

        students: @js(
            $students->map(function ($student) {

                return [
                    'id' =>
                        (string) $student->student_id,

                    'number' =>
                        $student->student_number,

                    'name' =>
                        trim(
                            $student->last_name
                            . ', '
                            . $student->first_name
                            . ' '
                            . ($student->middle_name ?? '')
                        ),

                    'college' =>
                        $student->college,

                    'program' =>
                        $student->program_code,

                    'year' =>
                        $student->year_level,
                ];

            })->values()
        ),

        get filteredStudents() {

            let q =
                this.search
                    .toLowerCase()
                    .trim();

            if (!q) {
                return this.students.slice(0, 8);
            }

            return this.students
                .filter(student => {

                    return (
                        student.name
                            .toLowerCase()
                            .includes(q)
                        ||
                        String(student.number ?? '')
                            .toLowerCase()
                            .includes(q)
                        ||
                        String(student.program ?? '')
                            .toLowerCase()
                            .includes(q)
                    );

                })
                .slice(0, 10);
        },

        chooseStudent(student) {

            this.selectedStudent =
                String(student.id);

            this.search =
                student.number
                + ' — '
                + student.name;
        }
    }"
>


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            eligibility-hero
            relative
            overflow-hidden
            rounded-[26px]
            px-8
            py-8
            text-white
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
                gap-7
                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >

            <div class="min-w-0">

                <p
                    class="
                        text-xs
                        font-bold
                        uppercase
                        tracking-[0.32em]
                        text-[#E7C75B]
                    "
                >
                    Participant Eligibility Management
                </p>


                <h1
                    class="
                        mt-3
                        text-3xl
                        font-bold
                        tracking-tight
                    "
                >
                    {{ $event->event_name }}
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
                    Define which students are required or allowed
                    to participate using group criteria or
                    individual student selection.
                </p>


                <div
                    class="
                        mt-5
                        flex
                        flex-wrap
                        items-center
                        gap-3
                        text-xs
                        text-white/65
                    "
                >

                    <span>
                        Event #{{ $event->event_id }}
                    </span>

                    <span>
                        •
                    </span>

                    <span>
                        {{
                            $event->department?->department_name
                            ?? 'University-wide'
                        }}
                    </span>

                    <span>
                        •
                    </span>

                    <span
                        class="
                            inline-flex
                            items-center
                            gap-2
                        "
                    >

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                {{
                                    $event->status === 'Upcoming'
                                        ? 'bg-yellow-400'
                                        : (
                                            $event->status === 'Ongoing'
                                                ? 'bg-green-400'
                                                : 'bg-gray-300'
                                        )
                                }}
                            "
                        ></span>

                        {{ $event->status }}

                    </span>

                </div>

            </div>


            <a
                href="{{ route('events.eligibility.events') }}"
                class="
                    inline-flex
                    shrink-0
                    items-center
                    justify-center
                    rounded-xl
                    bg-[#D4A017]
                    px-5
                    py-3
                    text-sm
                    font-bold
                    text-[#101064]
                    transition
                    hover:bg-white
                "
            >
                ← Available Events
            </a>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- MESSAGES --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <div
            class="
                module-card
                flex
                items-center
                gap-4
                border
                border-green-200
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
                        font-bold
                        text-green-800
                    "
                >
                    Eligibility Updated
                </p>

                <p
                    class="
                        mt-1
                        text-sm
                        text-green-700
                    "
                >
                    {{ session('success') }}
                </p>

            </div>

        </div>

    @endif



    @if($errors->any())

        <div
            class="
                module-card
                border
                border-red-200
                bg-red-50
                px-5
                py-4
            "
        >

            <p
                class="
                    text-sm
                    font-bold
                    text-red-700
                "
            >
                Please check the following:
            </p>

            <ul
                class="
                    mt-2
                    list-disc
                    space-y-1
                    pl-5
                    text-sm
                    text-red-600
                "
            >

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif



    {{-- ====================================================== --}}
    {{-- SUMMARY --}}
    {{-- ====================================================== --}}

    <section>

        <div
            class="
                module-card
                grid
                overflow-hidden
                border
                border-gray-200
                bg-white
                sm:grid-cols-2
                xl:grid-cols-5
            "
        >

            <div
                class="
                    border-b
                    border-gray-100
                    px-5
                    py-5
                    xl:border-b-0
                    xl:border-r
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Total Rules
                </p>

                <p
                    class="
                        mt-1
                        text-2xl
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ $rules->count() }}
                </p>

            </div>


            <div
                class="
                    border-b
                    border-gray-100
                    px-5
                    py-5
                    sm:border-l
                    xl:border-b-0
                    xl:border-l-0
                    xl:border-r
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Criteria Rules
                </p>

                <p
                    class="
                        mt-1
                        text-2xl
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ $criteriaRuleCount }}
                </p>

            </div>


            <div
                class="
                    border-b
                    border-gray-100
                    px-5
                    py-5
                    xl:border-b-0
                    xl:border-r
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Specific Students
                </p>

                <p
                    class="
                        mt-1
                        text-2xl
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ $specificRuleCount }}
                </p>

            </div>


            <div
                class="
                    border-b
                    border-gray-100
                    px-5
                    py-5
                    sm:border-l
                    xl:border-b-0
                    xl:border-l-0
                    xl:border-r
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Required
                </p>

                <p
                    class="
                        mt-1
                        text-2xl
                        font-bold
                        text-red-600
                    "
                >
                    {{ $requiredCount }}
                </p>

            </div>


            <div
                class="
                    px-5
                    py-5
                "
            >

                <p
                    class="
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Eligible Students
                </p>

                <p
                    class="
                        mt-1
                        text-2xl
                        font-bold
                        text-green-600
                    "
                >
                    {{ $eligibleStudents->count() }}
                </p>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- CONFIGURATION --}}
    {{-- ====================================================== --}}

    <section>

        <div class="mb-4">

            <p
                class="
                    text-xs
                    font-bold
                    uppercase
                    tracking-[0.25em]
                    text-[#D4A017]
                "
            >
                Eligibility Configuration
            </p>

            <h2
                class="
                    mt-1
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Add Eligible Participants
            </h2>

            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Select students using group criteria or add an
                individual student directly.
            </p>

        </div>



        @if($canModify)

            <div
                class="
                    module-card
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                "
            >


                {{-- MODE TABS --}}

                <div
                    class="
                        flex
                        border-b
                        border-gray-200
                        bg-gray-50
                        px-5
                        pt-4
                    "
                >

                    <button
                        type="button"
                        @click="mode='criteria'"
                        class="
                            border-b-2
                            px-5
                            py-3
                            text-sm
                            font-semibold
                            transition
                        "
                        :class="
                            mode === 'criteria'
                                ? 'border-[#D4A017] text-[#101064]'
                                : 'border-transparent text-gray-400 hover:text-gray-700'
                        "
                    >
                        By Criteria
                    </button>


                    <button
                        type="button"
                        @click="mode='specific'"
                        class="
                            border-b-2
                            px-5
                            py-3
                            text-sm
                            font-semibold
                            transition
                        "
                        :class="
                            mode === 'specific'
                                ? 'border-[#D4A017] text-[#101064]'
                                : 'border-transparent text-gray-400 hover:text-gray-700'
                        "
                    >
                        Specific Student
                    </button>

                </div>



                {{-- ====================================================== --}}
                {{-- BY CRITERIA --}}
                {{-- ====================================================== --}}

                <div
                    x-show="mode === 'criteria'"
                    x-cloak
                >

                    <form
                        method="POST"
                        action="{{ route(
                            'events.eligibility.store',
                            $event->event_id
                        ) }}"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="selection_mode"
                            value="criteria"
                        >


                        <div
                            class="
                                grid
                                grid-cols-1
                                lg:grid-cols-4
                            "
                        >


                            {{-- COLLEGE --}}

                            <div
                                class="
                                    border-b
                                    border-gray-100
                                    px-6
                                    py-5
                                    lg:border-b-0
                                    lg:border-r
                                "
                            >

                                <label
                                    class="
                                        mb-2
                                        block
                                        text-[10px]
                                        font-bold
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
                                        text-sm
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
                                            {{
                                                old('college')
                                                === $college
                                                    ? 'selected'
                                                    : ''
                                            }}
                                        >
                                            {{ $college }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>



                            {{-- PROGRAM --}}

                            <div
                                class="
                                    border-b
                                    border-gray-100
                                    px-6
                                    py-5
                                    lg:border-b-0
                                    lg:border-r
                                "
                            >

                                <label
                                    class="
                                        mb-2
                                        block
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                    "
                                >
                                    Program / Course
                                </label>


                                <select
                                    name="program_code"
                                    class="
                                        w-full
                                        rounded-lg
                                        border-gray-200
                                        text-sm
                                        focus:border-[#101064]
                                        focus:ring-[#101064]
                                    "
                                >

                                    <option value="">
                                        All Programs
                                    </option>


                                    @foreach($programs as $program)

                                        <option
                                            value="{{ $program->program_code }}"
                                            {{
                                                old('program_code')
                                                ===
                                                $program->program_code
                                                    ? 'selected'
                                                    : ''
                                            }}
                                        >

                                            {{ $program->program_code }}

                                            @if($program->program_name)

                                                —
                                                {{ $program->program_name }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>



                            {{-- YEAR --}}

                            <div
                                class="
                                    border-b
                                    border-gray-100
                                    px-6
                                    py-5
                                    lg:border-b-0
                                    lg:border-r
                                "
                            >

                                <label
                                    class="
                                        mb-2
                                        block
                                        text-[10px]
                                        font-bold
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
                                        text-sm
                                        focus:border-[#101064]
                                        focus:ring-[#101064]
                                    "
                                >

                                    <option value="">
                                        All Year Levels
                                    </option>


                                    @foreach($yearLevels as $year)

                                        <option
                                            value="{{ $year }}"
                                            {{
                                                (string) old('year_level')
                                                === (string) $year
                                                    ? 'selected'
                                                    : ''
                                            }}
                                        >
                                            Year {{ $year }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>



                            {{-- PARTICIPATION --}}

                            <div
                                class="
                                    px-6
                                    py-5
                                "
                            >

                                <label
                                    class="
                                        mb-2
                                        block
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                    "
                                >
                                    Participation Type
                                </label>


                                <select
                                    name="participation_type"
                                    class="
                                        w-full
                                        rounded-lg
                                        border-gray-200
                                        text-sm
                                        focus:border-[#101064]
                                        focus:ring-[#101064]
                                    "
                                >

                                    <option value="Required">
                                        Required
                                    </option>

                                    <option value="Optional">
                                        Optional
                                    </option>

                                </select>

                            </div>

                        </div>


                        <div
                            class="
                                flex
                                flex-col
                                gap-4
                                border-t
                                border-gray-100
                                bg-gray-50
                                px-6
                                py-4
                                sm:flex-row
                                sm:items-center
                                sm:justify-between
                            "
                        >

                            <p
                                class="
                                    text-xs
                                    leading-5
                                    text-gray-500
                                "
                            >
                                Criteria may be combined. Example:
                                College of Computer Studies +
                                BSIT + Year 3.
                            </p>


                            <button
                                type="submit"
                                class="
                                    rounded-lg
                                    bg-[#101064]
                                    px-5
                                    py-2.5
                                    text-sm
                                    font-bold
                                    text-white
                                    transition
                                    hover:bg-[#D4A017]
                                    hover:text-[#101064]
                                "
                            >
                                Add Criteria Rule
                            </button>

                        </div>

                    </form>

                </div>



                {{-- ====================================================== --}}
                {{-- SPECIFIC STUDENT --}}
                {{-- ====================================================== --}}

                <div
                    x-show="mode === 'specific'"
                    x-cloak
                >

                    <form
                        method="POST"
                        action="{{ route(
                            'events.eligibility.store',
                            $event->event_id
                        ) }}"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="selection_mode"
                            value="specific"
                        >

                        <input
                            type="hidden"
                            name="student_id"
                            x-model="selectedStudent"
                        >


                        <div
                            class="
                                grid
                                grid-cols-1
                                lg:grid-cols-[1fr_250px]
                            "
                        >


                            {{-- SEARCH STUDENT --}}

                            <div
                                class="
                                    relative
                                    border-b
                                    border-gray-100
                                    px-6
                                    py-5
                                    lg:border-b-0
                                    lg:border-r
                                "
                            >

                                <label
                                    class="
                                        mb-2
                                        block
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                    "
                                >
                                    Search Student
                                </label>


                                <input
                                    type="text"
                                    x-model="search"
                                    @input="
                                        if (
                                            selectedStudent
                                            &&
                                            search === ''
                                        ) {
                                            selectedStudent = '';
                                        }
                                    "
                                    placeholder="Search student number or name..."
                                    autocomplete="off"
                                    class="
                                        w-full
                                        rounded-lg
                                        border-gray-200
                                        px-4
                                        py-3
                                        text-sm
                                        focus:border-[#101064]
                                        focus:ring-[#101064]
                                    "
                                >


                                <div
                                    x-show="
                                        search.length > 0
                                        && !selectedStudent
                                    "
                                    x-cloak
                                    class="
                                        absolute
                                        left-6
                                        right-6
                                        top-[92px]
                                        z-30
                                        max-h-72
                                        overflow-y-auto
                                        border
                                        border-gray-200
                                        bg-white
                                        shadow-xl
                                    "
                                >

                                    <template
                                        x-for="student in filteredStudents"
                                        :key="student.id"
                                    >

                                        <button
                                            type="button"
                                            @click="chooseStudent(student)"
                                            class="
                                                flex
                                                w-full
                                                items-center
                                                justify-between
                                                gap-4
                                                border-b
                                                border-gray-100
                                                px-4
                                                py-3
                                                text-left
                                                transition
                                                last:border-b-0
                                                hover:bg-gray-50
                                            "
                                        >

                                            <div>

                                                <p
                                                    class="
                                                        text-sm
                                                        font-semibold
                                                        text-[#101064]
                                                    "
                                                    x-text="student.name"
                                                ></p>

                                                <p
                                                    class="
                                                        mt-1
                                                        text-xs
                                                        text-gray-400
                                                    "
                                                >
                                                    <span
                                                        x-text="student.number"
                                                    ></span>

                                                    <span>
                                                        •
                                                    </span>

                                                    <span
                                                        x-text="student.program"
                                                    ></span>

                                                    <span>
                                                        • Year
                                                    </span>

                                                    <span
                                                        x-text="student.year"
                                                    ></span>
                                                </p>

                                            </div>


                                            <span
                                                class="
                                                    text-xs
                                                    font-semibold
                                                    text-[#D4A017]
                                                "
                                            >
                                                Select
                                            </span>

                                        </button>

                                    </template>


                                    <div
                                        x-show="
                                            filteredStudents.length === 0
                                        "
                                        class="
                                            px-4
                                            py-6
                                            text-center
                                            text-sm
                                            text-gray-400
                                        "
                                    >
                                        No student found.
                                    </div>

                                </div>


                                <div
                                    x-show="selectedStudent"
                                    x-cloak
                                    class="
                                        mt-3
                                        flex
                                        items-center
                                        justify-between
                                        gap-4
                                        rounded-lg
                                        border
                                        border-green-200
                                        bg-green-50
                                        px-4
                                        py-3
                                    "
                                >

                                    <p
                                        class="
                                            text-xs
                                            font-semibold
                                            text-green-700
                                        "
                                    >
                                        Student selected
                                    </p>


                                    <button
                                        type="button"
                                        @click="
                                            selectedStudent='';
                                            search='';
                                        "
                                        class="
                                            text-xs
                                            font-semibold
                                            text-red-600
                                            hover:underline
                                        "
                                    >
                                        Change
                                    </button>

                                </div>

                            </div>



                            {{-- PARTICIPATION --}}

                            <div
                                class="
                                    px-6
                                    py-5
                                "
                            >

                                <label
                                    class="
                                        mb-2
                                        block
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                    "
                                >
                                    Participation Type
                                </label>


                                <select
                                    id="specific_participation_type"
                                    name="participation_type"
                                    required
                                    class="
                                        w-full
                                        rounded-lg
                                        border-gray-200
                                        text-sm
                                        focus:border-[#101064]
                                        focus:ring-[#101064]
                                    "
                                >

                                    <option
                                        value=""
                                        disabled
                                        @selected(
                                            old('selection_mode') !== 'specific'
                                            || !in_array(old('participation_type'), ['Required', 'Optional'], true)
                                        )
                                    >
                                        Select participation type
                                    </option>

                                    <option
                                        value="Required"
                                        @selected(old('selection_mode') === 'specific' && old('participation_type') === 'Required')
                                    >
                                        Required - Expected to participate
                                    </option>

                                    <option
                                        value="Optional"
                                        @selected(old('selection_mode') === 'specific' && old('participation_type') === 'Optional')
                                    >
                                        Optional - Allowed to participate
                                    </option>

                                </select>

                                <p class="mt-2 text-xs leading-5 text-gray-500">
                                    Choose whether this student is required to attend or may attend optionally.
                                </p>

                            </div>

                        </div>


                        <div
                            class="
                                flex
                                items-center
                                justify-between
                                gap-5
                                border-t
                                border-gray-100
                                bg-gray-50
                                px-6
                                py-4
                            "
                        >

                            <p
                                class="
                                    text-xs
                                    text-gray-500
                                "
                            >
                                Use this option when eligibility
                                applies to an individual student
                                instead of an entire group.
                            </p>


                            <button
                                type="submit"
                                :disabled="!selectedStudent"
                                class="
                                    rounded-lg
                                    bg-[#101064]
                                    px-5
                                    py-2.5
                                    text-sm
                                    font-bold
                                    text-white
                                    transition
                                    hover:bg-[#D4A017]
                                    hover:text-[#101064]
                                    disabled:cursor-not-allowed
                                    disabled:opacity-40
                                "
                            >
                                Add Specific Student
                            </button>

                        </div>

                    </form>

                </div>

            </div>


        @else

            <div
                class="
                    border
                    border-gray-200
                    bg-white
                    px-6
                    py-5
                "
            >

                <p
                    class="
                        font-bold
                        text-[#101064]
                    "
                >
                    Eligibility configuration is locked
                </p>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Eligibility can only be modified while
                    the event is Upcoming.
                </p>

            </div>

        @endif

    </section>



    {{-- ====================================================== --}}
    {{-- CURRENT RULES --}}
    {{-- ====================================================== --}}

    <section>

        <div
            class="
                mb-4
                flex
                items-end
                justify-between
                gap-5
            "
        >

            <div>

                <p
                    class="
                        text-xs
                        font-bold
                        uppercase
                        tracking-[0.25em]
                        text-[#D4A017]
                    "
                >
                    Configured Rules
                </p>

                <h2
                    class="
                        mt-1
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Current Eligibility Rules
                </h2>

            </div>


            @if(
                $canModify
                && $rules->isNotEmpty()
            )

                <form
                    method="POST"
                    action="{{ route(
                        'events.eligibility.refresh',
                        $event->event_id
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="
                            rounded-lg
                            border
                            border-[#D4A017]
                            px-4
                            py-2
                            text-xs
                            font-semibold
                            text-[#8A6D00]
                            transition
                            hover:bg-[#FFF9E7]
                        "
                    >
                        Refresh Students
                    </button>

                </form>

            @endif

        </div>



        <div
            class="
                module-card
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >

            @forelse($rules as $rule)

                <div
                    class="
                        flex
                        flex-col
                        gap-4
                        border-b
                        border-gray-100
                        px-6
                        py-4
                        last:border-b-0
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                    "
                >

                    <div
                        class="
                            flex
                            min-w-0
                            items-start
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
                                rounded-lg
                                {{
                                    $rule->rule_type === 'Specific'
                                        ? 'bg-purple-50 text-purple-700'
                                        : 'bg-blue-50 text-[#101064]'
                                }}
                            "
                        >
                            {{
                                $rule->rule_type === 'Specific'
                                    ? 'S'
                                    : 'C'
                            }}
                        </div>


                        <div class="min-w-0">

                            <div
                                class="
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-2
                                "
                            >

                                <span
                                    class="
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                    "
                                >
                                    {{
                                        $rule->rule_type === 'Specific'
                                            ? 'Specific Student'
                                            : 'Criteria Rule'
                                    }}
                                </span>


                                <span
                                    class="
                                        rounded-full
                                        px-2.5
                                        py-1
                                        text-[10px]
                                        font-bold
                                        {{
                                            $rule->participation_type === 'Required'
                                                ? 'bg-red-50 text-red-600'
                                                : 'bg-green-50 text-green-700'
                                        }}
                                    "
                                >
                                    {{ $rule->participation_type }}
                                </span>

                            </div>



                            @if($rule->rule_type === 'Specific')

                                <p
                                    class="
                                        mt-2
                                        font-semibold
                                        text-[#101064]
                                    "
                                >
                                    {{
                                        $rule->student?->student_number
                                        ?? 'Unknown Student'
                                    }}

                                    —

                                    {{
                                        trim(
                                            ($rule->student?->last_name ?? '')
                                            . ', '
                                            . ($rule->student?->first_name ?? '')
                                            . ' '
                                            . ($rule->student?->middle_name ?? '')
                                        )
                                    }}
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-gray-400
                                    "
                                >
                                    {{
                                        $rule->student?->program_code
                                        ?? '—'
                                    }}

                                    •

                                    Year
                                    {{
                                        $rule->student?->year_level
                                        ?? '—'
                                    }}
                                </p>


                            @else

                                <div
                                    class="
                                        mt-2
                                        flex
                                        flex-wrap
                                        gap-2
                                        text-xs
                                        text-gray-600
                                    "
                                >

                                    <span
                                        class="
                                            rounded-md
                                            bg-gray-100
                                            px-2.5
                                            py-1.5
                                        "
                                    >
                                        {{
                                            $rule->college
                                            ?? 'All Colleges'
                                        }}
                                    </span>


                                    <span
                                        class="
                                            rounded-md
                                            bg-gray-100
                                            px-2.5
                                            py-1.5
                                        "
                                    >
                                        {{
                                            $rule->program_code
                                            ?? 'All Programs'
                                        }}
                                    </span>


                                    <span
                                        class="
                                            rounded-md
                                            bg-gray-100
                                            px-2.5
                                            py-1.5
                                        "
                                    >
                                        {{
                                            $rule->year_level
                                                ? 'Year '
                                                    . $rule->year_level
                                                : 'All Years'
                                        }}
                                    </span>

                                </div>

                            @endif

                        </div>

                    </div>



                    @if($canModify)

                        <form
                            method="POST"
                            action="{{ route(
                                'events.eligibility.destroy',
                                [
                                    'event' =>
                                        $event->event_id,

                                    'rule' =>
                                        $rule->eligibility_rule_id,
                                ]
                            ) }}"
                            onsubmit="
                                return confirm(
                                    'Remove this eligibility rule?'
                                );
                            "
                        >

                            @csrf
                            @method('DELETE')


                            <button
                                type="submit"
                                class="
                                    rounded-lg
                                    border
                                    border-red-200
                                    px-4
                                    py-2
                                    text-xs
                                    font-semibold
                                    text-red-600
                                    transition
                                    hover:bg-red-50
                                "
                            >
                                Remove
                            </button>

                        </form>

                    @endif

                </div>


            @empty

                <div
                    class="
                        px-6
                        py-12
                        text-center
                    "
                >

                    <p
                        class="
                            font-semibold
                            text-[#101064]
                        "
                    >
                        No eligibility rules yet
                    </p>

                    <p
                        class="
                            mt-1
                            text-sm
                            text-gray-400
                        "
                    >
                        Add criteria or select a specific student.
                    </p>

                </div>

            @endforelse

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ELIGIBLE STUDENT LIST --}}
    {{-- ====================================================== --}}

    <section>

        <div
            class="
                mb-4
                flex
                flex-col
                gap-2
                sm:flex-row
                sm:items-end
                sm:justify-between
            "
        >

            <div>

                <p
                    class="
                        text-xs
                        font-bold
                        uppercase
                        tracking-[0.25em]
                        text-[#D4A017]
                    "
                >
                    Generated Participants
                </p>

                <h2
                    class="
                        mt-1
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Eligible Student List
                </h2>

            </div>


            <div
                class="
                    rounded-lg
                    bg-[#101064]
                    px-4
                    py-2
                    text-sm
                    font-bold
                    text-white
                "
            >
                {{ $eligibleStudents->count() }}
                Students
            </div>

        </div>



        <div
            class="
                module-card
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >

            @if($eligibleStudents->isNotEmpty())

                <div class="overflow-x-auto">

                    <table
                        class="
                            w-full
                            table-auto
                        "
                    >

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
                                        px-5
                                        py-3
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
                                        px-5
                                        py-3
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
                                        px-5
                                        py-3
                                        text-left
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                    "
                                >
                                    College
                                </th>

                                <th
                                    class="
                                        px-5
                                        py-3
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
                                        px-5
                                        py-3
                                        text-center
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                    "
                                >
                                    Year
                                </th>

                                <th
                                    class="
                                        px-5
                                        py-3
                                        text-center
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                    "
                                >
                                    Participation
                                </th>

                            </tr>

                        </thead>


                        <tbody
                            class="
                                divide-y
                                divide-gray-100
                            "
                        >

                            @foreach(
                                $eligibleStudents
                                as $record
                            )

                                @php

                                    $student =
                                        $record->student;

                                @endphp


                                <tr
                                    class="
                                        transition
                                        hover:bg-gray-50
                                    "
                                >

                                    <td
                                        class="
                                            px-5
                                            py-4
                                        "
                                    >

                                        <p
                                            class="
                                                whitespace-nowrap
                                                text-sm
                                                font-semibold
                                                text-[#101064]
                                            "
                                        >
                                            {{
                                                trim(
                                                    ($student?->last_name ?? '')
                                                    . ', '
                                                    . ($student?->first_name ?? '')
                                                    . ' '
                                                    . ($student?->middle_name ?? '')
                                                )
                                            }}
                                        </p>

                                    </td>


                                    <td
                                        class="
                                            whitespace-nowrap
                                            px-5
                                            py-4
                                            text-sm
                                            text-gray-600
                                        "
                                    >
                                        {{
                                            $student?->student_number
                                            ?? '—'
                                        }}
                                    </td>


                                    <td
                                        class="
                                            px-5
                                            py-4
                                            text-sm
                                            text-gray-600
                                        "
                                    >
                                        {{
                                            $student?->college
                                            ?? '—'
                                        }}
                                    </td>


                                    <td
                                        class="
                                            whitespace-nowrap
                                            px-5
                                            py-4
                                            text-sm
                                            font-medium
                                            text-gray-700
                                        "
                                    >
                                        {{
                                            $student?->program_code
                                            ?? '—'
                                        }}
                                    </td>


                                    <td
                                        class="
                                            px-5
                                            py-4
                                            text-center
                                            text-sm
                                            text-gray-600
                                        "
                                    >
                                        {{
                                            $student?->year_level
                                            ?? '—'
                                        }}
                                    </td>


                                    <td
                                        class="
                                            px-5
                                            py-4
                                            text-center
                                        "
                                    >

                                        <span
                                            class="
                                                inline-flex
                                                rounded-full
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                {{
                                                    $record->participation_type === 'Required'
                                                        ? 'bg-red-50 text-red-600'
                                                        : 'bg-green-50 text-green-700'
                                                }}
                                            "
                                        >
                                            {{
                                                $record->participation_type
                                            }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div
                    class="
                        px-6
                        py-14
                        text-center
                    "
                >

                    <p
                        class="
                            font-semibold
                            text-[#101064]
                        "
                    >
                        No eligible students yet
                    </p>

                    <p
                        class="
                            mt-1
                            text-sm
                            text-gray-400
                        "
                    >
                        Add an eligibility rule to generate
                        the participant list.
                    </p>

                </div>

            @endif

        </div>

    </section>


</div>

</x-admin-layout>