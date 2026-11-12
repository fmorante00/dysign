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
            <p class="text-xs font-semibold uppercase tracking-[0.30em] text-[#E7C75B]">Module #7 · Department Staff</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">Event Registration Management</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">Manage registrations for events organized by your department.</p>
        </section>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Department events</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ $events->count() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total registrations</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ $counts->sum() }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Upcoming events</p>
                <p class="mt-2 text-3xl font-bold text-[#101064]">{{ $events->where('status', 'Upcoming')->count() }}</p>
            </div>
        </div>

        <section class="space-y-4">
            <h2 class="text-xl font-bold text-slate-900">Select an Event</h2>
            @forelse ($events as $event)
                <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="space-y-2">
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-full bg-[#101064]/10 px-3 py-1 text-xs font-semibold text-[#101064]">{{ $event->status }}</span>
                                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">{{ (int) ($counts[$event->event_id] ?? 0) }} Registered</span>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $event->eligible_students_count }} Eligible</span>
                            </div>
                            <h3 class="text-lg font-bold text-[#101064]">{{ $event->event_name }}</h3>
                            <p class="text-sm text-slate-600">{{ $event->event_date->format('M d, Y') }} · {{ $event->location }}</p>
                        </div>
                        <a href="{{ route('events.registrations.show', $event->event_id) }}" class="inline-flex items-center justify-center rounded-xl bg-[#101064] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1c1c83]">View Participants</a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white px-6 py-12 text-center text-sm text-slate-500 shadow-sm">No events for your department yet.</div>
            @endforelse
        </section>
    </div>
</x-admin-layout>
