<x-admin-layout>

@php

    /*
    |--------------------------------------------------------------------------
    | PERMISSION SUMMARY
    |--------------------------------------------------------------------------
    */

    $totalPermissions = $permissions->count();

    $assignedPermissions = $role->permissions->count();

    $remainingPermissions = max(
        0,
        $totalPermissions - $assignedPermissions
    );

@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .permissions-hero {

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

    .permissions-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }


    /*
    |--------------------------------------------------------------------------
    | CHECKBOX
    |--------------------------------------------------------------------------
    */

    .permission-checkbox,
    .permission-select-all {

        accent-color: #101064;

    }

</style>



<div
    class="min-w-0 space-y-8"

    x-data="{

        selectAll: false,

        syncSelectAll() {

            const checkboxes = [
                ...document.querySelectorAll(
                    '.permission-checkbox'
                )
            ];

            if (checkboxes.length === 0) {

                this.selectAll = false;
                return;

            }

            this.selectAll =
                checkboxes.every(
                    checkbox => checkbox.checked
                );

        },


        toggleAll() {

            document.querySelectorAll(
                '.permission-checkbox'
            ).forEach(checkbox => {

                checkbox.checked =
                    this.selectAll;

            });

        }

    }"

    x-init="
        $nextTick(() => {
            syncSelectAll();
        })
    "
>


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            permissions-hero
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
                    Access Configuration
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
                    Manage Permissions
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
                    Configure the system access privileges assigned
                    to the
                    <span class="font-semibold text-white">
                        {{ $role->role_name }}
                    </span>
                    role.
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

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-[#E7C75B]
                            "
                        ></span>

                        {{ $totalPermissions }}
                        available permissions

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

                        {{ $assignedPermissions }}
                        currently assigned

                    </div>

                </div>

            </div>



            {{-- BACK BUTTON --}}

            <div class="shrink-0">

                <a
                    href="{{ route('roles.index') }}"
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

                    Back to Roles

                </a>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <section
            class="
                permissions-section
                overflow-hidden
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
                        Permissions Updated
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

        </section>

    @endif



    {{-- ====================================================== --}}
    {{-- ROLE INFORMATION --}}
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
                Role Information
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                {{ $role->role_name }}
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Review the selected role before modifying
                its access privileges.
            </p>

        </div>



        <div
            class="
                permissions-section
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


            {{-- ROLE NAME --}}

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
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Role Name
                </p>


                <p
                    class="
                        mt-2
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ $role->role_name }}
                </p>

            </div>



            {{-- ROLE DESCRIPTION --}}

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
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Description
                </p>


                <p
                    class="
                        mt-2
                        break-words
                        text-sm
                        leading-6
                        text-gray-600
                    "
                >
                    {{ $role->description ?: 'No description provided.' }}
                </p>

            </div>



            {{-- ROLE STATUS --}}

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
                        font-bold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Status
                </p>


                <div class="mt-2">

                    @if(
                        strtolower($role->status ?? '')
                        === 'active'
                    )

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

                            {{ $role->status }}

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

                            {{ $role->status ?? 'Inactive' }}

                        </span>

                    @endif

                </div>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ACCESS SUMMARY --}}
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
                Access Summary
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Permission Assignment
            </h2>

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


            {{-- AVAILABLE --}}

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
                        text-xs
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Available Permissions
                </p>


                <p
                    class="
                        mt-3
                        text-4xl
                        font-bold
                        text-[#101064]
                    "
                >
                    {{ $totalPermissions }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    Total configurable privileges
                </p>

            </div>



            {{-- ASSIGNED --}}

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
                        text-xs
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Assigned Permissions
                </p>


                <p
                    class="
                        mt-3
                        text-4xl
                        font-bold
                        text-green-600
                    "
                >
                    {{ $assignedPermissions }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    Currently enabled for this role
                </p>

            </div>



            {{-- NOT ASSIGNED --}}

            <div
                class="
                    min-w-0
                    px-6
                    py-6
                "
            >

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-wider
                        text-gray-400
                    "
                >
                    Not Assigned
                </p>


                <p
                    class="
                        mt-3
                        text-4xl
                        font-bold
                        text-[#D4A017]
                    "
                >
                    {{ $remainingPermissions }}
                </p>


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    Permissions currently disabled
                </p>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- PERMISSIONS FORM --}}
    {{-- ====================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'roles.permissions.update',
            $role->role_id
        ) }}"
        class="min-w-0"
    >

        @csrf
        @method('PUT')



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
                        Access Privileges
                    </p>


                    <h2
                        class="
                            mt-2
                            text-xl
                            font-bold
                            text-[#101064]
                        "
                    >
                        Available Permissions
                    </h2>


                    <p
                        class="
                            mt-1
                            text-sm
                            text-gray-400
                        "
                    >
                        Select the system functions this role
                        is authorized to access.
                    </p>

                </div>


                {{-- SELECT ALL --}}

                <label
                    class="
                        inline-flex
                        shrink-0
                        cursor-pointer
                        items-center
                        gap-3
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        px-4
                        py-2.5
                        text-sm
                        font-semibold
                        text-gray-600
                        transition
                        hover:border-[#D4A017]
                        hover:bg-[#FFFDF7]
                    "
                >

                    <input
                        type="checkbox"
                        x-model="selectAll"
                        @change="toggleAll()"
                        class="
                            permission-select-all
                            h-4
                            w-4
                            rounded
                            border-gray-300
                            text-[#101064]
                            focus:ring-[#101064]
                        "
                    >

                    Select All Permissions

                </label>

            </div>



            <div
                class="
                    permissions-section
                    min-w-0
                    overflow-hidden
                    border
                    border-gray-200
                    bg-white
                "
            >


                @forelse($permissions as $permission)

                    <label
                        class="
                            flex
                            cursor-pointer
                            items-start
                            gap-4
                            border-b
                            border-gray-100
                            px-6
                            py-5
                            transition
                            last:border-b-0
                            hover:bg-gray-50
                        "
                    >


                        <div class="pt-0.5">

                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->permission_id }}"

                                class="
                                    permission-checkbox
                                    h-5
                                    w-5
                                    rounded
                                    border-gray-300
                                    text-[#101064]
                                    focus:ring-[#101064]
                                "

                                @change="syncSelectAll()"

                                @if(
                                    $role->permissions->contains(
                                        'permission_id',
                                        $permission->permission_id
                                    )
                                )
                                    checked
                                @endif
                            >

                        </div>



                        <div
                            class="
                                min-w-0
                                flex-1
                            "
                        >

                            <div
                                class="
                                    flex
                                    min-w-0
                                    flex-col
                                    gap-2
                                    sm:flex-row
                                    sm:items-center
                                    sm:justify-between
                                "
                            >

                                <p
                                    class="
                                        break-words
                                        font-semibold
                                        text-[#101064]
                                    "
                                >
                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $permission->permission_name
                                        )
                                    ) }}
                                </p>


                                <span
                                    class="
                                        shrink-0
                                        text-[10px]
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-gray-300
                                    "
                                >
                                    Permission
                                    #{{ $permission->permission_id }}
                                </span>

                            </div>



                            <p
                                class="
                                    mt-1
                                    max-w-4xl
                                    text-sm
                                    leading-6
                                    text-gray-500
                                "
                            >
                                {{ $permission->description
                                    ?: 'No description provided for this permission.'
                                }}
                            </p>

                        </div>


                    </label>

                @empty

                    <div
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
                            No permissions available
                        </h3>


                        <p
                            class="
                                mt-2
                                text-sm
                                text-gray-400
                            "
                        >
                            There are currently no system permissions
                            available for assignment.
                        </p>

                    </div>

                @endforelse


            </div>

        </section>



        {{-- ====================================================== --}}
        {{-- SAVE ACTIONS --}}
        {{-- ====================================================== --}}

        <section
            class="
                permissions-section
                mt-8
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
                        Save Access Configuration
                    </p>


                    <p
                        class="
                            mt-1
                            text-xs
                            leading-5
                            text-gray-400
                        "
                    >
                        Changes will apply to users assigned
                        to the {{ $role->role_name }} role.
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
                        href="{{ route('roles.index') }}"
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
                            text-sm
                            font-semibold
                            text-gray-600
                            transition
                            hover:bg-gray-50
                        "
                    >
                        Back to Roles
                    </a>



                    <a
                        href="{{ route(
                            'roles.permissions',
                            $role->role_id
                        ) }}"
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
                            text-sm
                            font-semibold
                            text-[#8A6D00]
                            transition
                            hover:bg-[#FFF9E7]
                        "
                    >
                        Reset Changes
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
                            py-2.5
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

                        Save Permissions

                    </button>


                </div>

            </div>

        </section>


    </form>


</div>

</x-admin-layout>