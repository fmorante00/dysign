<x-admin-layout>

    <div class="space-y-6">

        {{-- Page Header --}}
        <section class="relative overflow-hidden rounded-3xl bg-[#101064] px-7 py-9 text-white shadow-sm sm:px-9">

            <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full bg-[#D4A017]/15 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>

            <div class="relative">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#E7C75B]">
                    Department Staff • Student Monitoring
                </p>

                <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    Participation Progress
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">
                    Track student attendance participation, completed eligible
                    events, and activity points within your department.
                </p>
            </div>

        </section>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-500">
                        Students with Eligible Events
                    </p>

                    <div class="rounded-xl bg-indigo-50 p-3 text-[#101064]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m20 0v-2a4 4 0 00-3-3.87M9 11a4 4 0 100-8 4 4 0 000 8zm8-7.87a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-3 text-4xl font-bold text-[#101064]">
                    {{ number_format($totalStudents) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Active students with completed eligible events,
                    matching the current search.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-500">
                        Completed Department Events
                    </p>

                    <div class="rounded-xl bg-amber-50 p-3 text-[#D4A017]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-3 text-4xl font-bold text-[#101064]">
                    {{ number_format($totalCompletedEvents) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    All completed events belonging to your department.
                </p>
            </div>

        </div>

        {{-- Student Progress Table --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">

                <div>
                    <h2 class="text-lg font-bold text-[#101064]">
                        Student Participation Records
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Based on completed eligible events and recorded attendance.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('staff.participation-progress.index') }}"
                      class="flex flex-wrap gap-2">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        maxlength="100"
                        placeholder="Search student..."
                        aria-label="Search student"
                        class="min-w-48 rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]"
                    >

                    <button type="submit"
                            class="rounded-xl bg-[#101064] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#202080]">
                        Search
                    </button>

                    @if ($search !== '')
                        <a href="{{ route('staff.participation-progress.index') }}"
                           class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Reset
                        </a>
                    @endif

                </form>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full text-left text-sm">

                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Student</th>
                            <th class="px-5 py-4">Program / Year</th>
                            <th class="px-5 py-4">Eligible Events</th>
                            <th class="px-5 py-4">Attended Events</th>
                            <th class="px-5 py-4">Progress</th>
                            <th class="px-5 py-4">Activity Points</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($students as $student)

                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-[#101064]">
                                        {{ $student->last_name }},
                                        {{ $student->first_name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $student->student_number }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-700">
                                        {{ $student->program_code ?? 'N/A' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Year {{ $student->year_level ?? 'N/A' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 font-semibold text-slate-700">
                                    {{ number_format($student->eligible_count) }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-slate-700">
                                    {{ number_format($student->attended_count) }}
                                </td>

                                <td class="min-w-44 px-5 py-4">

                                    @php
                                        $percentage = min(
                                            100,
                                            max(0, (float) $student->progress_percentage)
                                        );
                                    @endphp

                                    <div class="mb-2 flex items-center justify-between">
                                        <span class="font-bold text-[#101064]">
                                            {{ number_format($student->progress_percentage, 1) }}%
                                        </span>

                                        @if ($percentage >= 80)
                                            <span class="text-xs font-semibold text-green-700">
                                                High
                                            </span>
                                        @elseif ($percentage >= 50)
                                            <span class="text-xs font-semibold text-amber-700">
                                                Moderate
                                            </span>
                                        @else
                                            <span class="text-xs font-semibold text-red-700">
                                                Low
                                            </span>
                                        @endif
                                    </div>

                                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">

                                        <div
                                            class="h-full rounded-full transition-all
                                            {{ $percentage >= 80
                                                ? 'bg-green-500'
                                                : ($percentage >= 50
                                                    ? 'bg-[#D4A017]'
                                                    : 'bg-red-500') }}"
                                            style="width: {{ $percentage }}%"
                                        ></div>

                                    </div>

                                </td>

                                <td class="px-5 py-4">

                                    <span class="inline-flex rounded-xl bg-amber-50 px-3 py-1.5 font-bold text-[#101064]">
                                        {{ number_format($student->total_points) }} pts
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center">

                                    <p class="font-semibold text-slate-700">
                                        No student participation records found.
                                    </p>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Try another search or check completed
                                        eligible events for your department.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($students->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $students->links() }}
                </div>
            @endif

        </section>

        <p class="text-xs leading-5 text-slate-500">
            Participation progress is calculated as attended completed eligible events
            divided by total completed eligible events. Activity points are summed
            from department events. Progress labels are descriptive indicators,
            not official school classifications.
        </p>

    </div>

</x-admin-layout>
