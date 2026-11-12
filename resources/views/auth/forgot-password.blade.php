<x-guest-layout>
    {{--
        DySign full-screen university password recovery.
        Same shell and visual treatment as the DySign login page.
        Uses the existing Laravel password.email action, CSRF token, validation,
        session feedback, and sign-in link; no auth backend changes.
    --}}
    <style>
        [x-cloak] { display: none !important; }

        .dysign-login-shell { min-height: 100vh; min-height: 100dvh; }
        .dysign-login-photo {
            position: absolute; inset: 0; width: 100%; height: 100%;
            object-fit: cover; opacity: 0;
            transform: scale(1.055);
            transition: opacity 1.35s ease, transform 6.5s ease;
        }
        .dysign-login-photo.is-active { opacity: 1; transform: scale(1); }
        .dysign-login-glass {
            background: rgba(250, 250, 255, .84);
            -webkit-backdrop-filter: blur(29px) saturate(1.25);
            backdrop-filter: blur(29px) saturate(1.25);
        }
        .dysign-login-card {
            background: rgba(255,255,255,.56);
            -webkit-backdrop-filter: blur(15px);
            backdrop-filter: blur(15px);
        }
        @media (max-width: 1023px) {
            .dysign-login-glass { background: rgba(249,250,255,.91); }
        }
        @media (prefers-reduced-motion: reduce) {
            .dysign-login-photo { transition: none; transform: none; }
            .dysign-login-photo.is-active { transform: none; }
        }
    </style>

    {{-- Fixed shell intentionally escapes the default Breeze guest layout's
         centered, max-width card to fill the browser viewport edge to edge. --}}
    <div class="dysign-login-shell fixed inset-0 z-[100] overflow-y-auto overflow-x-hidden bg-[#0C0E49] text-[#161943] antialiased">
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true" id="dysignLoginSlides">
            <img src="{{ asset('images/dyci1.jpg') }}" alt="" class="dysign-login-photo is-active" fetchpriority="high">
            <img src="{{ asset('images/dyci2.jpg') }}" alt="" class="dysign-login-photo" loading="lazy">
            <img src="{{ asset('images/dyci3.jpg') }}" alt="" class="dysign-login-photo" loading="lazy">
            <img src="{{ asset('images/dyci4.jpg') }}" alt="" class="dysign-login-photo" loading="lazy">
            <img src="{{ asset('images/dyci5.jpg') }}" alt="" class="dysign-login-photo" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-r from-[#050831]/80 via-[#101064]/60 to-[#101064]/35"></div>
        </div>

        <main class="relative z-10 grid min-h-full w-full grid-cols-1 lg:min-h-[100dvh] lg:grid-cols-[53%_47%]">
            {{-- LEFT: editorial university identity, same message as the existing page. --}}
            <section class="relative hidden min-h-[100dvh] flex-col justify-between overflow-hidden border-r border-white/10 bg-gradient-to-r from-[#070B42]/70 via-[#101064]/45 to-transparent px-10 py-10 text-white lg:flex xl:px-[6.5vw] xl:py-12">
                <div class="pointer-events-none absolute -right-28 -top-44 h-[540px] w-[540px] rounded-full border border-white/15"></div>
                <div class="pointer-events-none absolute -right-10 -top-20 h-[390px] w-[390px] rounded-full border border-white/10"></div>

                <header class="relative flex items-center justify-between gap-5">
                    <a href="{{ route('landing') }}" class="flex items-center gap-4 text-white no-underline" aria-label="Go to DySign home">
                        <span class="flex h-[58px] w-[58px] shrink-0 items-center justify-center rounded-full border border-white/50 bg-white/95 p-2 shadow-xl">
                            <img src="{{ asset('images/logo.png') }}" alt="DYCI seal" class="h-full w-full object-contain">
                        </span>
                        <span class="leading-tight">
                            <span class="block text-[11px] font-bold uppercase tracking-[0.16em] sm:text-xs">Dr. Yanga's Colleges, Inc.</span>
                            <span class="mt-1 block text-[10px] font-semibold uppercase tracking-[0.23em] text-[#E9BE44]">Elida Campus · Bulacan</span>
                        </span>
                    </a>
                    <span class="hidden rounded-full border border-white/30 bg-white/10 px-4 py-2 text-[10px] font-bold uppercase tracking-[0.16em] text-white backdrop-blur-xl xl:inline">DySign Portal</span>
                </header>

                <div class="relative max-w-[760px] py-14">
                    <div class="mb-8 flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.24em] text-[#E9BE44]">
                        <span class="h-[2px] w-10 bg-[#D4A017]"></span>
                        A more connected campus
                    </div>
                    <h1 class="text-[clamp(3.5rem,5.1vw,6.7rem)] leading-[1.11] tracking-[-0.047em] drop-shadow-lg" style="font-family: Georgia, 'Times New Roman', serif;">
                        Every event.<br>
                        Every presence.<br>
                        <span class="italic text-[#E9BE44]">Every DYCIan.</span>
                    </h1>
                    <p class="mt-9 max-w-lg text-base leading-8 text-white/85 xl:text-lg">
                        One digital space for student event attendance, participation, and meaningful campus experiences.
                    </p>
                    <div class="mt-10 h-[2px] w-[74px] bg-[#D4A017]"></div>
                </div>

                <footer class="relative flex items-end justify-between gap-5 border-t border-white/25 pt-6">
                    <div>
                        <div class="text-xs font-extrabold tracking-[0.18em] text-[#E9BE44]">DYSIGN</div>
                        <div class="mt-2 text-xs text-white/70">Digital Identity & Event Participation Management</div>
                    </div>
                    <span class="shrink-0 text-[10px] font-semibold uppercase tracking-[0.15em] text-white/65">DYCI · BULACAN</span>
                </footer>
            </section>

            {{-- RIGHT: matching login page glass panel, password reset only. --}}
            <section class="dysign-login-glass relative flex min-h-[100dvh] flex-col border-l border-white/30 shadow-[-20px_0_80px_rgba(4,9,46,.14)]">
                {{-- Mobile school branding --}}
                <div class="flex items-center justify-between border-b border-white/65 px-6 py-5 sm:px-10 lg:hidden">
                    <a href="{{ route('landing') }}" class="flex items-center gap-3 no-underline">
                        <img src="{{ asset('images/logo.png') }}" alt="DYCI seal" class="h-11 w-11 rounded-full bg-white p-1 object-contain shadow-md">
                        <span>
                            <span class="block text-xl font-extrabold tracking-tight text-[#101064]">Dy<span class="text-[#D4A017]">Sign</span></span>
                            <span class="block text-[10px] font-medium text-[#5E6686]">Dr. Yanga's Colleges, Inc.</span>
                        </span>
                    </a>
                    <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#8E6F18]">Elida Campus</span>
                </div>

                <div class="hidden items-center justify-end gap-3 px-7 pt-9 text-[11px] font-bold uppercase tracking-[0.16em] text-[#59627D] lg:flex xl:px-14 xl:pt-11">
                    <svg class="h-4 w-4 text-[#B38824]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="5" y="10" width="14" height="11" rx="2"></rect>
                        <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                    </svg>
                    Secure account access
                </div>

                <div class="flex flex-1 items-center justify-center px-5 py-8 sm:px-8 sm:py-12 lg:px-8 lg:py-6 xl:px-14">
                    <div class="dysign-login-card w-full max-w-[510px] rounded-[28px] border border-white/85 px-6 py-8 shadow-[0_22px_65px_rgba(15,19,76,.13)] sm:px-9 sm:py-10 xl:px-10">
                        <div class="mb-8">
                            <div class="mb-4 flex items-center gap-3">
                                <span class="h-[3px] w-8 rounded-full bg-[#D4A017]"></span>
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.22em] text-[#9D7411]">DySign · Account Recovery</p>
                            </div>
                            <h1 class="text-[clamp(2.35rem,3.5vw,3.55rem)] leading-[1.13] tracking-[-0.035em] text-[#101064]" style="font-family: Georgia, 'Times New Roman', serif;">
                                Forgot password<span class="text-[#D4A017]">?</span>
                            </h1>
                            <p class="mt-3 text-sm leading-6 text-[#667087]">
                                Enter your registered email address. We'll send you a link to reset your password.
                            </p>
                        </div>

                        {{-- Preserve Laravel's session feedback and form validation. --}}
                        @if (session('status'))
                            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                                Please check your email address and try again.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                            @csrf
                            <div>
                                <label for="email" class="mb-2.5 block text-[13px] font-semibold text-[#22264C]">Email address</label>
                                <div class="group relative">
                                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#858CA3] transition group-focus-within:text-[#101064]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                        <path d="m3 7 9 6 9-6"></path>
                                    </svg>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        inputmode="email"
                                        required
                                        autofocus
                                        placeholder="name@example.com"
                                        @class([
                                            'block w-full rounded-xl border bg-[#F8F9FC] py-3.5 pl-12 pr-4 text-sm text-[#171B43] placeholder:text-[#9CA2B4] transition focus:border-[#101064] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#101064]/10',
                                            'border-red-300' => $errors->has('email'),
                                            'border-[#E0E3EC]' => ! $errors->has('email'),
                                        ])
                                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                        @if($errors->has('email')) aria-describedby="email-error" @endif
                                    >
                                </div>
                                @if ($errors->has('email'))
                                    <div id="email-error" class="mt-2">
                                        <x-input-error :messages="$errors->get('email')" />
                                    </div>
                                @endif
                            </div>

                            <button
                                type="submit"
                                class="group flex w-full items-center justify-center gap-3 rounded-xl bg-[#101064] px-6 py-4 text-sm font-semibold tracking-wide text-white shadow-[0_14px_30px_rgba(16,16,100,0.18)] transition hover:-translate-y-0.5 hover:bg-[#22228B] focus:outline-none focus:ring-4 focus:ring-[#D4A017]/30"
                            >
                                Send Reset Link
                                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M5 12h14m-6-6 6 6-6 6"></path>
                                </svg>
                            </button>
                        </form>

                        <div class="mt-7 border-t border-[#D9DBE8] pt-5 text-center">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-[#101064] underline decoration-[#D4A017]/60 underline-offset-4 transition hover:text-[#A27813]">
                                &larr; Back to Sign In
                            </a>
                        </div>
                    </div>
                </div>

                <footer class="flex flex-wrap items-center justify-between gap-2 border-t border-white/60 px-6 py-4 text-[11px] text-[#747E95] sm:px-10 xl:px-14">
                    <span>&copy; {{ date('Y') }} DySign · Dr. Yanga's Colleges, Inc.</span>
                    <a href="{{ route('landing') }}" class="font-semibold text-[#101064] underline decoration-[#D4A017]/60 underline-offset-4 hover:text-[#A27813]">Back to Home</a>
                </footer>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slides = Array.from(document.querySelectorAll('#dysignLoginSlides .dysign-login-photo'));
            if (slides.length < 2) return;

            let activeIndex = 0;
            let timer = null;
            const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

            function renderSlide(index) {
                activeIndex = (index + slides.length) % slides.length;
                slides.forEach((slide, i) => slide.classList.toggle('is-active', i === activeIndex));
            }
            function stop() {
                if (timer !== null) { clearInterval(timer); timer = null; }
            }
            function start() {
                stop();
                if (document.hidden || motionQuery.matches) return;
                timer = window.setInterval(() => renderSlide(activeIndex + 1), 5000);
            }
            document.addEventListener('visibilitychange', start);
            motionQuery.addEventListener?.('change', start);
            start();
        });
    </script>
</x-guest-layout>
