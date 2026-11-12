<x-admin-layout>

    @php
        $canEdit = $event->status === 'Upcoming';
        $presentPoints = $rule?->present_points ?? 10;
        $latePoints = $rule?->late_points ?? 5;
    @endphp

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="mb-8">
                <a href="{{ route('dashboard') }}"
                   class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-blue-900">
                    &larr; Back to Dashboard
                </a>

                <div class="flex flex-wrap items-start justify-between gap-4">

                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                MODULE 13
                            </span>

                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-900">
                                Department Staff
                            </span>
                        </div>

                        <h1 class="text-3xl font-extrabold tracking-tight"
                            style="color:#101064;">
                            Activity Point Management
                        </h1>

                        <p class="mt-2 text-sm text-slate-500">
                            Configure event participation points and monitor awarded student records.
                        </p>
                    </div>

                </div>
            </div>

            <!-- Notifications -->
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Event Information -->
            <div class="mb-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

                <div class="h-2" style="background:#D4A017;"></div>

                <div class="p-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">

                        <div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                                Selected Event
                            </p>

                            <h2 class="text-xl font-bold text-slate-900">
                                {{ $event->event_name }}
                            </h2>

                            <p class="mt-2 text-sm text-slate-500">
                                Event Date:
                                {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('F d, Y') : 'Not set' }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                Venue: {{ $event->location ?: 'Not specified' }}
                            </p>
                        </div>

                        <span class="rounded-full px-4 py-2 text-xs font-bold
                            {{ $canEdit ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700' }}">
                            {{ $event->status }}
                        </span>

                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <!-- Present -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">
                            Present Points
                        </p>
                        <span class="rounded-lg bg-green-100 px-2 py-1 text-xs font-bold text-green-700">
                            Present
                        </span>
                    </div>

                    <p class="text-3xl font-extrabold" style="color:#101064;">
                        {{ number_format($presentPoints) }}
                    </p>
                    <p class="mt-2 text-xs text-slate-400">Per valid attendance</p>
                </div>

                <!-- Late -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">
                            Late Points
                        </p>
                        <span class="rounded-lg bg-yellow-100 px-2 py-1 text-xs font-bold text-yellow-700">
                            Late
                        </span>
                    </div>

                    <p class="text-3xl font-extrabold" style="color:#101064;">
                        {{ number_format($latePoints) }}
                    </p>
                    <p class="mt-2 text-xs text-slate-400">Per valid late attendance</p>
                </div>

                <!-- Total Points -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="mb-4 text-sm font-medium text-slate-500">
                        Total Points Awarded
                    </p>

                    <p class="text-3xl font-extrabold" style="color:#101064;">
                        {{ number_format($totalPoints) }}
                    </p>
                    <p class="mt-2 text-xs text-slate-400">
                        Accumulated event points
                    </p>
                </div>

                <!-- Awarded Students -->
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="mb-4 text-sm font-medium text-slate-500">
                        Awarded Students
                    </p>

                    <p class="text-3xl font-extrabold" style="color:#101064;">
                        {{ number_format($awardedStudents) }}
                    </p>
                    <p class="mt-2 text-xs text-slate-400">
                        Students with earned points
                    </p>
                </div>

            </div>

            <!-- Point Rules -->
            <div class="mb-8 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

                <div class="border-b border-slate-100 p-6">
                    <h2 class="text-lg font-bold" style="color:#101064;">
                        Event Point Configuration
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Define how many points students earn through valid event participation.
                    </p>

                    @if(!$rule)
                        <p class="mt-3 text-sm font-medium text-amber-700">
                            No saved point rule yet. The values displayed are defaults.
                        </p>
                    @endif
                </div>

                <form
                    action="{{ route('staff.activity-points.rules.update', ['eventId' => $event->event_id]) }}"
                    method="POST"
                    class="p-6">

                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div>
                            <label for="present_points"
                                   class="mb-2 block text-sm font-semibold text-slate-700">
                                Present Attendance Points
                            </label>

                            <input
                                type="number"
                                id="present_points"
                                name="present_points"
                                value="{{ old('present_points', $presentPoints) }}"
                                min="0"
                                max="4294967295"
                                required
                                {{ !$canEdit ? 'disabled' : '' }}
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-100 disabled:bg-slate-100"
                            >

                            <p class="mt-2 text-xs text-slate-400">
                                Points for valid Present attendance.
                            </p>
                        </div>

                        <div>
                            <label for="late_points"
                                   class="mb-2 block text-sm font-semibold text-slate-700">
                                Late Attendance Points
                            </label>

                            <input
                                type="number"
                                id="late_points"
                                name="late_points"
                                value="{{ old('late_points', $latePoints) }}"
                                min="0"
                                max="4294967295"
                                required
                                {{ !$canEdit ? 'disabled' : '' }}
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-100 disabled:bg-slate-100"
                            >

                            <p class="mt-2 text-xs text-slate-400">
                                Points for valid Late attendance.
                            </p>
                        </div>

                    </div>

                    <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-6">

                        <p class="max-w-xl text-xs leading-relaxed text-slate-500">
                            Point settings can only be changed while the event is Upcoming.
                            Activity points are awarded through validated attendance.
                        </p>

                        @if($canEdit)
                            <button type="submit"
                                    class="rounded-xl px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90"
                                    style="background:#101064;">
                                Save Point Rules
                            </button>
                        @else
                            <span class="rounded-lg bg-slate-100 px-4 py-3 text-xs font-semibold text-slate-500">
                                Configuration Locked
                            </span>
                        @endif

                    </div>
                </form>
            </div>

            <!-- Student Points Table -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 p-6">

                    <div>
                        <h2 class="text-lg font-bold" style="color:#101064;">
                            Student Activity Point Records
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Automatically generated records from validated event attendance.
                        </p>
                    </div>

                    <span class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">
                        {{ $activityPoints->total() }} Records
                    </span>

                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">

                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Student
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Student Number
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Attendance
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Points
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Awarded At
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">

                            @forelse($activityPoints as $point)

                                <tr class="transition hover:bg-slate-50">

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-800">
                                        {{ trim(($point->student?->first_name ?? '') . ' ' . ($point->student?->last_name ?? '')) ?: 'Student unavailable' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $point->student?->student_number ?? 'N/A' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @php
                                            $attendanceStatus = $point->attendance?->status ?? 'Unknown';
                                        @endphp

                                        <span class="rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $attendanceStatus === 'Present'
                                                ? 'bg-green-100 text-green-700'
                                                : ($attendanceStatus === 'Late'
                                                    ? 'bg-yellow-100 text-yellow-700'
                                                    : 'bg-slate-100 text-slate-600') }}">
                                            {{ $attendanceStatus }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-bold" style="color:#101064;">
                                        {{ number_format($point->points_awarded) }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                        {{ $point->awarded_at?->format('M d, Y h:i A') ?? 'Not awarded' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">

                                        <div class="mx-auto max-w-sm">
                                            <div class="mb-4 text-4xl">
                                                &#127942;
                                            </div>

                                            <p class="font-semibold text-slate-700">
                                                No Activity Points Yet
                                            </p>

                                            <p class="mt-2 text-sm leading-relaxed text-slate-400">
                                                Student activity points will appear here once attendance has been validated and point awarding has been processed.
                                            </p>
                                        </div>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($activityPoints->hasPages())
                    <div class="flex items-center justify-between gap-4 border-t border-slate-100 px-6 py-4">

                        <div class="text-xs text-slate-500">
                            Page {{ $activityPoints->currentPage() }}
                            of {{ $activityPoints->lastPage() }}
                        </div>

                        <div class="flex gap-2">

                            @if($activityPoints->onFirstPage())
                                <span class="rounded-lg border px-4 py-2 text-xs text-slate-400">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $activityPoints->previousPageUrl() }}"
                                   class="rounded-lg border px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                    Previous
                                </a>
                            @endif

                            @if($activityPoints->hasMorePages())
                                <a href="{{ $activityPoints->nextPageUrl() }}"
                                   class="rounded-lg px-4 py-2 text-xs font-semibold text-white"
                                   style="background:#101064;">
                                    Next
                                </a>
                            @else
                                <span class="rounded-lg border px-4 py-2 text-xs text-slate-400">
                                    Next
                                </span>
                            @endif

                        </div>
                    </div>
                @endif

            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-xs text-slate-400">
                    DySign — Event Participation Management System
                </p>
            </div>

        </div>
    </div>

</x-admin-layout>
