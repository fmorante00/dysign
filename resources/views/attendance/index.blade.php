<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        {{ $event->event_name }} • DySign Attendance
    </title>


    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >


    @php

        $latestStudent =
            $latestAttendance?->student;


        $latestPhoto =
            $latestStudent?->photo_path
                ? asset(
                    'student_photos/'
                    . basename(
                        $latestStudent->photo_path
                    )
                )
                : null;


        $recentRecords =
            collect(
                $attendanceRecords ?? []
            )
            ->take(5);

    @endphp


    <style>

        :root {

            --dyci-navy:
                #101064;

            --dyci-gold:
                #D4A017;

            --dyci-cream:
                #FAFAF7;

            --dyci-text:
                #1F2937;

            --dyci-muted:
                #6B7280;

            --dyci-line:
                #E5E7EB;

            --dyci-soft:
                #F4F5FA;

            --dyci-green:
                #169C4A;

        }


        * {

            box-sizing:
                border-box;

        }


        html,
        body {

            width:
                100%;

            min-height:
                100%;

            margin:
                0;

        }


        body {

            overflow-x:
                hidden;

            background:
                var(--dyci-cream);

            color:
                var(--dyci-text);

            font-family:
                'Inter',
                sans-serif;

        }


        .serif {

            font-family:
                'Playfair Display',
                serif;

        }


        .kiosk-shell {

            min-height:
                100vh;

            display:
                flex;

            flex-direction:
                column;

            background:
                var(--dyci-cream);

        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .kiosk-header {

            position:
                relative;

            overflow:
                hidden;

            flex-shrink:
                0;

            background:

                linear-gradient(
                    rgba(16, 16, 100, .88),
                    rgba(16, 16, 100, .91)
                ),

                url('{{ asset('images/school.jpg') }}');

            background-size:
                cover;

            background-position:
                center;

            color:
                #ffffff;

            border-bottom:
                4px solid
                var(--dyci-gold);

        }


        .kiosk-header::after {

            content:
                "";

            position:
                absolute;

            inset:
                0;

            background:

                linear-gradient(
                    90deg,
                    rgba(255,255,255,.05) 1px,
                    transparent 1px
                ),

                linear-gradient(
                    rgba(255,255,255,.03) 1px,
                    transparent 1px
                );

            background-size:
                48px
                48px;

            opacity:
                .18;

            pointer-events:
                none;

        }


        .kiosk-header-inner {

            position:
                relative;

            z-index:
                2;

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            gap:
                24px;

            padding:
                26px
                44px
                24px;

        }


        .staff-back {

            position:
                absolute;

            top:
                12px;

            left:
                15px;

            z-index:
                5;

            display:
                inline-flex;

            align-items:
                center;

            gap:
                6px;

            padding:
                6px
                10px;

            border-radius:
                999px;

            background:
                rgba(255,255,255,.08);

            color:
                #ffffff;

            font-size:
                11px;

            font-weight:
                600;

            letter-spacing:
                .08em;

            text-decoration:
                none;

            text-transform:
                uppercase;

            opacity:
                .18;

            transition:
                .25s ease;

            backdrop-filter:
                blur(4px);

        }


        .staff-back:hover {

            opacity:
                .95;

            background:
                rgba(255,255,255,.14);

        }


        .brand-wrap {

            display:
                flex;

            align-items:
                center;

            gap:
                16px;

        }


        .brand-logo {

            width:
                64px;

            height:
                64px;

            flex-shrink:
                0;

            object-fit:
                contain;

        }


        .brand-block {

            display:
                flex;

            flex-direction:
                column;

            line-height:
                1;

        }


        .brand-title {

            display:
                flex;

            align-items:
                baseline;

            gap:
                8px;

            font-size:
                40px;

            font-weight:
                800;

            letter-spacing:
                -.03em;

        }


        .brand-title .dy {

            color:
                #ffffff;

        }


        .brand-title .sign {

            color:
                var(--dyci-gold);

            font-family:
                'Playfair Display',
                serif;

            font-style:
                italic;

            font-weight:
                700;

        }


        .brand-subtitle {

            margin-top:
                8px;

            color:
                rgba(255,255,255,.82);

            font-size:
                14px;

            letter-spacing:
                .08em;

            text-transform:
                uppercase;

        }


        .clock-wrap {

            flex-shrink:
                0;

            text-align:
                right;

        }


        .clock-time {

            color:
                #ffffff;

            font-size:
                48px;

            font-weight:
                800;

            letter-spacing:
                -.04em;

            line-height:
                1;

        }


        .clock-date {

            margin-top:
                8px;

            color:
                rgba(255,255,255,.84);

            font-size:
                14px;

            letter-spacing:
                .12em;

            text-transform:
                uppercase;

        }


        /*
        |--------------------------------------------------------------------------
        | MAIN
        |--------------------------------------------------------------------------
        */

        .kiosk-main {

            flex:
                1;

            display:
                flex;

            flex-direction:
                column;

            padding:
                30px
                52px
                26px;

        }


        .event-intro {

            margin-bottom:
                28px;

            text-align:
                center;

        }


        .event-kicker {

            color:
                #94A3B8;

            font-size:
                13px;

            letter-spacing:
                .55em;

            text-transform:
                uppercase;

        }


        .event-title {

            margin:
                12px
                0
                0;

            color:
                var(--dyci-navy);

            font-family:
                'Playfair Display',
                serif;

            font-size:
                clamp(
                    46px,
                    5vw,
                    76px
                );

            font-weight:
                600;

            letter-spacing:
                -.03em;

            line-height:
                1.02;

        }


        .event-meta {

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            flex-wrap:
                wrap;

            gap:
                28px;

            margin-top:
                20px;

        }


        .event-meta-group {

            min-width:
                220px;

        }


        .event-meta-label {

            color:
                var(--dyci-gold);

            font-size:
                12px;

            letter-spacing:
                .30em;

            text-transform:
                uppercase;

        }


        .event-meta-value {

            margin-top:
                8px;

            color:
                var(--dyci-navy);

            font-size:
                20px;

            font-weight:
                600;

        }


        .event-divider {

            width:
                1px;

            height:
                54px;

            background:
                #D1D5DB;

        }


        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE GRID
        |--------------------------------------------------------------------------
        */

        .attendance-grid {

            flex:
                1;

            display:
                grid;

            grid-template-columns:
                minmax(0, 2.05fr)
                minmax(320px, .95fr);

            gap:
                34px;

            min-height:
                0;

        }


        .section-label {

            color:
                var(--dyci-gold);

            font-size:
                12px;

            letter-spacing:
                .35em;

            text-transform:
                uppercase;

        }


        .section-title {

            margin:
                5px
                0
                0;

            color:
                var(--dyci-navy);

            font-family:
                'Playfair Display',
                serif;

            font-size:
                36px;

            font-weight:
                600;

        }


        .latest-column,
        .arrivals-column {

            min-width:
                0;

        }


        .latest-heading {

            display:
                flex;

            align-items:
                end;

            justify-content:
                space-between;

            gap:
                20px;

            margin-bottom:
                18px;

        }


        .live-indicator {

            display:
                flex;

            align-items:
                center;

            gap:
                8px;

            color:
                var(--dyci-green);

            font-size:
                14px;

            font-weight:
                600;

            white-space:
                nowrap;

        }


        .live-dot {

            width:
                10px;

            height:
                10px;

            border-radius:
                999px;

            background:
                #55D88A;

            animation:
                livePulse
                1.7s
                ease-in-out
                infinite;

        }


        @keyframes livePulse {

            0%,
            100% {

                opacity:
                    .45;

            }

            50% {

                opacity:
                    1;

            }

        }


        .latest-card {

            min-height:
                300px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            padding:
                42px;

            border:
                1px solid
                #DDE1E7;

            border-radius:
                30px;

            background:
                #ffffff;

            box-shadow:
                0 2px 5px
                rgba(15, 23, 42, .10);

        }


        .latest-content {

            width:
                100%;

            display:
                flex;

            align-items:
                center;

            gap:
                48px;

        }


        .profile-photo-wrap {

            position:
                relative;

            flex-shrink:
                0;

        }


        .profile-photo,
        .profile-fallback {

            width:
                190px;

            height:
                190px;

            border:
                9px solid
                #ffffff;

            border-radius:
                999px;

            box-shadow:
                0 12px 28px
                rgba(16,16,100,.12);

        }


        .profile-photo {

            display:
                block;

            object-fit:
                cover;

            background:
                #F1F2FA;

        }


        .profile-fallback {

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            background:
                #F1F2FA;

            color:
                var(--dyci-navy);

            font-family:
                'Playfair Display',
                serif;

            font-size:
                78px;

        }


        .profile-accent {

            position:
                absolute;

            right:
                9px;

            bottom:
                10px;

            width:
                34px;

            height:
                34px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            border:
                4px solid
                #ffffff;

            border-radius:
                999px;

            background:
                var(--dyci-gold);

            color:
                var(--dyci-navy);

            font-size:
                16px;

            font-weight:
                800;

        }


        .student-kicker {

            color:
                #94A3B8;

            font-size:
                12px;

            letter-spacing:
                .30em;

            text-transform:
                uppercase;

        }


        .student-name {

            margin-top:
                9px;

            color:
                var(--dyci-navy);

            font-family:
                'Playfair Display',
                serif;

            font-size:
                clamp(
                    40px,
                    4vw,
                    64px
                );

            font-weight:
                500;

            letter-spacing:
                -.03em;

            line-height:
                1.03;

        }


        .student-meta {

            display:
                flex;

            flex-wrap:
                wrap;

            align-items:
                center;

            gap:
                9px;

            margin-top:
                10px;

            color:
                #64748B;

            font-size:
                18px;

        }


        .attendance-confirmed {

            display:
                inline-flex;

            align-items:
                center;

            gap:
                8px;

            margin-top:
                22px;

            color:
                #008A36;

            font-size:
                15px;

            font-weight:
                700;

        }


        .waiting {

            width:
                100%;

            text-align:
                center;

        }


        .waiting-line {

            width:
                46px;

            height:
                3px;

            margin:
                0
                auto;

            background:
                var(--dyci-gold);

        }


        .waiting-title {

            margin-top:
                18px;

            color:
                var(--dyci-navy);

            font-family:
                'Playfair Display',
                serif;

            font-size:
                34px;

        }


        .waiting-text {

            margin-top:
                9px;

            color:
                #94A3B8;

            font-size:
                15px;

        }


        /*
        |--------------------------------------------------------------------------
        | RECENT ARRIVALS
        |--------------------------------------------------------------------------
        */

        .arrivals-heading {

            margin-bottom:
                18px;

        }


        .arrivals-card {

            overflow:
                hidden;

            border:
                1px solid
                #DDE1E7;

            border-radius:
                30px;

            background:
                #ffffff;

        }


        .arrival-row {

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            gap:
                18px;

            padding:
                20px
                24px;

            border-bottom:
                1px solid
                #EEF0F3;

        }


        .arrival-row:last-child {

            border-bottom:
                0;

        }


        .arrival-person {

            min-width:
                0;

            display:
                flex;

            align-items:
                center;

            gap:
                12px;

        }


        .arrival-photo,
        .arrival-fallback {

            width:
                46px;

            height:
                46px;

            flex-shrink:
                0;

            border-radius:
                999px;

            background:
                #F1F2FA;

        }


        .arrival-photo {

            object-fit:
                cover;

        }


        .arrival-fallback {

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            color:
                var(--dyci-navy);

            font-family:
                'Playfair Display',
                serif;

            font-size:
                21px;

            font-weight:
                600;

        }


        .arrival-name {

            overflow:
                hidden;

            color:
                var(--dyci-navy);

            font-size:
                14px;

            font-weight:
                700;

            text-overflow:
                ellipsis;

            white-space:
                nowrap;

        }


        .arrival-program {

            margin-top:
                3px;

            color:
                #6B7280;

            font-size:
                13px;

        }


        .arrival-time {

            flex-shrink:
                0;

            color:
                #64748B;

            font-size:
                13px;

        }


        .arrivals-empty {

            padding:
                35px
                24px;

            color:
                #94A3B8;

            text-align:
                center;

            font-size:
                14px;

        }


        /*
        |--------------------------------------------------------------------------
        | TICKER
        |--------------------------------------------------------------------------
        */

        .announcement-bar {

            display:
                grid;

            grid-template-columns:
                240px
                minmax(0, 1fr);

            margin-top:
                26px;

            overflow:
                hidden;

            background:
                var(--dyci-navy);

            color:
                #ffffff;

        }


        .announcement-label {

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            gap:
                10px;

            padding:
                17px
                20px;

            background:
                var(--dyci-gold);

            color:
                var(--dyci-navy);

            font-size:
                14px;

            font-weight:
                800;

            text-transform:
                uppercase;

        }


        .announcement-track {

            position:
                relative;

            overflow:
                hidden;

            display:
                flex;

            align-items:
                center;

            min-height:
                54px;

        }


        .ticker {

            display:
                inline-block;

            min-width:
                max-content;

            padding-left:
                100%;

            white-space:
                nowrap;

            animation:
                moveTicker
                28s
                linear
                infinite;

            font-size:
                16px;

            font-weight:
                600;

        }


        @keyframes moveTicker {

            from {

                transform:
                    translateX(0);

            }

            to {

                transform:
                    translateX(-100%);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | EVENT CLOSED OVERLAY
        |--------------------------------------------------------------------------
        */

        .closed-overlay {

            position:
                fixed;

            z-index:
                100;

            inset:
                0;

            display:
                none;

            align-items:
                center;

            justify-content:
                center;

            padding:
                24px;

            background:
                rgba(16,16,100,.92);

            backdrop-filter:
                blur(8px);

        }


        .closed-overlay.show {

            display:
                flex;

        }


        .closed-card {

            width:
                min(
                    560px,
                    100%
                );

            padding:
                46px;

            border:
                1px solid
                rgba(255,255,255,.16);

            border-radius:
                28px;

            background:
                #ffffff;

            text-align:
                center;

            box-shadow:
                0 25px 70px
                rgba(0,0,0,.30);

        }


        .closed-icon {

            width:
                64px;

            height:
                64px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            margin:
                0
                auto;

            border-radius:
                999px;

            background:
                #F1F2FA;

            color:
                var(--dyci-navy);

            font-size:
                27px;

        }


        .closed-title {

            margin-top:
                20px;

            color:
                var(--dyci-navy);

            font-family:
                'Playfair Display',
                serif;

            font-size:
                38px;

        }


        .closed-text {

            margin:
                12px
                auto
                0;

            max-width:
                390px;

            color:
                #6B7280;

            font-size:
                15px;

            line-height:
                1.7;

        }


        /*
        |--------------------------------------------------------------------------
        | ANIMATION
        |--------------------------------------------------------------------------
        */

        .fade-enter {

            animation:
                fadeEnter
                .42s
                ease;

        }


        @keyframes fadeEnter {

            from {

                opacity:
                    0;

                transform:
                    translateY(12px);

            }

            to {

                opacity:
                    1;

                transform:
                    translateY(0);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (
            max-width:
                1100px
        ) {

            .attendance-grid {

                grid-template-columns:
                    1fr;

            }


            .kiosk-main {

                padding-left:
                    28px;

                padding-right:
                    28px;

            }


            .latest-card {

                min-height:
                    320px;

            }

        }


        @media (
            max-width:
                720px
        ) {

            .kiosk-header-inner {

                padding:
                    22px
                    20px;

            }


            .brand-logo {

                width:
                    50px;

                height:
                    50px;

            }


            .brand-title {

                font-size:
                    30px;

            }


            .brand-subtitle {

                font-size:
                    10px;

            }


            .clock-time {

                font-size:
                    30px;

            }


            .clock-date {

                font-size:
                    10px;

            }


            .kiosk-main {

                padding:
                    24px
                    18px;

            }


            .event-divider {

                display:
                    none;

            }


            .latest-content {

                flex-direction:
                    column;

                align-items:
                    flex-start;

                gap:
                    24px;

            }


            .profile-photo,
            .profile-fallback {

                width:
                    145px;

                height:
                    145px;

            }


            .announcement-bar {

                grid-template-columns:
                    1fr;

            }


            .announcement-label {

                display:
                    none;

            }

        }

    </style>

</head>


<body>

<div class="kiosk-shell">


    {{-- ====================================================== --}}
    {{-- HEADER --}}
    {{-- ====================================================== --}}

    <header class="kiosk-header">

        <a
            href="{{ route('my-events.index') }}"
            class="staff-back"
        >
            <span>←</span>
            <span>Back</span>
        </a>


        <div class="kiosk-header-inner">

            <div class="brand-wrap">

                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="DySign Logo"
                    class="brand-logo"
                >


                <div class="brand-block">

                    <div class="brand-title">

                        <span class="dy">
                            Dy
                        </span>

                        <span class="sign">
                            Sign
                        </span>

                    </div>


                    <div class="brand-subtitle">
                        Dr. Yanga's Colleges, Inc.
                    </div>

                </div>

            </div>


            <div class="clock-wrap">

                <div
                    id="live_clock"
                    class="clock-time"
                >
                    00:00:00
                </div>


                <div
                    id="live_date"
                    class="clock-date"
                ></div>

            </div>

        </div>

    </header>



    {{-- ====================================================== --}}
    {{-- MAIN --}}
    {{-- ====================================================== --}}

    <main class="kiosk-main">


        {{-- EVENT INTRO --}}

        <section class="event-intro fade-enter">

            <div class="event-kicker">
                Welcome To
            </div>


            <h1 class="event-title">
                {{ $event->event_name }}
            </h1>


            <div class="event-meta">

                <div class="event-meta-group">

                    <div class="event-meta-label">
                        Schedule
                    </div>

                    <div class="event-meta-value">

                        {{
                            \Carbon\Carbon::parse(
                                $event->start_time
                            )->format('g:i A')
                        }}

                        —

                        {{
                            \Carbon\Carbon::parse(
                                $event->end_time
                            )->format('g:i A')
                        }}

                    </div>

                </div>


                <div class="event-divider"></div>


                <div class="event-meta-group">

                    <div class="event-meta-label">
                        Venue
                    </div>

                    <div class="event-meta-value">
                        {{ $event->location }}
                    </div>

                </div>

            </div>

        </section>



        {{-- ATTENDANCE AREA --}}

        <section class="attendance-grid">


            {{-- LATEST CHECK-IN --}}

            <div class="latest-column">

                <div class="latest-heading">

                    <div>

                        <div class="section-label">
                            Latest Check-In
                        </div>

                        <h2 class="section-title">
                            Welcome!
                        </h2>

                    </div>


                    <div
                        id="live_indicator"
                        class="live-indicator"
                    >
                        <span class="live-dot"></span>
                        <span id="live_indicator_text">
                            Live Attendance
                        </span>
                    </div>

                </div>


                <div
                    id="latest_scan"
                    class="latest-card"
                >

                    @if(
                        $latestAttendance
                        && $latestStudent
                    )

                        <div class="latest-content fade-enter">


                            {{-- PHOTO --}}

                            <div class="profile-photo-wrap">

                                @if($latestPhoto)

                                    <img
                                        src="{{ $latestPhoto }}"
                                        alt="{{ $latestStudent->first_name }} {{ $latestStudent->last_name }}"
                                        class="profile-photo"
                                        onerror="
                                            this.style.display='none';
                                            this.nextElementSibling.style.display='flex';
                                        "
                                    >

                                    <div
                                        class="profile-fallback"
                                        style="display:none"
                                    >
                                        {{
                                            strtoupper(
                                                substr(
                                                    $latestStudent->first_name,
                                                    0,
                                                    1
                                                )
                                            )
                                        }}
                                    </div>

                                @else

                                    <div class="profile-fallback">
                                        {{
                                            strtoupper(
                                                substr(
                                                    $latestStudent->first_name,
                                                    0,
                                                    1
                                                )
                                            )
                                        }}
                                    </div>

                                @endif


                                <div class="profile-accent">
                                    ✓
                                </div>

                            </div>



                            {{-- STUDENT DETAILS --}}

                            <div class="min-w-0">

                                <div class="student-kicker">
                                    Checked In Student
                                </div>


                                <div class="student-name">

                                    {{ $latestStudent->first_name }}

                                    {{ $latestStudent->last_name }}

                                </div>


                                <div class="student-meta">

                                    <span>
                                        {{ $latestStudent->program_code }}
                                    </span>

                                    @if($latestStudent->year_level)

                                        <span>•</span>

                                        <span>
                                            Year {{ $latestStudent->year_level }}
                                        </span>

                                    @endif

                                </div>


                                <div class="attendance-confirmed">
                                    ✓ Attendance Confirmed
                                </div>

                            </div>

                        </div>


                    @else

                        <div class="waiting">

                            <div class="waiting-line"></div>

                            <div class="waiting-title">
                                Waiting for first attendee
                            </div>

                            <div class="waiting-text">
                                Tap a registered student ID on the RFID reader.
                            </div>

                        </div>

                    @endif

                </div>

            </div>



            {{-- RECENT ARRIVALS --}}

            <aside class="arrivals-column">

                <div class="arrivals-heading">

                    <div class="section-label">
                        Recent Arrivals
                    </div>

                    <h2 class="section-title">
                        Today
                    </h2>

                </div>


                <div
                    id="attendance_feed"
                    class="arrivals-card"
                >

                    @forelse(
                        $recentRecords
                        as $record
                    )

                        @php

                            $student =
                                $record->student;


                            $photo =
                                $student?->photo_path
                                    ? asset(
                                        'student_photos/'
                                        . basename(
                                            $student->photo_path
                                        )
                                    )
                                    : null;

                        @endphp


                        <div class="arrival-row">

                            <div class="arrival-person">

                                @if($photo)

                                    <img
                                        src="{{ $photo }}"
                                        alt=""
                                        class="arrival-photo"
                                    >

                                @else

                                    <div class="arrival-fallback">

                                        {{
                                            strtoupper(
                                                substr(
                                                    $student?->first_name
                                                    ?? 'S',
                                                    0,
                                                    1
                                                )
                                            )
                                        }}

                                    </div>

                                @endif


                                <div class="min-w-0">

                                    <div class="arrival-name">

                                        {{ $student?->first_name }}

                                        {{ $student?->last_name }}

                                    </div>


                                    <div class="arrival-program">

                                        {{
                                            $student?->program_code
                                            ?? '—'
                                        }}

                                    </div>

                                </div>

                            </div>


                            <div class="arrival-time">

                                {{
                                    $record->time_in
                                        ? \Carbon\Carbon::parse(
                                            $record->time_in
                                        )->format('h:i A')
                                        : '—'
                                }}

                            </div>

                        </div>


                    @empty

                        <div class="arrivals-empty">
                            Recent student check-ins will appear here.
                        </div>

                    @endforelse

                </div>

            </aside>

        </section>



        {{-- ANNOUNCEMENT BAR --}}

        <section class="announcement-bar">

            <div class="announcement-label">

                <span>◀</span>

                <span>
                    Announcements
                </span>

            </div>


            <div class="announcement-track">

                <div
                    id="announcement_ticker"
                    class="ticker"
                >
                    Welcome to {{ $event->event_name }}
                    &nbsp;&nbsp; • &nbsp;&nbsp;
                    Attendance is being recorded through DySign RFID
                    &nbsp;&nbsp; • &nbsp;&nbsp;
                    Please keep your school ID ready
                    &nbsp;&nbsp; • &nbsp;&nbsp;
                    Dr. Yanga's Colleges, Inc.
                </div>

            </div>

        </section>

    </main>

</div>



{{-- ====================================================== --}}
{{-- EVENT CLOSED OVERLAY --}}
{{-- ====================================================== --}}

<div
    id="closed_overlay"
    class="closed-overlay"
>

    <div class="closed-card">

        <div class="closed-icon">
            ✓
        </div>


        <div class="closed-title">
            Attendance Closed
        </div>


        <div class="closed-text">
            The scheduled event has ended.
            No additional RFID attendance records can be accepted.
            Returning to the event page...
        </div>

    </div>

</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | ROUTES
        |--------------------------------------------------------------------------
        */

        const scanUrl =
            @json(
                route(
                    'attendance.scan',
                    $event->event_id,
                    false
                )
            );


        const feedUrl =
            @json(
                route(
                    'attendance.feed',
                    $event->event_id,
                    false
                )
            );


        const closedRedirectUrl =
            @json(
                route(
                    'my-events.show',
                    $event->event_id,
                    false
                )
            );


        const csrfToken =
            document
                .querySelector(
                    'meta[name="csrf-token"]'
                )
                ?.getAttribute(
                    'content'
                )
            || @json(csrf_token());


        /*
        |--------------------------------------------------------------------------
        | LIVE CLOCK
        |--------------------------------------------------------------------------
        */

        function updateClock() {

            const now =
                new Date();


            const time =
                now.toLocaleTimeString(
                    'en-US',
                    {
                        hour:
                            'numeric',

                        minute:
                            '2-digit',

                        second:
                            '2-digit',

                        hour12:
                            true
                    }
                );


            const date =
                now.toLocaleDateString(
                    'en-US',
                    {
                        weekday:
                            'long',

                        year:
                            'numeric',

                        month:
                            'long',

                        day:
                            'numeric'
                    }
                );


            const clock =
                document.getElementById(
                    'live_clock'
                );


            const dateLabel =
                document.getElementById(
                    'live_date'
                );


            if (clock) {

                clock.textContent =
                    time;

            }


            if (dateLabel) {

                dateLabel.textContent =
                    date;

            }

        }


        updateClock();


        setInterval(
            updateClock,
            1000
        );


        /*
        |--------------------------------------------------------------------------
        | SCANNER INPUT
        |--------------------------------------------------------------------------
        */

        let processing =
            false;


        let sessionClosed =
            false;


        let scanBuffer =
            '';


        let scanTimer =
            null;


        const scannerInput =
            document.createElement(
                'input'
            );


        scannerInput.type =
            'text';


        scannerInput.autocomplete =
            'off';


        scannerInput.inputMode =
            'numeric';


        scannerInput.style.position =
            'fixed';


        scannerInput.style.opacity =
            '0';


        scannerInput.style.pointerEvents =
            'none';


        scannerInput.style.left =
            '-9999px';


        document.body.appendChild(
            scannerInput
        );


        scannerInput.focus();


        /*
        |--------------------------------------------------------------------------
        | ESCAPE HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(
            value
        ) {

            return String(
                value
                ?? ''
            )
            .replaceAll(
                '&',
                '&amp;'
            )
            .replaceAll(
                '<',
                '&lt;'
            )
            .replaceAll(
                '>',
                '&gt;'
            )
            .replaceAll(
                '"',
                '&quot;'
            )
            .replaceAll(
                "'",
                '&#039;'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE SESSION
        |--------------------------------------------------------------------------
        */

        function closeSession() {

            if (
                sessionClosed
            ) {

                return;

            }


            sessionClosed =
                true;


            processing =
                false;


            scannerInput.disabled =
                true;


            const overlay =
                document.getElementById(
                    'closed_overlay'
                );


            const indicator =
                document.getElementById(
                    'live_indicator'
                );


            const indicatorText =
                document.getElementById(
                    'live_indicator_text'
                );


            if (overlay) {

                overlay.classList.add(
                    'show'
                );

            }


            if (indicator) {

                indicator.style.color =
                    '#6B7280';

            }


            if (indicatorText) {

                indicatorText.textContent =
                    'Attendance Closed';

            }


            setTimeout(
                function () {

                    window.location.href =
                        closedRedirectUrl;

                },
                2600
            );

        }


        /*
        |--------------------------------------------------------------------------
        | RFID BUFFER
        |--------------------------------------------------------------------------
        */

        scannerInput.addEventListener(
            'input',
            function () {

                if (
                    processing
                    || sessionClosed
                ) {

                    this.value =
                        '';

                    return;

                }


                scanBuffer +=
                    this.value;


                this.value =
                    '';


                clearTimeout(
                    scanTimer
                );


                scanTimer =
                    setTimeout(
                        function () {

                            const rfid =
                                scanBuffer
                                    .replace(
                                        /\D/g,
                                        ''
                                    )
                                    .slice(
                                        -10
                                    );


                            scanBuffer =
                                '';


                            if (
                                rfid.length
                                === 10
                            ) {

                                scanStudent(
                                    rfid
                                );

                            }

                        },
                        120
                    );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | SCAN STUDENT
        |--------------------------------------------------------------------------
        */

        async function scanStudent(
            rfid
        ) {

            if (
                processing
                || sessionClosed
            ) {

                return;

            }


            processing =
                true;


            try {

                const response =
                    await fetch(
                        scanUrl,
                        {
                            method:
                                'POST',

                            /*
                            |--------------------------------------------------------------------------
                            | Keep Laravel Session Cookie
                            |--------------------------------------------------------------------------
                            |
                            | The scanner page and attendance endpoint must use
                            | the exact same browser session for CSRF validation.
                            |
                            */

                            credentials:
                                'same-origin',

                            cache:
                                'no-store',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',

                                'X-CSRF-TOKEN':
                                    csrfToken,

                            },

                            body:
                                JSON.stringify({

                                    /*
                                     * Send the token in the body as an
                                     * additional Laravel CSRF fallback.
                                     */

                                    _token:
                                        csrfToken,

                                    rfid_identifier:
                                        rfid

                                })
                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | Expired / Stale Laravel Session
                |--------------------------------------------------------------------------
                |
                | HTTP 419 means the page's CSRF token no longer matches the
                | current Laravel session. Reloading obtains a fresh token.
                |
                */

                if (
                    response.status
                    === 419
                ) {

                    window.location.reload();

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Authentication Session Expired
                |--------------------------------------------------------------------------
                */

                if (
                    response.status
                    === 401
                    || response.status
                    === 403
                ) {

                    window.location.reload();

                    return;

                }


                const contentType =
                    response.headers.get(
                        'content-type'
                    )
                    ?? '';


                if (
                    !contentType.includes(
                        'application/json'
                    )
                ) {

                    throw new Error(
                        'Attendance endpoint returned a non-JSON response.'
                    );

                }


                const data =
                    await response.json();


                if (
                    data.code
                    === 'event_closed'
                ) {

                    closeSession();

                    return;

                }


                if (
                    data.record
                ) {

                    updateLatest(
                        data.record
                    );

                }


                if (
                    response.ok
                    && data.success
                ) {

                    await refreshAttendance();

                }

            }


            catch (error) {

                console.error(
                    error
                );

            }


            finally {

                processing =
                    false;


                if (
                    !sessionClosed
                ) {

                    scannerInput.focus();

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | REFRESH ATTENDANCE
        |--------------------------------------------------------------------------
        */

        async function refreshAttendance() {

            if (
                sessionClosed
            ) {

                return;

            }


            try {

                const response =
                    await fetch(
                        feedUrl,
                        {
                            credentials:
                                'same-origin',

                            cache:
                                'no-store',

                            headers: {

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'

                            }
                        }
                    );


                if (
                    response.status
                    === 401
                    || response.status
                    === 403
                    || response.status
                    === 419
                ) {

                    window.location.reload();

                    return;

                }


                if (
                    !response.ok
                ) {

                    return;

                }


                const contentType =
                    response.headers.get(
                        'content-type'
                    )
                    ?? '';


                if (
                    !contentType.includes(
                        'application/json'
                    )
                ) {

                    window.location.reload();

                    return;

                }


                const data =
                    await response.json();


                /*
                |--------------------------------------------------------------------------
                | EVENT ENDED
                |--------------------------------------------------------------------------
                */

                if (
                    data.scannable
                    === false
                ) {

                    closeSession();

                    return;

                }


                if (
                    data.records
                    && data.records.length
                ) {

                    updateLatest(
                        data.records[0]
                    );


                    updateFeed(
                        data.records
                    );

                }

            }


            catch (error) {

                console.error(
                    error
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE LATEST STUDENT
        |--------------------------------------------------------------------------
        */

        function updateLatest(
            record
        ) {

            const container =
                document.getElementById(
                    'latest_scan'
                );


            if (
                !container
            ) {

                return;

            }


            const firstName =
                record.first_name
                ?? 'Unknown';


            const lastName =
                record.last_name
                ?? '';


            const initial =
                firstName
                    .charAt(0)
                    .toUpperCase();


            const program =
                record.program_code
                ?? '—';


            const year =
                record.year_level
                    ? `Year ${record.year_level}`
                    : '';


            const photoMarkup =
                record.photo_url

                    ? `

                        <img
                            src="${escapeHtml(record.photo_url)}"
                            alt="${escapeHtml(firstName)} ${escapeHtml(lastName)}"
                            class="profile-photo"
                            onerror="
                                this.style.display='none';
                                this.nextElementSibling.style.display='flex';
                            "
                        >

                        <div
                            class="profile-fallback"
                            style="display:none"
                        >
                            ${escapeHtml(initial)}
                        </div>

                    `

                    : `

                        <div class="profile-fallback">
                            ${escapeHtml(initial)}
                        </div>

                    `;


            container.innerHTML =
                `

                    <div class="latest-content fade-enter">


                        <div class="profile-photo-wrap">

                            ${photoMarkup}

                            <div class="profile-accent">
                                ✓
                            </div>

                        </div>


                        <div class="min-w-0">

                            <div class="student-kicker">
                                Checked In Student
                            </div>


                            <div class="student-name">

                                ${escapeHtml(firstName)}
                                ${escapeHtml(lastName)}

                            </div>


                            <div class="student-meta">

                                <span>
                                    ${escapeHtml(program)}
                                </span>

                                ${
                                    year
                                        ? `

                                            <span>•</span>

                                            <span>
                                                ${escapeHtml(year)}
                                            </span>

                                        `
                                        : ''
                                }

                            </div>


                            <div class="attendance-confirmed">
                                ✓ Attendance Confirmed
                            </div>

                        </div>

                    </div>

                `;

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE RECENT ARRIVALS
        |--------------------------------------------------------------------------
        */

        function updateFeed(
            records
        ) {

            const container =
                document.getElementById(
                    'attendance_feed'
                );


            if (
                !container
            ) {

                return;

            }


            const recent =
                records.slice(
                    0,
                    5
                );


            container.innerHTML =
                '';


            recent.forEach(
                function (
                    record
                ) {

                    const firstName =
                        record.first_name
                        ?? 'Unknown';


                    const lastName =
                        record.last_name
                        ?? '';


                    const initial =
                        firstName
                            .charAt(0)
                            .toUpperCase();


                    const photoMarkup =
                        record.photo_url

                            ? `

                                <img
                                    src="${escapeHtml(record.photo_url)}"
                                    alt=""
                                    class="arrival-photo"
                                >

                            `

                            : `

                                <div class="arrival-fallback">
                                    ${escapeHtml(initial)}
                                </div>

                            `;


                    container.innerHTML +=
                        `

                            <div class="arrival-row">

                                <div class="arrival-person">

                                    ${photoMarkup}


                                    <div class="min-w-0">

                                        <div class="arrival-name">

                                            ${escapeHtml(firstName)}
                                            ${escapeHtml(lastName)}

                                        </div>


                                        <div class="arrival-program">

                                            ${escapeHtml(
                                                record.program_code
                                                ?? '—'
                                            )}

                                        </div>

                                    </div>

                                </div>


                                <div class="arrival-time">

                                    ${escapeHtml(
                                        record.time_display
                                        ?? '—'
                                    )}

                                </div>

                            </div>

                        `;

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | KEEP INPUT FOCUSED
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'click',
            function () {

                if (
                    !sessionClosed
                ) {

                    scannerInput.focus();

                }

            }
        );


        window.addEventListener(
            'focus',
            function () {

                if (
                    !sessionClosed
                ) {

                    scannerInput.focus();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | LIVE FEED
        |--------------------------------------------------------------------------
        */

        setInterval(
            refreshAttendance,
            5000
        );


        refreshAttendance();

    }
);

</script>

</body>

</html>
