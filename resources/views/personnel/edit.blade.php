<x-admin-layout>

@php

    /*
    |--------------------------------------------------------------------------
    | ACCOUNT STATUS
    |--------------------------------------------------------------------------
    */

    if ($personnel->user->status === 'Inactive') {

        $accountStatus = 'Inactive Account';
        $accountStatusClass = 'bg-gray-100 text-gray-600';
        $accountDotClass = 'bg-gray-400';

    } elseif ($personnel->user->must_change_password) {

        $accountStatus = 'Pending Setup';
        $accountStatusClass = 'bg-[#FFF9E7] text-[#B68A0D]';
        $accountDotClass = 'bg-[#D4A017]';

    } else {

        $accountStatus = 'Active Account';
        $accountStatusClass = 'bg-green-50 text-green-700';
        $accountDotClass = 'bg-green-500';

    }

@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .personnel-edit-hero {

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

    .personnel-edit-section {

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
            personnel-edit-hero
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
                min-w-0
                flex-col
                gap-8
                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >


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
                    Personnel Administration
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
                    Edit Personnel Account
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
                    Update personnel profile information,
                    departmental assignment, and system access.
                </p>


                <div
                    class="
                        mt-6
                        flex
                        flex-wrap
                        items-center
                        gap-x-6
                        gap-y-3
                        text-sm
                        text-white/70
                    "
                >

                    <span>
                        {{ $personnel->first_name }}
                        {{ $personnel->last_name }}
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



            <div class="shrink-0">

                <a
                    href="{{ route(
                        'personnel.show',
                        $personnel->personnel_id
                    ) }}"
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

                    Back to Profile

                </a>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ====================================================== --}}

    @if($errors->any())

        <section
            class="
                personnel-edit-section
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

                    <p class="font-bold text-red-700">
                        Changes could not be saved
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

        </section>

    @endif



    {{-- ====================================================== --}}
    {{-- EDIT FORM --}}
    {{-- ====================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'personnel.update',
            $personnel->personnel_id
        ) }}"
        class="space-y-8"
    >

        @csrf
        @method('PUT')



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
                    Update the personnel's official identity
                    and departmental information.
                </p>

            </div>



            <div
                class="
                    personnel-edit-section
                    min-w-0
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                "
            >

                <div
                    class="
                        grid
                        min-w-0
                        grid-cols-1
                        md:grid-cols-2
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
                        "
                    >

                        <label
                            for="first_name"
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
                            First Name
                            <span class="text-red-500">*</span>
                        </label>


                        <input
                            id="first_name"
                            type="text"
                            name="first_name"
                            value="{{ old(
                                'first_name',
                                $personnel->first_name
                            ) }}"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >


                        @error('first_name')
                            <p class="mt-2 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>



                    {{-- LAST NAME --}}

                    <div
                        class="
                            min-w-0
                            border-b
                            border-gray-100
                            px-6
                            py-6
                        "
                    >

                        <label
                            for="last_name"
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
                            Last Name
                            <span class="text-red-500">*</span>
                        </label>


                        <input
                            id="last_name"
                            type="text"
                            name="last_name"
                            value="{{ old(
                                'last_name',
                                $personnel->last_name
                            ) }}"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >


                        @error('last_name')
                            <p class="mt-2 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>



                    {{-- DEPARTMENT --}}

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

                        <label
                            for="department"
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
                            Department
                            <span class="text-red-500">*</span>
                        </label>


                        <input
                            id="department"
                            type="text"
                            name="department"
                            value="{{ old(
                                'department',
                                $personnel->department
                            ) }}"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >


                        @error('department')
                            <p class="mt-2 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>



                    {{-- POSITION --}}

                    <div
                        class="
                            min-w-0
                            px-6
                            py-6
                        "
                    >

                        <label
                            for="position"
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
                            Position
                            <span class="text-red-500">*</span>
                        </label>


                        <input
                            id="position"
                            type="text"
                            name="position"
                            value="{{ old(
                                'position',
                                $personnel->position
                            ) }}"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >


                        @error('position')
                            <p class="mt-2 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


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
                    System Account
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
                    Account credentials are displayed for reference
                    and cannot be edited from this page.
                </p>

            </div>



            <div
                class="
                    personnel-edit-section
                    min-w-0
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                "
            >

                <div
                    class="
                        grid
                        min-w-0
                        grid-cols-1
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
                            md:border-b-0
                            md:border-r
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
                            Username
                        </label>


                        <input
                            type="text"
                            value="{{ $personnel->user->username }}"
                            disabled
                            class="
                                w-full
                                cursor-not-allowed
                                rounded-lg
                                border-gray-200
                                bg-gray-50
                                px-4
                                py-3
                                text-sm
                                text-gray-500
                            "
                        >


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Username is managed as an account credential.
                        </p>

                    </div>



                    {{-- EMAIL --}}

                    <div
                        class="
                            min-w-0
                            px-6
                            py-6
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
                            Email Address
                        </label>


                        <input
                            type="email"
                            value="{{ $personnel->user->email }}"
                            disabled
                            class="
                                w-full
                                cursor-not-allowed
                                rounded-lg
                                border-gray-200
                                bg-gray-50
                                px-4
                                py-3
                                text-sm
                                text-gray-500
                            "
                        >


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Used for account communication and invitations.
                        </p>

                    </div>


                </div>

            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- ACCESS CONTROL --}}
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
                    Access Control
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Manage role assignment and review the current
                    account availability.
                </p>

            </div>



            <div
                class="
                    personnel-edit-section
                    min-w-0
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                "
            >

                <div
                    class="
                        grid
                        min-w-0
                        grid-cols-1
                        md:grid-cols-2
                    "
                >


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

                        <label
                            for="role_id"
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
                            Assigned Role
                            <span class="text-red-500">*</span>
                        </label>


                        <select
                            id="role_id"
                            name="role_id"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >

                            @foreach($roles as $role)

                                <option
                                    value="{{ $role->role_id }}"
                                    {{
                                        old(
                                            'role_id',
                                            $personnel->user->role_id
                                        ) == $role->role_id
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    {{ $role->role_name }}
                                </option>

                            @endforeach

                        </select>


                        @error('role_id')
                            <p class="mt-2 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror


                        <p
                            class="
                                mt-2
                                text-xs
                                leading-5
                                text-gray-400
                            "
                        >
                            Changing the role may change the modules
                            and permissions available to this user.
                        </p>

                    </div>



                    {{-- STATUS --}}

                    <div
                        class="
                            min-w-0
                            bg-gray-50/60
                            px-6
                            py-6
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
                            Current Account Status
                        </p>


                        <div class="mt-3">

                            <span
                                class="
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-full
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    {{ $accountStatusClass }}
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


                        <p
                            class="
                                mt-4
                                text-xs
                                leading-5
                                text-gray-500
                            "
                        >

                            @if($personnel->user->status === 'Inactive')

                                This account is currently inactive.
                                Account activation is managed from
                                Personnel Management.

                            @elseif($personnel->user->must_change_password)

                                This user has not yet completed the
                                secure account setup process.

                            @else

                                This account is active and available
                                for authorized system access.

                            @endif

                        </p>

                    </div>


                </div>

            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- RECORD INFORMATION --}}
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
                    personnel-edit-section
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
                            font-bold
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
                            font-bold
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
                            font-bold
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
                personnel-edit-section
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

                <div class="min-w-0">

                    <p
                        class="
                            text-xs
                            font-bold
                            text-[#101064]
                        "
                    >
                        Save Personnel Changes
                    </p>


                    <p
                        class="
                            mt-1
                            text-xs
                            leading-5
                            text-gray-400
                        "
                    >
                        Review the updated information before
                        saving changes to this account.
                    </p>

                </div>



                <div
                    class="
                        flex
                        shrink-0
                        flex-wrap
                        justify-end
                        gap-3
                    "
                >

                    <a
                        href="{{ route(
                            'personnel.show',
                            $personnel->personnel_id
                        ) }}"
                        class="
                            inline-flex
                            items-center
                            justify-center
                            rounded-lg
                            border
                            border-gray-200
                            bg-white
                            px-6
                            py-3
                            text-sm
                            font-semibold
                            text-gray-600
                            transition
                            hover:bg-gray-50
                        "
                    >
                        Cancel
                    </a>


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
                            py-3
                            text-sm
                            font-bold
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
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                        Save Changes

                    </button>

                </div>

            </div>

        </section>


    </form>


</div>

</x-admin-layout>