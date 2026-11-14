<x-admin-layout>
    <style>
        .ea-detail{width:100%;min-width:0;padding-bottom:28px;color:#101064}.ea-detail-hero{position:relative;isolation:isolate;overflow:hidden;border-radius:28px;color:white;background:linear-gradient(100deg,rgba(16,16,100,.97),rgba(16,16,100,.9) 50%,rgba(16,16,100,.71)),url('{{ asset('images/school.jpg') }}') center/cover}
        .ea-detail-hero:after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;background:#d4a017;z-index:2}
        .ea-detail-circle{position:absolute;border:2px solid rgba(255,255,255,.24);border-radius:50%;width:410px;height:410px;top:-150px;right:-55px;pointer-events:none}.ea-detail-circle:nth-child(2){width:300px;height:300px;top:-95px;right:0}.ea-detail-circle:nth-child(3){width:185px;height:185px;top:-36px;right:58px}
        .ea-detail-inner{position:relative;z-index:1;padding:32px 36px;min-height:190px;display:flex;justify-content:space-between;gap:20px;align-items:center}
        .ea-detail-back{display:inline-block;color:#ecd078;text-decoration:none;font-size:12px;font-weight:800;margin-bottom:19px}.ea-detail-back:hover{text-decoration:underline}.ea-detail-kicker{font-size:10px;font-weight:800;letter-spacing:.19em;text-transform:uppercase;color:#ecc45b}.ea-detail-hero h1{font-size:30px;font-weight:800;line-height:1.2;letter-spacing:-.02em;margin:11px 0}.ea-detail-hero p{font-size:12px;color:rgba(255,255,255,.81);line-height:1.8}
        .ea-detail-btn{display:inline-flex;justify-content:center;align-items:center;border:1px solid #dce2ef;border-radius:10px;padding:11px 15px;font-size:12px;font-weight:800;text-decoration:none;white-space:nowrap;background:#fff;color:#101064;min-height:42px}.ea-detail-btn:hover{background:#f3f5fd}.ea-detail-btn.primary{color:white;background:#101064;border-color:#101064}.ea-detail-btn.gold{background:#d4a017;border-color:#d4a017;color:#101064}
        .ea-detail-actions{display:flex;gap:10px;flex-wrap:wrap;margin:20px 0;align-items:center}.ea-detail-actions p{font-size:11px;color:#77849a;flex:1 1 220px}.ea-detail-metrics{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:18px}.ea-detail-metric{background:#fff;border:1px solid #e4e9f2;border-radius:16px;padding:19px;box-shadow:0 2px 5px rgba(16,16,100,.03)}.ea-detail-metric span{font-size:10px;letter-spacing:.08em;font-weight:800;text-transform:uppercase;color:#8290a7}.ea-detail-metric strong{display:block;font-weight:800;font-size:27px;margin:10px 0 5px}.ea-detail-metric small{color:#7a869b;font-size:11px}
        .ea-detail-note{border-radius:13px;padding:15px 17px;color:#5f6d88;background:#f7f9fe;border:1px solid #e1e7f2;font-size:12px;line-height:1.75;margin-bottom:21px}
        .ea-detail-title small{color:#b98100;letter-spacing:.2em;text-transform:uppercase;font-size:10px;font-weight:800}.ea-detail-title h2{font-size:19px;font-weight:800;margin:5px 0 6px}.ea-detail-title p{font-size:12px;color:#77839a;margin-bottom:15px}
        .ea-question{background:#fff;border:1px solid #e1e7f1;border-radius:15px;margin-bottom:11px;overflow:hidden;box-shadow:0 2px 6px rgba(16,16,100,.03)}.ea-question summary{list-style:none;cursor:pointer;display:flex;align-items:center;gap:13px;padding:19px}.ea-question summary::-webkit-details-marker{display:none}.ea-question summary:hover{background:#fdfdff}.ea-question-num{flex:0 0 37px;width:37px;height:37px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:#eef0fd;color:#101064;font-weight:800;font-size:13px}.ea-question-head{flex:1;min-width:0}.ea-question-head strong{display:block;font-size:14px;line-height:1.55}.ea-question-head small{display:block;color:#8290a5;margin-top:4px;font-size:11px}.ea-chevron{display:flex;align-items:center;justify-content:center;border-radius:50%;width:27px;height:27px;color:#101064;background:#f1f3fa}.ea-question[open] .ea-chevron{transform:rotate(180deg)}.ea-answer-body{border-top:1px solid #edf0f6;padding:21px 23px}.ea-answer-row{margin:0 0 17px}.ea-answer-row:last-child{margin-bottom:0}.ea-answer-label{display:flex;justify-content:space-between;gap:10px;color:#35435f;font-size:12px;margin-bottom:7px}.ea-answer-label b{white-space:nowrap;color:#101064}.ea-bar{height:9px;background:#edf0f8;border-radius:20px;overflow:hidden}.ea-bar-fill{height:100%;background:#d4a017;border-radius:20px}.ea-anon-comment{border:1px solid #e8edf5;background:#f9fafc;border-radius:10px;padding:13px 15px;margin-bottom:9px;font-size:12px;color:#4a5367;line-height:1.7;white-space:pre-wrap;overflow-wrap:anywhere}
        @media(max-width:900px){.ea-detail-metrics{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:600px){.ea-detail-inner{padding:28px 21px}.ea-detail-hero h1{font-size:24px}.ea-detail-circle{transform:translateX(160px)}.ea-detail-metric{padding:14px}.ea-detail-metric strong{font-size:22px}.ea-question summary{padding:15px}.ea-answer-body{padding:16px}}
    </style>
    <div class="ea-detail">
        <section class="ea-detail-hero">
            <div aria-hidden="true"><span class="ea-detail-circle"></span><span class="ea-detail-circle"></span><span class="ea-detail-circle"></span></div>
            <div class="ea-detail-inner">
                <div>
                    <a class="ea-detail-back" href="{{ route('staff.evaluation-analysis.index') }}">← Back to Evaluation Reports</a>
                    <div class="ea-detail-kicker">Evaluation Insights · Questionnaire Summary</div>
                    <h1>{{ $form->title }}</h1>
                    <p>{{ $form->event?->event_name }} · {{ $form->event?->event_date?->format('M d, Y') ?? 'Date unavailable' }} · {{ $form->status }}</p>
                </div>
            </div>
        </section>
        <div class="ea-detail-actions">
            <p>CSV contains a clean question-by-question table; use PDF for the branded, printable report. All exports are department-restricted and exclude student identities.</p>
            <a class="ea-detail-btn primary" href="{{ route('staff.evaluation-analysis.show', ['evaluationForm' => $form->evaluation_form_id, 'export' => 'csv']) }}">Download Analysis CSV</a>
            <a class="ea-detail-btn" href="{{ route('staff.evaluation-analysis.show', ['evaluationForm' => $form->evaluation_form_id, 'print' => 1]) }}" target="_blank" rel="noopener noreferrer">Print / Save PDF</a>
        </div>
        <section class="ea-detail-metrics" aria-label="Evaluation results">
            <div class="ea-detail-metric"><span>Submitted Responses</span><strong>{{ $submissionCount }}</strong><small>Completed questionnaires</small></div>
            <div class="ea-detail-metric"><span>Eligible Students</span><strong>{{ $eligibleCount }}</strong><small>Eligible roster size</small></div>
            <div class="ea-detail-metric"><span>Response Rate</span><strong>{{ $responseRate === null ? 'N/A' : $responseRate . '%' }}</strong><small>Responses ÷ eligible students</small></div>
            <div class="ea-detail-metric"><span>Average Rating</span><strong>{{ $overallRating === null ? 'N/A' : $overallRating }}</strong><small>Across answered rating items</small></div>
        </section>
        <div class="ea-detail-note"><strong>How the analysis works:</strong> Unanswered items are excluded from question percentages. For multiple-choice answers, percentages can total more than 100%. Response rate is currently calculated against the eligible student roster, not total attendance.</div>
        <div class="ea-detail-title"><small>Question-by-Question</small><h2>Response Breakdown</h2><p>Click a question to view its distribution or anonymous written feedback.</p></div>
        @forelse ($results as $index => $result)
            <details class="ea-question" @if ($index === 0) open @endif>
                <summary>
                    <span class="ea-question-num">{{ $index + 1 }}</span>
                    <span class="ea-question-head"><strong>{{ $result['question']->question_text }}</strong><small>{{ $result['question']->question_type }} · {{ $result['answered'] }} of {{ $submissionCount }} submission(s) answered @if($result['average'] !== null) · Avg {{ $result['average'] }} @endif</small></span>
                    <span class="ea-chevron" aria-hidden="true">⌄</span>
                </summary>
                <div class="ea-answer-body">
                    @if (count($result['distribution']))
                        @foreach ($result['distribution'] as $choice)
                            <div class="ea-answer-row"><div class="ea-answer-label"><span>{{ $choice['label'] }}</span><b>{{ $choice['count'] }} ({{ $choice['percent'] }}%)</b></div><div class="ea-bar"><div class="ea-bar-fill" style="width: {{ min(100, $choice['percent']) }}%"></div></div></div>
                        @endforeach
                    @elseif (in_array($result['question']->question_type, ['Short Answer', 'Paragraph']))
                        @forelse ($result['comments'] as $comment)
                            <div class="ea-anon-comment">{{ $comment }}</div>
                        @empty
                            <p style="font-size:12px;color:#8a94a7">No written responses yet.</p>
                        @endforelse
                        @if($result['answered'] > count($result['comments']))
                            <p style="font-size:11px;color:#8a94a7">Showing the first {{ count($result['comments']) }} anonymous comments.</p>
                        @endif
                    @else
                        <p style="font-size:12px;color:#8a94a7">No response options or answers have been recorded.</p>
                    @endif
                </div>
            </details>
        @empty
            <div class="ea-detail-note">This evaluation form has no questions.</div>
        @endforelse
    </div>
</x-admin-layout>
