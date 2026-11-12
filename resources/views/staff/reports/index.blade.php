<x-admin-layout>

    <div class="space-y-6">

        {{-- Page Header --}}
        <section class="relative overflow-hidden rounded-3xl bg-[#101064] px-7 py-8 text-white shadow-sm sm:px-9">

            <div class="absolute right-0 top-0 h-40 w-40 translate-x-12 -translate-y-12 rounded-full bg-[#D4A017]/20 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>

            <div class="relative">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#E7C75B]">
                    Module #19 • Department Staff
                </p>

                <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    Attendance &amp; Participation Reports
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">
                    Monitor event attendance, student participation,
                    and activity points within your department.
                </p>
            </div>

        </section>

        {{-- Report Filters --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="mb-5">
                <h2 class="text-lg font-bold text-[#101064]">
                    Report Filters
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Select an event and attendance status to filter the reports.
                </p>
            </div>

            <form
                method="GET"
                action="{{ route('staff.reports.index') }}"
                class="grid grid-cols-1 items-end gap-4 md:grid-cols-3"
            >

                <div>
                    <label for="event_id" class="mb-2 block text-sm font-semibold text-slate-700">
                        Event
                    </label>

                    <select
                        id="event_id"
                        name="event_id"
                        class="w-full rounded-xl border-slate-300 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]"
                    >
                        <option value="">All Department Events</option>

                        @foreach ($events as $event)
                            <option
                                value="{{ $event->event_id }}"
                                @selected((string) $selectedEvent === (string) $event->event_id)
                            >
                                {{ $event->event_name }}
                                ({{ $event->event_date }})
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">
                        Attendance Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-xl border-slate-300 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]"
                    >
                        <option value="all" @selected($selectedStatus === 'all')>
                            All Statuses
                        </option>
                        <option value="Present" @selected($selectedStatus === 'Present')>
                            Present
                        </option>
                        <option value="Late" @selected($selectedStatus === 'Late')>
                            Late
                        </option>
                    </select>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button
                        type="submit"
                        class="rounded-xl bg-[#101064] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#202080]"
                    >
                        Apply Filters
                    </button>

                    <a
                        href="{{ route('staff.reports.index') }}"
                        class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                    >
                        Reset
                    </a>
                </div>

            </form>

            <p class="mt-4 text-xs text-slate-500">
                Attendance status filters affect attendance results only.
                Participation results include Present and Late attendance
                for completed eligible events.
            </p>

        </section>

        {{-- Attendance Summary --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Total Attendance
                </p>

                <p class="mt-3 text-3xl font-bold text-[#101064]">
                    {{ number_format($totalAttendance) }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Records matching current filters
                </p>
            </div>

            <div class="rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-green-700">
                    Present
                </p>

                <p class="mt-3 text-3xl font-bold text-green-700">
                    {{ number_format($totalPresent) }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    On-time attendance records
                </p>
            </div>

            <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-amber-700">
                    Late
                </p>

                <p class="mt-3 text-3xl font-bold text-amber-700">
                    {{ number_format($totalLate) }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Late attendance records
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    On-Time Rate
                </p>

                <p class="mt-3 text-3xl font-bold text-[#101064]">
                    {{ number_format($onTimeRate, 1) }}%
                </p>

                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div
                        class="h-full rounded-full bg-[#D4A017]"
                        style="width: {{ min(100, max(0, $onTimeRate)) }}%"
                    ></div>
                </div>
            </div>

        </div>

        {{-- Attendance Records --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-bold text-[#101064]">
                    Attendance Report
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Student attendance records for your department's events.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">

                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Student</th>
                            <th class="px-5 py-4">Event</th>
                            <th class="px-5 py-4">Event Date</th>
                            <th class="px-5 py-4">Time In</th>
                            <th class="px-5 py-4">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($attendanceRecords as $record)

                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-[#101064]">
                                        {{ $record->last_name }},
                                        {{ $record->first_name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $record->student_number }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-slate-700">
                                    {{ $record->event_name }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $record->event_date }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $record->time_in ?? 'N/A' }}
                                </td>

                                <td class="px-5 py-4">

                                    @if ($record->status === 'Present')
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Present
                                        </span>
                                    @elseif ($record->status === 'Late')
                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Late
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            {{ $record->status }}
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    No attendance records found for the selected filters.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            @if ($attendanceRecords->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $attendanceRecords->links() }}
                </div>
            @endif

        </section>

        {{-- Participation Records --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-bold text-[#101064]">
                    Student Participation Report
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Participation is calculated from completed events
                    for which each student was eligible.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">

                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Student</th>
                            <th class="px-5 py-4">Program</th>
                            <th class="px-5 py-4">Eligible Events</th>
                            <th class="px-5 py-4">Attended</th>
                            <th class="px-5 py-4">Participation Rate</th>
                            <th class="px-5 py-4">Activity Points</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($participationRecords as $student)

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

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $student->program_code ?? 'N/A' }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-slate-700">
                                    {{ $student->eligible_count }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-slate-700">
                                    {{ $student->attended_count }}
                                </td>

                                <td class="min-w-40 px-5 py-4">

                                    <p class="mb-2 font-bold text-[#101064]">
                                        {{ number_format($student->participation_rate, 1) }}%
                                    </p>

                                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                        <div
                                            class="h-full rounded-full bg-[#D4A017]"
                                            style="width: {{ min(100, max(0, $student->participation_rate)) }}%"
                                        ></div>
                                    </div>

                                </td>

                                <td class="px-5 py-4 font-bold text-[#101064]">
                                    {{ number_format($student->total_points) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    No eligible student participation records found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            @if ($participationRecords->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $participationRecords->links() }}
                </div>
            @endif

        </section>

        <p class="text-xs text-slate-500">
            Reports display existing attendance and participation data.
            No records are modified when filters are applied.
        </p>

    </div>

</x-admin-layout>
