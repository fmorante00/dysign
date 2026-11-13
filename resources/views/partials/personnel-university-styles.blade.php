{{-- Shared visual treatment for the Staff Accounts module only; no business logic. --}}
<style>
    .dy-staff-page {
        --dy-staff-navy: #101064;
        --dy-staff-gold: #D4A017;
        --dy-staff-border: #e2e7f0;
    }

    .dy-staff-page :is(.personnel-hero, .personnel-create-hero, .personnel-edit-hero, .personnel-profile-hero) {
        isolation: isolate;
        box-shadow: 0 18px 38px -26px rgba(10, 12, 78, .55);
    }
    .dy-staff-page :is(.personnel-hero, .personnel-create-hero, .personnel-edit-hero, .personnel-profile-hero)::before {
        content: '';
        position: absolute;
        width: 275px;
        height: 275px;
        top: -175px;
        right: -60px;
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 9999px;
        pointer-events: none;
        z-index: 0;
    }
    .dy-staff-page :is(.personnel-hero, .personnel-create-hero, .personnel-edit-hero, .personnel-profile-hero)::after {
        content: '';
        position: absolute;
        width: 195px;
        height: 195px;
        top: -105px;
        right: -20px;
        border: 1px solid rgba(255, 255, 255, .10);
        border-radius: 9999px;
        pointer-events: none;
        z-index: 0;
    }
    .dy-staff-page :is(.personnel-hero, .personnel-create-hero, .personnel-edit-hero, .personnel-profile-hero) > :is(div, header, section):not(.absolute) {
        position: relative;
        z-index: 1;
    }

    .dy-staff-page :is(.personnel-section, .personnel-create-section, .personnel-edit-section, .personnel-profile-section) {
        border-color: var(--dy-staff-border);
        border-radius: 20px;
        box-shadow: 0 3px 13px -9px rgba(15, 23, 42, .20), 0 1px 2px rgba(15, 23, 42, .035);
    }
    .dy-staff-page :is(input, select, textarea):focus-visible {
        outline-color: var(--dy-staff-navy);
        outline-offset: 2px;
    }
    .dy-staff-page [data-staff-row] {
        transition: background-color .15s ease;
    }
    .dy-staff-page [data-staff-row]:hover {
        background-color: #f7f9ff;
    }
    .dy-staff-page .dy-staff-stat {
        position: relative;
        min-width: 0;
        border: 1px solid var(--dy-staff-border);
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 5px 20px -15px rgba(15, 23, 42, .18);
        padding: 22px;
        overflow: hidden;
    }
    .dy-staff-page .dy-staff-stat-icon {
        display: flex;
        width: 44px;
        height: 44px;
        flex: none;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: var(--dy-stat-soft, #eef0ff);
        color: var(--dy-stat-color, #101064);
    }
    .dy-staff-page .dy-staff-subcard {
        border: 1px solid var(--dy-staff-border);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 3px 14px -11px rgba(15, 23, 42, .15);
    }
    .dy-staff-page .dy-staff-action-link {
        transition: background-color .16s ease, border-color .16s ease, transform .16s ease;
    }
    .dy-staff-page .dy-staff-action-link:hover { transform: translateY(-1px); }
    .dy-staff-page .dy-staff-filter-pill[aria-pressed="true"] {
        color: #101064;
        border-color: rgba(16, 16, 100, .25);
        background: #eff1ff;
        box-shadow: inset 0 0 0 1px rgba(16, 16, 100, .05);
    }
    @media (prefers-reduced-motion: reduce) {
        .dy-staff-page .dy-staff-action-link,
        .dy-staff-page [data-staff-row] { transition: none; }
        .dy-staff-page .dy-staff-action-link:hover { transform: none; }
    }
</style>
