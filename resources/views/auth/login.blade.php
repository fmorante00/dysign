<x-guest-layout>
    {{--
        DySign login • Dr. Yanga's Colleges, Inc. / Elida Campus
        Keeps the existing Laravel authentication routes, CSRF protection,
        validation components, session messages, and remember-me behavior.
        Uses the image assets already referenced in the existing project.
    --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{-- The centering classes let this page fill the viewport even if the
         guest layout uses a narrow, centered content wrapper. --}}
    <div class="relative left-1/2 w-screen -translate-x-1/2 overflow-x-hidden bg-[#F1F3F8] text-[#161943] antialiased">
        <main class="mx-auto flex min-h-screen w-full max-w-[1700px] flex-col bg-white shadow-[0_20px_90px_rgba(10,17,69,0.08)] lg:min-h-[100dvh] lg:flex-row">

            {{-- LEFT: UNIVERSITY IDENTITY --}}
            <section class="relative isolate hidden min-h-[760px] overflow-hidden bg-[#101064] text-white lg:flex lg:w-[53%]">
                <img
                    src="{{ asset('images/school.jpg') }}"
                    alt="Dr. Yanga's Colleges campus community"
                    class="absolute inset-0 h-full w-full object-cover"
                >

                <div class="absolute inset-0 bg-gradient-to-r from-[#090944]/95 via-[#101064]/83 to-[#11146A]/58"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#07073D]/90 via-transparent to-[#08083E]/30"></div>

                {{-- A quiet geometric motif inspired by the school's editorial design. --}}
                <div class="pointer-events-none absolute -right-36 -top-40 h-[520px] w-[520px] rounded-full border border-white/15"></div>
                <div class="pointer-events-none absolute -right-20 -top-24 h-[400px] w-[400px] rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute bottom-0 right-16 h-28 w-px bg-gradient-to-t from-[#D4A017]/80 to-transparent"></div>

                <div class="relative z-10 flex w-full flex-col justify-between px-12 py-11 xl:px-16 xl:py-14">
                    <div class="flex items-center justify-between gap-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/95 p-1.5 shadow-lg shadow-[#04032D]/20">
                                <img src="{{ asset('images/logo.png') }}" alt="Dr. Yanga's Colleges logo" class="h-full w-full object-contain">
                            </div>
                            <div class="leading-tight">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-white">Dr. Yanga's Colleges, Inc.</p>
                                <p class="mt-1 text-[11px] tracking-[0.13em] text-blue-100/80">ELIDA CAMPUS</p>
                            </div>
                        </div>
                        <span class="rounded-full border border-white/25 bg-white/10 px-4 py-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-white/90 backdrop-blur-sm">
                            DySign Portal
                        </span>
                    </div>

                    <div class="max-w-[650px] pb-10 pt-20">
                        <div class="mb-7 flex items-center gap-3">
                            <span class="h-px w-10 bg-[#E0AF33]"></span>
                            <span class="text-xs font-semibold uppercase tracking-[0.24em] text-[#F0C35B]">A more connected campus</span>
                        </div>

                        <h1 class="text-5xl leading-[1.12] tracking-[-0.035em] xl:text-[clamp(3.5rem,4.6vw,5.8rem)]" style="font-family: Georgia, 'Times New Roman', serif;">
                            Every event.<br>
                            Every presence.<br>
                            <span class="italic text-[#E0AF33]">Every DYCIan.</span>
                        </h1>

                        <p class="mt-8 max-w-md text-base leading-8 text-[#E0E4FF] xl:text-lg">
                            One digital space for student event attendance, participation, and meaningful campus experiences.
                        </p>
                    </div>

                    <div class="flex items-end justify-between gap-6 border-t border-white/20 pt-6">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-[#E0AF33]">DySign</p>
                            <p class="mt-2 max-w-[320px] text-xs leading-6 text-blue-100/80">
                                Event Participation Management System
                            </p>
                        </div>
                        <span class="whitespace-nowrap text-[11px] tracking-[0.15em] text-white/65">ELIDA · BULACAN</span>
                    </div>
                </div>
            </section>

            {{-- RIGHT: SECURE LOGIN --}}
            <section class="relative flex min-h-screen w-full flex-col bg-white lg:min-h-[760px] lg:w-[47%]">
                {{-- Mobile brand header --}}
                <div class="flex items-center justify-between bg-[#101064] px-6 py-5 text-white sm:px-10 lg:hidden">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Dr. Yanga's Colleges logo" class="h-11 w-11 rounded-full bg-white p-1 object-contain">
                        <div>
                            <p class="text-lg font-extrabold tracking-tight">Dy<span class="text-[#E0AF33]">Sign</span></p>
                            <p class="text-[10px] tracking-wide text-blue-100">Dr. Yanga's Colleges, Inc.</p>
                        </div>
                    </div>
                    <span class="rounded-full border border-white/25 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest">Elida</span>
                </div>

                <div class="hidden items-center justify-end px-10 pt-10 lg:flex xl:px-16 xl:pt-12">
                    <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.15em] text-[#59627D]">
                        <svg class="h-4 w-4 text-[#B38824]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <rect x="5" y="10" width="14" height="11" rx="2"></rect>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                        </svg>
                        Secure account access
                    </div>
                </div>

                <div class="flex flex-1 items-center justify-center px-6 py-12 sm:px-10 lg:px-10 lg:py-16 xl:px-16">
                    <div class="w-full max-w-[435px]">
                        <div class="mb-9">
                            <div class="mb-6 flex items-center gap-2.5">
                                <span class="h-1.5 w-7 rounded-full bg-[#D4A017]"></span>
                                <p class="text-[11px] font-bold uppercase tracking-[0.21em] text-[#A27813]">DySign · Member Portal</p>
                            </div>

                            <h2 class="text-[clamp(2.65rem,4vw,3.7rem)] leading-[1.08] tracking-[-0.035em] text-[#101064]" style="font-family: Georgia, 'Times New Roman', serif;">
                                Welcome back<span class="text-[#D4A017]">.</span>
                            </h2>
                            <p class="mt-4 text-sm leading-7 text-[#667087]">
                                Sign in to continue to your event participation portal.
                            </p>
                        </div>

                        {{-- Preserve Laravel's status and validation behavior. --}}
                        @if (session('status'))
                            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                                <x-auth-session-status :status="session('status')" />
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                                <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 7v6M12 17h.01"></path>
                                </svg>
                                <p>We couldn't sign you in. Please check the details below.</p>
                            </div>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('login') }}"
                            x-data="{ showPassword: false, submitting: false }"
                            x-on:submit="submitting = true"
                            class="space-y-6"
                        >
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
                                        autocomplete="username"
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
                                @if($errors->has('email'))
                                    <div id="email-error" class="mt-2">
                                        <x-input-error :messages="$errors->get('email')" />
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label for="password" class="mb-2.5 block text-[13px] font-semibold text-[#22264C]">Password</label>
                                <div class="group relative">
                                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#858CA3] transition group-focus-within:text-[#101064]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <rect x="5" y="10" width="14" height="11" rx="2"></rect>
                                        <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                                    </svg>
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        x-bind:type="showPassword ? 'text' : 'password'"
                                        autocomplete="current-password"
                                        required
                                        placeholder="Enter your password"
                                        @class([
                                            'block w-full rounded-xl border bg-[#F8F9FC] py-3.5 pl-12 pr-14 text-sm text-[#171B43] placeholder:text-[#9CA2B4] transition focus:border-[#101064] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#101064]/10',
                                            'border-red-300' => $errors->has('password'),
                                            'border-[#E0E3EC]' => ! $errors->has('password'),
                                        ])
                                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                        @if($errors->has('password')) aria-describedby="password-error" @endif
                                    >
                                    <button
                                        type="button"
                                        x-on:click="showPassword = !showPassword"
                                        x-bind:aria-label="showPassword ? 'Hide password' : 'Show password'"
                                        x-bind:aria-pressed="showPassword.toString()"
                                        class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-[#727B93] transition hover:bg-[#ECEEF5] hover:text-[#101064] focus:outline-none focus:ring-2 focus:ring-[#101064]/30"
                                    >
                                        <svg x-show="!showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path d="M2 12s3.7-6 10-6 10 6 10 6-3.7 6-10 6-10-6-10-6Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <svg x-show="showPassword" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path d="m3 3 18 18M10.6 6.1A11 11 0 0 1 12 6c6.3 0 10 6 10 6a16.4 16.4 0 0 1-3.3 3.6M6.7 6.8C3.7 8.6 2 12 2 12s3.7 6 10 6c1.2 0 2.4-.2 3.4-.6"></path>
                                            <path d="M10 10a3 3 0 0 0 4 4"></path>
                                        </svg>
                                    </button>
                                </div>
                                @if($errors->has('password'))
                                    <div id="password-error" class="mt-2">
                                        <x-input-error :messages="$errors->get('password')" />
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <label class="inline-flex cursor-pointer items-center gap-2.5 text-sm text-[#626B82]">
                                    <input
                                        id="remember"
                                        type="checkbox"
                                        name="remember"
                                        value="1"
                                        @checked(old('remember'))
                                        class="h-4 w-4 rounded border-[#D0D5E2] text-[#101064] focus:ring-[#101064]/30"
                                    >
                                    Remember me
                                </label>

                                @if(Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#101064] underline decoration-[#D4A017]/60 underline-offset-4 transition hover:text-[#A27813]">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <button
                                type="submit"
                                x-bind:disabled="submitting"
                                class="group flex w-full items-center justify-center gap-3 rounded-xl bg-[#101064] px-6 py-4 text-sm font-semibold tracking-wide text-white shadow-[0_14px_30px_rgba(16,16,100,0.18)] transition hover:bg-[#191981] hover:shadow-[0_16px_35px_rgba(16,16,100,0.23)] focus:outline-none focus:ring-4 focus:ring-[#D4A017]/30 disabled:cursor-wait disabled:opacity-75"
                            >
                                <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                    <path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path>
                                </svg>
                                <span x-text="submitting ? 'Signing in...' : 'Sign in to DySign'">Sign in to DySign</span>
                                <svg x-show="!submitting" class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M5 12h14m-6-6 6 6-6 6"></path>
                                </svg>
                            </button>
                        </form>

                        <div class="mt-8 border-t border-[#EAECF2] pt-6">
                            <div class="flex items-start gap-3 rounded-xl bg-[#F7F8FC] px-4 py-3.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#A27813]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M12 11v5M12 8h.01"></path>
                                </svg>
                                <p class="text-xs leading-6 text-[#616B80]">
                                    Don't have access yet? Student accounts are created by the Administrator. Check your invitation email to activate your account.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="flex flex-wrap items-center justify-between gap-2 border-t border-[#F0F1F5] px-6 py-5 text-[11px] text-[#858BA0] sm:px-10 xl:px-16">
                    <span>© {{ date('Y') }} DySign · Dr. Yanga's Colleges, Inc.</span>
                    <span>Elida Campus</span>
                </footer>
            </section>
        </main>
    </div>
</x-guest-layout>
