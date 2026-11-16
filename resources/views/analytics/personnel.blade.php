<x-admin-layout>
    @php
        $tab = $filters['tab'] ?? 'overview';
        $tabs = [
            'overview' => ['label' => 'Overview', 'detail' => 'Assigned event snapshot'],
            'attendance' => ['label' => 'Attendance', 'detail' => 'Scans and attendance statuses'],
            'events' => ['label' => 'Events', 'detail' => 'Your assigned events'],
            'participation' => ['label' => 'Participation', 'detail' => 'Eligible attendance coverage'],
            'trends' => ['label' => 'Trends', 'detail' => 'Six-month event attendance'],
        ];
        $colors = [
            'Present' => '#15977b', 'Late' => '#e2a21b', 'Checked In' => '#506bdf',
            'Incomplete' => '#ee5b70', 'Absent' => '#75839b', 'Excused' => '#9165c7',
        ];
        $params = array_filter($filters, fn ($v) => $v !== '' && $v !== null);
        unset($params['page']);
        $pendingCheckout = (int) ($statusCounts['Checked In'] ?? 0);
        $needsAttention = $pendingCheckout + (int) ($statusCounts['Incomplete'] ?? 0);
        $completedPercent = $eventCount ? min(100, round($completedCount * 100 / $eventCount, 1)) : 0;
        $recognizedPercent = $attendanceTotal ? round($recordedAttendances * 100 / $attendanceTotal, 1) : 0;
        $activeEvents = $featuredEvents->filter(fn ($event) => (int) $event->recorded_count > 0)->take(5);
        $activeEventMax = max(1, (int) $activeEvents->max('recorded_count'));
        $trendTotal = array_sum(array_column($months, 'records'));
        $trendAttended = array_sum(array_column($months, 'attended'));
        $trendActiveMonths = count(array_filter($months, fn ($month) => (int) $month['records'] > 0));
        $trendMaximum = max(1, ...array_column($months, 'records'));
        $trendPeakMonth = null;
        foreach ($months as $month) {
            if ($trendPeakMonth === null || (int) $month['records'] > (int) $trendPeakMonth['records']) {
                $trendPeakMonth = $month;
            }
        }
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
                ['label' => 'Recorded attendance', 'number' => number_format($attendanceTotal), 'caption' => 'Official entries on assigned events'],
                ['label' => 'Present', 'number' => number_format($statusCounts['Present']), 'caption' => 'Finalized Present records'],
                ['label' => 'Late', 'number' => number_format($statusCounts['Late']), 'caption' => 'Finalized Late records'],
                ['label' => 'Checked In', 'number' => number_format($pendingCheckout), 'caption' => 'Entries currently marked Checked In'],
            ],
            'events' => [
                ['label' => 'Assigned events', 'number' => number_format($eventCount), 'caption' => 'Events assigned within selected filters'],
                ['label' => 'Completed events', 'number' => number_format($completedCount), 'caption' => 'Assigned events marked Completed'],
                ['label' => 'Recorded attendance', 'number' => number_format($attendanceTotal), 'caption' => 'Attendance entries for these events'],
                ['label' => 'Completion share', 'number' => number_format($completedPercent, 1) . '%', 'caption' => 'Completed among assigned events'],
            ],
            'participation' => [
                ['label' => 'Eligible entries', 'number' => number_format($eligibleCount), 'caption' => 'Eligible student-event entries'],
                ['label' => 'Recognized participation', 'number' => number_format($qualifiedAttended), 'caption' => 'Eligible entries marked Present or Late'],
                ['label' => 'Participation coverage', 'number' => number_format($participationRate, 1) . '%', 'caption' => 'On completed assigned events'],
                ['label' => 'Completed events', 'number' => number_format($completedCount), 'caption' => 'Used in this participation calculation'],
            ],
            'trends' => [
                ['label' => 'Six-month records', 'number' => number_format(array_sum(array_column($months, 'records'))), 'caption' => 'Recorded by event date'],
                ['label' => 'Six-month Present / Late', 'number' => number_format(array_sum(array_column($months, 'attended'))), 'caption' => 'Recognized attendance in this period'],
                ['label' => 'Filtered attendance', 'number' => number_format($attendanceTotal), 'caption' => 'Across selected event/date filters'],
                ['label' => 'Assigned events', 'number' => number_format($eventCount), 'caption' => 'Within the selected scope'],
            ],
            default => [
                ['label' => 'My assigned events', 'number' => number_format($eventCount), 'caption' => 'Events within selected filters'],
                ['label' => 'Recorded attendance', 'number' => number_format($attendanceTotal), 'caption' => 'Official records from assigned events'],
                ['label' => 'Present / Late', 'number' => number_format($recordedAttendances), 'caption' => 'Finalized attended records'],
                ['label' => 'Needs follow-up', 'number' => number_format($needsAttention), 'caption' => 'Checked In or Incomplete status'],
            ],
        };
    @endphp
    <style>
        .dpa{--navy:#101064;--gold:#e7b52f;--ink:#172650;--muted:#65758f;--border:#e2e7f0;color:var(--ink);padding-bottom:36px;min-width:0;font-family:inherit}
        .dpa *{box-sizing:border-box}.dpa a{text-decoration:none}.dpa h1,.dpa h2,.dpa h3,.dpa p{margin:0}.dpa a:focus-visible,.dpa button:focus-visible,.dpa select:focus-visible,.dpa input:focus-visible{outline:3px solid #edbd37;outline-offset:2px}
        .dpa-hero{background-image:linear-gradient(103deg,rgba(9,13,75,.98) 0%,rgba(15,17,89,.93) 51%,rgba(13,17,75,.68) 100%),url('{{ asset('images/school.jpg') }}');background-position:center;background-size:cover;color:white;border-radius:23px;border-bottom:3px solid var(--gold);padding:31px 34px;display:flex;align-items:center;justify-content:space-between;gap:24px;box-shadow:0 6px 22px #14194716}
        .dpa-kicker{font-size:10px;letter-spacing:.18em;color:#f2c950;text-transform:uppercase;font-weight:900}.dpa-hero h1{font-size:clamp(27px,3vw,35px);font-weight:900;letter-spacing:-.035em;line-height:1.15;margin:10px 0 9px;color:white}
        .dpa-hero p{font-size:12px;line-height:1.7;color:#e0e5fc;max-width:560px}.dpa-hero-meta{display:flex;gap:7px;flex-wrap:wrap;margin-top:15px}.dpa-hero-meta span{background:#ffffff18;border:1px solid #ffffff32;border-radius:50px;color:#fff;font-size:10px;font-weight:750;padding:7px 11px}
        .dpa-exportbox{background:#060f56a8;border:1px solid #ffffff3b;border-radius:15px;padding:17px 18px;min-width:225px;max-width:260px;flex-shrink:0;backdrop-filter:blur(4px)}.dpa-exportbox small{display:block;color:#f0c449;font-size:10px;font-weight:900;letter-spacing:.11em;text-transform:uppercase}.dpa-exportbox strong{display:block;color:#fff;font-size:16px;margin:5px 0 11px}.dpa-export{display:flex;justify-content:center;align-items:center;gap:8px;border-radius:10px;background:var(--gold)!important;border:1px solid #ffdc78;color:#101064!important;min-height:39px;font-size:12px;font-weight:900;padding:10px 14px}.dpa-export:hover{background:#ffcf4a!important}.dpa-exportbox em{display:block;text-align:center;font-size:10px;margin-top:9px;color:#e2e5ff;font-style:normal}
        .dpa-tabs{display:flex;align-items:center;gap:6px;overflow:auto;scrollbar-width:thin;background:#fff;border:1px solid var(--border);border-radius:14px;padding:6px;margin:16px 0;box-shadow:0 2px 10px #11184908}.dpa-tabs a{white-space:nowrap;flex:1;min-width:127px;text-align:center;font-size:12px;color:#65738e;font-weight:800;padding:12px 14px;border-radius:9px}.dpa-tabs a:hover{background:#f2f4f9;color:var(--navy)}.dpa-tabs a.active{background:var(--navy);color:#fff;box-shadow:0 3px 11px #1010642a}
        .dpa-filter{display:grid;grid-template-columns:minmax(220px,2fr) minmax(170px,1fr) minmax(170px,1fr) auto;gap:13px;align-items:end;background:#fff;border:1px solid var(--border);border-radius:14px;padding:17px 19px;margin-bottom:17px}.dpa-filter label{display:block;margin-bottom:6px;color:#596b88;font-size:11px;font-weight:800}.dpa-filter input,.dpa-filter select{display:block;width:100%;background:#fff;min-height:41px;border:1px solid #d4ddec;border-radius:9px;padding:10px 11px;font-size:12px;color:#263458}.dpa-filter-buttons{display:flex;gap:7px;align-items:center}.dpa-filter button{background:var(--navy);color:white;border:none;padding:12px 15px;border-radius:9px;font-size:12px;font-weight:850;cursor:pointer}.dpa-filter .reset{font-size:12px;color:var(--navy);font-weight:800;padding:10px 13px;background:#fff;border:1px solid #d6ddec;border-radius:9px}
        .dpa-lead{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:18px 22px;background:#fff;border:1px solid var(--border);border-left:4px solid var(--gold);border-radius:13px;margin-bottom:15px}.dpa-lead strong{display:block;font-size:19px;color:var(--navy);font-weight:900}.dpa-lead p{margin-top:4px;font-size:11px;color:var(--muted);line-height:1.6}.dpa-lead-badge{background:#f5f6fb;border:1px solid #e6eaf1;border-radius:11px;padding:11px 16px;text-align:center;min-width:125px}.dpa-lead-badge b{display:block;font-size:22px;color:var(--navy);line-height:1.15}.dpa-lead-badge span{font-size:10px;color:#6b7890}
        .dpa-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:13px;margin-bottom:16px}.dpa-stat{position:relative;background:#fff;border:1px solid var(--border);border-radius:15px;padding:20px 19px;min-width:0;min-height:142px;box-shadow:0 3px 12px #1324520a;overflow:hidden}.dpa-stat:before{content:'';position:absolute;left:0;right:0;top:0;height:3px;background:linear-gradient(90deg,var(--navy),var(--gold))}.dpa-stat .statlabel{font-size:11px;color:#5c6d88;font-weight:800}.dpa-stat .statvalue{display:block;font-size:31px;letter-spacing:-.035em;color:var(--navy);font-weight:900;margin:15px 0 8px;line-height:1.12}.dpa-stat p{font-size:10px;color:#8390a4;line-height:1.55}
        .dpa-grid{display:grid;grid-template-columns:1.2fr 1fr;gap:15px;margin-bottom:16px}.dpa-grid.equal{grid-template-columns:1fr 1fr}.dpa-panel{background:#fff;border:1px solid var(--border);border-radius:16px;padding:22px 23px;min-width:0;box-shadow:0 3px 14px #15245208}.dpa-panel h3{font-size:16px;font-weight:900;color:var(--navy)}.dpa-panel .sub{font-size:11px;color:#7a879e;line-height:1.6;margin-top:6px;margin-bottom:18px}.dpa-label{display:block;font-size:10px;letter-spacing:.12em;color:#bb820e;text-transform:uppercase;font-weight:900;margin-bottom:7px}.dpa-big{font-weight:950;color:var(--navy);font-size:39px;line-height:1.1;letter-spacing:-.04em}.dpa-muted{font-size:11px;color:#72829a;line-height:1.65}.dpa-minirow{display:flex;justify-content:space-between;gap:10px;align-items:center;font-size:11px;margin-bottom:8px;color:#5a6b85}.dpa-minirow strong{color:var(--navy);font-size:12px}.dpa-track{width:100%;height:9px;border-radius:99px;background:#edf0f6;overflow:hidden}.dpa-track i{display:block;height:100%;border-radius:99px;background:var(--navy)}.dpa-rowprogress{margin:14px 0}.dpa-footnote{font-size:10px;line-height:1.65;color:#8591a3;margin-top:15px}
        .dpa-stack{display:flex;width:100%;height:18px;border-radius:6px;overflow:hidden;background:#e9edf4;margin:18px 0 16px}.dpa-stack i{display:block;height:100%;min-width:0}.dpa-legend{display:grid;grid-template-columns:1fr 1fr;gap:11px 16px}.dpa-legend-row{display:flex;align-items:center;gap:8px;font-size:11px;color:#687791}.dpa-legend-row .dot{width:8px;height:8px;border-radius:3px;flex-shrink:0}.dpa-legend-row b{margin-left:auto;color:var(--navy);font-size:12px}
        .dpa-event{margin-bottom:15px}.dpa-event:last-child{margin-bottom:0}.dpa-eventname{display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#3b4e71;font-size:12px;font-weight:750}.dpa-eventcount{font-size:11px;color:var(--navy);font-weight:900;white-space:nowrap}
        .dpa-spotlight{display:flex;gap:15px;align-items:center;background:#f6f8fd;border-radius:11px;border:1px solid #eaf0f7;padding:17px}.dpa-spotlight b{font-size:29px;color:var(--navy);font-weight:900}.dpa-spotlight p{font-size:11px;color:#718099;line-height:1.6}.dpa-pill{font-size:10px;color:#24316b;background:#f2f4fa;border-radius:50px;padding:6px 10px;font-weight:800;white-space:nowrap}.dpa-empty{border:1px dashed #dbe2ef;background:#fafbfe;padding:18px;border-radius:11px;font-size:12px;color:#72819a;line-height:1.65}
        .dpa-ring-area{display:flex;gap:23px;align-items:center;flex-wrap:wrap}.dpa-ring{height:162px;width:162px;flex-shrink:0;border-radius:50%;display:grid;place-items:center}.dpa-ring-center{width:117px;height:117px;background:#fff;border-radius:50%;display:flex;flex-direction:column;justify-content:center;align-items:center}.dpa-ring-center b{font-size:27px;color:var(--navy);font-weight:900}.dpa-ring-center small{font-size:10px;color:#8390a5}
        .dpa-table-panel{background:white;border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-top:16px;box-shadow:0 3px 12px #13245208}.dpa-table-header{padding:19px 22px;border-bottom:1px solid #edf0f6;display:flex;align-items:center;justify-content:space-between;gap:15px}.dpa-table-header h3{font-size:15px;font-weight:900;color:var(--navy)}.dpa-table-header p{font-size:11px;color:#7c879c;margin-top:4px}.dpa-table-scroller{overflow:auto}.dpa-table{width:100%;min-width:670px;border-collapse:collapse}.dpa-table th{background:#f6f8fb;text-align:left;white-space:nowrap;padding:14px 16px;text-transform:uppercase;letter-spacing:.06em;font-weight:850;font-size:10px;color:#65758d}.dpa-table td{padding:14px 16px;border-top:1px solid #edf0f5;font-size:12px;color:#566783;vertical-align:top}.dpa-table td strong{color:var(--navy)}.dpa-table tr:hover td{background:#fafbfe}.dpa-page{padding:11px 20px;border-top:1px solid var(--border)}.dpa-page nav{font-size:12px}
        .dpa-chart{width:100%;height:210px;display:block;background:linear-gradient(#f8faff,#fff);border:1px solid #e9edf5;border-radius:12px;padding:10px}.dpa-month-grid{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:9px;margin-top:14px}.dpa-month{background:#f7f8fb;border:1px solid #edf0f5;padding:13px 9px;border-radius:10px;text-align:center}.dpa-month strong{display:block;color:var(--navy);font-size:18px}.dpa-month span{font-size:10px;color:#738198}
        @media(max-width:1120px){.dpa-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.dpa-grid{grid-template-columns:1fr}}
        @media(max-width:900px){.dpa-filter{grid-template-columns:1fr 1fr}.dpa-hero{padding:27px 24px}}
        @media(max-width:650px){.dpa-hero{flex-direction:column;align-items:stretch}.dpa-exportbox{width:100%;max-width:none}.dpa-filter{grid-template-columns:1fr}.dpa-lead{align-items:flex-start}.dpa-month-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media(max-width:440px){.dpa-stats{gap:9px}.dpa-stat{padding:18px 14px;min-height:135px}.dpa-stat .statvalue{font-size:26px}.dpa-panel{padding:18px}.dpa-legend{grid-template-columns:1fr}.dpa-ring{height:138px;width:138px}.dpa-ring-center{width:99px;height:99px}}
        .dpa-inset{background:#f6f8fc;border:1px solid #e7edf7;border-radius:12px;padding:17px 18px;margin-top:15px}.dpa-inset b{font-size:21px;color:var(--navy);display:block}.dpa-inset span{font-size:11px;color:#73829b;display:block;margin-top:4px}
        .dpa-note{padding:12px 15px;border-radius:10px;border:1px solid #eadfbf;background:#fffcf3;color:#7c6429;font-size:11px;line-height:1.65;margin-bottom:16px}
        .dpa-statusbadge{display:inline-flex;white-space:nowrap;border-radius:999px;background:#edf4ff;color:#213a75;padding:5px 9px;font-weight:800;font-size:10px}
        .dpa-rows{display:grid;gap:13px}.dpa-pair{display:flex;gap:15px;justify-content:space-between;align-items:center;font-size:12px;color:#536683}.dpa-pair b{color:var(--navy)}

        /* Compact, content-first personnel analytics: no stretched chart panels. */
        .dpa-grid{align-items:start}
        .dpa-attendance-grid{grid-template-columns:minmax(0,1.05fr) minmax(0,1fr)}
        .dpa-attendance-grid .dpa-panel{padding:20px 23px}
        .dpa-attendance-grid .dpa-panel .sub{margin-bottom:13px}
        .dpa-attendance-grid .dpa-ring{width:138px;height:138px}
        .dpa-attendance-grid .dpa-ring-center{width:101px;height:101px}
        .dpa-attendance-grid .dpa-ring-area{gap:19px;align-items:center}
        .dpa-attendance-grid .dpa-legend{gap:10px 13px}
        .dpa-attendance-detail{display:grid;gap:9px}
        .dpa-followup-row{background:#f8f9fd;border:1px solid #e7ebf3;border-radius:10px;padding:10px 12px}
        .dpa-followup-row .dpa-minirow{margin:0 0 8px;font-weight:800}
        .dpa-followup-row .dpa-minirow b{font-size:17px;color:#101064}
        .dpa-followup-row .dpa-track{height:6px}
        .dpa-followup-foot{font-size:10px;color:#7b879a;line-height:1.55;margin-top:3px}
        .dpa-trend-layout{display:grid;grid-template-columns:minmax(0,1.38fr) minmax(230px,.75fr);gap:15px;align-items:start;margin-bottom:16px}
        .dpa-trend-layout .dpa-panel{padding:20px 23px}
        .dpa-trend-layout .dpa-panel .sub{margin-bottom:14px}
        .dpa-trend-list{display:grid;gap:10px}
        .dpa-trend-row{display:grid;grid-template-columns:72px minmax(0,1fr) 26px;align-items:center;gap:12px;min-height:22px}
        .dpa-trend-row .month-label{font-size:11px;color:#5d6b85;font-weight:800}
        .dpa-trend-row .month-count{text-align:right;font-size:12px;color:var(--navy);font-weight:900;font-variant-numeric:tabular-nums}
        .dpa-trend-track{height:14px;border-radius:5px;background:#edf1f7;overflow:hidden}
        .dpa-trend-fill{position:relative;background:#172569;height:100%;border-radius:5px;min-width:0;overflow:hidden}
        .dpa-trend-recognized{height:100%;background:#e4b038;border-radius:5px 0 0 5px}
        .dpa-trend-legend{display:flex;flex-wrap:wrap;gap:8px 17px;margin-top:16px;font-size:10px;color:#6f7d96;align-items:center}
        .dpa-trend-legend span{display:inline-flex;align-items:center;gap:6px}
        .dpa-trend-legend i{display:inline-block;width:9px;height:9px;border-radius:2px}
        .dpa-insight-top{background:#101064;border-radius:12px;color:#fff;padding:17px 18px;margin-bottom:12px}
        .dpa-insight-top small{display:block;color:#efc64d;font-size:10px;font-weight:900;letter-spacing:.07em;text-transform:uppercase}
        .dpa-insight-top strong{font-size:23px;font-weight:900;display:block;margin:7px 0 3px;color:#fff}
        .dpa-insight-top p{font-size:11px;color:#dfe4ff;line-height:1.5}
        .dpa-insight-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
        .dpa-insight-grid div{background:#f6f8fd;border:1px solid #e8edf6;border-radius:10px;padding:12px 13px;min-width:0}
        .dpa-insight-grid strong{display:block;color:var(--navy);font-size:20px;line-height:1.2;font-weight:900;font-variant-numeric:tabular-nums}
        .dpa-insight-grid span{display:block;font-size:10px;color:#75829a;margin-top:6px;line-height:1.45}
        .dpa-trend-empty{font-size:11px;background:#fafbfe;border:1px dashed #dce4f0;padding:13px 14px;border-radius:9px;margin-top:10px;color:#76859b}
        @media(max-width:850px){.dpa-attendance-grid,.dpa-trend-layout{grid-template-columns:1fr}}
        @media(max-width:650px){.dpa-trend-row{grid-template-columns:67px minmax(0,1fr) 25px;gap:8px}.dpa-trend-layout .dpa-panel,.dpa-attendance-grid .dpa-panel{padding:17px}.dpa-attendance-grid .dpa-ring-area{align-items:center}.dpa-insight-grid{grid-template-columns:1fr 1fr}}

        /* Overview V5: content-balanced summary; no misleading identical full-width event bars. */
        .dpa-overview-grid{display:grid;grid-template-columns:minmax(0,1.2fr) minmax(0,1fr) minmax(0,.92fr);gap:14px;align-items:stretch;margin-bottom:15px}
        .dpa-overview-grid>.dpa-panel{padding:20px 21px;min-height:0}
        .dpa-overview-grid .sub{margin-bottom:13px}
        .dpa-overview-grid .dpa-legend{gap:9px 11px}
        .dpa-overview-grid .dpa-stack{margin:13px 0 16px;height:13px}
        .dpa-overview-grid .dpa-footnote{margin-top:12px}
        .dpa-overview-spotlight{display:flex;align-items:center;gap:13px;background:#f5f7fc;border:1px solid #e8edf5;border-radius:11px;padding:15px 14px;margin-bottom:15px}
        .dpa-overview-spotlight>strong{font-size:32px;line-height:1;font-weight:950;color:var(--navy)}
        .dpa-overview-spotlight b{display:block;font-size:11px;color:var(--navy)}
        .dpa-overview-spotlight span{font-size:10px;color:#6b7891;display:block;margin-top:4px}
        .dpa-overview-rate{font-size:40px;font-weight:950;line-height:1;color:var(--navy);margin:17px 0 12px;letter-spacing:-.035em}
        .dpa-overview-rate span{font-size:20px;margin-left:2px}
        .dpa-overview-event-panel{padding:20px 21px;margin-bottom:16px}
        .dpa-overview-event-heading{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:12px}
        .dpa-overview-event-heading .sub{margin:4px 0 0}
        .dpa-overview-event-list{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:10px}
        .dpa-overview-event{border:1px solid #e6ebf4;border-radius:11px;padding:14px 13px;min-width:0;display:flex;flex-direction:column;justify-content:space-between;gap:11px;background:#f8f9fc}
        .dpa-overview-event .dpa-eventname{font-size:11px;line-height:1.4;white-space:normal;overflow:visible;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:30px;color:#25335e}
        .dpa-overview-event-total{font-size:11px;font-weight:900;color:var(--navy)}
        @media(max-width:1230px){.dpa-overview-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.dpa-overview-grid>.dpa-panel:last-child{grid-column:1/-1}.dpa-overview-event-list{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media(max-width:720px){.dpa-overview-grid{grid-template-columns:1fr}.dpa-overview-grid>.dpa-panel:last-child{grid-column:auto}.dpa-overview-event-list{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:440px){.dpa-overview-event-list{grid-template-columns:1fr}.dpa-overview-event-heading{align-items:flex-start;flex-direction:column}}
    </style>
    <div class="dpa">
        <header class="dpa-hero">
            <div>
                <div class="dpa-kicker">DySign / Attendance Personnel / Analytics</div>
                <h1>My Event Analytics</h1>
                <p>Monitor your assigned events, recorded attendance outcomes, and check-in progress from one focused workspace.</p>
                <div class="dpa-hero-meta"><span>Assigned events only</span><span>Official attendance records</span><span>Read-only reporting</span></div>
            </div>
            <div class="dpa-exportbox">
                <small>Current report</small>
                <strong>{{ $tabs[$tab]['label'] }}</strong>
                <a href="{{ route('analytics.csv', array_merge($params, ['tab' => $tab])) }}" class="dpa-export">Download {{ $tabs[$tab]['label'] }} CSV</a>
                <em>Exports match selected filters</em>
            </div>
        </header>
        <nav class="dpa-tabs" aria-label="Attendance Personnel analytics categories">
            @foreach($tabs as $name => $item)
                <a href="{{ route('analytics.index', array_merge($params, ['tab' => $name])) }}" class="{{ $tab === $name ? 'active' : '' }}" @if($tab === $name) aria-current="page" @endif>{{ $item['label'] }}</a>
            @endforeach
        </nav>
        <form class="dpa-filter" action="{{ route('analytics.index') }}" method="GET">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div><label for="dpa-event">Assigned event</label><select id="dpa-event" name="event_id"><option value="">All assigned events</option>
                @foreach($selectEvents as $event)
                    <option value="{{ $event->event_id }}" @selected((int) ($filters['event_id'] ?? 0) === (int) $event->event_id)>{{ $event->event_name }} ({{ $event->event_date }})</option>
                @endforeach
            </select></div>
            <div><label for="dpa-from">Event date from</label><input id="dpa-from" type="date" name="from" value="{{ $filters['from'] }}"></div>
            <div><label for="dpa-to">Event date to</label><input id="dpa-to" type="date" name="to" value="{{ $filters['to'] }}"></div>
            <div class="dpa-filter-buttons"><button type="submit">Apply Filters</button><a href="{{ route('analytics.index', ['tab' => $tab]) }}" class="reset">Reset</a></div>
        </form>
        @if($errors->any())<div role="alert" class="dpa-empty" style="color:#aa253b;border-color:#fec5cf;margin-bottom:12px">{{ $errors->first() }}</div>@endif
        <section class="dpa-lead">
            <div>
                <strong>{{ $tabs[$tab]['detail'] }}</strong>
                <p>
                    @if($tab === 'overview')A practical summary of your assigned events, official attendance, and follow-up statuses.
                    @elseif($tab === 'attendance')Recorded check-ins, check-outs, and attendance status for assigned events.
                    @elseif($tab === 'events')Completion and recorded attendance for the events assigned to your account.
                    @elseif($tab === 'participation')Eligible students and recognized participation at completed assigned events.
                    @else Attendance records grouped by actual event month for the last six months.
                    @endif
                </p>
            </div>
            <div class="dpa-lead-badge"><b>{{ number_format($eventCount) }}</b><span>assigned events</span></div>
        </section>
        @if($tab !== 'trends')
            <div class="dpa-stats">
                @foreach($focusMetrics as $metric)
                    <section class="dpa-stat"><div class="statlabel">{{ $metric['label'] }}</div><strong class="statvalue">{{ $metric['number'] }}</strong><p>{{ $metric['caption'] }}</p></section>
                @endforeach
            </div>
        @endif
        @if($tab === 'overview')
            <div class="dpa-overview-grid">
                <section class="dpa-panel">
                    <span class="dpa-label">Attendance recording</span><h3>Attendance status mix</h3>
                    <p class="sub">Official statuses for your assigned events.</p>
                    <div class="dpa-stack" role="img" aria-label="Distribution of attendance records by status">
                        @foreach($statusCounts as $status => $count)
                            @if($count > 0)<i style="width:{{ $attendanceTotal ? $count * 100 / $attendanceTotal : 0 }}%;background:{{ $colors[$status] }}" title="{{ $status }}: {{ $count }}"></i>@endif
                        @endforeach
                    </div>
                    <div class="dpa-legend">
                        @foreach($statusCounts as $status => $count)
                            <div class="dpa-legend-row"><span class="dot" style="background:{{ $colors[$status] }}"></span><span>{{ $status }}</span><b>{{ number_format($count) }}</b></div>
                        @endforeach
                    </div>
                </section>
                <section class="dpa-panel">
                    <span class="dpa-label">Records to review</span><h3>Follow-up overview</h3>
                    <p class="sub">Checked In and Incomplete records in your events.</p>
                    <div class="dpa-overview-spotlight"><strong>{{ number_format($needsAttention) }}</strong><div><b>Records to check</b><span>{{ $pendingCheckout }} Checked In &middot; {{ (int) ($statusCounts['Incomplete'] ?? 0) }} Incomplete</span></div></div>
                    <div class="dpa-minirow"><span>Present / Late share of recorded entries</span><strong>{{ number_format($recognizedPercent, 1) }}%</strong></div>
                    <div class="dpa-track"><i style="background:#15977b;width:{{ min(100, $recognizedPercent) }}%"></i></div>
                    <p class="dpa-footnote">Review actual scan evidence before resolving attendance concerns.</p>
                </section>
                <section class="dpa-panel">
                    <span class="dpa-label">Event assignments</span><h3>Assignment completion</h3>
                    <p class="sub">Events officially marked Completed.</p>
                    <div class="dpa-overview-rate">{{ number_format($completedPercent, 1) }}<span>%</span></div>
                    <div class="dpa-track" style="height:11px;margin-top:9px"><i style="background:#e0ad2e;width:{{ $completedPercent }}%"></i></div>
                    <div class="dpa-minirow" style="margin-top:12px"><span>Completed assignments</span><strong>{{ $completedCount }} / {{ $eventCount }}</strong></div>
                    <p class="dpa-footnote">Based on event status, not assumed from RFID scans.</p>
                </section>
            </div>
            <section class="dpa-panel dpa-overview-event-panel">
                <div class="dpa-overview-event-heading"><div><span class="dpa-label">Assigned event activity</span><h3>Recent events with attendance</h3><p class="sub">Five recent events with recorded attendance. Counts are actual records.</p></div><span class="dpa-pill">{{ $activeEvents->count() }} events shown</span></div>
                <div class="dpa-overview-event-list">
                    @forelse($activeEvents as $event)
                        <div class="dpa-overview-event" title="{{ $event->event_name }}"><span class="dpa-eventname">{{ $event->event_name }}</span><span class="dpa-overview-event-total">{{ number_format($event->recorded_count) }} {{ (int) $event->recorded_count === 1 ? 'record' : 'records' }}</span></div>
                    @empty
                        <div class="dpa-empty">No recorded attendance found for the selected assigned events.</div>
                    @endforelse
                </div>
            </section>
        @elseif($tab === 'attendance')
            <div class="dpa-grid dpa-attendance-grid">
                <section class="dpa-panel">
                    <span class="dpa-label">Official records</span>
                    <h3>Attendance status mix</h3>
                    <p class="sub">Each saved attendance record is counted once.</p>
                    <div class="dpa-ring-area">
                        <div class="dpa-ring" style="background:{{ $donutStyle }}" role="img" aria-label="Attendance status distribution: {{ $attendanceTotal }} records">
                            <div class="dpa-ring-center"><b>{{ number_format($attendanceTotal) }}</b><small>records</small></div>
                        </div>
                        <div class="dpa-legend" style="flex:1;min-width:180px">
                            @foreach($statusCounts as $status => $count)
                                <div class="dpa-legend-row"><span class="dot" style="background:{{ $colors[$status] }}"></span><span>{{ $status }}</span><b>{{ number_format($count) }}</b></div>
                            @endforeach
                        </div>
                    </div>
                </section>
                <section class="dpa-panel">
                    <span class="dpa-label">Records to review</span>
                    <h3>Attendance completion</h3>
                    <p class="sub">Finalized statuses versus records still marked Checked In or Incomplete.</p>
                    <div class="dpa-attendance-detail">
                        @foreach([
                            ['label' => 'Present / Late', 'count' => $recordedAttendances, 'color' => '#149879'],
                            ['label' => 'Checked In', 'count' => $pendingCheckout, 'color' => '#506bdf'],
                            ['label' => 'Incomplete', 'count' => (int) $statusCounts['Incomplete'], 'color' => '#ee5b70'],
                        ] as $group)
                            <div class="dpa-followup-row">
                                <div class="dpa-minirow"><span>{{ $group['label'] }}</span><b>{{ number_format($group['count']) }}</b></div>
                                <div class="dpa-track"><i style="background:{{ $group['color'] }};width:{{ $attendanceTotal ? min(100, $group['count'] * 100 / $attendanceTotal) : 0 }}%"></i></div>
                            </div>
                        @endforeach
                    </div>
                    <p class="dpa-followup-foot">A Checked In status alone does not establish an overdue checkout. Check event schedules and actual scan evidence.</p>
                </section>
            </div>
        @elseif($tab === 'events')
            <div class="dpa-grid">
                <section class="dpa-panel"><span class="dpa-label">Event assignments</span><h3>Assignment completion</h3><p class="sub">Count of assigned events marked Completed.</p>
                    <div class="dpa-big">{{ number_format($completedPercent, 1) }}%</div><div class="dpa-track" style="margin:19px 0 12px;height:13px"><i style="background:#e0b02e;width:{{ $completedPercent }}%"></i></div><div class="dpa-pair"><span>Completed events</span><b>{{ $completedCount }} / {{ $eventCount }}</b></div>
                </section>
                <section class="dpa-panel"><span class="dpa-label">Event activity</span><h3>Events with recorded attendance</h3><p class="sub">Up to five recent assigned events with recorded entries.</p>
                    @forelse($activeEvents as $event)
                        <div class="dpa-event"><div class="dpa-minirow"><span class="dpa-eventname" title="{{ $event->event_name }}">{{ $event->event_name }}</span><span class="dpa-eventcount">{{ $event->recorded_count }}</span></div><div class="dpa-track"><i style="width:{{ $event->recorded_count * 100 / $activeEventMax }}%"></i></div></div>
                    @empty <div class="dpa-empty">No recent recorded activity for these assigned events.</div> @endforelse
                </section>
            </div>
        @elseif($tab === 'participation')
            <div class="dpa-grid">
                <section class="dpa-panel"><span class="dpa-label">Eligible attendance</span><h3>Recognized participation</h3><p class="sub">Only completed assigned events and student-event eligibility entries.</p>
                    <div class="dpa-big">{{ number_format($participationRate, 1) }}%</div><div class="dpa-track" style="margin:19px 0 13px;height:13px"><i style="background:#deaa2b;width:{{ min(100,$participationRate) }}%"></i></div>
                    <div class="dpa-pair"><span>Present / Late eligible entries</span><b>{{ $qualifiedAttended }} of {{ $eligibleCount }}</b></div><p class="dpa-footnote">Statuses are sourced from saved records. Photos do not supply check-in/out times.</p>
                </section>
                <section class="dpa-panel"><span class="dpa-label">Participation context</span><h3>Eligibility and attendance</h3><p class="sub">A clear distinction between eligibility and recorded attendance.</p>
                    <div class="dpa-inset"><b>{{ number_format($eligibleCount) }}</b><span>Eligible student-event entries for completed assigned events</span></div>
                    <div class="dpa-inset"><b>{{ number_format($qualifiedAttended) }}</b><span>Eligible entries with Present or Late status</span></div>
                </section>
            </div>
        @else
            <div class="dpa-trend-layout">
                <section class="dpa-panel">
                    <span class="dpa-label">Six-month record distribution</span>
                    <h3>Attendance by event month</h3>
                    <p class="sub">Official attendance records grouped by event date. Gold shows Present/Late records.</p>
                    <div class="dpa-trend-list" role="img" aria-label="Monthly attendance records over six months">
                        @foreach($months as $month)
                            @php
                                $monthRecords = (int) $month['records'];
                                $monthAttended = (int) $month['attended'];
                            @endphp
                            <div class="dpa-trend-row">
                                <span class="month-label">{{ $month['label'] }}</span>
                                <div class="dpa-trend-track" title="{{ $month['label'] }}: {{ $monthRecords }} records, {{ $monthAttended }} Present/Late">
                                    @if($monthRecords > 0)
                                        <div class="dpa-trend-fill" style="width:{{ min(100, $monthRecords * 100 / $trendMaximum) }}%">
                                            <div class="dpa-trend-recognized" style="width:{{ min(100, $monthAttended * 100 / $monthRecords) }}%"></div>
                                        </div>
                                    @endif
                                </div>
                                <strong class="month-count">{{ number_format($monthRecords) }}</strong>
                            </div>
                        @endforeach
                    </div>
                    <div class="dpa-trend-legend"><span><i style="background:#e4b038"></i>Present / Late</span><span><i style="background:#172569"></i>Other statuses</span><span><i style="background:#edf1f7;border:1px solid #d8dfeb"></i>No records</span></div>
                    @if($trendTotal === 0)<div class="dpa-trend-empty">No recorded attendance for these months under the selected filters.</div>@endif
                </section>
                <section class="dpa-panel">
                    <span class="dpa-label">Period highlights</span><h3>At a glance</h3>
                    <p class="sub">A concise summary of the selected six-month window.</p>
                    <div class="dpa-insight-top">
                        <small>Peak recorded month</small>
                        <strong>{{ $trendTotal > 0 ? ($trendPeakMonth['label'] ?? '—') : 'No activity' }}</strong>
                        <p>{{ $trendTotal > 0 ? number_format($trendPeakMonth['records']) . ' attendance records in the busiest month' : 'No month has a recorded attendance entry' }}</p>
                    </div>
                    <div class="dpa-insight-grid">
                        <div><strong>{{ $trendActiveMonths }} / 6</strong><span>Months with recorded attendance</span></div>
                        <div><strong>{{ number_format($trendAttended) }}</strong><span>Present / Late during the period</span></div>
                    </div>
                    <p class="dpa-footnote">Trends reflect event dates, not the date a record was edited. Download the Trends CSV for the exact monthly totals.</p>
                </section>
            </div>
        @endif
        @if(in_array($tab, ['overview', 'attendance'], true))
            <section class="dpa-table-panel"><div class="dpa-table-header"><div><h3>{{ $tab === 'overview' ? 'Recent attendance entries' : 'Attendance records' }}</h3><p>Exact stored Time In/Out and attendance status for assigned events.</p></div><span class="dpa-pill">{{ $attendanceRows->total() }} records</span></div>
                <div class="dpa-table-scroller"><table class="dpa-table"><thead><tr><th>Student</th><th>Event</th><th>Event date</th><th>Time In</th><th>Time Out</th><th>Status</th></tr></thead><tbody>
                @forelse($attendanceRows as $record)
                    <tr><td><strong>{{ $record->last_name }}, {{ $record->first_name }}</strong><div>{{ $record->student_number }}</div></td><td>{{ $record->event_name }}</td><td>{{ $record->event_date }}</td><td>{{ $record->time_in ?: 'Not recorded' }}</td><td>{{ $record->time_out ?: 'Not recorded' }}</td><td><span class="dpa-statusbadge">{{ $record->status }}</span></td></tr>
                @empty <tr><td colspan="6" style="text-align:center;padding:24px">No attendance records match your selected assigned events.</td></tr> @endforelse
                </tbody></table></div>@if($attendanceRows->hasPages())<div class="dpa-page">{{ $attendanceRows->links() }}</div>@endif
            </section>
        @elseif(in_array($tab, ['events', 'participation'], true))
            <section class="dpa-table-panel"><div class="dpa-table-header"><div><h3>Assigned event details</h3><p>Eligibility and recorded attendance for your assigned events.</p></div><span class="dpa-pill">{{ $eventRows->total() }} events</span></div>
                <div class="dpa-table-scroller"><table class="dpa-table"><thead><tr><th>Event</th><th>Event date</th><th>Status</th><th>Eligible</th><th>Recorded</th><th>Present/Late</th></tr></thead><tbody>
                @forelse($eventRows as $event)
                    <tr><td><strong>{{ $event->event_name }}</strong><div>{{ $event->location }}</div></td><td>{{ $event->event_date }}</td><td><span class="dpa-statusbadge">{{ $event->status }}</span></td><td>{{ $event->eligible_count }}</td><td>{{ $event->recorded_count }}</td><td><strong>{{ $event->attended_count }}</strong></td></tr>
                @empty <tr><td colspan="6" style="text-align:center;padding:24px">No assigned events match the selected filters.</td></tr> @endforelse
                </tbody></table></div>@if($eventRows->hasPages())<div class="dpa-page">{{ $eventRows->links() }}</div>@endif
            </section>
        @endif
        <p class="dpa-footnote">Data is read-only and restricted to assigned events. Attendance records are official system entries, not necessarily scans performed by this personnel account.</p>
    </div>
</x-admin-layout>
