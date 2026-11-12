<x-admin-layout>
    <style>
        .module-seven-hero {
            background: linear-gradient(100deg, rgba(16,16,100,.97), rgba(16,16,100,.88) 57%, rgba(16,16,100,.75)),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
    </style>

    <div class="min-w-0 space-y-7">
        <section class="module-seven-hero relative overflow-hidden rounded-[28px] px-7 py-8 text-white sm:px-8 lg:px-10 lg:py-10">
            <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>
            <a href="{{ route('events.registrations.index') }}" class="text-sm font-semibold text-[#E7C75B] hover:text-white">← Back to Event Registration</a>
            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.30em] text-[#E7C75B]">Module #7 · Participant Management</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">{{ $managedEvent->event_name }}</h1>
            <p class="mt-3 text-sm leading-6 text-white/75">{{ $managedEvent->event_date->format('M d, Y') }} · {{ $managedEvent->location }} · {{ $managedEvent->status }}</p>
        </section>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                @foreach ($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registered participants</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ $registeredCount }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Eligible students</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ $eligibleCount }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registration status</p>
                <p class="mt-3 font-bold {{ $canEdit ? 'text-emerald-700' : 'text-slate-500' }}">{{ $canEdit ? 'Open' : 'Closed' }}</p>
            </div>
        </div>

        @if ($canEdit)
            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                <h2 class="text-lg font-bold text-[#101064]">Register Eligible Student</h2>
                <p class="mt-1 text-sm text-slate-500">Enter the student's exact school-issued student number. Eligibility is checked automatically.</p>
                <form action="{{ route('events.registrations.store', $managedEvent->event_id) }}" method="POST" class="mt-4 flex flex-col gap-3 sm:flex-row">
                    @csrf
                    <label class="flex-1">
                        <span class="sr-only">Student Number</span>
                        <input type="text" name="student_number" value="{{ old('student_number') }}" maxlength="100" required placeholder="Student Number" class="w-full rounded-xl border-gray-300 text-sm focus:border-[#101064] focus:ring-[#101064]">
                    </label>
                    <button type="submit" class="rounded-xl bg-[#D4A017] px-6 py-3 text-sm font-bold text-[#101064] transition hover:bg-[#E7C75B]">Add Participant</button>
                </form>
            </section>
        @else
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900">Registration changes are closed because this event is no longer upcoming.</div>
        @endif

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-200 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <h2 class="text-lg font-bold text-[#101064]">Registered Participant List</h2>
                    <p class="mt-1 text-sm text-slate-500">Students currently registered for this event.</p>
                </div>
                <form method="GET" action="{{ route('events.registrations.show', $managedEvent->event_id) }}" class="flex gap-2">
                    <input type="search" name="search" value="{{ $search }}" maxlength="100" placeholder="Name or student number" class="w-full rounded-xl border-gray-300 text-sm focus:border-[#101064] focus:ring-[#101064] sm:w-64">
                    <button type="submit" class="rounded-xl bg-[#101064] px-4 py-2 text-sm font-semibold text-white">Search</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Student Number</th>
                            <th class="px-5 py-4">Student Name</th>
                            <th class="px-5 py-4">College / Program</th>
                            <th class="px-5 py-4">Registered At</th>
                            <th class="px-5 py-4">Registered By</th>
                            <th class="px-5 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($participants as $registration)
                            <tr class="align-top text-slate-700">
                                <td class="whitespace-nowrap px-5 py-4 font-semibold">{{ $registration->student?->student_number ?? '—' }}</td>
                                <td class="px-5 py-4">{{ $registration->student?->last_name }}, {{ $registration->student?->first_name }}</td>
                                <td class="px-5 py-4">{{ $registration->student?->college ?? '—' }}<br><span class="text-xs text-slate-500">{{ $registration->student?->program_code ?? '—' }}</span></td>
                                <td class="whitespace-nowrap px-5 py-4">{{ $registration->registered_at?->format('M d, Y g:i A') ?? '—' }}</td>
                                <td class="px-5 py-4">{{ $registration->registeredBy?->name ?? $registration->source }}</td>
                                <td class="px-5 py-4">
                                    @if ($canEdit)
                                        <form method="POST" action="{{ route('events.registrations.cancel', [$managedEvent->event_id, $registration->registration_id]) }}" onsubmit="return confirm('Remove this participant registration?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-red-700 hover:underline">Unregister</button>
                                        </form>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-12 text-center text-slate-500">No registered participants found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($participants->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">{{ $participants->links() }}</div>
            @endif
        </section>
    </div>
</x-admin-layout>
