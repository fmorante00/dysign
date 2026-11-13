{{-- Shared visual language for Student Data, Student Profile and Import. UI only. --}}
<style>
    .dy-student-university {
        --dy-student-border: #e2e8f0;
        --dy-student-navy: #101064;
        --dy-student-gold: #D4A017;
    }
    .dy-student-university :is(.student-records-hero, .student-profile-hero, .student-import-hero) {
        isolation: isolate;
        box-shadow: 0 15px 30px -24px rgba(16, 16, 100, .75);
    }
    .dy-student-university :is(.student-records-hero, .student-profile-hero, .student-import-hero) .dy-hero-circle {
        position: absolute;
        z-index: 0;
        pointer-events: none;
        border: 1px solid rgba(255,255,255,.17);
        border-radius: 9999px;
    }
    .dy-student-university .dy-hero-circle--outer {
        width: 330px;
        height: 330px;
        right: -66px;
        top: -116px;
    }
    .dy-student-university .dy-hero-circle--inner {
        width: 248px;
        height: 248px;
        right: -16px;
        top: -66px;
    }
    .dy-student-university .dy-hero-glow {
        position: absolute;
        z-index: 0;
        pointer-events: none;
        bottom: 0;
        right: 0;
        height: 115px;
        width: min(360px, 65%);
        background: linear-gradient(270deg,rgba(212,160,23,.18),transparent);
    }
    .dy-student-university section > .mb-4 > p:first-child {
        font-size: 11px;
        font-weight: 750;
        letter-spacing: .24em;
        color: #af8005;
    }
    .dy-student-university section > .mb-4 h2 {
        font-size: 1.23rem;
        letter-spacing: -.025em;
    }
    .dy-student-university section > .mb-4 p.text-gray-400 {
        color: #64748b;
    }
    /* Detail grids are individual, scannable university-style records, not flat spreadsheets. */
    .dy-student-university .student-profile-section.grid {
        gap: 14px;
        border: 0;
        overflow: visible;
        background: transparent;
        box-shadow: none;
    }
    .dy-student-university .student-profile-section.grid > div {
        min-width: 0;
        border: 1px solid var(--dy-student-border) !important;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 22px -20px rgba(16, 16, 100, .40);
    }
    .dy-student-university .student-profile-section.grid > div > p:first-child {
        color: #75839a;
        letter-spacing: .09em;
    }
    .dy-student-university .student-profile-section:not(.grid) {
        border: 1px solid var(--dy-student-border);
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 10px 26px -23px rgba(16, 16, 100, .5);
    }
    .dy-student-university .dy-import-steps {
        gap: 16px;
        overflow: visible;
        border: 0;
        background: transparent;
    }
    .dy-student-university .dy-import-steps > div {
        min-width: 0;
        border: 1px solid var(--dy-student-border) !important;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 22px -20px rgba(16,16,100,.45);
    }
    .dy-student-university .student-import-section {
        border-radius: 20px;
        box-shadow: 0 9px 25px -22px rgba(16, 16, 100, .40);
    }
    .dy-student-university .dy-import-dropzone {
        border-radius: 19px;
        border-color: #cbd5e1;
        background: linear-gradient(145deg,#f8faff 0%,#fff 70%);
    }
    .dy-student-university .dy-import-dropzone:hover,
    .dy-student-university .dy-import-dropzone:focus-within {
        border-color: var(--dy-student-gold);
        background: #fffdf5;
    }
    .dy-student-university .dy-profile-highlight {
        border: 1px solid var(--dy-student-border);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 24px -22px rgba(16,16,100,.45);
    }
    .dy-student-university .dy-profile-highlight dt {
        color: #718096;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
    }
    .dy-student-university .dy-profile-highlight dd {
        margin-top: 8px;
        color: var(--dy-student-navy);
        font-size: 15px;
        font-weight: 750;
        overflow-wrap: anywhere;
    }
    .dy-student-university .dy-profile-highlight small {
        display: block;
        margin-top: 9px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.5;
    }
    @media (max-width: 640px) {
        .dy-student-university .dy-hero-circle--outer {
            width: 250px;
            height: 250px;
            right: -115px;
            top: -120px;
        }
        .dy-student-university .dy-hero-circle--inner {
            width: 190px;
            height: 190px;
            right: -98px;
            top: -98px;
        }
    }
</style>
