<x-admin-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="rounded-3xl border-b-4 border-[#D4A017] bg-[#101064] px-7 py-9 text-white shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[.28em] text-[#F2C94C]">Module #10 · Attendance Personnel</p>
            <h1 class="mt-3 text-3xl font-extrabold">Attendance Exceptions</h1>
            <p class="mt-2 text-sm text-indigo-100">Report a failed or missed RFID scan for an event assigned to you. A Department Staff member must verify the request.</p>
        </section>

        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <strong>Please check the request:</strong>
                <ul class="mt-2 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-extrabold text-[#101064]">Report Attendance Exception</h2>
            <p class="mb-5 mt-1 text-sm text-slate-500">This submits a request only. It does not create an attendance record until staff approval.</p>
            <form method="POST" action="{{ route('personnel.attendance-exceptions.store') }}" class="grid gap-5 md:grid-cols-2">
                @csrf
                <div>
                    <label for="event_id" class="mb-2 block text-sm font-semibold text-slate-700">Assigned Event</label>
                    <select id="event_id" name="event_id" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                        <option value="">Select an assigned event</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->event_id }}" @selected(old('event_id') == $event->event_id)>
                                {{ $event->event_name }} · {{ $event->event_date?->format('M d, Y') }} ({{ $event->status }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="student_number" class="mb-2 block text-sm font-semibold text-slate-700">Student Number</label>
                    <input id="student_number" name="student_number" type="text" required maxlength="100" value="{{ old('student_number') }}" placeholder="e.g., 2024-03184" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label for="issue_type" class="mb-2 block text-sm font-semibold text-slate-700">Issue Type</label>
                    <select id="issue_type" name="issue_type" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                        <option value="">Select issue</option>
                        @foreach (['Missed Scan', 'Unreadable RFID', 'Scanner Failure', 'RFID Not Registered'] as $issue)
                            <option value="{{ $issue }}" @selected(old('issue_type') === $issue)>{{ $issue }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="observed_at" class="mb-2 block text-sm font-semibold text-slate-700">Observed Attendance Time (PH time)</label>
                    <input id="observed_at" name="observed_at" type="datetime-local" required value="{{ old('observed_at') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                    <p class="mt-1 text-xs text-slate-500">Must fall within the event's scheduled start and end.</p>
                </div>
                <div class="md:col-span-2">
                    <label for="reason" class="mb-2 block text-sm font-semibold text-slate-700">Explanation / Verification Notes</label>
                    <textarea id="reason" name="reason" rows="3" required minlength="10" maxlength="2000" placeholder="Describe what happened and how attendance was verified..." class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">{{ old('reason') }}</textarea>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="rounded-xl bg-[#D4A017] px-6 py-3 text-sm font-extrabold text-[#101064] hover:bg-yellow-400" @disabled($events->isEmpty())>Submit for Staff Review</button>
                </div>
                @if ($events->isEmpty())
                    <p class="md:col-span-2 text-sm text-slate-500">You have no assigned events. Ask Department Staff to assign you before reporting an exception.</p>
                @endif
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5"><h2 class="text-lg font-extrabold text-[#101064]">My Submitted Exceptions</h2></div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr>
                        <th class="px-5 py-3">Event / Student</th><th class="px-5 py-3">Issue & Observation</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Review</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($exceptions as $exception)
                            <tr>
                                <td class="px-5 py-4"><div class="font-bold text-[#101064]">{{ $exception->event?->event_name ?? 'Deleted Event' }}</div>
                                    <div class="mt-1 text-slate-600">{{ $exception->student?->student_number }} · {{ $exception->student?->last_name }}, {{ $exception->student?->first_name }}</div>
                                </td>
                                <td class="px-5 py-4"><div class="font-semibold">{{ $exception->issue_type }}</div><div class="mt-1 text-xs text-slate-500">{{ $exception->observed_at?->format('M d, Y g:i A') }}</div><div class="mt-1 max-w-sm whitespace-pre-wrap text-slate-600">{{ $exception->reason }}</div></td>
                                <td class="px-5 py-4"><span class="inline-block rounded-full px-3 py-1 text-xs font-bold {{ $exception->status === 'Approved' ? 'bg-green-100 text-green-800' : ($exception->status === 'Rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">{{ $exception->status }}</span></td>
                                <td class="px-5 py-4 text-slate-600">{{ $exception->review_notes ?: 'Awaiting staff review' }}
                                    @if ($exception->reviewed_at)<div class="mt-1 text-xs text-slate-500">{{ $exception->reviewed_at->format('M d, Y g:i A') }}</div>@endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">No attendance exceptions submitted yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $exceptions->links() }}</div>
        </section>
    </div>
</x-admin-layout>
