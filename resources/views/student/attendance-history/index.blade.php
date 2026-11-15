<x-admin-layout>
    {{-- Student Account / Module 9. Presentation-only redesign: the controller remains the source of truth. --}}
    @php
        $hasRecords = (int) $totalAttendance > 0;
        $filteredCount = $attendanceRecords->total();
        $hasActiveFilters = $selectedStatus !== 'all' || $searchTerm !== '' || $fromDate || $toDate || $selectedSort !== 'newest' || $perPage !== 5;
        $pageNumber = $attendanceRecords->currentPage();
        $lastPage = $attendanceRecords->lastPage();
        $pageWindowStart = max(1, min($pageNumber - 2, $lastPage - 4));
        $pageWindowEnd = min($lastPage, $pageWindowStart + 4);
        $statusStyles = [
            'Checked In' => 'dyh-badge--checked-in',
            'Present' => 'dyh-badge--present',
            'Late' => 'dyh-badge--late',
            'Incomplete' => 'dyh-badge--incomplete',
            'Absent' => 'dyh-badge--absent',
            'Excused' => 'dyh-badge--excused',
        ];
    @endphp

    <style>
        .dy-history-campus-hero {
            background-color: #101064;
            background-image:
                linear-gradient(104deg, rgba(11, 12, 76, .96) 0%, rgba(16, 16, 100, .91) 51%, rgba(16, 16, 100, .70) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        .dy-history-soft-shadow {
            box-shadow: 0 10px 28px rgba(16, 16, 100, .045), 0 1px 2px rgba(16, 16, 100, .035);
        }

        /* Matching the scoped styling used by the DySign Calendar V3 and registrations.
           Explicit CSS avoids the black outlines that appear when Tailwind arbitrary
           border-color utilities are not present in the compiled stylesheet. */
        .dyh-page { --dyh-navy:#101064; --dyh-gold:#D4A017; --dyh-line:#e7eaf2; --dyh-muted:#6f7f9b; min-width:0; }
        .dyh-page *, .dyh-page *::before, .dyh-page *::after { box-sizing:border-box; }
        .dyh-page .dyh-summary-card { border:1px solid var(--dyh-line) !important; border-radius:19px !important; background:#fff; box-shadow:0 7px 26px rgba(16,16,100,.045),0 1px 2px rgba(15,23,42,.035); }
        .dyh-page .dyh-summary-card:hover { box-shadow:0 10px 28px rgba(16,16,100,.08); }
        .dyh-page .dyh-panel { border:1px solid var(--dyh-line) !important; border-radius:23px !important; background:#fff; overflow:hidden; box-shadow:0 7px 26px rgba(16,16,100,.05),0 1px 2px rgba(15,23,42,.035); }
        .dyh-page .dyh-panel-head { position:relative; overflow:hidden; border:0 !important; background:linear-gradient(112deg,#101064 0%,#1b1b72 69%,#332e78 100%); padding:23px 27px; }
        .dyh-page .dyh-panel-head::before,.dyh-page .dyh-panel-head::after { content:''; position:absolute; pointer-events:none; width:210px; height:210px; right:-55px; top:-100px; border:1px solid rgba(255,255,255,.14); border-radius:50%; }
        .dyh-page .dyh-panel-head::after { width:155px; height:155px; right:-20px; top:-73px; }
        .dyh-page .dyh-panel-head-inner { position:relative; z-index:1; }
        .dyh-page .dyh-panel-head h2 { color:#fff !important; font-size:21px; line-height:1.35; letter-spacing:-.02em; }
        .dyh-page .dyh-panel-head p { color:rgba(255,255,255,.72) !important; }
        .dyh-page .dyh-panel-eyebrow { color:#efcf68 !important; }
        .dyh-page .dyh-filterbar { padding:23px 26px 18px; background:#fff; border-bottom:1px solid #eef1f6 !important; }
        .dyh-page .dyh-filter-heading { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:18px; }
        .dyh-page .dyh-filter-grid { display:grid; grid-template-columns:repeat(12,minmax(0,1fr)); gap:13px 14px; align-items:end; }
        .dyh-page .dyh-filter-field { min-width:0; display:flex; flex-direction:column; gap:6px; grid-column:span 3; }
        .dyh-page .dyh-filter-field--search { grid-column:span 6; }
        .dyh-page .dyh-filter-field--date { grid-column:span 3; }
        .dyh-page .dyh-filter-field--sort { grid-column:span 3; }
        .dyh-page .dyh-filter-field--per { grid-column:span 2; }
        .dyh-page .dyh-filter-field label { display:block; font-size:11px; font-weight:750; color:#52637e; }
        .dyh-page .dyh-filter-control { display:block; width:100%; height:44px; border:1px solid #dfe5ef !important; background:#f9fafc; border-radius:11px; color:#101064; padding:0 12px; font-size:12px; line-height:1.4; font-family:inherit; font-weight:600; min-width:0; }
        .dyh-page .dyh-filter-control::placeholder { color:#92a0b5; font-weight:400; }
        .dyh-page .dyh-filter-control:focus { outline:2px solid rgba(212,160,23,.3); outline-offset:1px; border-color:#d4a017 !important; }
        .dyh-page .dyh-filter-actions { display:flex; gap:9px; align-items:end; justify-content:flex-start; grid-column:span 4; }
        .dyh-page .dyh-filter-submit { display:inline-flex; gap:8px; align-items:center; justify-content:center; min-height:44px; padding:0 19px; border-radius:11px; background:#101064; color:#fff; font-size:12px; font-weight:800; border:1px solid #101064; cursor:pointer; }
        .dyh-page .dyh-filter-submit:hover { background:#23237b; }
        .dyh-page .dyh-filter-reset { display:inline-flex; align-items:center; justify-content:center; min-height:44px; padding:0 15px; border-radius:11px; background:#fff; color:#52637e; border:1px solid #dfe5ef; font-size:12px; font-weight:700; text-decoration:none; }
        .dyh-page .dyh-filter-reset:hover { background:#f6f8fc; }
        .dyh-page .dyh-active-chip { display:inline-flex; gap:6px; align-items:center; max-width:100%; border-radius:20px; border:1px solid #e5e9f4; background:#f5f7fc; padding:5px 10px; color:#334672; font-size:11px; font-weight:650; word-break:break-word; }
        .dyh-page .dyh-results-bar { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; padding:14px 26px; background:#fbfcff; border-bottom:1px solid #edf0f6; color:#66758e; font-size:12px; }
        .dyh-page .dyh-pager-footer { border-top:1px solid #edf1f6 !important; display:flex; gap:12px; align-items:center; justify-content:space-between; flex-wrap:wrap; padding:17px 25px; background:#fff; }
        .dyh-page .dyh-pager { display:flex; flex-wrap:wrap; gap:6px; align-items:center; }
        .dyh-page .dyh-page-link { min-width:34px; height:36px; display:inline-flex; align-items:center; justify-content:center; gap:5px; padding:0 11px; border-radius:10px; border:1px solid #e3e7f0; background:#fff; color:#101064; text-decoration:none; font-size:12px; font-weight:750; }
        .dyh-page .dyh-page-link:hover { background:#f0f2fb; }
        .dyh-page .dyh-page-link--current { background:#101064; border-color:#101064; color:#fff; }
        .dyh-page .dyh-page-link--disabled { color:#9aa6b9; background:#f8f9fc; cursor:not-allowed; }
        @media(max-width:1100px) { .dyh-page .dyh-filter-field--search { grid-column:span 7; } .dyh-page .dyh-filter-field { grid-column:span 5; } .dyh-page .dyh-filter-field--date, .dyh-page .dyh-filter-field--sort { grid-column:span 4; } .dyh-page .dyh-filter-field--per { grid-column:span 4; } .dyh-page .dyh-filter-actions { grid-column:span 8; } }
        @media(max-width:620px) { .dyh-page .dyh-filter-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } .dyh-page .dyh-filter-field,.dyh-page .dyh-filter-field--search,.dyh-page .dyh-filter-field--sort { grid-column:span 2; } .dyh-page .dyh-filter-field--date,.dyh-page .dyh-filter-field--per { grid-column:span 1; } .dyh-page .dyh-filter-actions { grid-column:span 2; } .dyh-page .dyh-pager-footer { padding:16px; } .dyh-page .dyh-pager { width:100%; justify-content:center; } }
        .dyh-page .dyh-hero-account { border:1px solid rgba(255,255,255,.2) !important; }
        .dyh-page .dyh-mobile-card { border:1px solid #e6ebf3 !important; border-radius:16px; background:#fff; box-shadow:0 2px 12px rgba(16,16,100,.025); }
        .dyh-page .dyh-mobile-card-details { border-top:1px solid #eef1f6 !important; }
        .dyh-page .dyh-table { border-collapse:separate; border-spacing:0; width:100%; }
        .dyh-page .dyh-table thead { background:#f8f9fc; }
        .dyh-page .dyh-table thead th { border:0 !important; color:#687993; }
        .dyh-page .dyh-table tbody,.dyh-page .dyh-table tbody tr { border:0 !important; }
        .dyh-page .dyh-table tbody td { border:0 !important; }
        .dyh-page .dyh-table tbody tr + tr td { border-top:1px solid #edf1f6 !important; }
        .dyh-page .dyh-table tbody tr:hover { background:#fbfcff; }
        .dyh-page .dyh-badge { display:inline-flex; align-items:center; justify-content:center; max-width:100%; padding:6px 10px; border:1px solid transparent !important; border-radius:8px; font-size:11px; line-height:1.3; font-weight:750; box-shadow:none !important; }
        .dyh-page .dyh-badge--checked-in { background:#eef2ff; border-color:#dbe4ff !important; color:#3346a2; }
        .dyh-page .dyh-badge--incomplete { background:#fff0f0; border-color:#fbd4d4 !important; color:#bd3030; }
        .dyh-page .dyh-badge--absent { background:#f0f2f6; border-color:#e0e5ed !important; color:#556276; }
        .dyh-page .dyh-badge--excused { background:#f4efff; border-color:#e5d9ff !important; color:#7548a1; }
        .dyh-page .dyh-badge--present { background:#eaf8f1; border-color:#cfebda !important; color:#117652; }
        .dyh-page .dyh-badge--late { background:#fff6e7; border-color:#f7e4b8 !important; color:#9a6409; }
        .dyh-page .dyh-badge--other { background:#f0f2f7; border-color:#e3e7ef !important; color:#58687f; }
        .dyh-page .dyh-note { border:1px solid #f2e7ca !important; background:#fffcf4; border-radius:17px; }
        .dyh-page .dyh-pagination { border-top:1px solid #edf1f6 !important; }
        @media(max-width:767px){ .dyh-page .dyh-panel-head { padding:23px 20px; } .dyh-page .dyh-filterbar { padding:18px; } .dyh-page .dyh-results-bar { padding:14px 18px; } .dyh-page .dyh-summary-card { border-radius:16px !important; } }
        @media(prefers-reduced-motion:reduce){ .dyh-page * { transition:none !important; } }
    </style>

    <div class="dyh-page min-w-0 space-y-5 pb-8 sm:space-y-6">
        {{-- Campus photo hero: same DySign language as the Student Dashboard / Registrations. --}}
        <section class="dy-history-campus-hero relative isolate overflow-hidden rounded-[26px] px-5 py-7 text-white shadow-sm sm:rounded-[28px] sm:px-9 sm:py-10">
            <div class="pointer-events-none absolute -right-16 -top-24 h-80 w-80 rounded-full border border-white/10" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -right-3 -top-10 h-56 w-56 rounded-full border border-white/10" aria-hidden="true"></div>
            <div class="pointer-events-none absolute bottom-0 right-0 h-32 w-72 bg-gradient-to-l from-[#D4A017]/15 to-transparent" aria-hidden="true"></div>

            <div class="relative flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div class="min-w-0 max-w-2xl">
                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.21em] text-[#E7C75B] sm:text-xs">
                        <span class="h-0.5 w-6 rounded-full bg-[#D4A017]"></span>
                        DYSIGN · STUDENT PORTAL
                    </div>
                    <h1 class="mt-4 text-[27px] font-extrabold leading-tight tracking-tight sm:text-4xl">My Attendance History</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-white/80">
                        Your official recorded event check-ins, attendance status, and participation history in one place.
                    </p>
                </div>

                <div class="dyh-hero-account inline-flex w-fit max-w-full items-center gap-3 rounded-2xl border border-white/20 bg-white/10 px-4 py-3 backdrop-blur-sm sm:px-5">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#D4A017]/20 text-[#F4D477]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 7h14v14H5zM9 16l2 2 4-4" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-white/65">Attendance account</p>
                        <p class="mt-1 text-sm font-semibold text-white">{{ $student->student_number }}</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Summary cards count records only; they do not mark unscanned required events as absent. --}}
        <section class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4" aria-label="Recorded attendance summary">
            <div class="dyh-summary-card min-w-0 p-4 sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-xs font-semibold leading-5 text-slate-500 sm:text-sm">Recorded Entries</p>
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#F0F1FB] text-[#101064]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 7h14v14H5z" /></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#101064]">{{ number_format($totalAttendance) }}</p>
                <p class="mt-2 text-[11px] leading-4 text-slate-500">Saved check-in records</p>
            </div>
            <div class="dyh-summary-card min-w-0 p-4 sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-xs font-semibold leading-5 text-slate-500 sm:text-sm">Present</p>
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold tracking-tight text-emerald-700">{{ number_format($totalPresent) }}</p>
                <p class="mt-2 text-[11px] leading-4 text-slate-500">Records marked Present</p>
            </div>
            <div class="dyh-summary-card min-w-0 p-4 sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-xs font-semibold leading-5 text-slate-500 sm:text-sm">Late</p>
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" /></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold tracking-tight text-amber-700">{{ number_format($totalLate) }}</p>
                <p class="mt-2 text-[11px] leading-4 text-slate-500">Records marked Late</p>
            </div>
            <div class="dyh-summary-card min-w-0 p-4 sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-xs font-semibold leading-5 text-slate-500 sm:text-sm">Incomplete</p>
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v6m0 4h.01" /></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold tracking-tight text-red-700">{{ number_format($totalIncomplete) }}</p>
                <p class="mt-2 text-[11px] leading-4 text-slate-500">Missing or invalid Time Out after finalization</p>
            </div>
        </section>

        {{-- Additional lifecycle status counts; all counts stay independent of the filter. --}}
        <section class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4" aria-label="Additional recorded status counts">
            <div class="dyh-summary-card flex items-center justify-between gap-3 p-4">
                <div>
                    <p class="text-xs font-bold text-[#3346a2]">Checked In</p>
                    <p class="mt-1 text-[11px] leading-4 text-slate-500">Awaiting valid Time Out</p>
                </div>
                <p class="shrink-0 text-2xl font-extrabold text-[#3346a2]">{{ number_format($totalCheckedIn) }}</p>
            </div>
            <div class="dyh-summary-card flex items-center justify-between gap-3 p-4">
                <div>
                    <p class="text-xs font-bold text-slate-600">Absent</p>
                    <p class="mt-1 text-[11px] leading-4 text-slate-500">Officially finalized only</p>
                </div>
                <p class="shrink-0 text-2xl font-extrabold text-slate-700">{{ number_format($totalAbsent) }}</p>
            </div>
            <div class="dyh-summary-card flex items-center justify-between gap-3 p-4">
                <div>
                    <p class="text-xs font-bold text-violet-700">Excused</p>
                    <p class="mt-1 text-[11px] leading-4 text-slate-500">Approved excuse records only</p>
                </div>
                <p class="shrink-0 text-2xl font-extrabold text-violet-700">{{ number_format($totalExcused) }}</p>
            </div>
        </section>

        {{-- Records and status filter. GET keeps browser navigation and pagination consistent. --}}
        <section id="attendance-records" class="dyh-panel min-w-0" aria-labelledby="dy-history-records-heading">
            <div class="dyh-panel-head">
                <div class="dyh-panel-head-inner">
                    <div class="mb-2 flex items-center gap-2">
                        <span class="h-0.5 w-6 rounded-full bg-[#D4A017]"></span>
                        <span class="dyh-panel-eyebrow text-[10px] font-bold uppercase tracking-[0.2em]">DySign · Attendance Records</span>
                    </div>
                    <h2 id="dy-history-records-heading" class="font-extrabold">My Event Attendance</h2>
                    <p class="mt-1 text-xs leading-5 sm:text-sm">View your recorded check-ins and attendance statuses across campus events.</p>
                </div>
            </div>
            <div class="dyh-filterbar">
                <div class="dyh-filter-heading">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-[#AB8117]">Search &amp; filters</p>
                        <h3 class="mt-1 text-base font-extrabold text-[#101064]">Find your attendance records</h3>
                        <p class="mt-1 text-xs leading-5 text-slate-500">Search events, narrow the dates, or view a specific attendance status.</p>
                    </div>
                    @if($hasActiveFilters)
                        <a href="{{ route('student.attendance-history.index') }}#attendance-records" class="dyh-filter-reset">Reset all filters</a>
                    @endif
                </div>

                @if($errors->any())
                    <div role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-semibold text-red-800">
                        Please check your filters: {{ $errors->first() }}
                    </div>
                @endif

                <form method="GET" action="{{ route('student.attendance-history.index') }}#attendance-records" class="dyh-filter-grid" aria-label="Filter attendance history">
                    <div class="dyh-filter-field dyh-filter-field--search">
                        <label for="dyh-search">Search event or venue</label>
                        <input id="dyh-search" class="dyh-filter-control" name="q" type="search" maxlength="100" value="{{ $searchTerm }}" placeholder="Event name or location..." autocomplete="off">
                    </div>
                    <div class="dyh-filter-field">
                        <label for="dyh-status">Attendance status</label>
                        <select id="dyh-status" name="status" class="dyh-filter-control">
                            <option value="all" @selected($selectedStatus === 'all')>All statuses</option>
                            @foreach(['Checked In','Present','Late','Incomplete','Absent','Excused'] as $statusOption)
                                <option value="{{ $statusOption }}" @selected($selectedStatus === $statusOption)>{{ $statusOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="dyh-filter-field dyh-filter-field--date">
                        <label for="dyh-date-from">Event date from</label>
                        <input id="dyh-date-from" class="dyh-filter-control" type="date" name="from" value="{{ $fromDate }}">
                    </div>
                    <div class="dyh-filter-field dyh-filter-field--date">
                        <label for="dyh-date-to">Event date to</label>
                        <input id="dyh-date-to" class="dyh-filter-control" type="date" name="to" value="{{ $toDate }}">
                    </div>
                    <div class="dyh-filter-field dyh-filter-field--sort">
                        <label for="dyh-sort">Sort records</label>
                        <select id="dyh-sort" name="sort" class="dyh-filter-control">
                            <option value="newest" @selected($selectedSort === 'newest')>Newest event first</option>
                            <option value="oldest" @selected($selectedSort === 'oldest')>Oldest event first</option>
                            <option value="recently_updated" @selected($selectedSort === 'recently_updated')>Recently updated</option>
                        </select>
                    </div>
                    <div class="dyh-filter-field dyh-filter-field--per">
                        <label for="dyh-per-page">Rows per page</label>
                        <select id="dyh-per-page" name="per_page" class="dyh-filter-control">
                            @foreach([5,10,20] as $pageSize)
                                <option value="{{ $pageSize }}" @selected($perPage === $pageSize)>{{ $pageSize }} records</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="dyh-filter-actions">
                        <button type="submit" class="dyh-filter-submit">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m16 16 4 4"/></svg>
                            Search &amp; Apply
                        </button>
                        <a class="dyh-filter-reset" href="{{ route('student.attendance-history.index') }}#attendance-records">Clear</a>
                    </div>
                </form>
                @if($hasActiveFilters)
                    <div class="mt-4 flex flex-wrap items-center gap-2" aria-label="Active attendance filters">
                        <span class="text-[11px] font-bold text-slate-500">Active:</span>
                        @if($searchTerm !== '') <span class="dyh-active-chip">Search: {{ $searchTerm }}</span> @endif
                        @if($selectedStatus !== 'all') <span class="dyh-active-chip">{{ $selectedStatus }}</span> @endif
                        @if($fromDate) <span class="dyh-active-chip">From {{ $fromDate }}</span> @endif
                        @if($toDate) <span class="dyh-active-chip">Until {{ $toDate }}</span> @endif
                        @if($selectedSort !== 'newest') <span class="dyh-active-chip">{{ $selectedSort === 'oldest' ? 'Oldest first' : 'Recently updated' }}</span> @endif
                        @if($perPage !== 5) <span class="dyh-active-chip">{{ $perPage }} per page</span> @endif
                    </div>
                @endif
            </div>
            <div class="dyh-results-bar" aria-live="polite">
                <span>
                    <strong class="font-extrabold text-[#101064]">{{ number_format($filteredCount) }}</strong>
                    {{ $filteredCount === 1 ? 'matching record' : 'matching records' }}
                    @if($hasActiveFilters) <span class="text-slate-400">· Filters applied</span> @endif
                </span>
                <span>Showing <strong class="text-[#101064]">{{ $attendanceRecords->firstItem() ?? 0 }}–{{ $attendanceRecords->lastItem() ?? 0 }}</strong> of {{ number_format($filteredCount) }}</span>
            </div>

            @if($attendanceRecords->isEmpty())
                <div class="px-5 py-14 text-center sm:py-20">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F0F1FA] text-[#101064]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 7h14v14H5zM8 16h8" /></svg>
                    </div>
                    <h3 class="mt-4 text-base font-extrabold text-[#101064]">{{ $hasRecords ? 'No matching attendance records' : 'No attendance recorded yet' }}</h3>
                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">
                        {{ $hasRecords ? 'Try another event keyword, date range, or attendance status.' : 'Your recorded event attendance will appear here once it is saved.' }}
                    </p>
                    @if($hasRecords)
                        <a href="{{ route('student.attendance-history.index') }}" class="mt-5 inline-flex rounded-xl bg-[#101064] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#23237B]">View all records</a>
                    @endif
                </div>
            @else
                {{-- Mobile/tablet view: readable full-width cards, with no sideways scrolling. --}}
                <div class="space-y-3 bg-[#FAFBFD] p-3 sm:p-5 lg:hidden">
                    @foreach($attendanceRecords as $record)
                        @php
                            $recordStatus = (string) ($record->status ?: 'Not specified');
                            $statusClass = $statusStyles[$recordStatus] ?? 'dyh-badge--other';
                        @endphp
                        <article class="dyh-mobile-card min-w-0 overflow-hidden p-4 sm:p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#A47D21]">Campus event</p>
                                    <h3 class="mt-1 break-words text-sm font-extrabold leading-6 text-[#101064] sm:text-base">{{ $record->event?->event_name ?: 'Event record unavailable' }}</h3>
                                    @if($record->event?->location)
                                        <p class="mt-1 break-words text-xs leading-5 text-slate-500">{{ $record->event->location }}</p>
                                    @endif
                                </div>
                                <span class="dyh-badge max-w-full shrink-0 {{ $statusClass }}">{{ $recordStatus }}</span>
                            </div>
                            <div class="dyh-mobile-card-details mt-4 grid grid-cols-2 gap-2.5 pt-4">
                                <div class="col-span-2 rounded-xl bg-[#F7F8FC] px-3 py-2.5 sm:col-span-1">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Event date</p>
                                    <p class="mt-1 text-xs font-semibold text-slate-700 sm:text-sm">{{ $record->event?->event_date ? \Illuminate\Support\Carbon::parse($record->event->event_date)->format('M d, Y') : 'Not available' }}</p>
                                </div>
                                <div class="rounded-xl bg-[#F7F8FC] px-3 py-2.5">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Time in</p>
                                    <p class="mt-1 text-xs font-semibold text-slate-700 sm:text-sm">{{ $record->time_in ? \Illuminate\Support\Carbon::parse($record->time_in)->format('h:i A') : 'Not recorded' }}</p>
                                </div>
                                <div class="rounded-xl bg-[#F7F8FC] px-3 py-2.5">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Time out</p>
                                    <p class="mt-1 text-xs font-semibold text-slate-700 sm:text-sm">{{ $record->time_out ? \Illuminate\Support\Carbon::parse($record->time_out)->format('h:i A') : 'Not recorded' }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Desktop view: same records and fields as mobile, displayed in a compact table. --}}
                <div class="hidden min-w-0 overflow-x-auto lg:block">
                    <table class="dyh-table min-w-full table-auto text-left text-sm">
                        <thead class="bg-[#F7F8FC] text-[11px] uppercase tracking-wider text-slate-500">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold">Event</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-4 font-bold">Event Date</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-4 font-bold">Time In</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-4 font-bold">Time Out</th>
                                <th scope="col" class="whitespace-nowrap px-6 py-4 font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EEF0F5]">
                            @foreach($attendanceRecords as $record)
                                @php
                                    $recordStatus = (string) ($record->status ?: 'Not specified');
                                    $statusClass = $statusStyles[$recordStatus] ?? 'dyh-badge--other';
                                @endphp
                                <tr class="transition hover:bg-[#FAFBFF]">
                                    <td class="max-w-[340px] px-6 py-4 align-top">
                                        <p class="break-words font-bold text-[#101064]">{{ $record->event?->event_name ?: 'Event record unavailable' }}</p>
                                        @if($record->event?->location)
                                            <p class="mt-1 break-words text-xs text-slate-500">{{ $record->event->location }}</p>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 align-top text-slate-600">{{ $record->event?->event_date ? \Illuminate\Support\Carbon::parse($record->event->event_date)->format('M d, Y') : '—' }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 align-top text-slate-600">{{ $record->time_in ? \Illuminate\Support\Carbon::parse($record->time_in)->format('h:i A') : '—' }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 align-top text-slate-600">{{ $record->time_out ? \Illuminate\Support\Carbon::parse($record->time_out)->format('h:i A') : 'Not recorded' }}</td>
                                    <td class="px-6 py-4 align-top"><span class="dyh-badge whitespace-nowrap {{ $statusClass }}">{{ $recordStatus }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if($filteredCount > 0)
                <div class="dyh-pager-footer">
                    <div>
                        <p class="text-xs font-bold text-[#101064]">Page {{ $pageNumber }} of {{ $lastPage }}</p>
                        <p class="mt-0.5 text-[11px] text-slate-500">{{ $attendanceRecords->firstItem() }}–{{ $attendanceRecords->lastItem() }} of {{ number_format($filteredCount) }} records</p>
                    </div>
                    <nav class="dyh-pager" aria-label="Attendance history pagination">
                        @if($attendanceRecords->onFirstPage())
                            <span class="dyh-page-link dyh-page-link--disabled" aria-disabled="true">Previous</span>
                        @else
                            <a class="dyh-page-link" rel="prev" href="{{ $attendanceRecords->previousPageUrl() }}">Previous</a>
                        @endif

                        @if($pageWindowStart > 1)
                            <a class="dyh-page-link" href="{{ $attendanceRecords->url(1) }}" aria-label="Go to page 1">1</a>
                            @if($pageWindowStart > 2) <span class="px-1 text-slate-400" aria-hidden="true">…</span> @endif
                        @endif
                        @for($page = $pageWindowStart; $page <= $pageWindowEnd; $page++)
                            @if($page === $pageNumber)
                                <span class="dyh-page-link dyh-page-link--current" aria-label="Page {{ $page }}" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="dyh-page-link" href="{{ $attendanceRecords->url($page) }}" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                            @endif
                        @endfor
                        @if($pageWindowEnd < $lastPage)
                            @if($pageWindowEnd < $lastPage - 1) <span class="px-1 text-slate-400" aria-hidden="true">…</span> @endif
                            <a class="dyh-page-link" href="{{ $attendanceRecords->url($lastPage) }}" aria-label="Go to page {{ $lastPage }}">{{ $lastPage }}</a>
                        @endif

                        @if($attendanceRecords->hasMorePages())
                            <a class="dyh-page-link" rel="next" href="{{ $attendanceRecords->nextPageUrl() }}">Next</a>
                        @else
                            <span class="dyh-page-link dyh-page-link--disabled" aria-disabled="true">Next</span>
                        @endif
                    </nav>
                </div>
            @endif
        </section>

        <aside class="dyh-note flex flex-col gap-3 px-4 py-4 sm:flex-row sm:items-start sm:gap-4 sm:px-6 sm:py-5">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#FFF1CB] text-[#9B7416]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 11v5m0-8h.01" /></svg>
            </div>
            <div class="min-w-0 flex-1">
                <h3 class="text-sm font-extrabold text-[#101064]">About your attendance history</h3>
                <p class="mt-1 text-xs leading-5 text-slate-600 sm:text-sm sm:leading-6">
                    This page shows saved attendance records only. Checked In means you still need a valid Time Out; Incomplete means the required checkout was not completed in time. Being eligible for a Required event does not automatically mark you Present or Absent. Absent and Excused appear only when officially recorded or approved.
                </p>
                <p class="mt-2 text-xs leading-5 text-slate-600 sm:text-sm sm:leading-6">If your scan is missing or incorrect, you can submit an attendance concern for review.</p>
                @if(\Illuminate\Support\Facades\Route::has('student.attendance-discrepancies.index'))
                    <a href="{{ route('student.attendance-discrepancies.index') }}" class="mt-3 inline-flex items-center gap-1.5 text-xs font-extrabold text-[#101064] underline decoration-[#D4A017] underline-offset-4 hover:text-[#6B550A] sm:text-sm">
                        Open Attendance Discrepancies
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" /></svg>
                    </a>
                @endif
            </div>
        </aside>

        <p class="text-[11px] leading-5 text-slate-500">
            Summary cards count your saved records, independent of the selected filter. Existing historical Present/Late entries without recorded Time Out remain unchanged pending an official review.
        </p>
    </div>
</x-admin-layout>
