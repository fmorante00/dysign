<x-admin-layout>

@php

    /*
    |--------------------------------------------------------------------------
    | PERSONNEL STATUS
    |--------------------------------------------------------------------------
    */

    if ($personnel->user->status === 'Inactive') {

        $accountStatus = 'Inactive';
        $accountStatusClass = 'bg-gray-100 text-gray-600';
        $accountDotClass = 'bg-gray-400';

    } elseif ($personnel->user->must_change_password) {

        $accountStatus = 'Pending Setup';
        $accountStatusClass = 'bg-[#FFF9E7] text-[#B68A0D]';
        $accountDotClass = 'bg-[#D4A017]';

    } else {

        $accountStatus = 'Active';
        $accountStatusClass = 'bg-green-50 text-green-700';
        $accountDotClass = 'bg-green-500';

    }


    /*
    |--------------------------------------------------------------------------
    | INVITATION
    |--------------------------------------------------------------------------
    */

    $invitation = $personnel->user->invitation;

    $invitationCompleted =
        $invitation
        && $invitation->used_at;

@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .personnel-profile-hero {

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

    .personnel-profile-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }


    /*
    |--------------------------------------------------------------------------
    | AVATAR
    |--------------------------------------------------------------------------
    */

    .personnel-profile-avatar {

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
            personnel-profile-hero
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


            {{-- PERSONNEL IDENTITY --}}

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


                {{-- AVATAR --}}

                <div
                    class="
                        personnel-profile-avatar
                        flex
                        shrink-0
                        items-center
                        justify-center
                        rounded-full
                        border-4
                        border-white/20
                        bg-white/10
                        text-3xl
                        font-bold
                        text-white
                    "
                >

                    {{ strtoupper(
                        substr(
                            $personnel->first_name,
                            0,
                            1
                        )
                    ) }}

                </div>



                {{-- DETAILS --}}

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
                        Personnel Profile
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
                        {{ $personnel->first_name }}
                        {{ $personnel->last_name }}
                    </h1>



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
                            {{ $personnel->position }}
                        </span>


                        <span class="text-white/30">
                            •
                        </span>


                        <span>
                            {{ $personnel->department }}
                        </span>


                        <span class="text-white/30">
                            •
                        </span>


                        <span>
                            {{ $personnel->user->role->role_name ?? 'No Role' }}
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
                                    {{ $accountDotClass }}
                                "
                            ></span>

                            {{ $accountStatus }}

                        </span>

                    </div>

                </div>

            </div>



            {{-- BACK BUTTON --}}

            <div class="shrink-0">

                <a
                    href="{{ route('personnel.index') }}"
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

                    Back to Personnel

                </a>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- PERSONNEL INFORMATION --}}
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
                Personnel Record
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Personnel Information
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Official personnel and departmental information.
            </p>

        </div>



        <div
            class="
                personnel-profile-section
                grid
                min-w-0
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                md:grid-cols-2
                xl:grid-cols-4
            "
        >


            {{-- FIRST NAME --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-r
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
                    First Name
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $personnel->first_name }}
                </p>

            </div>



            {{-- LAST NAME --}}

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
                    Last Name
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $personnel->last_name }}
                </p>

            </div>



            {{-- DEPARTMENT --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-r
                    md:border-b-0
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
                    Department
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $personnel->department }}
                </p>

            </div>



            {{-- POSITION --}}

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
                    Position
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $personnel->position }}
                </p>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ACCOUNT INFORMATION --}}
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
                System Access
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Account Information
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                DySign account credentials and access classification.
            </p>

        </div>



        <div
            class="
                personnel-profile-section
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


            {{-- USERNAME --}}

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
                    Username
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $personnel->user->username }}
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
                    {{ $personnel->user->email }}
                </p>

            </div>



            {{-- ROLE --}}

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
                    Assigned Role
                </p>


                <p
                    class="
                        mt-2
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ $personnel->user->role->role_name ?? 'N/A' }}
                </p>

            </div>



            {{-- STATUS --}}

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
                    Account Status
                </p>


                <div class="mt-2">

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
                            {{ $accountStatusClass }}
                        "
                    >

                        <span
                            class="
                                h-1.5
                                w-1.5
                                rounded-full
                                {{ $accountDotClass }}
                            "
                        ></span>

                        {{ $accountStatus }}

                    </span>

                </div>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ACCOUNT SETUP DETAILS --}}
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
                Account Lifecycle
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Account Setup Details
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Invitation and account activation information.
            </p>

        </div>



        <div
            class="
                personnel-profile-section
                grid
                min-w-0
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                md:grid-cols-2
                xl:grid-cols-4
            "
        >


            {{-- ACCOUNT CREATED --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-r
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
                    Account Created
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $personnel->created_at
                        ? $personnel->created_at->format('M d, Y')
                        : 'N/A'
                    }}
                </p>

            </div>



            {{-- INVITATION STATUS --}}

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
                    Invitation Status
                </p>


                <div class="mt-2">


                    @if($invitationCompleted)

                        <span
                            class="
                                inline-flex
                                items-center
                                gap-2
                                text-sm
                                font-semibold
                                text-green-600
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

                            Completed

                        </span>

                    @else

                        <span
                            class="
                                inline-flex
                                items-center
                                gap-2
                                text-sm
                                font-semibold
                                text-[#B68A0D]
                            "
                        >

                            <span
                                class="
                                    h-2
                                    w-2
                                    rounded-full
                                    bg-[#D4A017]
                                "
                            ></span>

                            Pending

                        </span>

                    @endif


                </div>

            </div>



            {{-- EXPIRATION --}}

            <div
                class="
                    min-w-0
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-r
                    md:border-b-0
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
                    Invitation Expiration
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        font-semibold
                        text-gray-700
                    "
                >

                    {{ $invitation?->expires_at
                        ? \Carbon\Carbon::parse(
                            $invitation->expires_at
                        )->format('M d, Y • h:i A')
                        : 'N/A'
                    }}

                </p>

            </div>



            {{-- PASSWORD SETUP --}}

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
                    Password Setup
                </p>


                <div class="mt-2">


                    @if($personnel->user->must_change_password)

                        <span
                            class="
                                inline-flex
                                items-center
                                gap-2
                                text-sm
                                font-semibold
                                text-[#B68A0D]
                            "
                        >

                            <span
                                class="
                                    h-2
                                    w-2
                                    rounded-full
                                    bg-[#D4A017]
                                "
                            ></span>

                            Not Completed

                        </span>

                    @else

                        <span
                            class="
                                inline-flex
                                items-center
                                gap-2
                                text-sm
                                font-semibold
                                text-green-600
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

                            Completed

                        </span>

                    @endif


                </div>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SETUP STATUS NOTICE --}}
    {{-- ====================================================== --}}

    @if($personnel->user->must_change_password)

        <section
            class="
                personnel-profile-section
                min-w-0
                overflow-hidden
                border
                border-[#E7C75B]
                bg-white
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-5
                    px-6
                    py-6
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
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
                            h-11
                            w-11
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
                                d="M3 8l9 6 9-6m-18 0l9-5 9 5v8l-9 5-9-5V8z"
                            />
                        </svg>

                    </div>



                    <div class="min-w-0">

                        <p
                            class="
                                font-bold
                                text-[#101064]
                            "
                        >
                            Account Setup Pending
                        </p>


                        <p
                            class="
                                mt-1
                                max-w-2xl
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            This personnel account has not completed the
                            secure password setup process. You may resend
                            the account invitation if necessary.
                        </p>

                    </div>

                </div>



                <form
                    method="POST"
                    action="{{ route(
                        'personnel.resendInvitation',
                        $personnel->personnel_id
                    ) }}"
                    class="shrink-0"
                >

                    @csrf


                    <button
                        type="submit"
                        class="
                            inline-flex
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
                                d="M4 4v6h6M20 20v-6h-6M5.64
                                18.36A9 9 0 1018.36 5.64"
                            />
                        </svg>

                        Resend Invitation

                    </button>

                </form>


            </div>

        </section>

    @endif



    {{-- ====================================================== --}}
    {{-- SYSTEM RECORD --}}
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

        </div>



        <div
            class="
                personnel-profile-section
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


            {{-- ID --}}

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
                    Personnel ID
                </p>


                <p
                    class="
                        mt-2
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $personnel->personnel_id }}
                </p>

            </div>



            {{-- CREATED --}}

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
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $personnel->created_at
                        ? $personnel->created_at->format(
                            'M d, Y • g:i A'
                        )
                        : '—'
                    }}
                </p>

            </div>



            {{-- UPDATED --}}

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
                        font-semibold
                        text-gray-700
                    "
                >
                    {{ $personnel->updated_at
                        ? $personnel->updated_at->format(
                            'M d, Y • g:i A'
                        )
                        : '—'
                    }}
                </p>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ACTIONS --}}
    {{-- ====================================================== --}}

    <section
        class="
            personnel-profile-section
            min-w-0
            overflow-hidden
            border
            border-gray-200
            bg-white
        "
    >

        <div
            class="
                flex
                min-w-0
                flex-col
                gap-4
                px-6
                py-5
                sm:flex-row
                sm:items-center
                sm:justify-between
            "
        >

            <div>

                <p
                    class="
                        text-xs
                        font-bold
                        text-[#101064]
                    "
                >
                    Personnel Management
                </p>


                <p
                    class="
                        mt-1
                        text-xs
                        text-gray-400
                    "
                >
                    Update this personnel's profile and system access.
                </p>

            </div>



            <div
                class="
                    flex
                    flex-wrap
                    gap-3
                "
            >


                @if($personnel->user->must_change_password)

                    <form
                        method="POST"
                        action="{{ route(
                            'personnel.resendInvitation',
                            $personnel->personnel_id
                        ) }}"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="
                                inline-flex
                                items-center
                                justify-center
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
                            Resend Invitation
                        </button>

                    </form>

                @endif



                <a
                    href="{{ route(
                        'personnel.edit',
                        $personnel->personnel_id
                    ) }}"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        bg-[#101064]
                        px-5
                        py-2.5
                        text-xs
                        font-semibold
                        text-white
                        transition
                        hover:bg-[#D4A017]
                        hover:text-[#101064]
                    "
                >

                    Edit Personnel

                    <span>→</span>

                </a>


            </div>

        </div>

    </section>


</div>

</x-admin-layout>