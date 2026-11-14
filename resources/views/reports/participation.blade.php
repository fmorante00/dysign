<x-admin-layout>
    <style>
        .dy-participation-hero {
            background: linear-gradient(100deg, rgba(12,13,79,.97) 0%, rgba(16,16,100,.91) 52%, rgba(16,16,100,.75) 100%),
                url('{{ asset('images/school.jpg') }}') center/cover;
        }
        .dy-participation-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; box-shadow: 0 5px 18px rgba(15,23,42,.035); }
        .dy-participation-field { width: 100%; min-height: 44px; border: 1px solid #dce3ee; border-radius: 12px; background: #fff; padding: 10px 12px; color: #101064; font-size: 13px; }
        .dy-participation-field:focus { outline: none; border-color: #c99b0e; box-shadow: 0 0 0 3px rgba(212,160,23,.15); }
        .dy-participation-label { display: block; margin-bottom: 8px; font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #64748b; }
        .dy-participation-table th { text-align: left; font-size: 10px; letter-spacing: .09em; text-transform: uppercase; color: #64748b; font-weight: 700; padding: 15px 18px; background: #f8fafc; white-space: nowrap; }
        .dy-participation-table td { padding: 15px 18px; font-size: 12px; color: #334155; border-top: 1px solid #eef2f7; vertical-align: middle; }
        .dy-participation-table tbody tr:hover { background: #fafbff; }
    </style>

    <div class="min-w-0 space-y-7 pb-8">
        <section class="dy-participation-hero relative isolate overflow-hidden rounded-[26px] px-7 py-9 text-white shadow-[0_10px_30px_rgba(16,16,100,.10)] sm:px-9">
            <div aria-hidden="true" class="pointer-events-none absolute -right-12 -top-36 h-[360px] w-[360px] rounded-full border border-white/20"></div>
            <div aria-hidden="true" class="pointer-events-none absolute right-24 -bottom-56 h-[380px] w-[380px] rounded-full border border-white/10"></div>
            <div class="absolute inset-x-0 bottom-0 h-[3px] bg-[#D4A017]"></div>
            <div class="relative z-10 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="max-w-2xl">
                    <p class="text-[10px] font-bold uppercase tracking-[.28em] text-[#F1CB57]">Reports &amp; Analytics</p>
                    <h1 class="mt-3 text-[28px] font-extrabold tracking-tight sm:text-3xl">Participation Reports</h1>
                    <p class="mt-3 text-sm leading-6 text-white/80">Review student participation classifications, filter the official records, and export exactly the CSV fields you need.</p>
                    <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-white/85">
                        <span>Live student participation records</span><span class="text-[#F1CB57]">•</span>
                        <span>Custom CSV columns</span><span class="text-[#F1CB57]">•</span>
                        <span>Administrator access</span>
                    </div>
                </div>
                <div class="shrink-0 rounded-2xl border border-white/20 bg-white/10 px-5 py-4 backdrop-blur-sm">
                    <p class="text-[10px] font-bold uppercase tracking-[.18em] text-[#F1CB57]">Report Method</p>
                    <p class="mt-2 text-sm font-bold">Automated Participation Evaluation</p>
                    <p class="mt-1 text-xs text-white/70">Attendance-based • Read-only</p>
                </div>
            </div>
        </section>

        @if($errors->any())
            <div role="alert" class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <section aria-labelledby="participation-summary-heading">
            <div class="mb-3">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#C38D00]">Report Overview</p>
                <h2 id="participation-summary-heading" class="mt-1 text-lg font-bold text-[#101064]">Participation Classification Summary</h2>
                <p class="mt-1 text-xs text-slate-500">Counts reflect all matching students under your selected filters, not only the current page.</p>
            </div>
            <div class="grid grid-cols-2 gap-3 xl:grid-cols-3 2xl:grid-cols-6">
                @foreach([
                    ['label'=>'Students Evaluated','value'=>$summary['total'],'color'=>'#101064','sub'=>'Matching student records'],
                    ['label'=>'Highly Participative','value'=>$summary['highly'],'color'=>'#059669','sub'=>'90–100%'],
                    ['label'=>'Participative','value'=>$summary['participative'],'color'=>'#2563eb','sub'=>'75–89%'],
                    ['label'=>'Moderately Participative','value'=>$summary['moderately'],'color'=>'#D4A017','sub'=>'60–74%'],
                    ['label'=>'Low Participation','value'=>$summary['low'],'color'=>'#dc2626','sub'=>'Below 60%'],
                    ['label'=>'No Data','value'=>$summary['no_data'],'color'=>'#64748b','sub'=>'No recorded events in scope'],
                ] as $stat)
                    <div class="dy-participation-card p-4 sm:p-5">
                        <p class="text-[10px] font-bold uppercase tracking-[.09em] text-slate-500">{{ $stat['label'] }}</p>
                        <p class="mt-2 text-3xl font-extrabold" style="color:{{ $stat['color'] }}">{{ number_format($stat['value']) }}</p>
                        <p class="mt-2 border-t border-slate-100 pt-2 text-[11px] text-slate-500">{{ $stat['sub'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section aria-labelledby="participation-filters-heading">
            <div class="mb-3">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#C38D00]">Report Builder</p>
                <h2 id="participation-filters-heading" class="mt-1 text-lg font-bold text-[#101064]">Search, Filter &amp; Export</h2>
                <p class="mt-1 text-xs leading-5 text-slate-500">Filter by student or academic profile. Event and date filters recalculate participation using recorded attendance events within that scope.</p>
            </div>
            <form id="participation-report-form" action="{{ route('reports.participation') }}" method="GET" class="dy-participation-card overflow-hidden">
                <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="sm:col-span-2">
                        <label for="pr-search" class="dy-participation-label">Student Search</label>
                        <input id="pr-search" class="dy-participation-field" type="search" name="search" value="{{ $filters['search'] }}" placeholder="Name, student number, college, program...">
                    </div>
                    <div>
                        <label for="pr-event" class="dy-participation-label">Event Scope</label>
                        <select id="pr-event" name="event_id" class="dy-participation-field">
                            <option value="">All Recorded Events</option>
                            @foreach($events as $event)
                                <option value="{{ $event->event_id }}" @selected((string)$filters['event_id']===(string)$event->event_id)>{{ $event->event_name }} • {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('M d, Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="pr-class" class="dy-participation-label">Participation Classification</label>
                        <select id="pr-class" name="classification" class="dy-participation-field">
                            <option value="">All Classifications</option>
                            @foreach($classifications as $classification)
                                <option value="{{ $classification }}" @selected($filters['classification']===$classification)>{{ $classification }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="pr-college" class="dy-participation-label">College</label>
                        <select id="pr-college" name="college" class="dy-participation-field">
                            <option value="">All Colleges</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college }}" @selected($filters['college']===$college)>{{ $college }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="pr-program" class="dy-participation-label">Program</label>
                        <select id="pr-program" name="program" class="dy-participation-field">
                            <option value="">All Programs</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->program_code }}" @selected($filters['program']===$program->program_code)>{{ $program->program_code }} — {{ $program->program_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="pr-year" class="dy-participation-label">Year Level</label>
                        <select id="pr-year" name="year_level" class="dy-participation-field">
                            <option value="">All Years</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" @selected((string)$filters['year_level']===(string)$year)>Year {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="pr-sort" class="dy-participation-label">Sort By</label>
                        <select id="pr-sort" name="sort" class="dy-participation-field">
                            <option value="name" @selected($filters['sort']==='name')>Student Name A–Z</option>
                            <option value="highest_rate" @selected($filters['sort']==='highest_rate')>Highest Participation</option>
                            <option value="lowest_rate" @selected($filters['sort']==='lowest_rate')>Lowest Participation</option>
                            <option value="most_events" @selected($filters['sort']==='most_events')>Most Events Attended</option>
                        </select>
                    </div>
                    <div>
                        <label for="pr-from" class="dy-participation-label">Event Date From</label>
                        <input id="pr-from" type="date" name="date_from" value="{{ $filters['date_from'] }}" class="dy-participation-field">
                    </div>
                    <div>
                        <label for="pr-to" class="dy-participation-label">Event Date To</label>
                        <input id="pr-to" type="date" name="date_to" value="{{ $filters['date_to'] }}" class="dy-participation-field">
                    </div>
                    <div>
                        <label for="pr-per-page" class="dy-participation-label">Rows Per Page</label>
                        <select id="pr-per-page" name="per_page" class="dy-participation-field">
                            @foreach([10,25,50] as $count)
                                <option value="{{ $count }}" @selected((int)$filters['per_page']===$count)>{{ $count }} students</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="border-t border-slate-100 bg-slate-50/60 p-5">
                    <details class="group rounded-xl border border-slate-200 bg-white p-4">
                        <summary class="cursor-pointer text-xs font-bold text-[#101064]">Customize CSV Columns <span class="font-normal text-slate-500">(choose only the fields to include)</span></summary>
                        <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-3 sm:grid-cols-3 lg:grid-cols-4">
                            @foreach($csvColumns as $key=>$title)
                                <label class="flex cursor-pointer items-start gap-2 text-xs text-slate-700">
                                    <input type="checkbox" name="columns[]" value="{{ $key }}" @checked(in_array($key, $selectedColumns, true)) class="mt-0.5 rounded border-slate-300 text-[#101064] focus:ring-[#D4A017]">
                                    <span>{{ $title }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-3 text-[11px] text-slate-500">Uncheck fields you do not need. If none are selected, the standard columns will be exported.</p>
                    </details>
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                        <a href="{{ route('reports.participation') }}" class="inline-flex min-h-[42px] items-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-100">Clear Filters</a>
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-xl bg-[#101064] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#0b0b4d]">Generate Report</button>
                            <button type="submit" formaction="{{ route('reports.participation.export') }}" formmethod="GET" class="inline-flex min-h-[42px] items-center justify-center rounded-xl bg-[#D4A017] px-5 py-2.5 text-xs font-bold text-[#101064] hover:bg-[#e9b326]">Export Filtered CSV</button>
                        </div>
                    </div>
                    <p class="mt-3 text-[11px] leading-5 text-slate-500">CSV includes every matching student (not just this page). The event-based rate uses distinct events with recorded attendance; event/date filters change the reporting scope.</p>
                </div>
            </form>
        </section>

        <section aria-labelledby="participation-results-heading">
            <div class="mb-3 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#C38D00]">Participation Data</p>
                    <h2 id="participation-results-heading" class="mt-1 text-lg font-bold text-[#101064]">Student Participation Results</h2>
                    <p class="mt-1 text-xs text-slate-500">{{ number_format($results->total()) }} matching students • {{ number_format($totalRecordedEvents) }} recorded event{{ $totalRecordedEvents === 1 ? '' : 's' }} in scope.</p>
                </div>
                <span class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-[#101064]">Page {{ $results->currentPage() }} of {{ $results->lastPage() }}</span>
            </div>
            <div class="dy-participation-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="dy-participation-table w-full min-w-[950px]">
                        <thead>
                            <tr>
                                <th>Student</th><th>College / Program</th><th>Year Level</th><th>Events Attended</th><th>Participation Rate</th><th>Classification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $student)
                                @php
                                    $badgeClass = match($student->classification) {
                                        'Highly Participative' => 'bg-emerald-50 text-emerald-700',
                                        'Participative' => 'bg-blue-50 text-blue-700',
                                        'Moderately Participative' => 'bg-amber-50 text-amber-700',
                                        'No Data' => 'bg-slate-100 text-slate-600',
                                        default => 'bg-red-50 text-red-700',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <p class="font-bold text-[#101064]">{{ $student->student_name }}</p>
                                        <p class="mt-1 text-[11px] text-slate-500">{{ $student->student_number }}</p>
                                    </td>
                                    <td>
                                        <p class="font-semibold text-[#101064]">{{ $student->program_code ?: 'Not specified' }}</p>
                                        <p class="mt-1 text-[11px] text-slate-500">{{ $student->college ?: 'Unassigned' }}</p>
                                    </td>
                                    <td>Year {{ $student->year_level ?: '—' }}</td>
                                    <td class="font-semibold">{{ $student->events_attended }} / {{ $student->total_applicable_events }}</td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <span class="min-w-[47px] font-bold text-[#101064]">{{ number_format($student->participation_rate,1) }}%</span>
                                            <div class="h-1.5 w-16 rounded-full bg-slate-100"><div class="h-1.5 rounded-full bg-[#D4A017]" style="width:{{ min(100,max(0,$student->participation_rate)) }}%"></div></div>
                                        </div>
                                    </td>
                                    <td><span class="inline-flex rounded-full px-3 py-1.5 text-[11px] font-semibold {{ $badgeClass }}">{{ $student->classification }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-16 text-center"><p class="font-bold text-[#101064]">No matching participation records</p><p class="mt-2 text-xs text-slate-500">Try clearing your filters or selecting a different event/date range.</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-col gap-4 border-t border-slate-100 bg-slate-50/60 px-5 py-4 md:flex-row md:items-center md:justify-between">
                    <p class="text-xs text-slate-600">
                        @if($results->total())
                            Showing <strong>{{ $results->firstItem() }}–{{ $results->lastItem() }}</strong> of <strong>{{ number_format($results->total()) }}</strong> matching students
                        @else
                            0 matching students
                        @endif
                    </p>
                    <div class="max-w-full overflow-x-auto text-sm">{{ $results->onEachSide(1)->links() }}</div>
                </div>
            </div>
        </section>
    </div>
</x-admin-layout>
