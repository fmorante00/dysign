<x-admin-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="rounded-3xl border-b-4 border-[#D4A017] bg-[#101064] px-7 py-9 text-white shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[.28em] text-[#F2C94C]">Module #10 · Department Staff</p>
            <h1 class="mt-3 text-3xl font-extrabold">Attendance Exception & Manual Verification</h1>
            <p class="mt-2 text-sm text-indigo-100">Review failed scan reports and approve attendance only when the evidence is sufficient.</p>
        </section>

        @if (session('success'))<div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">{{ session('success') }}</div>@endif
        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"><strong>Review could not be saved:</strong>
                <ul class="mt-2 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pending Reviews</p><div class="mt-2 text-3xl font-extrabold text-[#101064]">{{ $pendingCount }}</div></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Visible Requests (Current Page)</p><div class="mt-2 text-3xl font-extrabold text-[#101064]">{{ $exceptions->count() }}</div></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Scope</p><div class="mt-2 text-sm font-bold text-[#101064]">Your Department's Events Only</div></div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('staff.attendance-exceptions.index') }}" class="grid gap-4 md:grid-cols-3">
                <div><label class="mb-2 block text-xs font-semibold uppercase text-slate-500">Event</label>
                    <select name="event_id" class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm">
                        <option value="">All Department Events</option>
                        @foreach ($events as $event)<option value="{{ $event->event_id }}" @selected(request('event_id') == $event->event_id)>{{ $event->event_name }} · {{ $event->event_date?->format('M d') }}</option>@endforeach
                    </select>
                </div>
                <div><label class="mb-2 block text-xs font-semibold uppercase text-slate-500">Status</label>
                    <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm">
                        <option value="">All Statuses</option>
                        @foreach (['Pending', 'Approved', 'Rejected'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3"><button class="rounded-xl bg-[#101064] px-6 py-3 text-sm font-bold text-white">Filter</button><a href="{{ route('staff.attendance-exceptions.index') }}" class="py-3 text-sm font-semibold text-slate-500">Reset</a></div>
            </form>
        </section>

        <section class="space-y-4">
            @forelse ($exceptions as $exception)
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-[#D4A017]">#{{ $exception->exception_id }} · {{ $exception->issue_type }}</p>
                            <h2 class="mt-1 text-lg font-extrabold text-[#101064]">{{ $exception->student?->last_name }}, {{ $exception->student?->first_name }} <span class="text-sm font-normal text-slate-500">({{ $exception->student?->student_number }})</span></h2>
                            <p class="mt-1 text-sm text-slate-600">{{ $exception->event?->event_name }} · {{ $exception->observed_at?->format('M d, Y g:i A') }} PH time</p>
                            <p class="mt-1 text-xs text-slate-500">Reported by {{ $exception->reporter?->name ?? 'Unknown Personnel' }} · {{ $exception->created_at?->format('M d, Y g:i A') }}</p>
                        </div>
                        <span class="inline-block rounded-full px-3 py-1 text-xs font-bold {{ $exception->status === 'Approved' ? 'bg-green-100 text-green-800' : ($exception->status === 'Rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">{{ $exception->status }}</span>
                    </div>
                    <div class="mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-700"><span class="font-bold">Personnel explanation:</span><p class="mt-1 whitespace-pre-wrap">{{ $exception->reason }}</p></div>

                    @if ($exception->status === 'Pending')
                        <div class="mt-5 grid gap-4 border-t border-slate-100 pt-5 lg:grid-cols-2">
                            <form method="POST" action="{{ route('staff.attendance-exceptions.review', $exception->exception_id) }}" class="space-y-3 rounded-xl border border-green-200 bg-green-50 p-4">
                                @csrf @method('PATCH')
                                <input type="hidden" name="decision" value="Approved">
                                <p class="font-bold text-green-900">Approve & Record Manual Attendance</p>
                                <p class="text-xs text-green-900">Only approve verified reports. Attendance time will be the observation time reported above.</p>
                                <label class="block text-xs font-semibold text-green-900">Attendance Status</label>
                                <select name="attendance_status" required class="w-full rounded-lg border border-green-200 p-2 text-sm"><option value="Present">Present</option><option value="Late">Late</option></select>
                                <label class="block text-xs font-semibold text-green-900">Review Note (optional)</label>
                                <textarea name="review_notes" rows="2" maxlength="2000" class="w-full rounded-lg border border-green-200 p-2 text-sm" placeholder="Basis of verification"></textarea>
                                <button type="submit" class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-bold text-white" onclick="return confirm('Confirm manual attendance approval?')">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('staff.attendance-exceptions.review', $exception->exception_id) }}" class="space-y-3 rounded-xl border border-red-200 bg-red-50 p-4">
                                @csrf @method('PATCH')
                                <input type="hidden" name="decision" value="Rejected">
                                <p class="font-bold text-red-900">Reject Request</p>
                                <p class="text-xs text-red-900">Rejection does not create an attendance record.</p>
                                <label class="block text-xs font-semibold text-red-900">Reason for Rejection (required)</label>
                                <textarea name="review_notes" rows="3" maxlength="2000" required class="w-full rounded-lg border border-red-200 p-2 text-sm" placeholder="Explain why the request was rejected..."></textarea>
                                <button type="submit" class="rounded-lg bg-red-700 px-5 py-2.5 text-sm font-bold text-white" onclick="return confirm('Reject this attendance exception?')">Reject</button>
                            </form>
                        </div>
                    @else
                        <div class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-600">
                            <span class="font-bold">Review:</span> {{ $exception->review_notes ?: 'No note provided.' }}
                            <span class="ml-2 text-xs text-slate-500">{{ $exception->reviewer?->name }} · {{ $exception->reviewed_at?->format('M d, Y g:i A') }}</span>
                            @if ($exception->attendance_id)<p class="mt-2 text-green-800">Manual attendance saved as record #{{ $exception->attendance_id }}.</p>@endif
                        </div>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">No attendance exception reports match your filter.</div>
            @endforelse
            <div>{{ $exceptions->links() }}</div>
        </section>
    </div>
</x-admin-layout>
