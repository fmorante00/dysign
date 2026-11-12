
<x-admin-layout>

    <div class="min-h-screen bg-slate-50 py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">

                <div class="mb-3">
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-semibold text-slate-500 hover:text-blue-900">
                        &larr; Back to Dashboard
                    </a>
                </div>

                <div class="flex items-start justify-between gap-4">

                    <div>
                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                            MODULE 13
                        </span>

                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight"
                            style="color:#101064;">
                            Activity Point Management
                        </h1>

                        <p class="mt-2 text-sm text-slate-500">
                            Select an event to configure participation points and monitor earned student points.
                        </p>
                    </div>

                </div>

            </div>

            <!-- Filters -->
            <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <form method="GET"
                      action="{{ route('staff.activity-points.index') }}"
                      class="grid grid-cols-1 items-end gap-4 md:grid-cols-4">

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Search Events
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search by event name..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-800 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Event Status
                        </label>

                        <select name="status"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">

                            <option value="">All Statuses</option>

                            @foreach(['Upcoming', 'Ongoing', 'Completed', 'Cancelled'] as $option)
                                <option value="{{ $option }}"
                                    @selected($status === $option)>
                                    {{ $option }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full rounded-xl px-5 py-3 text-sm font-bold text-white transition hover:opacity-90"
                                style="background:#101064;">
                            Apply Filters
                        </button>
                    </div>

                </form>

            </div>

            <!-- Event List -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-6">

                    <div>
                        <h2 class="text-lg font-bold" style="color:#101064;">
                            Department Events
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Manage activity points for events assigned to your department.
                        </p>
                    </div>

                    <span class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">
                        {{ $events->total() }} Events
                    </span>

                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-100">

                        <thead class="bg-slate-50">

                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Event
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Date
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Point Rule
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Action
                                </th>
                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @forelse($events as $event)

                                @php
                                    $hasRule = in_array(
                                        (int) $event->event_id,
                                        $configuredEventIds,
                                        true
                                    );

                                    $statusStyle = match($event->status) {
                                        'Upcoming' => 'bg-blue-100 text-blue-800',
                                        'Ongoing' => 'bg-green-100 text-green-800',
                                        'Completed' => 'bg-slate-100 text-slate-700',
                                        'Cancelled' => 'bg-red-100 text-red-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp

                                <tr class="transition hover:bg-slate-50">

                                    <td class="px-6 py-5">

                                        <p class="font-bold text-slate-800">
                                            {{ $event->event_name }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $event->location ?: 'Venue not specified' }}
                                        </p>

                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-600">
                                        {{ $event->event_date
                                            ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y')
                                            : 'Not set' }}
                                    </td>

                                    <td class="px-6 py-5">

                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyle }}">
                                            {{ $event->status }}
                                        </span>

                                    </td>

                                    <td class="px-6 py-5">

                                        @if($hasRule)

                                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                                Configured
                                            </span>

                                        @else

                                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                                Not Configured
                                            </span>

                                        @endif

                                    </td>

                                    <td class="px-6 py-5 text-right">

                                        <a href="{{ route('staff.activity-points.show', ['eventId' => $event->event_id]) }}"
                                           class="inline-flex whitespace-nowrap rounded-lg px-4 py-2 text-xs font-bold text-white transition hover:opacity-90"
                                           style="background:#101064;">

                                            {{ $event->status === 'Upcoming'
                                                ? 'Manage Points'
                                                : 'View Points' }}

                                            &rarr;

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">

                                        <div class="mx-auto max-w-md">

                                            <div class="mb-3 text-4xl">
                                                &#128197;
                                            </div>

                                            <h3 class="font-bold text-slate-700">
                                                No Events Found
                                            </h3>

                                            <p class="mt-2 text-sm text-slate-400">
                                                There are no department events matching the selected filters.
                                            </p>

                                            <a href="{{ route('staff.activity-points.index') }}"
                                               class="mt-4 inline-block text-sm font-semibold text-blue-800 hover:underline">
                                                Clear Filters
                                            </a>

                                        </div>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if($events->hasPages())

                    <div class="border-t border-slate-100 px-6 py-4">

                        {{ $events->links() }}

                    </div>

                @endif

            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-xs text-slate-400">
                DySign — Event Participation Management System
            </div>

        </div>

    </div>

</x-admin-layout>
