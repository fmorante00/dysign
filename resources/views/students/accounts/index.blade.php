<x-admin-layout>

<style>
    /* Self-contained DySign university theme: aligned with Student Dashboard/Student Data. */
    .dy-provision-page { --dy-navy:#101064; --dy-gold:#D4A017; --dy-line:#e2e8f0; }
    .dy-provision-hero {
        isolation: isolate;
        background: linear-gradient(100deg,rgba(16,16,100,.97) 0%,rgba(16,16,100,.92) 52%,rgba(16,16,100,.74) 100%),
                    url('{{ asset('images/school.jpg') }}') center/cover no-repeat;
        box-shadow: 0 15px 30px -24px rgba(16,16,100,.7);
    }
    .dy-provision-hero .dy-hero-circle { position:absolute; z-index:0; pointer-events:none; border:1px solid rgba(255,255,255,.17); border-radius:9999px; }
    .dy-provision-hero .dy-hero-circle--outer { width:330px; height:330px; top:-116px; right:-66px; }
    .dy-provision-hero .dy-hero-circle--inner { width:248px; height:248px; top:-66px; right:-16px; }
    .dy-provision-hero .dy-hero-glow { position:absolute; z-index:0; pointer-events:none; bottom:0; right:0; height:115px; width:min(360px,65%); background:linear-gradient(270deg,rgba(212,160,23,.18),transparent); }
    .dy-provision-card { min-width:0; border:1px solid var(--dy-line); border-radius:20px; background:#fff; box-shadow:0 8px 24px -22px rgba(16,16,100,.44); }
    .dy-provision-card-icon { display:flex; align-items:center; justify-content:center; height:42px; width:42px; flex-shrink:0; border-radius:14px; }
    .dy-provision-stat { display:flex; flex-direction:column; justify-content:space-between; min-height:148px; padding:21px; }
    .dy-provision-stat-label { font-size:10px; font-weight:800; letter-spacing:.09em; text-transform:uppercase; color:#71809b; }
    .dy-provision-stat-description { padding-top:10px; border-top:1px solid #f0f2f7; font-size:11px; color:#64748b; }
    .dy-provision-eyebrow { font-size:10px; font-weight:800; letter-spacing:.25em; text-transform:uppercase; color:#b08310; }
    .dy-provision-section-title { margin-top:7px; font-size:19px; font-weight:800; letter-spacing:-.02em; color:#101064; }
    .dy-provision-validation-tile { border:1px solid #e7ebf2; border-radius:14px; background:#fafbfe; padding:16px 18px; }
    .dy-provision-validation-tile dt { font-size:11px; font-weight:700; color:#64748b; }
    .dy-provision-validation-tile dd { margin-top:8px; font-size:26px; font-weight:800; color:#101064; }
    .dy-provision-progress { height:10px; overflow:hidden; border-radius:99px; background:#edf0f7; }
    .dy-provision-progress > span { display:block; height:100%; border-radius:99px; background:linear-gradient(90deg,#101064,#4542ad); }
    .dy-provision-filter { border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; padding:11px 13px; color:#172554; width:100%; font-size:13px; }
    .dy-provision-filter:focus { border-color:#8d92c7; outline:0; box-shadow:0 0 0 3px rgba(16,16,100,.09); background:#fff; }
    .dy-provision-row:hover { background:#f8faff; }
    .dy-provision-page button:disabled { cursor:not-allowed; }
    .dy-provision-pager-button { border:1px solid #dce2ec; border-radius:10px; background:white; color:#101064; font-size:12px; font-weight:700; padding:9px 12px; }
    .dy-provision-pager-button:hover:not(:disabled) { background:#f0f1fa; }
    .dy-provision-pager-button:disabled { opacity:.45; }
    .dy-provision-pager-button[aria-current="page"] { background:#101064; border-color:#101064; color:#fff; }
    .dy-provision-scroll::-webkit-scrollbar {height:7px;}
    .dy-provision-scroll::-webkit-scrollbar-thumb {background:#cbd5e1;border-radius:99px;}
    @media (max-width:640px) {
        .dy-provision-hero .dy-hero-circle--outer {width:250px;height:250px;right:-115px;top:-120px;}
        .dy-provision-hero .dy-hero-circle--inner {width:190px;height:190px;right:-98px;top:-98px;}
    }
    @media (prefers-reduced-motion:reduce) {.dy-provision-page * {scroll-behavior:auto!important;}}
</style>

    <div class="dy-provision-page min-w-0 space-y-7 pb-8">

        {{-- PAGE HEADER --}}
        <section class="dy-provision-hero relative overflow-hidden rounded-[28px] px-7 py-8 text-white sm:px-8 lg:px-10 lg:py-10">
            <div class="dy-hero-circle dy-hero-circle--outer" aria-hidden="true"></div>
            <div class="dy-hero-circle dy-hero-circle--inner" aria-hidden="true"></div>
            <div class="dy-hero-glow" aria-hidden="true"></div>
            <div class="pointer-events-none absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]" aria-hidden="true"></div>

            <div class="relative z-10 flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-3xl min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-[.30em] text-[#E7C75B]">Student Account Management</p>
                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Automated Account Provisioning</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">
                        Assess student account eligibility, validate institutional records, and securely queue student login accounts for provisioning.
                    </p>
                    <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-white/80">
                        <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-[#E7C75B]"></span>{{ number_format($summary['total_students']) }} student records</span>
                        <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>{{ number_format($summary['eligible']) }} eligible for provisioning</span>
                    </div>
                </div>
                <a href="{{ route('students.index') }}"
                   class="inline-flex shrink-0 items-center justify-center gap-2 self-start rounded-xl bg-[#E4AA0A] px-5 py-3 text-xs font-extrabold text-[#101064] shadow-sm transition hover:bg-[#F3C044] lg:self-center">
                    <span aria-hidden="true">←</span> Back to Student Records
                </a>
            </div>
        </section>


        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))

            <div class="rounded-xl border border-green-200 bg-green-50 px-6 py-4">
                <p class="font-semibold text-green-800">
                    Action Completed
                </p>

                <p class="mt-1 text-sm text-green-700">
                    {{ session('success') }}
                </p>
            </div>

        @endif


        {{-- INFORMATION MESSAGE --}}
        @if(session('info'))

            <div class="rounded-xl border border-blue-200 bg-blue-50 px-6 py-4">
                <p class="text-sm font-medium text-blue-800">
                    {{ session('info') }}
                </p>
            </div>

        @endif


        {{-- VALIDATION ERRORS --}}
        @if($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 px-6 py-4">

                <p class="font-semibold text-red-800">
                    Unable to Process Request
                </p>

                @foreach($errors->all() as $error)
                    <p class="mt-1 text-sm text-red-700">
                        {{ $error }}
                    </p>
                @endforeach

            </div>

        @endif


        {{-- ACCOUNT SUMMARY --}}
        @php
            $needsVerification = $summary['missing_email'] + $summary['invalid_email'] + $summary['duplicate_email'] + $summary['email_in_use'];
            $provisionedPercent = $summary['total_students'] > 0
                ? round(($summary['already_provisioned'] / $summary['total_students']) * 100)
                : 0;
        @endphp
        <section aria-labelledby="provision-overview-heading">
            <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="dy-provision-eyebrow">Account Overview</p>
                    <h2 id="provision-overview-heading" class="dy-provision-section-title">Institutional Account Snapshot</h2>
                    <p class="mt-1 text-sm text-slate-500">Live metrics calculated from registered student records.</p>
                </div>
                <span class="rounded-full border border-[#e3e9f4] bg-white px-3 py-1.5 text-[11px] font-semibold text-[#101064]">Student Accounts</span>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="dy-provision-card dy-provision-stat">
                    <div class="flex items-start justify-between gap-3">
                        <div><p class="dy-provision-stat-label">Total Students</p><p class="mt-3 text-3xl font-extrabold text-[#101064]">{{ number_format($summary['total_students']) }}</p></div>
                        <span class="dy-provision-card-icon bg-indigo-50 text-indigo-700" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m14-14a4 4 0 0 1 0 8M22 21v-2a4 4 0 0 0-3-3.87M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>
                        </span>
                    </div>
                    <p class="dy-provision-stat-description">All imported student records</p>
                </div>
                <div class="dy-provision-card dy-provision-stat">
                    <div class="flex items-start justify-between gap-3">
                        <div><p class="dy-provision-stat-label">Eligible to Provision</p><p class="mt-3 text-3xl font-extrabold text-emerald-700">{{ number_format($summary['eligible']) }}</p></div>
                        <span class="dy-provision-card-icon bg-emerald-50 text-emerald-700" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/><circle cx="12" cy="12" r="10"/></svg>
                        </span>
                    </div>
                    <p class="dy-provision-stat-description">Ready for account creation</p>
                </div>
                <div class="dy-provision-card dy-provision-stat">
                    <div class="flex items-start justify-between gap-3">
                        <div><p class="dy-provision-stat-label">Existing Accounts</p><p class="mt-3 text-3xl font-extrabold text-blue-700">{{ number_format($summary['already_provisioned']) }}</p></div>
                        <span class="dy-provision-card-icon bg-blue-50 text-blue-700" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="3"/><path stroke-linecap="round" d="M7 10h5M7 14h8"/></svg>
                        </span>
                    </div>
                    <p class="dy-provision-stat-description">Students with linked accounts</p>
                </div>
                <div class="dy-provision-card dy-provision-stat">
                    <div class="flex items-start justify-between gap-3">
                        <div><p class="dy-provision-stat-label">Needs Verification</p><p class="mt-3 text-3xl font-extrabold text-amber-600">{{ number_format($needsVerification) }}</p></div>
                        <span class="dy-provision-card-icon bg-amber-50 text-amber-700" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.3 3.7 1.9 18.1a2 2 0 0 0 1.7 3h16.8a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z"/><path stroke-linecap="round" d="M12 9v5m0 4h.01"/></svg>
                        </span>
                    </div>
                    <p class="dy-provision-stat-description">Records requiring email checks</p>
                </div>
            </div>
            <div class="dy-provision-card mt-4 px-5 py-5 sm:px-6">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-extrabold text-[#101064]">Account Provisioning Coverage</h3>
                        <p class="mt-1 text-xs text-slate-500">Proportion of registered students with an existing linked account.</p>
                    </div>
                    <p class="text-lg font-extrabold text-[#101064]">{{ $provisionedPercent }}%</p>
                </div>
                <div class="dy-provision-progress mt-4" role="progressbar" aria-label="Account provisioning coverage" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $provisionedPercent }}"><span style="width: {{ $provisionedPercent }}%"></span></div>
                <div class="mt-3 flex flex-wrap gap-2 text-[11px]">
                    <span class="rounded-full bg-blue-50 px-3 py-1.5 font-semibold text-blue-700">{{ number_format($summary['already_provisioned']) }} linked accounts</span>
                    <span class="rounded-full bg-emerald-50 px-3 py-1.5 font-semibold text-emerald-700">{{ number_format($summary['eligible']) }} eligible</span>
                    <span class="rounded-full bg-amber-50 px-3 py-1.5 font-semibold text-amber-700">{{ number_format($needsVerification) }} need verification</span>
                </div>
            </div>
        </section>


        {{-- AUTOMATIC VALIDATION RESULTS --}}
        <section aria-labelledby="provision-validation-heading">
            <div class="mb-4">
                <p class="dy-provision-eyebrow">Automated Verification</p>
                <h2 id="provision-validation-heading" class="dy-provision-section-title">Automatic Validation Results</h2>
                <p class="mt-1 text-sm text-slate-500">Records that need attention before account provisioning.</p>
            </div>
            @php
                $validationItems = [
                    ['Missing Email', 'missing_email'],
                    ['Invalid Email', 'invalid_email'],
                    ['Duplicate Email', 'duplicate_email'],
                    ['Email Already Used', 'email_in_use'],
                    ['Inactive Students', 'inactive'],
                ];
            @endphp
            <div class="dy-provision-card p-5 sm:p-6">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <span class="dy-provision-card-icon bg-indigo-50 text-indigo-700" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20Z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-bold text-[#101064]">Eligibility Checks</h3>
                            <p class="text-xs text-slate-500">Email quality, duplicate checks, and student status</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[11px] font-semibold text-slate-600">Automatic assessment</span>
                </div>
                <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($validationItems as $item)
                        <div class="dy-provision-validation-tile">
                            <dt>{{ $item[0] }}</dt>
                            <dd>{{ number_format($summary[$item[1]]) }}</dd>
                        </div>
                    @endforeach
                </dl>
                <p class="mt-4 text-xs leading-5 text-slate-500">Records with validation issues are excluded from automatic provisioning until their information is corrected.</p>
            </div>
        </section>


        {{-- BULK ACCOUNT PROVISIONING --}}
        <section class="dy-provision-card overflow-hidden" aria-labelledby="provision-bulk-heading">

            <div class="border-b border-[#f2e8cb] bg-[#fffbef] px-6 py-6">

                <p class="text-xs font-bold uppercase tracking-widest text-[#B68A0D]">
                    Business Process Automation
                </p>

                <h2 id="provision-bulk-heading" class="mt-2 text-xl font-bold text-[#101064]">
                    Automated Bulk Account Provisioning
                </h2>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-600">
                    Automatically create student login accounts using
                    validated student records. Existing accounts and
                    records requiring verification will be skipped.
                </p>

            </div>


            <div class="space-y-6 p-6">

                {{-- ELIGIBLE COUNT --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-sm font-semibold text-gray-600">
                            Students Ready for Provisioning
                        </p>

                        <p class="mt-2 text-3xl font-bold text-green-600">
                            {{ number_format($summary['eligible']) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Based on the current student registry
                        </p>

                    </div>

                    <div class="rounded-2xl border border-[#e4e7f4] bg-[#f7f8ff] px-5 py-4">

                        <p class="text-xs font-semibold text-[#101064]">
                            Automated Processing
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Database queue · Batches of 50 students
                        </p>

                    </div>

                </div>


                {{-- PROVISIONING CONFIRMATION FORM --}}
                @if($summary['eligible'] > 0)

                    <form
                        method="POST"
                        action="{{ route('students.accounts.provision') }}"
                        class="space-y-5"
                     data-confirm data-confirm-title="Start account provisioning?" data-confirm-message="Queue account provisioning for eligible students. This may create many account invitations." data-confirm-confirm-text="Queue Accounts" data-confirm-variant="warning">

                        @csrf

                        <div class="rounded-xl border border-[#dbe1ed] bg-[#f8faff] p-5">

                            <label class="flex cursor-pointer items-start gap-3">

                                <input
                                    type="checkbox"
                                    name="confirm_provisioning"
                                    value="1"
                                    required
                                    class="mt-1 rounded border-gray-300 text-[#101064] focus:ring-[#101064]"
                                >

                                <span class="text-sm leading-6 text-gray-600">

                                    I confirm that I want DySign to
                                    automatically create login accounts
                                    for eligible students and queue their
                                    account setup invitations.

                                </span>

                            </label>

                        </div>


                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                            <p class="max-w-xl text-xs leading-5 text-gray-500">
                                The system will revalidate student records
                                before processing. Submitting this request
                                queues the work; it does not guarantee that
                                every account or invitation succeeds.
                            </p>

                            <button
                                type="submit"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#101064] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#D4A017] hover:text-[#101064]"
                            >

                                Bulk Provision Accounts →

                            </button>

                        </div>

                    </form>

                @else

                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                        <p class="font-semibold text-gray-700">
                            No Eligible Students Available
                        </p>

                        <p class="mt-2 text-sm text-gray-500">
                            All student records either have existing accounts
                            or require verification before provisioning.
                        </p>

                        <button
                            type="button"
                            disabled
                            class="mt-4 cursor-not-allowed rounded-xl bg-gray-200 px-6 py-3 text-sm font-semibold text-gray-400"
                        >
                            Bulk Provision Accounts
                        </button>

                    </div>

                @endif

            </div>

        </section>


        {{-- STUDENT ELIGIBILITY TABLE --}}
        <section class="dy-provision-card overflow-hidden" aria-labelledby="provision-eligibility-heading" id="provision-directory">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                <p class="dy-provision-eyebrow">Account Registry</p>
                <div class="mt-1 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 id="provision-eligibility-heading" class="text-lg font-extrabold text-[#101064]">Student Account Eligibility</h2>
                        <p class="mt-1 text-sm text-slate-500">Search, review, and manage student login access.</p>
                    </div>
                    <span class="rounded-full bg-[#f0f1ff] px-3 py-1.5 text-[11px] font-semibold text-[#101064]">{{ number_format($records->count()) }} records evaluated</span>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 border-b border-slate-100 bg-[#fbfcff] px-5 py-5 sm:px-6 md:grid-cols-12" id="provision-directory-controls">
                <label class="block md:col-span-6">
                    <span class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Search Student Accounts</span>
                    <input type="search" id="provision-student-search" class="dy-provision-filter" placeholder="Student number, name, email, or program..." autocomplete="off">
                </label>
                <label class="block md:col-span-4">
                    <span class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Provisioning Status</span>
                    <select id="provision-student-status" class="dy-provision-filter">
                        <option value="">All statuses</option>
                        <option value="eligible">Eligible</option>
                        <option value="already_provisioned">Account Exists</option>
                        <option value="inactive">Inactive</option>
                        <option value="missing_email">Missing Email</option>
                        <option value="invalid_email">Invalid Email</option>
                        <option value="duplicate_email">Duplicate Email</option>
                        <option value="email_in_use">Email Already Used</option>
                    </select>
                </label>
                <label class="block md:col-span-2">
                    <span class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Rows Per Page</span>
                    <select id="provision-student-perpage" class="dy-provision-filter">
                        <option value="10" selected>10 students</option>
                        <option value="25">25 students</option>
                        <option value="50">50 students</option>
                    </select>
                </label>
            </div>
            <div class="dy-provision-scroll overflow-x-auto">

                <table class="w-full min-w-[1200px] text-left" id="provision-students-table">

                    <thead class="bg-gray-50">

                        <tr class="text-xs uppercase tracking-wider text-gray-400">

                            <th class="px-6 py-4">Student Number</th>
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Program</th>
                            <th class="px-6 py-4">Provisioning Status</th>
                            <th class="px-6 py-4">Account Access</th>
                            <th class="px-6 py-4">Actions</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($records as $record)

                            <tr class="dy-provision-row transition hover:bg-gray-50" data-student-row data-provision-status="{{ $record['status'] }}" data-provision-search="{{ strtolower(($record['student_number'] ?? '') . ' ' . ($record['name'] ?? '') . ' ' . ($record['email'] ?? '') . ' ' . ($record['program_code'] ?? '')) }}">

                                <td class="px-6 py-4 text-sm font-semibold text-gray-700">
                                    {{ $record['student_number'] }}
                                </td>

                                <td class="px-6 py-4 text-sm font-semibold text-[#101064]">
                                    {{ $record['name'] }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $record['email'] ?: 'No email' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $record['program_code'] }}
                                </td>

                                <td class="px-6 py-4">

                                    @switch($record['status'])

                                        @case('eligible')
                                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                                Eligible
                                            </span>
                                            @break

                                        @case('already_provisioned')
                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                                Account Exists
                                            </span>
                                            @break

                                        @case('inactive')
                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                Inactive
                                            </span>
                                            @break

                                        @case('missing_email')
                                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                Missing Email
                                            </span>
                                            @break

                                        @case('invalid_email')
                                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                Invalid Email
                                            </span>
                                            @break

                                        @case('duplicate_email')
                                            <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">
                                                Duplicate Email
                                            </span>
                                            @break

                                        @case('email_in_use')
                                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                Email Already Used
                                            </span>
                                            @break

                                    @endswitch

                                </td>

                                {{-- Student login account state (separate from provisioning eligibility) --}}
                                <td class="px-6 py-4">
                                    @if(!$record['account_user_id'])
                                        <span class="text-xs text-gray-400">Not provisioned</span>
                                    @elseif($record['account_deactivated_at'] !== null)
                                        <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">Deactivated</span>
                                    @elseif($record['account_status'] === 'Active')
                                        <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Active</span>
                                    @elseif($record['account_setup_pending'])
                                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Pending Setup</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">Inactive</span>
                                    @endif
                                </td>

                                {{-- Account-management actions: only for linked Student Account users --}}
                                <td class="px-6 py-4">
                                    @if(!$record['account_user_id'] || !$record['account_is_student_role'])
                                        <span class="text-xs text-gray-400">—</span>
                                    @elseif($record['account_deactivated_at'] !== null || ($record['account_status'] === 'Inactive' && !$record['account_setup_pending']))
                                        @if($record['student_record_active'])
                                            <form method="POST"
                                                  action="{{ route('students.accounts.activate', ['id' => $record['account_user_id']]) }}" data-confirm data-confirm-title="Reactivate student account?" data-confirm-message="The student can regain access. Any pending account setup must still be completed." data-confirm-confirm-text="Reactivate" data-confirm-variant="warning">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="rounded-lg border border-green-600 px-4 py-2 text-xs font-bold text-green-700 transition hover:bg-green-50">
                                                    Reactivate
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">Student record inactive</span>
                                        @endif
                                    @else
                                        <form method="POST"
                                              action="{{ route('students.accounts.deactivate', ['id' => $record['account_user_id']]) }}" data-confirm data-confirm-title="{{ $record['account_setup_pending'] ? 'Disable student account?' : 'Deactivate student account?' }}" data-confirm-message="{{ $record['account_setup_pending'] ? 'The student cannot complete invitation setup while disabled.' : 'The student will be blocked from accessing DySign.' }}" data-confirm-confirm-text="{{ $record['account_setup_pending'] ? 'Disable Account' : 'Deactivate' }}" data-confirm-variant="danger">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="rounded-lg border border-red-200 px-4 py-2 text-xs font-bold text-red-700 transition hover:bg-red-50">
                                                {{ $record['account_setup_pending'] ? 'Disable Account' : 'Deactivate' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-6 py-16 text-center">

                                    <p class="font-semibold text-[#101064]">
                                        No student records found
                                    </p>

                                    <p class="mt-2 text-sm text-gray-400">
                                        Import student records to begin
                                        account eligibility checks.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                            <tr id="provision-students-filter-empty" hidden>
                                <td colspan="7" class="px-6 py-14 text-center">
                                    <p class="font-bold text-[#101064]">No matching student accounts</p>
                                    <p class="mt-1 text-xs text-slate-500">Try adjusting your search or status filter.</p>
                                </td>
                            </tr>
                    </tbody>

                </table>

            </div>


            @if($records->isNotEmpty())
                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 bg-[#fbfcff] px-5 py-4 sm:px-6" id="provision-pagination">
                    <p id="provision-page-summary" class="text-xs text-slate-600" aria-live="polite">{{ number_format($records->count()) }} student accounts evaluated</p>
                    <nav class="flex max-w-full flex-wrap items-center justify-end gap-1.5" aria-label="Student provisioning list pages">
                        <button type="button" id="provision-page-prev" class="dy-provision-pager-button">Previous</button>
                        <span id="provision-page-numbers" class="inline-flex flex-wrap items-center gap-1.5"></span>
                        <button type="button" id="provision-page-next" class="dy-provision-pager-button">Next</button>
                    </nav>
                </div>
            @endif

        </section>

    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('provision-students-table');
    const search = document.getElementById('provision-student-search');
    const status = document.getElementById('provision-student-status');
    const perPage = document.getElementById('provision-student-perpage');
    const summary = document.getElementById('provision-page-summary');
    const prev = document.getElementById('provision-page-prev');
    const next = document.getElementById('provision-page-next');
    const numbers = document.getElementById('provision-page-numbers');
    const empty = document.getElementById('provision-students-filter-empty');
    if (!table || !search || !status || !perPage || !summary || !prev || !next || !numbers) return;

    // Pagination only hides table rows; it never modifies form actions or confirmation handlers.
    const rows = Array.from(table.querySelectorAll('tr[data-student-row]'));
    let currentPage = 1;
    const searchIndex = rows.map(row => (row.dataset.provisionSearch || '').toLocaleLowerCase());

    function addNumber(page) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'dy-provision-pager-button';
        button.textContent = String(page);
        button.setAttribute('aria-label', 'Page ' + page);
        if (page === currentPage) button.setAttribute('aria-current', 'page');
        button.addEventListener('click', function () { currentPage = page; render(); });
        numbers.appendChild(button);
    }

    function render() {
        const query = search.value.trim().toLocaleLowerCase();
        const selectedStatus = status.value;
        const limit = Number(perPage.value) || 10;
        const filtered = rows.filter(function (row, index) {
            return (!selectedStatus || row.dataset.provisionStatus === selectedStatus)
                && (!query || searchIndex[index].includes(query));
        });
        const pages = Math.max(1, Math.ceil(filtered.length / limit));
        currentPage = Math.max(1, Math.min(currentPage, pages));
        const start = (currentPage - 1) * limit;
        const visible = new Set(filtered.slice(start, start + limit));
        rows.forEach(row => { row.style.display = visible.has(row) ? '' : 'none'; });
        if (empty) empty.hidden = filtered.length !== 0;
        const first = filtered.length ? start + 1 : 0;
        const last = Math.min(start + limit, filtered.length);
        summary.textContent = 'Showing ' + first + '–' + last + ' of ' + filtered.length
            + ' matching records (' + rows.length + ' total) · Page ' + currentPage + ' of ' + pages;
        prev.disabled = currentPage <= 1;
        next.disabled = currentPage >= pages;
        numbers.replaceChildren();
        const begin = Math.max(1, currentPage - 2);
        const end = Math.min(pages, begin + 4);
        for (let page = Math.max(1, end - 4); page <= end; page++) addNumber(page);
    }
    search.addEventListener('input', function () { currentPage = 1; render(); });
    status.addEventListener('change', function () { currentPage = 1; render(); });
    perPage.addEventListener('change', function () { currentPage = 1; render(); });
    prev.addEventListener('click', function () { if (currentPage > 1) { currentPage--; render(); } });
    next.addEventListener('click', function () { currentPage++; render(); });
    render();
});
</script>

</x-admin-layout>
