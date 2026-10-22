<x-admin-layout>
    @php
        $fullName = trim(collect([
            $student->first_name,
            $student->middle_name,
            $student->last_name,
        ])->filter()->implode(' '));

        $initials = strtoupper(
            substr((string) $student->first_name, 0, 1)
            . substr((string) $student->last_name, 0, 1)
        );
    @endphp

    <div class="min-w-0 space-y-7 pb-8">

        {{-- Welcome / Identity Banner --}}
        <section class="relative overflow-hidden rounded-[28px] bg-[#101064] px-6 py-8 text-white shadow-sm sm:px-9 sm:py-10">
            <div class="pointer-events-none absolute -right-16 -top-24 h-80 w-80 rounded-full border border-white/10"></div>
            <div class="pointer-events-none absolute -right-4 -top-12 h-60 w-60 rounded-full border border-white/10"></div>
            <div class="pointer-events-none absolute bottom-0 right-0 h-28 w-80 bg-gradient-to-l from-[#D4A017]/20 to-transparent"></div>

            <div class="relative flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0 max-w-2xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-[#E7C75B]">
                        DySign · Student Portal
                    </p>
                    <h1 class="mt-3 break-words text-3xl font-bold tracking-tight sm:text-4xl">
                        Welcome, {{ $student->first_name }}!
                    </h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-white/75">
                        Your personal overview of your student information and recorded event attendance.
                    </p>
                    <div class="mt-6 inline-flex max-w-full items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-medium text-white/90">
                        <svg class="h-4 w-4 shrink-0 text-[#E7C75B]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5zm-6-2v5c3 2 9 2 12 0v-5" />
                        </svg>
                        <span class="truncate">Student No. {{ $student->student_number }}</span>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-4 rounded-2xl border border-white/15 bg-white/10 px-5 py-4 backdrop-blur-sm">
                    <div class="relative flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-white/25 bg-white/15 text-lg font-bold text-white">
                        <span>{{ $initials ?: 'ST' }}</span>
                        @if($student->photo_path)
                            <img
                                src="{{ asset($student->photo_path) }}"
                                alt="Student profile photo"
                                class="absolute inset-0 h-full w-full object-cover"
                                onerror="this.style.display='none'"
                            >
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="max-w-[240px] truncate text-sm font-semibold">{{ $fullName }}</p>
                        <p class="mt-1 text-xs text-white/65">{{ $student->program_code ?: 'Program not specified' }}</p>
                        <span class="mt-2 inline-flex items-center gap-1.5 text-xs text-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                            Active student record
                        </span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Summary Cards --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3" aria-label="Student account overview">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Attendance Records</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-[#101064]">{{ number_format($totalAttendanceRecords) }}</p>
                    </div>
                    <div class="rounded-xl bg-indigo-50 p-3 text-[#101064]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 7V3m8 4V3M5 11h14M5 7h14v14H5zM9 16l2 2 4-4" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-500">Total attendance entries linked to your record</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Year Level</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-[#101064]">
                            {{ $student->year_level ? 'Year ' . $student->year_level : '—' }}
                        </p>
                    </div>
                    <div class="rounded-xl bg-amber-50 p-3 text-[#A9780B]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 14l9-5-9-5-9 5 9 5zm-6-2v5c3 2 9 2 12 0v-5" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-500">As recorded in your student profile</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">RFID Card</p>
                        <p class="mt-3 text-2xl font-bold tracking-tight {{ $student->rfid_identifier ? 'text-emerald-700' : 'text-amber-700' }}">
                            {{ $student->rfid_identifier ? 'Registered' : 'Not registered' }}
                        </p>
                    </div>
                    <div class="rounded-xl bg-slate-100 p-3 text-slate-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.7" />
                            <path stroke-linecap="round" stroke-width="1.7" d="M7 10h6m-6 4h3m7-4c1.4 1 1.4 3 0 4m-2-5c2.6 1.5 2.6 4.5 0 6" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-500">RFID enrollment status for event check-in</p>
            </div>
        </section>

        <div class="grid min-w-0 gap-6 xl:grid-cols-3">
            {{-- Personal Info --}}
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-1">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-base font-bold text-slate-900">My Student Information</h2>
                    <p class="mt-1 text-xs text-slate-500">Read-only information from your student record</p>
                </div>

                <dl class="space-y-5 px-6 py-6">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Full Name</dt>
                        <dd class="mt-1 break-words text-sm font-medium text-slate-800">{{ $fullName }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Student Number</dt>
                        <dd class="mt-1 break-words text-sm font-medium text-slate-800">{{ $student->student_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">College</dt>
                        <dd class="mt-1 break-words text-sm font-medium text-slate-800">{{ $student->college ?: 'Not specified' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Program</dt>
                        <dd class="mt-1 break-words text-sm font-medium text-slate-800">{{ $student->program_name ?: ($student->program_code ?: 'Not specified') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Enrollment Type</dt>
                        <dd class="mt-1 break-words text-sm font-medium text-slate-800">{{ $student->student_status ?: 'Not specified' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</dt>
                        <dd class="mt-1 break-all text-sm font-medium text-slate-800">{{ $student->email ?: 'Not specified' }}</dd>
                    </div>
                </dl>
            </section>

            {{-- Recent Attendance --}}
            <section class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Recent Event Attendance</h2>
                        <p class="mt-1 text-xs text-slate-500">Your five most recent attendance records</p>
                    </div>
                    <span class="rounded-full bg-[#101064]/5 px-3 py-1 text-xs font-semibold text-[#101064]">
                        {{ $recentAttendance->count() }} shown
                    </span>
                </div>

                @if($recentAttendance->isEmpty())
                    <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                        <div class="rounded-2xl bg-slate-100 p-4 text-slate-500">
                            <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3M5 11h14M5 7h14v14H5zM9 16h6" />
                            </svg>
                        </div>
                        <h3 class="mt-5 font-semibold text-slate-800">No attendance records yet</h3>
                        <p class="mt-2 max-w-sm text-sm leading-6 text-slate-500">
                            Your event attendance history will appear here once attendance has been recorded through DySign.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th scope="col" class="whitespace-nowrap px-6 py-4 font-semibold">Event</th>
                                    <th scope="col" class="whitespace-nowrap px-4 py-4 font-semibold">Event Date</th>
                                    <th scope="col" class="whitespace-nowrap px-4 py-4 font-semibold">Time In</th>
                                    <th scope="col" class="whitespace-nowrap px-6 py-4 font-semibold">Time Out</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentAttendance as $record)
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="max-w-[240px] px-6 py-4 align-top">
                                            <p class="font-semibold text-slate-800">
                                                {{ $record->event?->event_name ?: 'Event record unavailable' }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 align-top text-slate-600">
                                            {{ $record->event?->event_date ? \Illuminate\Support\Carbon::parse($record->event->event_date)->format('M d, Y') : '—' }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 align-top text-slate-600">
                                            {{ $record->time_in ? \Illuminate\Support\Carbon::parse($record->time_in)->format('h:i A') : '—' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 align-top text-slate-600">
                                            @if($record->time_out)
                                                {{ \Illuminate\Support\Carbon::parse($record->time_out)->format('h:i A') }}
                                            @else
                                                <span class="text-slate-400">Not recorded</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                        <p class="text-xs leading-5 text-slate-500">
                            These entries show recorded RFID attendance activity. A time-in entry alone does not necessarily indicate completed participation.
                        </p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-admin-layout>
