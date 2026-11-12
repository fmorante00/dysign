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
            <p class="text-xs font-semibold uppercase tracking-[0.30em] text-[#E7C75B]">Module #7 · Student Account</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">My Event Registrations</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">Register for events that you are eligible to attend, and view your registration status.</p>
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
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Eligible events</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ $events->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registered</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ $registrations->where('status', 'Registered')->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Upcoming</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ $events->where('status', 'Upcoming')->count() }}</p>
            </div>
        </div>

        <section class="space-y-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Available Events</h2>
                <p class="mt-1 text-sm text-slate-500">Only events in your eligible-student list are shown.</p>
            </div>

            @forelse ($events as $event)
                @php
                    $registration = $registrations->get($event->event_id);
                    $participationType = $eligibilities->get($event->event_id)?->participation_type ?? 'Optional';
                    $canRegister = $event->calculateScheduledStatus() === 'Upcoming';
                @endphp
                <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0 space-y-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full bg-[#101064]/10 px-3 py-1 text-xs font-semibold text-[#101064]">{{ $participationType }}</span>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $event->status }}</span>
                                @if ($registration?->status === 'Registered')
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Registered</span>
                                @elseif ($registration?->status === 'Cancelled')
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Registration cancelled</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">Not registered</span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-[#101064]">{{ $event->event_name }}</h3>
                            @if ($event->description)
                                <p class="max-w-2xl text-sm leading-6 text-slate-600">{{ $event->description }}</p>
                            @endif
                            <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-600">
                                <span>📅 {{ $event->event_date->format('M d, Y') }}</span>
                                <span>🕒 {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</span>
                                <span>📍 {{ $event->location }}</span>
                                <span>🏫 {{ $event->department?->department_name ?? 'School Event' }}</span>
                            </div>
                        </div>
                        <div class="w-full shrink-0 lg:w-48">
                            @if (!$canRegister)
                                <div class="rounded-xl bg-slate-100 px-4 py-3 text-center text-sm font-semibold text-slate-500">Registration closed</div>
                            @elseif ($registration?->status === 'Registered')
                                <form action="{{ route('student.registrations.cancel', $event->event_id) }}" method="POST" onsubmit="return confirm('Cancel your registration?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-100">Cancel Registration</button>
                                </form>
                            @else
                                <form action="{{ route('student.registrations.store', $event->event_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full rounded-xl bg-[#D4A017] px-4 py-3 text-sm font-bold text-[#101064] transition hover:bg-[#E7C75B]">Register Now</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white px-6 py-12 text-center shadow-sm">
                    <p class="font-semibold text-slate-700">No eligible events yet.</p>
                    <p class="mt-1 text-sm text-slate-500">Please check again when your department publishes event eligibility.</p>
                </div>
            @endforelse
        </section>
    </div>
</x-admin-layout>
