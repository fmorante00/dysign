<x-admin-layout>

@php

    $totalPersonnel = $personnel->count();

    $activeAccounts = $personnel->filter(function ($person) {

        return $person->user
            && $person->user->status === 'Active'
            && $person->user->must_change_password == false;

    })->count();


    $pendingSetup = $personnel->filter(function ($person) {

        return $person->user
            && $person->user->must_change_password == true;

    })->count();

@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .personnel-hero {

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

    .personnel-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }


    /*
    |--------------------------------------------------------------------------
    | SCROLLBAR
    |--------------------------------------------------------------------------
    */

    .personnel-scrollbar::-webkit-scrollbar {

        width: 6px;
        height: 6px;

    }

    .personnel-scrollbar::-webkit-scrollbar-track {

        background: #f3f4f6;

    }

    .personnel-scrollbar::-webkit-scrollbar-thumb {

        background: #d1d5db;
        border-radius: 9999px;

    }

    .personnel-scrollbar::-webkit-scrollbar-thumb:hover {

        background: #9ca3af;

    }

</style>


<div
    class="min-w-0 space-y-8"

    x-data="{

        openMenu: null,

        showModal: false,

        formId: '',

        action: '',

        personnelName: '',

        search: '',


        openDropdown(id) {

            this.openMenu =
                this.openMenu === id
                    ? null
                    : id;

        },


        openConfirm(form, action, name) {

            this.formId = form;

            this.action = action;

            this.personnelName = name;

            this.showModal = true;

            this.openMenu = null;

        },


        submitForm() {

            const form =
                document.getElementById(
                    this.formId
                );

            if (form) {

                form.submit();

            }

        }

    }"
>


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            personnel-hero
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
                    User Administration
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
                    Personnel Management
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
                    Manage authorized personnel accounts,
                    departmental assignments, access roles,
                    and account setup within DySign.
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
                                d="M16 21v-2a4 4 0 00-4-4H6a4
                                4 0 00-4 4v2m7-10a4 4 0 100-8
                                4 4 0 000 8zm8 10v-2a4 4 0
                                00-3-3.87"
                            />
                        </svg>

                        <span>
                            {{ $totalPersonnel }}
                            personnel records
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

                        <span>
                            {{ $activeAccounts }}
                            active accounts
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
                                bg-[#E7C75B]
                            "
                        ></span>

                        <span>
                            {{ $pendingSetup }}
                            pending setup
                        </span>

                    </div>


                </div>

            </div>



            {{-- ADD PERSONNEL --}}

            <div class="shrink-0">

                <a
                    href="{{ route('personnel.create') }}"
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
                            d="M12 4v16m8-8H4"
                        />
                    </svg>

                    Add Personnel

                </a>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <div
            class="
                personnel-section
                border
                border-green-200
                bg-white
            "
        >

            <div
                class="
                    flex
                    items-center
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
                        bg-green-50
                        font-bold
                        text-green-600
                    "
                >
                    ✓
                </div>


                <div class="min-w-0">

                    <p
                        class="
                            font-bold
                            text-[#101064]
                        "
                    >
                        Action Completed
                    </p>


                    <p
                        class="
                            mt-1
                            break-words
                            text-sm
                            text-gray-500
                        "
                    >
                        {{ session('success') }}
                    </p>

                </div>

            </div>

        </div>

    @endif



    {{-- ====================================================== --}}
    {{-- PERSONNEL OVERVIEW --}}
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
                    Personnel Account Summary
                </h2>


                <p
                    class="
                        text-sm
                        text-gray-400
                    "
                >
                    Current authorized personnel records
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


            {{-- TOTAL PERSONNEL --}}

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

                    <div class="min-w-0">

                        <p
                            class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Total Personnel
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-[#101064]
                            "
                        >
                            {{ $totalPersonnel }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Registered accounts
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
                                d="M12 12a4 4 0 100-8 4 4 0
                                000 8zm-7 9a7 7 0 0114 0"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- ACTIVE ACCOUNTS --}}

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

                    <div class="min-w-0">

                        <p
                            class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Active Accounts
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-green-600
                            "
                        >
                            {{ $activeAccounts }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Currently active users
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



            {{-- PENDING SETUP --}}

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

                    <div class="min-w-0">

                        <p
                            class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Pending Setup
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-[#D4A017]
                            "
                        >
                            {{ $pendingSetup }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Awaiting account completion
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
                                d="M12 8v4m0 4h.01M10.29
                                3.86L1.82 18a2 2 0 001.71
                                3h16.94a2 2 0 001.71-3L13.71
                                3.86a2 2 0 00-3.42 0z"
                            />
                        </svg>

                    </div>

                </div>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- PERSONNEL DIRECTORY --}}
    {{-- ====================================================== --}}

    <section class="min-w-0">


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
                    Account Registry
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Personnel Directory
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    View and manage authorized system personnel.
                </p>

            </div>


            <a
                href="{{ route('personnel.create') }}"
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
                Add Personnel
                <span>→</span>
            </a>

        </div>



        <div
            class="
                personnel-section
                min-w-0
                border
                border-gray-200
                bg-white
            "
        >


            {{-- SEARCH --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-5
                "
            >

                <div
                    class="
                        flex
                        min-w-0
                        flex-col
                        gap-3
                        md:flex-row
                        md:items-end
                        md:justify-between
                    "
                >

                    <div
                        class="
                            w-full
                            md:max-w-md
                        "
                    >

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
                            Search Personnel
                        </label>


                        <div class="relative">

                            <svg
                                class="
                                    absolute
                                    left-4
                                    top-1/2
                                    h-4
                                    w-4
                                    -translate-y-1/2
                                    text-gray-400
                                "
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35m2.35-5.65a8
                                    8 0 11-16 0 8 8 0 0116 0z"
                                />
                            </svg>


                            <input
                                type="text"
                                x-model="search"
                                placeholder="Search name, email, department, position, or role..."
                                class="
                                    w-full
                                    rounded-lg
                                    border-gray-200
                                    py-2.5
                                    pl-11
                                    pr-4
                                    text-sm
                                    text-gray-700
                                    placeholder:text-gray-400
                                    focus:border-[#101064]
                                    focus:ring-[#101064]
                                "
                            >

                        </div>

                    </div>


                    <p
                        class="
                            shrink-0
                            text-xs
                            text-gray-400
                        "
                    >
                        {{ $totalPersonnel }}
                        registered personnel
                    </p>

                </div>

            </div>



            {{-- TABLE --}}

            <div
                class="
                    personnel-scrollbar
                    w-full
                    max-w-full
                    overflow-x-auto
                "
            >

                <table
                    class="
                        w-full
                        min-w-[1150px]
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
                                Personnel
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
                                Department
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
                                Position
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
                                Role
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



                    <tbody
                        class="
                            divide-y
                            divide-gray-100
                            bg-white
                        "
                    >


                        @foreach($personnel as $person)

                            @php

                                $searchText = strtolower(
                                    ($person->first_name ?? '') . ' ' .
                                    ($person->last_name ?? '') . ' ' .
                                    ($person->department ?? '') . ' ' .
                                    ($person->position ?? '') . ' ' .
                                    ($person->user->email ?? '') . ' ' .
                                    ($person->user->role->role_name ?? '')
                                );

                            @endphp


                            <tr
                                data-search="{{ $searchText }}"

                                x-show="
                                    $el.dataset.search.includes(
                                        search.toLowerCase()
                                    )
                                "

                                class="
                                    transition
                                    hover:bg-gray-50
                                "
                            >


                                {{-- PERSONNEL --}}

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
                                                    $person->first_name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        </div>



                                        <div class="min-w-0">

                                            <p
                                                class="
                                                    max-w-[240px]
                                                    truncate
                                                    font-semibold
                                                    text-[#101064]
                                                "
                                            >
                                                {{ $person->first_name }}
                                                {{ $person->last_name }}
                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    max-w-[240px]
                                                    truncate
                                                    text-xs
                                                    text-gray-400
                                                "
                                            >
                                                {{ $person->user->email }}
                                            </p>

                                        </div>

                                    </div>

                                </td>



                                {{-- DEPARTMENT --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-sm
                                        text-gray-600
                                    "
                                >
                                    {{ $person->department }}
                                </td>



                                {{-- POSITION --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-sm
                                        text-gray-600
                                    "
                                >
                                    {{ $person->position }}
                                </td>



                                {{-- ROLE --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-sm
                                        font-semibold
                                        text-[#101064]
                                    "
                                >
                                    {{ $person->user->role->role_name }}
                                </td>



                                {{-- STATUS --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                    "
                                >


                                    @if($person->user->status == 'Inactive')


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

                                            Inactive

                                        </span>


                                    @elseif($person->user->must_change_password)


                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-[#FFF9E7]
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-[#B68A0D]
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

                                            Pending Setup

                                        </span>


                                    @else


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


                                    @endif


                                </td>



                                {{-- ACTION --}}

                                <td
                                    class="
                                        relative
                                        px-6
                                        py-5
                                        text-right
                                    "
                                >


                                    <div class="relative inline-block">


                                        <button
                                            type="button"
                                            @click.stop="
                                                openDropdown(
                                                    {{ $person->personnel_id }}
                                                )
                                            "
                                            class="
                                                inline-flex
                                                h-9
                                                w-9
                                                items-center
                                                justify-center
                                                rounded-lg
                                                border
                                                border-gray-200
                                                bg-white
                                                text-lg
                                                text-gray-500
                                                transition
                                                hover:border-[#101064]
                                                hover:bg-gray-50
                                                hover:text-[#101064]
                                            "
                                        >
                                            ⋮
                                        </button>



                                        {{-- DROPDOWN --}}

                                        <div
                                            x-show="
                                                openMenu ===
                                                {{ $person->personnel_id }}
                                            "
                                            @click.outside="
                                                openMenu = null
                                            "
                                            x-transition

                                            style="display:none;"

                                            class="
                                                absolute
                                                bottom-11
                                                right-0
                                                z-[999]
                                                w-52
                                                overflow-hidden
                                                border
                                                border-gray-200
                                                bg-white
                                                text-left
                                                shadow-xl
                                            "
                                        >


                                            <a
                                                href="{{ route(
                                                    'personnel.show',
                                                    $person->personnel_id
                                                ) }}"
                                                class="
                                                    block
                                                    border-b
                                                    border-gray-100
                                                    px-5
                                                    py-3
                                                    text-sm
                                                    font-medium
                                                    text-gray-700
                                                    transition
                                                    hover:bg-gray-50
                                                    hover:text-[#101064]
                                                "
                                            >
                                                View Details
                                            </a>



                                            <a
                                                href="{{ route(
                                                    'personnel.edit',
                                                    $person->personnel_id
                                                ) }}"
                                                class="
                                                    block
                                                    border-b
                                                    border-gray-100
                                                    px-5
                                                    py-3
                                                    text-sm
                                                    font-medium
                                                    text-gray-700
                                                    transition
                                                    hover:bg-gray-50
                                                    hover:text-[#101064]
                                                "
                                            >
                                                Edit Personnel
                                            </a>



                                            @if(
                                                $person
                                                    ->user
                                                    ->must_change_password
                                            )

                                                <form
                                                    method="POST"

                                                    action="{{
                                                        route(
                                                            'personnel.resendInvitation',
                                                            $person->personnel_id
                                                        )
                                                    }}"
                                                >

                                                    @csrf


                                                    <button
                                                        type="submit"
                                                        class="
                                                            block
                                                            w-full
                                                            border-b
                                                            border-gray-100
                                                            px-5
                                                            py-3
                                                            text-left
                                                            text-sm
                                                            font-medium
                                                            text-[#101064]
                                                            transition
                                                            hover:bg-gray-50
                                                        "
                                                    >
                                                        Resend Invitation
                                                    </button>

                                                </form>

                                            @endif



                                            @if(
                                                $person->user->status
                                                == 'Active'
                                            )


                                                <form
                                                    id="deactivate-{{ $person->personnel_id }}"

                                                    method="POST"

                                                    action="{{
                                                        route(
                                                            'personnel.destroy',
                                                            $person->personnel_id
                                                        )
                                                    }}"
                                                >

                                                    @csrf
                                                    @method('DELETE')


                                                    <button
                                                        type="button"

                                                        @click="
                                                            openConfirm(
                                                                'deactivate-{{ $person->personnel_id }}',
                                                                'deactivate',
                                                                @js(
                                                                    $person->first_name
                                                                    . ' '
                                                                    . $person->last_name
                                                                )
                                                            )
                                                        "

                                                        class="
                                                            block
                                                            w-full
                                                            px-5
                                                            py-3
                                                            text-left
                                                            text-sm
                                                            font-medium
                                                            text-[#8A6D00]
                                                            transition
                                                            hover:bg-[#FFF9E7]
                                                        "
                                                    >
                                                        Deactivate Account
                                                    </button>

                                                </form>


                                            @else


                                                <form
                                                    id="activate-{{ $person->personnel_id }}"

                                                    method="POST"

                                                    action="{{
                                                        route(
                                                            'personnel.activate',
                                                            $person->personnel_id
                                                        )
                                                    }}"
                                                >

                                                    @csrf
                                                    @method('PATCH')


                                                    <button
                                                        type="button"

                                                        @click="
                                                            openConfirm(
                                                                'activate-{{ $person->personnel_id }}',
                                                                'activate',
                                                                @js(
                                                                    $person->first_name
                                                                    . ' '
                                                                    . $person->last_name
                                                                )
                                                            )
                                                        "

                                                        class="
                                                            block
                                                            w-full
                                                            px-5
                                                            py-3
                                                            text-left
                                                            text-sm
                                                            font-medium
                                                            text-green-700
                                                            transition
                                                            hover:bg-green-50
                                                        "
                                                    >
                                                        Activate Account
                                                    </button>

                                                </form>


                                            @endif


                                        </div>


                                    </div>


                                </td>


                            </tr>


                        @endforeach


                    </tbody>

                </table>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- CONFIRMATION MODAL --}}
    {{-- ====================================================== --}}

    <div
        x-show="showModal"
        x-transition.opacity

        style="display:none;"

        class="
            fixed
            inset-0
            z-[9999]
            flex
            items-center
            justify-center
            bg-black/50
            px-4
            py-6
            backdrop-blur-[2px]
        "
    >


        <div
            @click.outside="showModal = false"

            class="
                w-full
                max-w-md
                overflow-hidden
                rounded-2xl
                bg-white
                shadow-2xl
            "
        >


            {{-- MODAL HEADER --}}

            <div
                class="
                    relative
                    bg-[#101064]
                    px-6
                    py-5
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
                        flex
                        items-center
                        justify-between
                        gap-4
                    "
                >

                    <div>

                        <p
                            class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-[0.25em]
                                text-[#E7C75B]
                            "
                        >
                            Account Management
                        </p>


                        <h2
                            class="
                                mt-2
                                text-xl
                                font-bold
                            "
                        >
                            Confirm Account Action
                        </h2>

                    </div>


                    <button
                        type="button"
                        @click="showModal = false"
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-full
                            text-white/70
                            transition
                            hover:bg-white/10
                            hover:text-white
                        "
                    >
                        ×
                    </button>

                </div>

            </div>



            {{-- MODAL BODY --}}

            <div class="px-6 py-6">


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
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-[#FFF9E7]
                            text-lg
                            font-bold
                            text-[#B68A0D]
                        "
                    >
                        !
                    </div>


                    <div class="min-w-0">

                        <p
                            class="
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >

                            Are you sure you want to

                            <span
                                class="
                                    font-bold
                                    text-[#101064]
                                "
                                x-text="action"
                            ></span>

                            this account?

                        </p>


                        <p
                            class="
                                mt-3
                                break-words
                                font-bold
                                text-[#101064]
                            "
                            x-text="personnelName"
                        ></p>

                    </div>

                </div>


            </div>



            {{-- MODAL FOOTER --}}

            <div
                class="
                    flex
                    justify-end
                    gap-3
                    border-t
                    border-gray-100
                    bg-gray-50
                    px-6
                    py-4
                "
            >

                <button
                    type="button"
                    @click="showModal = false"
                    class="
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        px-5
                        py-2.5
                        text-sm
                        font-semibold
                        text-gray-600
                        transition
                        hover:bg-gray-50
                    "
                >
                    Cancel
                </button>


                <button
                    type="button"
                    @click="submitForm()"
                    class="
                        rounded-lg
                        bg-[#101064]
                        px-5
                        py-2.5
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-[#D4A017]
                        hover:text-[#101064]
                    "
                >
                    Confirm
                </button>

            </div>


        </div>

    </div>


</div>

</x-admin-layout>