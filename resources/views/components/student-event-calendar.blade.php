{{-- DySign Student Calendar • visual-only schedule; mutations happen in Event Registrations. --}}
@once
<style>
  /* Scoped styles prevent Tailwind version/configuration from changing calendar layout. */
  [x-cloak] { display: none !important; }
  .dyc-shell { --dyc-navy:#101064; --dyc-gold:#D4A017; --dyc-ink:#172040; --dyc-muted:#71809a; --dyc-line:#e7eaf2; --dyc-soft:#f7f8fc; display:grid; grid-template-columns:minmax(0, 1.65fr) minmax(300px,.95fr); gap:20px; width:100%; min-width:0; align-items:start; font-family:inherit; }
  .dyc-shell *, .dyc-modal * { box-sizing:border-box; }
  .dyc-panel { min-width:0; overflow:hidden; border:1px solid #e8eaf1; border-radius:24px; background:#fff; box-shadow:0 7px 28px rgba(16,16,100,.055),0 1px 3px rgba(15,23,42,.035); }
  .dyc-header { position:relative; overflow:hidden; background:linear-gradient(113deg,#101064 0%,#1b1a75 70%,#343075 100%); padding:24px 28px; color:#fff; }
  .dyc-header::before,.dyc-header::after { content:""; position:absolute; width:210px; height:210px; right:-55px; top:-105px; border:1px solid rgba(255,255,255,.12); border-radius:50%; pointer-events:none; }
  .dyc-header::after { width:158px; height:158px; right:-23px; top:-78px; }
  .dyc-header-inner { position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; }
  .dyc-eyebrow { display:flex; align-items:center; gap:10px; color:#efd47f; font-size:10px; font-weight:800; letter-spacing:.19em; text-transform:uppercase; }
  .dyc-eyebrow::before { content:""; display:inline-block; width:21px; height:3px; border-radius:10px; background:#d4a017; }
  .dyc-heading { margin:9px 0 4px; color:#fff; font-size:22px; font-weight:800; line-height:1.3; letter-spacing:-.03em; }
  .dyc-subheading { margin:0; color:rgba(255,255,255,.72); font-size:12px; line-height:1.6; }
  .dyc-monthbar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:22px 28px 18px; border-bottom:1px solid #f0f1f6; }
  .dyc-month-name { margin:0; color:#101064; font-size:17px; line-height:1.4; font-weight:800; letter-spacing:-.025em; }
  .dyc-month-desc { margin:3px 0 0; color:var(--dyc-muted); font-size:11px; }
  .dyc-controls { display:flex; align-items:center; gap:8px; }
  .dyc-control { display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border:1px solid #e3e6f0; border-radius:10px; background:#fff; color:#20215f; cursor:pointer; transition:background .18s,border-color .18s; }
  .dyc-control:hover:not(:disabled) { background:#f4f4fa; border-color:#cacde0; }
  .dyc-control:disabled { opacity:.38; cursor:not-allowed; }
  .dyc-today-button { width:auto; padding:0 12px; font-size:11px; font-weight:700; line-height:1; }
  .dyc-control:focus-visible,.dyc-day:focus-visible,.dyc-action:focus-visible,.dyc-close:focus-visible { outline:3px solid rgba(212,160,23,.6); outline-offset:2px; }
  .dyc-control svg { width:16px; height:16px; }
  .dyc-calendar-body { padding:18px 23px 22px; }
  .dyc-weekdays,.dyc-days { display:grid; grid-template-columns:repeat(7,minmax(0,1fr)); gap:7px; }
  .dyc-weekdays { margin-bottom:9px; }
  .dyc-weekday { padding:8px 0; text-align:center; color:#7e8aa3; font-size:10px; font-weight:800; letter-spacing:.09em; }
  .dyc-day,.dyc-day-empty { position:relative; display:flex; flex-direction:column; align-items:center; justify-content:center; width:100%; height:68px; border-radius:12px; }
  .dyc-day { border:1px solid #eff0f5; background:#fafbfe; color:#303c62; cursor:pointer; transition:background .18s,border-color .18s,box-shadow .18s; font-family:inherit; }
  .dyc-day:hover { border-color:#d4a017; background:#fffdf6; box-shadow:0 3px 14px rgba(16,16,100,.06); }
  .dyc-day--has-event { border-color:#e5e6f0; background:#fff; }
  .dyc-day--today { border:1.5px solid #d4a017; background:#fffbef; color:#101064; }
  .dyc-day-empty { background:#fff; }
  .dyc-day-number { font-size:13px; font-weight:750; line-height:1.3; }
  .dyc-day-indicators { display:flex; align-items:center; justify-content:center; gap:3px; min-height:7px; margin-top:6px; }
  .dyc-dot { display:inline-block; width:5px; height:5px; flex:none; border-radius:100px; }
  .dyc-dot--upcoming { background:#d4a017; } .dyc-dot--ongoing { background:#16a085; } .dyc-dot--past { background:#96a0b6; } .dyc-dot--cancelled { background:#d77c79; }
  .dyc-day-count { position:absolute; right:6px; top:5px; display:inline-flex; align-items:center; justify-content:center; min-width:17px; height:17px; padding:0 4px; border-radius:7px; background:#edeef7; color:#353175; font-size:9px; font-weight:800; }
  .dyc-legend { display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; margin-top:19px; padding-top:17px; border-top:1px solid #f0f1f6; }
  .dyc-legend-items { display:flex; gap:14px; align-items:center; flex-wrap:wrap; }
  .dyc-legend-item { display:flex; align-items:center; gap:6px; color:#71809a; font-size:10px; font-weight:600; }
  .dyc-help { color:#8390a8; font-size:10px; }
  .dyc-right-head { padding:23px 23px 19px; border-bottom:1px solid #f0f1f6; }
  .dyc-right-title { margin:8px 0 5px; color:#101064; font-size:20px; line-height:1.4; letter-spacing:-.03em; font-weight:800; }
  .dyc-right-desc { margin:0; color:#71809a; font-size:12px; line-height:1.6; }
  .dyc-activities { display:flex; flex-direction:column; gap:12px; padding:19px; }
  .dyc-event-card { padding:16px; border:1px solid #e8eaf1; border-radius:15px; background:#fff; transition:border-color .15s,box-shadow .15s; }
  .dyc-event-card:hover { border-color:#d9d9e7; box-shadow:0 4px 18px rgba(16,16,100,.05); }
  .dyc-event-head { display:flex; gap:13px; align-items:flex-start; }
  .dyc-event-date { display:flex; align-items:center; justify-content:center; flex-direction:column; flex:none; width:51px; height:57px; border-radius:12px; background:#f0f0f8; color:#17166b; }
  .dyc-event-date small { font-size:10px; font-weight:800; letter-spacing:.07em; } .dyc-event-date strong { font-size:21px; font-weight:900; line-height:1.1; }
  .dyc-event-name { margin:0; color:#171d3d; font-size:13px; font-weight:800; line-height:1.5; overflow-wrap:anywhere; }
  .dyc-event-meta { margin:6px 0 0; color:#72809c; font-size:11px; line-height:1.5; overflow-wrap:anywhere; }
  .dyc-badges { display:flex; align-items:center; flex-wrap:wrap; gap:6px; margin-top:13px; padding-top:12px; border-top:1px solid #f0f1f6; }
  .dyc-badge { display:inline-block; padding:6px 8px; border-radius:7px; font-size:10px; line-height:1.2; font-weight:750; white-space:normal; }
  .dyc-badge--upcoming { background:#fff7e3; color:#8b6208; } .dyc-badge--ongoing { background:#e9f8f2; color:#176a52; }
  .dyc-badge--closed { background:#eef0f4; color:#56647a; } .dyc-badge--open { background:#e6f7ef; color:#08654b; }
  .dyc-badge--registered { background:#eef0fe; color:#262483; } .dyc-badge--cancelled { background:#fff0f0; color:#a94343; }
  .dyc-badge--past { background:#f0f2f6; color:#6c7890; }
  .dyc-empty { padding:32px 18px; border:1px dashed #d9dfea; border-radius:14px; background:#fafbfd; text-align:center; }
  .dyc-empty-icon { display:flex; align-items:center; justify-content:center; margin:0 auto 12px; width:42px; height:42px; border-radius:12px; background:#eeeef8; color:#101064; }
  .dyc-empty strong { display:block; color:#101064; font-size:13px; }
  .dyc-empty p { margin:7px 0 0; color:#8290a5; font-size:11px; line-height:1.7; }
  .dyc-actions { padding:16px 19px 19px; border-top:1px solid #f0f1f6; }
  .dyc-action { display:inline-flex; align-items:center; justify-content:center; gap:10px; min-height:43px; width:100%; padding:10px 16px; border:none; border-radius:11px; background:#101064; color:white; font-size:12px; font-weight:800; line-height:1.3; text-align:center; text-decoration:none; cursor:pointer; transition:background .15s; }
  .dyc-action:hover { color:white; background:#282584; } .dyc-action svg { width:16px; height:16px; }
  .dyc-action-hint { margin:10px 0 0; color:#8692a6; font-size:10px; text-align:center; line-height:1.5; }
  /* Modal is teleported to the document body. */
  .dyc-modal { position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center; padding:20px; }
  .dyc-modal-overlay { position:absolute; inset:0; background:rgba(9,12,46,.72); backdrop-filter:blur(3px); }
  .dyc-dialog { position:relative; display:flex; flex-direction:column; width:100%; max-width:620px; max-height:min(85vh,760px); overflow:hidden; border-radius:22px; background:#fff; box-shadow:0 22px 75px rgba(1,5,33,.32); }
  .dyc-dialog-head { position:relative; flex:none; padding:23px 27px; overflow:hidden; background:linear-gradient(115deg,#101064,#302b82); color:#fff; }
  .dyc-dialog-head::after { content:""; position:absolute; right:-48px; top:-90px; width:210px; height:210px; border:1px solid rgba(255,255,255,.16); border-radius:50%; }
  .dyc-dialog-head-inner { position:relative; z-index:1; display:flex; justify-content:space-between; gap:18px; }
  .dyc-dialog-date { margin:9px 0 4px; color:#fff; font-size:22px; line-height:1.3; font-weight:800; }
  .dyc-dialog-sub { margin:0; color:rgba(255,255,255,.7); font-size:12px; }
  .dyc-close { display:flex; justify-content:center; align-items:center; flex:none; width:35px; height:35px; border:1px solid rgba(255,255,255,.26); border-radius:10px; background:rgba(255,255,255,.12); color:white; cursor:pointer; }
  .dyc-close:hover { background:rgba(255,255,255,.23); } .dyc-close svg { width:18px; height:18px; }
  .dyc-dialog-body { flex:1; min-height:0; overflow:auto; display:flex; flex-direction:column; gap:12px; padding:20px; background:#f8f9fc; }
  .dyc-detail-card { padding:19px; border:1px solid #e6e9f1; border-radius:16px; background:#fff; }
  .dyc-detail-title { margin:12px 0 0; color:#101064; font-size:17px; line-height:1.45; font-weight:800; overflow-wrap:anywhere; }
  .dyc-detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:11px; margin-top:15px; }
  .dyc-detail-info { padding:12px 13px; border-radius:10px; background:#f5f7fb; }
  .dyc-detail-info dt { color:#8190a8; font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
  .dyc-detail-info dd { margin:6px 0 0; color:#35425b; font-size:12px; font-weight:700; line-height:1.5; overflow-wrap:anywhere; }
  .dyc-detail-note { margin:12px 0 0; color:#74829a; font-size:11px; line-height:1.6; }
  .dyc-dialog-foot { display:flex; justify-content:space-between; align-items:center; gap:14px; padding:15px 20px; border-top:1px solid #e8ebf2; background:#fff; }
  .dyc-dialog-foot p { margin:0; color:#8390a3; font-size:11px; line-height:1.5; }
  .dyc-dialog-foot .dyc-action { width:auto; white-space:nowrap; padding:10px 16px; }
  @media (max-width:1170px) { .dyc-shell { grid-template-columns:1fr; } }
  @media (max-width:640px) { .dyc-header { padding:23px 20px; } .dyc-monthbar { padding:18px 17px; } .dyc-calendar-body { padding:15px 11px 19px; } .dyc-weekdays,.dyc-days { gap:4px; } .dyc-day,.dyc-day-empty { height:49px; border-radius:9px; } .dyc-day-number { font-size:12px; } .dyc-day-count { top:2px; right:2px; min-width:13px; height:13px; padding:0 2px; font-size:8px; } .dyc-legend-items { gap:9px; } .dyc-dialog-head { padding:19px; } .dyc-dialog-body { padding:13px; } .dyc-detail-card { padding:15px; } .dyc-detail-grid { grid-template-columns:1fr; } .dyc-dialog-foot { align-items:stretch; flex-direction:column; } .dyc-dialog-foot .dyc-action { width:100%; } }
  @media (prefers-reduced-motion:reduce) { .dyc-shell *,.dyc-modal * { transition:none !important; } }
</style>
@endonce

<section class="dyc-shell"
    x-data="dySignStudentEventCalendarV3(@js($calendarEvents), @js($calendarToday), @js($calendarFirstMonth), @js($calendarLastMonth))"
    @keydown.escape.window="closeDate()"
    aria-label="Student campus calendar">

    <div class="dyc-panel">
        <header class="dyc-header">
            <div class="dyc-header-inner">
                <div>
                    <div class="dyc-eyebrow">DySign · Student Portal</div>
                    <h2 class="dyc-heading">Academic Event Calendar</h2>
                    <p class="dyc-subheading">Explore your campus schedule and view event details by date.</p>
                </div>
                <svg width="34" height="34" fill="none" stroke="#e7c75b" stroke-width="1.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3v4m8-4v4M4 10h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" /></svg>
            </div>
        </header>

        <div class="dyc-monthbar">
            <div>
                <h3 class="dyc-month-name" x-text="monthTitle()"></h3>
                <p class="dyc-month-desc">Select a date to open its event details</p>
            </div>
            <div class="dyc-controls">
                <button type="button" class="dyc-control dyc-today-button" @click="goToday()">Today</button>
                <button type="button" class="dyc-control" @click="moveMonth(-1)" :disabled="!canMove(-1)" aria-label="Previous month">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                </button>
                <button type="button" class="dyc-control" @click="moveMonth(1)" :disabled="!canMove(1)" aria-label="Next month">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>

        <div class="dyc-calendar-body">
            <div class="dyc-weekdays" aria-hidden="true">
                <template x-for="name in ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']" :key="name"><span class="dyc-weekday" x-text="name"></span></template>
            </div>
            <div class="dyc-days">
                <template x-for="day in calendarDays()" :key="day.key">
                    <div>
                        <template x-if="!day.date"><div class="dyc-day-empty" aria-hidden="true"></div></template>
                        <template x-if="day.date">
                            <button type="button" class="dyc-day"
                                :class="{'dyc-day--today': day.date === today, 'dyc-day--has-event': eventsOn(day.date).length > 0}"
                                :aria-label="dateLabel(day.date) + ', ' + eventsOn(day.date).length + ' campus events'"
                                @click="openDate(day.date)">
                                <span class="dyc-day-number" x-text="day.number"></span>
                                <span x-show="eventsOn(day.date).length > 1" class="dyc-day-count" x-text="eventsOn(day.date).length"></span>
                                <span class="dyc-day-indicators">
                                    <i x-show="eventsOn(day.date).some(e => e.eventStatus === 'Upcoming')" class="dyc-dot dyc-dot--upcoming"></i>
                                    <i x-show="eventsOn(day.date).some(e => e.eventStatus === 'Ongoing')" class="dyc-dot dyc-dot--ongoing"></i>
                                    <i x-show="eventsOn(day.date).some(e => e.eventStatus === 'Completed')" class="dyc-dot dyc-dot--past"></i>
                                    <i x-show="eventsOn(day.date).some(e => e.eventStatus === 'Cancelled')" class="dyc-dot dyc-dot--cancelled"></i>
                                </span>
                            </button>
                        </template>
                    </div>
                </template>
            </div>
            <div class="dyc-legend">
                <div class="dyc-legend-items">
                    <span class="dyc-legend-item"><i class="dyc-dot dyc-dot--upcoming"></i>Upcoming</span>
                    <span class="dyc-legend-item"><i class="dyc-dot dyc-dot--ongoing"></i>Ongoing</span>
                    <span class="dyc-legend-item"><i class="dyc-dot dyc-dot--past"></i>Completed</span>
                    <span class="dyc-legend-item"><i class="dyc-dot dyc-dot--cancelled"></i>Cancelled</span>
                </div>
                <span class="dyc-help">Click a day for event details</span>
            </div>
        </div>
    </div>

    <aside class="dyc-panel" aria-label="Upcoming campus events">
        <header class="dyc-right-head">
            <div class="dyc-eyebrow" style="color:#93731b">Student activities</div>
            <h2 class="dyc-right-title">Upcoming Events</h2>
            <p class="dyc-right-desc">A quick look at future and ongoing events, including registration availability.</p>
        </header>
        <div class="dyc-activities">
            <template x-if="upcomingEvents().length === 0">
                <div class="dyc-empty">
                    <span class="dyc-empty-icon"><svg width="23" height="23" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3v4m8-4v4M4 10h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg></span>
                    <strong>No upcoming events</strong>
                    <p>Your eligible or registered upcoming events will appear here.</p>
                </div>
            </template>
            <template x-for="event in upcomingEvents()" :key="event.id">
                <article class="dyc-event-card">
                    <div class="dyc-event-head">
                        <div class="dyc-event-date"><small x-text="shortMonth(event.date)"></small><strong x-text="Number(event.date.slice(8,10))"></strong></div>
                        <div style="min-width:0;flex:1">
                            <h3 class="dyc-event-name" x-text="event.title"></h3>
                            <p class="dyc-event-meta"><span x-text="event.time"></span><template x-if="event.endTime"><span> – <span x-text="event.endTime"></span></span></template></p>
                            <p class="dyc-event-meta" x-text="event.location"></p>
                        </div>
                    </div>
                    <div class="dyc-badges">
                        <span class="dyc-badge" :class="statusClass(event.eventStatus)" x-text="event.eventStatus"></span>
                        <span class="dyc-badge" :class="registrationClass(event)" x-text="event.registrationStatus"></span>
                        <span x-show="event.registered" class="dyc-badge dyc-badge--registered">You are registered</span>
                        <span x-show="event.attended" class="dyc-badge dyc-badge--open">Attendance recorded</span>
                    </div>
                </article>
            </template>
        </div>
        @if(\Illuminate\Support\Facades\Route::has('student.registrations.index'))
            <footer class="dyc-actions">
                <a class="dyc-action" href="{{ route('student.registrations.index') }}">View My Event Registrations
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </a>
                <p class="dyc-action-hint">Registration is managed on the event registration page.</p>
            </footer>
        @endif
    </aside>

    <template x-teleport="body">
        <div x-show="modalOpen" x-cloak class="dyc-modal" @keydown.escape.window="closeDate()">
            <div class="dyc-modal-overlay" @click="closeDate()" aria-hidden="true"></div>
            <section x-show="modalOpen" x-transition.opacity.duration.150ms role="dialog" aria-modal="true" aria-labelledby="dyc-modal-title"
                tabindex="-1" class="dyc-dialog" @keydown.tab="trapTab($event)">
                <header class="dyc-dialog-head">
                    <div class="dyc-dialog-head-inner">
                        <div>
                            <div class="dyc-eyebrow">DySign · Calendar Details</div>
                            <h2 class="dyc-dialog-date" id="dyc-modal-title" x-text="dateLabel(selectedDate)"></h2>
                            <p class="dyc-dialog-sub" x-text="eventCountText(selectedDate)"></p>
                        </div>
                        <button class="dyc-close" type="button" x-ref="modalClose" @click="closeDate()" aria-label="Close calendar details">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12" /></svg>
                        </button>
                    </div>
                </header>
                <div class="dyc-dialog-body">
                    <template x-if="eventsOn(selectedDate).length === 0">
                        <div class="dyc-empty"><span class="dyc-empty-icon"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3v4m8-4v4M4 10h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg></span><strong>No events scheduled</strong><p>There are no student-related events on this date.</p></div>
                    </template>
                    <template x-for="event in eventsOn(selectedDate)" :key="event.id">
                        <article class="dyc-detail-card">
                            <div class="dyc-badges" style="margin-top:0;padding-top:0;border-top:0">
                                <span class="dyc-badge" :class="statusClass(event.eventStatus)" x-text="event.eventStatus"></span>
                                <span class="dyc-badge" :class="registrationClass(event)" x-text="event.registrationStatus"></span>
                                <span x-show="event.registered" class="dyc-badge dyc-badge--registered">You are registered</span>
                                <span x-show="event.cancelledRegistration" class="dyc-badge dyc-badge--cancelled">Previous registration cancelled</span>
                            </div>
                            <h3 class="dyc-detail-title" x-text="event.title"></h3>
                            <dl class="dyc-detail-grid">
                                <div class="dyc-detail-info"><dt>Schedule</dt><dd><span x-text="event.time"></span><template x-if="event.endTime"><span> – <span x-text="event.endTime"></span></span></template></dd></div>
                                <div class="dyc-detail-info"><dt>Venue</dt><dd x-text="event.location"></dd></div>
                            </dl>
                            <p class="dyc-detail-note" x-show="event.attended">An attendance scan has been recorded for this event.</p>
                            <p class="dyc-detail-note" x-show="event.eventStatus === 'Ongoing' || event.eventStatus === 'Completed'">Registration is closed because this event has already started.</p>
                            <p class="dyc-detail-note" x-show="event.eventStatus === 'Cancelled'">This event has been cancelled.</p>
                        </article>
                    </template>
                </div>
                <footer class="dyc-dialog-foot">
                    <p>Event status and registration availability are displayed separately.</p>
                    @if(\Illuminate\Support\Facades\Route::has('student.registrations.index'))
                        <a class="dyc-action" href="{{ route('student.registrations.index') }}">Manage Registrations</a>
                    @else
                        <button class="dyc-action" type="button" @click="closeDate()">Close</button>
                    @endif
                </footer>
            </section>
        </div>
    </template>
</section>

@once
<script>
window.dySignStudentEventCalendarV3 = function(events, today, minMonth, maxMonth) {
    return {
        events: Array.isArray(events) ? events : [],
        today: today,
        minMonth: minMonth,
        maxMonth: maxMonth,
        month: today.slice(0,7),
        selectedDate: today,
        modalOpen: false,
        previousFocus: null,
        monthTitle() {
            const [year, month] = this.month.split('-').map(Number);
            return new Intl.DateTimeFormat('en-PH', {month:'long',year:'numeric'}).format(new Date(year, month-1, 1));
        },
        shiftedMonth(delta) {
            const [year, month] = this.month.split('-').map(Number);
            const date = new Date(year, month-1+delta, 1);
            return date.getFullYear() + '-' + String(date.getMonth()+1).padStart(2, '0');
        },
        canMove(delta) {
            const month = this.shiftedMonth(delta);
            return month >= this.minMonth && month <= this.maxMonth;
        },
        moveMonth(delta) {
            if (this.canMove(delta)) this.month = this.shiftedMonth(delta);
        },
        goToday() { this.month = this.today.slice(0,7); },
        calendarDays() {
            const [year, month] = this.month.split('-').map(Number);
            const offset = (new Date(year,month-1,1).getDay()+6)%7;
            const count = new Date(year,month,0).getDate();
            const days = [];
            for (let n=0; n<offset; n++) days.push({key:'start-'+n, date:null, number:''});
            for (let n=1; n<=count; n++) {
                const date=this.month+'-'+String(n).padStart(2,'0');
                days.push({key:date,date:date,number:n});
            }
            while (days.length%7) days.push({key:'end-'+days.length,date:null,number:''});
            return days;
        },
        eventsOn(date) {
            return this.events.filter(event=>event.date===date);
        },
        dateLabel(date) {
            if (!date) return '';
            const [year,month,day] = date.split('-').map(Number);
            return new Intl.DateTimeFormat('en-PH',{dateStyle:'full'}).format(new Date(year,month-1,day));
        },
        shortMonth(date) {
            const [year,month] = date.split('-').map(Number);
            return new Intl.DateTimeFormat('en-PH',{month:'short'}).format(new Date(year,month-1,1));
        },
        eventCountText(date) {
            const count=this.eventsOn(date).length;
            return count === 1 ? '1 campus event' : count+' campus events';
        },
        openDate(date) {
            this.previousFocus = document.activeElement;
            this.selectedDate = date;
            this.modalOpen = true;
            this.$nextTick(()=>this.$refs.modalClose?.focus());
        },
        closeDate() {
            if (!this.modalOpen) return;
            this.modalOpen=false;
            this.$nextTick(()=>this.previousFocus?.focus?.());
        },
        trapTab(event) {
            const dialog=event.currentTarget;
            const focusables=[...dialog.querySelectorAll('a[href],button:not([disabled]),[tabindex="0"]')].filter(el=>el.offsetParent!==null);
            if (!focusables.length) return;
            const first=focusables[0], last=focusables[focusables.length-1];
            if (event.shiftKey && document.activeElement===first) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement===last) { event.preventDefault(); first.focus(); }
        },
        statusClass(status) {
            if (status === 'Upcoming') return 'dyc-badge--upcoming';
            if (status === 'Ongoing') return 'dyc-badge--ongoing';
            if (status === 'Cancelled') return 'dyc-badge--cancelled';
            return 'dyc-badge--past';
        },
        registrationClass(event) {
            if (event.registrationStatus === 'Registration open') return 'dyc-badge--open';
            return 'dyc-badge--closed';
        },
        upcomingEvents() {
            return this.events
                .filter(event => event.eventStatus === 'Upcoming' || event.eventStatus === 'Ongoing')
                .sort((a,b) => a.date.localeCompare(b.date) || a.sortTime.localeCompare(b.sortTime))
                .slice(0,5);
        }
    };
};
</script>
@endonce
