<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DySign | Digital Identity System</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="preload" as="image" href="{{ asset('images/logo.png') }}">
    <link rel="preload" as="image" href="{{ asset('images/dyci1.jpg') }}">
    <link rel="preload" as="image" href="{{ asset('images/dyci4.jpg') }}">

    <script>document.documentElement.classList.add('js');</script>

    <style>
        :root {
            --navy: #101064;
            --gold: #D4A017;
            --soft-gold: #F0C35B;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        * {
            margin: 0;
            padding: 0;
        }

        html { scroll-behavior: smooth; }

        body {
            min-width: 320px;
            background: var(--navy);
            color: #fff;
            font-family: "Segoe UI", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        a, button { -webkit-tap-highlight-color: transparent; }
        button { font: inherit; }

        a:focus-visible, button:focus-visible {
            outline: 3px solid var(--soft-gold);
            outline-offset: 4px;
        }

        .landing {
            position: relative;
            isolation: isolate;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-height: 100svh;
            overflow: hidden;
            background: #0a0d49;
        }

        /* Keep the five existing photos and the original slideshow JS. */
        .slideshow {
            position: absolute;
            inset: 0;
            z-index: -3;
            background: #0a0d49;
        }

        .slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            opacity: 0;
            transform: scale(1.045);
            transition: opacity 1.15s ease-in-out, transform 7s ease;
        }

        .slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .overlay {
            position: absolute;
            inset: 0;
            z-index: -2;
            pointer-events: none;
            background: linear-gradient(
                95deg,
                rgba(7, 9, 58, .91) 0%,
                rgba(12, 15, 76, .80) 44%,
                rgba(17, 20, 91, .59) 75%,
                rgba(13, 17, 74, .56) 100%
            );
        }

        .bottom-glow {
            position: absolute;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background: linear-gradient(
                0deg,
                rgba(8, 10, 62, .82) 0%,
                rgba(10, 12, 65, .20) 34%,
                transparent 65%
            );
        }

        /* Subtle outlined circles — like the login page. */
        .decorative-rings {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .decorative-ring {
            position: absolute;
            display: block;
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 50%;
        }

        .decorative-ring.one {
            width: 610px;
            height: 610px;
            top: -330px;
            right: 9%;
        }

        .decorative-ring.two {
            width: 460px;
            height: 460px;
            top: -256px;
            right: calc(9% + 75px);
            border-color: rgba(255, 255, 255, .12);
        }

        .decorative-ring.three {
            width: 340px;
            height: 340px;
            bottom: -255px;
            right: -100px;
            border-color: rgba(212, 160, 23, .18);
        }

        /* Header */
        .navbar, .hero, .footer {
            position: relative;
            z-index: 1;
            width: 100%;
        }

        .navbar {
            max-width: 1600px;
            margin: 0 auto;
            padding: 30px 6.7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            color: white;
        }

        .brand img {
            width: 58px;
            height: 58px;
            flex-shrink: 0;
            object-fit: contain;
            padding: 5px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .5);
            background: rgba(255, 255, 255, .94);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .18);
        }

        .brand-name {
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -.8px;
            line-height: 1.1;
        }

        .brand-name span { color: var(--gold); }

        .brand-subtitle {
            margin-top: 4px;
            font-size: 11px;
            letter-spacing: .04em;
            color: rgba(255,255,255,.76);
        }

        .nav-signin {
            padding: 12px 26px;
            border: 1px solid rgba(255,255,255,.52);
            border-radius: 10px;
            background: rgba(255,255,255,.09);
            -webkit-backdrop-filter: blur(12px);
            backdrop-filter: blur(12px);
            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: background .25s, border-color .25s, transform .25s;
            white-space: nowrap;
        }

        .nav-signin:hover {
            background: rgba(212,160,23,.96);
            border-color: var(--gold);
            color: var(--navy);
            transform: translateY(-1px);
        }

        /* Main hero */
        .hero {
            flex: 1;
            max-width: 1600px;
            margin: 0 auto;
            padding: 42px 6.7% 65px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: clamp(36px, 6vw, 125px);
        }

        .hero-content {
            flex: 1 1 660px;
            min-width: 0;
            max-width: 760px;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 13px;
            color: var(--soft-gold);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .22em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            display: block;
            width: 35px;
            height: 2px;
            flex-shrink: 0;
            background: var(--gold);
        }

        .school-name {
            margin-top: 28px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(3.8rem, 5.45vw, 6rem);
            font-weight: 500;
            line-height: 1.09;
            letter-spacing: -.045em;
            text-shadow: 0 9px 32px rgba(0,0,0,.15);
        }

        .school-name span {
            color: #F0C35B;
            font-style: italic;
        }

        .hero-description {
            max-width: 560px;
            margin-top: 28px;
            color: rgba(246, 247, 255, .86);
            font-size: 16px;
            line-height: 1.85;
        }

        .hero-actions {
            margin-top: 35px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .main-signin {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding: 16px 30px;
            border-radius: 11px;
            background: var(--gold);
            color: var(--navy);
            text-decoration: none;
            font-size: 14px;
            font-weight: 800;
            box-shadow: 0 12px 30px rgba(0,0,0,.2);
            transition: transform .25s, background .25s, box-shadow .25s;
        }

        .main-signin:hover {
            background: #EDBF45;
            box-shadow: 0 15px 32px rgba(0,0,0,.22);
            transform: translateY(-2px);
        }

        .secure-note {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,.76);
            font-size: 12px;
            white-space: nowrap;
        }

        /* Quiet frosted-glass card, not a second competing hero. */
        .identity-card {
            position: relative;
            width: clamp(265px, 25vw, 315px);
            flex: 0 0 auto;
            overflow: hidden;
            padding: 29px;
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 21px;
            background: linear-gradient(135deg, rgba(255,255,255,.18), rgba(255,255,255,.07));
            -webkit-backdrop-filter: blur(20px);
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 48px rgba(3,5,37,.22), inset 0 1px 0 rgba(255,255,255,.13);
        }

        .identity-card img {
            display: block;
            width: 82px;
            height: 82px;
            object-fit: contain;
            margin-bottom: 23px;
            padding: 5px;
            border-radius: 50%;
            background: rgba(255,255,255,.94);
        }

        .identity-label {
            color: #F0C35B;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .19em;
            text-transform: uppercase;
        }

        .identity-card h2 {
            margin-top: 14px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 27px;
            font-weight: 500;
            line-height: 1.23;
        }

        .identity-card p {
            margin-top: 15px;
            color: rgba(255,255,255,.80);
            font-size: 13px;
            line-height: 1.8;
        }

        .gold-line {
            width: 52px;
            height: 3px;
            margin-top: 24px;
            border-radius: 5px;
            background: var(--gold);
        }

        /* Footer and familiar manual slideshow controls */
        .footer {
            max-width: 1600px;
            margin: 0 auto;
            padding: 23px 6.7% 27px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            border-top: 1px solid rgba(255,255,255,.12);
            color: rgba(255,255,255,.69);
            font-size: 11px;
        }

        .slideshow-controls {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .control-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.36);
            background: rgba(255,255,255,.12);
            -webkit-backdrop-filter: blur(9px);
            backdrop-filter: blur(9px);
            color: white;
            font-size: 21px;
            cursor: pointer;
            transition: background .2s, color .2s, border-color .2s;
        }

        .control-button:hover {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--navy);
        }

        .dots {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border: 0;
            border-radius: 30px;
            background: rgba(255,255,255,.45);
            cursor: pointer;
            transition: width .25s, background .25s;
        }

        .dot.active {
            width: 27px;
            background: var(--gold);
        }

        @media (max-width: 1000px) {
            .identity-card { width: 260px; }
            .school-name { font-size: clamp(3.15rem, 5.8vw, 4.7rem); }
            .hero { gap: 32px; }
        }

        @media (max-width: 800px) {
            .identity-card { display: none; }
            .hero { justify-content: flex-start; }
            .hero-content { flex-basis: 100%; max-width: 720px; }
            .decorative-ring.one { right: -180px; }
            .decorative-ring.two { right: -105px; }
        }

        @media (max-width: 600px) {
            .navbar { padding: 21px 6%; }
            .brand { gap: 10px; }
            .brand img { width: 45px; height: 45px; }
            .brand-name { font-size: 22px; }
            .brand-subtitle { font-size: 10px; }
            .nav-signin { padding: 10px 15px; font-size: 12px; }
            .hero { padding: 65px 6% 60px; }
            .school-name {
                margin-top: 22px;
                font-size: clamp(2.8rem, 10.7vw, 4rem);
                line-height: 1.11;
            }
            .eyebrow { gap: 10px; font-size: 9px; letter-spacing: .14em; }
            .eyebrow::before { width: 25px; }
            .hero-description { margin-top: 22px; font-size: 14px; line-height: 1.8; }
            .hero-actions { margin-top: 29px; gap: 15px; }
            .main-signin { padding: 15px 22px; }
            .footer { padding: 18px 6% 22px; font-size: 10px; }
            .slideshow-controls { margin-left: auto; }
            .decorative-rings { opacity: .7; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .slide, .main-signin, .nav-signin, .dot { transition: none; }
        }

        /* ===========================================
           DYCI × DySign University Intro
           Only the intro is styled here; the landing page is unchanged.
           =========================================== */
        .dyci-intro {
            display: none; /* Safe fallback when JavaScript is disabled. */
            position: fixed;
            inset: 0;
            z-index: 99999;
            align-items: center;
            justify-content: center;
            isolation: isolate;
            overflow: hidden;
            padding: 24px;
            background:
                linear-gradient(110deg,
                    rgba(9, 11, 55, .87) 0%,
                    rgba(16, 16, 100, .69) 48%,
                    rgba(12, 14, 66, .79) 100%),
                url('{{ asset('images/dyci4.jpg') }}') center center / cover no-repeat;
            opacity: 1;
            visibility: visible;
            transition: opacity .78s ease, visibility .78s ease;
        }

        html.js .dyci-intro { display: flex; }

        .dyci-intro.is-exiting {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        /* Delicate architectural rings, matching the login/landing branding. */
        .dyci-intro::before,
        .dyci-intro::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .dyci-intro::before {
            width: min(60vw, 650px);
            aspect-ratio: 1;
            right: -160px;
            top: -330px;
            border: 1px solid rgba(255, 255, 255, .20);
        }

        .dyci-intro::after {
            width: min(45vw, 495px);
            aspect-ratio: 1;
            right: -75px;
            top: -260px;
            border: 1px solid rgba(255, 255, 255, .13);
        }

        /* Floating school identity: no rectangle, border, or glass panel. */
        .dyci-intro-content {
            position: relative;
            z-index: 1;
            width: min(100%, 520px);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0;
            padding: 28px 18px;
            border: none;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
            animation: dyci-intro-enter .8s ease both;
        }

        .dyci-intro-seal {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            border-radius: 50%;
            background: rgba(255,255,255,.97);
            box-shadow: 0 6px 22px rgba(5, 8, 36, .18);
        }

        .dyci-intro-seal img {
            display: block;
            width: 82px; /* Avoid enlarging a potentially small logo asset. */
            height: 82px;
            max-width: 100%;
            object-fit: contain;
            image-rendering: auto;
            filter: none;
        }

        .dyci-intro-wordmark {
            margin-top: 22px;
            font-size: clamp(42px, 7vw, 52px);
            font-weight: 800;
            letter-spacing: -.055em;
            line-height: 1.15;
            color: #FFFFFF;
            text-shadow: 0 4px 25px rgba(5, 8, 36, .42);
        }

        .dyci-intro-wordmark span { color: #F0C35B; }

        .dyci-intro-name {
            margin-top: 9px;
            font-size: 11px;
            line-height: 1.65;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .94);
            text-shadow: 0 2px 12px rgba(5, 8, 36, .40);
        }

        .dyci-intro-caption {
            margin-top: 4px;
            color: rgba(255, 255, 255, .70);
            font-size: 11px;
            letter-spacing: .04em;
        }

        .dyci-intro-accent {
            width: 42px;
            height: 2px;
            margin-top: 23px;
            border-radius: 50px;
            background: #F0C35B;
        }

        @keyframes dyci-intro-enter {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .dyci-intro-content { padding: 25px 12px; }
            .dyci-intro-seal { width: 94px; height: 94px; }
            .dyci-intro-seal img { width: 74px; height: 74px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .dyci-intro, .dyci-intro-content { transition: none; animation: none; }
        }
    </style>
</head>

<body>

    <!-- Intro overlay: fades into the existing DySign landing page. -->
    <div id="dyci-intro" class="dyci-intro" aria-hidden="true">
        <div class="dyci-intro-content">
            <div class="dyci-intro-seal">
                <img src="{{ asset('images/logo.png') }}" alt="" width="82" height="82" decoding="async">
            </div>
            <p class="dyci-intro-wordmark">Dy<span>Sign</span></p>
            <p class="dyci-intro-name">Dr. Yanga's Colleges, Inc.</p>
            <p class="dyci-intro-caption">Digital Identity System</p>
            <span class="dyci-intro-accent" aria-hidden="true"></span>
        </div>
    </div>

    <main class="landing">

        <!-- Five DYCI Background Photos -->
        <div class="slideshow" aria-hidden="true">
            <img
                src="{{ asset('images/dyci1.jpg') }}"
                class="slide active"
                alt=""
                fetchpriority="high"
            >

            <img
                src="{{ asset('images/dyci2.jpg') }}"
                class="slide"
                alt=""
            >

            <img
                src="{{ asset('images/dyci3.jpg') }}"
                class="slide"
                alt=""
            >

            <img
                src="{{ asset('images/dyci4.jpg') }}"
                class="slide"
                alt=""
            >

            <img
                src="{{ asset('images/dyci5.jpg') }}"
                class="slide"
                alt=""
            >
        </div>

        <div class="overlay"></div>
        <div class="bottom-glow"></div>

        <!-- Decorative university-style rings, inspired by the login page. -->
        <div class="decorative-rings" aria-hidden="true">
            <span class="decorative-ring one"></span>
            <span class="decorative-ring two"></span>
            <span class="decorative-ring three"></span>
        </div>

        <!-- Navbar -->
        <header class="navbar">

            <div class="brand">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="DYCI Logo"
                >

                <div>
                    <div class="brand-name">
                        Dy<span>Sign</span>
                    </div>

                    <div class="brand-subtitle">
                        Digital Identity System
                    </div>
                </div>
            </div>

            <a href="{{ route('login') }}" class="nav-signin">
                Sign In
            </a>

        </header>

        <!-- Hero -->
        <section class="hero">

            <div class="hero-content">

                <div class="eyebrow">
                    Dr. Yanga's Colleges, Inc.
                </div>

                <h1 class="school-name">
                    Your Campus.<br>
                    Your Identity.<br>
                    <span>One DySign.</span>
                </h1>

                <p class="hero-description">
                    A centralized digital platform for student identity,
                    event management, RFID attendance, and participation
                    monitoring at Dr. Yanga's Colleges, Inc.
                </p>

                <div class="hero-actions">

                    <a
                        href="{{ route('login') }}"
                        class="main-signin"
                    >
                        Sign In to DySign

                        <span aria-hidden="true">→</span>
                    </a>

                    <div class="secure-note">
                        <span aria-hidden="true">✓</span>
                        Authorized users only
                    </div>

                </div>

            </div>

            <!-- School Identity Card -->
            <div class="identity-card">

                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="DYCI Official Logo"
                >

                <div class="identity-label">
                    DYCI - ELIDA CAMPUS
                </div>

                <h2>
                    Dr. Yanga's<br>
                    Colleges, Inc.
                </h2>

                <p>
                    Connecting students, staff, and school
                    activities through one digital
                    identity and attendance platform.
                </p>

                <div class="gold-line"></div>

            </div>

        </section>

        <!-- Footer -->
        <footer class="footer">

            <p>
                © {{ date('Y') }} Dr. Yanga's Colleges, Inc.
                | DySign Digital Identity System
            </p>

            <div class="slideshow-controls">

                <button
                    type="button"
                    class="control-button"
                    id="previousSlide"
                    aria-label="Previous photo"
                >
                    &#8249;
                </button>

                <div class="dots" aria-label="Slideshow controls">

                    <button class="dot active" type="button" data-slide="0" aria-label="Show photo 1" aria-current="true"></button>
                    <button class="dot" type="button" data-slide="1" aria-label="Show photo 2"></button>
                    <button class="dot" type="button" data-slide="2" aria-label="Show photo 3"></button>
                    <button class="dot" type="button" data-slide="3" aria-label="Show photo 4"></button>
                    <button class="dot" type="button" data-slide="4" aria-label="Show photo 5"></button>

                </div>

                <button
                    type="button"
                    class="control-button"
                    id="nextSlide"
                    aria-label="Next photo"
                >
                    &#8250;
                </button>

            </div>

        </footer>

    </main>

    <!-- Automatic Slideshow -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Fade the DYCI logo intro into the existing landing page.
            const intro = document.getElementById('dyci-intro');
            if (intro) {
                const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const displayTime = reducedMotion ? 0 : 1650;

                window.setTimeout(() => {
                    intro.classList.add('is-exiting');
                    // Release the overlay after the fade finishes.
                    window.setTimeout(() => intro.remove(), reducedMotion ? 0 : 850);
                }, displayTime);
            }

            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.dot');

            const previousButton =
                document.getElementById('previousSlide');

            const nextButton =
                document.getElementById('nextSlide');

            let currentSlide = 0;
            let slideshowTimer = null;

            const interval = 5000;

            function showSlide(index) {

                currentSlide =
                    (index + slides.length) % slides.length;

                slides.forEach((slide, i) => {
                    slide.classList.toggle(
                        'active',
                        i === currentSlide
                    );
                });

                dots.forEach((dot, i) => {
                    const active = i === currentSlide;

                    dot.classList.toggle('active', active);

                    if (active) {
                        dot.setAttribute('aria-current', 'true');
                    } else {
                        dot.removeAttribute('aria-current');
                    }
                });
            }

            function stopSlideshow() {
                if (slideshowTimer !== null) {
                    clearInterval(slideshowTimer);
                    slideshowTimer = null;
                }
            }

            function startSlideshow() {
                stopSlideshow();

                if (
                    document.hidden ||
                    window.matchMedia(
                        '(prefers-reduced-motion: reduce)'
                    ).matches
                ) {
                    return;
                }

                slideshowTimer = setInterval(() => {
                    showSlide(currentSlide + 1);
                }, interval);
            }

            function navigateTo(index) {
                showSlide(index);
                startSlideshow();
            }

            previousButton.addEventListener('click', () => {
                navigateTo(currentSlide - 1);
            });

            nextButton.addEventListener('click', () => {
                navigateTo(currentSlide + 1);
            });

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    navigateTo(index);
                });
            });

            document.addEventListener(
                'visibilitychange',
                startSlideshow
            );

            startSlideshow();
        });
    </script>

</body>
</html>
