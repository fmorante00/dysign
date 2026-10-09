<x-admin-layout>

<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .personnel-create-hero {
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

    .personnel-create-section {
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
            personnel-create-hero
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
                    Account Registration
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
                    Create Personnel Account
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
                    Register authorized personnel, configure their
                    system access, and send a secure account setup
                    invitation.
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

                    <div class="flex items-center gap-2">

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-green-400
                            "
                        ></span>

                        Secure invitation setup

                    </div>


                    <div class="flex items-center gap-2">

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-[#E7C75B]
                            "
                        ></span>

                        Role-based system access

                    </div>

                </div>

            </div>



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
    {{-- VALIDATION ERRORS --}}
    {{-- ====================================================== --}}

    @if($errors->any())

        <section
            class="
                personnel-create-section
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
                        Please review the information entered
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
    {{-- CREATE FORM --}}
    {{-- ====================================================== --}}

    <form
        method="POST"
        action="{{ route('personnel.store') }}"
        class="space-y-8"
    >

        @csrf



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
                    Enter the official identity and departmental
                    information of the personnel.
                </p>

            </div>



            <div
                class="
                    personnel-create-section
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
                            value="{{ old('first_name') }}"
                            placeholder="Enter first name"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                placeholder:text-gray-400
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
                            value="{{ old('last_name') }}"
                            placeholder="Enter last name"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                placeholder:text-gray-400
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
                            value="{{ old('department') }}"
                            placeholder="Example: Registrar Office"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                placeholder:text-gray-400
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
                            value="{{ old('position') }}"
                            placeholder="Example: Registrar Staff"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                placeholder:text-gray-400
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
                    Create the account identity that will be used
                    to access DySign.
                </p>

            </div>



            <div
                class="
                    personnel-create-section
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
                            md:border-r
                            md:border-b-0
                        "
                    >

                        <label
                            for="username"
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
                            <span class="text-red-500">*</span>
                        </label>


                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Enter username"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                placeholder:text-gray-400
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >


                        @error('username')
                            <p class="mt-2 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

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
                            for="email"
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
                            <span class="text-red-500">*</span>
                        </label>


                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="example@email.com"
                            required
                            class="
                                w-full
                                rounded-lg
                                border-gray-200
                                px-4
                                py-3
                                text-sm
                                text-gray-700
                                placeholder:text-gray-400
                                focus:border-[#101064]
                                focus:ring-[#101064]
                            "
                        >


                        @error('email')
                            <p class="mt-2 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                </div>



                {{-- ====================================================== --}}
                {{-- INVITATION NOTICE --}}
                {{-- ====================================================== --}}

                <div
                    class="
                        border-t
                        border-gray-100
                        bg-[#FFFDF7]
                        px-6
                        py-5
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
                                Secure Account Setup
                            </p>


                            <p
                                class="
                                    mt-1
                                    max-w-3xl
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                After the account is created, the
                                personnel will receive an email
                                invitation containing a secure setup
                                link. They will create their own
                                password through the invitation.
                            </p>

                        </div>

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
                    Assign the appropriate system role and access
                    level to this personnel.
                </p>

            </div>



            <div
                class="
                    personnel-create-section
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
                        grid-cols-1
                        lg:grid-cols-[1fr_340px]
                    "
                >


                    {{-- ROLE FIELD --}}

                    <div
                        class="
                            min-w-0
                            border-b
                            border-gray-100
                            px-6
                            py-6
                            lg:border-b-0
                            lg:border-r
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

                            <option value="">
                                Select role
                            </option>


                            @foreach($roles as $role)

                                <option
                                    value="{{ $role->role_id }}"
                                    {{ old('role_id') == $role->role_id
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

                    </div>



                    {{-- ROLE INFO --}}

                    <div
                        class="
                            min-w-0
                            bg-gray-50/70
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
                            Access Assignment
                        </p>


                        <p
                            class="
                                mt-2
                                text-sm
                                font-semibold
                                text-[#101064]
                            "
                        >
                            Role-Based Permissions
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                leading-5
                                text-gray-500
                            "
                        >
                            The selected role determines which
                            DySign modules and administrative
                            functions this account can access.
                        </p>

                    </div>


                </div>

            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- FORM ACTIONS --}}
        {{-- ====================================================== --}}

        <section
            class="
                personnel-create-section
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
                        Ready to create this account?
                    </p>


                    <p
                        class="
                            mt-1
                            text-xs
                            leading-5
                            text-gray-400
                        "
                    >
                        An invitation will be generated after the
                        personnel account is successfully registered.
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
                        href="{{ route('personnel.index') }}"
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

                        Create Account & Send Invitation

                    </button>

                </div>

            </div>

        </section>


    </form>


</div>

</x-admin-layout>