<x-admin-layout>
<style>
    .dm-page { --dm-navy:#101064; --dm-gold:#D4A017; --dm-muted:#64748b; color:#111b46; width:100%; min-width:0; padding-bottom:2rem; }
    .dm-page * {box-sizing:border-box}
    .dm-page .dm-hero {position:relative; isolation:isolate; overflow:hidden; border-radius:23px; padding:32px 34px; color:#fff;
        background:linear-gradient(95deg,rgba(16,16,100,.98) 0%,rgba(16,16,100,.90) 54%,rgba(16,16,100,.77) 100%),url('{{ asset('images/school.jpg') }}') center / cover no-repeat; box-shadow:0 2px 3px rgba(16,16,100,.09); }
    .dm-page .dm-hero:after {content:'';position:absolute;bottom:0;left:0;right:0;height:4px;background:var(--dm-gold)}
    .dm-page .dm-eyebrow{font-size:10px;letter-spacing:.21em;text-transform:uppercase;font-weight:800;color:#e8bb42;margin:0 0 10px}
    .dm-page .dm-hero h1{font-size:clamp(25px,3vw,34px);font-weight:800;line-height:1.15;letter-spacing:-.04em;margin:0 0 10px}
    .dm-page .dm-hero p:not(.dm-eyebrow){max-width:730px;font-size:13px;line-height:1.7;color:#e5e9ff;margin:0}
    .dm-page .dm-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:13px;margin-top:18px}
    .dm-page .dm-stat,.dm-page .dm-panel{background:#fff;border:1px solid #e1e7f2;border-radius:16px;box-shadow:0 2px 3px rgba(16,16,100,.035)}
    .dm-page .dm-stat{padding:20px 20px 18px;position:relative;min-height:116px}
    .dm-page .dm-stat:before{content:'';display:block;position:absolute;top:0;left:20px;right:20px;height:3px;background:#e9b728;border-radius:0 0 3px 3px}
    .dm-page .dm-stat .label{font-size:11px;font-weight:700;color:#536b8e;margin-bottom:9px}
    .dm-page .dm-stat .value{font-size:29px;font-weight:850;letter-spacing:-.035em;line-height:1.1;color:#101064}
    .dm-page .dm-stat .hint{font-size:11px;color:#64748b;margin-top:7px}
    .dm-page .dm-panel{margin-top:17px;overflow:hidden}
    .dm-page .dm-panel-pad{padding:20px 22px}
    .dm-page h2{font-weight:800;font-size:17px;color:var(--dm-navy);letter-spacing:-.015em;margin:0}
    .dm-page .dm-sub{font-size:12px;color:var(--dm-muted);margin-top:5px;line-height:1.5}
    .dm-page .dm-pill{display:inline-flex;border-radius:999px;background:#f1f5fb;color:#101064;font-size:10px;font-weight:750;padding:5px 10px;white-space:nowrap}
    .dm-page .dm-bar{display:flex;overflow:hidden;border-radius:999px;height:12px;background:#f1f5f9;margin-top:15px}
    .dm-page .dm-bar>span{height:100%;display:block;min-width:0}
    .dm-page .dm-legend{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px;margin-top:13px}
    .dm-page .dm-legend-item{display:flex;align-items:center;justify-content:space-between;gap:5px;font-size:11px;color:#50627e}
    .dm-page .dm-dot{display:inline-block;width:8px;height:8px;flex:none;border-radius:50%;margin-right:5px}
    .dm-page .dm-filter-title{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;padding:18px 22px;border-bottom:1px solid #e9edf5}
    .dm-page .dm-export-buttons{display:flex;gap:8px;flex-wrap:wrap}
    .dm-page .dm-btn{display:inline-flex;justify-content:center;align-items:center;gap:6px;min-height:39px;padding:9px 15px;border-radius:10px;font-weight:800;font-size:12px;text-decoration:none;white-space:nowrap;border:1px solid transparent;transition:filter .15s ease}
    .dm-page .dm-btn:hover{filter:brightness(1.08)}
    .dm-page .dm-btn.navy{background:#101064!important;color:#fff!important}
    .dm-page .dm-btn.gold{background:#E5AE19!important;color:#101064!important}
    .dm-page .dm-btn.light{background:#fff!important;color:#101064!important;border-color:#cfd8e8}
    .dm-page .dm-filters{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:12px;padding:20px 22px}
    .dm-page .dm-field{min-width:0;grid-column:span 2}
    .dm-page .dm-field.wide{grid-column:span 3}
    .dm-page .dm-field.small{grid-column:span 1}
    .dm-page .dm-field label{display:block;font-size:11px;font-weight:750;color:#364d75;margin-bottom:6px}
    .dm-page .dm-field input,.dm-page .dm-field select{display:block;width:100%;height:41px;background:#fff;color:#10164c;border:1px solid #cdd7e8;border-radius:10px;padding:8px 11px;font-size:12px;min-width:0}
    .dm-page .dm-field input:focus,.dm-page .dm-field select:focus{border-color:#101064;outline:2px solid #cfd6ff;outline-offset:0}
    .dm-page .dm-filter-actions{grid-column:1/-1;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;border-top:1px solid #edf1f7;padding-top:14px}
    .dm-page .dm-actions{display:flex;gap:8px;align-items:center}
    .dm-page .dm-note{font-size:11px;color:#667999;line-height:1.45}
    .dm-page .dm-records-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:19px 21px;border-bottom:1px solid #e9edf5}
    .dm-page .dm-table-wrap{width:100%;overflow-x:auto}
    .dm-page table{border-collapse:collapse;width:100%;min-width:990px;table-layout:auto}
    .dm-page thead{background:#f6f8fc}
    .dm-page th{font-size:10px;letter-spacing:.06em;text-transform:uppercase;text-align:left;color:#607492;padding:13px 16px;font-weight:800;white-space:nowrap}
    .dm-page td{padding:15px 16px;border-top:1px solid #ebeff6;vertical-align:top;font-size:12px;color:#233651}
    .dm-page tbody tr:hover{background:#fafbfe}
    .dm-page .dm-primary{font-weight:750;color:#101064;font-size:12px}
    .dm-page .dm-secondary{color:#64748b;font-size:11px;margin-top:5px}
    .dm-page .dm-status{display:inline-block;padding:5px 9px;border-radius:999px;font-size:10px;font-weight:800;white-space:nowrap}
    .dm-page .dm-pagination{padding:16px 21px;display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;border-top:1px solid #ebeff6}
    .dm-page .dm-pages{display:flex;gap:6px;align-items:center;flex-wrap:wrap}
    .dm-page .dm-page-link{display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 9px;border:1px solid #d5deed;border-radius:9px;color:#101064;font-size:12px;font-weight:800;text-decoration:none}
    .dm-page .dm-page-link.active{background:#101064;color:#fff;border-color:#101064}
    .dm-page .dm-page-link.disabled{opacity:.38;pointer-events:none}
    @media(max-width:980px){.dm-page .dm-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.dm-page .dm-legend{grid-template-columns:repeat(3,minmax(0,1fr))}.dm-page .dm-field{grid-column:span 3}.dm-page .dm-field.small{grid-column:span 3}}
    @media(max-width:600px){.dm-page .dm-hero{padding:26px 20px}.dm-page .dm-stats{gap:9px}.dm-page .dm-stat{padding:17px 14px}.dm-page .dm-stat .value{font-size:25px}.dm-page .dm-panel-pad{padding:16px}.dm-page .dm-legend{grid-template-columns:repeat(2,minmax(0,1fr))}.dm-page .dm-filters{grid-template-columns:1fr;padding:17px}.dm-page .dm-field,.dm-page .dm-field.wide,.dm-page .dm-field.small{grid-column:1}.dm-page .dm-filter-title{padding:17px}.dm-page .dm-export-buttons{width:100%}.dm-page .dm-export-buttons a{flex:1}.dm-page .dm-filter-actions{align-items:stretch}.dm-page .dm-actions{width:100%}.dm-page .dm-actions>*{flex:1}.dm-page .dm-records-head{padding:16px}}
</style>

<div class="dm-page">
    <section class="dm-hero">
        <p class="dm-eyebrow">Department Staff · Personnel Coordination</p>
        <h1>Duty Monitoring</h1>
        <p>Review personnel assignments, recorded check-ins and check-outs, duty completion, and exceptions across your department's events.</p>
    </section>

    <section class="dm-stats" aria-label="Filtered duty summary">
        <div class="dm-stat"><div class="label">Matching Duties</div><div class="value">{{ $summary['total'] }}</div><div class="hint">Records matching current filters</div></div>
        <div class="dm-stat"><div class="label">Completed</div><div class="value" style="color:#008b67">{{ $summary['completed'] }}</div><div class="hint">Recorded check-out completed</div></div>
        <div class="dm-stat"><div class="label">Needs Attention</div><div class="value" style="color:#c35429">{{ $summary['attention'] }}</div><div class="hint">Missed or incomplete duties</div></div>
        <div class="dm-stat"><div class="label">Assigned / On Duty</div><div class="value">{{ $summary['active'] }}</div><div class="hint">{{ $summary['assigned'] }} assigned · {{ $summary['on_duty'] }} on duty</div></div>
    </section>

    @php
        $statusDisplay = [
            ['label'=>'Assigned', 'key'=>'assigned', 'color'=>'#8594b2'],
            ['label'=>'On Duty', 'key'=>'on_duty', 'color'=>'#4268d8'],
            ['label'=>'Completed', 'key'=>'completed', 'color'=>'#069677'],
            ['label'=>'Missed', 'key'=>'missed', 'color'=>'#dc4f65'],
            ['label'=>'Incomplete', 'key'=>'incomplete', 'color'=>'#e2a215'],
        ];
    @endphp
    <section class="dm-panel" aria-label="Duty status overview">
        <div class="dm-panel-pad">
            <div style="display:flex;align-items:start;justify-content:space-between;gap:10px;flex-wrap:wrap">
                <div><h2>Duty Status Overview</h2><p class="dm-sub">Automatic duty classifications across the filtered records.</p></div>
                <span class="dm-pill">View Only</span>
            </div>
            <div class="dm-bar" aria-hidden="true">
                @foreach($statusDisplay as $status)
                    <span style="background:{{ $status['color'] }};width:{{ $summary['total'] ? round($summary[$status['key']] * 100 / $summary['total'], 2) : 0 }}%"></span>
                @endforeach
            </div>
            <div class="dm-legend">
                @foreach($statusDisplay as $status)
                    <div class="dm-legend-item"><span><span class="dm-dot" style="background:{{ $status['color'] }}"></span>{{ $status['label'] }}</span><strong style="color:#101064">{{ $summary[$status['key']] }}</strong></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="dm-panel" aria-labelledby="dm-filter-heading">
        <div class="dm-filter-title">
            <div><p class="dm-eyebrow" style="margin-bottom:5px">Records &amp; Exports</p><h2 id="dm-filter-heading">Find Duty Records</h2><p class="dm-sub">Search and filter your department's duty monitoring history.</p></div>
            <div class="dm-export-buttons">
                <a class="dm-btn light" href="{{ route('personnel-duties.staff', array_merge(request()->except(['page', 'export']), ['export'=>'csv'])) }}">↓ Download CSV</a>
                <a class="dm-btn gold" href="{{ route('personnel-duties.staff', array_merge(request()->except(['page', 'export']), ['export'=>'pdf'])) }}">↓ Download PDF</a>
            </div>
        </div>
        <form method="GET" action="{{ route('personnel-duties.staff') }}" class="dm-filters">
            <div class="dm-field wide"><label for="dm-search">Search personnel, position or event</label><input id="dm-search" name="search" type="search" maxlength="120" placeholder="Search by name or event" value="{{ $filters['search'] }}"></div>
            <div class="dm-field wide"><label for="dm-event">Event</label><select name="event" id="dm-event"><option value="">All department events</option>@foreach($events as $event)<option value="{{ $event->event_id }}" @selected((string)$filters['event']===(string)$event->event_id)>{{ $event->event_name }}</option>@endforeach</select></div>
            <div class="dm-field"><label for="dm-status">Duty status</label><select id="dm-status" name="status"><option value="">All statuses</option>@foreach(['Assigned','On Duty','Completed','Missed','Incomplete'] as $status)<option value="{{ $status }}" @selected($filters['status']===$status)>{{ $status }}</option>@endforeach</select></div>
            <div class="dm-field"><label for="dm-from">Event date from</label><input type="date" id="dm-from" name="date_from" value="{{ $filters['date_from'] }}"></div>
            <div class="dm-field"><label for="dm-to">Event date to</label><input type="date" id="dm-to" name="date_to" value="{{ $filters['date_to'] }}"></div>
            <div class="dm-field wide"><label for="dm-sort">Sort by</label><select name="sort" id="dm-sort">@foreach(['newest'=>'Newest event first','oldest'=>'Oldest event first','personnel'=>'Personnel name A-Z','event'=>'Event name A-Z','duration'=>'Longest duty first'] as $key=>$label)<option value="{{ $key }}" @selected($filters['sort']===$key)>{{ $label }}</option>@endforeach</select></div>
            <div class="dm-field wide"><label for="dm-perpage">Rows per page</label><select id="dm-perpage" name="per_page">@foreach([5,10,20] as $n)<option value="{{ $n }}" @selected($filters['per_page']===$n)>{{ $n }} records</option>@endforeach</select></div>
            <div class="dm-filter-actions"><span class="dm-note">CSV/PDF exports include all matching records, not just the current page. Times are saved duty records—not inferred.</span><div class="dm-actions"><button type="submit" class="dm-btn navy">Apply Filters</button><a href="{{ route('personnel-duties.staff') }}" class="dm-btn light">Reset</a></div></div>
        </form>
    </section>

    <section class="dm-panel" aria-labelledby="dm-records-heading">
        <div class="dm-records-head"><div><h2 id="dm-records-heading">Personnel Duty Records</h2><p class="dm-sub">Check-in/out, scheduled duties, and final statuses for authorized department events.</p></div><span class="dm-pill">{{ number_format($duties->total()) }} matching · Showing {{ $duties->firstItem() ?? 0 }}–{{ $duties->lastItem() ?? 0 }}</span></div>
        <div class="dm-table-wrap">
            <table>
                <thead><tr><th>Personnel</th><th>Event</th><th>Schedule</th><th>Check-In</th><th>Check-Out</th><th>Duty Time</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($duties as $duty)
                        @php
                            $badgeStyles = [
                                'Assigned' => 'background:#eef2f8;color:#475569',
                                'On Duty' => 'background:#e9efff;color:#315bc3',
                                'Completed' => 'background:#e3f8ef;color:#067553',
                                'Missed' => 'background:#ffeaed;color:#c32f45',
                                'Incomplete' => 'background:#fff5da;color:#a86f00',
                            ];
                            $badgeStyle = $badgeStyles[$duty->duty_status] ?? $badgeStyles['Assigned'];
                        @endphp
                        <tr>
                            <td><div class="dm-primary">{{ trim($duty->first_name . ' ' . $duty->last_name) }}</div><div class="dm-secondary">{{ $duty->position ?: 'Attendance Personnel' }}</div></td>
                            <td><div class="dm-primary" style="max-width:230px;white-space:normal">{{ $duty->event_name }}</div><div class="dm-secondary">{{ $duty->location ?: 'Location not specified' }}</div></td>
                            <td><div class="dm-primary" style="white-space:nowrap">{{ \Carbon\Carbon::parse($duty->event_date)->format('M d, Y') }}</div><div class="dm-secondary" style="white-space:nowrap">{{ \Carbon\Carbon::parse($duty->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($duty->end_time)->format('g:i A') }}</div></td>
                            <td style="white-space:nowrap">{{ $duty->check_in_at ? \Carbon\Carbon::parse($duty->check_in_at)->format('M d, g:i A') : 'Not recorded' }}</td>
                            <td style="white-space:nowrap">{{ $duty->check_out_at ? \Carbon\Carbon::parse($duty->check_out_at)->format('M d, g:i A') : 'Not recorded' }}</td>
                            <td style="white-space:nowrap">{{ $duty->completed_minutes !== null ? intdiv((int)$duty->completed_minutes,60) . 'h ' . ((int)$duty->completed_minutes%60) . 'm' : '—' }}</td>
                            <td><span class="dm-status" style="{{ $badgeStyle }}">{{ $duty->duty_status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="padding:38px;text-align:center"><div class="dm-primary">No duty records match these filters.</div><div class="dm-secondary">Try resetting the filters or choosing a different event/date range.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="dm-pagination">
            <span class="dm-note">{{ $duties->total() }} filtered duty records · Page {{ $duties->currentPage() }} of {{ $duties->lastPage() }}</span>
            @if($duties->hasPages())
                <nav class="dm-pages" aria-label="Duty records pages">
                    <a class="dm-page-link {{ $duties->onFirstPage() ? 'disabled':'' }}" @if($duties->onFirstPage()) aria-disabled="true" @else href="{{ $duties->previousPageUrl() }}" @endif>Previous</a>
                    @for($i=max(1,$duties->currentPage()-2);$i<=min($duties->lastPage(),$duties->currentPage()+2);$i++)
                        <a class="dm-page-link {{ $i===$duties->currentPage()?'active':'' }}" href="{{ $duties->url($i) }}" @if($i===$duties->currentPage()) aria-current="page" @endif>{{ $i }}</a>
                    @endfor
                    <a class="dm-page-link {{ !$duties->hasMorePages() ? 'disabled':'' }}" @if(!$duties->hasMorePages()) aria-disabled="true" @else href="{{ $duties->nextPageUrl() }}" @endif>Next</a>
                </nav>
            @endif
        </div>
    </section>
</div>
</x-admin-layout>
