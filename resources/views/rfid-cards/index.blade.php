<x-admin-layout>
    <div class="space-y-7 p-1">
        <section class="overflow-hidden rounded-3xl bg-[#101064] text-white">
            <div class="p-7 md:p-9">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#D4A017]">System Administrator · RFID Security</p>
                <h1 class="mt-2 text-3xl font-bold">RFID Card Lifecycle Management</h1>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-white/75">
                    Report lost cards, issue replacement RFID identifiers, and review historical assignments without modifying attendance history.
                </p>
            </div>
            <div class="h-1 bg-[#D4A017]"></div>
        </section>

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-800">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">
                <p class="font-semibold text-red-800">Unable to complete RFID request</p>
                @foreach($errors->all() as $error)
                    <p class="mt-1 text-sm text-red-700">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Active RFID Cards</p>
                <p class="mt-3 text-3xl font-extrabold text-[#101064]">{{ number_format($counts['active']) }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Lost RFID Cards</p>
                <p class="mt-3 text-3xl font-extrabold text-red-600">{{ number_format($counts['lost']) }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Replaced RFID Cards</p>
                <p class="mt-3 text-3xl font-extrabold text-amber-600">{{ number_format($counts['replaced']) }}</p>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <div class="flex flex-col justify-between gap-4 border-b border-gray-100 px-6 py-5 md:flex-row md:items-center">
                <div>
                    <h2 class="text-xl font-bold text-[#101064]">Student RFID Registry</h2>
                    <p class="mt-1 text-sm text-gray-500">Only the Administrator can disable or replace RFID assignments.</p>
                </div>
                <form method="GET" action="{{ route('rfid-cards.index') }}" class="flex flex-wrap gap-2">
                    <input name="search" value="{{ $search }}" placeholder="Student number, name, or program"
                           class="w-64 max-w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#101064] focus:ring-[#101064]">
                    <button class="rounded-lg bg-[#101064] px-4 py-2 text-sm font-semibold text-white">Search</button>
                    @if($search !== '')
                        <a href="{{ route('rfid-cards.index') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Clear</a>
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-4">Student</th>
                            <th class="px-5 py-4">Program</th>
                            <th class="px-5 py-4">Current RFID UID</th>
                            <th class="px-5 py-4">Card Status</th>
                            <th class="px-5 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                        @php
                            $card = $latestCards->get($student->student_id);
                            $inSync = $card && $card->rfid_identifier === $student->rfid_identifier;
                        @endphp
                        <tr class="align-top hover:bg-gray-50">
                            <td class="px-5 py-5">
                                <p class="text-sm font-bold text-[#101064]">{{ trim($student->first_name.' '.($student->middle_name ?? '').' '.$student->last_name) }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $student->student_number }}</p>
                            </td>
                            <td class="px-5 py-5 text-sm text-gray-600">{{ $student->program_code }}</td>
                            <td class="px-5 py-5 font-mono text-sm text-gray-700">{{ $student->rfid_identifier }}</td>
                            <td class="px-5 py-5">
                                @if(!$inSync)
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">Needs review</span>
                                @elseif($card->status === 'Active')
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Active</span>
                                @elseif($card->status === 'Lost')
                                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">Lost — blocked</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">{{ $card->status }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($inSync && in_array($card->status, ['Active', 'Lost'], true))
                                    <div class="flex flex-wrap items-start gap-2">
                                        @if($card->status === 'Active')
                                            <details class="group">
                                                <summary class="cursor-pointer list-none rounded-lg border border-red-300 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-50">Report Lost</summary>
                                                <form method="POST" action="{{ route('rfid-cards.lost', $student->student_id) }}"
                                                      class="mt-2 w-64 rounded-xl border border-red-100 bg-red-50 p-3"
                                                      onsubmit="return confirm('Disable this RFID card immediately? Attendance history will remain unchanged.');">
                                                    @csrf
                                                    <label class="text-xs font-semibold text-gray-700">Reason for loss</label>
                                                    <textarea name="reason" required minlength="3" maxlength="500" rows="2" placeholder="Reported lost card" class="mt-1 w-full rounded-lg border border-gray-300 p-2 text-xs"></textarea>
                                                    <button class="mt-2 rounded-lg bg-red-700 px-3 py-2 text-xs font-bold text-white">Confirm Lost Card</button>
                                                </form>
                                            </details>
                                        @endif
                                        <details class="group">
                                            <summary class="cursor-pointer list-none rounded-lg border border-[#D4A017] px-3 py-2 text-xs font-bold text-[#101064] hover:bg-[#FFF9E7]">Replace RFID</summary>
                                            <form method="POST" action="{{ route('rfid-cards.replace', $student->student_id) }}"
                                                  class="mt-2 w-64 rounded-xl border border-amber-200 bg-[#FFF9E7] p-3"
                                                  onsubmit="return confirm('Assign this NEW UID and disable the previous RFID card?');">
                                                @csrf
                                                <label class="text-xs font-semibold text-gray-700">New card UID (10 digits)</label>
                                                <input name="rfid_identifier" required inputmode="numeric" pattern="[0-9]{10}" maxlength="10" placeholder="0000000000"
                                                       class="mt-1 w-full rounded-lg border border-gray-300 p-2 font-mono text-sm">
                                                <label class="mt-2 block text-xs font-semibold text-gray-700">Replacement reason</label>
                                                <textarea name="reason" required minlength="3" maxlength="500" rows="2" placeholder="Lost, damaged, or reissued" class="mt-1 w-full rounded-lg border border-gray-300 p-2 text-xs"></textarea>
                                                <button class="mt-2 rounded-lg bg-[#101064] px-3 py-2 text-xs font-bold text-white">Confirm Replacement</button>
                                            </form>
                                        </details>
                                    </div>
                                @else
                                    <details>
                                        <summary class="cursor-pointer text-xs font-semibold text-amber-700">Review / Approve Imported UID</summary>
                                        <form method="POST" action="{{ route('rfid-cards.approve', $student->student_id) }}"
                                              class="mt-2 w-64 rounded-xl border border-amber-200 bg-[#FFF9E7] p-3"
                                              onsubmit="return confirm('Approve this imported RFID as the student’s active card? Any previous active card will be disabled.');">
                                            @csrf
                                            <p class="text-xs text-gray-600">Verify the physical school card first. Old lost/replaced UIDs cannot be approved.</p>
                                            <textarea name="reason" required minlength="3" maxlength="500" rows="2" placeholder="Reason for approving imported UID" class="mt-2 w-full rounded-lg border border-gray-300 p-2 text-xs"></textarea>
                                            <button class="mt-2 rounded-lg bg-[#101064] px-3 py-2 text-xs font-bold text-white">Approve RFID</button>
                                        </form>
                                    </details>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No matching students found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-100 px-6 py-4">{{ $students->links() }}</div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-xl font-bold text-[#101064]">RFID Assignment History</h2>
                <p class="mt-1 text-sm text-gray-500">The original UID remains recorded after a card is reported lost or replaced.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3">Student</th>
                            <th class="px-5 py-3">RFID UID</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Reason</th>
                            <th class="px-5 py-3">Updated</th>
                            <th class="px-5 py-3">Processed By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($history as $entry)
                        <tr class="text-sm text-gray-700">
                            <td class="px-5 py-4">{{ $entry->student?->student_number ?? 'Unknown' }}</td>
                            <td class="px-5 py-4 font-mono">{{ $entry->rfid_identifier }}</td>
                            <td class="px-5 py-4">{{ $entry->status }}</td>
                            <td class="px-5 py-4">{{ $entry->reason ?: '—' }}</td>
                            <td class="px-5 py-4 text-xs">{{ $entry->updated_at?->format('M d, Y h:i A') }}</td>
                            <td class="px-5 py-4 text-xs">{{ $entry->endedBy?->name ?? $entry->assignedBy?->name ?? 'System import' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No RFID assignment history yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-admin-layout>
