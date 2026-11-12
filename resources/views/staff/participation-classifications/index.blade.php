
<x-admin-layout>

    <style>
        .classification-hero {
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
        <section class="classification-hero relative overflow-hidden rounded-[28px] px-7 py-8 text-white sm:px-8 lg:px-10 lg:py-10">

            <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>

            <p class="text-xs font-semibold uppercase tracking-[0.30em] text-[#E7C75B]">
                Module #15 - Department Staff
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">
                Student Participation Classification
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">
                Monitor student participation performance,
                attendance rates, and earned activity points.
            </p>

        </section>

        <!-- Classification Guide -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div class="rounded-2xl border border-green-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-green-700">
                    Active Participation
                </p>
                <p class="mt-2 text-2xl font-bold text-[#101064]">
                    80% - 100%
                </p>
                <p class="mt-2 text-xs text-slate-500">
                    High event participation rate
                </p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-amber-700">
                    Moderate Participation
                </p>
                <p class="mt-2 text-2xl font-bold text-[#101064]">
                    50% - 79.9%
                </p>
                <p class="mt-2 text-xs text-slate-500">
                    Moderate event participation rate
                </p>
            </div>

            <div class="rounded-2xl border border-red-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-red-700">
                    Low Participation
                </p>
                <p class="mt-2 text-2xl font-bold text-[#101064]">
                    Below 50%
                </p>
                <p class="mt-2 text-xs text-slate-500">
                    Low event participation rate
                </p>
            </div>

        </div>

        <!-- Student Classification Table -->
        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 px-6 py-5">

                <div>
                    <h2 class="text-lg font-bold text-[#101064]">
                        Student Classifications
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Based on completed eligible events and recorded attendance.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('staff.participation-classifications.index') }}"
                      class="flex flex-wrap gap-2">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search student..."
                        class="rounded-xl border border-gray-300 px-4 py-2 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]"
                    >

                    <button type="submit"
                            class="rounded-xl bg-[#101064] px-5 py-2 text-sm font-semibold text-white hover:bg-[#191985]">
                        Search
                    </button>

                </form>

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
                            <th class="px-5 py-4">Points</th>
                            <th class="px-5 py-4">Classification</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

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

                                    <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                                        <div
                                            class="h-full rounded-full bg-[#D4A017]"
                                            style="width: {{ min(100, max(0, $student->participation_rate)) }}%;">
                                        </div>

                                    </div>

                                </td>

                                <td class="px-5 py-4 font-bold text-[#101064]">
                                    {{ number_format($student->total_points) }}
                                </td>

                                <td class="px-5 py-4">

                                    @if ($student->classification === 'Active Participation')

                                        <span class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Active
                                        </span>

                                    @elseif ($student->classification === 'Moderate Participation')

                                        <span class="inline-block rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Moderate
                                        </span>

                                    @elseif ($student->classification === 'Low Participation')

                                        <span class="inline-block rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Low
                                        </span>

                                    @else

                                        <span class="inline-block rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            No Data
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    No students with completed eligible events found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($students->hasPages())
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $students->links() }}
                </div>
            @endif

        </section>

        <p class="text-xs text-slate-500">
            Classification thresholds are currently proposed for testing
            and are not yet an official school policy.
        </p>

    </div>

</x-admin-layout>
