<x-admin-layout>
    {{-- Student Account / Module 9. Presentation-only redesign: the controller remains the source of truth. --}}
    @php
        $hasRecords = (int) $totalAttendance > 0;
        $filteredCount = $attendanceRecords->total();
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
        .dyh-page .dyh-filterbar { display:flex; flex-wrap:wrap; align-items:end; justify-content:space-between; gap:16px; padding:18px 26px; background:#fff; border-bottom:1px solid #eef1f6 !important; }
        .dyh-page .dyh-filter-hint { margin:0; color:#6e7b92; font-size:12px; line-height:1.65; }
        .dyh-page .dyh-filter-select { border:1px solid #dfe5ef !important; background:#f9fafc; border-radius:11px; color:#101064; min-height:44px; }
        .dyh-page .dyh-filter-select:focus { outline:2px solid rgba(212,160,23,.35); outline-offset:1px; border-color:#d4a017 !important; }
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
        @media(max-width:767px){ .dyh-page .dyh-panel-head { padding:23px 20px; } .dyh-page .dyh-filterbar { padding:16px 18px; } .dyh-page .dyh-summary-card { border-radius:16px !important; } }
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
        <section class="dyh-panel min-w-0" aria-labelledby="dy-history-records-heading">
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
                <p class="dyh-filter-hint">
                    <strong class="font-bold text-[#101064]">{{ number_format($filteredCount) }}</strong>
                    {{ $filteredCount === 1 ? 'record' : 'records' }}{{ $selectedStatus !== 'all' ? ' matching the selected status' : ' in your history' }}
                </p>
                <form method="GET" action="{{ route('student.attendance-history.index') }}" class="flex w-full flex-wrap items-end gap-2 sm:w-auto">
                    <div class="min-w-[145px] flex-1 sm:min-w-[200px] sm:flex-none">
                        <label for="attendance-status" class="mb-1.5 block text-[11px] font-bold text-slate-600">Filter by status</label>
                        <select id="attendance-status" name="status" class="dyh-filter-select block w-full px-3 text-sm font-medium">
                            <option value="all" @selected($selectedStatus === 'all')>All recorded statuses</option>
                            <option value="Checked In" @selected($selectedStatus === 'Checked In')>Checked In</option>
                            <option value="Present" @selected($selectedStatus === 'Present')>Present</option>
                            <option value="Late" @selected($selectedStatus === 'Late')>Late</option>
                            <option value="Incomplete" @selected($selectedStatus === 'Incomplete')>Incomplete</option>
                            <option value="Absent" @selected($selectedStatus === 'Absent')>Absent</option>
                            <option value="Excused" @selected($selectedStatus === 'Excused')>Excused</option>
                        </select>
                    </div>
                    <button type="submit" class="inline-flex h-11 items-center justify-center rounded-xl bg-[#101064] px-5 text-xs font-bold text-white transition hover:bg-[#23237B] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#D4A017] sm:text-sm">Apply</button>
                    @if($selectedStatus !== 'all')
                        <a href="{{ route('student.attendance-history.index') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-[#DBE0EA] px-4 text-xs font-bold text-slate-600 transition hover:bg-slate-50 sm:text-sm">Clear</a>
                    @endif
                </form>
            </div>

            @if($attendanceRecords->isEmpty())
                <div class="px-5 py-14 text-center sm:py-20">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F0F1FA] text-[#101064]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 7h14v14H5zM8 16h8" /></svg>
                    </div>
                    <h3 class="mt-4 text-base font-extrabold text-[#101064]">{{ $hasRecords ? 'No matching attendance records' : 'No attendance recorded yet' }}</h3>
                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">
                        {{ $hasRecords ? 'Try a different attendance status filter to view your other records.' : 'Your recorded event attendance will appear here once it is saved.' }}
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

            @if($attendanceRecords->hasPages())
                <div class="dyh-pagination px-3 py-4 sm:px-6">
                    {{ $attendanceRecords->links() }}
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
