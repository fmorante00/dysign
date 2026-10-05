<x-admin-layout>
    <div class="space-y-8">
        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                <span class="font-semibold">✓</span>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                <span class="font-semibold">!</span>
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#101064]">
                    {{ $event->event_name }}
                </h1>
                <p class="mt-2 text-gray-500">Event Details</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('events.index') }}"
                    class="rounded-xl border border-gray-200 px-5 py-3 font-semibold text-gray-600 transition hover:bg-gray-50"
                >
                    Back
                </a>

                <a
                    href="{{ route('events.edit', $event->event_id) }}"
                    class="rounded-xl bg-[#101064] px-5 py-3 font-semibold text-white transition hover:bg-[#0c0c4f]"
                >
                    Edit Event
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
                            class="rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-700"
                        >
                            Cancel Event
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-400">Event Name</p>
                    <p class="mt-1 font-semibold text-[#101064]">
                        {{ $event->event_name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Department</p>

                    @if($event->department)
                        <span class="mt-1 inline-flex rounded-full bg-purple-100 px-3 py-1 text-sm font-medium text-purple-700">
                            {{ $event->department->department_name }}
                        </span>
                    @else
                        <p class="mt-1 font-semibold text-gray-700">
                            University-wide Event
                        </p>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-400">Date</p>
                    <p class="mt-1 font-semibold text-gray-700">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Location</p>
                    <p class="mt-1 font-semibold text-gray-700">
                        {{ $event->location }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Schedule</p>
                    <p class="mt-1 font-semibold text-gray-700">
                        {{ date('g:i A', strtotime($event->start_time)) }}
                        -
                        {{ date('g:i A', strtotime($event->end_time)) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-400">Status</p>

                    @if($event->status === 'Upcoming')
                        <span class="mt-1 inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                            Upcoming
                        </span>
                    @elseif($event->status === 'Ongoing')
                        <span class="mt-1 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                            Ongoing
                        </span>
                    @elseif($event->status === 'Completed')
                        <span class="mt-1 inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                            Completed
                        </span>
                    @else
                        <span class="mt-1 inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                            Cancelled
                        </span>
                    @endif
                </div>
            </div>

            <div class="mt-8 border-t border-gray-100 pt-8">
                <p class="text-sm text-gray-400">Description</p>
                <p class="mt-2 whitespace-pre-line text-gray-700">
                    {{ $event->description ?? 'No description provided.' }}
                </p>
            </div>

            <div class="mt-8 border-t border-gray-100 pt-6">
                <p class="text-sm text-gray-400">Created By</p>
                <p class="mt-1 font-semibold text-gray-700">
                    {{ $event->creator->name ?? 'Unknown' }}
                </p>
            </div>
        </div>
    </div>
</x-admin-layout>