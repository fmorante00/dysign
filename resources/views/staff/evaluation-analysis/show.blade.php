<x-admin-layout>
    <div class="min-w-0 space-y-7">
        <section class="relative overflow-hidden rounded-[28px] bg-[#101064] px-7 py-8 text-white shadow-sm sm:px-8 lg:px-10">
            <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>
            <a class="text-xs font-semibold text-[#E7C75B] hover:underline" href="{{ route('staff.evaluation-analysis.index') }}">← Back to Evaluation Analysis</a>
            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.30em] text-[#E7C75B]">Module #18 · Questionnaire Summary</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">{{ $form->title }}</h1>
            <p class="mt-3 text-sm text-white/70">{{ $form->event?->event_name }} · {{ $form->event?->event_date?->format('M d, Y') }} · {{ $form->status }}</p>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'Submitted Responses', 'value' => $submissionCount],
                ['label' => 'Eligible Students', 'value' => $eligibleCount],
                ['label' => 'Response Rate', 'value' => $responseRate === null ? 'N/A' : $responseRate . '%'],
                ['label' => 'Average Rating', 'value' => $overallRating === null ? 'N/A' : $overallRating],
            ] as $metric)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $metric['label'] }}</p>
                    <p class="mt-3 text-3xl font-bold text-[#101064]">{{ $metric['value'] }}</p>
                </div>
            @endforeach
        </section>
        <p class="text-xs text-gray-500">Response rate = submitted responses ÷ current eligible students. Ratings average all answered rating questions. Blank or unanswered questions are excluded from the per-question percentages. Multiple-choice percentages may total over 100%.</p>

        @forelse ($results as $index => $result)
            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.20em] text-[#D4A017]">Question {{ $index + 1 }} · {{ $result['question']->question_type }}</p>
                    <h2 class="mt-2 text-lg font-bold text-[#101064]">{{ $result['question']->question_text }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $result['answered'] }} answered out of {{ $submissionCount }} submissions
                        @if ($result['average'] !== null) · Average rating: <strong class="text-[#101064]">{{ $result['average'] }}</strong> @endif
                    </p>
                </div>
                <div class="space-y-4 px-6 py-6">
                    @if (count($result['distribution']))
                        @foreach ($result['distribution'] as $choice)
                            <div>
                                <div class="mb-1 flex items-center justify-between gap-4 text-sm">
                                    <span class="font-medium text-gray-700">{{ $choice['label'] }}</span>
                                    <span class="shrink-0 font-semibold text-[#101064]">{{ $choice['count'] }} ({{ $choice['percent'] }}%)</span>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-gray-100">
                                    <div class="h-full rounded-full bg-[#D4A017]" style="width: {{ min(100, $choice['percent']) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @elseif (in_array($result['question']->question_type, ['Short Answer', 'Paragraph']))
                        @forelse ($result['comments'] as $comment)
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-sm leading-6 text-gray-700 whitespace-pre-wrap break-words">{{ $comment }}</div>
                        @empty
                            <p class="text-sm text-gray-500">No written responses yet.</p>
                        @endforelse
                        @if ($result['answered'] > count($result['comments']))
                            <p class="text-xs text-gray-500">Showing the first {{ count($result['comments']) }} written responses.</p>
                        @endif
                    @else
                        <p class="text-sm text-gray-500">No response options or answers are available for this question yet.</p>
                    @endif
                </div>
            </section>
        @empty
            <section class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500">This evaluation form has no questions yet.</section>
        @endforelse
    </div>
</x-admin-layout>
