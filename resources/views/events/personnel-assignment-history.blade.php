<x-admin-layout>
<style>.dy-assign-history-hero{background-image:linear-gradient(102deg,rgba(12,13,79,.97),rgba(16,16,100,.83)),url('{{ asset('images/school.jpg') }}');background-position:center;background-size:cover}</style>
<div class="space-y-6 pb-8">
    <section class="dy-assign-history-hero relative overflow-hidden rounded-[28px] bg-[#101064] px-7 py-8 text-white sm:px-9 sm:py-10">
        <div class="pointer-events-none absolute -right-16 -top-24 h-80 w-80 rounded-full border border-white/10"></div>
        <div class="pointer-events-none absolute -right-4 -top-12 h-60 w-60 rounded-full border border-white/10"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-4">
            <div><p class="text-[11px] font-bold uppercase tracking-[.25em] text-[#E7C75B]">Personnel Assignment / Historical Review</p><h1 class="mt-3 text-3xl font-bold">{{ $event->event_name }}</h1><p class="mt-3 text-sm text-white/75">Personnel assigned to this event. Historical and active assignments cannot be changed here.</p><div class="mt-5 flex flex-wrap gap-2 text-xs"><span class="rounded-full border border-white/20 bg-white/10 px-3 py-2">{{ $event->status }}</span><span class="rounded-full border border-white/20 bg-white/10 px-3 py-2">{{ $event->department?->department_name ?? 'Department' }}</span></div></div>
            <a href="{{ route('events.personnel-assignment.events') }}" class="rounded-xl bg-[#D4A017] px-5 py-3 text-sm font-bold text-[#101064] hover:bg-yellow-300">← Back to Events</a>
        </div>
    </section>
    <div class="grid gap-3 sm:grid-cols-3">
        @foreach([
            ['Event Date', $event->event_date?->format('F j, Y') ?? '—'],
            ['Schedule', ($event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : '—').' – '.($event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : '—')],
            ['Assigned Personnel', $event->assignments->count()],
        ] as $detail)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ $detail[0] }}</p><p class="mt-2 text-lg font-bold text-[#101064]">{{ $detail[1] }}</p></div>
        @endforeach
    </div>
    <section>
        <div class="mb-4"><p class="text-[11px] font-bold uppercase tracking-[.25em] text-[#C18D11]">Assignment History</p><h2 class="mt-2 text-lg font-bold text-[#101064]">Assigned Attendance Personnel</h2><p class="mt-1 text-sm text-slate-500">Review the saved roster for {{ $event->location ?: 'this event' }}. No editing is permitted after the event starts.</p></div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @forelse($event->assignments as $assignment)
                @php($person = $assignment->personnel)
                <div class="flex items-center gap-4 border-b border-slate-100 px-5 py-5 last:border-b-0">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-lg font-bold text-[#101064]">{{ strtoupper(substr((string) ($person?->first_name ?? 'P'),0,1)) }}</span>
                    <div class="min-w-0 flex-1"><p class="font-semibold text-[#101064]">{{ trim(($person?->first_name ?? 'Former').' '.($person?->last_name ?? 'Personnel')) }}</p><p class="mt-1 text-xs text-slate-500">{{ $person?->position ?: 'Attendance Personnel' }}</p></div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Assigned</span>
                </div>
            @empty
                <div class="px-6 py-16 text-center"><p class="font-bold text-[#101064]">No personnel recorded</p><p class="mt-2 text-sm text-slate-500">No attendance personnel assignment was saved for this event.</p></div>
            @endforelse
        </div>
    </section>
</div>
</x-admin-layout>
