<x-admin-layout>
    @php
        $totalParticipants = (int) $participantCounts->sum();
        $requiredCount = (int) ($participantCounts['Required'] ?? 0);
        $optionalCount = (int) ($participantCounts['Optional'] ?? 0);
        $criteriaCount = $rules->where('rule_type', 'Criteria')->count();
        $specificCount = $rules->where('rule_type', 'Specific')->count();
    @endphp

    <style>
        .dy-elig-detail-hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background: linear-gradient(102deg, rgba(13,14,88,.97), rgba(16,16,100,.91) 54%, rgba(16,16,100,.70)),
                url('{{ asset('images/school.jpg') }}') center / cover;
        }
        .dy-elig-detail-ring {
            position:absolute; width: 408px; height: 408px; top:-150px; right:-42px;
            border:2px solid rgba(255,255,255,.20); border-radius:100%; pointer-events:none;
        }
        .dy-elig-detail-ring:nth-child(2) { width: 300px; height: 300px; top:-96px; right:12px; border-color:rgba(255,255,255,.17); }
        .dy-elig-detail-ring:nth-child(3) { width: 196px; height: 196px; top:-42px; right:65px; border-color:rgba(255,255,255,.14); }
        .dy-elig-detail-input {
            width:100%; min-height:46px; padding:10px 13px; border:1px solid #e2e8f0;
            border-radius:12px; background:#fff; color:#101064; font-size:13px;
        }
        .dy-elig-detail-input:focus { outline:none; border-color:#101064; box-shadow:0 0 0 3px rgba(16,16,100,.08); }
        @media(max-width:640px) { .dy-elig-detail-ring { transform:translateX(170px); } }
    </style>

    <div class="min-w-0 space-y-7 pb-9">
        <section class="dy-elig-detail-hero rounded-[28px] text-white">
            <div class="absolute inset-0 z-0" aria-hidden="true">
                <span class="dy-elig-detail-ring"></span><span class="dy-elig-detail-ring"></span><span class="dy-elig-detail-ring"></span>
            </div>
            <div class="absolute inset-x-0 bottom-0 z-20 h-1 bg-[#D4A017]"></div>
            <div class="relative z-10 flex min-h-[205px] flex-wrap items-center justify-between gap-6 px-7 py-9 sm:px-9 lg:px-10">
                <div class="min-w-0 max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[.27em] text-[#F0C65B]">Participant Eligibility · Institutional Review</p>
                    <h1 class="mt-3 break-words text-2xl font-bold tracking-tight sm:text-3xl">{{ $event->event_name }}</h1>
                    <p class="mt-3 text-sm leading-6 text-white/80">Review configured participation rules and the saved eligible-student roster. This administrator view cannot change eligibility.</p>
                    <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 text-xs text-white/80">
                        <span>Event #{{ $event->event_id }}</span>
                        <span>{{ $event->department?->department_name ?? 'University-wide' }}</span>
                        <span>{{ $event->status }}</span>
                    </div>
                </div>
                <div class="flex flex-col gap-3 sm:items-end">
                    <a href="{{ route('events.eligibility.events') }}" class="inline-flex items-center justify-center rounded-xl bg-[#D4A017] px-5 py-3 text-xs font-bold text-[#101064] hover:bg-[#E8B735]">← All Events</a>
                    <span class="text-center text-xs text-white/80">Administrator · Read Only</span>
                </div>
            </div>
        </section>

        <section aria-label="Event context" class="grid gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-2 xl:grid-cols-4">
            <div><p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Event date</p><p class="mt-2 text-sm font-semibold text-[#101064]">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p></div>
            <div><p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Event time</p><p class="mt-2 text-sm font-semibold text-[#101064]">{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</p></div>
            <div><p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Venue</p><p class="mt-2 break-words text-sm font-semibold text-[#101064]">{{ $event->location ?: 'Not specified' }}</p></div>
            <div><p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Eligibility access</p><p class="mt-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">View Only</p></div>
        </section>

        <section aria-labelledby="admin-elig-summary">
            <div class="mb-4">
                <p class="text-[11px] font-bold uppercase tracking-[.24em] text-[#B8890F]">Participation Overview</p>
                <h2 id="admin-elig-summary" class="mt-1.5 text-lg font-bold text-[#101064]">Eligible Student Summary</h2>
                <p class="mt-1 text-xs text-slate-500">Totals are based on the saved roster for this event, not just the displayed page.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                @foreach([
                    ['title' => 'Eligible Students', 'value' => $totalParticipants, 'color' => '#101064'],
                    ['title' => 'Required', 'value' => $requiredCount, 'color' => '#DC2626'],
                    ['title' => 'Optional', 'value' => $optionalCount, 'color' => '#047857'],
                    ['title' => 'Criteria Rules', 'value' => $criteriaCount, 'color' => '#B8890F'],
                    ['title' => 'Specific Rules', 'value' => $specificCount, 'color' => '#475569'],
                ] as $stat)
                    <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">{{ $stat['title'] }}</p>
                        <p class="mt-2 text-3xl font-bold" style="color: {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                        <div class="mt-3 border-t border-slate-100"></div>
                    </div>
                @endforeach
            </div>
        </section>

        <section aria-labelledby="admin-elig-rules">
            <div class="mb-4">
                <p class="text-[11px] font-bold uppercase tracking-[.24em] text-[#B8890F]">Configuration Review</p>
                <h2 id="admin-elig-rules" class="mt-1.5 text-lg font-bold text-[#101064]">Current Eligibility Rules</h2>
                <p class="mt-1 text-xs text-slate-500">Rule definitions maintained by the authorized event staff. Changes cannot be made from this page.</p>
            </div>
            <div class="grid gap-3 lg:grid-cols-2">
                @forelse($rules as $rule)
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="text-xs font-bold text-[#101064]">{{ $rule->rule_type === 'Specific' ? 'Individual Student' : 'Group Criteria' }}</span>
                            <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $rule->participation_type === 'Required' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700' }}">{{ $rule->participation_type }}</span>
                        </div>
                        @if($rule->rule_type === 'Specific')
                            <p class="mt-3 text-sm font-medium text-slate-700">{{ trim(($rule->student?->last_name ?? '') . ', ' . ($rule->student?->first_name ?? '')) ?: 'Student record unavailable' }}</p>
                            <p class="mt-1 text-xs text-slate-500">Student number: {{ $rule->student?->student_number ?? 'Not available' }}</p>
                        @else
                            <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600">
                                <span class="rounded-lg bg-slate-50 px-3 py-2">{{ $rule->college ?: 'All Colleges' }}</span>
                                <span class="rounded-lg bg-slate-50 px-3 py-2">{{ $rule->program_code ?: 'All Programs' }}</span>
                                <span class="rounded-lg bg-slate-50 px-3 py-2">{{ $rule->year_level ? 'Year '.$rule->year_level : 'All Year Levels' }}</span>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="rounded-2xl border border-slate-200 bg-white px-6 py-10 text-center text-sm text-slate-500 lg:col-span-2">No eligibility rules have been configured for this event.</div>
                @endforelse
            </div>
        </section>

        <section aria-labelledby="admin-elig-students">
            <div class="mb-4 flex flex-wrap items-end justify-between gap-2">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[.24em] text-[#B8890F]">Student Roster</p>
                    <h2 id="admin-elig-students" class="mt-1.5 text-lg font-bold text-[#101064]">Eligible Participants</h2>
                    <p class="mt-1 text-xs text-slate-500">Read-only snapshot of the generated participant list.</p>
                </div>
                <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-[#101064]">{{ $totalParticipants }} total eligible</span>
            </div>
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <form method="GET" action="{{ route('events.eligibility.index', $event->event_id) }}" class="grid gap-3 border-b border-slate-100 p-4 sm:grid-cols-[minmax(0,1fr)_180px_auto]">
                    <label>
                        <span class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Find a student</span>
                        <input type="search" name="search" value="{{ $search }}" maxlength="120" placeholder="Name or student number" class="dy-elig-detail-input" />
                    </label>
                    <label>
                        <span class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Participation</span>
                        <select name="participation" class="dy-elig-detail-input">
                            <option value="all" @selected($participation === 'all')>All Types</option>
                            <option value="Required" @selected($participation === 'Required')>Required</option>
                            <option value="Optional" @selected($participation === 'Optional')>Optional</option>
                        </select>
                    </label>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="min-h-[46px] rounded-xl bg-[#101064] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#202075]">Filter</button>
                        <a href="{{ route('events.eligibility.index', $event->event_id) }}" class="inline-flex min-h-[46px] items-center rounded-xl border border-slate-200 px-3 text-xs font-semibold text-slate-600">Reset</a>
                    </div>
                </form>
                <div class="overflow-x-auto">
                    <table class="min-w-[760px] w-full text-left">
                        <thead class="border-b border-slate-200 bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-3.5">Student</th><th class="px-5 py-3.5">Student Number</th>
                                <th class="px-5 py-3.5">College / Program</th><th class="px-5 py-3.5">Year</th>
                                <th class="px-5 py-3.5">Participation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($eligibleStudents as $record)
                                @php($student = $record->student)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-4 text-sm font-semibold text-[#101064]">{{ trim(($student?->first_name ?? '') . ' ' . ($student?->middle_name ?? '') . ' ' . ($student?->last_name ?? '')) ?: 'Record unavailable' }}</td>
                                    <td class="px-5 py-4 text-xs text-slate-600">{{ $student?->student_number ?? '—' }}</td>
                                    <td class="px-5 py-4"><p class="text-xs font-semibold text-slate-700">{{ $student?->college ?? '—' }}</p><p class="mt-1 text-xs text-slate-400">{{ $student?->program_code ?? '—' }}</p></td>
                                    <td class="px-5 py-4 text-xs text-slate-600">{{ $student?->year_level ?? '—' }}</td>
                                    <td class="px-5 py-4"><span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $record->participation_type === 'Required' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700' }}">{{ $record->participation_type }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-14 text-center text-sm text-slate-500">{{ $totalParticipants ? 'No students match the selected filters.' : 'No eligible students have been generated for this event.' }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 px-5 py-4">
                    <p class="text-xs text-slate-500">Showing {{ $eligibleStudents->count() ? $eligibleStudents->firstItem().'–'.$eligibleStudents->lastItem() : '0' }} of {{ $eligibleStudents->total() }} matching students</p>
                    @if($eligibleStudents->hasPages())<div class="min-w-0">{{ $eligibleStudents->links() }}</div>@endif
                </div>
            </div>
        </section>
    </div>
</x-admin-layout>
