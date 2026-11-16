<x-admin-layout>
<style>
.pa3{--p-navy:#11145f;--p-gold:#e7ad16;--p-border:#e2e8f2;--p-muted:#61708f;color:#151b49;font-family:inherit}
.pa3 *{box-sizing:border-box}
.pa3 .pa3-hero{position:relative;min-height:178px;border-radius:21px;overflow:hidden;display:flex;align-items:center;padding:27px 32px;background:linear-gradient(105deg,rgba(12,16,88,.98) 2%,rgba(17,21,97,.90) 48%,rgba(22,25,93,.73) 100%),url('{{ asset('images/school.jpg') }}') center/cover;border-bottom:3px solid var(--p-gold);color:white}
.pa3 .pa3-kicker{font-size:10px;letter-spacing:2.2px;text-transform:uppercase;font-weight:850;color:#ffd36b}
.pa3 h1{font-size:30px;line-height:1.18;margin:8px 0 8px;letter-spacing:-.7px;font-weight:850;color:#fff}
.pa3 .pa3-hero p{font-size:13px;line-height:1.65;color:#e9ebfa;max-width:670px;margin:0}
.pa3 .pa3-hero-tag{display:inline-flex;align-items:center;gap:7px;font-size:11px;font-weight:750;padding:8px 12px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:99px;margin-top:15px}
.pa3 .pa3-dot{background:#24d89b;width:7px;height:7px;border-radius:50%;display:inline-block;flex:0 0 auto}
.pa3 .pa3-stats{display:grid;grid-template-columns:1fr 1fr 1.35fr;gap:13px;margin:16px 0}
.pa3 .pa3-panel{border:1px solid var(--p-border);background:#fff;box-shadow:0 3px 10px rgba(18,27,64,.03);border-radius:16px;min-width:0;overflow:hidden}
.pa3 .pa3-stat{padding:17px 20px;display:flex;align-items:center;gap:16px;min-height:94px}
.pa3 .pa3-stat-icon{background:#f0f3fe;color:var(--p-navy);height:42px;width:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex:0 0 auto}
.pa3 .pa3-stat small{display:block;color:var(--p-muted);font-size:11px;font-weight:650;margin-bottom:4px}
.pa3 .pa3-stat strong{font-weight:850;color:var(--p-navy);font-size:23px;line-height:1.15;display:block}
.pa3 .pa3-stat .pa3-next{font-size:16px;letter-spacing:-.3px}
.pa3 .pa3-stat .pa3-sub{display:block;font-size:11px;color:var(--p-muted);margin-top:4px}
.pa3 .pa3-create{margin-bottom:16px}
.pa3 .pa3-create>summary{list-style:none;cursor:pointer;padding:17px 20px;display:flex;align-items:center;justify-content:space-between;gap:14px;user-select:none}
.pa3 .pa3-create>summary::-webkit-details-marker,.pa3 .pa3-edit>summary::-webkit-details-marker,.pa3 .pa3-advanced>summary::-webkit-details-marker{display:none}
.pa3 .pa3-section-label{font-size:10px;letter-spacing:1.6px;color:#b47d00;text-transform:uppercase;font-weight:850;display:block;margin-bottom:4px}
.pa3 .pa3-section-title{font-size:17px;font-weight:850;color:var(--p-navy);margin:0}
.pa3 .pa3-desc{font-size:12px;line-height:1.6;color:var(--p-muted);margin:4px 0 0}
.pa3 .pa3-summary-btn{display:inline-flex;align-items:center;gap:9px;background:var(--p-gold);color:#12154e;border-radius:10px;padding:11px 15px;font-size:12px;font-weight:850;flex:0 0 auto}
.pa3 .pa3-create[open] .pa3-summary-btn{background:#eff2f9}
.pa3 .pa3-create-body{border-top:1px solid #e9edf5;padding:19px 20px}
.pa3 .pa3-form-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:13px;align-items:end}
.pa3 .pa3-field{display:flex;flex-direction:column;gap:7px;min-width:0}
.pa3 .pa3-field label{font-size:11px;font-weight:750;color:#465775}
.pa3 .pa3-input,.pa3 .pa3-textarea{border:1px solid #d5deea;border-radius:10px;background:#fff;outline:none;color:#18224c;font-size:12px;width:100%;min-width:0}
.pa3 .pa3-input{height:41px;padding:0 12px}
.pa3 .pa3-input:focus,.pa3 .pa3-textarea:focus{border-color:#d8aa2e;box-shadow:0 0 0 3px rgba(231,173,22,.15)}
.pa3 .pa3-textarea{resize:vertical;min-height:72px;padding:10px 12px}
.pa3 .pa3-create-foot{display:flex;align-items:center;gap:18px;justify-content:space-between;margin-top:14px}
.pa3 .pa3-create-foot p{font-size:11px;color:var(--p-muted);line-height:1.5;margin:0}
.pa3 .pa3-btn{display:inline-flex;align-items:center;justify-content:center;border:1px solid transparent;border-radius:9px;font-size:12px;font-weight:800;line-height:1.2;min-height:39px;padding:10px 15px;text-decoration:none;cursor:pointer;white-space:nowrap}
.pa3 .pa3-btn-navy{background:var(--p-navy);color:#fff}.pa3 .pa3-btn-navy:hover{background:#292a83}
.pa3 .pa3-btn-gold{background:var(--p-gold);color:#151750}.pa3 .pa3-btn-gold:hover{background:#f5c340}
.pa3 .pa3-btn-white{border-color:#d6dfec;background:#fff;color:var(--p-navy)}
.pa3 .pa3-btn-danger{border-color:#f7c9d0;color:#b22a3d;background:#fff}
.pa3 .pa3-results-head{padding:20px 22px 16px;border-bottom:1px solid #edf0f5;display:flex;justify-content:space-between;gap:12px;align-items:center}
.pa3 .pa3-count{border-radius:99px;background:#f2f5fb;color:#1b2467;padding:6px 10px;font-size:11px;font-weight:800;white-space:nowrap}
.pa3 .pa3-filters{display:flex;flex-wrap:wrap;gap:11px;align-items:flex-end;padding:15px 21px 12px;background:#fbfcff;border-bottom:1px solid #edf0f5}
.pa3 .pa3-search{flex:1 1 280px}.pa3 .pa3-period{flex:0 1 180px}
.pa3 .pa3-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.pa3 .pa3-advanced{flex:1 1 100%;font-size:11px;color:var(--p-muted)}
.pa3 .pa3-advanced>summary{list-style:none;display:inline-flex;align-items:center;gap:7px;color:#1c277b;font-weight:800;cursor:pointer;padding:4px 0}
.pa3 .pa3-advanced-row{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:11px;margin:11px 0 3px}
.pa3 .pa3-list-meta{padding:12px 22px;color:var(--p-muted);font-size:11px;border-bottom:1px solid #eef1f6}
.pa3 .pa3-list-meta strong{color:var(--p-navy)}
.pa3 .pa3-row{display:grid;grid-template-columns:76px minmax(0,1fr) auto;gap:17px;align-items:center;padding:17px 22px;border-bottom:1px solid #edf0f5}
.pa3 .pa3-datebox{border-radius:12px;background:#f3f5fc;border:1px solid #e5e9f4;text-align:center;padding:8px 5px;min-height:72px;display:flex;flex-direction:column;justify-content:center}
.pa3 .pa3-datebox small{font-size:10px;color:#606d92;font-weight:800;letter-spacing:1.3px}
.pa3 .pa3-datebox strong{font-size:25px;color:var(--p-navy);font-weight:850;line-height:1.1;margin:2px 0}
.pa3 .pa3-datebox span{font-size:10px;color:#6e7894}
.pa3 .pa3-record-title{font-size:14px;font-weight:800;color:var(--p-navy)}
.pa3 .pa3-times{font-size:12px;font-weight:750;color:#38496d;margin-top:6px}
.pa3 .pa3-reason{font-size:12px;color:var(--p-muted);line-height:1.5;margin-top:5px;overflow-wrap:anywhere}
.pa3 .pa3-pill{font-size:10px;font-weight:850;border-radius:99px;padding:6px 10px;white-space:nowrap;display:inline-block}
.pa3 .pa3-pill-up{color:#8a5a00;background:#fff4da}.pa3 .pa3-pill-past{color:#5e6d83;background:#f1f3f7}
.pa3 .pa3-right{display:flex;align-items:end;gap:9px;flex-direction:column;justify-self:end}
.pa3 .pa3-record-actions{display:flex;gap:7px;align-items:center}
.pa3 .pa3-edit{grid-column:2/-1;border:1px solid #e2e8f3;border-radius:12px;background:#f9faff;padding:12px 14px}
.pa3 .pa3-edit>summary{font-size:12px;color:#242575;font-weight:800;cursor:pointer;list-style:none}
.pa3 .pa3-edit-form{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:11px;margin-top:14px}
.pa3 .pa3-row-edit{grid-column:1/-1;display:none}
.pa3 .pa3-row-edit:has(details[open]){display:block}
.pa3 .pa3-pagination{padding:16px 20px}.pa3 .pa3-empty{text-align:center;padding:34px 15px}
.pa3 .pa3-notice{padding:13px 17px;border-radius:12px;font-size:12px;margin:15px 0}
.pa3 .pa3-notice-success{background:#ecfcf3;color:#065b35;border:1px solid #b8ebce}
.pa3 .pa3-notice-error{background:#fff1f2;color:#9c1b29;border:1px solid #fecdd3}
@media(max-width:900px){.pa3 .pa3-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.pa3 .pa3-stat:last-child{grid-column:1/-1}.pa3 .pa3-advanced-row{grid-template-columns:1fr 1fr}}
@media(max-width:600px){.pa3 .pa3-hero{min-height:0;padding:24px 22px}.pa3 h1{font-size:25px}.pa3 .pa3-stats{grid-template-columns:1fr}.pa3 .pa3-stat:last-child{grid-column:auto}.pa3 .pa3-create>summary{align-items:start;flex-direction:column}.pa3 .pa3-form-grid{grid-template-columns:1fr}.pa3 .pa3-create-foot{align-items:stretch;flex-direction:column}.pa3 .pa3-filters{display:grid;grid-template-columns:1fr 1fr}.pa3 .pa3-search,.pa3 .pa3-advanced{grid-column:1/-1}.pa3 .pa3-actions{grid-column:1/-1}.pa3 .pa3-advanced-row{grid-template-columns:1fr 1fr}.pa3 .pa3-row{grid-template-columns:64px minmax(0,1fr);gap:12px;padding:15px}.pa3 .pa3-right{grid-column:2;flex-direction:row;justify-self:start;align-items:center;flex-wrap:wrap}.pa3 .pa3-datebox{min-height:68px}.pa3 .pa3-edit-form{grid-template-columns:1fr 1fr}.pa3 .pa3-results-head{align-items:start}.pa3 .pa3-record-actions{flex-wrap:wrap}}
@media(max-width:410px){.pa3 .pa3-filters{grid-template-columns:1fr}.pa3 .pa3-search,.pa3 .pa3-advanced,.pa3 .pa3-actions{grid-column:1}.pa3 .pa3-advanced-row{grid-template-columns:1fr}.pa3 .pa3-edit-form{grid-template-columns:1fr}}
</style>

<div class="pa3 space-y-4">
    <header class="pa3-hero">
        <div>
            <div class="pa3-kicker">Attendance Personnel · My Schedule</div>
            <h1>My Availability</h1>
            <p>Manage the times you're unavailable for event duties. Department Staff can review your declared schedule when planning assignments.</p>
            <div class="pa3-hero-tag"><span class="pa3-dot"></span> Available by default outside your declared periods</div>
        </div>
    </header>

    @if(session('success'))<div class="pa3-notice pa3-notice-success" role="status">{{ session('success') }}</div>@endif
    @if($errors->any())
        <div class="pa3-notice pa3-notice-error" role="alert"><strong>Please review the following:</strong>
            <ul style="margin:7px 0 0 20px;list-style:disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="pa3-stats">
        <div class="pa3-panel pa3-stat"><div class="pa3-stat-icon">◷</div><div><small>Upcoming unavailable</small><strong>{{ $upcomingCount }}</strong><span class="pa3-sub">Declared future periods</span></div></div>
        <div class="pa3-panel pa3-stat"><div class="pa3-stat-icon">▤</div><div><small>Total recorded</small><strong>{{ $totalCount }}</strong><span class="pa3-sub">All saved periods</span></div></div>
        <div class="pa3-panel pa3-stat"><div class="pa3-stat-icon">▣</div><div><small>Next unavailable period</small>
            @if($nextUnavailable)
                <strong class="pa3-next">{{ $nextUnavailable->availability_date->format('D, M j, Y') }}</strong>
                <span class="pa3-sub">{{ \Carbon\Carbon::parse($nextUnavailable->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($nextUnavailable->end_time)->format('g:i A') }}</span>
            @else
                <strong class="pa3-next">None scheduled</strong><span class="pa3-sub">No future unavailability declared</span>
            @endif
        </div></div>
    </div>

    <details class="pa3-panel pa3-create" id="add-unavailability" @if($errors->any()) open @endif>
        <summary>
            <span><span class="pa3-section-label">Availability planner</span><span class="pa3-section-title">Declare an unavailable period</span><span class="pa3-desc" style="display:block">Only add dates and times when you cannot accept an attendance assignment.</span></span>
            <span class="pa3-summary-btn"><span aria-hidden="true">＋</span> Add Unavailable Time</span>
        </summary>
        <div class="pa3-create-body">
            <form method="POST" action="{{ route('personnel-availability.store') }}" data-confirm data-confirm-title="Save unavailable period?" data-confirm-message="This period will be marked unavailable for your future duties." data-confirm-confirm-text="Save Period">
                @csrf
                <div class="pa3-form-grid">
                    <div class="pa3-field"><label for="pa3-new-date">Unavailable date *</label><input id="pa3-new-date" name="availability_date" type="date" class="pa3-input" value="{{ old('availability_date') }}" min="{{ $today }}" required></div>
                    <div class="pa3-field"><label for="pa3-new-start">From *</label><input id="pa3-new-start" name="start_time" type="time" class="pa3-input" value="{{ old('start_time') }}" required></div>
                    <div class="pa3-field"><label for="pa3-new-end">Until *</label><input id="pa3-new-end" name="end_time" type="time" class="pa3-input" value="{{ old('end_time') }}" required></div>
                    <div class="pa3-field" style="grid-column:1/-1"><label for="pa3-new-reason">Reason (optional)</label><textarea id="pa3-new-reason" name="reason" class="pa3-textarea" maxlength="1000" rows="2" placeholder="e.g. Class schedule, meeting, or personal commitment">{{ old('reason') }}</textarea></div>
                </div>
                <div class="pa3-create-foot"><p>Unlisted times remain available. Overlapping periods and past dates are not allowed.</p><button type="submit" class="pa3-btn pa3-btn-gold">Save Unavailable Period</button></div>
            </form>
        </div>
    </details>

    <section class="pa3-panel" id="schedule-list">
        <div class="pa3-results-head">
            <div><span class="pa3-section-label">Schedule & history</span><h2 class="pa3-section-title">My Unavailable Periods</h2><p class="pa3-desc">Review and manage your saved unavailability.</p></div>
            <span class="pa3-count">{{ $entries->total() }} matching</span>
        </div>
        <form method="GET" action="{{ route('personnel-availability.index') }}" class="pa3-filters">
            <div class="pa3-field pa3-search"><label for="pa3-query">Search reason or date</label><input class="pa3-input" id="pa3-query" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Search by reason or YYYY-MM-DD"></div>
            <div class="pa3-field pa3-period"><label for="pa3-period">Show schedules</label><select class="pa3-input" id="pa3-period" name="period"><option value="upcoming" @selected($filters['period'] === 'upcoming')>Upcoming</option><option value="past" @selected($filters['period'] === 'past')>Past</option><option value="all" @selected($filters['period'] === 'all')>All dates</option></select></div>
            <div class="pa3-actions"><button class="pa3-btn pa3-btn-navy" type="submit">Search / Apply</button><a class="pa3-btn pa3-btn-white" href="{{ route('personnel-availability.index') }}">Reset</a></div>
            <details class="pa3-advanced" @if($filters['from'] || $filters['to'] || $filters['sort'] !== 'soonest' || $filters['per_page'] !== 5) open @endif>
                <summary>＋ More filters <span style="font-weight:500;color:#8491a9">(date range, sorting, page size)</span></summary>
                <div class="pa3-advanced-row">
                    <div class="pa3-field"><label for="pa3-from">From date</label><input id="pa3-from" name="from" type="date" value="{{ $filters['from'] }}" class="pa3-input"></div>
                    <div class="pa3-field"><label for="pa3-to">To date</label><input id="pa3-to" name="to" type="date" value="{{ $filters['to'] }}" class="pa3-input"></div>
                    <div class="pa3-field"><label for="pa3-sort">Sort order</label><select id="pa3-sort" name="sort" class="pa3-input"><option value="soonest" @selected($filters['sort'] === 'soonest')>Earliest first</option><option value="latest" @selected($filters['sort'] === 'latest')>Latest first</option></select></div>
                    <div class="pa3-field"><label for="pa3-per-page">Rows per page</label><select id="pa3-per-page" name="per_page" class="pa3-input"><option value="5" @selected($filters['per_page'] === 5)>5 periods</option><option value="10" @selected($filters['per_page'] === 10)>10 periods</option><option value="20" @selected($filters['per_page'] === 20)>20 periods</option></select></div>
                </div>
            </details>
        </form>
        @if($entries->total())<div class="pa3-list-meta">Showing <strong>{{ $entries->firstItem() }}–{{ $entries->lastItem() }}</strong> of {{ $entries->total() }} periods</div>@endif
        @forelse($entries as $availability)
            @php
                $dateString = $availability->availability_date->format('Y-m-d');
                $startString = substr((string)$availability->start_time,0,5);
                $endString = substr((string)$availability->end_time,0,5);
                $isPast = $dateString < $today || ($dateString === $today && $endString <= substr($timeNow,0,5));
                $canEdit = $dateString > $today || ($dateString === $today && $startString > substr($timeNow,0,5));
                $canDelete = $dateString >= $today;
            @endphp
            <article class="pa3-row">
                <div class="pa3-datebox"><small>{{ $availability->availability_date->format('M') }}</small><strong>{{ $availability->availability_date->format('d') }}</strong><span>{{ $availability->availability_date->format('Y') }}</span></div>
                <div><div class="pa3-record-title">{{ $availability->availability_date->format('l, F j') }}</div><div class="pa3-times">{{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}</div><div class="pa3-reason">{{ $availability->reason ?: 'No reason provided' }}</div></div>
                <div class="pa3-right">
                    <span class="pa3-pill {{ $isPast ? 'pa3-pill-past' : 'pa3-pill-up' }}">{{ $isPast ? 'Past' : 'Unavailable' }}</span>
                    @if($canEdit || $canDelete)
                        <div class="pa3-record-actions">
                            @if($canEdit)<button type="button" class="pa3-btn pa3-btn-white" onclick="document.getElementById('pa3-edit-{{ $availability->availability_id }}').open = !document.getElementById('pa3-edit-{{ $availability->availability_id }}').open">Edit</button>@endif
                            @if($canDelete)
                                <form method="POST" action="{{ route('personnel-availability.destroy', $availability->availability_id) }}" data-confirm data-confirm-title="Remove unavailable period?" data-confirm-message="This unavailable period will be deleted from your schedule." data-confirm-confirm-text="Remove" data-confirm-variant="danger">@csrf @method('DELETE')<button type="submit" class="pa3-btn pa3-btn-danger">Remove</button></form>
                            @endif
                        </div>
                    @endif
                </div>
                @if($canEdit)
                <div class="pa3-row-edit"><details id="pa3-edit-{{ $availability->availability_id }}" class="pa3-edit">
                    <summary>Update {{ $availability->availability_date->format('M j') }} unavailable period</summary>
                    <form method="POST" action="{{ route('personnel-availability.update', $availability->availability_id) }}" class="pa3-edit-form" data-confirm data-confirm-title="Update unavailable period?" data-confirm-message="Save your updated schedule. The original record will be replaced." data-confirm-confirm-text="Save Changes">
                        @csrf @method('PUT')
                        <div class="pa3-field"><label>Date</label><input name="availability_date" type="date" class="pa3-input" value="{{ $dateString }}" min="{{ $today }}" required></div>
                        <div class="pa3-field"><label>Start time</label><input name="start_time" type="time" class="pa3-input" value="{{ $startString }}" required></div>
                        <div class="pa3-field"><label>End time</label><input name="end_time" type="time" class="pa3-input" value="{{ $endString }}" required></div>
                        <div class="pa3-field" style="grid-column:1/-1"><label>Reason (optional)</label><textarea name="reason" maxlength="1000" class="pa3-textarea" rows="2">{{ $availability->reason }}</textarea></div>
                        <div style="grid-column:1/-1"><button type="submit" class="pa3-btn pa3-btn-navy">Save Changes</button></div>
                    </form>
                </details></div>
                @endif
            </article>
        @empty
            <div class="pa3-empty"><div style="font-size:26px;opacity:.7">▣</div><h3 class="pa3-section-title" style="font-size:15px;margin:8px 0">No matching unavailable periods</h3><p class="pa3-desc">Try different filters or add a new unavailable period above.</p></div>
        @endforelse
        @if($entries->hasPages())<div class="pa3-pagination">{{ $entries->links() }}</div>@endif
    </section>
</div>
</x-admin-layout>
