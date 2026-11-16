<x-admin-layout>
    <style>
        .dy-pp-hero{background-image:radial-gradient(circle at 97% 10%,transparent 0 84px,rgba(255,255,255,.15) 85px 87px,transparent 88px 136px,rgba(255,255,255,.1) 137px 139px,transparent 140px),linear-gradient(105deg,rgba(16,16,100,.98),rgba(16,16,100,.9) 55%,rgba(16,16,100,.73)),url('{{ asset('images/school.jpg') }}');background-size:cover;background-position:center}
        .dy-pp-table{width:100%;border-collapse:collapse;min-width:740px}
        .dy-pp-table th{text-align:left;font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;background:#f8fafc;padding:14px 17px;border-bottom:1px solid #e2e8f0}
        .dy-pp-table td{padding:15px 17px;border-bottom:1px solid #edf0f5;font-size:13px;color:#475569;vertical-align:middle}
        .dy-pp-table tbody tr:hover{background:#fafbff}
        .dy-pp-table tbody tr:last-child td{border-bottom:0}
        .dy-pp-field{display:block;width:100%;min-height:44px;border:1px solid #d9e2f0;border-radius:12px;background:white;padding:10px 13px;font-size:13px;color:#101064}
        .dy-pp-field:focus{outline:2px solid rgba(16,16,100,.2);border-color:#101064}
        .dy-pp-pill{display:inline-flex;align-items:center;border-radius:999px;padding:5px 11px;font-size:11px;font-weight:700;white-space:nowrap}
        .dy-pp-steps nav[role=navigation] a,.dy-pp-steps nav[role=navigation] span{font-size:12px}
        @media(max-width:640px){.dy-pp-hero{background-position:60% center}.dy-pp-table{min-width:670px}}
    </style>

    <div class="w-full min-w-0 space-y-6 pb-9">
        <section class="dy-pp-hero relative isolate overflow-hidden rounded-[28px] border-b-4 border-[#D4A017] px-6 py-9 text-white shadow-sm sm:px-9" aria-labelledby="participation-heading">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[.24em] text-[#F2C94C]">DySign &middot; Student Account</p>
                    <h1 id="participation-heading" class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">My Participation Progress</h1>
                    <p class="mt-3 text-sm leading-6 text-indigo-100">Your event participation, verified attendance records, and progress toward completed eligible events.</p>
                    <div class="mt-5 inline-flex max-w-full flex-wrap items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-medium text-white/90">
                        <span class="h-2 w-2 rounded-full bg-[#F2C94C]"></span>
                        {{ $student->student_number }} &middot; {{ $student->program_code ?: 'Student' }}
                    </div>
                </div>
                <div class="flex shrink-0 flex-col gap-2 lg:items-end">
                    <a href="{{ route('student.participation-progress.pdf') }}" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#E5AE19] px-5 py-3 text-sm font-extrabold text-[#101064] shadow-sm transition hover:bg-[#F5C440] focus:outline-none focus:ring-4 focus:ring-amber-200" style="background-color:#E5AE19 !important; color:#101064 !important; border:2px solid #F3C34B; box-shadow:0 4px 12px rgba(0,0,0,0.18);" aria-label="Download complete participation report PDF">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12m0 0 5-5m-5 5-5-5M5 17v3h14v-3"/></svg>
                        Download Participation PDF
                    </a>
                    <span class="text-[11px] text-indigo-100/90">Full report &middot; All recorded attendance</span>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Participation summary">
            @foreach([
                ['label'=>'Events Attended','value'=>number_format($summary['attended']),'desc'=>'Completed eligible events marked Present or Late','tone'=>'#101064','icon'=>'check'],
                ['label'=>'Eligible Events','value'=>number_format($summary['eligible']),'desc'=>'Completed events for which you were eligible','tone'=>'#101064','icon'=>'calendar'],
                ['label'=>'Recorded Attendance','value'=>number_format($summary['recorded']),'desc'=>'Saved attendance entries across your events','tone'=>'#101064','icon'=>'list'],
                ['label'=>'Participation Rate','value'=>number_format($summary['percentage'],1).'%','desc'=>'Attended divided by completed eligible events','tone'=>'#C28C08','icon'=>'chart']
            ] as $metric)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold text-slate-500">{{ $metric['label'] }}</p>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-[#101064]">
                            @if($metric['icon']==='check')<svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><path d="m5 12 5 5L20 7"/></svg>
                            @elseif($metric['icon']==='calendar')<svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18"/></svg>
                            @elseif($metric['icon']==='list')<svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><path d="M9 6h12M9 12h12M9 18h12M4 6h1M4 12h1M4 18h1"/></svg>
                            @else<svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><path d="M4 19V5m0 14h17M7 14l4-4 4 3 5-7"/></svg>@endif
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold" style="color:{{ $metric['tone'] }}">{{ $metric['value'] }}</p>
                    <p class="mt-2 text-xs leading-5 text-slate-500">{{ $metric['desc'] }}</p>
                </div>
            @endforeach
        </section>

        <div class="grid gap-5 xl:grid-cols-5">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-3" aria-label="Participation overview">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#A9780B]">Participation Overview</p>
                        <h2 class="mt-1 text-lg font-extrabold text-[#101064]">Your progress at a glance</h2>
                        <p class="mt-1 text-xs text-slate-500">Based on completed events you were eligible to attend.</p>
                    </div>
                    <span class="rounded-xl bg-amber-50 px-3 py-2 text-xl font-extrabold text-[#B27D00]">{{ number_format($summary['percentage'], 1) }}%</span>
                </div>
                <div class="mt-7 h-4 overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-label="Participation rate" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $summary['percentage'] }}">
                    <div class="h-full rounded-full bg-[#D4A017]" style="width:{{ max(0,min(100,$summary['percentage'])) }}%"></div>
                </div>
                <div class="mt-4 flex flex-wrap justify-between gap-3 text-xs font-semibold text-slate-600">
                    <span><strong class="text-[#101064]">{{ $summary['attended'] }}</strong> events attended</span>
                    <span><strong class="text-[#101064]">{{ $summary['other'] }}</strong> other eligible completed events</span>
                </div>
                @if($summary['eligible'] === 0)
                    <p class="mt-4 rounded-xl bg-slate-50 px-4 py-3 text-xs text-slate-600">No completed eligible events are available yet.</p>
                @endif
            </section>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2" aria-label="Recorded attendance breakdown">
                <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#A9780B]">Attendance Breakdown</p>
                <h2 class="mt-1 text-lg font-extrabold text-[#101064]">Recorded statuses</h2>
                <div class="mt-5 grid grid-cols-2 gap-3">
                    @foreach(['Present'=>'emerald','Late'=>'amber','Checked In'=>'indigo','Incomplete'=>'rose','Absent'=>'slate','Excused'=>'violet'] as $status=>$color)
                        <div class="rounded-xl border border-slate-100 bg-slate-50/60 px-4 py-3">
                            <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full" style="background:{{ ['Present'=>'#059669','Late'=>'#D97706','Checked In'=>'#4F46E5','Incomplete'=>'#E11D48','Absent'=>'#64748b','Excused'=>'#7C3AED'][$status] }}"></span><span class="text-[11px] text-slate-600">{{ $status }}</span></div>
                            <p class="mt-1 text-xl font-extrabold text-[#101064]">{{ $summary['statuses'][$status] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="history-heading">
            <div class="border-b border-slate-100 bg-[#101064] px-6 py-5 text-white sm:px-7">
                <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#F2C94C]">Participation Records</p>
                <h2 id="history-heading" class="mt-1 text-xl font-extrabold">My Event Attendance</h2>
                <p class="mt-1 text-xs text-indigo-100">Search and filter your recorded attendance. Your downloadable report includes all records.</p>
            </div>
            <form method="GET" action="{{ route('student.participation-progress.index') }}" class="grid gap-3 border-b border-slate-100 bg-slate-50/60 p-5 sm:p-6 md:grid-cols-2 xl:grid-cols-6" aria-label="Filter attendance records">
                <div class="xl:col-span-2">
                    <label for="pp-search" class="mb-2 block text-xs font-bold text-slate-700">Search event or venue</label>
                    <input id="pp-search" class="dy-pp-field" type="search" name="search" maxlength="100" value="{{ $filters['search'] }}" placeholder="Enter event name...">
                </div>
                <div>
                    <label for="pp-status" class="mb-2 block text-xs font-bold text-slate-700">Status</label>
                    <select id="pp-status" class="dy-pp-field" name="status"><option value="all">All statuses</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected($filters['status']===$status)>{{ $status }}</option>@endforeach</select>
                </div>
                <div>
                    <label for="pp-from" class="mb-2 block text-xs font-bold text-slate-700">From date</label>
                    <input id="pp-from" class="dy-pp-field" type="date" name="from" value="{{ $filters['from'] ?? '' }}">
                </div>
                <div>
                    <label for="pp-to" class="mb-2 block text-xs font-bold text-slate-700">To date</label>
                    <input id="pp-to" class="dy-pp-field" type="date" name="to" value="{{ $filters['to'] ?? '' }}">
                </div>
                <div>
                    <label for="pp-sort" class="mb-2 block text-xs font-bold text-slate-700">Sort by</label>
                    <select id="pp-sort" class="dy-pp-field" name="sort"><option value="newest" @selected($filters['sort']==='newest')>Newest first</option><option value="oldest" @selected($filters['sort']==='oldest')>Oldest first</option></select>
                </div>
                <div class="md:col-span-2 xl:col-span-6 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2"><label for="pp-per-page" class="text-xs font-bold text-slate-700">Per page</label><select id="pp-per-page" class="dy-pp-field w-auto" name="per_page">@foreach([5,10,20] as $count)<option value="{{ $count }}" @selected($filters['per_page']===$count)>{{ $count }}</option>@endforeach</select></div>
                    <div class="flex gap-2"><a href="{{ route('student.participation-progress.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-[#101064] hover:bg-slate-100">Reset</a><button type="submit" class="min-h-11 rounded-xl bg-[#101064] px-5 py-2 text-xs font-extrabold text-white hover:bg-[#242481]">Apply Filters</button></div>
                </div>
            </form>
            @if($errors->any())<div class="m-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-800" role="alert">{{ $errors->first() }}</div>@endif
            <div class="flex flex-wrap items-center justify-between gap-2 px-6 py-4 text-xs text-slate-500"><span><strong class="text-[#101064]">{{ $attendanceRecords->total() }}</strong> matching records</span><span>Showing {{ $attendanceRecords->firstItem() ?? 0 }}-{{ $attendanceRecords->lastItem() ?? 0 }} of {{ $attendanceRecords->total() }}</span></div>
            <div class="overflow-x-auto">
                <table class="dy-pp-table">
                    <thead><tr><th scope="col">Event</th><th scope="col">Event Date</th><th scope="col">Time In</th><th scope="col">Time Out</th><th scope="col">Status</th></tr></thead>
                    <tbody>
                        @forelse($attendanceRecords as $record)
                            <tr>
                                <td><p class="font-bold text-[#101064]">{{ $record->event?->event_name ?? 'Event unavailable' }}</p><p class="mt-1 max-w-[280px] truncate text-xs text-slate-400">{{ $record->event?->location ?: 'Campus event' }}</p></td>
                                <td class="whitespace-nowrap">{{ $record->event?->event_date?->format('M d, Y') ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap">{{ $record->time_in?->format('g:i A') ?? 'Not recorded' }}</td>
                                <td class="whitespace-nowrap">{{ $record->time_out?->format('g:i A') ?? 'Not recorded' }}</td>
                                <td>
                                    @php $statusStyles = ['Present'=>'background:#ecfdf5;color:#047857','Late'=>'background:#fffbeb;color:#b45309','Checked In'=>'background:#eef2ff;color:#4338ca','Incomplete'=>'background:#fff1f2;color:#be123c','Absent'=>'background:#f1f5f9;color:#475569','Excused'=>'background:#f5f3ff;color:#6d28d9']; @endphp
                                    <span class="dy-pp-pill" style="{{ $statusStyles[$record->status] ?? 'background:#f1f5f9;color:#475569' }}">{{ $record->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-12 text-center"><p class="font-bold text-slate-600">No matching attendance records</p><p class="mt-1 text-xs text-slate-400">Try adjusting your search or filters.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($attendanceRecords->hasPages())
                <div class="dy-pp-steps border-t border-slate-100 px-6 py-5">{{ $attendanceRecords->onEachSide(1)->links() }}</div>
            @endif
            <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-4 text-xs leading-5 text-slate-500">Only finalized <strong>Present</strong> and <strong>Late</strong> records count as attended in the participation percentage. Pending, incomplete and excused records are displayed without inventing scan timestamps.</div>
        </section>
    </div>
</x-admin-layout>
