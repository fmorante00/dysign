<x-admin-layout>
    <style>
        .dysign-personnel-hero {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            background-image:
                radial-gradient(circle at 94% 15%,
                    transparent 0 92px,
                    rgba(255,255,255,.19) 93px 95px,
                    transparent 96px 146px,
                    rgba(255,255,255,.14) 147px 149px,
                    transparent 150px),
                linear-gradient(100deg,
                    rgba(16,16,100,.97) 0%,
                    rgba(16,16,100,.91) 52%,
                    rgba(16,16,100,.73) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
    </style>

    <div class="w-full min-w-0 space-y-6 pb-8">
        <section class="dysign-personnel-hero rounded-[28px] border-b-4 border-[#D4A017] px-7 py-9 text-white shadow-sm sm:px-9">
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
            <form method="POST" action="{{ route('personnel.attendance-exceptions.store') }}" class="grid gap-5 md:grid-cols-2" data-confirm data-confirm-title="Submit attendance exception?" data-confirm-message="Submit this exception report and its evidence for review." data-confirm-confirm-text="Submit Report" data-confirm-variant="default">
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
                    @if (\Illuminate\Support\Facades\Schema::hasColumn('attendance_exceptions', 'checked_out_at'))
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Automatic Time In</label>
                        <input type="hidden" name="capture_mode" value="live">

                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                            Time In will be recorded automatically when you submit.
                        </div>

                        <p class="mt-1 text-xs text-slate-500">
                            Actual Philippine server time. Available only during the event's valid check-in window.
                        </p>
                    @else
                        <label for="observed_at" class="mb-2 block text-sm font-semibold text-slate-700">Observed Attendance Time (PH time)</label>
                        <input id="observed_at" name="observed_at" type="datetime-local" required value="{{ old('observed_at') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                        <p class="mt-1 text-xs text-slate-500">Must fall within the event's scheduled start and end.</p>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label for="reason" class="mb-2 block text-sm font-semibold text-slate-700">Explanation / Verification Notes</label>
                    <textarea id="reason" name="reason" rows="3" required minlength="10" maxlength="2000" placeholder="Describe what happened and how attendance was verified..." class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">{{ old('reason') }}</textarea>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="rounded-xl bg-[#D4A017] px-6 py-3 text-sm font-extrabold text-[#101064] hover:bg-yellow-400 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:bg-[#D4A017]" @disabled($events->isEmpty())>{{ \Illuminate\Support\Facades\Schema::hasColumn("attendance_exceptions", "checked_out_at") ? "Record Time In & Submit Report" : "Submit for Staff Review" }}</button>
                </div>
                @if ($events->isEmpty())
                    @if (\Illuminate\Support\Facades\Schema::hasColumn('attendance_exceptions', 'checked_out_at'))
    <p class="md:col-span-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        No assigned events are currently accepting check-ins.
        Manual Check-In opens one hour before an event starts and closes when the event ends.
        Late check-ins are accepted while the event is ongoing.
    </p>
@else
    <p class="md:col-span-2 text-sm text-slate-500">
        You have no assigned events. Ask Department Staff to assign you before reporting an exception.
    </p>
@endif
                @endif
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-6 py-5">
    <div>
        <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#BD8D18]">Attendance History</p>
        <h2 class="mt-1 text-lg font-extrabold text-[#101064]">My Submitted Exceptions</h2>
        <p class="mt-1 text-xs text-slate-500">Track manual check-ins, check-outs, and department review decisions.</p>
    </div>
    <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600">
        {{ $exceptions->total() }} total reports
    </span>
</div>
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
                                <td class="px-5 py-4"><div class="font-semibold">{{ $exception->issue_type }}</div><div class="mt-1 text-xs text-slate-500">{{ $exception->observed_at?->format('M d, Y g:i A') }}</div><details class="mt-2 max-w-sm">
    <summary class="cursor-pointer text-xs font-semibold text-[#101064] hover:underline">
        View incident notes
    </summary>
    <p class="mt-2 whitespace-pre-wrap rounded-lg bg-slate-50 p-3 text-xs leading-5 text-slate-600">
        {{ $exception->reason }}
    </p>
</details></td>
                                <td class="px-5 py-4"><span class="inline-block rounded-full px-3 py-1 text-xs font-bold {{ $exception->status === 'Approved' ? 'bg-green-100 text-green-800' : ($exception->status === 'Rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">{{ $exception->status }}</span></td>
                                <td class="px-5 py-4 text-slate-600">@if ($exception->status === 'Pending')
    <span class="text-xs font-medium text-amber-700">Awaiting staff review</span>
@elseif ($exception->status === 'Rejected')
    <p class="text-xs leading-5 text-red-700">
        <strong>Rejected:</strong>
        {{ \Illuminate\Support\Str::limit($exception->review_notes ?: 'No reason provided.', 130) }}
    </p>
@else
    <span class="text-xs font-semibold text-emerald-700">Approved by Department Staff</span>
    @if ($exception->review_notes)
        <details class="mt-2">
            <summary class="cursor-pointer text-xs font-semibold text-[#101064]">
                View reviewer notes
            </summary>
            <p class="mt-2 whitespace-pre-wrap text-xs leading-5 text-slate-600">
                {{ $exception->review_notes }}
            </p>
        </details>
    @endif
@endif
                                    @if ($exception->reviewed_at)<div class="mt-1 text-xs text-slate-500">{{ $exception->reviewed_at->format('M d, Y g:i A') }}</div>@endif
                                    @if (\Illuminate\Support\Facades\Schema::hasColumn('attendance_exceptions', 'checked_out_at'))
                                        @if ($exception->checked_out_at)
                                            <div class="mt-3 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-xs font-semibold text-green-800">
                                                Checked Out: {{ $exception->checked_out_at->format('M d, Y g:i A') }}
                                            </div>
                                        @elseif ($exception->status === 'Pending')
                                            @php
                                                $checkoutEvent = $exception->event;
                                                $checkoutNow = \Carbon\Carbon::now('Asia/Manila');

                                                $checkoutStartsAt = null;
                                                $checkoutEndsAt = null;

                                                if ($checkoutEvent) {
                                                    $eventDate = \Carbon\Carbon::parse(
                                                        $checkoutEvent->event_date,
                                                        'Asia/Manila'
                                                    )->format('Y-m-d');

                                                    $startTime = \Carbon\Carbon::parse(
                                                        $checkoutEvent->start_time,
                                                        'Asia/Manila'
                                                    )->format('H:i:s');

                                                    $endTime = \Carbon\Carbon::parse(
                                                        $checkoutEvent->end_time,
                                                        'Asia/Manila'
                                                    )->format('H:i:s');

                                                    $checkoutStartsAt = \Carbon\Carbon::parse(
                                                        "{$eventDate} {$startTime}",
                                                        'Asia/Manila'
                                                    );

                                                    $checkoutEndsAt = \Carbon\Carbon::parse(
                                                        "{$eventDate} {$endTime}",
                                                        'Asia/Manila'
                                                    );

                                                    if ($checkoutEndsAt->lt($checkoutStartsAt)) {
                                                        $checkoutEndsAt->addDay();
                                                    }
                                                }

                                                $checkoutClosesAt = $checkoutEndsAt?->copy()->addHour();

                                                $canCheckOut = $checkoutEndsAt
                                                    && $checkoutNow->gte($checkoutEndsAt)
                                                    && $checkoutNow->lte($checkoutClosesAt);
                                            @endphp
                                            <form method="POST"
                                                action="{{ route('personnel.attendance-exceptions.checkout', $exception->exception_id) }}"
                                                class="mt-3"
                                                data-confirm
                                                data-confirm-title="Confirm Manual Check-Out?"
                                                data-confirm-message="Confirm the student is actually checking out now. The current Philippine server time will be recorded automatically."
                                                data-confirm-confirm-text="Record Check-Out"
                                                data-confirm-variant="warning">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    @disabled(!$canCheckOut)
                                                    class="rounded-xl bg-[#D4A017] px-4 py-2 text-xs font-bold text-[#101064] transition hover:bg-yellow-400 disabled:cursor-not-allowed disabled:opacity-40">
                                                    Record Check-Out
                                                </button>
                                            </form>

                                            <p class="mt-2 text-xs">
                                                @if ($canCheckOut)
                                                    <span class="font-semibold text-emerald-700">
                                                        Check-Out is available until
                                                        {{ $checkoutClosesAt->format('g:i A') }} (PH time).
                                                    </span>
                                                @elseif ($checkoutEndsAt && $checkoutNow->lt($checkoutEndsAt))
                                                    <span class="text-amber-700">
                                                        Check-Out opens on
                                                        {{ $checkoutEndsAt->format('M d, Y g:i A') }}.
                                                        Refresh the page when the checkout window opens.
                                                    </span>
                                                @else
                                                    <span class="text-red-700">
                                                        Check-Out is unavailable because the permitted window has ended
                                                        or the event details are missing.
                                                    </span>
                                                @endif
                                            </p>
                                        @endif
                                    @endif
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
