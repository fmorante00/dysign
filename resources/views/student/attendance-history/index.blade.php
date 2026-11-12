<x-admin-layout>

    <div class="space-y-6">

        {{-- Page Header --}}
        <section class="relative overflow-hidden rounded-3xl bg-[#101064] px-7 py-9 text-white shadow-sm sm:px-9">

            <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full bg-[#D4A017]/20 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>

            <div class="relative">

                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#E7C75B]">
                    Student Account • Module #9
                </p>

                <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    My Attendance History
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">
                    View your recorded event attendance, check your
                    attendance status, and monitor your punctuality.
                </p>

            </div>

        </section>

        {{-- Attendance Summary --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Attendance --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm font-semibold text-slate-500">
                    Total Attendance
                </p>

                <p class="mt-3 text-4xl font-bold text-[#101064]">
                    {{ number_format($totalAttendance) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    All your recorded attendance entries
                </p>

            </div>

            {{-- Present --}}
            <div class="rounded-2xl border border-green-100 bg-white p-6 shadow-sm">

                <p class="text-sm font-semibold text-green-700">
                    Present
                </p>

                <p class="mt-3 text-4xl font-bold text-green-700">
                    {{ number_format($totalPresent) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Recorded on-time attendance
                </p>

            </div>

            {{-- Late --}}
            <div class="rounded-2xl border border-amber-100 bg-white p-6 shadow-sm">

                <p class="text-sm font-semibold text-amber-700">
                    Late
                </p>

                <p class="mt-3 text-4xl font-bold text-amber-700">
                    {{ number_format($totalLate) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Recorded late attendance
                </p>

            </div>

            {{-- On-Time Rate --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm font-semibold text-slate-500">
                    On-Time Rate
                </p>

                <p class="mt-3 text-4xl font-bold text-[#101064]">
                    {{ number_format($onTimeRate, 1) }}%
                </p>

                <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-100">

                    <div
                        class="h-full rounded-full bg-[#D4A017]"
                        style="width: {{ min(100, max(0, $onTimeRate)) }}%"
                    ></div>

                </div>

            </div>

        </div>

        {{-- Attendance Records --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Table Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">

                <div>

                    <h2 class="text-lg font-bold text-[#101064]">
                        My Attendance Records
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Your attendance history across recorded events.
                    </p>

                </div>

                {{-- Attendance Filter --}}
                <form
                    method="GET"
                    action="{{ route('student.attendance-history.index') }}"
                    class="flex flex-wrap items-end gap-2"
                >

                    <div>

                        <label
                            for="status"
                            class="mb-1.5 block text-xs font-semibold text-slate-600"
                        >
                            Attendance Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]"
                        >

                            <option value="all" @selected($selectedStatus === 'all')>
                                All Attendance
                            </option>

                            <option value="Present" @selected($selectedStatus === 'Present')>
                                Present
                            </option>

                            <option value="Late" @selected($selectedStatus === 'Late')>
                                Late
                            </option>

                        </select>

                    </div>

                    <button
                        type="submit"
                        class="rounded-xl bg-[#101064] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#202080]"
                    >
                        Apply Filter
                    </button>

                    @if ($selectedStatus !== 'all')

                        <a
                            href="{{ route('student.attendance-history.index') }}"
                            class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50"
                        >
                            Reset
                        </a>

                    @endif

                </form>

            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">

                <table class="min-w-full text-left text-sm">

                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">

                        <tr>
                            <th class="px-6 py-4">Event</th>
                            <th class="px-6 py-4">Event Date</th>
                            <th class="px-6 py-4">Time In</th>
                            <th class="px-6 py-4">Attendance Status</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($attendanceRecords as $record)

                            <tr class="transition hover:bg-slate-50">

                                {{-- Event Name --}}
                                <td class="px-6 py-5">

                                    <p class="font-semibold text-[#101064]">
                                        {{ $record->event?->event_name ?? 'Event Unavailable' }}
                                    </p>

                                    @if ($record->event?->location)

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $record->event->location }}
                                        </p>

                                    @endif

                                </td>

                                {{-- Event Date --}}
                                <td class="px-6 py-5 text-slate-700">

                                    @if ($record->event?->event_date)

                                        {{ \Illuminate\Support\Carbon::parse($record->event->event_date)->format('M d, Y') }}

                                    @else

                                        N/A

                                    @endif

                                </td>

                                {{-- Time In --}}
                                <td class="px-6 py-5 text-slate-700">

                                    @if ($record->time_in)

                                        {{ \Illuminate\Support\Carbon::parse($record->time_in)->format('h:i A') }}

                                    @else

                                        N/A

                                    @endif

                                </td>

                                {{-- Attendance Status --}}
                                <td class="px-6 py-5">

                                    @if ($record->status === 'Present')

                                        <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1.5 text-xs font-bold text-green-700">

                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>

                                            Present

                                        </span>

                                    @elseif ($record->status === 'Late')

                                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-700">

                                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                                            Late

                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600">
                                            {{ $record->status }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-6 py-16 text-center">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-7 w-7 text-[#101064]"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"
                                            />
                                        </svg>

                                    </div>

                                    <h3 class="mt-4 font-bold text-[#101064]">
                                        No Attendance Records Found
                                    </h3>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Your attendance records will appear here
                                        once attendance has been recorded.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if ($attendanceRecords->hasPages())

                <div class="border-t border-slate-100 px-6 py-4">

                    {{ $attendanceRecords->links() }}

                </div>

            @endif

        </section>

        {{-- Information --}}
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-5">

            <h3 class="text-sm font-bold text-[#101064]">
                Attendance Information
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                This page displays your recorded event attendance.
                If you notice an incorrect or missing attendance record,
                you may submit a request through the
                Attendance Discrepancies section.
            </p>

            <a
                href="{{ route('student.attendance-discrepancies.index') }}"
                class="mt-3 inline-flex items-center text-sm font-bold text-[#101064] underline decoration-[#D4A017] underline-offset-4 hover:text-[#D4A017]"
            >
                View Attendance Discrepancies
            </a>

        </div>

        <p class="text-xs text-slate-500">
            Attendance summaries reflect all your recorded attendance entries.
            The status filter affects the history table only.
        </p>

    </div>

</x-admin-layout>
