{{-- DySign student-only responsive shell. The shared desktop shell and other roles stay unchanged. --}}
@auth
@if(auth()->user()?->role?->role_name === 'Student Account')
<style>
    .dy-mobile-toggle,.dy-mobile-scrim { display:none; }
    @media (max-width: 1023px) {
        /* The original shared layout uses a 288px fixed left sidebar and ml-72. */
        body > div.h-screen { width:100%; min-width:0; max-width:100vw; }
        body > div.h-screen > div.ml-72 {
            margin-left:0 !important;
            min-width:0 !important;
            width:100% !important;
            max-width:100vw;
        }
        body > div.h-screen > div.ml-72 > main {
            min-width:0;
            max-width:100vw;
            padding:20px !important;
            overflow-x:auto;
            overscroll-behavior:contain;
        }
        /* Drawer keeps exactly the existing sidebar menus and active states. */
        body aside.fixed.left-0.top-0.w-72 {
            width:min(18rem, calc(100vw - 44px)) !important;
            max-width:calc(100vw - 44px);
            z-index:1100 !important;
            transform:translateX(-110%);
            transition:transform .22s ease;
            will-change:transform;
            padding-bottom:env(safe-area-inset-bottom);
            box-shadow:12px 0 34px rgba(13,18,58,.2);
        }
        body.dy-student-nav-open aside.fixed.left-0.top-0.w-72 { transform:translateX(0); }
        .dy-mobile-scrim {
            display:block;
            position:fixed;
            inset:0;
            z-index:1090;
            visibility:hidden;
            opacity:0;
            pointer-events:none;
            background:rgba(7,11,45,.62);
            transition:opacity .22s ease,visibility .22s;
        }
        body.dy-student-nav-open .dy-mobile-scrim {
            visibility:visible;
            opacity:1;
            pointer-events:auto;
        }
        .dy-mobile-toggle {
            display:flex;
            position:fixed;
            top:14px;
            left:13px;
            z-index:1110;
            width:44px;
            height:44px;
            align-items:center;
            justify-content:center;
            border:1px solid #dce2ee;
            border-radius:12px;
            background:white;
            color:#101064;
            box-shadow:0 2px 8px rgba(12,24,75,.08);
            cursor:pointer;
            transition:left .22s ease,background .22s ease;
        }
        .dy-mobile-toggle:focus-visible { outline:3px solid #D4A017; outline-offset:2px; }
        .dy-mobile-toggle svg { width:22px; height:22px; }
        .dy-mobile-toggle .dy-close-svg { display:none; }
        body.dy-student-nav-open .dy-mobile-toggle {
            left:calc(min(18rem, 100vw - 44px) - 54px);
            border-color:rgba(255,255,255,.28);
            background:rgba(255,255,255,.12);
            color:white;
            box-shadow:none;
        }
        body.dy-student-nav-open .dy-mobile-toggle .dy-menu-svg { display:none; }
        body.dy-student-nav-open .dy-mobile-toggle .dy-close-svg { display:block; }
        /* Header title, avatar, and notifications must coexist on a narrow phone. */
        body > div.h-screen > div.ml-72 > header [class~="h-24"] {
            height:72px !important;
            min-height:72px;
            padding-left:68px !important;
            padding-right:12px !important;
            gap:8px;
        }
        body > div.h-screen > div.ml-72 > header [class~="h-24"] > div:first-child {
            min-width:0;
            flex:1;
            overflow:hidden;
        }
        body > div.h-screen > div.ml-72 > header [class~="h-24"] > div:first-child h1 {
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
            font-size:15px !important;
            line-height:1.35;
        }
        body > div.h-screen > div.ml-72 > header [class~="h-24"] > div:first-child p {
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
            font-size:10px !important;
        }
        body > div.h-screen > div.ml-72 > header [class~="h-24"] > div:last-child {
            gap:10px !important;
            flex-shrink:0;
        }
        body > div.h-screen > div.ml-72 > header button > div.text-left { display:none; }
        body > div.h-screen > div.ml-72 > header [class~="w-10"][class~="h-10"] { width:36px; height:36px; }
        body > div.h-screen > div.ml-72 > header [class~="w-80"] { max-width:calc(100vw - 24px) !important; }
        /* Prevent nested grids, tables, and calendar from widening the viewport. */
        body main > div { min-width:0; max-width:100%; }
        body main section, body main article { max-width:100%; }
        body main .overflow-x-auto { max-width:100%; }
        body main img { max-width:100%; }
        .dyc-shell { grid-template-columns:minmax(0,1fr) !important; }
        .dyc-panel,.dyc-monthbar,.dsr-panel,.dsr { min-width:0; max-width:100%; }
        .dsr-search { max-width:100%; }
        .dsr-event-main { min-width:0; }
    }
    @media (max-width: 639px) {
        body > div.h-screen > div.ml-72 > main { padding:12px !important; }
        body main .dy-student-campus-hero { padding:25px 19px !important; border-radius:21px; }
        body main .dy-student-campus-hero h1 { font-size:26px !important; }
        body main .dy-student-campus-hero > .relative { gap:18px; }
        body main .dy-student-campus-hero .flex.shrink-0 { flex-shrink:1; min-width:0; width:100%; padding:12px; }
        .dyc-monthbar { flex-wrap:wrap; gap:12px; }
        .dyc-calendar-body { padding:12px 9px 17px !important; }
        .dyc-weekdays,.dyc-days { gap:3px !important; }
        .dyc-day,.dyc-day-empty { height:43px !important; }
        .dyc-day-number { font-size:12px; }
        .dyc-dialog { max-height:calc(100dvh - 24px) !important; }
        .dsr-card-list { padding:10px !important; }
        .dsr-toolbar { padding:11px !important; }
        .dsr-badge { white-space:normal !important; }
    }
    @media (prefers-reduced-motion:reduce) {
        .dy-mobile-toggle, .dy-mobile-scrim, body aside.fixed.left-0.top-0.w-72 {transition:none !important;}
    }

    /* DYSIGN_STUDENT_NOTIFICATION_MOBILE_V1
       Keep the bell popup inside the phone viewport (student role only). */
    @media (max-width: 639px) {
        body > div.h-screen > div.ml-72 > header [x-show="notificationsOpen"] {
            position: fixed !important;
            top: 72px !important;
            left: 12px !important;
            right: 12px !important;
            width: auto !important;
            min-width: 0 !important;
            max-width: none !important;
            margin-top: 0 !important;
            max-height: calc(100dvh - 84px);
            overflow-x: hidden;
            overflow-y: auto;
            z-index: 1200 !important;
            box-shadow: 0 20px 50px rgba(9, 14, 50, .22) !important;
        }
    }

</style>

<button id="dy-student-mobile-toggle" class="dy-mobile-toggle" type="button"
    aria-label="Open student navigation" aria-controls="dy-student-sidebar" aria-expanded="false">
    <svg class="dy-menu-svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
    <svg class="dy-close-svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" /></svg>
</button>
<div class="dy-mobile-scrim" id="dy-student-mobile-scrim" aria-hidden="true"></div>
<script>
(function () {
    const button = document.getElementById('dy-student-mobile-toggle');
    const overlay = document.getElementById('dy-student-mobile-scrim');
    const sidebar = document.querySelector('aside.fixed.left-0.top-0.w-72');
    if (!button || !overlay || !sidebar) {
        if (button) button.style.display = 'none';
        return;
    }
    sidebar.id = 'dy-student-sidebar';
    const mobile = window.matchMedia('(max-width: 1023px)');
    function setOpen(open, restoreFocus) {
        const shouldOpen = Boolean(open && mobile.matches);
        document.body.classList.toggle('dy-student-nav-open', shouldOpen);
        button.setAttribute('aria-expanded', String(shouldOpen));
        button.setAttribute('aria-label', shouldOpen ? 'Close student navigation' : 'Open student navigation');
        sidebar.setAttribute('aria-hidden', mobile.matches && !shouldOpen ? 'true' : 'false');
        if (shouldOpen) {
            const first = sidebar.querySelector('a[href],button:not([disabled])');
            if (first) first.focus({ preventScroll:true });
        } else if (restoreFocus && mobile.matches) {
            button.focus({ preventScroll:true });
        }
    }
    setOpen(false,false);
    button.addEventListener('click', function () {
        setOpen(!document.body.classList.contains('dy-student-nav-open'),true);
    });
    overlay.addEventListener('click', function () { setOpen(false,true); });
    sidebar.addEventListener('click', function (event) {
        if (event.target.closest('a[href]') && mobile.matches) setOpen(false,false);
    });
    document.addEventListener('keydown', function (event) {
        if (!document.body.classList.contains('dy-student-nav-open')) return;
        if (event.key === 'Escape') { setOpen(false,true); return; }
        if (event.key !== 'Tab') return;
        const focusables = [button, ...sidebar.querySelectorAll('a[href],button:not([disabled]),[tabindex="0"]')]
            .filter(el => el.getClientRects().length > 0);
        if (!focusables.length) return;
        const first = focusables[0], last = focusables[focusables.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    });
    window.addEventListener('resize', function () {
        if (!mobile.matches) setOpen(false,false);
        else sidebar.setAttribute('aria-hidden', document.body.classList.contains('dy-student-nav-open') ? 'false' : 'true');
    });
})();
</script>
@endif
@endauth
