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

        // Same read-only imported photo source used by the shared Student navbar.
        $profilePhotoUrl = null;
        if ($roleName === 'Student Account') {
            $profilePhotoPath = data_get($student, 'photo_path');
            $profilePhotoFile = is_string($profilePhotoPath)
                ? basename(str_replace('\\', '/', $profilePhotoPath))
                : '';
            if (
                $profilePhotoFile !== ''
                && preg_match('/\A[A-Za-z0-9_-]+\.(?:jpe?g|png|webp)\z/i', $profilePhotoFile)
                && is_file(public_path('student_photos/' . $profilePhotoFile))
            ) {
                $profilePhotoUrl = asset('student_photos/' . $profilePhotoFile);
            }
        }

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
        /* Fixed sizing independent of Tailwind's compiled arbitrary utilities. */
        .dysign-student-profile-photo {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 116px;
            width: 116px;
            height: 136px;
            min-width: 116px;
            overflow: hidden;
            border: 3px solid #fff;
            border-radius: 18px;
            background: #101064;
            box-shadow: 0 0 0 2px rgba(212,160,23,.56), 0 10px 24px rgba(16,16,100,.14);
        }
        .dysign-student-profile-photo > .dysign-student-photo-initials {
            display: flex;
            position: absolute;
            inset: 0;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 30px;
            font-weight: 800;
        }
        .dysign-student-profile-photo > img {
            display: block;
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            max-width: none;
            object-fit: cover;
            object-position: center 22%;
            border-radius: 15px;
        }
        @media (max-width: 640px) {
            .dysign-student-profile-photo {
                flex-basis: 108px;
                min-width: 108px;
                width: 108px;
                height: 126px;
            }
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

        @if ($roleName === 'Student Account')
            {{-- Imported student photo; student official details are read-only. --}}
            <section class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
                <div class="relative overflow-hidden border-b border-slate-100 bg-[#F8FAFE] px-6 py-7 sm:px-8">
                    <div class="absolute right-0 top-0 h-full w-[35%] opacity-[.035]" style="background-image: url('{{ asset('images/school.jpg') }}'); background-position:center; background-size:cover" aria-hidden="true"></div>
                    <div class="relative flex flex-col items-start gap-5 sm:flex-row sm:items-center sm:gap-7">
                        <div class="dysign-student-profile-photo" aria-label="Official student profile photo">
                            <div class="dysign-student-photo-initials">{{ $initials ?: 'D' }}</div>
                            @if ($profilePhotoUrl)
                                <img src="{{ $profilePhotoUrl }}" alt="{{ $displayName }} official student photo"
                                    loading="eager" decoding="async" onerror="this.style.display='none'">
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#AD7F08]">Official student profile</p>
                            <h2 class="mt-1 break-words text-2xl font-extrabold tracking-tight text-[#101064] sm:text-[28px]">{{ $displayName }}</h2>
                            @if ($studentNumber)
                                <p class="mt-1 text-sm font-semibold text-slate-600">Student No. {{ $studentNumber }}</p>
                            @endif
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <span class="inline-flex rounded-full bg-[#101064]/[0.08] px-3 py-1 text-xs font-bold text-[#101064]">Student Account</span>
                                @if ($accountStatus)
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ strtolower($accountStatus) === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">{{ $accountStatus }}</span>
                                @endif
                                <span class="inline-flex rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-800">Read-only profile</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid lg:grid-cols-2">
                    <div class="border-b border-slate-100 px-6 py-7 sm:px-8 lg:border-b-0 lg:border-r">
                        <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="text-lg font-bold text-[#101064]">Student Information</h3>
                                <p class="mt-1 text-xs text-slate-500">Details from your official student record</p>
                            </div>
                            <span class="h-2 w-2 rounded-full bg-[#D4A017]" aria-hidden="true"></span>
                        </div>
                        <dl class="grid gap-x-6 gap-y-6 sm:grid-cols-2">
                            <div><dt class="dysign-profile-info-label">Student Number</dt><dd class="dysign-profile-info-value">{{ $studentNumber ?: 'Not available' }}</dd></div>
                            <div><dt class="dysign-profile-info-label">Year Level</dt><dd class="dysign-profile-info-value">{{ $yearLevel ? (is_numeric($yearLevel) ? 'Year '.$yearLevel : $yearLevel) : 'Not available' }}</dd></div>
                            <div class="sm:col-span-2"><dt class="dysign-profile-info-label">Program</dt><dd class="dysign-profile-info-value">{{ is_string($programName) && trim($programName) !== '' ? $programName : 'Not available' }}</dd></div>
                            @if (data_get($student, 'college'))
                                <div class="sm:col-span-2"><dt class="dysign-profile-info-label">College</dt><dd class="dysign-profile-info-value">{{ data_get($student, 'college') }}</dd></div>
                            @endif
                            @if ($enrollmentType)
                                <div><dt class="dysign-profile-info-label">Enrollment Type</dt><dd class="dysign-profile-info-value">{{ $enrollmentType }}</dd></div>
                            @endif
                        </dl>
                    </div>
                    <div class="px-6 py-7 sm:px-8">
                        <div class="mb-6 border-b border-slate-100 pb-4">
                            <h3 class="text-lg font-bold text-[#101064]">Account Information</h3>
                            <p class="mt-1 text-xs text-slate-500">Your DySign account and access details</p>
                        </div>
                        <dl class="grid gap-x-6 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-2"><dt class="dysign-profile-info-label">Email Address</dt><dd class="dysign-profile-info-value">{{ $user->email ?: 'Not provided' }}</dd></div>
                            <div><dt class="dysign-profile-info-label">Account Role</dt><dd class="dysign-profile-info-value">{{ $roleName }}</dd></div>
                            <div><dt class="dysign-profile-info-label">Account Status</dt><dd class="dysign-profile-info-value">{{ $accountStatus ?: 'Not specified' }}</dd></div>
                            <div><dt class="dysign-profile-info-label">Account Created</dt><dd class="dysign-profile-info-value">{{ $user->created_at?->format('F d, Y') ?? 'Not available' }}</dd></div>
                        </dl>
                    </div>
                </div>
            </section>
        @else
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

        @if ($roleName === 'Attendance Personnel' && $personnelPosition)
            <section class="rounded-[24px] border border-slate-200 bg-white px-7 py-7 shadow-sm sm:px-8">
                <h2 class="text-xl font-bold text-[#101064]">Personnel Information</h2>
                <div class="mt-6"><p class="dysign-profile-info-label">Position / Designation</p><p class="dysign-profile-info-value">{{ $personnelPosition }}</p></div>
            </section>
        @endif

        @endif

        <p class="px-1 text-xs leading-6 text-slate-500">
            This page is read-only. For corrections to your official student, personnel, or department record, contact your authorized school administrator.
        </p>
    </div>
</x-admin-layout>
