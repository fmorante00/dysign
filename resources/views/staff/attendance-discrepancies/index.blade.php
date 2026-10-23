<x-admin-layout>
    <div class="min-w-0 space-y-6 pb-10">
        <section class="rounded-[26px] border-b-4 border-[#D4A017] bg-[#101064] px-6 py-8 text-white shadow-sm sm:px-9">
            <p class="text-xs font-semibold uppercase tracking-[0.23em] text-[#E7C75B]">DySign · Attendance Personnel</p>
            <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Attendance Discrepancy Review</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">Review attendance concerns for events assigned to you. Record a decision and verified time without rewriting original RFID scan evidence.</p>
        </section>

        @if(session('success'))
            <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <p class="font-semibold">Unable to complete review:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Requests</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ number_format($counts['all']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pending Review</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ number_format($counts['pending']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Resolved</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format($counts['resolved']) }}</p>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold text-[#101064]">Assigned Event Requests</h2>
                    <p class="mt-1 text-xs text-slate-500">You may review requests only for your assigned events.</p>
                </div>
                <form method="GET" action="{{ route('attendance-discrepancies.staff.index') }}">
                    <label for="status" class="sr-only">Filter by status</label>
                    <select id="status" name="status" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        @foreach(['All', 'Pending', 'Approved', 'Rejected'] as $option)
                            <option value="{{ $option }}" @selected($status === $option)>{{ $option }} Requests</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="space-y-4 p-5 sm:p-6">
                @forelse($requests as $item)
                    @php
                        $badge = match($item->status) {
                            'Approved' => 'bg-emerald-50 text-emerald-800',
                            'Rejected' => 'bg-red-50 text-red-800',
                            default => 'bg-amber-50 text-amber-800',
                        };
                    @endphp
                    <article class="min-w-0 rounded-xl border border-slate-200 p-4 sm:p-5">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-base font-bold text-[#101064]">{{ $item->student?->first_name }} {{ $item->student?->last_name }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $item->student?->student_number }} · {{ $item->student?->program_code }} · Request #{{ $item->discrepancy_request_id }}</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">{{ $item->event?->event_name ?? 'Event unavailable' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $item->request_type }} · Submitted {{ $item->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $badge }}">{{ $item->status }}</span>
                        </div>

                        <div class="mt-4 grid gap-4 rounded-lg bg-slate-50 p-4 text-sm text-slate-700 md:grid-cols-2">
                            <div>
                                <p class="text-xs font-bold uppercase text-slate-500">Student's Explanation</p>
                                <p class="mt-2 whitespace-pre-line leading-6">{{ $item->description }}</p>
                                @if($item->expected_time_in)
                                    <p class="mt-2 text-xs">Student-reported time: {{ $item->expected_time_in->format('M d, Y h:i A') }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase text-slate-500">Original Attendance Snapshot</p>
                                @if($item->attendance_id)
                                    <p class="mt-2">Recorded status: <strong>{{ $item->original_attendance_status ?: '—' }}</strong></p>
                                    <p class="mt-1">Time-in: {{ $item->original_time_in?->format('M d, Y h:i A') ?: '—' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">Record ID: {{ $item->attendance_id }}</p>
                                @else
                                    <p class="mt-2">No original attendance record was linked at submission.</p>
                                @endif
                            </div>
                        </div>

                        @if($item->status === 'Pending')
                            <details class="mt-4 rounded-xl border border-indigo-100 bg-indigo-50/50 p-4">
                                <summary class="cursor-pointer text-sm font-semibold text-[#101064]">Review Request</summary>
                                <form method="POST" action="{{ route('attendance-discrepancies.staff.review', $item->discrepancy_request_id) }}" class="mt-4 space-y-4">
                                    @csrf
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label for="decision-{{ $item->discrepancy_request_id }}" class="mb-2 block text-sm font-semibold text-slate-700">Decision *</label>
                                            <select id="decision-{{ $item->discrepancy_request_id }}" name="decision" required class="w-full rounded-lg border-slate-300 text-sm">
                                                <option value="">Select decision</option>
                                                <option value="Approved">Approve — verified discrepancy</option>
                                                <option value="Rejected">Reject — not verified</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="verified-{{ $item->discrepancy_request_id }}" class="mb-2 block text-sm font-semibold text-slate-700">Verified Time-In (required for approval)</label>
                                            <input id="verified-{{ $item->discrepancy_request_id }}" type="datetime-local" name="verified_time_in" class="w-full rounded-lg border-slate-300 text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="notes-{{ $item->discrepancy_request_id }}" class="mb-2 block text-sm font-semibold text-slate-700">Resolution Notes *</label>
                                        <textarea id="notes-{{ $item->discrepancy_request_id }}" name="resolution_notes" required minlength="10" maxlength="2000" rows="3"
                                                  placeholder="Explain how you verified this request and what evidence was checked..."
                                                  class="w-full rounded-lg border-slate-300 text-sm"></textarea>
                                    </div>
                                    <button type="submit" class="rounded-lg bg-[#101064] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#202080]">Save Review Decision</button>
                                </form>
                            </details>
                        @else
                            <div class="mt-4 border-t border-slate-100 pt-4 text-sm">
                                <p class="font-semibold text-[#101064]">Resolution</p>
                                <p class="mt-1 whitespace-pre-line text-slate-700">{{ $item->resolution_notes }}</p>
                                @if($item->verified_time_in)
                                    <p class="mt-2 text-xs text-slate-500">Verified time: {{ $item->verified_time_in->format('M d, Y h:i A') }}</p>
                                @endif
                                <p class="mt-2 text-xs text-slate-500">Reviewed by {{ $item->reviewer?->name ?? 'Former personnel' }} on {{ $item->reviewed_at?->format('M d, Y h:i A') ?? '—' }}</p>
                            </div>
                        @endif
                    </article>
                @empty
                    <p class="py-12 text-center text-sm text-slate-500">No discrepancy requests found for your assigned events.</p>
                @endforelse
            </div>
            @if($requests->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">{{ $requests->links() }}</div>
            @endif
        </section>
        <p class="text-xs leading-5 text-slate-500">Approving a request records verification, not a new RFID tap. The original scanner attendance table is unchanged; report corrections require a separately authorized integration.</p>
    </div>
</x-admin-layout>
