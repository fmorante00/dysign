<x-admin-layout>
    @php
        /* The existing registration controller supplies these four values. */
        $entries = $events->map(function ($event) use ($registrations, $eligibilities) {
            $registration = $registrations->get($event->event_id);
            $status = $event->calculateScheduledStatus();
            $isRegistered = $registration?->status === 'Registered';
            $wasCancelled = $registration?->status === 'Cancelled';
            // The eligibility record is the authoritative source of Required/Optional.
            // Never assume Optional if a type is missing or unrecognized.
            $participation = $eligibilities->get($event->event_id)?->participation_type;
            $isRequired = $participation === 'Required';
            $isOptional = $participation === 'Optional';
            $isOpen = $isOptional && $status === 'Upcoming';
            $isClosedOptional = $isOptional && !$isOpen;
            $isOptionalRegistered = $isOptional && $isRegistered;

            return compact('event', 'registration', 'status', 'isRegistered', 'wasCancelled',
                'isOpen', 'participation', 'isRequired', 'isOptional', 'isClosedOptional', 'isOptionalRegistered');
        });

        $requiredCount = $entries->where('isRequired', true)->count();
        $openCount = $entries->where('isOpen', true)->count();
        $registeredCount = $entries->where('isOptionalRegistered', true)->count();
        $closedCount = $entries->where('isClosedOptional', true)->count();
    @endphp

    <style>
        /* Scope all custom styles to Student Registration. No shared layout changes. */
        [x-cloak] { display: none !important; }
        .dsr { --dy-navy:#101064; --dy-gold:#D4A017; --dy-line:#E2E7F0; --dy-muted:#64748b; color:#15213C; }
        .dsr * { box-sizing:border-box; }
        .dsr-hero { position:relative; isolation:isolate; overflow:hidden; border-radius:28px; padding:35px 37px; color:white;
            background:linear-gradient(101deg,rgba(13,14,75,.97) 0%,rgba(16,16,100,.90) 51%,rgba(16,16,100,.72) 100%),url('{{ asset('images/school.jpg') }}') center/cover;
            box-shadow:0 8px 22px rgba(19,30,65,.08); }
        .dsr-hero::before,.dsr-hero::after{content:'';position:absolute;border:1px solid rgba(255,255,255,.11);border-radius:50%;pointer-events:none;z-index:0;}
        .dsr-hero > *{position:relative;z-index:1;}
        .dsr-hero::before{width:340px;height:340px;right:-90px;top:-185px;}
        .dsr-hero::after{width:260px;height:260px;right:-39px;top:-147px;}
        .dsr-eyebrow{display:inline-flex;align-items:center;gap:9px;font-size:10px;font-weight:800;letter-spacing:.24em;text-transform:uppercase;color:#F3D772;}
        .dsr-eyebrow::before{content:'';display:block;width:22px;height:3px;background:#D4A017;border-radius:4px;}
        .dsr-hero h1{font-weight:800;font-size:clamp(26px,3vw,37px);line-height:1.17;letter-spacing:-.035em;margin:13px 0 8px;}
        .dsr-hero p{font-size:13px;line-height:1.8;color:rgba(255,255,255,.79);max-width:650px;}
        .dsr-hero-foot{display:flex;flex-wrap:wrap;gap:10px;margin-top:25px;}
        .dsr-chip{display:inline-flex;align-items:center;gap:6px;padding:9px 12px;border:1px solid rgba(255,255,255,.17);border-radius:999px;background:rgba(255,255,255,.1);font-size:11px;font-weight:600;color:#F7F7FF;}
        .dsr-stat-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;}
        .dsr-stat{border-radius:18px;border:1px solid var(--dy-line);background:#fff;padding:21px 23px;box-shadow:0 3px 9px rgba(23,38,69,.035);display:flex;justify-content:space-between;gap:12px;align-items:flex-start;}
        .dsr-stat-label{color:#65748D;font-size:12px;font-weight:650;}
        .dsr-stat-number{font-size:29px;font-weight:800;line-height:1.15;color:var(--dy-navy);letter-spacing:-.045em;margin-top:12px;}
        .dsr-stat-desc{color:#8290A4;font-size:10px;line-height:1.6;margin-top:9px;}
        .dsr-stat-icon{display:grid;place-items:center;min-width:44px;height:44px;border-radius:12px;background:#EFF0FB;color:var(--dy-navy);}
        .dsr-stat-icon.gold{background:#FFF8E6;color:#AC7F12;}
        .dsr-stat-icon.green{background:#EAF8F1;color:#087A55;}
        .dsr-panel{border:1px solid var(--dy-line);border-radius:22px;background:#fff;box-shadow:0 5px 16px rgba(18,34,64,.035);overflow:hidden;}
        .dsr-panel-head{display:flex;justify-content:space-between;gap:18px;flex-wrap:wrap;align-items:center;padding:25px 26px;border-bottom:1px solid #EEF1F6;}
        .dsr-panel-title{color:var(--dy-navy);font-size:19px;line-height:1.3;font-weight:800;letter-spacing:-.02em;margin-top:8px;}
        .dsr-panel-sub{font-size:12px;line-height:1.7;color:#748198;margin-top:5px;}
        .dsr-search{display:flex;align-items:center;gap:10px;min-width:230px;border:1px solid #DFE5EF;border-radius:11px;background:#F8FAFD;padding:10px 12px;}
        .dsr-search:focus-within{border-color:#D4A017;box-shadow:0 0 0 3px rgba(212,160,23,.09);}
        .dsr-search input{width:100%;border:0;outline:0;background:transparent;color:#15213C;font-size:12px;}
        .dsr-search input::placeholder{color:#94A3B8;}
        .dsr-toolbar{padding:15px 25px;border-bottom:1px solid #EDF0F5;display:flex;gap:8px;flex-wrap:wrap;background:#FCFDFE;}
        .dsr-filter{border:1px solid transparent;border-radius:9px;padding:9px 13px;font-size:11px;font-weight:750;color:#63708A;background:transparent;transition:.16s;}
        .dsr-filter:hover{background:#F1F3F9;color:#101064;}
        .dsr-filter.is-active{background:#101064;color:white;box-shadow:0 3px 9px rgba(16,16,100,.15);}
        .dsr-card-list{padding:18px 22px 24px;display:grid;gap:14px;}
        .dsr-event{border:1px solid #E5EAF2;border-radius:18px;background:white;overflow:hidden;transition:border-color .15s,box-shadow .15s;}
        .dsr-event:hover{border-color:#CDD5E4;box-shadow:0 7px 22px rgba(17,29,56,.055);}
        .dsr-event-main{padding:20px 21px 20px;display:grid;grid-template-columns:65px minmax(0,1fr) 188px;gap:20px;align-items:start;}
        .dsr-date{border:1px solid #E2E7F1;border-radius:14px;background:#F3F4FB;text-align:center;padding:12px 8px 11px;color:#101064;}
        .dsr-date-month{font-size:10px;font-weight:850;letter-spacing:.1em;text-transform:uppercase;}
        .dsr-date-day{font-size:25px;line-height:1.15;font-weight:850;letter-spacing:-.04em;margin-top:3px;}
        .dsr-date-year{font-size:10px;margin-top:3px;color:#65748D;}
        .dsr-badges{display:flex;flex-wrap:wrap;align-items:center;gap:7px;margin-bottom:9px;}
        .dsr-badge{display:inline-flex;align-items:center;gap:5px;border:1px solid transparent;border-radius:7px;padding:5px 8px;font-size:10px;font-weight:750;line-height:1.1;white-space:nowrap;}
        .dsr-badge.blue{color:#303080;background:#F0F1FB;border-color:#E5E6F7;}
        .dsr-badge.gold{color:#8B670D;background:#FFF7E4;border-color:#F9E9BD;}
        .dsr-badge.green{color:#087A55;background:#EAF8F1;border-color:#D4F1E3;}
        .dsr-badge.grey{color:#5F6C81;background:#F1F3F6;border-color:#E9EDF2;}
        .dsr-badge.red{color:#B32632;background:#FFF0F1;border-color:#F9D8DC;}
        .dsr-event-title{color:#101064;font-size:16px;line-height:1.35;letter-spacing:-.01em;font-weight:800;}
        .dsr-event-description{font-size:12px;color:#65748D;line-height:1.75;margin-top:7px;max-width:600px;}
        .dsr-meta{display:flex;gap:9px 20px;flex-wrap:wrap;margin-top:15px;}
        .dsr-meta-item{font-size:11px;line-height:1.5;color:#596882;display:inline-flex;align-items:center;gap:6px;}
        .dsr-meta-item svg{flex-shrink:0;color:#9B791F;}
        .dsr-action{display:flex;flex-direction:column;gap:8px;align-items:stretch;align-self:center;}
        .dsr-action button,.dsr-action .dsr-action-text{min-height:42px;width:100%;display:flex;align-items:center;justify-content:center;text-align:center;border-radius:11px;font-size:11px;font-weight:800;padding:9px 12px;transition:.16s;}
        .dsr-btn-primary{background:#101064;color:white;border:1px solid #101064;cursor:pointer;}
        .dsr-btn-primary:hover{background:#282880;}
        .dsr-btn-outline{background:white;color:#A42A36;border:1px solid #F0CCD1;cursor:pointer;}
        .dsr-btn-outline:hover{background:#FFF6F7;}
        .dsr-action-text{background:#F3F5F8;color:#65748D;border:1px solid #EAEDF2;}
        .dsr-action-text.required{background:#EFF6F1;color:#087A55;border-color:#CFE9D9;}
        .dsr-action-note{text-align:center;color:#8A96A8;font-size:10px;line-height:1.55;}
        .dsr-event-foot{padding:11px 22px 12px;border-top:1px solid #F0F2F6;background:#FBFCFE;display:flex;gap:8px;align-items:center;flex-wrap:wrap;color:#77859C;font-size:10px;line-height:1.55;}
        .dsr-event-foot svg{color:#AF8522;flex-shrink:0;}
        .dsr-empty{padding:58px 18px;text-align:center;}
        .dsr-empty-icon{width:56px;height:56px;margin:0 auto 15px;display:grid;place-items:center;border-radius:18px;background:#F0F1FA;color:#101064;}
        .dsr-empty h3{font-size:15px;font-weight:800;color:#101064;}
        .dsr-empty p{font-size:12px;color:#718096;line-height:1.75;max-width:430px;margin:7px auto 0;}
        .dsr-alert{padding:14px 17px;border-radius:13px;font-size:12px;line-height:1.6;border:1px solid;}
        .dsr-alert.success{color:#08684A;border-color:#BFE6D5;background:#ECFAF3;}
        .dsr-alert.error{color:#9E2631;border-color:#F2C7CD;background:#FFF1F2;}
        .dsr-modal-overlay{position:fixed;inset:0;z-index:1000;display:flex;align-items:center;justify-content:center;padding:18px;background:rgba(9,17,52,.69);backdrop-filter:blur(3px);}
        .dsr-modal{width:100%;max-width:440px;background:white;overflow:hidden;border-radius:21px;box-shadow:0 25px 70px rgba(0,0,0,.24);}
        .dsr-modal-head{padding:25px 26px 22px;background:#101064;color:white;position:relative;overflow:hidden;}
        .dsr-modal-head::after{content:'';position:absolute;border:1px solid rgba(255,255,255,.13);border-radius:50%;width:200px;height:200px;right:-60px;top:-128px;}
        .dsr-modal-body{padding:24px 26px 27px;}
        .dsr-modal-actions{display:flex;justify-content:end;gap:9px;margin-top:23px;flex-wrap:wrap;}
        .dsr-modal-actions button{border-radius:10px;padding:11px 17px;font-size:12px;font-weight:750;}
        .dsr-modal-secondary{border:1px solid #DDE4EE;background:white;color:#334155;}
        .dsr-modal-danger{border:1px solid #B6283C;background:#B6283C;color:white;}
        @media (max-width:1100px){.dsr-stat-grid{grid-template-columns:repeat(2,minmax(0,1fr));}.dsr-event-main{grid-template-columns:65px minmax(0,1fr);}.dsr-action{grid-column:2;flex-direction:row;justify-content:flex-start;align-items:center;}.dsr-action button,.dsr-action .dsr-action-text{width:auto;min-width:155px;}.dsr-action-note{text-align:left;}}
        @media (max-width:740px){.dsr-hero{padding:28px 24px;}.dsr-stat-grid{grid-template-columns:1fr;}.dsr-panel-head{padding:22px 19px;}.dsr-search{width:100%;}.dsr-toolbar{padding:13px 14px;}.dsr-card-list{padding:14px;}.dsr-event-main{padding:16px;grid-template-columns:56px minmax(0,1fr);gap:13px;}.dsr-date{padding:11px 5px;}.dsr-date-day{font-size:22px;}.dsr-action{grid-column:1/-1;flex-wrap:wrap;}.dsr-event-foot{padding:11px 15px;}}
        @media (prefers-reduced-motion:reduce){.dsr *{transition:none !important;}}
    </style>

    <div class="dsr min-w-0 space-y-6 pb-10" x-data="{
        tab: 'all',
        search: '',
        cancelId: null,
        cancelName: '',
        matches(el) {
            const d = el.dataset;
            const category = this.tab === 'all'
                || (this.tab === 'required' && d.required === '1')
                || (this.tab === 'open' && d.open === '1')
                || (this.tab === 'registered' && d.registered === '1')
                || (this.tab === 'closed' && d.closed === '1');
            return category && d.search.includes(this.search.trim().toLowerCase());
        },
        visibleCount() {
            // Use the event-list element, not $el (which can refer to the empty-state node).
            const eventList = this.$refs.eventList;
            return eventList
                ? [...eventList.querySelectorAll('[data-dsr-event]')].filter(el => this.matches(el)).length
                : 0;
        },
        showCancel(id, name) { this.cancelId = id; this.cancelName = name; this.$nextTick(() => this.$refs.cancelClose?.focus()); },
        closeCancel() { this.cancelId = null; this.cancelName = ''; },
        confirmCancel() {
            const form = document.getElementById('dsr-cancel-form-' + this.cancelId);
            if (form) form.requestSubmit();
        }
    }" @keydown.escape.window="closeCancel()">

        <section class="dsr-hero" aria-labelledby="dsr-title">
            <div class="dsr-eyebrow">DySign · Student Portal</div>
            <h1 id="dsr-title">My Event Registrations</h1>
            <p>View your required campus activities and choose which optional events to join. Official attendance is recorded through RFID check-in.</p>
            <div class="dsr-hero-foot">
                <span class="dsr-chip">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M8 3v4m8-4v4M4 10h16M5 5h14v15H5z"/></svg>
                    Official campus activities
                </span>
                <span class="dsr-chip">Required: automatically included · Optional: registration needed</span>
            </div>
        </section>

        @if (session('success'))
            <div class="dsr-alert success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="dsr-alert error" role="alert">
                <strong>We couldn't complete your request.</strong>
                @foreach ($errors->all() as $message)
                    <div class="mt-1">{{ $message }}</div>
                @endforeach
            </div>
        @endif

        <section class="dsr-stat-grid" aria-label="Registration overview">
            <div class="dsr-stat">
                <div><p class="dsr-stat-label">Eligible Events</p><p class="dsr-stat-number">{{ number_format($entries->count()) }}</p><p class="dsr-stat-desc">Events on your eligibility list</p></div>
                <div class="dsr-stat-icon"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M8 3v4m8-4v4M4 10h16M5 5h14v15H5z"/></svg></div>
            </div>
            <div class="dsr-stat">
                <div><p class="dsr-stat-label">Required Attendance</p><p class="dsr-stat-number">{{ number_format($requiredCount) }}</p><p class="dsr-stat-desc">Already included; RFID check-in still required</p></div>
                <div class="dsr-stat-icon green"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg></div>
            </div>
            <div class="dsr-stat">
                <div><p class="dsr-stat-label">Optional Registration Open</p><p class="dsr-stat-number">{{ number_format($openCount) }}</p><p class="dsr-stat-desc">Upcoming optional events accepting registration</p></div>
                <div class="dsr-stat-icon gold"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 3v18M3 12h18"/></svg></div>
            </div>
            <div class="dsr-stat">
                <div><p class="dsr-stat-label">My Optional Registrations</p><p class="dsr-stat-number">{{ number_format($registeredCount) }}</p><p class="dsr-stat-desc">Active optional event registrations</p></div>
                <div class="dsr-stat-icon"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M8 3v4m8-4v4M4 10h16M5 5h14v15H5z"/></svg></div>
            </div>
        </section>

        <section class="dsr-panel" aria-labelledby="dsr-events-title">
            <div class="dsr-panel-head">
                <div>
                    <div class="dsr-eyebrow" style="color:#93731B">Campus Events</div>
                    <h2 id="dsr-events-title" class="dsr-panel-title">My Eligible Events</h2>
                    <p class="dsr-panel-sub">Required students are included automatically. Only optional events need registration.</p>
                </div>
                <label class="dsr-search">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="1.9" aria-hidden="true"><circle cx="10.8" cy="10.8" r="7"/><path d="m16 16 5 5"/></svg>
                    <input type="search" x-model.debounce.200ms="search" placeholder="Search events or venues" aria-label="Search eligible events">
                </label>
            </div>
            <div class="dsr-toolbar" role="group" aria-label="Filter events">
                <button type="button" class="dsr-filter" :class="tab === 'all' && 'is-active'" :aria-pressed="tab === 'all'" @click="tab='all'">All Events <span>({{ $entries->count() }})</span></button>
                <button type="button" class="dsr-filter" :class="tab === 'required' && 'is-active'" :aria-pressed="tab === 'required'" @click="tab='required'">Required <span>({{ $requiredCount }})</span></button>
                <button type="button" class="dsr-filter" :class="tab === 'open' && 'is-active'" :aria-pressed="tab === 'open'" @click="tab='open'">Optional — Open <span>({{ $openCount }})</span></button>
                <button type="button" class="dsr-filter" :class="tab === 'registered' && 'is-active'" :aria-pressed="tab === 'registered'" @click="tab='registered'">My Optional Registrations <span>({{ $registeredCount }})</span></button>
                <button type="button" class="dsr-filter" :class="tab === 'closed' && 'is-active'" :aria-pressed="tab === 'closed'" @click="tab='closed'">Optional — Closed / Past <span>({{ $closedCount }})</span></button>
            </div>

            <div class="dsr-card-list" x-ref="eventList">
                @forelse ($entries as $entry)
                    @php
                        $event = $entry['event'];
                        $status = $entry['status'];
                        $isRegistered = $entry['isRegistered'];
                        $wasCancelled = $entry['wasCancelled'];
                        $isOpen = $entry['isOpen'];
                        $isRequired = $entry['isRequired'];
                        $isOptional = $entry['isOptional'];
                        $isClosedOptional = $entry['isClosedOptional'];
                        $isOptionalRegistered = $entry['isOptionalRegistered'];
                        $participation = $entry['participation'];
                        $date = $event->event_date;
                        $startTime = $event->start_time ? \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') : 'To be announced';
                        $endTime = $event->end_time ? \Illuminate\Support\Carbon::parse($event->end_time)->format('g:i A') : null;
                    @endphp
                    <article data-dsr-event
                        data-required="{{ $isRequired ? '1' : '0' }}"
                        data-open="{{ $isOpen ? '1' : '0' }}"
                        data-registered="{{ $isOptionalRegistered ? '1' : '0' }}"
                        data-closed="{{ $isClosedOptional ? '1' : '0' }}"
                        data-search="{{ strtolower((string) $event->event_name . ' ' . (string) $event->location . ' ' . (string) ($event->department?->department_name ?? '')) }}"
                        x-show="matches($el)"
                        class="dsr-event">
                        <div class="dsr-event-main">
                            <div class="dsr-date" aria-hidden="true">
                                <div class="dsr-date-month">{{ $date->format('M') }}</div>
                                <div class="dsr-date-day">{{ $date->format('d') }}</div>
                                <div class="dsr-date-year">{{ $date->format('Y') }}</div>
                            </div>
                            <div>
                                <div class="dsr-badges">
                                    <span class="dsr-badge {{ $isRequired ? 'blue' : ($isOptional ? 'gold' : 'grey') }}">{{ $isRequired ? 'Required attendance' : ($isOptional ? 'Optional event' : 'Eligibility pending') }}</span>
                                    <span class="dsr-badge {{ $status === 'Upcoming' ? 'gold' : ($status === 'Ongoing' ? 'green' : ($status === 'Cancelled' ? 'red' : 'grey')) }}">{{ $status }}</span>
                                    @if ($isRequired)
                                        <span class="dsr-badge green">Automatically included</span>
                                        @if ($isRegistered)
                                            <span class="dsr-badge grey">Previous registration on record</span>
                                        @endif
                                    @elseif ($isOptional)
                                        <span class="dsr-badge {{ $isOpen ? 'gold' : 'grey' }}">{{ $isOpen ? 'Registration open' : 'Registration closed' }}</span>
                                        @if ($isRegistered)
                                            <span class="dsr-badge green">{{ $status === 'Cancelled' ? 'Previously registered' : 'You are registered' }}</span>
                                        @elseif ($wasCancelled)
                                            <span class="dsr-badge grey">You cancelled your registration</span>
                                        @else
                                            <span class="dsr-badge grey">Not registered</span>
                                        @endif
                                    @endif
                                </div>
                                <h3 class="dsr-event-title">{{ $event->event_name }}</h3>
                                @if ($event->description)
                                    <p class="dsr-event-description">{{ $event->description }}</p>
                                @endif
                                <div class="dsr-meta">
                                    <span class="dsr-meta-item"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M8 3v4m8-4v4M4 10h16M5 5h14v15H5z"/></svg>{{ $date->format('M d, Y') }}</span>
                                    <span class="dsr-meta-item"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>{{ $startTime }}{{ $endTime ? ' – ' . $endTime : '' }}</span>
                                    <span class="dsr-meta-item"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>{{ $event->location ?: 'Venue to be announced' }}</span>
                                    <span class="dsr-meta-item"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 10 12 4l9 6M5 10v10h14V10M10 20v-7h4v7"/></svg>{{ $event->department?->department_name ?? 'School Event' }}</span>
                                </div>
                            </div>
                            <div class="dsr-action">
                                @if ($isRequired)
                                    <div class="dsr-action-text required">Attendance Required</div>
                                    <div class="dsr-action-note">No registration needed. You are on the expected attendance list; check in by RFID.</div>
                                @elseif (!$isOptional)
                                    <div class="dsr-action-text">Eligibility Needs Review</div>
                                    <div class="dsr-action-note">Please contact your department to confirm your participation type.</div>
                                @elseif (!$isOpen)
                                    <div class="dsr-action-text">Registration Closed</div>
                                    <div class="dsr-action-note">{{ $status === 'Cancelled' ? 'This event was cancelled' : ($status === 'Ongoing' ? 'Event already started' : 'Event has ended') }}</div>
                                @elseif ($isRegistered)
                                    <button type="button" class="dsr-btn-outline" data-event-name="{{ $event->event_name }}" @click="showCancel({{ (int) $event->event_id }}, $el.dataset.eventName)">Cancel Registration</button>
                                    <div class="dsr-action-note">Your spot is currently registered</div>
                                    <form id="dsr-cancel-form-{{ (int) $event->event_id }}" action="{{ route('student.registrations.cancel', $event->event_id) }}" method="POST" hidden>
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @else
                                    <form action="{{ route('student.registrations.store', $event->event_id) }}" method="POST">
                                        @csrf
                                        <button class="dsr-btn-primary" type="submit">{{ $wasCancelled ? 'Register Again' : 'Register Now' }}</button>
                                    </form>
                                    <div class="dsr-action-note">{{ $wasCancelled ? 'Registration currently cancelled' : 'Registration is currently open' }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="dsr-event-foot">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5m0-8h.01"/></svg>
                            @if ($status === 'Cancelled')
                                This event was cancelled. Attendance and registration actions are unavailable.
                            @elseif ($isRequired)
                                You are expected to attend. Being required does not mark you Present; RFID attendance is still needed.
                            @elseif (!$isOptional)
                                Your participation type is missing or unrecognized. Please ask your department to check eligibility.
                            @elseif ($isRegistered && !$isOpen)
                                Your optional registration is recorded; the registration window is now closed.
                            @elseif (!$isOpen)
                                This optional event is visible for reference, but registration is closed.
                            @else
                                Optional registration closes when the event starts, subject to eligibility checks.
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="dsr-empty">
                        <div class="dsr-empty-icon"><svg width="27" height="27" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M8 3v4m8-4v4M4 10h16M5 5h14v15H5zM9 15h6"/></svg></div>
                        <h3>No eligible events yet</h3>
                        <p>Events assigned to your student record will appear here when your department publishes eligibility.</p>
                    </div>
                @endforelse

                @if ($entries->isNotEmpty())
                    <div x-show="visibleCount() === 0" x-cloak class="dsr-empty">
                        <div class="dsr-empty-icon"><svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="10" cy="10" r="7"/><path d="m16 16 5 5"/></svg></div>
                        <h3>No matching events</h3>
                        <p>Try another search or choose a different registration filter.</p>
                        <button type="button" class="dsr-filter is-active" style="margin-top:15px;" @click="tab='all';search=''">Clear filters</button>
                    </div>
                @endif
            </div>
        </section>

        <template x-teleport="body">
            <div x-cloak x-show="cancelId !== null" class="dsr dsr-modal-overlay" @click.self="closeCancel()" role="presentation">
                <section role="dialog" aria-modal="true" aria-labelledby="dsr-cancel-title" class="dsr-modal">
                    <div class="dsr-modal-head">
                        <p class="dsr-eyebrow">Registration management</p>
                        <h2 id="dsr-cancel-title" style="font-size:21px;font-weight:800;margin-top:11px;">Cancel Registration?</h2>
                    </div>
                    <div class="dsr-modal-body">
                        <p style="font-size:13px;color:#52627A;line-height:1.75;">Are you sure you want to cancel your registration for <strong style="color:#101064;" x-text="cancelName"></strong>? You can register again only while the event's registration window remains open.</p>
                        <div class="dsr-modal-actions">
                            <button x-ref="cancelClose" type="button" class="dsr-modal-secondary" @click="closeCancel()">Keep Registration</button>
                            <button type="button" class="dsr-modal-danger" @click="confirmCancel()">Yes, Cancel</button>
                        </div>
                    </div>
                </section>
            </div>
        </template>
    </div>
</x-admin-layout>
