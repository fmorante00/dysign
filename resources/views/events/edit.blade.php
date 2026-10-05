<x-admin-layout>
    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-bold text-[#101064]">Edit Event</h1>
            <p class="mt-2 text-gray-500">Update event information.</p>
        </div>

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                <p class="font-semibold">Please check the following:</p>
                <ul class="mt-2 list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
            <form method="POST" action="{{ route('events.update', $event->event_id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="event_name" class="text-sm font-semibold text-gray-600">
                            Event Name
                        </label>
                        <input
                            id="event_name"
                            type="text"
                            name="event_name"
                            value="{{ old('event_name', $event->event_name) }}"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        >
                    </div>

                    <div>
                        <label for="location" class="text-sm font-semibold text-gray-600">
                            Location
                        </label>
                        <input
                            id="location"
                            type="text"
                            name="location"
                            value="{{ old('location', $event->location) }}"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        >
                    </div>

                    <div>
                        <label for="department_id" class="text-sm font-semibold text-gray-600">
                            Department
                        </label>

                        @if(auth()->user()->role->role_name === 'Department Staff')
                            <div class="mt-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700">
                                {{ auth()->user()->department->department_name ?? 'No department assigned' }}
                            </div>
                        @else
                            <select
                                id="department_id"
                                name="department_id"
                                class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                            >
                                <option value="">University-wide Event</option>

                                @foreach($departments as $department)
                                    <option
                                        value="{{ $department->department_id }}"
                                        {{ old('department_id', $event->department_id) == $department->department_id ? 'selected' : '' }}
                                    >
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div>
                        <label for="event_date" class="text-sm font-semibold text-gray-600">
                            Event Date
                        </label>
                        <input
                            id="event_date"
                            type="date"
                            name="event_date"
                            value="{{ old('event_date', $event->event_date) }}"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        >
                    </div>

                    <div>
                        <label for="start_time" class="text-sm font-semibold text-gray-600">
                            Start Time
                        </label>
                        <input
                            id="start_time"
                            type="time"
                            name="start_time"
                            value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('H:i')) }}"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        >
                    </div>

                    <div>
                        <label for="end_time" class="text-sm font-semibold text-gray-600">
                            End Time
                        </label>
                        <input
                            id="end_time"
                            type="time"
                            name="end_time"
                            value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('H:i')) }}"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        >
                    </div>

                    <div>
                        <label for="status" class="text-sm font-semibold text-gray-600">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        >
                            <option value="Upcoming" {{ old('status', $event->status) === 'Upcoming' ? 'selected' : '' }}>
                                Upcoming
                            </option>
                            <option value="Ongoing" {{ old('status', $event->status) === 'Ongoing' ? 'selected' : '' }}>
                                Ongoing
                            </option>
                            <option value="Completed" {{ old('status', $event->status) === 'Completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="Cancelled" {{ old('status', $event->status) === 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="text-sm font-semibold text-gray-600">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                    >{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a
                        href="{{ route('events.show', $event->event_id) }}"
                        class="rounded-xl border border-gray-200 px-5 py-3 font-semibold text-gray-600 transition hover:bg-gray-50"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rounded-xl bg-[#101064] px-5 py-3 font-semibold text-white transition hover:bg-[#0c0c4f]"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>