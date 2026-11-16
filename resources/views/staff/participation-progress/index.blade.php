<x-admin-layout>
    <style>
        .dy-staff-part-hero {
            background-image: radial-gradient(circle at 97% 12%, transparent 0 78px, rgba(255,255,255,.17) 79px 81px, transparent 82px 128px, rgba(255,255,255,.10) 129px 131px, transparent 132px), linear-gradient(105deg, rgba(16,16,100,.98) 0%, rgba(16,16,100,.94) 49%, rgba(16,16,100,.76) 100%), url('{{ asset('images/school.jpg') }}');
            background-position: center;
            background-size: cover;
        }
        .dy-staff-part-pdf { display:inline-flex; align-items:center; justify-content:center; gap:8px; background:#E5AE19!important; color:#101064!important; border:1px solid #F5C847; border-radius:12px; padding:12px 17px; font-size:13px; font-weight:800; text-decoration:none; }
        .dy-staff-part-pdf:hover { background:#F6C744!important; }
        .dy-staff-part-csv { display:inline-flex; align-items:center; justify-content:center; gap:8px; background:#fff!important; color:#101064!important; border:1px solid #cbd5e1; border-radius:12px; padding:12px 17px; font-size:13px; font-weight:800; text-decoration:none; }
        .dy-staff-part-csv:hover { background:#f8fafc!important; }
        .dy-staff-part-primary { background:#101064!important; color:white!important; }
        .dy-staff-part-mobile { display:none; }
        @media (max-width: 920px) {
            .dy-staff-part-table { display:none; }
            .dy-staff-part-mobile { display:block; }
        }
    </style>

    <div class="w-full min-w-0 space-y-6 pb-10">
        {{-- Existing school-wide theme, but department-scoped data and actions. --}}
        <section class="dy-staff-part-hero relative overflow-hidden rounded-[28px] border-b-4 border-[#D4A017] px-7 py-8 text-white shadow-sm sm:px-9 sm:py-9">
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-extrabold uppercase tracking-[.22em] text-[#F2C94C]">DySign &middot; Department Staff</p>
                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Participation Progress</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-indigo-100">Monitor eligible student participation across completed department events, review progress, and export official-system data for reporting.</p>
                    <div class="mt-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white">
                        <span class="h-2 w-2 rounded-full bg-[#F2C94C]"></span>
                        Department events only &middot; Attendance-based progress
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 lg:max-w-sm lg:justify-end">
                    <a class="dy-staff-part-csv" href="{{ route('staff.participation-progress.csv', request()->only(['search', 'program', 'year', 'progress', 'sort'])) }}" aria-label="Download filtered participation report as CSV">
                        <svg class="h-4 w-4" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3v12m-4-4 4 4 4-4M4 17v3h16v-3"/></svg>
                        Download CSV
                    </a>
                    <a class="dy-staff-part-pdf" href="{{ route('staff.participation-progress.pdf', request()->only(['search', 'program', 'year', 'progress', 'sort'])) }}" aria-label="Download filtered participation report as PDF">
                        <svg class="h-4 w-4" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3v12m-4-4 4 4 4-4M4 17v3h16v-3"/></svg>
                        Download PDF
                    </a>
                    <p class="w-full text-xs text-indigo-100/90 lg:text-right">Exports include all matching students, not just the current page.</p>
                </div>
            </div>
        </section>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Department participation overview">
            @foreach([
                ['label' => 'Matching Students', 'value' => number_format($totalStudents), 'help' => 'Active, eligible students matching filters', 'tone' => 'indigo'],
                ['label' => 'Completed Events', 'value' => number_format($totalCompletedEvents), 'help' => 'Completed events in your department', 'tone' => 'amber'],
                ['label' => 'Average Participation', 'value' => number_format($averageProgress, 1) . '%', 'help' => 'Average attendance rate in filtered results', 'tone' => 'emerald'],
                ['label' => 'Showing Per Page', 'value' => (string) $filters['per_page'], 'help' => 'Choose 5, 10, or 20 records below', 'tone' => 'slate'],
            ] as $metric)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">{{ $metric['label'] }}</p>
                            <p class="mt-4 text-3xl font-extrabold tracking-tight {{ $metric['tone'] === 'amber' ? 'text-[#B88400]' : 'text-[#101064]' }}">{{ $metric['value'] }}</p>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $metric['tone'] === 'amber' ? 'bg-amber-50 text-amber-700' : ($metric['tone'] === 'emerald' ? 'bg-emerald-50 text-emerald-700' : 'bg-indigo-50 text-[#101064]') }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 17l5-5 4 3 7-8M4 4v16h16"/></svg>
                        </div>
                    </div>
                    <p class="mt-2 text-xs leading-5 text-slate-500">{{ $metric['help'] }}</p>
                </div>
            @endforeach
        </div>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="staff-progress-title">
            <header class="border-b border-slate-100 bg-[#101064] px-6 py-5 text-white sm:px-7">
                <p class="text-[11px] font-bold uppercase tracking-[.19em] text-[#F2C94C]">Participation &amp; Student Monitoring</p>
                <h2 id="staff-progress-title" class="mt-1 text-lg font-extrabold">Student Participation Records</h2>
                <p class="mt-1 text-xs text-indigo-100">Completed eligible events and recorded attendance; no activity points.</p>
            </header>

            <form method="GET" action="{{ route('staff.participation-progress.index') }}" class="grid gap-4 border-b border-slate-100 bg-slate-50/60 p-5 sm:p-6 lg:grid-cols-3 xl:grid-cols-6" aria-label="Filter student participation">
                <div class="lg:col-span-2 xl:col-span-2">
                    <label for="dy-staff-part-search" class="mb-2 block text-xs font-bold text-slate-700">Search Student</label>
                    <input id="dy-staff-part-search" name="search" type="search" maxlength="100" value="{{ $filters['search'] }}" placeholder="Name, student number, or program" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]">
                </div>
                <div>
                    <label for="dy-staff-part-program" class="mb-2 block text-xs font-bold text-slate-700">Program</label>
                    <select id="dy-staff-part-program" name="program" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-sm">
                        <option value="">All programs</option>
                        @foreach($programs as $program)
                            <option value="{{ $program }}" @selected($filters['program'] === (string) $program)>{{ $program }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="dy-staff-part-year" class="mb-2 block text-xs font-bold text-slate-700">Year Level</label>
                    <select id="dy-staff-part-year" name="year" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-sm">
                        <option value="">All years</option>
                        @foreach(range(1, 8) as $year)
                            <option value="{{ $year }}" @selected($filters['year'] === $year)>Year {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="dy-staff-part-progress" class="mb-2 block text-xs font-bold text-slate-700">Progress Level</label>
                    <select id="dy-staff-part-progress" name="progress" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-sm">
                        <option value="">All levels</option>
                        <option value="high" @selected($filters['progress'] === 'high')>High (80%+)</option>
                        <option value="moderate" @selected($filters['progress'] === 'moderate')>Moderate (50-79.9%)</option>
                        <option value="low" @selected($filters['progress'] === 'low')>Low (below 50%)</option>
                    </select>
                </div>
                <div>
                    <label for="dy-staff-part-sort" class="mb-2 block text-xs font-bold text-slate-700">Sort By</label>
                    <select id="dy-staff-part-sort" name="sort" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-sm">
                        <option value="name_asc" @selected($filters['sort'] === 'name_asc')>Name A to Z</option>
                        <option value="name_desc" @selected($filters['sort'] === 'name_desc')>Name Z to A</option>
                        <option value="progress_desc" @selected($filters['sort'] === 'progress_desc')>Highest progress</option>
                        <option value="progress_asc" @selected($filters['sort'] === 'progress_asc')>Lowest progress</option>
                        <option value="attended_desc" @selected($filters['sort'] === 'attended_desc')>Most attended</option>
                        <option value="eligible_desc" @selected($filters['sort'] === 'eligible_desc')>Most eligible</option>
                    </select>
                </div>
                <div class="flex flex-wrap items-end gap-3 lg:col-span-3 xl:col-span-6">
                    <div class="w-40">
                        <label for="dy-staff-part-per-page" class="mb-2 block text-xs font-bold text-slate-700">Rows Per Page</label>
                        <select id="dy-staff-part-per-page" name="per_page" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-sm">
                            @foreach([5, 10, 20] as $size)
                                <option value="{{ $size }}" @selected($filters['per_page'] === $size)>{{ $size }} students</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="dy-staff-part-primary rounded-xl px-6 py-3 text-sm font-extrabold transition hover:opacity-90">Apply Filters</button>
                    <a href="{{ route('staff.participation-progress.index') }}" class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-bold text-[#101064] hover:bg-slate-50">Reset</a>
                    <p class="ml-auto text-xs text-slate-500">Filters apply to the table and both downloads.</p>
                </div>
            </form>

            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4 text-xs text-slate-600 sm:px-6">
                <span><strong class="text-[#101064]">{{ number_format($students->total()) }}</strong> matching student{{ $students->total() === 1 ? '' : 's' }}</span>
                <span>Showing {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} of {{ $students->total() }}</span>
            </div>

            <div class="dy-staff-part-table overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-4">Student</th>
                            <th scope="col" class="px-5 py-4">Program / Year</th>
                            <th scope="col" class="px-5 py-4 text-center">Eligible</th>
                            <th scope="col" class="px-5 py-4 text-center">Attended</th>
                            <th scope="col" class="min-w-48 px-5 py-4">Participation Rate</th>
                            <th scope="col" class="px-5 py-4">Level</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $student)
                            @php
                                $rate = min(100, max(0, (float) $student->progress_percentage));
                                $level = $rate >= 80 ? 'High' : ($rate >= 50 ? 'Moderate' : 'Low');
                                $levelClass = $rate >= 80 ? 'bg-emerald-50 text-emerald-700' : ($rate >= 50 ? 'bg-amber-50 text-amber-800' : 'bg-rose-50 text-rose-700');
                            @endphp
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-4"><p class="font-bold text-[#101064]">{{ $student->last_name }}, {{ $student->first_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $student->student_number }}</p></td>
                                <td class="px-5 py-4"><p class="font-semibold text-slate-700">{{ $student->program_code ?: 'N/A' }}</p><p class="mt-1 text-xs text-slate-500">Year {{ $student->year_level ?: 'N/A' }}</p></td>
                                <td class="px-5 py-4 text-center font-bold text-slate-700">{{ number_format($student->eligible_count) }}</td>
                                <td class="px-5 py-4 text-center font-bold text-[#101064]">{{ number_format($student->attended_count) }}</td>
                                <td class="px-5 py-4"><div class="mb-2 font-bold text-[#101064]">{{ number_format($rate, 1) }}%</div><div class="h-2.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full" style="width:{{ $rate }}%; background-color:{{ $rate >= 80 ? '#059669' : ($rate >= 50 ? '#D4A017' : '#C24141') }};"></div></div></td>
                                <td class="px-5 py-4"><span class="inline-flex rounded-full px-3 py-1.5 text-xs font-bold {{ $levelClass }}">{{ $level }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-14 text-center"><p class="font-bold text-[#101064]">No matching student records</p><p class="mt-2 text-xs text-slate-500">Try clearing the filters to view more participation records.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="dy-staff-part-mobile divide-y divide-slate-100">
                @forelse($students as $student)
                    @php
                        $rate = min(100, max(0, (float) $student->progress_percentage));
                        $level = $rate >= 80 ? 'High' : ($rate >= 50 ? 'Moderate' : 'Low');
                    @endphp
                    <article class="p-5">
                        <div class="flex items-start justify-between gap-3"><div><p class="font-extrabold text-[#101064]">{{ $student->last_name }}, {{ $student->first_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $student->student_number }} &middot; {{ $student->program_code ?: 'N/A' }} &middot; Year {{ $student->year_level ?: 'N/A' }}</p></div><span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-[#101064]">{{ $level }}</span></div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-sm"><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Eligible</p><p class="mt-1 font-bold text-[#101064]">{{ $student->eligible_count }}</p></div><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Attended</p><p class="mt-1 font-bold text-[#101064]">{{ $student->attended_count }}</p></div></div>
                        <p class="mt-4 text-sm font-bold text-[#101064]">{{ number_format($rate, 1) }}% participation</p>
                        <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full" style="width:{{ $rate }}%; background-color:#D4A017;"></div></div>
                    </article>
                @empty
                    <p class="p-7 text-center text-sm text-slate-600">No matching student records.</p>
                @endforelse
            </div>

            @if($students->hasPages())
                <div class="border-t border-slate-100 px-5 py-4 sm:px-6">{{ $students->links() }}</div>
            @endif
        </section>

        <p class="text-xs leading-5 text-slate-500">How participation is calculated: Present or Late attendance in completed events for which the student is eligible, divided by the number of completed eligible events. High = 80% or above; Moderate = 50% to 79.9%; Low = below 50%. Progress labels are monitoring indicators, not formal academic classifications. CSV and PDF are read-only reports.</p>
    </div>
</x-admin-layout>
