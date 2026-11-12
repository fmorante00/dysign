
<x-admin-layout>

    <style>
        .progress-hero {
            background:
                linear-gradient(
                    100deg,
                    rgba(16,16,100,.97),
                    rgba(16,16,100,.88) 57%,
                    rgba(16,16,100,.75)
                ),
                url('{{ asset('images/school.jpg') }}');

            background-size: cover;
            background-position: center;
        }
    </style>

    <div class="min-w-0 space-y-7">

        <!-- Page Header -->
        <section class="progress-hero relative overflow-hidden
                        rounded-[28px] px-7 py-8 text-white
                        sm:px-8 lg:px-10 lg:py-10">

            <div class="absolute bottom-0 left-0 h-1 w-full
                        bg-[#D4A017]"></div>

            <p class="text-xs font-semibold uppercase
                      tracking-[0.30em] text-[#E7C75B]">
                Module #14 - Student Account
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight
                       md:text-4xl">
                My Participation Progress
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-6
                      text-white/75">
                Monitor your event attendance, participation
                progress, and earned activity points.
            </p>
        </section>

        <!-- Statistics -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-2xl border border-gray-200
                        bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase
                          tracking-wider text-slate-500">
                    Total Activity Points
                </p>

                <p class="mt-3 text-3xl font-bold text-[#101064]">
                    {{ number_format($totalPoints) }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Points earned from recorded activities
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200
                        bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase
                          tracking-wider text-slate-500">
                    Events Attended
                </p>

                <p class="mt-3 text-3xl font-bold text-[#101064]">
                    {{ $attendedEvents }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Completed eligible events attended
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200
                        bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase
                          tracking-wider text-slate-500">
                    Eligible Events
                </p>

                <p class="mt-3 text-3xl font-bold text-[#101064]">
                    {{ $totalEligibleEvents }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Completed events you were eligible for
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200
                        bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase
                          tracking-wider text-slate-500">
                    Participation Progress
                </p>

                <p class="mt-3 text-3xl font-bold text-[#D4A017]">
                    {{ number_format($progressPercentage, 1) }}%
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Attendance rate for completed eligible events
                </p>
            </div>

        </div>

        <!-- Participation Progress -->
        <section class="rounded-2xl border border-gray-200
                        bg-white p-6 shadow-sm">

            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-[#101064]">
                        Participation Overview
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Your attendance progress across completed
                        eligible events.
                    </p>
                </div>

                <span class="rounded-full bg-[#D4A017]/10
                             px-4 py-2 text-sm font-bold
                             text-[#956B00]">
                    {{ number_format($progressPercentage, 1) }}%
                </span>
            </div>

            <div class="mt-6 h-4 overflow-hidden rounded-full
                        bg-slate-100"
                 role="progressbar"
                 aria-label="Participation progress"
                 aria-valuemin="0"
                 aria-valuemax="100"
                 aria-valuenow="{{ $progressPercentage }}">

                <div class="h-full rounded-full bg-[#D4A017]
                            transition-all duration-500"
                     style="width: {{ min(100, max(0, $progressPercentage)) }}%;">
                </div>
            </div>

            <div class="mt-3 flex flex-wrap justify-between gap-2
                        text-sm text-slate-500">

                <span>
                    {{ $attendedEvents }} attended
                </span>

                <span>
                    {{ $totalEligibleEvents }} eligible completed events
                </span>

            </div>

            @if ($totalEligibleEvents === 0)
                <p class="mt-4 rounded-xl bg-slate-50 px-4 py-3
                          text-sm text-slate-500">
                    No completed eligible events are available
                    for participation tracking yet.
                </p>
            @endif

        </section>

        <!-- Attendance History -->
        <section class="overflow-hidden rounded-2xl
                        border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-lg font-bold text-[#101064]">
                    Attendance History
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Your recorded attendance across events.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">

                    <thead class="bg-slate-50 text-xs uppercase
                                  tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Event</th>
                            <th class="px-6 py-4">Time In</th>
                            <th class="px-6 py-4">Attendance Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($attendanceRecords as $record)
                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-4 font-semibold
                                           text-slate-800">
                                    {{ $record->event?->event_name ?? 'Event unavailable' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4
                                           text-slate-600">
                                    {{ $record->time_in?->format('M d, Y - h:i A') ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($record->status === 'Present')
                                        <span class="rounded-full bg-green-100
                                                     px-3 py-1 text-xs
                                                     font-semibold text-green-700">
                                            Present
                                        </span>
                                    @elseif ($record->status === 'Late')
                                        <span class="rounded-full bg-amber-100
                                                     px-3 py-1 text-xs
                                                     font-semibold text-amber-700">
                                            Late
                                        </span>
                                    @else
                                        <span class="rounded-full bg-slate-100
                                                     px-3 py-1 text-xs
                                                     font-semibold text-slate-600">
                                            {{ $record->status }}
                                        </span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-10 text-center
                                           text-slate-500">
                                    No attendance records found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            @if ($attendanceRecords->hasPages())
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $attendanceRecords->links() }}
                </div>
            @endif

        </section>

        <!-- Recent Activity Points -->
        <section class="overflow-hidden rounded-2xl
                        border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-lg font-bold text-[#101064]">
                    Recent Activity Points
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Your latest awarded participation points.
                </p>
            </div>

            <div class="divide-y divide-gray-100">

                @forelse ($pointRecords as $point)

                    <div class="flex flex-wrap items-center
                                justify-between gap-4 px-6 py-5">

                        <div>
                            <p class="font-semibold text-slate-800">
                                {{ $point->event?->event_name ?? 'Event unavailable' }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $point->awarded_at?->format('M d, Y') ?? 'N/A' }}
                            </p>
                        </div>

                        <span class="rounded-xl bg-[#D4A017]/10
                                     px-4 py-2 font-bold text-[#956B00]">
                            +{{ $point->points_awarded }} pts
                        </span>

                    </div>

                @empty

                    <div class="px-6 py-10 text-center">
                        <p class="font-semibold text-slate-600">
                            No activity points awarded yet.
                        </p>

                        <p class="mt-2 text-sm text-slate-400">
                            Your points will appear here
                            once they are recorded.
                        </p>
                    </div>

                @endforelse

            </div>

        </section>

    </div>

</x-admin-layout>
