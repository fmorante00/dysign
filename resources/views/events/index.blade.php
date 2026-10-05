<x-admin-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#101064]">Event Management</h1>
                <p class="mt-2 text-gray-500">Manage university and department activities.</p>
            </div>

            <a href="{{ route('events.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-[#101064] px-5 py-3 font-semibold text-white transition hover:bg-[#0c0c4f]">
                + Create Event
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                <p class="font-semibold">Please check the following:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('events.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                <div class="lg:col-span-2">
                    <label for="search" class="mb-2 block text-sm font-semibold text-gray-700">
                        Search
                    </label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search event or location..."
                        class="w-full rounded-xl border-gray-200 px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]"
                    >
                </div>

                @if(auth()->user()->role->role_name === 'Department Staff')
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Department
                        </label>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600">
                            {{ auth()->user()->department->department_name ?? 'No department assigned' }}
                        </div>
                    </div>
                @else
                    <div>
                        <label for="department_id" class="mb-2 block text-sm font-semibold text-gray-700">
                            Department
                        </label>
                        <select
                            id="department_id"
                            name="department_id"
                            class="w-full rounded-xl border-gray-200 px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]"
                        >
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option
                                    value="{{ $department->department_id }}"
                                    {{ request('department_id') == $department->department_id ? 'selected' : '' }}
                                >
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label for="status" class="mb-2 block text-sm font-semibold text-gray-700">
                        Status
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-xl border-gray-200 px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]"
                    >
                        <option value="">All Statuses</option>
                        <option value="Upcoming" {{ request('status') === 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="Ongoing" {{ request('status') === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label for="event_date" class="mb-2 block text-sm font-semibold text-gray-700">
                        Date
                    </label>
                    <input
                        type="date"
                        id="event_date"
                        name="event_date"
                        value="{{ request('event_date') }}"
                        class="w-full rounded-xl border-gray-200 px-4 py-3 text-sm focus:border-[#101064] focus:ring-[#101064]"
                    >
                </div>

                <div class="flex items-end gap-2 md:col-span-2 lg:col-span-5">
                    <button
                        type="submit"
                        class="rounded-xl bg-[#101064] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#0c0c4f]"
                    >
                        Filter Events
                    </button>

                    <a
                        href="{{ route('events.index') }}"
                        class="rounded-xl border border-gray-200 px-5 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                    >
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-[#101064]">Event Directory</h2>
                        <p class="text-sm text-gray-500">
                            {{ $events->count() }} {{ $events->count() === 1 ? 'event' : 'events' }} found
                        </p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px]">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500">
                                Event
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500">
                                Department
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500">
                                Date & Time
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500">
                                Location
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($events as $event)
                            <tr class="border-t border-gray-100 transition hover:bg-gray-50">
                                <td class="px-6 py-5">
                                    <div>
                                        <p class="font-semibold text-[#101064]">
                                            {{ $event->event_name }}
                                        </p>

                                        @if($event->description)
                                            <p class="mt-1 max-w-xs truncate text-sm text-gray-400">
                                                {{ $event->description }}
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    @if($event->department)
                                        <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700">
                                            {{ $event->department->department_name }}
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                            University-wide
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-5">
                                    <p class="font-medium text-gray-700">
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-400">
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                    </p>
                                </td>

                                <td class="px-6 py-5 text-gray-600">
                                    {{ $event->location }}
                                </td>

                                <td class="px-6 py-5">
                                    @if($event->status === 'Upcoming')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                            Upcoming
                                        </span>
                                    @elseif($event->status === 'Ongoing')
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                            Ongoing
                                        </span>
                                    @elseif($event->status === 'Completed')
                                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                            Cancelled
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <a
                                            href="{{ route('events.show', $event->event_id) }}"
                                            class="font-semibold text-[#101064] hover:underline"
                                        >
                                            View
                                        </a>

                                        <a
                                            href="{{ route('events.edit', $event->event_id) }}"
                                            class="font-semibold text-gray-600 hover:text-[#101064]"
                                        >
                                            Edit
                                        </a>

                                        @if($event->status !== 'Cancelled')
                                            <form
                                                method="POST"
                                                action="{{ route('events.destroy', $event->event_id) }}"
                                                onsubmit="return confirm('Are you sure you want to cancel this event?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="font-semibold text-red-600 hover:text-red-700"
                                                >
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="mx-auto max-w-md">
                                        <p class="text-lg font-semibold text-gray-700">
                                            No events found
                                        </p>

                                        <p class="mt-2 text-sm text-gray-400">
                                            There are no events matching your current filters.
                                        </p>

                                        <a
                                            href="{{ route('events.create') }}"
                                            class="mt-5 inline-flex rounded-xl bg-[#101064] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#0c0c4f]"
                                        >
                                            Create an Event
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>