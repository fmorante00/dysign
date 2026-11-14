<x-admin-layout>
    <style>
        .pa-v2{--navy:#101064;--gold:#d4a017;--line:#e2e8f0;--muted:#64748b;color:#0f172a}
        .pa-v2 *{box-sizing:border-box}
        .pa-v2 .pa-hero{position:relative;overflow:hidden;border-radius:26px;padding:30px 34px;background-image:linear-gradient(102deg,rgba(12,13,79,.97) 0%,rgba(16,16,100,.90) 54%,rgba(16,16,100,.72) 100%),url('{{ asset('images/school.jpg') }}');background-position:center;background-size:cover;color:white}
        .pa-v2 .pa-hero:after{content:"";position:absolute;bottom:0;left:0;width:100%;height:3px;background:var(--gold)}
        .pa-v2 .pa-orbit{pointer-events:none;position:absolute;right:-60px;top:-120px;border:1px solid rgba(255,255,255,.12);width:365px;height:365px;border-radius:50%}
        .pa-v2 .pa-orbit.two{right:10px;top:-66px;width:260px;height:260px}
        .pa-v2 .pa-hero-inner{position:relative;display:flex;gap:18px;align-items:center;justify-content:space-between;flex-wrap:wrap}
        .pa-v2 .pa-eyebrow{margin:0 0 10px;color:#e7c75b;font-size:11px;font-weight:800;letter-spacing:.22em;text-transform:uppercase}
        .pa-v2 .pa-hero h1{font-size:30px;line-height:1.2;font-weight:800;letter-spacing:-.035em;margin:0}
        .pa-v2 .pa-hero p.pa-description{margin:12px 0 0;max-width:640px;font-size:13px;line-height:1.7;color:rgba(255,255,255,.8)}
        .pa-v2 .pa-hero-meta{display:flex;gap:9px;flex-wrap:wrap;margin-top:18px}
        .pa-v2 .pa-hero-meta span{display:inline-block;padding:7px 12px;border-radius:999px;border:1px solid rgba(255,255,255,.19);background:rgba(255,255,255,.09);font-size:11px;font-weight:600}
        .pa-v2 .pa-hero-right{padding:15px 17px;min-width:210px;border:1px solid rgba(255,255,255,.18);border-radius:16px;background:rgba(255,255,255,.10);backdrop-filter:blur(7px)}
        .pa-v2 .pa-hero-right strong{display:block;font-size:13px;margin-top:3px}
        .pa-v2 .pa-hero-right small{display:block;margin-top:5px;color:rgba(255,255,255,.8);line-height:1.5}
        .pa-v2 .pa-summary{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
        .pa-v2 .pa-summary-card{min-width:0;min-height:122px;padding:19px 20px;border:1px solid var(--line);border-radius:17px;background:white;box-shadow:0 2px 3px rgba(15,23,42,.035)}
        .pa-v2 .pa-summary-head{display:flex;align-items:center;justify-content:space-between;gap:8px}
        .pa-v2 .pa-summary-label{font-size:10px;font-weight:750;letter-spacing:.09em;text-transform:uppercase;color:#64748b}
        .pa-v2 .pa-icon{display:inline-flex;align-items:center;justify-content:center;width:33px;height:33px;border-radius:11px;background:#eef2ff;color:var(--navy);font-size:15px}
        .pa-v2 .pa-count{font-size:30px;line-height:1.1;font-weight:800;margin:6px 0}
        .pa-v2 .pa-caption{font-size:11px;line-height:1.5;color:var(--muted)}
        .pa-v2 .pa-heading{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:end;gap:12px}
        .pa-v2 .pa-section-eyebrow{color:#bd8900;font-size:11px;font-weight:800;letter-spacing:.22em;text-transform:uppercase}
        .pa-v2 h2{margin:5px 0 0;color:var(--navy);font-size:19px;font-weight:800;letter-spacing:-.015em}
        .pa-v2 .pa-subtitle{margin:5px 0 0;color:var(--muted);font-size:12px;line-height:1.6}
        .pa-v2 .pa-total{font-size:11px;padding:7px 12px;border:1px solid #dfe4ef;border-radius:999px;background:white;color:#405274;font-weight:700}
        .pa-v2 .pa-directory{border:1px solid var(--line);background:white;border-radius:20px;overflow:hidden;box-shadow:0 2px 4px rgba(15,23,42,.035)}
        .pa-v2 .pa-filters{padding:17px 20px;background:#fff;border-bottom:1px solid #edf0f5;display:grid;grid-template-columns:minmax(0,2fr) minmax(155px,1fr) minmax(125px,.8fr) auto;gap:13px;align-items:end}
        .pa-v2 .pa-field{display:block;min-width:0}
        .pa-v2 .pa-field-label{display:block;font-size:10px;letter-spacing:.085em;text-transform:uppercase;font-weight:800;color:#667692;margin-bottom:7px}
        .pa-v2 .pa-field input,.pa-v2 .pa-field select{display:block;width:100%;min-width:0;height:41px;border:1px solid #d9e0ea;border-radius:10px;background:white;padding:0 12px;color:#17234a;font-size:12px;box-shadow:none;outline-offset:2px}
        .pa-v2 .pa-field input:focus,.pa-v2 .pa-field select:focus{border-color:#101064;outline:2px solid rgba(16,16,100,.12)}
        .pa-v2 .pa-actions{display:flex;gap:8px;align-items:center}
        .pa-v2 .pa-button{height:41px;display:inline-flex;align-items:center;justify-content:center;gap:7px;white-space:nowrap;border-radius:10px;padding:0 16px;font-size:12px;font-weight:750;text-decoration:none;cursor:pointer}
        .pa-v2 .pa-button.primary{border:1px solid var(--navy);background:var(--navy);color:white}
        .pa-v2 .pa-button.ghost{border:1px solid #d9e0ea;background:white;color:#253357}
        .pa-v2 .pa-button:hover{filter:brightness(1.05)}
        .pa-v2 .pa-tabs{display:flex;gap:8px;flex-wrap:wrap;padding:12px 20px;border-bottom:1px solid #edf0f5;background:#fafbfe}
        .pa-v2 .pa-tab{border:1px solid #dfe5f0;border-radius:9px;padding:8px 12px;background:white;color:#586782;text-decoration:none;font-size:11px;font-weight:750}
        .pa-v2 .pa-tab.active{background:#101064;color:white;border-color:#101064}
        .pa-v2 .pa-list{padding:12px 18px;display:grid;gap:11px}
        .pa-v2 .pa-event{display:flex;align-items:center;gap:16px;padding:17px 18px;border:1px solid #e3e8f3;border-radius:15px;background:white;transition:border-color .15s,box-shadow .15s}
        .pa-v2 .pa-event:hover{border-color:#b9c2e9;box-shadow:0 5px 18px rgba(16,16,100,.06)}
        .pa-v2 .pa-date{width:60px;height:69px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;justify-content:center;border:1px solid #dce4fb;border-radius:12px;background:#f3f6ff;color:#101064}
        .pa-v2 .pa-date span:first-child{font-size:10px;font-weight:800;letter-spacing:.06em}
        .pa-v2 .pa-date span:last-child{font-size:24px;font-weight:800;line-height:1.2}
        .pa-v2 .pa-event-body{flex:1;min-width:0}
        .pa-v2 .pa-tags{display:flex;align-items:center;flex-wrap:wrap;gap:6px}
        .pa-v2 .pa-pill{display:inline-flex;padding:4px 9px;border-radius:999px;border:1px solid;font-size:10px;font-weight:750}
        .pa-v2 .pa-pill.upcoming{background:#fff9e9;border-color:#f6e8b8;color:#9c6d00}
        .pa-v2 .pa-pill.ongoing{background:#ebfbf3;border-color:#cbf1dc;color:#06744b}
        .pa-v2 .pa-pill.completed{background:#f1f5f9;border-color:#dfe6ed;color:#475569}
        .pa-v2 .pa-pill.cancelled{background:#fff0f0;border-color:#fed7d7;color:#b42318}
        .pa-v2 .pa-pill.neutral{background:#f8fafc;border-color:#e2e8f0;color:#475569}
        .pa-v2 .pa-event h3{margin:8px 0 5px;font-size:14px;line-height:1.45;font-weight:800;color:#101064}
        .pa-v2 .pa-meta{display:flex;flex-wrap:wrap;gap:6px 16px;font-size:11px;line-height:1.5;color:#64748b}
        .pa-v2 .pa-event-cta{flex-shrink:0}
        .pa-v2 .pa-event-cta .pa-button{min-width:144px}
        .pa-v2 .pa-empty{padding:55px 20px;text-align:center}
        .pa-v2 .pa-empty-icon{width:54px;height:54px;margin:auto auto 12px;border-radius:17px;background:#f1f4fc;display:flex;align-items:center;justify-content:center;font-size:23px;color:#101064}
        .pa-v2 .pa-footer{padding:13px 20px;border-top:1px solid #edf0f5;background:#fafbfe;color:#64748b;font-size:11px}
        .pa-v2 .pa-pagination{border-top:1px solid #edf0f5;padding:14px 20px}
        .pa-v2 .pa-notice{border:1px solid #dfe6f4;border-radius:14px;background:#f8faff;padding:13px 16px;font-size:12px;line-height:1.7;color:#425277}
        @media(max-width:1050px){.pa-v2 .pa-filters{grid-template-columns:minmax(0,1fr) minmax(170px,1fr)}.pa-v2 .pa-summary{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:650px){.pa-v2 .pa-hero{padding:26px 22px}.pa-v2 .pa-hero h1{font-size:25px}.pa-v2 .pa-summary{grid-template-columns:repeat(2,minmax(0,1fr));gap:9px}.pa-v2 .pa-summary-card{padding:14px}.pa-v2 .pa-count{font-size:25px}.pa-v2 .pa-filters{grid-template-columns:1fr}.pa-v2 .pa-actions .pa-button{flex:1}.pa-v2 .pa-list{padding:10px}.pa-v2 .pa-event{gap:11px;padding:14px;align-items:flex-start;flex-wrap:wrap}.pa-v2 .pa-date{width:53px;height:60px}.pa-v2 .pa-event-body{flex-basis:calc(100% - 72px)}.pa-v2 .pa-event-cta{width:100%}.pa-v2 .pa-event-cta .pa-button{width:100%}.pa-v2 .pa-tabs{padding:10px}}
    </style>

    <div class="pa-v2 space-y-6 pb-8">
        <section class="pa-hero" aria-labelledby="personnel-assignment-title">
            <div class="pa-orbit"></div><div class="pa-orbit two"></div>
            <div class="pa-hero-inner">
                <div>
                    <p class="pa-eyebrow">DySign / Department Event Operations</p>
                    <h1 id="personnel-assignment-title">Personnel Assignment</h1>
                    <p class="pa-description">Coordinate attendance coverage, review existing duty assignments, and manage personnel for upcoming departmental events.</p>
                    <div class="pa-hero-meta"><span>{{ $totalEvents }} department events</span><span>{{ $assignedCount }} with personnel assigned</span></div>
                </div>
                <div class="pa-hero-right"><span class="pa-eyebrow" style="font-size:9px;margin-bottom:0">Assignment Access</span><strong>Upcoming events: editable</strong><small>Ongoing and past events are read-only.</small></div>
            </div>
        </section>

        <section class="pa-summary" aria-label="Department event overview">
            @foreach([
                ['label'=>'Total Events','value'=>$totalEvents,'caption'=>'Department event records','color'=>'#101064','icon'=>'▣'],
                ['label'=>'Upcoming','value'=>$upcomingCount,'caption'=>$unassignedUpcomingCount.' needing personnel','color'=>'#ba8500','icon'=>'◷'],
                ['label'=>'Ongoing','value'=>$ongoingCount,'caption'=>'Currently active sessions','color'=>'#00875b','icon'=>'●'],
                ['label'=>'Completed','value'=>$completedCount,'caption'=>'Available for historical review','color'=>'#334155','icon'=>'✓'],
            ] as $metric)
                <div class="pa-summary-card">
                    <div class="pa-summary-head"><span class="pa-summary-label">{{ $metric['label'] }}</span><span class="pa-icon">{{ $metric['icon'] }}</span></div>
                    <p class="pa-count" style="color:{{ $metric['color'] }}">{{ $metric['value'] }}</p>
                    <p class="pa-caption">{{ $metric['caption'] }}</p>
                </div>
            @endforeach
        </section>

        <section aria-labelledby="pa-directory-title">
            <div class="pa-heading mb-4">
                <div><span class="pa-section-eyebrow">Department Event Registry</span><h2 id="pa-directory-title">Event Assignment Directory</h2><p class="pa-subtitle">Find an event, view its assigned personnel, or manage an upcoming duty roster.</p></div>
                <span class="pa-total">{{ $events->total() }} matching events</span>
            </div>
            <div class="pa-directory">
                <form method="GET" action="{{ route('events.personnel-assignment.events') }}" class="pa-filters">
                    <label class="pa-field"><span class="pa-field-label">Search event or venue</span><input type="search" name="q" value="{{ $search }}" maxlength="150" placeholder="Event title or venue" /></label>
                    <label class="pa-field"><span class="pa-field-label">Event status</span><select name="status">@foreach(['all'=>'All statuses','upcoming'=>'Upcoming','ongoing'=>'Ongoing','completed'=>'Completed','cancelled'=>'Cancelled'] as $key=>$label)<option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>@endforeach</select></label>
                    <label class="pa-field"><span class="pa-field-label">Rows per page</span><select name="per_page">@foreach([10,25,50] as $size)<option value="{{ $size }}" @selected($perPage === $size)>{{ $size }} events</option>@endforeach</select></label>
                    <div class="pa-actions"><button type="submit" class="pa-button primary">Apply Filters</button><a class="pa-button ghost" href="{{ route('events.personnel-assignment.events') }}">Reset</a></div>
                </form>
                <nav class="pa-tabs" aria-label="Event status quick filters">
                    @foreach(['all'=>'All Events','upcoming'=>'Upcoming','ongoing'=>'Ongoing','completed'=>'Completed','cancelled'=>'Cancelled'] as $key=>$label)
                        <a href="{{ route('events.personnel-assignment.events', ['status'=>$key,'q'=>$search,'per_page'=>$perPage]) }}" class="pa-tab {{ $status === $key ? 'active' : '' }}" @if($status === $key) aria-current="page" @endif>{{ $label }}</a>
                    @endforeach
                </nav>
                <div class="pa-list">
                    @forelse($events as $event)
                        @php
                            $st = strtolower((string) $event->status);
                            $editable = $st === 'upcoming';
                            $statusClass = in_array($st, ['upcoming','ongoing','completed','cancelled'], true) ? $st : 'neutral';
                        @endphp
                        <article class="pa-event">
                            <div class="pa-date" aria-label="{{ $event->event_date?->format('F j, Y') ?? 'Date not set' }}"><span>{{ $event->event_date?->format('M') ?? 'TBA' }}</span><span>{{ $event->event_date?->format('d') ?? '—' }}</span></div>
                            <div class="pa-event-body">
                                <div class="pa-tags">
                                    <span class="pa-pill {{ $statusClass }}">{{ $event->status }}</span>
                                    <span class="pa-pill neutral">{{ $event->assignments_count }} personnel assigned</span>
                                    @if($editable && !$event->assignments_count)<span class="pa-pill upcoming">Needs assignment</span>@endif
                                </div>
                                <h3>{{ $event->event_name }}</h3>
                                <div class="pa-meta">
                                    <span>{{ $event->event_date?->format('M j, Y') ?? 'Date TBA' }}</span>
                                    <span>{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : 'Time TBA' }}{{ $event->end_time ? '–'.\Carbon\Carbon::parse($event->end_time)->format('g:i A') : '' }}</span>
                                    <span>{{ $event->location ?: 'Venue TBA' }}</span>
                                </div>
                            </div>
                            <div class="pa-event-cta"><a class="pa-button {{ $editable ? 'primary' : 'ghost' }}" href="{{ route('events.personnel-assignment.index', $event->event_id) }}">{{ $editable ? 'Manage Personnel' : 'View Assignments' }} <span aria-hidden="true">→</span></a></div>
                        </article>
                    @empty
                        <div class="pa-empty"><div class="pa-empty-icon">▦</div><strong style="color:#101064;font-size:14px">No matching events</strong><p class="pa-subtitle">Try another search or status filter to see your department's events.</p><a class="pa-button ghost" style="margin-top:15px" href="{{ route('events.personnel-assignment.events') }}">Clear Filters</a></div>
                    @endforelse
                </div>
                @if($events->hasPages())<div class="pa-pagination">{{ $events->links() }}</div>@endif
                <div class="pa-footer">Showing {{ $events->firstItem() ?? 0 }}–{{ $events->lastItem() ?? 0 }} of {{ $events->total() }} matching events · Past event records are view-only</div>
            </div>
        </section>
        <div class="pa-notice">
            <strong style="color:#101064">Email notifications:</strong> New personnel assignments generate queued emails to the account contact address.
            @if(app()->environment('local') && filled(config('services.dysign_personnel_test_email')))
                <strong>Local testing is enabled:</strong> assignment notifications will be redirected to the configured test inbox.
            @else
                For test accounts with placeholder addresses, configure a local-only test inbox before assigning.
            @endif
        </div>
    </div>
</x-admin-layout>
