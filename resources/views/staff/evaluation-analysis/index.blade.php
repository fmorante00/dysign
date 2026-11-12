<x-admin-layout>
    <div class="min-w-0 space-y-7">
        <section class="relative overflow-hidden rounded-[28px] bg-[#101064] px-7 py-8 text-white shadow-sm sm:px-8 lg:px-10">
            <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>
            <p class="text-xs font-semibold uppercase tracking-[0.30em] text-[#E7C75B]">Module #18 · Department Staff</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">Event Evaluation Analysis</h1>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-white/70">View response counts, ratings, feedback, and questionnaire summaries for your department's events.</p>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-100 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-[#101064]">Evaluation Forms</h2>
                    <p class="mt-1 text-sm text-gray-500">Select a questionnaire to see its analysis.</p>
                </div>
                <form method="GET" action="{{ route('staff.evaluation-analysis.index') }}" class="flex flex-wrap gap-2">
                    <label for="search" class="sr-only">Search evaluations</label>
                    <input id="search" name="search" value="{{ $search }}" placeholder="Search form or event" class="w-64 max-w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:border-[#101064] focus:ring-[#101064]">
                    <button class="rounded-xl bg-[#101064] px-5 py-2 text-sm font-semibold text-white hover:bg-[#24247c]">Search</button>
                </form>
            </div>
            @forelse ($forms as $form)
                <div class="flex flex-col gap-3 border-b border-gray-100 px-6 py-5 last:border-b-0 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="text-base font-bold text-[#101064]">{{ $form->title }}</p>
                        <p class="mt-1 text-sm text-gray-600">{{ $form->event?->event_name ?? 'Event unavailable' }} · {{ $form->event?->event_date?->format('M d, Y') ?? 'No date' }}</p>
                        <p class="mt-2 text-xs text-gray-500">Status: {{ $form->status }} · Responses: {{ $form->submissions_count }}</p>
                    </div>
                    <a href="{{ route('staff.evaluation-analysis.show', $form->evaluation_form_id) }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[#D4A017] px-5 py-2.5 text-sm font-semibold text-[#101064] hover:bg-[#e5b326]">View Analysis</a>
                </div>
            @empty
                <div class="p-10 text-center text-sm text-gray-500">No evaluation forms found for your department.</div>
            @endforelse
        </section>
        <div>{{ $forms->links() }}</div>
    </div>
</x-admin-layout>
