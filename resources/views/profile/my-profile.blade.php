<x-admin-layout>
    @php
        $displayName = $user->name ?: 'DySign User';
        $initials = collect(preg_split('/\s+/', trim($displayName)))
            ->filter()
            ->take(2)
            ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');

        $departmentName = $user->department?->department_name;
        $accountStatus = $user->status ?? null;

        // Student information is shown only when an existing student record was found.
        $studentNumber = data_get($student, 'student_number')
            ?? data_get($student, 'student_no')
            ?? data_get($student, 'student_id_number')
            ?? data_get($user, 'student_number');
        $yearLevel = data_get($student, 'year_level') ?? data_get($student, 'year');
        $programName = data_get($student, 'program.program_name')
            ?? data_get($student, 'program_name')
            ?? data_get($student, 'course_name');
        $enrollmentType = data_get($student, 'enrollment_type');
        $personnelPosition = data_get($personnel, 'position')
            ?? data_get($personnel, 'designation');
    @endphp

    <style>
        .dysign-profile-hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background-image:
                linear-gradient(105deg, rgba(16,16,100,.97) 0%, rgba(16,16,100,.90) 56%, rgba(16,16,100,.70) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-position: center;
            background-size: cover;
        }
        .dysign-profile-rings {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .dysign-profile-rings span {
            display: block;
            position: absolute;
            border: 2px solid rgba(255,255,255,.27);
            border-radius: 50%;
            box-sizing: border-box;
        }
        .dysign-profile-rings span:nth-child(1) {
            width: 420px; height: 420px; right: -55px; top: -177px;
            border-color: rgba(255,255,255,.43);
        }
        .dysign-profile-rings span:nth-child(2) {
            width: 310px; height: 310px; right: 0; top: -123px;
        }
        .dysign-profile-rings span:nth-child(3) {
            width: 205px; height: 205px; right: 52px; top: -71px;
            border-color: rgba(212,160,23,.55);
        }
        .dysign-profile-info-label {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .10em;
        }
        .dysign-profile-info-value {
            color: #101064;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }
    </style>

    <div class="min-w-0 space-y-6">
        {{-- HERO: consistent with DySign Event Management pages --}}
        <section class="dysign-profile-hero rounded-[28px] text-white shadow-sm">
            <div class="dysign-profile-rings" aria-hidden="true">
                <span></span><span></span><span></span>
            </div>
            <div class="absolute bottom-0 left-0 right-0 z-10 h-1 bg-[#D4A017]"></div>
            <div class="relative z-10 flex flex-col gap-5 px-7 py-9 sm:px-9 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div>
                    <div class="mb-4 flex items-center gap-3">
                        <span class="h-[2px] w-8 bg-[#D4A017]"></span>
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-[#E7C75B]">DySign / Account Profile</p>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">My Profile</h1>
                    <p class="mt-2 max-w-xl text-sm leading-6 text-white/80">View your official account information and assigned access in the DySign system.</p>
                </div>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex shrink-0 items-center justify-center gap-2 self-start rounded-xl border border-white/25 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
                    <span aria-hidden="true">←</span> Back to Dashboard
                </a>
            </div>
        </section>

        {{-- User identity --}}
        <section class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-5 border-b border-slate-100 px-7 py-7 sm:flex-row sm:items-center sm:px-8">
                <div class="flex h-[78px] w-[78px] shrink-0 items-center justify-center rounded-[22px] bg-[#101064] text-2xl font-bold text-white ring-4 ring-[#D4A017]/20">
                    {{ $initials ?: 'D' }}
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="break-words text-2xl font-bold text-[#101064]">{{ $displayName }}</h2>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-[#101064]/[0.08] px-3 py-1 text-xs font-bold text-[#101064]">
                            {{ $roleName }}
                        </span>
                        @if ($accountStatus)
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ strtolower($accountStatus) === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ $accountStatus }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid gap-x-8 gap-y-8 px-7 py-8 sm:grid-cols-2 sm:px-8 lg:grid-cols-3">
                <div>
                    <p class="dysign-profile-info-label">Full Name</p>
                    <p class="dysign-profile-info-value">{{ $displayName }}</p>
                </div>
                <div>
                    <p class="dysign-profile-info-label">Email Address</p>
                    <p class="dysign-profile-info-value">{{ $user->email ?: 'Not provided' }}</p>
                </div>
                <div>
                    <p class="dysign-profile-info-label">Account Role</p>
                    <p class="dysign-profile-info-value">{{ $roleName }}</p>
                </div>
                <div>
                    <p class="dysign-profile-info-label">Department / College</p>
                    <p class="dysign-profile-info-value">{{ $departmentName ?: 'School-wide / Not assigned' }}</p>
                </div>
                <div>
                    <p class="dysign-profile-info-label">Account Status</p>
                    <p class="dysign-profile-info-value">{{ $accountStatus ?: 'Not specified' }}</p>
                </div>
                <div>
                    <p class="dysign-profile-info-label">Account Created</p>
                    <p class="dysign-profile-info-value">{{ $user->created_at?->format('F d, Y') ?? 'Not available' }}</p>
                </div>
            </div>
        </section>

        @if ($roleName === 'Student Account' && ($studentNumber || $yearLevel || is_string($programName) || $enrollmentType))
            <section class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-7 py-6 sm:px-8">
                    <h2 class="text-xl font-bold text-[#101064]">Student Information</h2>
                    <p class="mt-1 text-xs text-slate-500">Details from your linked student record</p>
                </div>
                <div class="grid gap-x-8 gap-y-8 px-7 py-7 sm:grid-cols-2 sm:px-8 lg:grid-cols-3">
                    @if ($studentNumber)
                        <div><p class="dysign-profile-info-label">Student Number</p><p class="dysign-profile-info-value">{{ $studentNumber }}</p></div>
                    @endif
                    @if ($yearLevel)
                        <div><p class="dysign-profile-info-label">Year Level</p><p class="dysign-profile-info-value">{{ is_numeric($yearLevel) ? 'Year '.$yearLevel : $yearLevel }}</p></div>
                    @endif
                    @if (is_string($programName) && $programName !== '')
                        <div><p class="dysign-profile-info-label">Program</p><p class="dysign-profile-info-value">{{ $programName }}</p></div>
                    @endif
                    @if ($enrollmentType)
                        <div><p class="dysign-profile-info-label">Enrollment Type</p><p class="dysign-profile-info-value">{{ $enrollmentType }}</p></div>
                    @endif
                </div>
            </section>
        @elseif ($roleName === 'Attendance Personnel' && $personnelPosition)
            <section class="rounded-[24px] border border-slate-200 bg-white px-7 py-7 shadow-sm sm:px-8">
                <h2 class="text-xl font-bold text-[#101064]">Personnel Information</h2>
                <div class="mt-6"><p class="dysign-profile-info-label">Position / Designation</p><p class="dysign-profile-info-value">{{ $personnelPosition }}</p></div>
            </section>
        @endif

        <p class="px-1 text-xs leading-6 text-slate-500">
            This page is read-only. For corrections to your official student, personnel, or department record, contact your authorized school administrator.
        </p>
    </div>
</x-admin-layout>
