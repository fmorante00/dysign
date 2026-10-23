<x-admin-layout>
    <div class="min-w-0 space-y-6 pb-10">
        <section class="rounded-[26px] border-b-4 border-[#D4A017] bg-[#101064] px-6 py-8 text-white shadow-sm sm:px-9">
            <p class="text-xs font-semibold uppercase tracking-[0.23em] text-[#E7C75B]">DySign · Student Account</p>
            <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Attendance Discrepancy Requests</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">
                Report missing or incorrect event attendance. Your request will be reviewed by Attendance Personnel assigned to the event.
            </p>
        </section>

        @if(session('success'))
            <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <p class="font-semibold">Please check your request:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1fr_1.15fr]">
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-[#101064]">Submit a Verification Request</h2>
                    <p class="mt-1 text-xs text-slate-500">Student No. {{ $student->student_number }} · {{ $student->first_name }} {{ $student->last_name }}</p>
                </div>

                <form method="POST" action="{{ route('attendance-discrepancies.student.store') }}" class="space-y-5 p-6">
                    @csrf
                    <div>
                        <label for="event_id" class="mb-2 block text-sm font-semibold text-slate-700">Event <span class="text-red-600">*</span></label>
                        <select id="event_id" name="event_id" required class="w-full rounded-lg border-slate-300 text-sm focus:border-[#101064] focus:ring-[#101064]">
                            <option value="">Select a current or past event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->event_id }}" @selected(old('event_id') == $event->event_id)>
                                    {{ $event->event_name }} — {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('M d, Y') }}
                                    {{ $attendanceByEvent->has($event->event_id) ? '(Recorded)' : '(No recorded attendance)' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-slate-500">Only the most recent 100 current or past events appear. Your request is checked against your own attendance.</p>
                    </div>

                    <div>
                        <label for="request_type" class="mb-2 block text-sm font-semibold text-slate-700">Issue Type <span class="text-red-600">*</span></label>
                        <select id="request_type" name="request_type" required class="w-full rounded-lg border-slate-300 text-sm focus:border-[#101064] focus:ring-[#101064]">
                            <option value="">Select issue type</option>
                            <option value="Missing Attendance" @selected(old('request_type') === 'Missing Attendance')>Missing Attendance — no record was found</option>
                            <option value="Incorrect Attendance" @selected(old('request_type') === 'Incorrect Attendance')>Incorrect Attendance — a record exists but is wrong</option>
                        </select>
                    </div>

                    <div>
                        <label for="expected_time_in" class="mb-2 block text-sm font-semibold text-slate-700">Expected Time-In <span class="font-normal text-slate-400">(optional)</span></label>
                        <input id="expected_time_in" name="expected_time_in" type="datetime-local" value="{{ old('expected_time_in') }}"
                               class="w-full rounded-lg border-slate-300 text-sm focus:border-[#101064] focus:ring-[#101064]">
                        <p class="mt-2 text-xs text-slate-500">Provide an approximate time if known. This is a claim, not proof of attendance.</p>
                    </div>

                    <div>
                        <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Explanation <span class="text-red-600">*</span></label>
                        <textarea id="description" name="description" minlength="15" maxlength="2000" rows="5" required
                                  placeholder="Describe what happened, including any evidence or witnesses..."
                                  class="w-full rounded-lg border-slate-300 text-sm focus:border-[#101064] focus:ring-[#101064]">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-[#101064] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#202080]">
                        Submit Discrepancy Request
                    </button>
                </form>
            </section>

            <section class="min-w-0 rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-[#101064]">My Requests</h2>
                    <p class="mt-1 text-xs text-slate-500">Review statuses and Attendance Personnel's decisions.</p>
                </div>

                <div class="space-y-3 p-5">
                    @forelse($requests as $item)
                        @php
                            $badge = match($item->status) {
                                'Approved' => 'bg-emerald-50 text-emerald-800',
                                'Rejected' => 'bg-red-50 text-red-800',
                                default => 'bg-amber-50 text-amber-800',
                            };
                        @endphp
                        <article class="rounded-xl border border-slate-200 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $item->event?->event_name ?? 'Event unavailable' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">#{{ $item->discrepancy_request_id }} · {{ $item->request_type }} · {{ $item->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $badge }}">{{ $item->status }}</span>
                            </div>
                            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $item->description }}</p>
                            @if($item->expected_time_in)
                                <p class="mt-2 text-xs text-slate-500">Requested time: {{ $item->expected_time_in->format('M d, Y h:i A') }}</p>
                            @endif
                            @if($item->status !== 'Pending')
                                <div class="mt-4 rounded-lg bg-slate-50 p-3 text-sm">
                                    <p class="font-semibold text-[#101064]">Review outcome</p>
                                    <p class="mt-1 whitespace-pre-line text-slate-700">{{ $item->resolution_notes ?: 'No remarks provided.' }}</p>
                                    @if($item->status === 'Approved' && $item->verified_time_in)
                                        <p class="mt-2 text-xs font-medium text-slate-600">Verified time: {{ $item->verified_time_in->format('M d, Y h:i A') }}</p>
                                    @endif
                                </div>
                            @endif
                        </article>
                    @empty
                        <p class="py-10 text-center text-sm text-slate-500">You have not submitted any discrepancy requests.</p>
                    @endforelse
                </div>
                @if($requests->hasPages())
                    <div class="border-t border-slate-100 px-5 py-4">{{ $requests->links() }}</div>
                @endif
            </section>
        </div>
        <p class="text-xs leading-5 text-slate-500">An approved request records a verified review outcome. Original RFID scans remain unchanged; automatic attendance report corrections are not part of this version.</p>
    </div>
</x-admin-layout>
