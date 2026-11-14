<x-admin-layout>
    <style>
        .dy-attendance-report-hero {
            background: linear-gradient(100deg, rgba(12,13,79,.97) 0%, rgba(16,16,100,.91) 52%, rgba(16,16,100,.75) 100%),
                url('{{ asset('images/school.jpg') }}') center/cover;
        }
        .dy-report-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; box-shadow: 0 5px 18px rgba(15,23,42,.035); }
        .dy-report-field { width: 100%; min-height: 44px; border: 1px solid #dce3ee; border-radius: 12px; background: #fff; padding: 10px 12px; color: #101064; font-size: 13px; }
        .dy-report-field:focus { outline: none; border-color: #c99b0e; box-shadow: 0 0 0 3px rgba(212,160,23,.15); }
        .dy-report-label { display: block; margin-bottom: 8px; font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #64748b; }
        .dy-report-table th { text-align: left; font-size: 10px; letter-spacing: .09em; text-transform: uppercase; color: #64748b; font-weight: 700; padding: 15px 18px; background: #f8fafc; white-space: nowrap; }
        .dy-report-table td { padding: 15px 18px; font-size: 12px; color: #334155; border-top: 1px solid #eef2f7; vertical-align: middle; }
        .dy-report-table tbody tr:hover { background: #fafbff; }
    </style>

    <div class="min-w-0 space-y-7 pb-8">
        {{-- Consistent DySign campus banner with Student Dashboard-style circles. --}}
        <section class="dy-attendance-report-hero relative isolate overflow-hidden rounded-[26px] px-7 py-9 text-white shadow-[0_10px_30px_rgba(16,16,100,.10)] sm:px-9">
            <div aria-hidden="true" class="pointer-events-none absolute -right-12 -top-36 h-[360px] w-[360px] rounded-full border border-white/20"></div>
            <div aria-hidden="true" class="pointer-events-none absolute right-24 -bottom-56 h-[380px] w-[380px] rounded-full border border-white/10"></div>
            <div class="absolute inset-x-0 bottom-0 h-[3px] bg-[#D4A017]"></div>
            <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="max-w-2xl">
                    <p class="text-[10px] font-bold uppercase tracking-[.28em] text-[#F1CB57]">Reports &amp; Analytics</p>
                    <h1 class="mt-3 text-[28px] font-extrabold tracking-tight sm:text-3xl">Attendance Reports</h1>
                    <p class="mt-3 text-sm leading-6 text-white/80">Generate official event attendance reports, review RFID records, and export only the information you need.</p>
                    <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 text-xs text-white/85">
                        <span>RFID attendance records</span>
                        <span class="text-[#F1CB57]">•</span>
                        <span>Filterable CSV exports</span>
                        <span class="text-[#F1CB57]">•</span>
                        <span>Administrator access</span>
                    </div>
                </div>
                <div class="shrink-0 rounded-2xl border border-white/20 bg-white/10 px-5 py-4 backdrop-blur-sm">
                    <p class="text-[10px] font-bold uppercase tracking-[.18em] text-[#F1CB57]">Report Source</p>
                    <p class="mt-2 text-sm font-bold">DySign Attendance Database</p>
                    <p class="mt-1 text-xs text-white/70">Live stored records • Read-only</p>
                </div>
            </div>
        </section>

        <section aria-labelledby="attendance-summary-title">
            <div class="mb-3">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#C38D00]">Filtered Overview</p>
                <h2 id="attendance-summary-title" class="mt-1 text-lg font-extrabold text-[#101064]">Attendance Report Summary</h2>
                <p class="mt-1 text-xs text-slate-500">Counts reflect all matching records across every page, not just the current table.</p>
            </div>
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-3 xl:grid-cols-6">
                @foreach ([
                    ['label'=>'Attendance Records','value'=>$summary['total'],'color'=>'text-[#101064]','note'=>'Matching entries'],
                    ['label'=>'Events With Records','value'=>$summary['events'],'color'=>'text-[#101064]','note'=>'Distinct events'],
                    ['label'=>'Present','value'=>$summary['present'],'color'=>'text-emerald-600','note'=>'Finalized on time'],
                    ['label'=>'Late','value'=>$summary['late'],'color'=>'text-amber-600','note'=>'Finalized late'],
                    ['label'=>'Checked In','value'=>$summary['checked_in'],'color'=>'text-blue-600','note'=>'Awaiting final status'],
                    ['label'=>'Checked Out','value'=>$summary['checked_out'],'color'=>'text-[#101064]','note'=>'With time-out record'],
                ] as $card)
                    <div class="dy-report-card p-4 sm:p-5">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">{{ $card['label'] }}</p>
                        <p class="mt-3 text-3xl font-extrabold {{ $card['color'] }}">{{ number_format($card['value']) }}</p>
                        <p class="mt-2 text-[11px] text-slate-500">{{ $card['note'] }}</p>
                    </div>
                @endforeach
            </div>
            <p class="mt-3 text-xs text-slate-500">
                Other statuses: <strong class="text-slate-700">{{ $summary['incomplete'] }} Incomplete</strong>,
                <strong class="text-slate-700">{{ $summary['absent'] }} Absent</strong>,
                <strong class="text-slate-700">{{ $summary['excused'] }} Excused</strong>.
                On-time rate (Present ÷ finalized Present/Late):
                <strong class="text-[#101064]">{{ $summary['on_time_rate'] === null ? 'N/A' : number_format($summary['on_time_rate'], 1).'%' }}</strong>.
            </p>
        </section>

        <section aria-labelledby="report-filter-title">
            <div class="mb-3">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#C38D00]">Report Builder</p>
                <h2 id="report-filter-title" class="mt-1 text-lg font-extrabold text-[#101064]">Choose Your Report Data</h2>
                <p class="mt-1 text-xs text-slate-500">Select the event, date range, student group, and attendance status. CSV export uses the same filters.</p>
            </div>

            <form id="attendance-report-form" action="{{ route('reports.attendance') }}" method="GET" class="dy-report-card overflow-hidden">
                <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 xl:grid-cols-4 xl:p-6">
                    <div class="sm:col-span-2">
                        <label class="dy-report-label" for="ar-event">Event</label>
                        <select class="dy-report-field" id="ar-event" name="event_id">
                            <option value="all">All University Events</option>
                            @foreach($events as $event)
                                <option value="{{ $event->event_id }}" @selected((string) $filters['event_id'] === (string) $event->event_id)>
                                    {{ $event->event_name }} • {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }} ({{ $event->status }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="dy-report-label" for="ar-status">Attendance Status</label>
                        <select class="dy-report-field" id="ar-status" name="status">
                            <option value="all">All Attendance Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="dy-report-label" for="ar-college">College / Department</label>
                        <select class="dy-report-field" id="ar-college" name="college">
                            <option value="">All Colleges</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college }}" @selected($filters['college'] === $college)>{{ $college }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="dy-report-label" for="ar-program">Program</label>
                        <select class="dy-report-field" id="ar-program" name="program">
                            <option value="">All Programs</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->program_code }}" @selected($filters['program'] === $program->program_code)>
                                    {{ $program->program_code }}{{ $program->program_name ? ' — '.$program->program_name : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="dy-report-label" for="ar-year">Year Level</label>
                        <select class="dy-report-field" id="ar-year" name="year_level">
                            <option value="">All Year Levels</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" @selected((string) $filters['year_level'] === (string) $year)>Year {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="dy-report-label" for="ar-from">Event Date From</label>
                        <input class="dy-report-field" id="ar-from" type="date" name="date_from" value="{{ $filters['date_from'] }}">
                    </div>
                    <div>
                        <label class="dy-report-label" for="ar-to">Event Date To</label>
                        <input class="dy-report-field" id="ar-to" type="date" name="date_to" value="{{ $filters['date_to'] }}">
                    </div>
                    <div class="sm:col-span-2 xl:col-span-3">
                        <label class="dy-report-label" for="ar-search">Search Student or Event</label>
                        <input class="dy-report-field" id="ar-search" type="search" name="search" value="{{ $filters['search'] }}" placeholder="Student number, name, or event title..." maxlength="120">
                    </div>
                    <div>
                        <label class="dy-report-label" for="ar-per-page">Rows Per Page</label>
                        <select class="dy-report-field" id="ar-per-page" name="per_page">
                            @foreach([10,25,50] as $size)
                                <option value="{{ $size }}" @selected((int) $filters['per_page'] === $size)>{{ $size }} records</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="border-t border-slate-100 px-5 py-4 xl:px-6">
                    <details class="group">
                        <summary class="flex cursor-pointer select-none items-center gap-2 text-xs font-bold text-[#101064]">Customize CSV Columns <span class="font-normal text-slate-500">(choose only the fields you want to export)</span></summary>
                        <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-3 sm:grid-cols-3 xl:grid-cols-5">
                            @foreach($csvColumns as $key => $label)
                                <label class="flex items-start gap-2 text-xs text-slate-700">
                                    <input type="checkbox" name="columns[]" value="{{ $key }}" @checked(in_array($key, $selectedColumns, true))
                                        class="mt-0.5 rounded border-slate-300 text-[#101064] focus:ring-[#D4A017]">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-3 text-xs text-slate-500">If no fields are selected, the standard set is exported. Choose a subset to control the CSV columns.</p>
                    </details>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between xl:px-6">
                    <p class="text-xs text-slate-500">{{ number_format($summary['total']) }} matching attendance records. Export includes all matching pages.</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('reports.attendance') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-100">Clear Filters</a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[#101064] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-[#0b0b4c]">Generate Report</button>
                        <button type="submit" formaction="{{ route('reports.attendance.export') }}" formmethod="GET" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#D4A017] px-5 py-2.5 text-xs font-bold text-[#101064] transition hover:bg-[#e9b326]">
                            <svg class="h-4 w-4" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3h16v-3"/></svg>
                            Export Filtered CSV
                        </button>
                    </div>
                </div>
            </form>
            @if($errors->any())
                <div class="mt-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif
        </section>

        <section aria-labelledby="report-table-title">
            <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#C38D00]">Attendance Registry</p>
                    <h2 id="report-table-title" class="mt-1 text-lg font-extrabold text-[#101064]">Filtered Attendance Records</h2>
                    <p class="mt-1 text-xs text-slate-500">Showing {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ number_format($records->total()) }} records</p>
                </div>
                <span class="w-fit rounded-full border border-slate-200 bg-white px-3 py-2 text-xs text-slate-500">Page {{ $records->currentPage() }} of {{ $records->lastPage() }}</span>
            </div>

            <div class="dy-report-card overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="dy-report-table w-full min-w-[1100px] border-collapse">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>College / Program</th>
                                <th>Event / Date</th>
                                <th>Attendance Status</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Recorded By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                                @php
                                    $studentName = trim(collect([$record->first_name, $record->middle_name, $record->last_name])->filter()->implode(' '));
                                    $statusTone = match ($record->status) {
                                        'Present' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'Late' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'Checked In' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'Absent' => 'bg-red-50 text-red-700 border-red-100',
                                        'Excused' => 'bg-violet-50 text-violet-700 border-violet-100',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <p class="font-bold text-[#101064]">{{ $studentName }}</p>
                                        <p class="mt-1 text-[11px] text-slate-500">{{ $record->student_number }}</p>
                                    </td>
                                    <td>
                                        <p class="font-semibold text-slate-700">{{ $record->program_code ?: '—' }}</p>
                                        <p class="mt-1 text-[11px] text-slate-500">{{ $record->college ?: '—' }} · Year {{ $record->year_level ?: '—' }}</p>
                                    </td>
                                    <td>
                                        <p class="max-w-[240px] font-semibold text-[#101064]">{{ $record->event_name }}</p>
                                        <p class="mt-1 text-[11px] text-slate-500">{{ \Carbon\Carbon::parse($record->event_date)->format('M d, Y') }}</p>
                                    </td>
                                    <td><span class="inline-flex whitespace-nowrap rounded-full border px-3 py-1 text-[11px] font-bold {{ $statusTone }}">{{ $record->status }}</span></td>
                                    <td>{{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('M d, Y · h:i A') : '—' }}</td>
                                    <td>{{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('M d, Y · h:i A') : '—' }}</td>
                                    <td>{{ $record->scanned_by_name ?: 'System / Not recorded' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="!px-5 !py-16 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-[#101064]">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3v3m8-3v3M4 10h16M6 5h12a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z" /></svg>
                                        </div>
                                        <h3 class="text-base font-extrabold text-[#101064]">No attendance records match</h3>
                                        <p class="mt-2 text-xs leading-5 text-slate-500">Try another event, status, student group, or event-date range. Only stored attendance records are included.</p>
                                    </div>
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($records->hasPages())
                    <div class="border-t border-slate-100 px-5 py-4">{{ $records->onEachSide(1)->links() }}</div>
                @endif
            </div>
            <p class="mt-3 text-xs leading-5 text-slate-500">Note: Students without an attendance record are not automatically counted as Absent. The report displays only actual records and statuses saved in DySign. Export includes all filtered records, not just this page.</p>
        </section>
    </div>
</x-admin-layout>
