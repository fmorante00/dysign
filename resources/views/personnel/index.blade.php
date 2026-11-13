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
            && $person->user->status === 'Active'
            && $person->user->must_change_password == true;

    })->count();

    // Inactive accounts are tracked separately from pending account setup.
    $inactiveAccounts = $personnel->filter(function ($person) {
        return $person->user && $person->user->status === 'Inactive';
    })->count();

    $staffRoles = $personnel->pluck('user.role.role_name')
        ->filter()->unique()->sort()->values();

    // Consistent with the three mutually exclusive account-status filters.
    $readinessPercentage = $totalPersonnel > 0
        ? (int) round(($activeAccounts / $totalPersonnel) * 100)
        : 0;
    $staffDepartmentCount = $personnel->pluck('department')
        ->filter()->unique()->count();
    $staffRoleCount = $staffRoles->count();

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

@include('partials.personnel-university-styles')


<div
    class="dy-staff-page min-w-0 space-y-7 pb-8"

    @keydown.escape.window="openMenu = null; showModal = false"
    @scroll.window="openMenu = null"
    @resize.window="openMenu = null"
    x-data="{

        openMenu: null,

        // Position the account menu in the viewport, outside the scrollable table.
        menuTop: 0,
        menuLeft: 0,

        showModal: false,

        formId: '',

        action: '',

        personnelName: '',

        search: '',

        roleFilter: 'all',

        statusFilter: 'all',

        submitting: false,

        matchesStaff(row) {
            const query = this.search.trim().toLowerCase();
            return row.dataset.search.includes(query)
                && (this.roleFilter === 'all' || row.dataset.role === this.roleFilter)
                && (this.statusFilter === 'all' || row.dataset.status === this.statusFilter);
        },

        get visibleCount() {
            return Array.from(document.querySelectorAll('[data-staff-row]'))
                .filter(row => this.matchesStaff(row)).length;
        },

        clearFilters() {
            this.search = '';
            this.roleFilter = 'all';
            this.statusFilter = 'all';
            this.openMenu = null;
        },

        openDropdown(id) {
            this.openMenu = this.openMenu === id ? null : id;
        },

        positionDropdown(id, trigger) {
            if (this.openMenu !== id || !trigger) return;

            const menu = document.getElementById('staff-actions-menu-' + id);
            if (!menu) return;

            const rect = trigger.getBoundingClientRect();
            const width = menu.offsetWidth || 208;
            const height = menu.offsetHeight || 180;
            const margin = 12;
            const gap = 8;

            this.menuLeft = Math.max(
                margin,
                Math.min(rect.right - width, window.innerWidth - width - margin)
            );

            this.menuTop = window.innerHeight - rect.bottom >= height + gap + margin
                ? rect.bottom + gap
                : Math.max(margin, rect.top - height - gap);
        },


        openConfirm(form, action, name) {

            this.formId = form;

            this.action = action;

            this.personnelName = name;

            this.showModal = true;

            this.openMenu = null;

        },


        submitForm() {

            if (this.submitting) return;

            const form =
                document.getElementById(
                    this.formId
                );

            if (form) {

                this.submitting = true;
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
                    Staff Account Management
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
                    Manage staff account creation, assigned roles, account setup,
                    and access status within DySign.
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
                            staff account records
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

                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                        <span>{{ $inactiveAccounts }} inactive</span>
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

                    Add Staff Account

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
    {{-- ERROR MESSAGE --}}
    {{-- ====================================================== --}}

    @if(session('error'))
        <div class="personnel-section flex items-start gap-4 border border-red-200 bg-white px-6 py-5" role="alert">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-50 font-bold text-red-600">!</span>
            <div class="min-w-0">
                <p class="font-bold text-[#101064]">Action Needs Attention</p>
                <p class="mt-1 break-words text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- ====================================================== --}}
    {{-- INSTITUTIONAL ACCESS OVERVIEW --}}
    {{-- ====================================================== --}}

    <section class="min-w-0" aria-labelledby="staff-overview-heading">
        <div class="mb-4 flex min-w-0 flex-col justify-between gap-3 sm:flex-row sm:items-end">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[.24em] text-[#B68A0D]">Account Overview</p>
                <h2 id="staff-overview-heading" class="mt-2 text-xl font-bold tracking-tight text-[#101064]">Institutional Access Snapshot</h2>
                <p class="mt-1 text-sm text-slate-500">Live account readiness based on registered staff records.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-600">
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5">
                    <svg class="h-3.5 w-3.5 text-[#101064]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10l9-7 9 7v10H3V10Zm5 10v-8h8v8"/></svg>
                    {{ $staffDepartmentCount }} {{ $staffDepartmentCount === 1 ? 'department' : 'departments' }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5">
                    <svg class="h-3.5 w-3.5 text-[#101064]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2 3 7l9 5 9-5-9-5Zm-9 10 9 5 9-5M3 17l9 5 9-5"/></svg>
                    {{ $staffRoleCount }} {{ $staffRoleCount === 1 ? 'role' : 'roles' }}
                </span>
            </div>
        </div>

        <div class="grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="dy-staff-stat" style="--dy-stat-color:#101064;--dy-stat-soft:#f0f1ff;">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Accounts</p>
                        <p class="mt-3 text-4xl font-bold tracking-tight text-[#101064]">{{ number_format($totalPersonnel) }}</p>
                    </div>
                    <span class="dy-staff-stat-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16 20v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m3-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm14 10v-2a4 4 0 0 0-3-3.87M16 2.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                </div>
                <p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Registered staff records</p>
            </article>

            <article class="dy-staff-stat" style="--dy-stat-color:#059669;--dy-stat-soft:#ecfdf5;">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Active & Ready</p>
                        <p class="mt-3 text-4xl font-bold tracking-tight text-emerald-700">{{ number_format($activeAccounts) }}</p>
                    </div>
                    <span class="dy-staff-stat-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/><circle cx="12" cy="12" r="10"/></svg>
                    </span>
                </div>
                <p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Account setup completed</p>
            </article>

            <article class="dy-staff-stat" style="--dy-stat-color:#B68A0D;--dy-stat-soft:#fff8e4;">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Pending Setup</p>
                        <p class="mt-3 text-4xl font-bold tracking-tight text-[#B68A0D]">{{ number_format($pendingSetup) }}</p>
                    </div>
                    <span class="dy-staff-stat-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                    </span>
                </div>
                <p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Awaiting password setup</p>
            </article>

            <article class="dy-staff-stat" style="--dy-stat-color:#64748b;--dy-stat-soft:#f1f5f9;">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Inactive Accounts</p>
                        <p class="mt-3 text-4xl font-bold tracking-tight text-slate-700">{{ number_format($inactiveAccounts) }}</p>
                    </div>
                    <span class="dy-staff-stat-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8"/></svg>
                    </span>
                </div>
                <p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Access currently disabled</p>
            </article>
        </div>

        <div class="dy-staff-subcard mt-4 grid min-w-0 gap-5 px-5 py-5 md:grid-cols-[minmax(0,1fr)_auto] md:items-center md:px-7">
            <div class="min-w-0">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-bold text-[#101064]">Account Readiness</h3>
                        <p class="mt-0.5 text-xs text-slate-500">Staff accounts with active access and completed setup.</p>
                    </div>
                    <p class="text-sm font-bold tabular-nums text-[#101064]">{{ $readinessPercentage }}%</p>
                </div>
                <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-label="Staff account readiness" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $readinessPercentage }}">
                    <div class="h-full rounded-full bg-gradient-to-r from-[#101064] to-[#4849a2]" style="width: {{ $readinessPercentage }}%"></div>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 text-[11px] font-semibold md:justify-end">
                <button type="button" @click="statusFilter='active';openMenu=null; $nextTick(() => document.getElementById('staff-directory')?.scrollIntoView({behavior:'smooth',block:'start'}))" class="dy-staff-action-link inline-flex items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-3 py-2 text-emerald-700 hover:bg-emerald-100"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> {{ $activeAccounts }} Ready</button>
                <button type="button" @click="statusFilter='pending';openMenu=null; $nextTick(() => document.getElementById('staff-directory')?.scrollIntoView({behavior:'smooth',block:'start'}))" class="dy-staff-action-link inline-flex items-center gap-2 rounded-full border border-amber-100 bg-amber-50 px-3 py-2 text-amber-700 hover:bg-amber-100"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> {{ $pendingSetup }} Pending</button>
                <button type="button" @click="statusFilter='inactive';openMenu=null; $nextTick(() => document.getElementById('staff-directory')?.scrollIntoView({behavior:'smooth',block:'start'}))" class="dy-staff-action-link inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700 hover:bg-slate-100"><span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> {{ $inactiveAccounts }} Inactive</button>
            </div>
        </div>
    </section>


    {{-- ====================================================== --}}
    {{-- PERSONNEL DIRECTORY --}}
    {{-- ====================================================== --}}

    <section id="staff-directory" class="min-w-0 scroll-mt-7" aria-labelledby="staff-directory-heading">


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


                <h2 id="staff-directory-heading"
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Staff Account Directory
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-400
                    "
                >
                    View, filter, and manage authorized DySign staff accounts.
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
                    dy-staff-action-link
                "
            >
                Add Staff Account
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
                            Search Staff Accounts
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
                                type="search"
                                aria-label="Search staff accounts"
                                x-model="search"
                                placeholder="Search name, username, email, department, or role..."
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


                    {{-- ROLE FILTER --}}
                    <div class="w-full md:w-52">
                        <label for="staff-role-filter" class="mb-2 block text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                            Filter by Role
                        </label>
                        <select id="staff-role-filter" x-model="roleFilter" @change="openMenu = null"
                                class="w-full rounded-lg border-gray-200 py-2.5 text-sm text-gray-700 focus:border-[#101064] focus:ring-[#101064]">
                            <option value="all">All Roles</option>
                            @foreach($staffRoles as $roleName)
                                <option value="{{ $roleName }}">{{ $roleName }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- STATUS FILTER --}}
                    <div class="w-full md:w-44">
                        <label for="staff-status-filter" class="mb-2 block text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                            Filter by Status
                        </label>
                        <select id="staff-status-filter" x-model="statusFilter" @change="openMenu = null"
                                class="w-full rounded-lg border-gray-200 py-2.5 text-sm text-gray-700 focus:border-[#101064] focus:ring-[#101064]">
                            <option value="all">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending Setup</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="flex shrink-0 items-center gap-3 pb-2">
                        <p class="text-xs text-gray-500" aria-live="polite">
                            <span x-text="visibleCount">{{ $totalPersonnel }}</span> of {{ $totalPersonnel }} shown
                        </p>
                        <button type="button" @click="clearFilters()"
                                x-show="search || roleFilter !== 'all' || statusFilter !== 'all'"
                                class="text-xs font-semibold text-[#101064] underline underline-offset-2 hover:text-[#B68A0D]">
                            Clear
                        </button>
                    </div>

                </div>

            </div>



            {{-- Quick account-status filters share the existing Alpine state. --}}
            <div class="flex min-w-0 flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-3.5 sm:px-6">
                <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Quick staff account filters">
                    <span class="mr-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Quick view</span>
                    <button type="button" @click="statusFilter='all';openMenu=null" :aria-pressed="statusFilter==='all'" class="dy-staff-filter-pill rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">All</button>
                    <button type="button" @click="statusFilter='active';openMenu=null" :aria-pressed="statusFilter==='active'" class="dy-staff-filter-pill rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">Active</button>
                    <button type="button" @click="statusFilter='pending';openMenu=null" :aria-pressed="statusFilter==='pending'" class="dy-staff-filter-pill rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">Pending</button>
                    <button type="button" @click="statusFilter='inactive';openMenu=null" :aria-pressed="statusFilter==='inactive'" class="dy-staff-filter-pill rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">Inactive</button>
                </div>
                <p class="text-xs text-slate-400">Choose an account to review details or manage access.</p>
            </div>

            {{-- TABLE --}}

            <div
                class="
                    personnel-scrollbar
                    w-full
                    max-w-full
                    overflow-x-auto
                "
                @scroll.passive="openMenu = null"
            >

                <table
                    class="
                        w-full
                        min-w-[980px]
                        table-auto
                    "
                >


                    <thead
                        class="
                            border-b
                            border-gray-200
                            bg-[#f7f8fc]
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
                                Staff Member
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
                                    ($person->user->email ?? '') . ' ' .
                                    ($person->user->username ?? '') . ' ' .
                                    ($person->position ?? '') . ' ' .
                                    ($person->user->role->role_name ?? '')
                                );

                                $accountStatus = $person->user->status === 'Inactive'
                                    ? 'inactive'
                                    : ($person->user->must_change_password ? 'pending' : 'active');

                            @endphp


                            <tr
                                data-staff-row
                                data-search="{{ $searchText }}"
                                data-role="{{ $person->user->role->role_name ?? '' }}"
                                data-status="{{ $accountStatus }}"
                                x-show="matchesStaff($el)"

                                class="
                                    transition
                                    hover:bg-[#f7f9ff]
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
                                            <p class="mt-1 max-w-[240px] truncate text-xs text-gray-400">
                                                Username: {{ $person->user->username }}
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
                                            aria-label="Actions for {{ $person->first_name }} {{ $person->last_name }}"
                                            :aria-expanded="openMenu === {{ $person->personnel_id }}"
                                            aria-haspopup="true"
                                            @click.stop="
                                                openDropdown({{ $person->personnel_id }});
                                                $nextTick(() => positionDropdown({{ $person->personnel_id }}, $el));
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



                                        {{-- Move the menu outside the scrollable table so it cannot be clipped. --}}
                                        <template x-teleport="body">
                                        <div
                                            id="staff-actions-menu-{{ $person->personnel_id }}"
                                            x-show="
                                                openMenu ===
                                                {{ $person->personnel_id }}
                                            "
                                            @click.outside="
                                                openMenu = null
                                            "
                                            x-transition.opacity.duration.100ms
                                            :style="{ top: menuTop + 'px', left: menuLeft + 'px' }"
                                            style="display:none;"

                                            class="
                                                fixed
                                                z-[999]
                                                w-52
                                                overflow-hidden
                                                rounded-xl
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
                                                Edit Staff Account
                                            </a>



                                            @if(
                                                $person->user->status === 'Active'
                                                && $person->user->must_change_password
                                            )

                                                <form
                                                    method="POST"

                                                    action="{{
                                                        route(
                                                            'personnel.resendInvitation',
                                                            $person->personnel_id
                                                        )
                                                    }}"
                                                 data-confirm data-confirm-title="Resend invitation?" data-confirm-message="Send a new account invitation to this personnel member." data-confirm-confirm-text="Resend" data-confirm-variant="default">

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
                                        </template>


                                    </div>


                                </td>


                            </tr>


                        @endforeach

                        <tr x-show="visibleCount === 0" style="display:none;">
                            <td colspan="5" class="px-6 py-14 text-center">
                                <p class="font-semibold text-[#101064]" x-text="{{ $totalPersonnel }} === 0 ? 'No staff accounts yet' : 'No matching staff accounts'"></p>
                                <p class="mt-2 text-sm text-gray-500">Try adjusting your search or filters.</p>
                                <button type="button" @click="clearFilters()" x-show="{{ $totalPersonnel }} > 0"
                                        class="mt-4 rounded-lg border border-[#101064] px-4 py-2 text-sm font-semibold text-[#101064] hover:bg-gray-50">
                                    Clear Filters
                                </button>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-[#fbfcff] px-6 py-4">
                <p class="text-xs text-slate-500" aria-live="polite"><span class="font-bold text-[#101064]" x-text="visibleCount">{{ $totalPersonnel }}</span> of <span class="font-bold">{{ $totalPersonnel }}</span> staff records displayed</p>
                <p class="text-[11px] text-slate-400">Account changes require confirmation and authorized access.</p>
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
                    :disabled="submitting"
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