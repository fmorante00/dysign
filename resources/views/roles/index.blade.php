<x-admin-layout>

@php

    /*
    |--------------------------------------------------------------------------
    | ROLE SUMMARY
    |--------------------------------------------------------------------------
    */

    $totalRoles = $roles->count();

    $activeRoles = $roles->filter(function ($role) {

        return strtolower($role->status ?? '') === 'active';

    })->count();


    $totalAssignedUsers = $roles->sum(function ($role) {

        return $role->users_count ?? 0;

    });

@endphp


<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .roles-access-hero {

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

    .roles-access-section {

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .025);

    }


    /*
    |--------------------------------------------------------------------------
    | SCROLLBAR
    |--------------------------------------------------------------------------
    */

    .roles-scrollbar::-webkit-scrollbar {

        width: 6px;
        height: 6px;

    }


    .roles-scrollbar::-webkit-scrollbar-track {

        background: #f3f4f6;

    }


    .roles-scrollbar::-webkit-scrollbar-thumb {

        background: #d1d5db;
        border-radius: 9999px;

    }


    .roles-scrollbar::-webkit-scrollbar-thumb:hover {

        background: #9ca3af;

    }

</style>



<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            roles-access-hero
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
                    Access Administration
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
                    Roles & Access
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
                    Manage system roles and control access to
                    DySign modules and administrative functions.
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
                                d="M12 15l-3.5 2 1-4-3-2.5 4-.3L12 6.5l1.5 3.7 4 .3-3 2.5 1 4z"
                            />
                        </svg>

                        <span>
                            {{ $totalRoles }}
                            system roles
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
                            {{ $activeRoles }}
                            active roles
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
                            {{ $totalAssignedUsers }}
                            assigned users
                        </span>

                    </div>


                </div>

            </div>



            {{-- HERO INDICATOR --}}

            <div class="hidden shrink-0 lg:block">

                <div
                    class="
                        flex
                        h-20
                        w-20
                        items-center
                        justify-center
                        rounded-full
                        border
                        border-white/20
                        bg-white/10
                        backdrop-blur-sm
                    "
                >

                    <svg
                        class="
                            h-9
                            w-9
                            text-[#E7C75B]
                        "
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="1.6"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4zm-3 9l2 2 4-4"
                        />
                    </svg>

                </div>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- OVERVIEW --}}
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
                    Access Control Summary
                </h2>


                <p
                    class="
                        text-sm
                        text-gray-400
                    "
                >
                    Current role and access assignments
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


            {{-- TOTAL ROLES --}}

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
                            Total Roles
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-[#101064]
                            "
                        >
                            {{ $totalRoles }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Configured system roles
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
                                d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- ACTIVE ROLES --}}

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
                            Active Roles
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-green-600
                            "
                        >
                            {{ $activeRoles }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Available for assignment
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



            {{-- ASSIGNED USERS --}}

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
                            Assigned Users
                        </p>


                        <p
                            class="
                                mt-3
                                text-4xl
                                font-bold
                                text-[#D4A017]
                            "
                        >
                            {{ $totalAssignedUsers }}
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Accounts using these roles
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
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm8 10v-2a4 4 0 00-3-3.87"
                            />
                        </svg>

                    </div>

                </div>

            </div>


        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- SYSTEM ROLES --}}
    {{-- ====================================================== --}}

    <section class="min-w-0">


        <div
            class="
                mb-4
                flex
                min-w-0
                flex-col
                gap-3
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
                    Role Registry
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    System Roles
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    Review roles and manage their assigned permissions.
                </p>

            </div>



            <div
                class="
                    flex
                    items-center
                    gap-2
                    text-xs
                    text-gray-400
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

                Active roles are available for personnel assignment

            </div>

        </div>



        <div
            class="
                roles-access-section
                min-w-0
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >


            {{-- TABLE --}}

            <div
                class="
                    roles-scrollbar
                    w-full
                    max-w-full
                    overflow-x-auto
                "
            >

                <table
                    class="
                        w-full
                        min-w-[950px]
                        table-auto
                        text-left
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
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Description
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Users
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
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


                        @forelse($roles as $role)


                            <tr
                                class="
                                    transition
                                    hover:bg-gray-50
                                "
                            >


                                {{-- ROLE --}}

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
                                                    d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z"
                                                />
                                            </svg>

                                        </div>



                                        <div class="min-w-0">

                                            <p
                                                class="
                                                    break-words
                                                    font-bold
                                                    text-[#101064]
                                                "
                                            >
                                                {{ $role->role_name }}
                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-gray-400
                                                "
                                            >
                                                Role ID:
                                                {{ $role->role_id }}
                                            </p>

                                        </div>


                                    </div>

                                </td>



                                {{-- DESCRIPTION --}}

                                <td
                                    class="
                                        max-w-md
                                        px-6
                                        py-5
                                        text-sm
                                        leading-6
                                        text-gray-600
                                    "
                                >

                                    {{ $role->description
                                        ?: 'No description provided.'
                                    }}

                                </td>



                                {{-- USERS --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                    "
                                >

                                    <div
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                        "
                                    >

                                        <span
                                            class="
                                                flex
                                                h-8
                                                min-w-8
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-[#F0F1F8]
                                                px-2
                                                text-xs
                                                font-bold
                                                text-[#101064]
                                            "
                                        >
                                            {{ $role->users_count }}
                                        </span>


                                        <span
                                            class="
                                                text-xs
                                                text-gray-400
                                            "
                                        >
                                            {{ $role->users_count == 1
                                                ? 'user'
                                                : 'users'
                                            }}
                                        </span>

                                    </div>

                                </td>



                                {{-- STATUS --}}

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-5
                                    "
                                >


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
                                        href="{{ route(
                                            'roles.permissions',
                                            $role->role_id
                                        ) }}"
                                        class="
                                            inline-flex
                                            items-center
                                            justify-center
                                            gap-2
                                            rounded-lg
                                            border
                                            border-[#101064]
                                            bg-white
                                            px-4
                                            py-2.5
                                            text-xs
                                            font-semibold
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
                                                d="M12 15l-3.5 2 1-4-3-2.5 4-.3L12 6.5l1.5 3.7 4 .3-3 2.5 1 4z"
                                            />
                                        </svg>

                                        Manage Permissions

                                    </a>

                                </td>


                            </tr>


                        @empty


                            <tr>

                                <td
                                    colspan="5"
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
                                        No system roles available
                                    </h3>


                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            text-gray-400
                                        "
                                    >
                                        No role records are currently configured.
                                    </p>

                                </td>

                            </tr>


                        @endforelse


                    </tbody>


                </table>

            </div>


            {{-- TABLE FOOTER --}}

            <div
                class="
                    flex
                    flex-col
                    gap-2
                    border-t
                    border-gray-100
                    bg-gray-50/60
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
                        text-gray-400
                    "
                >
                    Permissions determine which DySign functions
                    each role is authorized to access.
                </p>


                <p
                    class="
                        shrink-0
                        text-xs
                        font-semibold
                        text-[#101064]
                    "
                >
                    {{ $totalRoles }}
                    {{ $totalRoles == 1 ? 'role' : 'roles' }}
                </p>

            </div>


        </div>

    </section>


</div>

</x-admin-layout>