<x-admin-layout>
    @php
        $tab = $filters['tab'] ?? 'overview';
        $tabs = [
            'overview' => ['label' => 'Overview', 'detail' => 'Department snapshot'],
            'attendance' => ['label' => 'Attendance', 'detail' => 'Status and records'],
            'events' => ['label' => 'Events', 'detail' => 'Event performance'],
            'participation' => ['label' => 'Participation', 'detail' => 'Eligible attendance'],
            'trends' => ['label' => 'Trends', 'detail' => 'Six-month movement'],
        ];
        $colors = [
            'Present' => '#15977b', 'Late' => '#e2a21b', 'Checked In' => '#506bdf',
            'Incomplete' => '#ee5b70', 'Absent' => '#75839b', 'Excused' => '#9165c7',
        ];
        $params = array_filter($filters, fn ($v) => $v !== '' && $v !== null);
        unset($params['page']);
        $participatingPercent = $attendanceTotal ? round($recordedAttendances * 100 / $attendanceTotal, 1) : 0;
        $completedPercent = $eventCount ? round($completedCount * 100 / $eventCount, 1) : 0;
        $attentionCount = (int) $statusCounts['Incomplete'] + (int) $statusCounts['Checked In'] + (int) $statusCounts['Absent'];
        $attentionPercent = $attendanceTotal ? round($attentionCount * 100 / $attendanceTotal, 1) : 0;
        $activeEvents = $featuredEvents->filter(fn ($e) => (int) $e->recorded_count > 0)->take(5);
        $activeEventMax = max(1, (int) $activeEvents->max('recorded_count'));
        $statusSegments = [];
        $position = 0;
        foreach ($statusCounts as $status => $count) {
            if ((int) $count <= 0) continue;
            $end = $position + 100 * (int) $count / max(1, $attendanceTotal);
            $statusSegments[] = $colors[$status] . ' ' . round($position, 2) . '% ' . round($end, 2) . '%';
            $position = $end;
        }
        $donutStyle = $statusSegments ? 'conic-gradient(' . implode(',', $statusSegments) . ')' : 'conic-gradient(#e8ecf5 0% 100%)';
        $focusMetrics = match ($tab) {
            'attendance' => [
                ['label' => 'Recorded attendance', 'number' => number_format($attendanceTotal), 'caption' => 'Saved official attendance records'],
                ['label' => 'Present', 'number' => number_format($statusCounts['Present']), 'caption' => 'Marked Present'],
                ['label' => 'Late', 'number' => number_format($statusCounts['Late']), 'caption' => 'Marked Late'],
                ['label' => 'Incomplete', 'number' => number_format($statusCounts['Incomplete']), 'caption' => 'Missing complete attendance evidence'],
            ],
            'events' => [
                ['label' => 'Department events', 'number' => number_format($eventCount), 'caption' => 'Events in selected scope'],
                ['label' => 'Completed events', 'number' => number_format($completedCount), 'caption' => 'Marked Completed'],
                ['label' => 'Attendance records', 'number' => number_format($attendanceTotal), 'caption' => 'Across selected events'],
                ['label' => 'Event completion', 'number' => number_format($completedPercent, 1) . '%', 'caption' => 'Completed out of events in scope'],
            ],
            'participation' => [
                ['label' => 'Eligible entries', 'number' => number_format($eligibleCount), 'caption' => 'Student-event eligibility entries'],
                ['label' => 'Attended entries', 'number' => number_format($qualifiedAttended), 'caption' => 'Eligible entries marked Present/Late'],
                ['label' => 'Participation rate', 'number' => number_format($participationRate, 1) . '%', 'caption' => 'Attended divided by eligible entries'],
                ['label' => 'Completed events', 'number' => number_format($completedCount), 'caption' => 'Used for participation calculation'],
            ],
            'trends' => [
                ['label' => 'Six-month records', 'number' => number_format(array_sum(array_column($months, 'records'))), 'caption' => 'Recorded in the displayed months'],
                ['label' => 'Six-month attended', 'number' => number_format(array_sum(array_column($months, 'attended'))), 'caption' => 'Present or Late in those months'],
                ['label' => 'All filtered records', 'number' => number_format($attendanceTotal), 'caption' => 'Across the selected date range'],
                ['label' => 'Completed events', 'number' => number_format($completedCount), 'caption' => 'Within the selected date range'],
            ],
            default => [
                ['label' => 'Completed events', 'number' => number_format($completedCount), 'caption' => 'Department events finalized'],
                ['label' => 'Recorded attendance', 'number' => number_format($attendanceTotal), 'caption' => 'Official attendance entries'],
                ['label' => 'Eligible attendances', 'number' => number_format($qualifiedAttended), 'caption' => 'Present/Late in completed eligible events'],
                ['label' => 'Participation rate', 'number' => number_format($participationRate, 1) . '%', 'caption' => 'Across eligible student-event entries'],
            ],
        };
    @endphp
    <style>
        .dsa{--navy:#101064;--gold:#e7b52f;--ink:#172650;--muted:#65758f;--border:#e2e7f0;color:var(--ink);padding-bottom:36px;min-width:0;font-family:inherit}
        .dsa *{box-sizing:border-box}.dsa a{text-decoration:none}.dsa h1,.dsa h2,.dsa h3,.dsa p{margin:0}.dsa a:focus-visible,.dsa button:focus-visible,.dsa select:focus-visible,.dsa input:focus-visible{outline:3px solid #edbd37;outline-offset:2px}
        .dsa-hero{background-image:linear-gradient(103deg,rgba(9,13,75,.98) 0%,rgba(15,17,89,.93) 51%,rgba(13,17,75,.68) 100%),url('{{ asset('images/school.jpg') }}');background-position:center;background-size:cover;color:white;border-radius:23px;border-bottom:3px solid var(--gold);padding:31px 34px;display:flex;align-items:center;justify-content:space-between;gap:24px;box-shadow:0 6px 22px #14194716}
        .dsa-kicker{font-size:10px;letter-spacing:.18em;color:#f2c950;text-transform:uppercase;font-weight:900}.dsa-hero h1{font-size:clamp(27px,3vw,35px);font-weight:900;letter-spacing:-.035em;line-height:1.15;margin:10px 0 9px;color:white}
        .dsa-hero p{font-size:12px;line-height:1.7;color:#e0e5fc;max-width:560px}.dsa-hero-meta{display:flex;gap:7px;flex-wrap:wrap;margin-top:15px}.dsa-hero-meta span{background:#ffffff18;border:1px solid #ffffff32;border-radius:50px;color:#fff;font-size:10px;font-weight:750;padding:7px 11px}
        .dsa-exportbox{background:#060f56a8;border:1px solid #ffffff3b;border-radius:15px;padding:17px 18px;min-width:225px;max-width:260px;flex-shrink:0;backdrop-filter:blur(4px)}.dsa-exportbox small{display:block;color:#f0c449;font-size:10px;font-weight:900;letter-spacing:.11em;text-transform:uppercase}.dsa-exportbox strong{display:block;color:#fff;font-size:16px;margin:5px 0 11px}.dsa-export{display:flex;justify-content:center;align-items:center;gap:8px;border-radius:10px;background:var(--gold)!important;border:1px solid #ffdc78;color:#101064!important;min-height:39px;font-size:12px;font-weight:900;padding:10px 14px}.dsa-export:hover{background:#ffcf4a!important}.dsa-exportbox em{display:block;text-align:center;font-size:10px;margin-top:9px;color:#e2e5ff;font-style:normal}
        .dsa-tabs{display:flex;align-items:center;gap:6px;overflow:auto;scrollbar-width:thin;background:#fff;border:1px solid var(--border);border-radius:14px;padding:6px;margin:16px 0;box-shadow:0 2px 10px #11184908}.dsa-tabs a{white-space:nowrap;flex:1;min-width:127px;text-align:center;font-size:12px;color:#65738e;font-weight:800;padding:12px 14px;border-radius:9px}.dsa-tabs a:hover{background:#f2f4f9;color:var(--navy)}.dsa-tabs a.active{background:var(--navy);color:#fff;box-shadow:0 3px 11px #1010642a}
        .dsa-filter{display:grid;grid-template-columns:minmax(220px,2fr) minmax(170px,1fr) minmax(170px,1fr) auto;gap:13px;align-items:end;background:#fff;border:1px solid var(--border);border-radius:14px;padding:17px 19px;margin-bottom:17px}.dsa-filter label{display:block;margin-bottom:6px;color:#596b88;font-size:11px;font-weight:800}.dsa-filter input,.dsa-filter select{display:block;width:100%;background:#fff;min-height:41px;border:1px solid #d4ddec;border-radius:9px;padding:10px 11px;font-size:12px;color:#263458}.dsa-filter-buttons{display:flex;gap:7px;align-items:center}.dsa-filter button{background:var(--navy);color:white;border:none;padding:12px 15px;border-radius:9px;font-size:12px;font-weight:850;cursor:pointer}.dsa-filter .reset{font-size:12px;color:var(--navy);font-weight:800;padding:10px 13px;background:#fff;border:1px solid #d6ddec;border-radius:9px}
        .dsa-lead{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:18px 22px;background:#fff;border:1px solid var(--border);border-left:4px solid var(--gold);border-radius:13px;margin-bottom:15px}.dsa-lead strong{display:block;font-size:19px;color:var(--navy);font-weight:900}.dsa-lead p{margin-top:4px;font-size:11px;color:var(--muted);line-height:1.6}.dsa-lead-badge{background:#f5f6fb;border:1px solid #e6eaf1;border-radius:11px;padding:11px 16px;text-align:center;min-width:125px}.dsa-lead-badge b{display:block;font-size:22px;color:var(--navy);line-height:1.15}.dsa-lead-badge span{font-size:10px;color:#6b7890}
        .dsa-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:13px;margin-bottom:16px}.dsa-stat{position:relative;background:#fff;border:1px solid var(--border);border-radius:15px;padding:20px 19px;min-width:0;min-height:142px;box-shadow:0 3px 12px #1324520a;overflow:hidden}.dsa-stat:before{content:'';position:absolute;left:0;right:0;top:0;height:3px;background:linear-gradient(90deg,var(--navy),var(--gold))}.dsa-stat .statlabel{font-size:11px;color:#5c6d88;font-weight:800}.dsa-stat .statvalue{display:block;font-size:31px;letter-spacing:-.035em;color:var(--navy);font-weight:900;margin:15px 0 8px;line-height:1.12}.dsa-stat p{font-size:10px;color:#8390a4;line-height:1.55}
        .dsa-grid{display:grid;grid-template-columns:1.2fr 1fr;gap:15px;margin-bottom:16px}.dsa-grid.equal{grid-template-columns:1fr 1fr}.dsa-panel{background:#fff;border:1px solid var(--border);border-radius:16px;padding:22px 23px;min-width:0;box-shadow:0 3px 14px #15245208}.dsa-panel h3{font-size:16px;font-weight:900;color:var(--navy)}.dsa-panel .sub{font-size:11px;color:#7a879e;line-height:1.6;margin-top:6px;margin-bottom:18px}.dsa-label{display:block;font-size:10px;letter-spacing:.12em;color:#bb820e;text-transform:uppercase;font-weight:900;margin-bottom:7px}.dsa-big{font-weight:950;color:var(--navy);font-size:39px;line-height:1.1;letter-spacing:-.04em}.dsa-muted{font-size:11px;color:#72829a;line-height:1.65}.dsa-minirow{display:flex;justify-content:space-between;gap:10px;align-items:center;font-size:11px;margin-bottom:8px;color:#5a6b85}.dsa-minirow strong{color:var(--navy);font-size:12px}.dsa-track{width:100%;height:9px;border-radius:99px;background:#edf0f6;overflow:hidden}.dsa-track i{display:block;height:100%;border-radius:99px;background:var(--navy)}.dsa-rowprogress{margin:14px 0}.dsa-footnote{font-size:10px;line-height:1.65;color:#8591a3;margin-top:15px}
        .dsa-stack{display:flex;width:100%;height:18px;border-radius:6px;overflow:hidden;background:#e9edf4;margin:18px 0 16px}.dsa-stack i{display:block;height:100%;min-width:0}.dsa-legend{display:grid;grid-template-columns:1fr 1fr;gap:11px 16px}.dsa-legend-row{display:flex;align-items:center;gap:8px;font-size:11px;color:#687791}.dsa-legend-row .dot{width:8px;height:8px;border-radius:3px;flex-shrink:0}.dsa-legend-row b{margin-left:auto;color:var(--navy);font-size:12px}
        .dsa-event{margin-bottom:15px}.dsa-event:last-child{margin-bottom:0}.dsa-eventname{display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#3b4e71;font-size:12px;font-weight:750}.dsa-eventcount{font-size:11px;color:var(--navy);font-weight:900;white-space:nowrap}
        .dsa-spotlight{display:flex;gap:15px;align-items:center;background:#f6f8fd;border-radius:11px;border:1px solid #eaf0f7;padding:17px}.dsa-spotlight b{font-size:29px;color:var(--navy);font-weight:900}.dsa-spotlight p{font-size:11px;color:#718099;line-height:1.6}.dsa-pill{font-size:10px;color:#24316b;background:#f2f4fa;border-radius:50px;padding:6px 10px;font-weight:800;white-space:nowrap}.dsa-empty{border:1px dashed #dbe2ef;background:#fafbfe;padding:18px;border-radius:11px;font-size:12px;color:#72819a;line-height:1.65}
        .dsa-ring-area{display:flex;gap:23px;align-items:center;flex-wrap:wrap}.dsa-ring{height:162px;width:162px;flex-shrink:0;border-radius:50%;display:grid;place-items:center}.dsa-ring-center{width:117px;height:117px;background:#fff;border-radius:50%;display:flex;flex-direction:column;justify-content:center;align-items:center}.dsa-ring-center b{font-size:27px;color:var(--navy);font-weight:900}.dsa-ring-center small{font-size:10px;color:#8390a5}
        .dsa-table-panel{background:white;border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-top:16px;box-shadow:0 3px 12px #13245208}.dsa-table-header{padding:19px 22px;border-bottom:1px solid #edf0f6;display:flex;align-items:center;justify-content:space-between;gap:15px}.dsa-table-header h3{font-size:15px;font-weight:900;color:var(--navy)}.dsa-table-header p{font-size:11px;color:#7c879c;margin-top:4px}.dsa-table-scroller{overflow:auto}.dsa-table{width:100%;min-width:670px;border-collapse:collapse}.dsa-table th{background:#f6f8fb;text-align:left;white-space:nowrap;padding:14px 16px;text-transform:uppercase;letter-spacing:.06em;font-weight:850;font-size:10px;color:#65758d}.dsa-table td{padding:14px 16px;border-top:1px solid #edf0f5;font-size:12px;color:#566783;vertical-align:top}.dsa-table td strong{color:var(--navy)}.dsa-table tr:hover td{background:#fafbfe}.dsa-page{padding:11px 20px;border-top:1px solid var(--border)}.dsa-page nav{font-size:12px}
        .dsa-chart{width:100%;height:210px;display:block;background:linear-gradient(#f8faff,#fff);border:1px solid #e9edf5;border-radius:12px;padding:10px}.dsa-month-grid{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:9px;margin-top:14px}.dsa-month{background:#f7f8fb;border:1px solid #edf0f5;padding:13px 9px;border-radius:10px;text-align:center}.dsa-month strong{display:block;color:var(--navy);font-size:18px}.dsa-month span{font-size:10px;color:#738198}
        @media(max-width:1120px){.dsa-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.dsa-grid{grid-template-columns:1fr}}
        @media(max-width:900px){.dsa-filter{grid-template-columns:1fr 1fr}.dsa-hero{padding:27px 24px}}
        @media(max-width:650px){.dsa-hero{flex-direction:column;align-items:stretch}.dsa-exportbox{width:100%;max-width:none}.dsa-filter{grid-template-columns:1fr}.dsa-lead{align-items:flex-start}.dsa-month-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media(max-width:440px){.dsa-stats{gap:9px}.dsa-stat{padding:18px 14px;min-height:135px}.dsa-stat .statvalue{font-size:26px}.dsa-panel{padding:18px}.dsa-legend{grid-template-columns:1fr}.dsa-ring{height:138px;width:138px}.dsa-ring-center{width:99px;height:99px}}
    </style>
    <div class="dsa">
        <header class="dsa-hero">
            <div>
                <div class="dsa-kicker">DySign / Department Staff / Analytics</div>
                <h1>Department Analytics</h1>
                <p>Review your department's event attendance, student participation, and recorded outcomes in one clear workspace.</p>
                <div class="dsa-hero-meta"><span>Your department only</span><span>Official records</span><span>Read-only reporting</span></div>
            </div>
            <div class="dsa-exportbox">
                <small>Current report</small>
                <strong>{{ $tabs[$tab]['label'] }}</strong>
                <a href="{{ route('analytics.csv', array_merge($params, ['tab' => $tab])) }}" class="dsa-export" aria-label="Download {{ $tabs[$tab]['label'] }} CSV">Download {{ $tabs[$tab]['label'] }} CSV</a>
                <em>Includes your applied filters</em>
            </div>
        </header>
        <nav class="dsa-tabs" aria-label="Department analytics categories">
            @foreach($tabs as $name => $item)
                <a href="{{ route('analytics.index', array_merge($params, ['tab' => $name])) }}" class="{{ $tab === $name ? 'active' : '' }}" @if($tab === $name) aria-current="page" @endif>{{ $item['label'] }}</a>
            @endforeach
        </nav>
        <form class="dsa-filter" action="{{ route('analytics.index') }}" method="GET">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div><label for="dsa-event">Department event</label><select id="dsa-event" name="event_id"><option value="">All department events</option>
                @foreach($selectEvents as $event)
                    <option value="{{ $event->event_id }}" @selected((int) ($filters['event_id'] ?? 0) === (int) $event->event_id)>{{ $event->event_name }} ({{ $event->event_date }})</option>
                @endforeach
            </select></div>
            <div><label for="dsa-from">Event date from</label><input id="dsa-from" type="date" name="from" value="{{ $filters['from'] }}"></div>
            <div><label for="dsa-to">Event date to</label><input id="dsa-to" type="date" name="to" value="{{ $filters['to'] }}"></div>
            <div class="dsa-filter-buttons"><button type="submit">Apply</button><a href="{{ route('analytics.index', ['tab' => $tab]) }}" class="reset">Reset</a></div>
        </form>
        @if($errors->any())<div role="alert" class="dsa-empty" style="color:#aa253b;border-color:#fec5cf;margin-bottom:12px">{{ $errors->first() }}</div>@endif
        <section class="dsa-lead">
            <div><strong>{{ $tabs[$tab]['detail'] }}</strong><p>
                @if($tab === 'overview') A concise view of completed events, recorded attendance, and eligible participation.
                @elseif($tab === 'attendance') Status counts and exact timestamps from approved attendance records.
                @elseif($tab === 'events') Compare department events by actual recorded and attended entries.
                @elseif($tab === 'participation') Measure eligible attendance without treating missing timestamps as confirmed scans.
                @else Six-month attendance history grouped by the event date.
                @endif
            </p></div>
            <div class="dsa-lead-badge"><b>{{ number_format($eventCount) }}</b><span>events in scope</span></div>
        </section>
        <div class="dsa-stats">
            @foreach($focusMetrics as $metric)
                <section class="dsa-stat"><div class="statlabel">{{ $metric['label'] }}</div><strong class="statvalue">{{ $metric['number'] }}</strong><p>{{ $metric['caption'] }}</p></section>
            @endforeach
        </div>
        @if($tab === 'overview')
            <div class="dsa-grid">
                <section class="dsa-panel">
                    <span class="dsa-label">Department performance</span><h3>Eligible participation</h3><p class="sub">Based only on completed events and students eligible for them.</p>
                    <div class="dsa-big">{{ number_format($participationRate, 1) }}%</div><p class="dsa-muted" style="margin-top:7px">{{ number_format($qualifiedAttended) }} attended of {{ number_format($eligibleCount) }} eligible student-event entries</p>
                    <div class="dsa-track" style="margin-top:17px;height:13px"><i style="background:#ddb029;width:{{ min(100, $participationRate) }}%"></i></div>
                    <div class="dsa-minirow" style="margin-top:13px"><span>Completed events in scope</span><strong>{{ $completedCount }} / {{ $eventCount }}</strong></div>
                    <div class="dsa-track"><i style="width:{{ $completedPercent }}%"></i></div>
                    <p class="dsa-footnote">Present or Late counts as attended. Incomplete and Checked In remain separate until attendance is finalized.</p>
                </section>
                <section class="dsa-panel">
                    <span class="dsa-label">Recorded attendance</span><h3>Status composition</h3><p class="sub">Distribution of actual saved statuses; each record counted once.</p>
                    <div class="dsa-stack" role="img" aria-label="Stacked attendance status distribution">
                        @foreach($statusCounts as $status => $count)
                            @if($count > 0)<i style="width:{{ $attendanceTotal ? $count * 100 / $attendanceTotal : 0 }}%;background:{{ $colors[$status] }}" title="{{ $status }}: {{ $count }}"></i>@endif
                        @endforeach
                    </div>
                    <div class="dsa-legend">
                        @foreach($statusCounts as $status => $count)
                            <div class="dsa-legend-row"><span class="dot" style="background:{{ $colors[$status] }}"></span><span>{{ $status }}</span><b>{{ number_format($count) }}</b></div>
                        @endforeach
                    </div>
                </section>
            </div>
            <div class="dsa-grid equal">
                <section class="dsa-panel">
                    <span class="dsa-label">Recent activity</span><h3>Events with attendance</h3><p class="sub">Up to five recent events with at least one recorded attendance entry.</p>
                    @forelse($activeEvents as $event)
                        <div class="dsa-event"><div class="dsa-minirow"><span class="dsa-eventname" title="{{ $event->event_name }}">{{ $event->event_name }}</span><span class="dsa-eventcount">{{ $event->recorded_count }}</span></div><div class="dsa-track"><i style="background:#dcae2d;width:{{ (int) $event->recorded_count * 100 / $activeEventMax }}%"></i></div></div>
                    @empty
                        <div class="dsa-empty">No recent events with recorded attendance in this selection.</div>
                    @endforelse
                    <p class="dsa-footnote">Showing only events with recorded activity, not a long list of zero-value bars.</p>
                </section>
                <section class="dsa-panel">
                    <span class="dsa-label">Follow-up visibility</span><h3>Records needing attention</h3><p class="sub">Statuses that may need completion or review; not automatically absences.</p>
                    <div class="dsa-spotlight"><b>{{ number_format($attentionCount) }}</b><p>Checked In, Incomplete or Absent records out of {{ number_format($attendanceTotal) }} total.</p></div>
                    <div class="dsa-rowprogress"><div class="dsa-minirow"><span>Share of recorded attendance</span><strong>{{ number_format($attentionPercent, 1) }}%</strong></div><div class="dsa-track"><i style="background:#e0a525;width:{{ $attentionPercent }}%"></i></div></div>
                    <p class="dsa-footnote">This indicator is informational. It does not change any attendance decision.</p>
                </section>
            </div>
        @elseif($tab === 'attendance')
            <div class="dsa-grid">
                <section class="dsa-panel">
                    <span class="dsa-label">Attendance breakdown</span><h3>Official status distribution</h3><p class="sub">Current saved attendance status for each matching record.</p>
                    <div class="dsa-ring-area"><div class="dsa-ring" style="background:{{ $donutStyle }}"><div class="dsa-ring-center"><b>{{ $attendanceTotal }}</b><small>records</small></div></div><div class="dsa-legend" style="flex:1;min-width:175px">@foreach($statusCounts as $status => $count)<div class="dsa-legend-row"><span class="dot" style="background:{{ $colors[$status] }}"></span>{{ $status }}<b>{{ $count }}</b></div>@endforeach</div></div>
                </section>
                <section class="dsa-panel">
                    <span class="dsa-label">Attendance quality</span><h3>Finalized versus open records</h3><p class="sub">Status mix without estimating check-in or check-out times.</p>
                    <div class="dsa-spotlight"><b>{{ number_format($participatingPercent, 1) }}%</b><p>Records marked Present or Late, out of {{ $attendanceTotal }} attendance entries.</p></div>
                    <div class="dsa-rowprogress"><div class="dsa-minirow"><span>Present or Late</span><strong>{{ $recordedAttendances }}</strong></div><div class="dsa-track"><i style="background:#15977b;width:{{ $participatingPercent }}%"></i></div></div>
                    <div class="dsa-rowprogress"><div class="dsa-minirow"><span>Other statuses</span><strong>{{ $attendanceTotal - $recordedAttendances }}</strong></div><div class="dsa-track"><i style="background:#e0ac29;width:{{ 100 - $participatingPercent }}%"></i></div></div>
                </section>
            </div>
        @elseif($tab === 'events')
            <div class="dsa-grid">
                <section class="dsa-panel"><span class="dsa-label">Event completion</span><h3>Completion of department events</h3><p class="sub">Completed events compared with all events in the selected scope.</p><div class="dsa-big">{{ number_format($completedPercent, 1) }}%</div><div class="dsa-track" style="height:13px;margin-top:18px"><i style="background:#dba82b;width:{{ $completedPercent }}%"></i></div><p class="dsa-footnote">{{ $completedCount }} completed of {{ $eventCount }} total matching events.</p></section>
                <section class="dsa-panel"><span class="dsa-label">Recorded activity</span><h3>Recent attended events</h3><p class="sub">Only recently listed events with recorded attendance.</p>
                    @forelse($activeEvents as $event)
                        <div class="dsa-event"><div class="dsa-minirow"><span class="dsa-eventname" title="{{ $event->event_name }}">{{ $event->event_name }}</span><strong>{{ $event->recorded_count }} recorded</strong></div><div class="dsa-track"><i style="width:{{ (int) $event->recorded_count * 100 / $activeEventMax }}%"></i></div></div>
                    @empty<div class="dsa-empty">No matching recent events have recorded attendance.</div>@endforelse
                </section>
            </div>
        @elseif($tab === 'participation')
            <div class="dsa-grid">
                <section class="dsa-panel"><span class="dsa-label">Eligible student attendance</span><h3>Participation coverage</h3><p class="sub">Calculated from completed events and eligible student-event entries.</p><div class="dsa-big">{{ number_format($participationRate, 1) }}%</div><div class="dsa-track" style="margin:20px 0 13px;height:14px"><i style="background:#dca926;width:{{ min(100, $participationRate) }}%"></i></div><div class="dsa-minirow"><span>Attended eligible entries</span><strong>{{ $qualifiedAttended }} / {{ $eligibleCount }}</strong></div><p class="dsa-footnote">No missing time is inferred from a photo, schedule, or an approval decision.</p></section>
                <section class="dsa-panel"><span class="dsa-label">At a glance</span><h3>Participation figures</h3><p class="sub">Use these totals to interpret participation across the selected department events.</p><div class="dsa-spotlight"><b>{{ number_format($eligibleCount) }}</b><p>Total eligible student-event entries from completed events.</p></div><div class="dsa-spotlight" style="margin-top:12px"><b>{{ number_format($qualifiedAttended) }}</b><p>Eligible entries with official Present or Late status.</p></div></section>
            </div>
        @else
            <section class="dsa-panel" style="margin-bottom:16px"><span class="dsa-label">Attendance movement</span><h3>Six-month attendance trend</h3><p class="sub">Monthly counts are based on event dates, not the time the report was opened.</p>
                <svg class="dsa-chart" viewBox="0 0 560 175" role="img" aria-label="Six-month attendance trend chart">
                    @foreach([30,70,110,150] as $y)<line x1="25" y1="{{ $y }}" x2="545" y2="{{ $y }}" stroke="#e5e9f2" stroke-width="1" />@endforeach
                    <polyline points="{{ implode(' ', $linePoints) }}" fill="none" stroke="#101064" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    @foreach($linePoints as $point)
                        @php [$x, $y] = explode(',', $point); @endphp
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="5" stroke="#fff" stroke-width="2" fill="#dba625" />
                    @endforeach
                </svg>
                <div class="dsa-month-grid">@foreach($months as $month)<div class="dsa-month"><strong>{{ $month['records'] }}</strong><span>{{ $month['label'] }}</span></div>@endforeach</div>
                <p class="dsa-footnote">Each month shows recorded attendance, regardless of status. See the CSV for attended-versus-other counts.</p>
            </section>
        @endif
        @if(in_array($tab, ['overview', 'attendance'], true))
            <section class="dsa-table-panel"><div class="dsa-table-header"><div><h3>{{ $tab === 'overview' ? 'Latest attendance records' : 'Attendance records' }}</h3><p>Exact recorded check-in/out times and official statuses.</p></div><span class="dsa-pill">{{ $attendanceRows->total() }} records</span></div>
                <div class="dsa-table-scroller"><table class="dsa-table"><thead><tr><th>Student</th><th>Event</th><th>Event date</th><th>Time In</th><th>Time Out</th><th>Status</th></tr></thead><tbody>
                    @forelse($attendanceRows as $record)
                        <tr><td><strong>{{ $record->last_name }}, {{ $record->first_name }}</strong><div>{{ $record->student_number }}</div></td><td>{{ $record->event_name }}</td><td>{{ $record->event_date }}</td><td>{{ $record->time_in ?: 'Not recorded' }}</td><td>{{ $record->time_out ?: 'Not recorded' }}</td><td><span class="dsa-pill">{{ $record->status }}</span></td></tr>
                    @empty<tr><td colspan="6" style="text-align:center;padding:25px">No attendance records match the selected filters.</td></tr>@endforelse
                </tbody></table></div>@if($attendanceRows->hasPages())<div class="dsa-page">{{ $attendanceRows->links() }}</div>@endif
            </section>
        @elseif(in_array($tab, ['events','participation'], true))
            <section class="dsa-table-panel"><div class="dsa-table-header"><div><h3>Department event details</h3><p>Eligibility and recorded participation for each event.</p></div><span class="dsa-pill">{{ $eventRows->total() }} events</span></div>
                <div class="dsa-table-scroller"><table class="dsa-table"><thead><tr><th>Event</th><th>Event date</th><th>Status</th><th>Eligible</th><th>Recorded</th><th>Present/Late</th></tr></thead><tbody>
                    @forelse($eventRows as $event)
                        <tr><td><strong>{{ $event->event_name }}</strong><div>{{ $event->location }}</div></td><td>{{ $event->event_date }}</td><td><span class="dsa-pill">{{ $event->status }}</span></td><td>{{ $event->eligible_count }}</td><td>{{ $event->recorded_count }}</td><td><strong>{{ $event->attended_count }}</strong></td></tr>
                    @empty<tr><td colspan="6" style="text-align:center;padding:25px">No department events match the filters.</td></tr>@endforelse
                </tbody></table></div>@if($eventRows->hasPages())<div class="dsa-page">{{ $eventRows->links() }}</div>@endif
            </section>
        @endif
        <p class="dsa-footnote">Analytics are read-only and scoped to the logged-in Department Staff account. Missing historical timestamps are never generated by this page.</p>
    </div>
</x-admin-layout>
