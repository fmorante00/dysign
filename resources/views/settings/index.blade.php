<x-admin-layout>
    @php
        $displayName = trim($user->name ?: 'DySign User');
        $initials = collect(preg_split('/\s+/', $displayName))
            ->filter()->take(2)
            ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
        $roleName = $user->role?->role_name ?? 'Not assigned';
        // Student academic details come from students, not users.department_id.
        // User already has a student() relationship, so no controller or migration change is needed.
        $isStudentAccount = $roleName === 'Student Account';
        $studentRecord = $isStudentAccount ? $user->student : null;
        $departmentName = $isStudentAccount
            ? ($studentRecord?->college ?: 'College not available')
            : ($user->department?->department_name ?? 'Not assigned');
        $accountStatus = $user->status ?? 'Unknown';
        $isActive = strtolower($accountStatus) === 'active';
    @endphp

    <style>
        [x-cloak] { display: none !important; }
        .ds3-hero {
            position: relative;
            isolation: isolate;
            background:
                linear-gradient(105deg, rgba(16,16,100,.98) 0%, rgba(16,16,100,.92) 57%, rgba(16,16,100,.69) 100%),
                url('{{ asset('images/school.jpg') }}') center/cover;
        }
        .ds3-hero::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0;
            height: 4px; background: #D4A017;
        }
        .ds3-hero-ring {
            position: absolute; pointer-events: none; border-radius: 50%;
            border: 1px solid rgba(255,255,255,.20);
        }
        .ds3-card {
            background: #fff; border: 1px solid #e2e8f0;
            border-radius: 22px; box-shadow: 0 6px 24px rgba(15,23,42,.035);
        }
        .ds3-field { display: block; width: 100%; min-height: 46px; margin-top: 8px;
            border: 1px solid #cbd5e1; border-radius: 11px; background: #fff;
            padding: 11px 13px; color: #172033; font-size: 14px;
            transition: border-color .18s ease, box-shadow .18s ease;
        }
        .ds3-field:focus { outline: none; border-color: #101064; box-shadow: 0 0 0 3px rgba(16,16,100,.11); }
        .ds3-label { color: #334155; font-size: 12px; font-weight: 700; }
        .ds3-primary {
            display: inline-flex; justify-content: center; align-items: center; gap: 8px;
            min-height: 44px; border: 1px solid #101064; border-radius: 11px;
            padding: 11px 18px; background: #101064; color: white;
            font-size: 13px; font-weight: 700; transition: background .18s ease;
        }
        .ds3-primary:hover { background: #29298b; }
        .ds3-primary:disabled { opacity: .5; cursor: not-allowed; }
        .ds3-secondary {
            display: inline-flex; justify-content: center; align-items: center; gap: 8px;
            min-height: 43px; padding: 10px 16px; border: 1px solid #d5dce7;
            border-radius: 11px; background: white; color: #334155;
            font-size: 13px; font-weight: 700; transition: border-color .18s, background .18s;
        }
        .ds3-secondary:hover { border-color: #101064; background: #f8f9fd; }
        .ds3-tab { display: flex; align-items: center; gap: 12px; width: 100%; padding: 12px 14px;
            border-radius: 12px; color: #475569; font-size: 13px; font-weight: 700;
            text-align: left; transition: background .18s, color .18s;
        }
        .ds3-tab:hover { background: #f1f4fa; color: #101064; }
        .ds3-tab[aria-selected="true"] { background: #101064; color: white; box-shadow: 0 4px 12px rgba(16,16,100,.12); }
        .ds3-tab[aria-selected="true"] .ds3-tab-icon { color: #E7C75B; }
        .ds3-tab-icon { flex: none; width: 19px; height: 19px; color: #64748b; }
        .ds3-eyebrow { font-size: 10px; line-height: 1.4; font-weight: 800;
            letter-spacing: .15em; text-transform: uppercase; color: #ac8320;
        }
        .ds3-help { margin-top: 7px; color: #64748b; font-size: 12px; line-height: 1.55; }
        .ds3-error { margin-top: 7px; font-size: 12px; font-weight: 600; color: #b91c1c; }
        .ds3-rule { border-top: 1px solid #edf0f5; }
        .ds3-data-label { font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .055em; color: #64748b;
        }
        .ds3-data-value { margin-top: 7px; color: #101064; font-size: 14px; font-weight: 700;
            line-height: 1.55; overflow-wrap: anywhere;
        }
        @media (prefers-reduced-motion: reduce) {
            .ds3-tab, .ds3-field, .ds3-primary, .ds3-secondary { transition: none; }
        }
    </style>

    <div
        x-data="{
            tab: {{ \Illuminate\Support\Js::from(
                $errors->updatePassword->any() || session('status') === 'password-updated'
                    ? 'security'
                    : (session('status') === 'verification-link-sent' ? 'account' : '')
            ) }} || (
                ['account', 'security', 'access'].includes(window.location.hash.slice(1))
                    ? window.location.hash.slice(1)
                    : (['account', 'security', 'access'].includes(window.sessionStorage.getItem('dysign-settings-tab'))
                        ? window.sessionStorage.getItem('dysign-settings-tab') : 'account')
            ),
            setTab(target) {
                this.tab = target;
                window.sessionStorage.setItem('dysign-settings-tab', target);
                window.history.replaceState(null, '', '#' + target);
            },
            showCurrent: false,
            showNew: false,
            showConfirm: false,
            newPassword: '',
            confirmPassword: '',
            capsLock: false,
            copiedId: false,
            get passwordScore() {
                const p = this.newPassword;
                if (!p.length) return 0;
                let score = 0;
                if (p.length >= 8) score++;
                if (p.length >= 12) score++;
                if (/[a-z]/.test(p) && /[A-Z]/.test(p)) score++;
                if (/\d/.test(p) && /[^A-Za-z0-9]/.test(p)) score++;
                return score;
            },
            get passwordLabel() {
                return ['Enter a new password', 'Needs improvement', 'Fair', 'Good', 'Strong'][this.passwordScore];
            }
        }"
        class="min-w-0 space-y-6 pb-10"
    >
        {{-- Distinctive, restrained DySign hero. --}}
        <section class="ds3-hero overflow-hidden rounded-[28px] text-white shadow-sm">
            <div class="ds3-hero-ring" style="width:410px;height:410px;right:-80px;top:-205px" aria-hidden="true"></div>
            <div class="ds3-hero-ring" style="width:300px;height:300px;right:-25px;top:-145px" aria-hidden="true"></div>
            <div class="ds3-hero-ring" style="width:180px;height:180px;right:35px;top:-85px;border-color:rgba(212,160,23,.55)" aria-hidden="true"></div>
            <div class="relative z-10 flex flex-col justify-between gap-6 px-7 py-9 sm:px-9 lg:flex-row lg:items-end lg:px-10 lg:py-10">
                <div class="max-w-xl">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="h-[2px] w-9 bg-[#D4A017]"></span>
                        <span class="text-[11px] font-bold uppercase tracking-[.2em] text-[#E7C75B]">DySign / Personal Control Center</span>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Account Settings</h1>
                    <p class="mt-3 text-sm leading-6 text-white/80">
                        Review your official account details, protect your sign-in, and see your access information.
                    </p>
                </div>
            </div>
        </section>

        {{-- At-a-glance identity strip, no synthetic security scores. --}}
        <section class="ds3-card flex flex-col gap-5 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
            <div class="flex min-w-0 items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#101064] text-lg font-extrabold text-white ring-4 ring-[#D4A017]/15">
                    {{ $initials ?: 'D' }}
                </div>
                <div class="min-w-0">
                    <p class="break-words text-base font-bold leading-6 text-[#101064]">{{ $displayName }}</p>
                    <p class="mt-1 break-words text-xs text-slate-500">{{ $roleName }} <span class="px-1.5 text-slate-300">•</span> {{ $departmentName }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3 sm:justify-end">
                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                    <span class="h-2 w-2 rounded-full {{ $isActive ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                    {{ $accountStatus }} Account
                </span>
            </div>
        </section>

        <div class="grid items-start gap-6 lg:grid-cols-[245px_minmax(0,1fr)]">
            {{-- In-page navigation: works without adding any routes. --}}
            <aside class="min-w-0 space-y-4 lg:sticky lg:top-6">
                <nav aria-label="Account settings sections" class="ds3-card p-3" role="tablist">
                    <p class="px-3 pb-3 pt-2 text-[10px] font-extrabold uppercase tracking-[.15em] text-slate-400">Your workspace</p>
                    <div class="grid grid-cols-2 gap-1 sm:grid-cols-3 lg:grid-cols-1">
                        <button type="button" class="ds3-tab" role="tab" id="ds3-tab-account"
                                aria-controls="ds3-panel-account" x-bind:aria-selected="tab === 'account'"
                                x-on:click="setTab('account')">
                            <svg class="ds3-tab-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4 21a8 8 0 0 1 16 0"/></svg>
                            <span>My Account</span>
                        </button>
                        <button type="button" class="ds3-tab" role="tab" id="ds3-tab-security"
                                aria-controls="ds3-panel-security" x-bind:aria-selected="tab === 'security'"
                                x-on:click="setTab('security')">
                            <svg class="ds3-tab-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path stroke-linecap="round" d="M8 10V7a4 4 0 1 1 8 0v3"/></svg>
                            <span>Security</span>
                        </button>
                        <button type="button" class="ds3-tab" role="tab" id="ds3-tab-access"
                                aria-controls="ds3-panel-access" x-bind:aria-selected="tab === 'access'"
                                x-on:click="setTab('access')">
                            <svg class="ds3-tab-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M12 3l8 4v5c0 5-3.4 8.5-8 10-4.6-1.5-8-5-8-10V7l8-4z"/><path stroke-linecap="round" d="M9 12l2 2 4-4"/></svg>
                            <span>Access &amp; Info</span>
                        </button>
                    </div>
                </nav>
                <section class="rounded-[19px] border border-[#101064]/10 bg-[#101064]/[.035] p-5">
                    <div class="flex items-center gap-2 text-[#101064]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M12 3l8 4v5c0 5-3.4 8.5-8 10-4.6-1.5-8-5-8-10V7l8-4z"/></svg>
                        <h3 class="text-sm font-bold">Account protection</h3>
                    </div>
                    <p class="mt-2 text-xs leading-6 text-slate-600">Never share your password or verification codes. Contact an authorized school administrator for official record corrections.</p>
                </section>
            </aside>

            <div class="min-w-0">
                {{-- ACCOUNT: Display-only identity fields, never submitted from Settings. --}}
                <section x-show="tab === 'account'" x-cloak id="ds3-panel-account" role="tabpanel" aria-labelledby="ds3-tab-account" class="ds3-card overflow-hidden">
                    <header class="border-b border-slate-100 px-6 py-7 sm:px-8">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="ds3-eyebrow">01 / Account identity</p>
                                <h2 class="mt-2 text-xl font-bold tracking-tight text-[#101064] sm:text-2xl">Account Information</h2>
                            </div>
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-[#101064]/15 bg-[#101064]/[.05] px-3 py-1.5 text-xs font-bold text-[#101064]">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path stroke-linecap="round" d="M8 10V7a4 4 0 1 1 8 0v3"/></svg>
                                Read-only
                            </span>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">These details are linked to your official DySign account and cannot be modified here.</p>
                    </header>

                    <div class="space-y-6 px-6 py-7 sm:px-8 sm:py-8">
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <p class="ds3-label" id="ds3-name-label">Full name</p>
                                <div class="mt-2 flex min-h-[51px] items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3" aria-labelledby="ds3-name-label">
                                    <span class="min-w-0 break-words text-sm font-semibold text-[#101064]">{{ $user->name ?: 'Not available' }}</span>
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path stroke-linecap="round" d="M8 10V7a4 4 0 1 1 8 0v3"/></svg>
                                </div>
                                <p class="ds3-help">Registered account name</p>
                            </div>
                            <div>
                                <p class="ds3-label" id="ds3-email-label">Email address</p>
                                <div class="mt-2 flex min-h-[51px] items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3" aria-labelledby="ds3-email-label">
                                    <span class="min-w-0 break-all text-sm font-semibold text-[#101064]">{{ $user->email ?: 'Not available' }}</span>
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path stroke-linecap="round" d="M8 10V7a4 4 0 1 1 8 0v3"/></svg>
                                </div>
                                <p class="ds3-help">Registered account email</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-xl border border-[#D4A017]/25 bg-[#D4A017]/[.065] px-4 py-4">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#986c0c]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 11v5M12 8h.01"/></svg>
                            <div>
                                <p class="text-sm font-bold text-[#101064]">Need to correct your information?</p>
                                <p class="mt-1 text-xs leading-6 text-slate-700">Contact authorized school personnel to request an update to your name or registered email.
                                    @if($isStudentAccount)
                                        Your student number and academic records are also maintained by the school.
                                    @else
                                        Your role and departmental assignment are also maintained by authorized staff.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="ds3-rule px-6 py-6 sm:px-8">
                            <p class="text-sm font-semibold text-amber-800">Your email address is not verified.</p>
                            <form action="{{ route('verification.send') }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="text-sm font-bold text-[#101064] underline underline-offset-2">Resend verification email</button>
                            </form>
                            @if(session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm font-semibold text-emerald-700">A new verification link was sent.</p>
                            @endif
                        </div>
                    @endif
                </section>

                {{-- SECURITY: backend password validation stays unchanged; UI has useful guidance. --}}
                <section x-show="tab === 'security'" x-cloak id="ds3-panel-security" role="tabpanel" aria-labelledby="ds3-tab-security" class="ds3-card overflow-hidden">
                    <header class="border-b border-slate-100 px-6 py-7 sm:px-8">
                        <p class="ds3-eyebrow">02 / Sign-in security</p>
                        <h2 class="mt-2 text-xl font-bold tracking-tight text-[#101064] sm:text-2xl">Password &amp; Security</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Change your password and review the security of your current session.</p>
                    </header>

                    @if(session('status') === 'password-updated')
                        <div role="status" class="mx-6 mt-6 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 sm:mx-8">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>
                            Password changed successfully.
                        </div>
                    @endif
                    <div class="mx-6 mt-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs leading-6 text-amber-900 sm:mx-8">
                        Changing your password should require the current password. The server must verify it; the strength meter below is only guidance.
                    </div>
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5 px-6 py-7 sm:px-8 sm:py-8">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="ds3-label" for="ds3-current-password">Current password</label>
                            <div class="relative">
                                <input class="ds3-field pr-16" id="ds3-current-password" name="current_password"
                                       x-bind:type="showCurrent ? 'text' : 'password'" autocomplete="current-password"
                                       x-on:keyup="capsLock = $event.getModifierState('CapsLock')" required>
                                <button type="button" class="absolute bottom-0 right-3 top-2 mt-2 text-xs font-bold text-[#101064] hover:underline"
                                        x-on:click="showCurrent = !showCurrent" x-text="showCurrent ? 'Hide' : 'Show'"
                                        x-bind:aria-label="showCurrent ? 'Hide current password' : 'Show current password'"></button>
                            </div>
                            @foreach($errors->updatePassword->get('current_password') as $message)
                                <p class="ds3-error" role="alert">{{ $message }}</p>
                            @endforeach
                        </div>
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="ds3-label" for="ds3-new-password">New password</label>
                                <div class="relative">
                                    <input class="ds3-field pr-16" id="ds3-new-password" name="password"
                                           x-bind:type="showNew ? 'text' : 'password'" x-model="newPassword"
                                           x-on:keyup="capsLock = $event.getModifierState('CapsLock')"
                                           autocomplete="new-password" required>
                                    <button type="button" class="absolute bottom-0 right-3 top-2 mt-2 text-xs font-bold text-[#101064] hover:underline"
                                            x-on:click="showNew = !showNew" x-text="showNew ? 'Hide' : 'Show'"
                                            x-bind:aria-label="showNew ? 'Hide new password' : 'Show new password'"></button>
                                </div>
                                @foreach($errors->updatePassword->get('password') as $message)
                                    <p class="ds3-error" role="alert">{{ $message }}</p>
                                @endforeach
                            </div>
                            <div>
                                <label class="ds3-label" for="ds3-confirm-password">Confirm new password</label>
                                <div class="relative">
                                    <input class="ds3-field pr-16" id="ds3-confirm-password" name="password_confirmation"
                                           x-bind:type="showConfirm ? 'text' : 'password'" x-model="confirmPassword"
                                           x-on:keyup="capsLock = $event.getModifierState('CapsLock')"
                                           autocomplete="new-password" required>
                                    <button type="button" class="absolute bottom-0 right-3 top-2 mt-2 text-xs font-bold text-[#101064] hover:underline"
                                            x-on:click="showConfirm = !showConfirm" x-text="showConfirm ? 'Hide' : 'Show'"
                                            x-bind:aria-label="showConfirm ? 'Hide confirmed password' : 'Show confirmed password'"></button>
                                </div>
                                <p class="ds3-error" x-show="confirmPassword.length > 0 && newPassword !== confirmPassword" x-cloak role="alert">Passwords do not match.</p>
                            </div>
                        </div>
                        <p class="text-xs font-semibold text-amber-700" x-show="capsLock" x-cloak>Caps Lock is on.</p>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-xs font-bold text-[#101064]">Password strength guide</p>
                                <p class="text-xs font-semibold text-slate-600" x-text="passwordLabel"></p>
                            </div>
                            <div class="mt-3 grid grid-cols-4 gap-1.5" aria-hidden="true">
                                <template x-for="i in 4" :key="i">
                                    <div class="h-1.5 rounded-full" x-bind:class="passwordScore >= i ? (passwordScore >= 3 ? 'bg-emerald-500' : 'bg-amber-500') : 'bg-slate-200'"></div>
                                </template>
                            </div>
                            <p class="ds3-help">For stronger passwords, use 12+ characters with uppercase, lowercase, numbers and symbols. This guide does not replace server-side validation.</p>
                        </div>
                        <div class="ds3-rule flex flex-wrap items-center justify-end gap-3 pt-6">
                            <button type="submit" class="ds3-primary" x-bind:disabled="newPassword.length > 0 && confirmPassword.length > 0 && newPassword !== confirmPassword">
                                Update Password
                            </button>
                        </div>
                    </form>
                    <div class="ds3-rule flex flex-wrap items-center justify-between gap-4 bg-slate-50 px-6 py-6 sm:px-8">
                        <div>
                            <h3 class="text-sm font-bold text-[#101064]">Current session</h3>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Finished using DySign on this device? Sign out to protect your account.</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="ds3-secondary">Sign Out</button>
                        </form>
                    </div>
                </section>

                {{-- ACCESS: official role read-only, usable shortcuts and account identifier. --}}
                <section x-show="tab === 'access'" x-cloak id="ds3-panel-access" role="tabpanel" aria-labelledby="ds3-tab-access" class="ds3-card overflow-hidden">
                    <header class="border-b border-slate-100 px-6 py-7 sm:px-8">
                        <p class="ds3-eyebrow">03 / Account context</p>
                        <h2 class="mt-2 text-xl font-bold tracking-tight text-[#101064] sm:text-2xl">Access &amp; System Information</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Review your assigned access and useful account details. These details are read-only.</p>
                    </header>
                    <div class="grid gap-x-8 gap-y-7 px-6 py-8 sm:grid-cols-2 sm:px-8">
                        <div><p class="ds3-data-label">Role</p><p class="ds3-data-value">{{ $roleName }}</p></div>
                        @if($isStudentAccount)
                            <div><p class="ds3-data-label">Official Student Number</p><p class="ds3-data-value">{{ $studentRecord?->student_number ?: 'Not available' }}</p></div>
                            <div><p class="ds3-data-label">College</p><p class="ds3-data-value">{{ $studentRecord?->college ?: 'Not available' }}</p></div>
                            <div>
                                <p class="ds3-data-label">Program</p>
                                <p class="ds3-data-value">{{ $studentRecord?->program_name ?: 'Not available' }}</p>
                                @if($studentRecord?->program_code)
                                    <p class="ds3-help">Program code: {{ $studentRecord->program_code }}</p>
                                @endif
                            </div>
                            <div><p class="ds3-data-label">Year Level</p><p class="ds3-data-value">{{ $studentRecord?->year_level ? $studentRecord->year_level . ($studentRecord->year_level == 1 ? 'st' : ($studentRecord->year_level == 2 ? 'nd' : ($studentRecord->year_level == 3 ? 'rd' : 'th'))) . ' Year' : 'Not available' }}</p></div>
                        @else
                            <div><p class="ds3-data-label">Assigned Department</p><p class="ds3-data-value">{{ $departmentName }}</p></div>
                        @endif
                        <div><p class="ds3-data-label">Account status</p><p class="ds3-data-value">{{ $accountStatus }}</p></div>
                        <div><p class="ds3-data-label">Date created</p><p class="ds3-data-value">{{ $user->created_at?->format('F j, Y') ?? 'Not available' }}</p></div>
                        <div>
                            <p class="ds3-data-label">Account ID</p>
                            <div class="mt-1.5 flex flex-wrap items-center gap-3">
                                <code class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-bold text-[#101064]">{{ $user->user_id }}</code>
                                <button type="button" class="text-xs font-bold text-[#101064] hover:underline"
                                        x-on:click="navigator.clipboard.writeText('{{ (int) $user->user_id }}').then(() => { copiedId = true; }).catch(() => { copiedId = false; })"
                                        x-text="copiedId ? 'Copied!' : 'Copy ID'">Copy ID</button>
                            </div>
                            <p class="ds3-help">Internal DySign account ID, not your official school ID number.</p>
                        </div>
                        <div><p class="ds3-data-label">Account information updated</p><p class="ds3-data-value">{{ $user->updated_at?->format('F j, Y · g:i A') ?? 'Not available' }}</p></div>
                    </div>
                    <div class="ds3-rule px-6 py-7 sm:px-8">
                        <h3 class="text-sm font-bold text-[#101064]">Quick actions</h3>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('my-profile.show') }}" class="ds3-secondary">View official profile</a>
                            <a href="{{ route('dashboard') }}" class="ds3-secondary">Go to dashboard</a>
                        </div>
                    </div>
                    <div class="ds3-rule bg-slate-50 px-6 py-6 sm:px-8">
                        <p class="text-xs leading-6 text-slate-600">For changes to official student information, departmental assignments, or role permissions, contact your authorized school administrator.</p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-admin-layout>
