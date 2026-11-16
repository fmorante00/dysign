<x-admin-layout>
    @php
        $navTabs = [
            'overview' => ['Overview', 'School-wide snapshot'],
            'attendance' => ['Attendance', 'Record quality'],
            'events' => ['Events', 'Event performance'],
            'participation' => ['Participation', 'Eligible attendance'],
            'trends' => ['Trends', 'Month by month'],
        ];
        $palette = [
            'Present' => '#159776', 'Late' => '#d79a14', 'Checked In' => '#5269dd',
            'Incomplete' => '#ee5a70', 'Absent' => '#778499', 'Excused' => '#9263d7',
        ];
        $tab = $filters['tab'];
        $query = array_filter($filters, fn ($value) => $value !== null && $value !== '');
        unset($query['page']);
        $baseCount = max(1, $attendanceTotal);
        $segments = [];
        $segmentFrom = 0;
        foreach ($statusCounts as $status => $count) {
            if ($count < 1) continue;
            $segmentTo = $segmentFrom + ($count * 100 / $baseCount);
            $segments[] = $palette[$status] . ' ' . round($segmentFrom, 2) . '% ' . round($segmentTo, 2) . '%';
            $segmentFrom = $segmentTo;
        }
        $ring = $segments ? 'conic-gradient(' . implode(',', $segments) . ')' : 'conic-gradient(#e7eaf3 0 100%)';
        $attendedPercent = $attendanceTotal ? round($recordedAttendances * 100 / $attendanceTotal, 1) : 0;
        $maxEvent = max(1, (int) ($featuredEvents->max('recorded_count') ?? 0));
        $recentCount = max(1, $maxTrend);
        $completedPercent = $eventCount ? round(100 * $completedCount / $eventCount, 1) : 0;
    @endphp
    <style>
        .adma { color:#101064;min-width:0;padding-bottom:34px;font-family:inherit; }
        .adma *{box-sizing:border-box}
        .adma a{text-decoration:none}
        .adma .eyebrow{font-size:10px;letter-spacing:.16em;text-transform:uppercase;font-weight:850;color:#bd8608}
        .adma .intro{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap;margin-bottom:20px}
        .adma h1{font-size:clamp(26px,3vw,33px);font-weight:900;line-height:1.12;letter-spacing:-.03em;margin:5px 0 7px;color:#101064}
        .adma .intro p{font-size:13px;color:#71809c;line-height:1.6}
        .adma .csv{display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:12px;background:#e8b221!important;color:#101064!important;padding:12px 18px;font-weight:850;font-size:12px;border:1px solid #c89209;min-height:43px;box-shadow:0 4px 13px #ac780a20}
        .adma .csv:hover{background:#f4c33c!important}
        .adma .nav{display:flex;gap:5px;overflow-x:auto;background:#fff;border:1px solid #e0e6f0;border-radius:15px;padding:5px;margin:0 0 18px;scrollbar-width:thin}
        .adma .nav a{display:flex;flex:1;min-width:130px;align-items:center;justify-content:center;padding:12px 15px;border-radius:11px;font-size:12px;font-weight:850;color:#73809a;white-space:nowrap}
        .adma .nav a.active{background:#101064;color:#fff;box-shadow:0 3px 12px #10106429}
        .adma .filters{border:1px solid #e0e6f0;background:#fff;border-radius:16px;padding:18px;margin:0 0 18px;display:grid;grid-template-columns:minmax(220px,2fr) minmax(155px,1fr) minmax(155px,1fr) auto;align-items:end;gap:12px}
        .adma label{display:block;color:#62708b;font-size:11px;font-weight:800;margin-bottom:7px}
        .adma select,.adma input{display:block;border:1px solid #d5ddeb;background:#fff;color:#101064;padding:10px 11px;font-size:12px;border-radius:9px;width:100%;min-width:0;height:42px}
        .adma .controls{display:flex;gap:8px;align-items:center}
        .adma button.apply{border:0;background:#101064;color:white;border-radius:9px;font-weight:800;padding:11px 15px;height:42px;cursor:pointer;white-space:nowrap}
        .adma .reset{border:1px solid #d5ddeb;padding:11px 12px;border-radius:9px;font-size:12px;font-weight:800;color:#101064;white-space:nowrap}
        .adma .hero{background:linear-gradient(112deg,#101064 0%,#1c2868 62%,#4a4c6b 100%);border-radius:20px;border-bottom:4px solid #e9b327;color:white;padding:25px 27px;display:flex;align-items:center;justify-content:space-between;gap:22px;margin-bottom:18px}
        .adma .hero .eyebrow{color:#f0c44e}
        .adma .hero h2{color:white;font-size:22px;line-height:1.2;font-weight:900;margin:6px 0}
        .adma .hero p{font-size:12px;color:#e1e6ff;line-height:1.55}
        .adma .hero-stat{flex:none;text-align:right;border-left:1px solid #ffffff30;padding-left:22px}
        .adma .hero-stat strong{font-size:29px;color:#f0c44e;display:block;font-weight:900}
        .adma .hero-stat small{font-size:11px;color:#fff}
        .adma .metricgrid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:13px;margin-bottom:17px}
        .adma .metric{border:1px solid #dee5ee;border-radius:15px;background:#fff;padding:20px 20px 18px;box-shadow:0 3px 13px #0e1b4710;min-width:0}
        .adma .metric .label{color:#6b7b98;font-size:11px;font-weight:750;min-height:29px}
        .adma .metric strong{color:#101064;display:block;font-size:31px;font-weight:900;letter-spacing:-.04em;line-height:1.2;margin:5px 0 6px}
        .adma .metric small{color:#8793a9;font-size:10px;line-height:1.45}
        .adma .metric .accent{height:4px;background:#ecf0f6;border-radius:99px;overflow:hidden;margin-top:14px}
        .adma .metric .accent i{display:block;height:100%;background:#e9b327;border-radius:99px}
        .adma .cols{display:grid;grid-template-columns:1.1fr .9fr;gap:16px;margin-bottom:16px}
        .adma .panel{background:white;border:1px solid #dfe5ef;border-radius:16px;padding:22px;box-shadow:0 3px 14px #11194d08;min-width:0}
        .adma .panel h3{font-weight:900;font-size:15px;color:#101064;line-height:1.2}
        .adma .panel p.sub{font-size:11px;color:#8792a6;line-height:1.65;margin:4px 0 16px}
        .adma .ringwrap{display:flex;gap:22px;align-items:center;flex-wrap:wrap}
        .adma .ring{height:172px;width:172px;flex:none;background:var(--ring);border-radius:50%;display:grid;place-items:center}
        .adma .ring .inside{width:124px;height:124px;background:white;border-radius:50%;display:flex;flex-direction:column;align-items:center;justify-content:center}
        .adma .ring strong{font-size:30px;font-weight:900;color:#101064}
        .adma .ring small{font-size:10px;color:#7d879f}
        .adma .legend{display:grid;gap:12px;flex:1;min-width:155px}
        .adma .legendrow{display:flex;gap:10px;align-items:center;color:#596980;font-size:11px}
        .adma .legendrow b{margin-left:auto;font-size:12px;color:#101064}
        .adma .square{width:9px;height:9px;border-radius:3px;flex:none}
        .adma .progress-row{margin:0 0 16px}
        .adma .prog-head{display:flex;justify-content:space-between;gap:12px;margin-bottom:7px;font-size:11px;color:#51617d}
        .adma .prog-head b{color:#101064;white-space:nowrap}
        .adma .track{background:#eef1f7;height:10px;border-radius:99px;overflow:hidden}
        .adma .track span{display:block;background:#101064;border-radius:99px;height:100%}
        .adma .kpi-strip{border-radius:14px;padding:17px 19px;background:#f5f7fc;display:flex;align-items:center;justify-content:space-between;gap:12px;margin:16px 0}
        .adma .kpi-strip b{font-size:12px;font-weight:900}
        .adma .kpi-strip strong{font-size:20px;color:#b67c00}
        .adma .tablepanel{background:white;border:1px solid #dfe5ef;border-radius:16px;overflow:hidden;margin-top:17px}
        .adma .tableheading{display:flex;align-items:center;justify-content:space-between;gap:15px;flex-wrap:wrap;padding:18px 21px;border-bottom:1px solid #e9edf4}
        .adma .tableheading h3{font-size:15px;color:#101064;font-weight:900}
        .adma .tableheading span{font-size:11px;color:#77839a}
        .adma .tablewrap{overflow-x:auto}
        .adma table{width:100%;min-width:750px;border-collapse:collapse;font-size:12px}
        .adma th{text-align:left;background:#f5f7fb;color:#64738d;text-transform:uppercase;font-size:10px;letter-spacing:.04em;padding:13px 15px;white-space:nowrap}
        .adma td{padding:13px 15px;border-top:1px solid #eef1f5;color:#475778;vertical-align:middle}
        .adma td strong{color:#101064;font-weight:800}
        .adma td small{color:#8b96aa;font-size:10px;display:block;margin-top:4px}
        .adma .pill{display:inline-block;background:#edf1fa;border-radius:999px;padding:5px 8px;color:#101064;font-weight:800;font-size:10px;white-space:nowrap}
        .adma .pager{padding:14px 20px;border-top:1px solid #eef1f5}
        .adma .fine{margin-top:12px;font-size:11px;color:#7d8ba3;line-height:1.5}
        .adma .empty{padding:22px;color:#8793a9;font-size:12px}
        .adma .trend{width:100%;height:auto;display:block}
        .adma .month-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:8px;margin-top:9px}
        .adma .month-grid div{text-align:center;font-size:10px;color:#71809a}
        .adma .month-grid strong{display:block;color:#101064;font-size:13px;margin-bottom:5px}
        @media(max-width:1050px){.adma .metricgrid{grid-template-columns:repeat(2,minmax(0,1fr))}.adma .filters{grid-template-columns:1fr 1fr}.adma .cols{grid-template-columns:1fr}}
        @media(max-width:600px){.adma .filters{grid-template-columns:1fr}.adma .metricgrid{gap:9px}.adma .metric{padding:16px}.adma .metric strong{font-size:26px}.adma .hero{padding:20px;flex-direction:column;align-items:flex-start}.adma .hero-stat{text-align:left;border-left:0;border-top:1px solid #ffffff30;padding-left:0;padding-top:12px}.adma .panel{padding:18px}.adma .ring{width:135px;height:135px}.adma .ring .inside{width:95px;height:95px}.adma .month-grid{gap:3px}.adma .month-grid div{font-size:9px}}
    </style>

    <style>
        /* Administrator Analytics V3 — editorial, responsive, and scoped to this view. */
        .adma{--navy:#101064;--gold:#e2a912;--line:#e0e6ef;--muted:#68768e;max-width:1700px;margin:0 auto;padding:8px 2px 30px;color:#17264b}
        .adma .intro{min-height:220px;margin:0 0 20px;padding:31px 35px;display:flex;align-items:center;justify-content:space-between;gap:28px;position:relative;overflow:hidden;background-color:var(--navy);background-size:cover;background-position:center;border:1px solid #2a2c84;border-bottom:4px solid var(--gold);border-radius:22px;box-shadow:0 9px 26px rgba(15,21,77,.10)}
        .adma .intro-main{position:relative;z-index:1;max-width:700px}
        .adma .intro .eyebrow{font-size:10px;color:#f7ce5a;letter-spacing:.22em;display:flex;align-items:center;gap:9px}
        .adma .eyebrow-dash{width:22px;height:2px;background:#f1c349;display:inline-block}
        .adma .intro h1{font-size:clamp(29px,3vw,39px);letter-spacing:-.03em;color:#fff;margin:10px 0 9px;font-weight:900}
        .adma .intro p{font-size:13px;line-height:1.6;color:#e3e9ff;max-width:610px}
        .adma .intro-facts{display:flex;gap:7px;flex-wrap:wrap;margin-top:21px}
        .adma .intro-facts span{border:1px solid #ffffff38;background:#ffffff16;border-radius:999px;color:#edf1ff;padding:7px 12px;font-size:10px;font-weight:750;letter-spacing:.015em}
        .adma .intro-action{flex:0 0 auto;display:flex;flex-direction:column;align-items:flex-start;gap:9px;border:1px solid #ffffff35;background:#080f56a8;border-radius:16px;padding:19px 20px;min-width:240px;backdrop-filter:blur(6px)}
        .adma .export-caption{font-size:9px;letter-spacing:.16em;color:#efc956;font-weight:900}
        .adma .intro-action>strong{font-size:19px;line-height:1.3;color:#fff}
        .adma .intro .csv{width:100%;min-height:42px;margin-top:3px;box-shadow:none;border-radius:10px;padding:11px 14px;line-height:1.25;font-size:12px;background:#ebba2d!important;border:1px solid #f9d15d}
        .adma .export-hint{color:#dce4ff;font-size:10px}
        .adma .nav{background:#fff;border-color:#e3e8f1;box-shadow:0 3px 13px #13235309;gap:7px;margin-bottom:15px;border-radius:14px;padding:6px}
        .adma .nav a{font-size:12px;min-width:120px;padding:13px;border-radius:9px;color:#65738c;transition:background .15s,color .15s}
        .adma .nav a.active{background:var(--navy);color:#fff;box-shadow:0 4px 12px #1010642b}
        .adma .nav a:not(.active):hover{background:#f0f3fb;color:var(--navy)}
        .adma .filters{padding:16px 18px;margin-bottom:22px;border:1px solid #e0e6ef;border-radius:15px;box-shadow:0 3px 12px #17235306}
        .adma .filters select,.adma .filters input{border-color:#d6deeb;color:#223257;font-size:12px}
        .adma .filters label{color:#566784}
        .adma .filters .apply{background:var(--navy);height:42px}
        .adma .filters .apply:hover{background:#242482}
        .adma .filters .reset{height:42px;display:inline-flex;align-items:center}
        .adma .hero{margin:0 0 17px;padding:20px 23px;min-height:95px;border:1px solid #e0e6ee;border-left:4px solid var(--gold);border-bottom:1px solid #e0e6ee;border-radius:15px;background:#fff;box-shadow:0 3px 12px #1a205507;gap:22px}
        .adma .hero .eyebrow{color:#b7840d;font-size:10px}
        .adma .hero h2{color:#111957;font-size:20px;font-weight:900;margin:5px 0}
        .adma .hero p{color:#66758c;font-size:12px;max-width:650px;line-height:1.55}
        .adma .hero-stat{background:#f5f6fb;border:1px solid #e7ebf2;border-radius:12px;padding:11px 16px;min-width:145px;text-align:center}
        .adma .hero-stat strong{font-size:26px;color:#101064;font-weight:900;line-height:1.1}
        .adma .hero-stat small{display:block;color:#6e7890;font-size:10px;margin-top:4px}
        .adma .metricgrid{gap:14px;margin-bottom:17px}
        .adma .metric{position:relative;background:#fff;border:1px solid #dde4f0;border-radius:16px;padding:22px 21px;min-height:144px;box-shadow:0 4px 15px #121a4e0b;overflow:hidden}
        .adma .metric::before{content:'';position:absolute;left:0;top:0;width:100%;height:3px;background:linear-gradient(90deg,#101064,#e9b52c);opacity:.9}
        .adma .metric-top{display:flex;align-items:center;justify-content:space-between;gap:10px}
        .adma .metric .label{font-size:11px;color:#546680;min-height:0;font-weight:800}
        .adma .metric-mark{font-weight:900;color:#b0b9ce;font-size:11px}
        .adma .metric strong{font-size:33px;font-weight:900;color:#101064;letter-spacing:-.04em;margin:15px 0 5px;line-height:1}
        .adma .metric small{font-size:10px;line-height:1.6;color:#78879e}
        .adma .metric .accent{height:4px;margin-top:15px;background:#eef1f7}
        .adma .cols{grid-template-columns:1fr 1fr;gap:16px}
        .adma .panel{padding:23px 24px;border-color:#e0e6ee;border-radius:17px;box-shadow:0 4px 14px #131c4a08}
        .adma .panel h3{font-size:17px;font-weight:900;letter-spacing:-.015em}
        .adma .panel .sub{font-size:11px;color:#72809a;margin:6px 0 18px}
        .adma .panel-kicker{font-size:10px;font-weight:850;letter-spacing:.12em;text-transform:uppercase;color:#c28a0f;margin-bottom:7px}
        .adma .panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:7px}
        .adma .panel-link{font-size:11px;font-weight:850;color:#101064;padding:8px 10px;background:#eff2fc;border-radius:8px;white-space:nowrap}
        .adma .panel-link:hover{background:#e2e9fb}
        .adma .ringwrap{gap:30px}
        .adma .ring{width:174px;height:174px}
        .adma .ring .inside{width:126px;height:126px}
        .adma .legendrow{font-size:12px;color:#5a6981;padding:2px 0}
        .adma .legendrow b{font-size:12px}
        .adma .progress-row{margin-bottom:17px}
        .adma .prog-head{font-size:12px;color:#43536f}
        .adma .track{height:8px;background:#edf1f7}
        .adma .tablepanel{margin-top:18px;border-radius:17px;border-color:#e0e6ee;box-shadow:0 4px 14px #11194b09}
        .adma .tableheading{padding:20px 23px}
        .adma .tableheading h3{font-size:16px}
        .adma th{background:#f4f6fb;color:#67758c;padding:14px 16px}
        .adma td{padding:16px;border-top:1px solid #edf1f7;font-size:12px;color:#42516b}
        .adma tbody tr:hover{background:#fafbff}
        .adma .fine{font-size:10px;color:#8792a6;margin:15px 1px 0}
        .adma .trend-panel{padding:25px 27px;overflow:hidden}
        .adma .trend-chart{background:linear-gradient(180deg,#f7f9fe,#fff);border:1px solid #eef1f7;border-radius:14px;padding:7px 17px;margin:3px 0 10px}
        .adma .trend{display:block;width:100%;height:238px;max-height:238px}
        .adma .trend-total{display:flex;flex-direction:column;align-items:flex-end;background:#f1f3fa;border-radius:11px;padding:11px 14px;min-width:105px;flex-shrink:0}
        .adma .trend-total strong{font-size:23px;font-weight:900;color:#101064;line-height:1}
        .adma .trend-total span{font-size:10px;color:#6b7892;margin-top:5px}
        .adma .month-grid{border:1px solid #edf0f6;border-radius:12px;padding:13px 5px;background:#fafbfe;margin-top:11px}
        .adma .month-grid div{font-size:10px;color:#64738d}
        .adma .month-grid strong{font-size:15px;margin-bottom:3px}
        .adma .chart-explainer{font-size:11px;color:#7988a2;margin:12px 0 0}
        .adma .empty{border:1px dashed #dbe2ef;background:#f8faff;border-radius:12px;padding:19px;line-height:1.6}
        @media(max-width:1100px){.adma .intro{padding:25px}.adma .intro-action{min-width:215px}.adma .cols{grid-template-columns:1fr}.adma .metricgrid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:740px){.adma .intro{padding:25px 23px;align-items:stretch;flex-direction:column}.adma .intro-action{width:100%;min-width:0;flex:auto}.adma .filters{grid-template-columns:1fr 1fr}.adma .hero{flex-wrap:wrap}.adma .hero-stat{min-width:0}}
        @media(max-width:540px){.adma .filters{grid-template-columns:1fr}.adma .metricgrid{gap:9px}.adma .metric{padding:19px 16px;min-height:130px}.adma .metric strong{font-size:29px}.adma .ring{width:140px;height:140px}.adma .ring .inside{width:100px;height:100px}.adma .panel{padding:18px}.adma .trend-chart{padding:7px}.adma .trend{height:205px}.adma .month-grid{gap:2px}.adma .month-grid div{font-size:9px}}
    </style>

    <main class="adma">
        <header class="intro" style="background-image:linear-gradient(102deg,rgba(9,12,74,.96) 0%,rgba(16,16,94,.91) 47%,rgba(16,16,88,.70) 100%),url('{{ asset('images/school.jpg') }}');">
            <div class="intro-main">
                <div class="eyebrow"><span class="eyebrow-dash" aria-hidden="true"></span> DYSIGN / ADMINISTRATION</div>
                <h1>Institutional Analytics</h1>
                <p>School-wide attendance, events and participation — presented from official DySign records.</p>
                <div class="intro-facts"><span>School-wide access</span><span>Official records</span><span>Live filtered view</span></div>
            </div>
            <div class="intro-action">
                <div class="export-caption">CURRENT REPORT</div>
                <strong>{{ $navTabs[$tab][0] }}</strong>
                <a class="csv" href="{{ route('analytics.csv', array_merge($query, ['tab' => $tab])) }}" aria-label="Export {{ ucfirst($tab) }} analytics as CSV">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3h16v-3"/></svg>
                    Download {{ ucfirst($tab) }} CSV
                </a>
                <span class="export-hint">Uses your selected filters</span>
            </div>
        </header>

        <nav class="nav" aria-label="Analytics categories">
            @foreach($navTabs as $name => $info)
                <a href="{{ route('analytics.index', array_merge($query, ['tab' => $name])) }}" class="{{ $tab === $name ? 'active' : '' }}" @if($tab === $name) aria-current="page" @endif>{{ $info[0] }}</a>
            @endforeach
        </nav>

        <form class="filters" action="{{ route('analytics.index') }}" method="GET">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div><label for="adma-event">Event</label><select id="adma-event" name="event_id"><option value="">All school events</option>
                @foreach($selectEvents as $event)
                    <option value="{{ $event->event_id }}" @selected(($filters['event_id'] ?? null) === (int) $event->event_id)>{{ $event->event_name }} ({{ $event->event_date }})</option>
                @endforeach
            </select></div>
            <div><label for="adma-from">Event date from</label><input id="adma-from" type="date" name="from" value="{{ $filters['from'] }}"></div>
            <div><label for="adma-to">Event date to</label><input id="adma-to" type="date" name="to" value="{{ $filters['to'] }}"></div>
            <div class="controls"><button class="apply" type="submit">Apply</button><a class="reset" href="{{ route('analytics.index', ['tab' => $tab]) }}">Reset</a></div>
        </form>
        @if($errors->any())
            <div role="alert" style="padding:14px;background:#fff1f1;color:#9d1737;border-radius:10px;margin-bottom:15px">{{ $errors->first() }}</div>
        @endif

        @if($tab === 'overview')
            <section class="hero">
                <div><div class="eyebrow">Overview</div><h2>School performance at a glance</h2><p>A concise view of official activity within the selected reporting period.</p></div>
                <div class="hero-stat"><strong>{{ number_format($completedCount) }}</strong><small>completed events</small></div>
            </section>
            <section class="metricgrid" aria-label="Key school indicators">
                @foreach([
                    ['Events in scope', $eventCount, 'All events matching the selected period', 100],
                    ['Recorded attendance', $attendanceTotal, 'Official attendance records', 100],
                    ['Present or Late', $recordedAttendances, 'Attendance counted as participation', $attendedPercent],
                    ['Participation rate', number_format($participationRate,1).'%', 'Attended eligible student-event entries', $participationRate],
                ] as $metric)
                    <div class="metric"><div class="metric-top"><div class="label">{{ $metric[0] }}</div><span class="metric-mark" aria-hidden="true">{{ ['01', '02', '03', '04'][$loop->index] }}</span></div><strong>{{ is_numeric($metric[1]) ? number_format($metric[1]) : $metric[1] }}</strong><small>{{ $metric[2] }}</small></div>
                @endforeach
            </section>
            <div class="cols">
                <section class="panel"><div class="panel-kicker">Record distribution</div><h3>Attendance by status</h3><p class="sub">Each recorded status is counted once.</p>
                    <div class="ringwrap"><div class="ring" style="--ring:{{ $ring }}"><div class="inside"><strong>{{ number_format($attendanceTotal) }}</strong><small>records</small></div></div>
                        <div class="legend">@foreach($statusCounts as $status => $count)<div class="legendrow"><span class="square" style="background:{{ $palette[$status] }}"></span>{{ $status }}<b>{{ $count }}</b></div>@endforeach</div>
                    </div>
                </section>
                <section class="panel"><div class="panel-header"><div><div class="panel-kicker">Event activity</div><h3>Recent events with attendance</h3><p class="sub">Only recent events with recorded attendance are shown.</p></div><a class="panel-link" href="{{ route('analytics.index', array_merge($query, ['tab' => 'events'])) }}">View events</a></div>
                    @forelse($featuredEvents->filter(fn ($event) => (int) $event->recorded_count > 0) as $event)
                        <div class="progress-row"><div class="prog-head"><span title="{{ $event->event_name }}">{{ \Illuminate\Support\Str::limit($event->event_name, 38) }}</span><b>{{ $event->recorded_count }}</b></div><div class="track"><span style="width:{{ 100 * $event->recorded_count / $maxEvent }}%;background:#e5ac18"></span></div></div>
                    @empty <div class="empty">No recorded attendance among the most recent events. Open the Events category for the full event list.</div> @endforelse
                </section>
            </div>
        @elseif($tab === 'attendance')
            <section class="hero"><div><div class="eyebrow">Attendance quality</div><h2>Recorded status breakdown</h2><p>Attendance states are kept distinct. Missing timestamps are never inferred.</p></div><div class="hero-stat"><strong>{{ number_format($attendanceTotal) }}</strong><small>attendance records</small></div></section>
            <section class="metricgrid">
                @foreach([
                    ['Present', $statusCounts['Present'], '#159776'],
                    ['Late', $statusCounts['Late'], '#d79a14'],
                    ['Checked In', $statusCounts['Checked In'], '#5269dd'],
                    ['Incomplete', $statusCounts['Incomplete'], '#ee5a70'],
                ] as $metric)
                    <div class="metric"><div class="label">{{ $metric[0] }}</div><strong>{{ $metric[1] }}</strong><small>{{ $attendanceTotal ? number_format(100*$metric[1]/$attendanceTotal,1) : '0.0' }}% of recorded attendance</small><div class="accent"><i style="background:{{ $metric[2] }};width:{{ $attendanceTotal ? 100*$metric[1]/$attendanceTotal : 0 }}%"></i></div></div>
                @endforeach
            </section>
            <section class="panel"><h3>All recorded attendance statuses</h3><p class="sub">Compare the count and share of each status in the current scope.</p>
                @foreach($statusCounts as $status => $count)
                    <div class="progress-row"><div class="prog-head"><span>{{ $status }}</span><b>{{ $count }} &middot; {{ $attendanceTotal ? number_format(100*$count/$attendanceTotal,1) : '0.0' }}%</b></div><div class="track"><span style="background:{{ $palette[$status] }};width:{{ $attendanceTotal ? 100*$count/$attendanceTotal : 0 }}%"></span></div></div>
                @endforeach
            </section>
        @elseif($tab === 'events')
            <section class="hero"><div><div class="eyebrow">Event performance</div><h2>School event activity</h2><p>Eligible students, recorded attendance, and confirmed Present/Late participation.</p></div><div class="hero-stat"><strong>{{ number_format($eventCount) }}</strong><small>events in scope</small></div></section>
            <section class="metricgrid">
                @foreach([
                    ['Events in scope', $eventCount, 'All filtered events', 100],
                    ['Completed events', $completedCount, 'Finalized event records', $completedPercent],
                    ['Recorded attendance', $attendanceTotal, 'Across the selected events', 100],
                    ['Present or Late', $recordedAttendances, 'Officially counted attendance', $attendedPercent],
                ] as $metric)<div class="metric"><div class="label">{{ $metric[0] }}</div><strong>{{ number_format($metric[1]) }}</strong><small>{{ $metric[2] }}</small><div class="accent"><i style="width:{{ $metric[3] }}%"></i></div></div>@endforeach
            </section>
        @elseif($tab === 'participation')
            <section class="hero"><div><div class="eyebrow">Participation analysis</div><h2>Eligible vs. attended</h2><p>Participation uses only Present and Late records from completed eligible events.</p></div><div class="hero-stat"><strong>{{ number_format($participationRate,1) }}%</strong><small>participation rate</small></div></section>
            <section class="metricgrid">
                @foreach([
                    ['Eligible entries', $eligibleCount, 'Student-event eligibility entries', 100],
                    ['Attended eligible entries', $qualifiedAttended, 'Present or Late from eligible events', $participationRate],
                    ['Remaining entries', max(0,$eligibleCount-$qualifiedAttended), 'Eligible entries not counted as attended', 100-$participationRate],
                    ['Completed events', $completedCount, 'Events eligible for the calculation', $completedPercent],
                ] as $metric)<div class="metric"><div class="label">{{ $metric[0] }}</div><strong>{{ number_format($metric[1]) }}</strong><small>{{ $metric[2] }}</small><div class="accent"><i style="width:{{ $metric[3] }}%"></i></div></div>@endforeach
            </section>
            <section class="panel"><h3>Participation against eligible entries</h3><p class="sub">A percentage of eligible student-event entries, not a grade.</p>
                <div class="kpi-strip"><b>{{ number_format($qualifiedAttended) }} attended / {{ number_format($eligibleCount) }} eligible</b><strong>{{ number_format($participationRate,1) }}%</strong></div>
                <div class="track" style="height:15px"><span style="background:#159776;width:{{ $participationRate }}%"></span></div>
            </section>
        @elseif($tab === 'trends')
            <section class="hero"><div><div class="eyebrow">Attendance trends</div><h2>Last six months</h2><p>Monthly attendance grouped by event date. Use the event/date filters above to narrow the records.</p></div><div class="hero-stat"><strong>{{ number_format($attendanceTotal) }}</strong><small>recorded in scope</small></div></section>
            <section class="panel trend-panel"><div class="panel-header"><div><div class="panel-kicker">Six-month activity</div><h3>Monthly attendance trend</h3><p class="sub">Records grouped by event date, not estimated attendance.</p></div><div class="trend-total"><strong>{{ number_format(array_sum(array_column($months, 'records'))) }}</strong><span>records in chart</span></div></div>
                <div class="trend-chart"><svg class="trend" viewBox="0 0 560 175" role="img" aria-label="Recorded attendance during the last six months">
                    @foreach([30,70,110,150] as $y)<line x1="20" y1="{{ $y }}" x2="540" y2="{{ $y }}" stroke="#e6eaf2" stroke-width="1"/>@endforeach
                    <polyline fill="none" stroke="#101064" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" points="{{ implode(' ', $linePoints) }}"/>
                    @foreach($linePoints as $point)
                        @php [$pointX, $pointY] = explode(',', $point); @endphp
                        <circle cx="{{ $pointX }}" cy="{{ $pointY }}" r="5" fill="#e5ad20" stroke="#fff" stroke-width="2"/>
                    @endforeach
                </svg></div>
                <div class="month-grid">@foreach($months as $month)<div><strong>{{ $month['records'] }}</strong>{{ $month['label'] }}</div>@endforeach</div>
                <p class="chart-explainer">Zero months are shown on the baseline. Filtered dates and events may reduce these totals.</p>
            </section>
        @endif

        @if(in_array($tab, ['overview','attendance'], true))
            <section class="tablepanel"><div class="tableheading"><h3>Attendance records</h3><span>{{ number_format($attendanceRows->total()) }} matching records</span></div>
                <div class="tablewrap"><table><thead><tr><th>Student</th><th>Event</th><th>Event date</th><th>Time In</th><th>Time Out</th><th>Status</th></tr></thead><tbody>
                    @forelse($attendanceRows as $record)
                        <tr><td><strong>{{ $record->last_name }}, {{ $record->first_name }}</strong><small>{{ $record->student_number }}</small></td><td>{{ $record->event_name }}</td><td>{{ $record->event_date }}</td><td>{{ $record->time_in ?: 'Not recorded' }}</td><td>{{ $record->time_out ?: 'Not recorded' }}</td><td><span class="pill">{{ $record->status }}</span></td></tr>
                    @empty <tr><td colspan="6">No records match these filters.</td></tr> @endforelse
                </tbody></table></div>
                @if($attendanceRows->hasPages())<div class="pager">{{ $attendanceRows->links() }}</div>@endif
            </section>
        @elseif(in_array($tab, ['events','participation'], true))
            <section class="tablepanel"><div class="tableheading"><h3>Event-level breakdown</h3><span>{{ number_format($eventRows->total()) }} matching events</span></div>
                <div class="tablewrap"><table><thead><tr><th>Event</th><th>Date</th><th>Event status</th><th>Eligible entries</th><th>Recorded</th><th>Present / Late</th></tr></thead><tbody>
                    @forelse($eventRows as $event)
                        <tr><td><strong>{{ $event->event_name }}</strong><small>{{ $event->location }}</small></td><td>{{ $event->event_date }}</td><td><span class="pill">{{ $event->status }}</span></td><td>{{ $event->eligible_count }}</td><td>{{ $event->recorded_count }}</td><td><strong>{{ $event->attended_count }}</strong></td></tr>
                    @empty <tr><td colspan="6">No events match these filters.</td></tr> @endforelse
                </tbody></table></div>
                @if($eventRows->hasPages())<div class="pager">{{ $eventRows->links() }}</div>@endif
            </section>
        @else
            <section class="tablepanel"><div class="tableheading"><h3>Monthly record totals</h3><span>Six-month comparison</span></div>
                <div class="tablewrap"><table style="min-width:480px"><thead><tr><th>Month</th><th>Recorded</th><th>Present / Late</th><th>Other statuses</th></tr></thead><tbody>
                    @foreach($months as $month)<tr><td><strong>{{ $month['label'] }}</strong></td><td>{{ $month['records'] }}</td><td>{{ $month['attended'] }}</td><td>{{ $month['records']-$month['attended'] }}</td></tr>@endforeach
                </tbody></table></div>
            </section>
        @endif
        <p class="fine">Data source: official DySign event, eligibility and attendance records. CSV downloads follow the selected category and filters.</p>
    </main>
</x-admin-layout>
