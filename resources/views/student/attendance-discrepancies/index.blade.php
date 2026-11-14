<x-admin-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="rounded-3xl border-b-4 border-[#D4A017] bg-[#101064] px-7 py-9 text-white shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[.28em] text-[#F2C94C]">Module #22 · Student Account</p>
            <h1 class="mt-3 text-3xl font-extrabold">My Attendance Discrepancies</h1>
            <p class="mt-2 text-sm text-indigo-100">Report a missing attendance record, wrong attendance status, or incorrect time. Department Staff makes the final decision.</p>
        </section>

        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <p class="font-bold">Please correct the request:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-extrabold text-[#101064]">Submit Attendance Discrepancy</h2>
            <p class="mt-1 text-sm text-slate-500">Only eligible, completed events are available. A request does not automatically change attendance.</p>
            <form method="POST" action="{{ route('student.attendance-discrepancies.store') }}" class="mt-5 grid gap-5 md:grid-cols-2" data-confirm data-confirm-title="Submit attendance discrepancy?" data-confirm-message="Send the attendance discrepancy and supporting explanation to staff." data-confirm-confirm-text="Submit Request" data-confirm-variant="default">
                @csrf
                <div class="md:col-span-2">
                    <label for="event_id" class="mb-2 block text-sm font-bold text-slate-700">Completed Event</label>
                    <select name="event_id" id="event_id" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                        <option value="">Select event</option>
                        @foreach ($events as $event)
                            @php($attendance = $attendanceByEvent->get($event->event_id))
                            <option value="{{ $event->event_id }}" @selected(old('event_id') == $event->event_id)>
                                {{ $event->event_name }} · {{ $event->event_date?->format('M d, Y') }} · {{ $attendance ? $attendance->status . ' recorded' : 'No attendance recorded' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="request_type" class="mb-2 block text-sm font-bold text-slate-700">Issue Type</label>
                    <select name="request_type" id="request_type" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                        <option value="">Select issue</option>
                        @foreach (['Missing Attendance', 'Incorrect Status', 'Incorrect Time'] as $type)
                            <option value="{{ $type }}" @selected(old('request_type') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="requested_status" class="mb-2 block text-sm font-bold text-slate-700">Correct Attendance Status</label>
                    <select name="requested_status" id="requested_status" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                        <option value="">Not applicable to Incorrect Time</option>
                        @foreach (['Present', 'Late'] as $status)
                            <option value="{{ $status }}" @selected(old('requested_status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Required for Missing Attendance or Incorrect Status.</p>
                </div>
                <div class="md:col-span-2">
                    <label for="requested_time_in" class="mb-2 block text-sm font-bold text-slate-700">Correct Attendance Time (Philippine time)</label>
                    <input type="datetime-local" name="requested_time_in" id="requested_time_in" value="{{ old('requested_time_in') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                    <p class="mt-1 text-xs text-slate-500">Required for Missing Attendance or Incorrect Time. Must be within the event schedule.</p>
                </div>
                <div class="md:col-span-2">
                    <label for="reason" class="mb-2 block text-sm font-bold text-slate-700">Explanation / Evidence</label>
                    <textarea name="reason" id="reason" rows="4" required minlength="10" maxlength="2000" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm" placeholder="Explain what happened and why your attendance needs correction.">{{ old('reason') }}</textarea>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button class="rounded-xl bg-[#D4A017] px-6 py-3 text-sm font-extrabold text-[#101064] hover:bg-yellow-400 disabled:opacity-50" @disabled($events->isEmpty())>Submit for Review</button>
                </div>
                @if ($events->isEmpty())
                    <p class="md:col-span-2 text-sm text-slate-500">No eligible completed events found yet.</p>
                @endif
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5"><h2 class="text-lg font-extrabold text-[#101064]">My Submitted Requests</h2></div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr>
                        <th class="px-5 py-3">Event</th><th class="px-5 py-3">Requested Change</th><th class="px-5 py-3">Verification / Status</th><th class="px-5 py-3">Review</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse ($discrepancies as $item)
                        <tr>
                            <td class="px-5 py-4"><p class="font-bold text-[#101064]">{{ $item->event?->event_name ?? 'Unavailable Event' }}</p><p class="mt-1 text-xs text-slate-500">Submitted {{ $item->created_at?->format('M d, Y') }}</p></td>
                            <td class="px-5 py-4"><p class="font-semibold">{{ $item->request_type }}</p>
                                @if ($item->requested_status)<p class="text-slate-600">Status: {{ $item->requested_status }}</p>@endif
                                @if ($item->requested_time_in)<p class="text-slate-600">Time: {{ $item->requested_time_in->format('M d, Y g:i A') }}</p>@endif
                                <p class="mt-1 max-w-sm whitespace-pre-wrap text-xs text-slate-500">{{ $item->reason }}</p>
                            </td>
                            <td class="px-5 py-4"><p class="text-xs text-slate-500">Personnel: {{ $item->verification_status }}</p>
                                <span class="mt-2 inline-block rounded-full px-3 py-1 text-xs font-bold {{ $item->status === 'Approved' ? 'bg-green-100 text-green-800' : ($item->status === 'Rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">{{ $item->status }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $item->review_notes ?: 'Awaiting Department Staff review.' }}
                                @if ($item->reviewed_at)<p class="mt-1 text-xs text-slate-500">{{ $item->reviewer?->name }} · {{ $item->reviewed_at->format('M d, Y g:i A') }}</p>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">No attendance discrepancy requests submitted yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $discrepancies->links() }}</div>
        </section>
    </div>
</x-admin-layout>
