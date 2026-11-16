<x-admin-layout>
<style>
.pa-page{--pa-navy:#101064;--pa-gold:#e3ad16;--pa-ink:#101654;--pa-muted:#60708b;--pa-line:#dde4f1;color:var(--pa-ink);display:grid;gap:20px;font-family:inherit}
.pa-hero{position:relative;overflow:hidden;border-radius:24px;padding:32px 36px;color:white;background:linear-gradient(100deg,rgba(13,15,86,.96),rgba(17,20,102,.84) 60%,rgba(15,23,79,.70)),url('{{ asset('images/school.jpg') }}') center/cover;box-shadow:0 4px 10px #10106410}
.pa-hero:after{content:"";position:absolute;bottom:0;left:0;width:100%;height:4px;background:var(--pa-gold)}
.pa-eyebrow{font-size:11px;letter-spacing:.21em;text-transform:uppercase;font-weight:800;color:#f4cb48}
.pa-hero h1{font-size:clamp(26px,3vw,35px);font-weight:850;line-height:1.13;margin:12px 0}
.pa-hero p{font-size:13px;line-height:1.7;max-width:650px;color:#e6e9ff}
.pa-hero .pa-hero-note{display:inline-flex;align-items:center;gap:8px;background:#ffffff20;border:1px solid #ffffff35;border-radius:40px;padding:8px 13px;margin-top:15px;font-size:11px;color:white}
.pa-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
.pa-stat,.pa-panel,.pa-period,.pa-person{background:white;border:1px solid var(--pa-line);border-radius:16px;box-shadow:0 2px 4px #11134f08}
.pa-stat{padding:20px 21px;min-width:0}.pa-stat small{display:block;color:var(--pa-muted);font-size:12px}.pa-stat strong{display:block;font-size:30px;color:var(--pa-navy);margin:7px 0 2px;line-height:1.1;font-weight:850}.pa-stat span{font-size:11px;color:var(--pa-muted)}
.pa-panel{overflow:hidden}.pa-head{padding:20px 22px 16px;border-bottom:1px solid #edf0f6;display:flex;align-items:start;justify-content:space-between;gap:18px}.pa-head h2{font-size:20px;font-weight:800;color:var(--pa-navy);margin:4px 0}.pa-head p{font-size:12px;color:var(--pa-muted);line-height:1.55}.pa-head .pa-small{border-radius:20px;padding:7px 10px;background:#f4f6fc;border:1px solid var(--pa-line);font-size:11px;white-space:nowrap;color:var(--pa-navy);font-weight:700}
.pa-filter{padding:18px 21px;border-bottom:1px solid #edf0f6;display:grid;grid-template-columns:1.4fr repeat(2,minmax(130px,.8fr)) repeat(3,minmax(120px,.72fr));gap:12px;align-items:end}.pa-field{min-width:0}.pa-field label{display:block;font-size:11px;font-weight:800;color:#354363;margin-bottom:7px}.pa-field input,.pa-field select{width:100%;min-width:0;background:white;outline:0;border:1px solid #cfdaeb;border-radius:10px;padding:10px 11px;font-size:12px;color:var(--pa-ink);height:41px}.pa-field input:focus,.pa-field select:focus{border-color:var(--pa-navy);box-shadow:0 0 0 2px #10106416}.pa-actions{grid-column:1/-1;display:flex;gap:9px;align-items:center;justify-content:flex-end}.pa-btn{display:inline-flex;align-items:center;justify-content:center;border-radius:9px;font-size:12px;font-weight:800;min-height:39px;padding:9px 15px;text-decoration:none;white-space:nowrap;cursor:pointer}.pa-primary{color:white;background:var(--pa-navy);border:1px solid var(--pa-navy)}.pa-outline{background:white;color:var(--pa-navy);border:1px solid #cdd7e9}.pa-primary:hover{background:#25258e}.pa-outline:hover{background:#f5f7ff}
.pa-alert{padding:13px 17px;border:1px solid #f4dfac;background:#fffaf0;color:#775013;border-radius:12px;font-size:12px;line-height:1.65}.pa-error{padding:13px 18px;color:#9b1c1c;background:#fff0f0;border:1px solid #ffd0d0;border-radius:12px;font-size:12px}
.pa-result-top{padding:15px 22px;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;font-size:12px;color:var(--pa-muted)}.pa-result-top strong{color:var(--pa-navy)}.pa-periods{padding:0 18px 16px;display:grid;gap:10px}.pa-period{display:grid;grid-template-columns:155px minmax(0,1.25fr) minmax(0,1.4fr);gap:15px;align-items:center;padding:17px 19px;background:#fff;box-shadow:none}.pa-period-date{border-right:1px solid #ebedf4;padding-right:16px}.pa-period-date strong{display:block;font-size:15px;color:var(--pa-navy)}.pa-period-date span{display:block;font-size:11px;color:var(--pa-muted);margin-top:5px}.pa-name{font-size:14px;font-weight:800;color:var(--pa-navy);line-height:1.3}.pa-detail{font-size:11px;color:var(--pa-muted);margin-top:4px;line-height:1.5}.pa-time{font-size:15px;font-weight:800;color:#9a5213}.pa-reason{font-size:12px;color:#4e5b70;margin-top:6px;line-height:1.5;overflow-wrap:anywhere}.pa-red{display:inline-flex;color:#a43c30;background:#fff0e9;border:1px solid #f9dfd4;border-radius:20px;padding:5px 8px;font-size:10px;font-weight:800;margin-left:7px}.pa-quiet{font-size:11px;color:#71809a}
.pa-pagebar{display:flex;justify-content:space-between;align-items:center;gap:14px;padding:15px 21px 19px;border-top:1px solid #edf0f6;flex-wrap:wrap;color:var(--pa-muted);font-size:12px}.pa-pagebuttons{display:flex;gap:6px;align-items:center}.pa-pagebuttons a,.pa-pagebuttons span{display:inline-flex;justify-content:center;align-items:center;min-width:36px;min-height:34px;border:1px solid #d7dfec;border-radius:9px;text-decoration:none;color:var(--pa-navy);padding:5px 9px;font-weight:750}.pa-pagebuttons a:hover{background:#f4f5ff}.pa-pagebuttons .pa-disabled{color:#a8b1c3;background:#f8f9fc}.pa-pagebuttons .pa-current{background:var(--pa-navy);border-color:var(--pa-navy);color:white}
.pa-empty{padding:45px 23px;text-align:center}.pa-empty strong{display:block;font-size:15px;color:var(--pa-navy);margin-bottom:8px}.pa-empty p{max-width:490px;margin:auto;line-height:1.6;font-size:12px;color:var(--pa-muted)}
.pa-date-check{padding:18px 21px;display:flex;align-items:end;gap:12px;flex-wrap:wrap}.pa-date-check .pa-field{min-width:220px}.pa-date-results{padding:0 19px 17px;display:grid;gap:9px}.pa-person{padding:16px 18px;display:flex;justify-content:space-between;align-items:start;gap:13px;box-shadow:none}.pa-person-right{text-align:right;max-width:52%}.pa-person-right strong{font-size:11px;font-weight:800}.pa-conflict{color:#9b3c29}.pa-clear{color:#087654}.pa-slot{font-size:11px;color:#6b758b;margin-top:5px;line-height:1.55}
@media(max-width:1150px){.pa-filter{grid-template-columns:repeat(3,minmax(0,1fr))}.pa-period{grid-template-columns:135px minmax(0,1fr) minmax(0,1fr)}}
@media(max-width:700px){.pa-page{gap:14px}.pa-hero{padding:26px 20px;border-radius:18px}.pa-stats{grid-template-columns:1fr 1fr;gap:9px}.pa-stat{padding:15px}.pa-stat strong{font-size:25px}.pa-stats .pa-stat:last-child{grid-column:1/-1}.pa-head{padding:17px}.pa-head h2{font-size:18px}.pa-filter{padding:16px;grid-template-columns:1fr 1fr}.pa-field:first-child{grid-column:1/-1}.pa-actions{justify-content:stretch}.pa-actions .pa-btn{flex:1}.pa-periods{padding:0 11px 12px}.pa-period{grid-template-columns:1fr;gap:8px;padding:15px}.pa-period-date{border-right:0;border-bottom:1px solid #eef0f6;padding:0 0 9px}.pa-date-check{padding:16px}.pa-date-check .pa-field{min-width:0;width:100%}.pa-date-check .pa-btn{flex:1}.pa-result-top{padding:15px}.pa-pagebar{padding:15px}.pa-person{flex-direction:column}.pa-person-right{text-align:left;max-width:100%}}
</style>
<div class="pa-page">
    <section class="pa-hero">
        <div class="pa-eyebrow">Department Staff · Personnel Coordination</div>
        <h1>Personnel Availability</h1>
        <p>See upcoming unavailable dates and time windows before assigning Attendance Personnel to an event.</p>
        <span class="pa-hero-note">View only · Your department and school-wide personnel</span>
    </section>

    @if($errors->any())
        <div class="pa-error" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="pa-stats">
        <div class="pa-stat"><small>Unavailable periods in view</small><strong>{{ number_format($periodCount) }}</strong><span>Declared periods matching the selected filters</span></div>
        <div class="pa-stat"><small>Personnel with unavailable periods</small><strong>{{ number_format($peopleWithPeriods) }}</strong><span>Distinct personnel in these results</span></div>
        <div class="pa-stat"><small>Personnel in your scope</small><strong>{{ number_format($personnelCount) }}</strong><span>Active, eligible Attendance Personnel</span></div>
    </div>

    <section class="pa-panel" id="upcoming-periods">
        <header class="pa-head">
            <div><div class="pa-eyebrow" style="color:#ba8700">Schedule overview</div><h2>Declared Unavailable Periods</h2><p>Upcoming periods appear automatically. Search or change the dates to find a specific schedule.</p></div>
            <span class="pa-small">{{ $scope === 'upcoming' ? 'Upcoming' : ($scope === 'past' ? 'Past' : 'All dates') }}</span>
        </header>
        <form class="pa-filter" method="GET" action="{{ route('personnel-availability.staff') }}">
            @if($checkDate !== '') <input type="hidden" name="check_date" value="{{ $checkDate }}"> @endif
            <div class="pa-field"><label for="pa-search">Search personnel</label><input id="pa-search" type="search" name="search" value="{{ $search }}" placeholder="Name or position"></div>
            <div class="pa-field"><label for="pa-from">From date</label><input id="pa-from" type="date" name="from" value="{{ $from }}"></div>
            <div class="pa-field"><label for="pa-to">To date</label><input id="pa-to" type="date" name="to" value="{{ $to }}"></div>
            <div class="pa-field"><label for="pa-scope">Schedule</label><select id="pa-scope" name="scope"><option value="upcoming" @selected($scope==='upcoming')>Upcoming</option><option value="past" @selected($scope==='past')>Past records</option><option value="all" @selected($scope==='all')>All dates</option></select></div>
            <div class="pa-field"><label for="pa-kind">Personnel scope</label><select id="pa-kind" name="kind"><option value="all" @selected($kind==='all')>All authorized</option><option value="department" @selected($kind==='department')>My department</option><option value="schoolwide" @selected($kind==='schoolwide')>School-wide</option></select></div>
            <div class="pa-field"><label for="pa-sort">Sort</label><select id="pa-sort" name="sort"><option value="soonest" @selected($sort==='soonest')>Earliest first</option><option value="latest" @selected($sort==='latest')>Latest first</option></select></div>
            <div class="pa-field"><label for="pa-per-page">Rows per page</label><select id="pa-per-page" name="per_page"><option value="5" @selected($perPage===5)>5 records</option><option value="10" @selected($perPage===10)>10 records</option><option value="20" @selected($perPage===20)>20 records</option></select></div>
            <div class="pa-actions"><button class="pa-btn pa-primary" type="submit">Apply Filters</button><a class="pa-btn pa-outline" href="{{ route('personnel-availability.staff') }}">Reset</a></div>
        </form>
        <div class="pa-result-top"><span><strong>{{ number_format($periodCount) }}</strong> matching unavailable {{ \Illuminate\Support\Str::plural('period',$periodCount) }}</span><span>{{ $periods->total() ? 'Showing '.$periods->firstItem().'–'.$periods->lastItem() : 'No matching schedules' }}</span></div>
        <div class="pa-periods">
            @forelse($periods as $period)
                <article class="pa-period">
                    <div class="pa-period-date"><strong>{{ \Carbon\Carbon::parse($period->availability_date)->format('M d, Y') }}</strong><span>{{ \Carbon\Carbon::parse($period->availability_date)->format('l') }}</span></div>
                    <div><div class="pa-name">{{ $period->personnel?->first_name }} {{ $period->personnel?->last_name }}</div><div class="pa-detail">{{ $period->personnel?->position ?: 'Attendance Personnel' }} · {{ $period->personnel?->user?->department?->department_name ?? 'School-wide Personnel' }}</div></div>
                    <div><div><span class="pa-time">{{ \Carbon\Carbon::parse($period->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($period->end_time)->format('g:i A') }}</span><span class="pa-red">Unavailable</span></div><div class="pa-reason">{{ $period->reason ?: 'No reason provided' }}</div></div>
                </article>
            @empty
                <div class="pa-empty"><strong>No declared unavailable periods match</strong><p>Try expanding the date range or selecting All dates. An empty list means no unavailability was declared in this scope; it does not verify an entire event's schedule.</p></div>
            @endforelse
        </div>
        @if($periods->lastPage()>1)
        <nav class="pa-pagebar" aria-label="Unavailable periods pages"><span>Page {{ $periods->currentPage() }} of {{ $periods->lastPage() }}</span><div class="pa-pagebuttons">
            @if($periods->onFirstPage()) <span class="pa-disabled">Previous</span> @else <a href="{{ $periods->previousPageUrl() }}">Previous</a> @endif
            @foreach(range(max(1,$periods->currentPage()-2),min($periods->lastPage(),$periods->currentPage()+2)) as $pageNumber)
                @if($pageNumber===$periods->currentPage()) <span class="pa-current">{{ $pageNumber }}</span> @else <a href="{{ $periods->url($pageNumber) }}">{{ $pageNumber }}</a> @endif
            @endforeach
            @if($periods->hasMorePages()) <a href="{{ $periods->nextPageUrl() }}">Next</a> @else <span class="pa-disabled">Next</span> @endif
        </div></nav>
        @endif
    </section>

    <section class="pa-panel" id="date-check">
        <header class="pa-head"><div><div class="pa-eyebrow" style="color:#ba8700">Date-specific check</div><h2>Who's Unavailable on a Date?</h2><p>Choose a date to see each person's declared unavailable time windows.</p></div><span class="pa-small">View only</span></header>
        <form class="pa-date-check" method="GET" action="{{ route('personnel-availability.staff') }}#date-check">
            <input type="hidden" name="search" value="{{ $search }}"><input type="hidden" name="scope" value="{{ $scope }}"><input type="hidden" name="kind" value="{{ $kind }}"><input type="hidden" name="from" value="{{ $from }}"><input type="hidden" name="to" value="{{ $to }}"><input type="hidden" name="sort" value="{{ $sort }}"><input type="hidden" name="per_page" value="{{ $perPage }}">
            <div class="pa-field"><label for="pa-check-date">Schedule date</label><input id="pa-check-date" type="date" name="check_date" value="{{ $checkDate !== '' ? $checkDate : $today }}" required></div>
            <button class="pa-btn pa-primary" type="submit">Check Date</button>
            @if($checkDate !== '') <a class="pa-btn pa-outline" href="{{ route('personnel-availability.staff', request()->except(['check_date','people_page','page'])) }}#date-check">Clear Date</a> @endif
        </form>
        @if($checkDate !== '')
            <div class="pa-result-top"><span><strong>{{ number_format($dailyConflictCount) }}</strong> personnel with declared unavailability on {{ \Carbon\Carbon::parse($checkDate)->format('F d, Y') }}</span><span>{{ $dailyPeople->total() }} personnel in the filtered roster</span></div>
            <div class="pa-date-results">
                @forelse($dailyPeople as $person)
                    <article class="pa-person"><div><div class="pa-name">{{ $person->first_name }} {{ $person->last_name }}</div><div class="pa-detail">{{ $person->position ?: 'Attendance Personnel' }} · {{ $person->user?->department?->department_name ?? 'School-wide Personnel' }}</div></div><div class="pa-person-right">
                    @if($person->availabilities->isNotEmpty())
                        <strong class="pa-conflict">Unavailable · {{ $person->availabilities->count() }} {{ \Illuminate\Support\Str::plural('period',$person->availabilities->count()) }}</strong>
                        @foreach($person->availabilities as $window)
                            <div class="pa-slot">{{ \Carbon\Carbon::parse($window->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($window->end_time)->format('g:i A') }}{{ $window->reason ? ' · '.$window->reason : '' }}</div>
                        @endforeach
                    @else
                        <strong class="pa-clear">No declared unavailability</strong><div class="pa-slot">Confirm the actual event schedule before assignment.</div>
                    @endif
                    </div></article>
                @empty
                    <div class="pa-empty"><strong>No matching personnel</strong><p>Try adjusting the personnel search or scope filter.</p></div>
                @endforelse
            </div>
            @if($dailyPeople->lastPage()>1)
                <nav class="pa-pagebar" aria-label="Personnel roster pages"><span>Showing {{ $dailyPeople->firstItem() }}–{{ $dailyPeople->lastItem() }} of {{ $dailyPeople->total() }}</span><div class="pa-pagebuttons">
                    @if($dailyPeople->onFirstPage()) <span class="pa-disabled">Previous</span> @else <a href="{{ $dailyPeople->previousPageUrl() }}#date-check">Previous</a> @endif
                    <span class="pa-current">{{ $dailyPeople->currentPage() }} / {{ $dailyPeople->lastPage() }}</span>
                    @if($dailyPeople->hasMorePages()) <a href="{{ $dailyPeople->nextPageUrl() }}#date-check">Next</a> @else <span class="pa-disabled">Next</span> @endif
                </div></nav>
            @endif
        @else
            <div class="pa-alert" style="margin:0 20px 20px">The schedule above already lists declared upcoming unavailable dates. Use this optional date check to compare personnel on a specific day.</div>
        @endif
    </section>
    <p class="pa-quiet">Scheduling note: Unavailable time windows are declarations from Attendance Personnel. Availability for an event depends on whether any declared window overlaps the actual event schedule.</p>
</div>
</x-admin-layout>
