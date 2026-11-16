<x-admin-layout>
    @php
        $analyticsTabs = [
            'overview' => ['title' => 'Overview', 'subtitle' => 'Key indicators'],
            'attendance' => ['title' => 'Attendance', 'subtitle' => 'Recorded statuses'],
            'events' => ['title' => 'Events', 'subtitle' => 'Event insights'],
            'participation' => ['title' => 'Participation', 'subtitle' => 'Eligible attendance'],
            'trends' => ['title' => 'Trends', 'subtitle' => 'Monthly patterns'],
        ];
        $analyticsStatusColors = [
            'Present' => '#0b9b71', 'Late' => '#d58b12', 'Checked In' => '#4967db',
            'Incomplete' => '#ed4260', 'Absent' => '#758297', 'Excused' => '#9168d7',
        ];
        $analyticsDenominator = max(1, $attendanceTotal);
        $analyticsSlices = [];
        $analyticsPosition = 0;
        foreach ($statusCounts as $analyticsStatus => $analyticsCount) {
            if (!$analyticsCount) continue;
            $analyticsTo = $analyticsPosition + $analyticsCount * 100 / $analyticsDenominator;
            $analyticsSlices[] = $analyticsStatusColors[$analyticsStatus] . ' ' . round($analyticsPosition, 2) . '% ' . round($analyticsTo, 2) . '%';
            $analyticsPosition = $analyticsTo;
        }
        $analyticsDonut = count($analyticsSlices) ? 'conic-gradient(' . implode(', ', $analyticsSlices) . ')' : 'conic-gradient(#e7ebf4 0% 100%)';
        $analyticsMonthMax = max(1, $maxTrend);
        $analyticsTopEventMax = max(1, (int) ($featuredEvents->max('recorded_count') ?? 0));
        $analyticsUrlParams = array_filter($filters, fn ($v) => $v !== '' && $v !== null);
    @endphp

    <style>
        .dya { color:#101064; width:100%; min-width:0; padding-bottom:28px; }
        .dya * { box-sizing:border-box; }
        .dya-hero { border-radius:26px; border-bottom:4px solid #e9b327; padding:34px 32px; color:white; background-image:linear-gradient(105deg,rgba(16,16,100,.97),rgba(16,16,100,.87) 55%,rgba(16,16,100,.78)),url('{{ asset('images/school.jpg') }}'); background-size:cover; background-position:center; box-shadow:0 8px 28px rgba(12,19,72,.07); }
        .dya-eyebrow {font-size:11px;font-weight:800;letter-spacing:.19em;color:#ffd451;text-transform:uppercase;}
        .dya-hero h1 {margin:10px 0 9px;font-size:clamp(26px,3vw,37px);font-weight:900;letter-spacing:-.04em;line-height:1.12;color:white;}
        .dya-hero p {font-size:13px;line-height:1.7;color:#e2e6ff;max-width:620px;}
        .dya-tag {display:inline-flex; align-items:center;gap:8px;margin-top:15px;border:1px solid #ffffff35;border-radius:999px;padding:7px 12px;background:#ffffff15;font-size:11px;color:white;font-weight:700;}
        .dya-dot {width:7px;height:7px;border-radius:50%;background:#e9b327;display:inline-block;}
        .dya-export {display:inline-flex;align-items:center;justify-content:center;gap:8px;background:#e9b327!important;color:#101064!important;font-size:13px;font-weight:800;border:2px solid #ffe28a;border-radius:12px;padding:12px 17px;text-decoration:none!important;box-shadow:0 3px 16px #0002;white-space:nowrap;}
        .dya-export:hover {background:#ffce48!important;}
        .dya-filter {margin-top:18px;padding:17px 19px;background:white;border:1px solid #e1e6f0;border-radius:18px;display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;}
        .dya-filter label {display:block;font-size:11px;font-weight:800;color:#63728e;margin-bottom:7px;}
        .dya-filter input,.dya-filter select {width:100%;min-width:0;border:1px solid #d3dcec;border-radius:10px;background:#fff;color:#101064;padding:10px 12px;font-size:13px;outline:none;}
        .dya-field {flex:1 1 180px;min-width:140px;}
        .dya-filter .dya-apply {background:#101064!important;color:white!important;padding:11px 20px;border:0;border-radius:10px;font-size:13px;font-weight:800;cursor:pointer;}
        .dya-reset {padding:10px 16px;border:1px solid #dce2ec;border-radius:10px;text-decoration:none!important;color:#101064;font-size:13px;font-weight:700;}
        .dya-tabs {display:flex;gap:5px;overflow:auto;scrollbar-width:thin;margin:20px 0 18px;padding:5px;border:1px solid #e4e8f2;border-radius:15px;background:white;}
        .dya-tab {text-decoration:none!important;display:flex;align-items:center;justify-content:center;flex:1;gap:8px;min-width:135px;padding:12px 15px;border-radius:11px;font-size:12px;font-weight:800;color:#6a7791;white-space:nowrap;}
        .dya-tab.active {background:#101064;color:white;box-shadow:0 3px 8px #10106423;}
        .dya-metrics {display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;}
        .dya-card {background:white;border:1px solid #dfe4ed;border-radius:18px;box-shadow:0 3px 13px rgba(19,26,70,.04);min-width:0;overflow:hidden;}
        .dya-stat {padding:22px 23px;}
        .dya-stat .label {color:#65738f;font-size:12px;font-weight:700;}
        .dya-stat .value {color:#101064;font-size:31px;font-weight:900;line-height:1.2;margin:13px 0 8px;letter-spacing:-.03em;}
        .dya-stat .help {font-size:11px;line-height:1.5;color:#8791a8;}
        .dya-stat .track {margin-top:14px;height:5px;border-radius:9px;background:#edf0f7;overflow:hidden;}
        .dya-stat .track span {display:block;height:100%;border-radius:9px;background:#e2a812;}
        .dya-section {margin-top:17px;display:grid;grid-template-columns:1fr 1fr;gap:17px;}
        .dya-panel {padding:22px 24px;}
        .dya-panel h2 {font-size:16px;font-weight:900;color:#101064;}
        .dya-panel .desc {font-size:12px;color:#74809a;margin-top:4px;line-height:1.6;}
        .dya-panel .mini {margin-top:20px;}
        .dya-donut {width:165px;height:165px;flex:none;border-radius:50%;display:grid;place-items:center;}
        .dya-donut-center {width:115px;height:115px;border-radius:50%;background:white;display:flex;align-items:center;justify-content:center;flex-direction:column;box-shadow:0 0 0 1px #fff;}
        .dya-donut-center strong {font-size:29px;font-weight:900;color:#101064;line-height:1.1;}
        .dya-donut-center small {font-size:10px;color:#7f8aa1;}
        .dya-legends {flex:1;display:grid;gap:9px;min-width:0;}
        .dya-legend {display:flex;align-items:center;gap:8px;font-size:11px;color:#63718a;}
        .dya-legend b {margin-left:auto;font-size:12px;color:#101064;}
        .dya-legend .dot {width:8px;height:8px;flex:none;border-radius:3px;}
        .dya-bars {display:grid;gap:15px;}
        .dya-bar-top {display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:7px;font-size:12px;color:#455572;}
        .dya-bar-top strong {color:#101064;font-size:12px;}
        .dya-bar-track {height:9px;background:#eef1f7;border-radius:99px;overflow:hidden;}
        .dya-bar-track span {height:100%;display:block;border-radius:99px;background:#101064;}
        .dya-months {display:flex;justify-content:space-around;gap:5px;margin-top:2px;font-size:10px;color:#73809a;}
        .dya-banner {padding:18px 22px;background:linear-gradient(105deg,#101064,#242674);border-radius:16px;color:white;display:flex;align-items:center;justify-content:space-between;gap:17px;flex-wrap:wrap;}
        .dya-banner strong {font-size:14px;}
        .dya-banner p {font-size:11px;color:#d5d9ff;margin-top:5px;}
        .dya-table-head {padding:20px 23px;border-bottom:1px solid #edf0f6;display:flex;justify-content:space-between;align-items:center;gap:10px;}
        .dya-table-head h2 {font-size:16px;font-weight:900;color:#101064;}
        .dya-table-wrap {overflow:auto;}
        .dya-table {border-collapse:collapse;width:100%;min-width:680px;}
        .dya-table th {text-align:left;background:#f6f8fb;color:#627492;font-size:10px;letter-spacing:.06em;text-transform:uppercase;padding:14px 17px;white-space:nowrap;}
        .dya-table td {font-size:12px;padding:14px 17px;color:#51617e;border-top:1px solid #eef1f6;vertical-align:top;}
        .dya-table td strong {color:#101064;}
        .dya-status {font-size:10px;display:inline-block;padding:6px 9px;border-radius:50px;background:#f1f4fa;color:#101064;font-weight:800;white-space:nowrap;}
        .dya-pagination {padding:12px 20px;border-top:1px solid #eef1f7;}
        .dya-pagination nav {font-size:12px;}
        @media (max-width:1100px) { .dya-metrics{grid-template-columns:repeat(2,minmax(0,1fr))} }
        @media (max-width:840px) { .dya-section{grid-template-columns:1fr} .dya-hero{padding:27px 23px} }
        @media (max-width:490px) { .dya-metrics{gap:9px}.dya-stat{padding:17px 15px}.dya-stat .value{font-size:25px}.dya-panel{padding:18px 17px}.dya-tabs{margin-top:15px}.dya-donut{width:130px;height:130px}.dya-donut-center{width:89px;height:89px}.dya-donut-center strong{font-size:23px} }
    </style>

    <div class="dya space-y-0">
        <section class="dya-hero">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:25px;flex-wrap:wrap;">
                <div style="flex:1 1 450px;">
                    <div class="dya-eyebrow">DYSIGN &middot; {{ $audienceLabel }}</div>
                    <h1>Analytics Dashboard</h1>
                    <p>Explore attendance, event activity, participation, and trends using records available to your account.</p>
                    <div class="dya-tag"><span class="dya-dot"></span>{{ $scopeLabel }}</div>
                </div>
                <div>
                    <a class="dya-export" href="{{ route('analytics.csv', $analyticsUrlParams) }}" aria-label="Download the selected analytics category as CSV">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3v12m-4-4 4 4 4-4M4 17v4h16v-4"/></svg>
                        Export {{ $analyticsTabs[$filters['tab']]['title'] }} CSV
                    </a>
                    <p style="color:#e4e6fa;font-size:10px;text-align:center;margin-top:8px;">All matching records &middot; Not just this page</p>
                </div>
            </div>
        </section>

        <form class="dya-filter" method="GET" action="{{ route('analytics.index') }}">
            <input type="hidden" name="tab" value="{{ $filters['tab'] }}">
            <div class="dya-field" style="flex:2 1 250px;"><label for="analytics-event">Event</label>
                <select id="analytics-event" name="event_id"><option value="">All events in your scope</option>
                    @foreach($selectEvents as $event)
                        <option value="{{ $event->event_id }}" @selected((int)$filters['event_id'] === (int)$event->event_id)>{{ $event->event_name }} &middot; {{ $event->event_date }}</option>
                    @endforeach
                </select>
            </div>
            <div class="dya-field"><label for="analytics-from">From date</label><input type="date" id="analytics-from" name="from" value="{{ $filters['from'] }}"></div>
            <div class="dya-field"><label for="analytics-to">To date</label><input type="date" id="analytics-to" name="to" value="{{ $filters['to'] }}"></div>
            <button type="submit" class="dya-apply">Apply Filters</button>
            <a href="{{ route('analytics.index', ['tab' => $filters['tab']]) }}" class="dya-reset">Reset</a>
        </form>
        @if($errors->any())
            <div style="padding:12px 17px;margin-top:10px;border-radius:12px;background:#fff2f0;color:#9b2530;font-size:13px;">{{ $errors->first() }}</div>
        @endif

        <nav class="dya-tabs" aria-label="Analytics categories">
            @foreach($analyticsTabs as $key => $info)
                <a href="{{ route('analytics.index', array_merge($analyticsUrlParams, ['tab' => $key])) }}" class="dya-tab {{ $filters['tab'] === $key ? 'active' : '' }}" @if($filters['tab'] === $key) aria-current="page" @endif>{{ $info['title'] }}</a>
            @endforeach
        </nav>

        <div class="dya-metrics">
            @foreach([
                ['label' => 'Events in Scope', 'value' => number_format($eventCount), 'help' => $scopeLabel, 'percent' => 100],
                ['label' => 'Recorded Attendance', 'value' => number_format($attendanceTotal), 'help' => 'Saved attendance records', 'percent' => $attendanceTotal ? 100 : 0],
                ['label' => 'Present / Late', 'value' => number_format($recordedAttendances), 'help' => 'Participation recognized by status', 'percent' => $attendanceTotal ? round($recordedAttendances * 100 / $attendanceTotal, 1) : 0],
                ['label' => 'Participation Rate', 'value' => number_format($participationRate, 1) . '%', 'help' => $qualifiedAttended . ' of ' . $eligibleCount . ' eligible completed entries', 'percent' => $participationRate],
            ] as $metric)
                <section class="dya-card dya-stat">
                    <div class="label">{{ $metric['label'] }}</div>
                    <div class="value">{{ $metric['value'] }}</div>
                    <div class="help">{{ $metric['help'] }}</div>
                    <div class="track"><span style="width:{{ $metric['percent'] }}%"></span></div>
                </section>
            @endforeach
        </div>

        @if(in_array($filters['tab'], ['overview', 'attendance'], true))
            <div class="dya-section">
                <section class="dya-card dya-panel">
                    <h2>Attendance status distribution</h2><p class="desc">A clear breakdown of all recorded attendance statuses.</p>
                    <div class="mini" style="display:flex;align-items:center;gap:25px;flex-wrap:wrap;">
                        <div class="dya-donut" style="background:{{ $analyticsDonut }}"><div class="dya-donut-center"><strong>{{ number_format($attendanceTotal) }}</strong><small>records</small></div></div>
                        <div class="dya-legends">
                            @foreach($statusCounts as $status => $count)
                                <div class="dya-legend"><span class="dot" style="background:{{ $analyticsStatusColors[$status] }}"></span>{{ $status }}<b>{{ number_format($count) }}</b></div>
                            @endforeach
                        </div>
                    </div>
                </section>
                <section class="dya-card dya-panel">
                    <h2>Attendance comparison</h2><p class="desc">Compare statuses using a horizontal bar chart.</p>
                    <div class="mini dya-bars">
                        @foreach($statusCounts as $status => $count)
                            <div><div class="dya-bar-top"><span>{{ $status }}</span><strong>{{ $count }} &middot; {{ $attendanceTotal ? number_format($count * 100 / $attendanceTotal, 1) : '0.0' }}%</strong></div>
                                <div class="dya-bar-track"><span style="background:{{ $analyticsStatusColors[$status] }};width:{{ $attendanceTotal ? $count * 100 / $attendanceTotal : 0 }}%"></span></div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        @endif

        @if(in_array($filters['tab'], ['overview', 'trends'], true))
            <div class="dya-section">
                <section class="dya-card dya-panel">
                    <h2>Monthly attendance trend</h2><p class="desc">Six months of recorded attendance, grouped by event date.</p>
                    <div class="mini">
                        <svg viewBox="0 0 560 175" style="display:block;width:100%;height:auto;max-height:230px;" role="img" aria-label="Line chart of attendance totals over the last six months">
                            @foreach([30,70,110,150] as $y)
                                <line x1="25" y1="{{ $y }}" x2="545" y2="{{ $y }}" stroke="#e9edf5" stroke-width="1"/>
                            @endforeach
                            <polyline fill="none" stroke="#101064" stroke-width="4" stroke-linejoin="round" stroke-linecap="round" points="{{ implode(' ', $linePoints) }}"/>
                            @foreach($linePoints as $point)
                                @php([$xPoint, $yPoint] = explode(',', $point))
                                <circle cx="{{ $xPoint }}" cy="{{ $yPoint }}" r="5" fill="#e2ab17" stroke="#fff" stroke-width="2"/>
                            @endforeach
                        </svg>
                        <div class="dya-months">@foreach($months as $month)<span>{{ $month['label'] }}</span>@endforeach</div>
                    </div>
                </section>
                <section class="dya-card dya-panel">
                    <h2>Attendance by month</h2><p class="desc">Monthly recorded vs Present/Late attendance.</p>
                    <div class="mini dya-bars">
                        @foreach($months as $month)
                            <div>
                                <div class="dya-bar-top"><span>{{ $month['label'] }}</span><strong>{{ $month['records'] }} records</strong></div>
                                <div class="dya-bar-track" style="height:12px;">
                                    <span style="width:{{ $month['records'] * 100 / $analyticsMonthMax }}%;background:#dfe5ff;position:relative;">
                                        <span style="width:{{ $month['records'] ? $month['attended'] * 100 / $month['records'] : 0 }}%;background:#1f4bb7;display:block;height:100%;"></span>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        @endif

        @if(in_array($filters['tab'], ['overview', 'events', 'participation'], true))
            <div class="dya-section">
                <section class="dya-card dya-panel">
                    <h2>Event attendance comparison</h2><p class="desc">Recent events within your authorized scope.</p>
                    <div class="mini dya-bars">
                        @forelse($featuredEvents as $event)
                            <div><div class="dya-bar-top"><span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:77%;" title="{{ $event->event_name }}">{{ $event->event_name }}</span><strong>{{ $event->recorded_count }}</strong></div><div class="dya-bar-track"><span style="background:#e3af1e;width:{{ $event->recorded_count * 100 / $analyticsTopEventMax }}%"></span></div></div>
                        @empty
                            <p class="desc">No events match your filters.</p>
                        @endforelse
                    </div>
                </section>
                <section class="dya-card dya-panel">
                    <h2>Participation indicators</h2><p class="desc">Completed eligible events determine the attendance-based participation rate.</p>
                    <div class="mini" style="display:grid;gap:20px;">
                        <div class="dya-banner"><div><strong>{{ number_format($qualifiedAttended) }} eligible attendances</strong><p>Present or Late, from completed eligible events</p></div><strong style="font-size:26px;color:#f0be28;">{{ number_format($participationRate, 1) }}%</strong></div>
                        <div><div class="dya-bar-top"><span>Completed events in scope</span><strong>{{ $completedCount }} / {{ $eventCount }}</strong></div><div class="dya-bar-track" style="height:12px;"><span style="background:#101064;width:{{ $eventCount ? $completedCount * 100 / $eventCount : 0 }}%"></span></div></div>
                        <div><div class="dya-bar-top"><span>Present / Late share of all records</span><strong>{{ $attendanceTotal ? number_format($recordedAttendances * 100 / $attendanceTotal, 1) : '0.0' }}%</strong></div><div class="dya-bar-track" style="height:12px;"><span style="background:#12a77a;width:{{ $attendanceTotal ? $recordedAttendances * 100 / $attendanceTotal : 0 }}%"></span></div></div>
                        <p class="desc">A missing historical Time In/Out is never estimated from the event schedule. Incomplete, Checked In, and Excused remain separate statuses.</p>
                    </div>
                </section>
            </div>
        @endif

        @if($filters['tab'] === 'attendance' || $filters['tab'] === 'overview')
            <div class="dya-card" style="margin-top:18px;">
                <div class="dya-table-head"><div><h2>Recent attendance records</h2><p class="desc">Original recorded timestamps and official statuses</p></div><span class="dya-status">{{ $attendanceRows->total() }} records</span></div>
                <div class="dya-table-wrap"><table class="dya-table"><thead><tr><th>Student</th><th>Event</th><th>Event date</th><th>Time In</th><th>Time Out</th><th>Status</th></tr></thead><tbody>
                    @forelse($attendanceRows as $r)
                        <tr><td><strong>{{ $r->last_name }}, {{ $r->first_name }}</strong><div>{{ $r->student_number }}</div></td><td>{{ $r->event_name }}</td><td>{{ $r->event_date }}</td><td>{{ $r->time_in ?: 'Not recorded' }}</td><td>{{ $r->time_out ?: 'Not recorded' }}</td><td><span class="dya-status">{{ $r->status }}</span></td></tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:30px;">No attendance records match the selected filters.</td></tr>
                    @endforelse
                </tbody></table></div>
                @if($attendanceRows->hasPages())<div class="dya-pagination">{{ $attendanceRows->links() }}</div>@endif
            </div>
        @endif

        @if(in_array($filters['tab'], ['events', 'participation'], true))
            <div class="dya-card" style="margin-top:18px;">
                <div class="dya-table-head"><div><h2>Event {{ $filters['tab'] === 'participation' ? 'participation' : 'analytics' }} details</h2><p class="desc">Eligible students and official attendance totals by event</p></div><span class="dya-status">{{ $eventRows->total() }} events</span></div>
                <div class="dya-table-wrap"><table class="dya-table"><thead><tr><th>Event</th><th>Date</th><th>Status</th><th>Eligible</th><th>Recorded</th><th>Present / Late</th></tr></thead><tbody>
                    @forelse($eventRows as $e)
                        <tr><td><strong>{{ $e->event_name }}</strong><div>{{ $e->location }}</div></td><td>{{ $e->event_date }}</td><td><span class="dya-status">{{ $e->status }}</span></td><td>{{ $e->eligible_count }}</td><td>{{ $e->recorded_count }}</td><td><strong>{{ $e->attended_count }}</strong></td></tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:30px;">No events match the selected filters.</td></tr>
                    @endforelse
                </tbody></table></div>
                @if($eventRows->hasPages())<div class="dya-pagination">{{ $eventRows->links() }}</div>@endif
            </div>
        @endif

        <p style="margin:14px 4px 0;color:#8390a5;font-size:11px;">Analytics reflect current DySign records. Access is restricted by role on the server; the CSV uses the same event/date filters and your account's scope.</p>
    </div>
</x-admin-layout>
